<?php
/**
* This is the autoresponder job system object. This handles everything for autoresponder sending.
*
* @version     $Id: jobs_autoresponders.php,v 1.44 2007/05/30 05:26:39 chris Exp $

*
* @package API
* @subpackage Jobs
*/

/**
* Require the base job class.
*/
require_once(dirname(__FILE__) . '/jobs.php');

/**
* This class handles scheduled sending job processing.
*
* @package API
* @subpackage Jobs
*/
class Jobs_Autoresponders_API extends Jobs_API
{

	/**
	* Whether debug mode is on or not.
	*
	* @var Boolean
	*/
	var $Debug = false;

	/**
	* Where to log messages if debug is enabled.
	*
	* @var String
	*/
	var $LogFile = null;

	/**
	* The job status for the job we're running.
	*
	* @see FetchJob
	* @see ProcessJob
	* @see ActionJob
	*
	* @var Array
	*/
	var $jobstatus = null;

	/**
	* The job details for the job we're running. This is used to save database queries.
	*
	* @see FetchJob
	* @see ProcessJob
	* @see ActionJob
	*
	* @var Array
	*/
	var $jobdetails = array();

	/**
	* A reference to the autoresponder api. Saves us having to re-create it all the time.
	*
	* @see Jobs_Send_API
	* @see ProcessJob
	* @see ActionJob
	*
	* @var Object
	*/
	var $autoresponder_api = null;

	/**
	* Used to remember the autoresponder we're sending. Saves constantly loading it from the database.
	*
	* @see ActionJob
	*
	* @var Array
	*/
	var $autoresponder = array();

	/**
	* A count of the emails we have sent (used for reporting).
	*
	* @var Int
	*/
	var $emailssent = 0;

	/**
	* Which list we're currently processing.
	*
	* @var Int
	*/
	var $listid = 0;

	/**
	* Current time (used to work out how long someone has been subscribed for).
	*
	* @var Int
	*/
	var $currenttime = 0;

	/**
	* The pause between sending each newsletter if applicable.
	*
	* @var Int
	*/
	var $userpause = null;

	/**
	* Constructor
	* Calls the parent object to set up the database.
	* Sets up references to the email api, list api and subscriber api.
	*
	* @see Email_API
	* @see Lists_API
	* @see Subscriber_API
	* @see newsletter_api
	*
	* @see Jobs_API::Jobs_API
	*/
	function Jobs_Autoresponders_API()
	{
		$this->LogFile = TEMP_DIRECTORY . '/autoresponder_debug.log';

		if ($this->Debug) {
			error_log(__FILE__ . "\t" . __LINE__ . "\t" . "---------------------\n", 3, $this->LogFile);
		}

		$this->currenttime = $this->GetServerTime();

		Jobs_API::Jobs_API(false);
		require(SENDSTUDIO_LANGUAGE_DIRECTORY . '/jobs_autoresponders.php');

		if ($this->Debug) {
			$this->Db->QueryLog = TEMP_DIRECTORY . '/autoresponder_queries.log';
			$this->Db->LogQuery('-----------');
		}

		if (is_null($this->Email_API)) {
			if (!class_exists('ss_email_api')) {
				require(dirname(__FILE__) . '/ss_email.php');
			}
			$email = &new SS_Email_API();
			$this->Email_API = &$email;
		}

		if (is_null($this->Subscriber_API)) {
			if (!class_exists('subscribers_api')) {
				require(dirname(__FILE__) . '/subscribers.php');
			}
			$subscribers = &new Subscribers_API();
			$this->Subscriber_API = &$subscribers;
		}

		if (is_null($this->Lists_API)) {
			if (!class_exists('lists_api')) {
				require(dirname(__FILE__) . '/lists.php');
			}
			$lists = &new Lists_API();
			$this->Lists_API = &$lists;
		}

		if (is_null($this->autoresponder_api)) {
			require(dirname(__FILE__) . '/autoresponders.php');
			$newsl = &new Autoresponders_API();
			$this->autoresponder_api = &$newsl;
		}

		if (is_null($this->Stats_API)) {
			if (!class_exists('stats_api')) {
				require(dirname(__FILE__) . '/stats.php');
			}
			$statsapi = &new Stats_API();
			$this->Stats_API = &$statsapi;
		}
	}

	/**
	* FetchJob
	* Fetches the next autoresponder job from the queue that hasn't been looked at yet.
	*
	* @see ProcessJob
	*
	* @return False|Int If there is no next queue, this returns false. If there is a next queue, it returns the queueid for handing to the ProcessJob function
	*/
	function FetchJob()
	{

		if ($this->Debug) {
			$this->Db->LogQuery('###################');
			error_log(__FILE__ . "\t" . __LINE__ . "\t" . "###################\n", 3, $this->LogFile);
		}

		$timenow = $this->GetServerTime();
		$half_hour_ago = $timenow - (30 * 60);

		$queues_done = array('0');

		// get the queues we CAN'T process.
		$query = "SELECT queueid FROM " . SENDSTUDIO_TABLEPREFIX . "jobs WHERE jobtype='autoresponder' AND lastupdatetime > " . $half_hour_ago;
		$already_running_result = $this->Db->Query($query);
		while ($row = $this->Db->Fetch($already_running_result)) {
			$queues_done[] = $row['queueid'];
		}

		$query = "SELECT q.queueid AS queueid, q.ownerid, a.autoresponderid, s.statid, s.hiddenby FROM " . SENDSTUDIO_TABLEPREFIX . "queues q, " . SENDSTUDIO_TABLEPREFIX . "autoresponders a LEFT OUTER JOIN " . SENDSTUDIO_TABLEPREFIX . "stats_autoresponders s ON s.autoresponderid=a.autoresponderid WHERE a.queueid=q.queueid AND q.queuetype='autoresponder' AND q.queueid NOT IN (" . implode(',', $queues_done) . ") GROUP BY q.queueid, q.ownerid, a.autoresponderid, s.statid, s.hiddenby ORDER BY s.hiddenby ASC LIMIT 1";

		$result = $this->Db->Query($query);
		if (!$result) {
			return false;
		}

		$row = $this->Db->Fetch($result);
		if (empty($row)) {
			if (sizeof($queues_done) != 1) {
				$query = "DELETE FROM " . SENDSTUDIO_TABLEPREFIX . "jobs WHERE jobtype='autoresponder' AND queueid IN (" . implode(',', $queues_done) . ")";
				$cleanup_result = $this->Db->Query($query);
			}
			return false;
		}

		$this->autoresponder_api->Load($row['autoresponderid']);

		if ($this->autoresponder_api->Get('ownerid') <= 0) {
			if (sizeof($queues_done) != 1) {
				$query = "DELETE FROM " . SENDSTUDIO_TABLEPREFIX . "jobs WHERE jobtype='autoresponder' AND queueid IN (" . implode(',', $queues_done) . ")";
				$cleanup_result = $this->Db->Query($query);
			}

			return false;
		}

		$listid = $this->autoresponder_api->Get('listid');

		if (!$listid) {
			return false;
		}

		$job_details = array(
			'Lists' => array($listid),
			'autorespondername' => $this->autoresponder_api->Get('name')
		);

		$jobcreated = $this->Create(
			'autoresponder',
			$timenow,
			$this->autoresponder_api->Get('ownerid'),
			$job_details,
			'autoresponder',
			$this->autoresponder_api->Get('autoresponderid'),
			$job_details['Lists'],
			$this->autoresponder_api->Get('ownerid')
		);

		$this->StartJob($jobcreated);

		$this->JobQueue($jobcreated, $row['queueid']);

		if (!$row['statid'] || $row['hiddenby'] > 0) {
			$autoresponderdetails = array(
				'autoresponderid' => $row['autoresponderid']
			);
			$statid = $this->Stats_API->SaveAutoresponderStats($autoresponderdetails);
		} else {
			$statid = $row['statid'];
		}
		$this->statid = $statid;

		$this->jobowner = $row['ownerid'];
		return $row['queueid'];
	}

	/**
	* ProcessJob
	* Processes an autoresponder queue
	* Checks a queue for duplicates, makes sure the queue is present and has recipients in it and then calls ActionJob to handle the rest
	*
	* @param Int $queueid Autoresponder queue to process. This is passed to ActionJob
	*
	* @see GetUser
	* @see GetSession
	* @see RemoveDuplicatesInQueue
	* @see QueueSize
	* @see ActionJob
	* @see UnprocessQueue
	*
	* @return True Always returns true
	*/
	function ProcessJob($queueid=0)
	{
		$queueid = (int)$queueid;

		$this->user = &GetUser($this->jobowner);

		$session = &GetSession();
		$session->Set('UserDetails', $this->user);

		$queuesize = $this->QueueSize($queueid, 'autoresponder');

		if ($this->Debug) {
			error_log(__FILE__ . "\t" . __LINE__ . "\t" . "queuesize: " . $queuesize . " for queueid " . $queueid . "\n", 3, $this->LogFile);
		}

		$jobid_query = "SELECT jobid FROM " . SENDSTUDIO_TABLEPREFIX . "jobs WHERE queueid='" . $queueid . "'";
		$jobid_result = $this->Db->Query($jobid_query);
		$jobid = $this->Db->FetchOne($jobid_result, 'jobid');

		if (!$jobid) {
			if ($this->Debug) {
				error_log(__FILE__ . "\t" . __LINE__ . "\t" . "no jobid (result " . gettype($jobid_result) . "; " . $jobid_result . ")" . "\n", 3, $this->LogFile);
				error_log(__FILE__ . "\t" . __LINE__ . "\t" . "Returning" . "\n", 3, $this->LogFile);
			}
			return true;
		}

		$timenow = $this->GetServerTime();
		$query = "UPDATE " . SENDSTUDIO_TABLEPREFIX . "jobs SET lastupdatetime=" . $timenow . " WHERE jobid='" . $jobid . "'";
		$update_job_result = $this->Db->Query($query);

		if ($queuesize <= 0) {
			if ($this->Debug) {
				error_log(__FILE__ . "\t" . __LINE__ . "\t" . "Returning" . "\n", 3, $this->LogFile);
			}
			return true;
		}

		if ($this->Debug) {
			error_log(__FILE__ . "\t" . __LINE__ . "\t" . "Actioning jobid " . $jobid . "\n", 3, $this->LogFile);
		}

		$finished = $this->ActionJob($queueid, $jobid);

		if ($this->Debug) {
			error_log(__FILE__ . "\t" . __LINE__ . "\t" . "Finished: " . $finished . "\n", 3, $this->LogFile);
		}

		// we need to turn 'processed' emails back to normal so we can check them next time.
		$this->UnprocessQueue($queueid);
		return true;
	}

	/**
	* ActionJob
	* This actually processes the autoresponder job and sends it out.
	* It makes sure the autoresponder queue is present (if not, returns false)
	* It makes sure the autoresponder exists and is active (if not, returns false)
	* It makes sure the autoresponder has some content (if not, returns false)
	* Once that is done, it removes any newly banned subscribers
	* Then removes any newly unsubscribe recipients
	* It makes sure the recipient is valid, is on the list and matches the criteria set by the autoresponder
	* Then it gets to work, constructing the email to get sent to the final recipient
	* Once all recipients for this queue have been looked at, it will "UnProcess" the queue to make everyone active again so next time the job runs, it can start all over again.
	* The only recipients that are treated this way are the ones who are before the autoresponder's "hours after subscription" timeframe.
	*
	* @param Int $queueid The queue to process.
	*
	* @see IsQueue
	* @see Autoresponders_API::Load
	* @see Autoresponders_API::Active
	* @see SendStudio_Functions::GetAttachments
	* @see Lists_API::Load
	* @see RemoveBannedEmails
	* @see RemoveUnsubscribedEmails
	* @see Email_API
	* @see FetchFromQueue
	* @see Subscribers_API::LoadSubscriberList
	* @see RemoveFromQueue
	* @see MarkAsProcessed
	* @see MatchCriteria
	*
	* @return Boolean Returns false if the queue can't be processed for any reason, otherwise it gets processed and returns true.
	*/
	function ActionJob($queueid=0, $jobid=0)
	{
		$queueid = (int)$queueid;
		if (!$this->IsQueue($queueid, 'autoresponder')) {
			if ($this->Debug) {
				error_log(__FILE__ . "\t" . __LINE__ . "\t" . "queueid (" . $queueid . ") is not valid" . "\n", 3, $this->LogFile);
				error_log(__FILE__ . "\t" . __LINE__ . "\t" . "Returning" . "\n", 3, $this->LogFile);
			}
			return false;
		}

		if ($this->Debug) {
			error_log(__FILE__ . "\t" . __LINE__ . "\t" . "found queueid (" . $queueid . ")" . "\n", 3, $this->LogFile);
		}

		$query = "SELECT autoresponderid FROM " . SENDSTUDIO_TABLEPREFIX . "autoresponders WHERE queueid='" . $queueid . "'";
		$result = $this->Db->Query($query);
		if (!$result) {
			if ($this->Debug) {
				error_log(__FILE__ . "\t" . __LINE__ . "\t" . "unable to find autoresponder for queue" . "\n", 3, $this->LogFile);
				error_log(__FILE__ . "\t" . __LINE__ . "\t" . "Returning" . "\n", 3, $this->LogFile);
			}
			return false;
		}

		if ($this->Debug) {
			error_log(__FILE__ . "\t" . __LINE__ . "\t" . "got autoresponder result" . "\n", 3, $this->LogFile);
		}

		$row = $this->Db->Fetch($result);
		if (empty($row)) {
			if ($this->Debug) {
				error_log(__FILE__ . "\t" . __LINE__ . "\t" . "unable to find autoresponder" . "\n", 3, $this->LogFile);
				error_log(__FILE__ . "\t" . __LINE__ . "\t" . "Returning" . "\n", 3, $this->LogFile);
			}
			return false;
		}

		if ($this->Debug) {
			error_log(__FILE__ . "\t" . __LINE__ . "\t" . "found autoresponder (" . $row['autoresponderid'] . ")" . "\n", 3, $this->LogFile);
		}

		$this->autoresponder_api->Load($row['autoresponderid']);
		if ($this->autoresponder_api->autoresponderid <= 0) {
			if ($this->Debug) {
				error_log(__FILE__ . "\t" . __LINE__ . "\t" . "unable to find autoresponder" . "\n", 3, $this->LogFile);
				error_log(__FILE__ . "\t" . __LINE__ . "\t" . "Returning" . "\n", 3, $this->LogFile);
			}
			return false;
		}

		if ($this->Debug) {
			error_log(__FILE__ . "\t" . __LINE__ . "\t" . "loaded autoresponder (" . $row['autoresponderid'] . ")" . "\n", 3, $this->LogFile);
		}

		// if the autoresponder isn't active, don't do anything.
		if (!$this->autoresponder_api->Active()) {
			if ($this->Debug) {
				error_log(__FILE__ . "\t" . __LINE__ . "\t" . "autoresponder not active" . "\n", 3, $this->LogFile);
				error_log(__FILE__ . "\t" . __LINE__ . "\t" . "Returning" . "\n", 3, $this->LogFile);
			}
			return false;
		}

		if ($this->Debug) {
			error_log(__FILE__ . "\t" . __LINE__ . "\t" . "autoresponder is active" . "\n", 3, $this->LogFile);
		}

		// if the autoresponder is empty, don't do anything.
		if (empty($this->autoresponder_api->textbody) && empty($this->autoresponder_api->htmlbody)) {
			if ($this->Debug) {
				error_log(__FILE__ . "\t" . __LINE__ . "\t" . "autoresponder bodies are empty" . "\n", 3, $this->LogFile);
				error_log(__FILE__ . "\t" . __LINE__ . "\t" . "Returning" . "\n", 3, $this->LogFile);
			}
			return false;
		}

		if ($this->Debug) {
			error_log(__FILE__ . "\t" . __LINE__ . "\t" . "autoresponder has text &/or html body" . "\n", 3, $this->LogFile);
		}

		$this->autoresponder['Attachments'] = SendStudio_Functions::GetAttachments('autoresponders', $this->autoresponder_api->Get('autoresponderid'), true);

		$this->listid = $this->autoresponder_api->Get('listid');

		$this->Lists_API->Load($this->listid);
		$listname = $this->Lists_API->Get('name');

		$search_criteria = $this->autoresponder_api->Get('searchcriteria');

		if ($this->Debug) {
			error_log(__FILE__ . "\t" . __LINE__ . "\t" . "search_criteria: " . array_contents($search_criteria) . "\n", 3, $this->LogFile);
		}

		// double check there are no duplicates in the autoresponder queue.
		$this->RemoveDuplicatesInQueue($queueid, 'autoresponder');

		// remove any that have been newly banned.
		$this->RemoveBannedEmails($this->listid, $queueid, 'autoresponder');

		// remove any that have unsubscribed.
		$this->RemoveUnsubscribedEmails($this->listid, $queueid, 'autoresponder');

		$this->Email_API->ForgetEmail();

		$this->Email_API->Set('statid', $this->statid);
		$this->Email_API->Set('listids', array($this->listid));

		$this->Email_API->SetSmtp(SENDSTUDIO_SMTP_SERVER, SENDSTUDIO_SMTP_USERNAME, @base64_decode(SENDSTUDIO_SMTP_PASSWORD), SENDSTUDIO_SMTP_PORT);

		if ($this->user->smtpserver) {
			$this->Email_API->SetSmtp($this->user->smtpserver, $this->user->smtpusername, $this->user->smtppassword, $this->user->smtpport);
		}

		if (is_null($this->userpause)) {
			$pause = $pausetime = 0;
			if ($this->user->perhour > 0) {
				$pause = $this->user->perhour;
			}

			// in case the system rate is less than the user rate, lower it.
			if (SENDSTUDIO_MAXHOURLYRATE > 0) {
				if ($pause == 0) {
					$pause = SENDSTUDIO_MAXHOURLYRATE;
				} else {
					$pause = min($pause, SENDSTUDIO_MAXHOURLYRATE);
				}
			}

			if ($pause > 0) {
				$pausetime = 3600 / $pause;
			}
			$this->userpause = $pausetime;
			if ($this->Debug) {
				error_log(__FILE__ . "\t" . __LINE__ . "\t" . "userpause is set to " . $this->userpause . "\n", 3, $this->LogFile);
			}
		}

		if ($this->autoresponder_api->Get('tracklinks')) {
			$this->Email_API->TrackLinks(true);
		}

		if ($this->autoresponder_api->Get('trackopens')) {
			$this->Email_API->TrackOpens(true);
		}

		if (SENDSTUDIO_FORCE_UNSUBLINK) {
			$this->Email_API->ForceLinkChecks(true);
		}

		$this->Email_API->Set('CharSet', $this->autoresponder_api->Get('charset'));

		if (!SENDSTUDIO_SAFE_MODE) {
			$this->Email_API->Set('imagedir', TEMP_DIRECTORY . '/autoresponder.' . $queueid);
		} else {
			$this->Email_API->Set('imagedir', TEMP_DIRECTORY . '/autoresponder');
		}

		// clear out the attachments just to be safe.
		$this->Email_API->ClearAttachments();

		if ($this->autoresponder['Attachments']) {
			$path = $this->autoresponder['Attachments']['path'];
			$files = $this->autoresponder['Attachments']['filelist'];
			foreach ($files as $p => $file) {
				$this->Email_API->AddAttachment($path . '/' . $file);
			}
		}

		$this->Email_API->Set('Subject', $this->autoresponder_api->Get('subject'));

		$this->Email_API->Set('FromName', $this->autoresponder_api->Get('sendfromname'));
		$this->Email_API->Set('FromAddress', $this->autoresponder_api->Get('sendfromemail'));
		$this->Email_API->Set('ReplyTo', $this->autoresponder_api->Get('replytoemail'));
		$this->Email_API->Set('BounceAddress', $this->autoresponder_api->Get('bounceemail'));

		$this->Email_API->Set('Multipart', $this->autoresponder_api->Get('multipart'));
		$this->Email_API->Set('EmbedImages', $this->autoresponder_api->Get('embedimages'));

		if ($this->autoresponder_api->GetBody('text')) {
			$this->Email_API->AddBody('text', $this->autoresponder_api->GetBody('text'));
			$this->Email_API->AppendBody('text', $this->user->Get('textfooter'));
			$this->Email_API->AppendBody('text', stripslashes(SENDSTUDIO_TEXTFOOTER));
		}

		if ($this->autoresponder_api->GetBody('html')) {
			$this->Email_API->AddBody('html', $this->autoresponder_api->GetBody('html'));
			$this->Email_API->AppendBody('html', $this->user->Get('htmlfooter'));
			$this->Email_API->AppendBody('html', stripslashes(SENDSTUDIO_HTMLFOOTER));
		}

		if ($this->autoresponder_api->Get('multipart')) {
			if ($this->autoresponder_api->GetBody('text') && $this->autoresponder_api->GetBody('html')) {
				$sent_format = 'm';
			} else {
				$this->Email_API->Set('Multipart', false);
			}
		}

		$custom_fields_to_replace = $this->Email_API->GetCustomFields();

		$personalize_customfields = array();

		$firstname_field = $this->autoresponder_api->Get('to_firstname');
		if ($firstname_field) {
			$personalize_customfields[] = $firstname_field;
		}

		$lastname_field = $this->autoresponder_api->Get('to_lastname');
		if ($lastname_field) {
			$personalize_customfields[] = $lastname_field;
		}

		$personalize_customfields = array_unique($personalize_customfields);

		$emails_sent = 0;

		while ($recipients = $this->FetchFromQueue($queueid, 'autoresponder', 1, 1000)) {
			if (empty($recipients)) {
				if ($this->Debug) {
					error_log(__FILE__ . "\t" . __LINE__ . "\t" . "no more recipients" . "\n", 3, $this->LogFile);
					error_log(__FILE__ . "\t" . __LINE__ . "\t" . "Returning" . "\n", 3, $this->LogFile);
				}
				return true;
			}

			if ($this->Debug) {
				error_log(__FILE__ . "\t" . __LINE__ . "\t" . "found " . sizeof($recipients) . " recipients" . "\n", 3, $this->LogFile);
			}

			$emails_sent++;

			if ($emails_sent % 10 == 0) {
				$timenow = $this->GetServerTime();
				$query = "UPDATE " . SENDSTUDIO_TABLEPREFIX . "jobs SET lastupdatetime=" . $timenow . " WHERE jobid='" . $jobid . "'";
				$update_job_result = $this->Db->Query($query);
				$emails_sent = 0;
			}

			$all_customfields = $this->Subscriber_API->GetAllSubscriberCustomFields($this->listid, $custom_fields_to_replace, $recipients, $personalize_customfields);

			foreach ($recipients as $p => $recipientid) {
				$subscriberinfo = $this->Subscriber_API->LoadSubscriberList($recipientid, $this->listid, true, false, false);

				if ($this->Debug) {
					error_log(__FILE__ . "\t" . __LINE__ . "\t" . "recipientid: " . $recipientid . "; subscriberinfo: " . array_contents($subscriberinfo) . "\n", 3, $this->LogFile);
				}

				// if they don't match the search criteria, remember it for later and don't sent it.
				if (empty($subscriberinfo) || !isset($subscriberinfo['subscribedate'])) {
					if ($this->Debug) {
						error_log(__FILE__ . "\t" . __LINE__ . "\t" . "recipientid: " . $recipientid . "; subscriber info is empty or date is not set" . "\n", 3, $this->LogFile);
					}
					$this->MarkAsSent($queueid, $recipientid);
					continue;
				}

				if ($this->Debug) {
					error_log(__FILE__ . "\t" . __LINE__ . "\t" . "recipientid: " . $recipientid . " has email address " . $subscriberinfo['emailaddress'] . "\n", 3, $this->LogFile);
				}

				// work out how long they have been subscribed for.
				$hours_subscribed = floor(($this->currenttime - $subscriberinfo['subscribedate']) / 3600);

				if ($this->Debug) {
					error_log(__FILE__ . "\t" . __LINE__ . "\t" . "recipientid: " . $recipientid . " subscribed for " . $hours_subscribed . "\n", 3, $this->LogFile);
				}

				// not long enough? Go to the next one.
				if ($hours_subscribed < $this->autoresponder_api->Get('hoursaftersubscription')) {
					if ($this->Debug) {
						error_log(__FILE__ . "\t" . __LINE__ . "\t" . "recipientid: " . $recipientid . "; not time to send the autoresponder yet (subscribed for " . $hours_subscribed . "; hours set to: " . $this->autoresponder_api->Get('hoursaftersubscription') . ")" . "\n", 3, $this->LogFile);
					}
					$this->MarkAsProcessed($queueid, 'autoresponder', $recipientid);
					continue;
				}

				if ($this->Debug) {
					error_log(__FILE__ . "\t" . __LINE__ . "\t" . "recipientid: " . $recipientid . " has been subscribed for long enough" . "\n", 3, $this->LogFile);
				}

				// if they don't match the search criteria, remember it for later and don't send it.
				if (!$this->MatchCriteria($search_criteria, $recipientid)) {
					if ($this->Debug) {
						error_log(__FILE__ . "\t" . __LINE__ . "\t" . "recipientid: " . $recipientid . "; dont meet search criteria (" . array_contents($search_criteria) . ")" . "\n", 3, $this->LogFile);
					}
					$this->MarkAsSent($queueid, $recipientid);
					continue;
				}

				if ($this->Debug) {
					error_log(__FILE__ . "\t" . __LINE__ . "\t" . "does meet search criteria" . "\n", 3, $this->LogFile);
				}

				$this->Email_API->ClearRecipients();

				$subscriberinfo['CustomFields'] = array();

				if (!empty($all_customfields)) {
					if (isset($all_customfields[$recipientid])) {
						$subscriberinfo['CustomFields'] = $all_customfields[$recipientid];
					}
				} else {
					/**
					* If the subscriber has no custom fields coming from the database, then set up blank placeholders.
					* If they have no custom fields in the database, they have no records in the 'all_customfields' array - so we need to fill it up with blank entries.
					*/
					foreach ($custom_fields_to_replace as $fieldid => $fieldname) {
						$subscriberinfo['CustomFields'][] = array(
							'fieldid' => $fieldid,
							'fieldname' => $fieldname,
							'fieldtype' => 'text',
							'defaultvalue' => '',
							'fieldsettings' => '',
							'subscriberid' => $recipientid,
							'data' => ''
						);
					}
				}

				$name = false;

				$firstname_field = $this->autoresponder_api->Get('to_firstname');
				if ($firstname_field) {
					foreach ($subscriberinfo['CustomFields'] as $p => $details) {
						if ($details['fieldid'] == $firstname_field && $details['data'] != '') {
							$name = $details['data'];
							break;
						}
					}
				}

				$lastname_field = $this->autoresponder_api->Get('to_lastname');
				if ($lastname_field) {
					foreach ($subscriberinfo['CustomFields'] as $p => $details) {
						if ($details['fieldid'] == $lastname_field && $details['data'] != '') {
							$name .= ' ' . $details['data'];
							break;
						}
					}
				}

				$this->Email_API->AddRecipient($subscriberinfo['emailaddress'], $name, $subscriberinfo['format'], $subscriberinfo['subscriberid']);

				$subscriberinfo['listid'] = $this->listid;
				$subscriberinfo['listname'] = $listname;
				$subscriberinfo['autoresponder'] = $this->autoresponder_api->Get('autoresponderid');
				$subscriberinfo['statid'] = $this->statid;

				$this->Email_API->AddCustomFieldInfo($subscriberinfo['emailaddress'], $subscriberinfo);

				$mail_result = $this->Email_API->Send(true);

				if ($this->Debug) {
					error_log(__FILE__ . "\t" . __LINE__ . "\t" . "recipientid: " . $recipientid . "; mail result: " . array_contents($mail_result) . "\n", 3, $this->LogFile);
				}

				if (!$this->Email_API->Get('Multipart')) {
					$sent_format = $subscriberinfo['format'];
				}

				if ($mail_result['success'] > 0) {
					$this->Stats_API->UpdateRecipient($this->statid, $sent_format, 'a');
				}

				$this->emailssent++;

				$this->MarkAsSent($queueid, $recipientid);

				// do we need to pause between each email?
				if ($this->userpause > 0) {
					if ($this->userpause > 0 && $this->userpause < 1) {
						$p = ceil($this->userpause*1000000);
						usleep($p);
					} else {
						$p = ceil($this->userpause);
						sleep($p);
					}
				} // end if we need to pause.
			} // end foreach recipient
		} // end while loop (to go through each subscriber in the queue).

		$this->Email_API->CleanupImages();

		// reset the 'pause' counter.
		$this->userpause = null;
		return true;
	}

	/**
	* MatchCriteria
	* This will make sure the recipient passed in matches the search criteria information which could possibly include custom fields.
	* It creates an array to pass off to the Subscribers_API::GetSubscribers method
	*
	* @param Array $search_criteria Search criteria of the autoresonder. This is reconstructed for passing to the GetSubscribers method in Subscribers_API
	* @param Int $recipient The specific recipient we're checking
	*
	* @see Subscribers_API::GetSubscribers
	*
	* @return Boolean Returns true if the recipient matches the criteria passed in, else false.
	*/
	function MatchCriteria($search_criteria=array(), $recipient=0)
	{
		$searchinfo = array('List' => $this->listid);

		if (isset($search_criteria['customfields'])) {
			$searchinfo['CustomFields'] = $search_criteria['customfields'];
		}

		if (isset($search_criteria['format'])) {
			// only need to include the format IF you are restricting to subscribers.
			// using "either format" uses '-1'
			// sending to 'h'tml subscribers only or 't'ext subscribers only should restrict the matching.
			if ($search_criteria['format'] != '-1') {
				$searchinfo['Format'] = $search_criteria['format'];
			}
		}

		if (isset($search_criteria['email'])) {
			$searchinfo['Email'] = $search_criteria['email'];
		}
		if (isset($search_criteria['status'])) {
			$searchinfo['Status'] = $search_criteria['status'];
		}
		if (isset($search_criteria['confirmed'])) {
			$searchinfo['Confirmed'] = $search_criteria['confirmed'];
		}

		if (isset($search_criteria['link'])) {
			$searchinfo['Link'] = $search_criteria['link'];
		}

		if (isset($search_criteria['newsletter'])) {
			$searchinfo['Newsletter'] = $search_criteria['newsletter'];
		}

		$searchinfo['Subscriber'] = $recipient;

		$check = $this->Subscriber_API->GetSubscribers($searchinfo, array(), true);

		if ($this->Debug) {
			error_log(__FILE__ . "\t" . __LINE__ . "\t" . "checking search critiera (" . array_contents($search_criteria) . ") for recipient (" . $recipient . ") returned " . $check . "\n", 3, $this->LogFile);
		}

		if ($check == 1) {
			return true;
		}

		return false;
	}

	/**
	* UnprocessQueue
	* This marks all recipients left in the queue as unprocessed. This allows the autoresponder to run again next time and reprocess everything.
	*
	* @param Int $queueid The queue to 'unprocess'.
	*
	* @see UnprocessQueue
	*
	* @return Boolean Returns false if there is no queueid passed in or if the unprocess query failed. If it works, this will return true.
	*/
	function UnprocessQueue($queueid=0)
	{
		$queueid = (int)$queueid;
		if ($queueid <= 0) {
			return false;
		}

		$query = "DELETE FROM " . SENDSTUDIO_TABLEPREFIX . "queues WHERE sent='1' AND queueid='" . $queueid . "' AND queuetype='autoresponder' AND processed='1'";
		$result = $this->Db->Query($query);

		$query = "UPDATE " . SENDSTUDIO_TABLEPREFIX . "queues SET processed='0' WHERE queueid='" . $queueid . "' AND queuetype='autoresponder' AND sent='0'";
		$result = $this->Db->Query($query);
		return $result;
	}


	/**
	* MarkAsSent
	* Marks recipients as processed & sent in the queue. An update is usually 'cheaper' in database terms to do than a delete so that's what this does.
	*
	* @param Int $queueid The queueid you're processing recipients for.
	* @param Mixed $recipients A list of recipients to process in the queue. This can be an array or a singular recipient id.
	*
	* @return Boolean Returns true if the query worked, returns false if there was a problem with the query.
	*/
	function MarkAsSent($queueid=0, $recipients=array())
	{
		if (!is_array($recipients)) {
			$recipients = array($recipients);
		}

		$recipients = $this->CheckIntVars($recipients);

		// stops the query from failing.
		if (empty($recipients)) {
			return false;
		}

		$query = "UPDATE " . SENDSTUDIO_TABLEPREFIX . "queues SET processed=1, sent=1 WHERE queueid='" . $this->Db->Quote($queueid) . "' AND queuetype='autoresponder' AND recipient IN (" . implode(',', $recipients) . ")";
		$result = $this->Db->Query($query);
		if ($result) {
			return true;
		}
		return false;
	}

}

?>
