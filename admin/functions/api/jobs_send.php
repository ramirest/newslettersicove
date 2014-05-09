<?php
/**
* This is the send job system object. This handles everything for scheduled sending.
*
* @version     $Id: jobs_send.php,v 1.34 2007/05/30 05:26:18 chris Exp $

*
* @package API
* @subpackage Jobs
*/

/**
* Require the base job class.
*/
require(dirname(__FILE__) . '/send.php');

/**
* This class handles scheduled sending job processing.
*
* @package API
* @subpackage Jobs
*/
class Jobs_Send_API extends Send_API
{

	var $Notify_Email_API = null;

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
	* Constructor
	* Calls the parent object to set up the database.
	* Sets up references to the email api, list api and subscriber api.
	*
	* @see Email_API
	* @see Lists_API
	* @see Subscriber_API
	* @see Newsletter_API
	* @see Jobs_API::Jobs_API
	*/
	function Jobs_Send_API()
	{

		if (is_null($this->Notify_Email_API)) {
			if (!class_exists('ss_email_api')) {
				require(dirname(__FILE__) . '/ss_email.php');
			}
			$notify_email = &new SS_Email_API();
			$this->Notify_Email_API = &$notify_email;
		}

		require(SENDSTUDIO_LANGUAGE_DIRECTORY . '/jobs_send.php');
		$this->Send_API();
		$this->Jobs_API(true);
	}

	/**
	* FetchJob
	* Fetches the next 'send' jobtype from the queue that is 'w'aiting.
	* It also checks for stalled sends, which are ones that haven't had a lastupdatetime update in the last 30 minutes.
	*
	* @return Int|False Returns false if there is no next job. Otherwise returns the jobid to run.
	*/
	function FetchJob()
	{
		$timenow = $this->GetServerTime();
		$half_hour_ago = $timenow - (30 * 60);

		$query = "SELECT * FROM " . SENDSTUDIO_TABLEPREFIX . "jobs WHERE jobtype='send' AND (";

		/**
		* get "waiting" jobs
		*/
		$query .= " (jobstatus ='w' AND jobtime < " . $timenow . ") OR ";

		/**
		* Get jobs that haven't been updated in half an hour.
		* This is in case a job has broken (eg the db went down or server was rebooted mid-send).
		*/
		$query .= " (jobstatus='i' AND jobtime < " . $timenow . " AND lastupdatetime < " . $half_hour_ago . ")";

		/**
		* order the results so we get the one scheduled first to send.
		*/
		$query .= ") AND (approved > 0)";

		$query .= " ORDER BY jobtime ASC LIMIT 1";
		$result = $this->Db->Query($query);
		if (!$result) {
			return false;
		}

		$row = $this->Db->Fetch($result);
		if (empty($row)) {
			return false;
		}

		$query = "SELECT COUNT(*) AS count FROM " . SENDSTUDIO_TABLEPREFIX . "jobs_lists WHERE jobid='" . $row['jobid'] . "'";
		$result = $this->Db->Query($query);
		$count_check = $this->Db->FetchOne($result, 'count');
		if ($count_check <= 0) {
			echo '*** Found an orphaned job: ' . $row['jobid'] . ' *** - No entries in the jobs_lists table. Please contact your administrator.' . "\n";
			$this->PauseJob($row['jobid']);
			return false;
		}

		$this->jobstatus = $row['jobstatus'];
		$this->jobdetails = unserialize($row['jobdetails']);
		$this->jobowner = $row['ownerid'];

		// if the job is in progress, we'll pause it so the send part can pick it up properly.
		if ($row['jobstatus'] == 'i') {
			$this->PauseJob($row['jobid']);
		}
		return $row['jobid'];
	}

	/**
	* ProcessJob
	* Does most of the work setting up the job (creates the queue, removes duplicates and so on) to run. Once the job has been set up ready to run, it 'Actions' the job, then marks it as 'finished'.
	*
	* @param Int $jobid The job to run.
	*
	* @see Email_API
	* @see Subscriber_API
	* @see Lists_API
	* @see newsletter_api
	* @see GetUser
	* @see StartJob
	* @see GetJobQueue
	* @see CreateQueue
	* @see JobQueue
	* @see Subscribers_API::GetSubscribers
	* @see RemoveDuplicatesInQueue
	* @see RemoveBannedEmails
	* @see ActionJob
	* @see FinishJob
	*
	* @return Boolean Returns false if the job can't be started. Otherwise runs the job and returns true.
	*/
	function ProcessJob($jobid=0)
	{
		if (!$this->StartJob($jobid)) {
			$this->PauseJob($jobid);
			$this->jobstatus = 'p';
			return false;
		}

		$user = &GetUser($this->jobowner);
		$session = &GetSession();
		$session->Set('UserDetails', $user);

		$queueid = false;

		// if there's no queue, start one up.
		if (!$queueid = $this->GetJobQueue($jobid)) {
			$sendqueue = $this->CreateQueue('send');
			$queueok = $this->JobQueue($jobid, $sendqueue);
			$send_criteria = $this->jobdetails['SendCriteria'];

			$original_queuesize = $this->jobdetails['SendSize'];

			$queueinfo = array('queueid' => $sendqueue, 'queuetype' => 'send', 'ownerid' => $this->jobowner);

			$this->Subscriber_API->GetSubscribers($send_criteria, array(), false, $queueinfo);

			$this->Subscriber_API->Db->OptimizeTable(SENDSTUDIO_TABLEPREFIX . "queues");

			$this->Subscriber_API->RemoveDuplicatesInQueue($sendqueue, 'send');

			$this->Subscriber_API->RemoveBannedEmails($this->jobdetails['Lists'], $sendqueue, 'send');

			$this->Subscriber_API->RemoveUnsubscribedEmails($this->jobdetails['Lists'], $sendqueue, 'send');

			$this->Subscriber_API->Db->OptimizeTable(SENDSTUDIO_TABLEPREFIX . "queues");

			$queueid = $sendqueue;

			$newsletterstats = $this->jobdetails;
			$newsletterstats['Queue'] = $sendqueue;
			$newsletterstats['SentBy'] = $queueinfo['ownerid'];

			$real_queuesize = $this->Subscriber_API->QueueSize($queueid, 'send');

			$newsletterstats['SendSize'] = $real_queuesize;

			$statid = $this->Stats_API->SaveNewsletterStats($newsletterstats);

			/**
			* So we can link user stats to send stats, we need to update it.
			*/
			$this->Stats_API->UpdateUserStats($queueinfo['ownerid'], $jobid, $statid);

			/**
			* The 'queuesize' in the stats_users table is updated by MarkNewsletterFinished in send.php
			* so we don't need to worry about it while setting up the send.
			* That takes into account whether some recipients were skipped because a html-only email was sent etc.
			*/

			/**
			* We re-check the user stats in case a bunch of subscribers have joined, or the user has done something like:
			* - create a list
			* - added a few subscribers
			* - scheduled a send
			* - added more subscribers
			*/
			$check_stats = $this->Stats_API->ReCheckUserStats($user, $original_queuesize, $real_queuesize, AdjustTime(), $statid);

			list($ok_to_send, $not_ok_to_send_reason) = $check_stats;
			if (!$ok_to_send) {
				$this->PauseJob($jobid);
				$this->UnapproveJob($jobid);
				return false;
			}
		}

		$this->statid = $this->LoadStats($jobid);
		$queuesize = $this->Subscriber_API->QueueSize($queueid, 'send');

		// used by send.php::CleanUp
		$this->queuesize = $this->jobdetails['SendSize'];

		/**
		* There's nothing left? Just mark it as done.
		*/
		if ($queuesize == 0) {
			$this->jobstatus = 'c';
			$this->FinishJob($jobid);
			return true;
		}

		$finished = $this->ActionJob($jobid, $queueid);

		if ($finished) {
			$this->jobstatus = 'c';
			$this->FinishJob($jobid);
		}
		return true;
	}

	/**
	* ActionJob
	* Actions the job passed in. It goes through the queue (also passed in) and sends an email to each of the recipients in the queue. Since the queue has already been checked for duplicates and banned emails, it doesn't need to do any of this.
	*
	* @param Int $jobid The Job to action.
	* @param Int $queueid The queue for the job.
	*
	* @see IsQueue
	* @see Newsletters_API::Load
	* @see Newsletters_API::Get
	* @see SendStudio_Functions::GetAttachments
	* @see Email_API::SetSmtp
	* @see Email_API::AddAttachment
	* @see Email_API::Set
	* @see Email_API::AddBody
	* @see Email_API::AppendBody
	* @see FetchFromQueue
	* @see Email_API::ClearRecipients
	* @see Subscribers_API::LoadSubscriberFromList
	* @see Lists_API::Load
	* @see RemoveFromQueue
	* @see Subscribers_API::GetFormat
	* @see Email_API::AddRecipient
	* @see Email_API::AddCustomFieldInfo
	* @see Email_API::Send
	*
	* @return Boolean Returns true if the job has been actioned successfully. If anything doesn't work (eg newsletter can't be loaded) it returns false.
	*/
	function ActionJob($jobid=0, $queueid=0)
	{
		if (!$this->SetupJob($jobid, $queueid)) {
			return false;
		}

		$this->NotifyOwner();

		$emails_sent = 0;

		$this->Db->Query("BEGIN");

		while ($recipients = $this->FetchFromQueue($queueid, 'send', 1, 500)) {
			if (empty($recipients)) {
				return true;
			}
			$this->SetupCustomFields($recipients);

			foreach ($recipients as $p => $recipientid) {
				$send_results = $this->SendToRecipient($recipientid, $queueid);

				$emails_sent++;

				$paused = false;

				/**
				* update lastupdatedtime so we can track what's going on.
				* This is used so we can see if the server has crashed or the cron job has been stopped in mid-send.
				*
				* @see FetchJob
				*/
				if ($this->userpause > 0 || ($this->userpause == 0 && (($emails_sent % 5) == 0))) {
					$query = "UPDATE " . SENDSTUDIO_TABLEPREFIX . "jobs SET lastupdatetime='" . $this->GetServerTime() . "' WHERE jobid='" . (int)$jobid . "'";
					$this->Db->Query($query);
					$this->Db->Query("COMMIT");
					$this->Db->Query("BEGIN");
					$paused = $this->JobPaused($jobid);
					$emails_sent = 0;
				}

				if ($paused) {
					break;
				}

				// we should only need to pause if we successfully sent.
				if ($send_results['success'] > 0) {
					$this->Pause();
				}
			}

			if ($paused) {
				break;
			}
		}

		$this->Db->Query("COMMIT");

		if ($paused) {
			$this->jobstatus = 'p';
			$finished = false;
		} else {
			$this->jobstatus = 'c';
			$finished = true;
			$this->CleanUp($queueid);
		}

		$this->NotifyOwner();

		$this->ResetSend();

		return $finished;
	}

	/**
	* NotifyOwner
	* This will notify the list owner(s) of job runs.
	* This will send the appropriate message depending on the state of the job.
	* If the job is not set to notify the owner, this does nothing.
	*
	* @see emailssent
	* @see jobdetails
	* @see jobstatus
	* @see Email_API::ForgetEmail
	* @see GetUser
	* @see Email_API::Set
	* @see Email_API::Subject
	* @see Email_API::FromName
	* @see Email_API::FromAddress
	* @see Email_API::Multipart
	* @see Email_API::AddBody
	* @see Email_API::ClearAttachments
	* @see Email_API::ClearRecipients
	* @see Email_API::AddRecipient
	* @see Email_API::Send
	* @see Sendstudio_Functions::PrintTime
	* @see Sendstudio_Functions::FormatNumber
	*
	* @return Void Doesn't return anything.
	*/
	function NotifyOwner()
	{
		if (empty($this->jobdetails)) {
			return;
		}

		if (!$this->jobdetails['NotifyOwner']) {
			return;
		}

		if (is_null($this->jobstatus)) {
			return;
		}

		$this->Notify_Email_API->ForgetEmail();

		$time = $this->sendstudio_functions->PrintTime();

		switch ($this->jobstatus) {
			case 'c':
				$subject = sprintf(GetLang('Job_Subject_Complete'), $this->newsletter['Subject']);
				if ($this->emailssent == 1) {
					$message = sprintf(GetLang('Job_Message_Complete_One'), $this->newsletter['Subject'], $time);
				} else {
					$message = sprintf(GetLang('Job_Message_Complete_Many'), $this->newsletter['Subject'], $time, $this->sendstudio_functions->FormatNumber($this->emailssent));
				}
			break;
			case 'p':
				$subject = sprintf(GetLang('Job_Subject_Paused'), $this->newsletter['Subject']);
				if ($this->emailssent == 1) {
					$message = sprintf(GetLang('Job_Message_Paused_One'), $this->newsletter['Subject'], $time);
				} else {
					$message = sprintf(GetLang('Job_Message_Paused_Many'), $this->newsletter['Subject'], $time, $this->sendstudio_functions->FormatNumber($this->emailssent));
				}
			break;
			default:
				$subject = sprintf(GetLang('Job_Subject_Started'), $this->newsletter['Subject']);
				$message = sprintf(GetLang('Job_Message_Started'), $this->newsletter['Subject'], $time);
		}

		$owner = &GetUser($this->jobowner);

		$this->Notify_Email_API->Set('Subject', $subject);
		$this->Notify_Email_API->Set('CharSet', SENDSTUDIO_CHARSET);
		if ($owner->fullname) {
			$this->Notify_Email_API->Set('FromName', $owner->fullname);
		} else {
			$this->Notify_Email_API->Set('FromName', GetLang('SendingSystem'));
		}

		if ($owner->emailaddress) {
			$this->Notify_Email_API->Set('FromAddress', $owner->emailaddress);
		} else {
			$this->Notify_Email_API->Set('FromAddress', GetLang('SendingSystem_From'));
		}

		$this->Notify_Email_API->Set('Multipart', false);

		$this->Notify_Email_API->AddBody('text', $message);

		$this->Notify_Email_API->ClearAttachments();
		$this->Notify_Email_API->ClearRecipients();

		$query = "SELECT listid, ownername, owneremail FROM " . SENDSTUDIO_TABLEPREFIX . "lists WHERE listid IN(" . implode(',', $this->jobdetails['Lists']) . ")";
		$result = $this->Db->Query($query);

		while ($row = $this->Db->Fetch($result)) {
			$this->Notify_Email_API->AddRecipient($row['owneremail'], $row['ownername'], 't');
		}

		$this->Notify_Email_API->Send();

		$this->Notify_Email_API->ForgetEmail();

	}

}

?>
