<?php
/**
* This is the send system api.
*
* @version     $Id: send.php,v 1.17 2007/06/04 08:13:52 chris Exp $

*
* @package API
* @subpackage Send_API
*/

/**
* Require the base API class.
*/
require_once(dirname(__FILE__) . '/jobs.php');

class Send_API extends Jobs_API
{

	var $queuesize = 0;

	var $to_customfields = array();
	var $custom_fields_to_replace = array();

	var $sendstudio_functions = null;

	var $jobowner = 0;

	/**
	* Email_API holds the email api class temporarily so we can notify the list admin etc if we need to.
	*
	* @see Bounce
	*
	* @var Object $Email_API
	*/
	var $Email_API = null;

	/**
	* Subscriber_API holds the subscriber api class temporarily so we can record bounce info etc.
	*
	* @see Bounce
	*
	* @var Object $Subscriber_API
	*/
	var $Subscriber_API = null;

	/**
	* Lists_API holds the list api class temporarily so we can record bounce info etc.
	*
	* @see Bounce
	*
	* @var Object $Lists_API
	*/
	var $Lists_API = null;

	/**
	* Stats_API holds the stats api class temporarily so we can record bounce info etc.
	*
	* @see Bounce
	*
	* @var Object $Stats_API
	*/
	var $Stats_API = null;

	/**
	* A reference to the newsletter api. Saves us having to re-create it all the time.
	*
	* @see Jobs_Send_API
	* @see ProcessJob
	* @see ActionJob
	*
	* @var Object
	*/
	var $Newsletters_API = null;

	/**
	* Used to remember the newsletter we're sending. Saves constantly loading it from the database.
	*
	* @see ActionJob
	*
	* @var Array
	*/
	var $newsletter = array();

	/**
	* A count of the emails we have sent (used for reporting).
	*
	* @var Int
	*/
	var $emailssent = 0;

	/**
	* The pause between sending each newsletter if applicable.
	*
	* @var Int
	*/
	var $userpause = null;

	/**
	* Temporarily holds the user object so it can be easily referenced.
	*
	* @var Object
	*/
	var $user = null;

	/**
	* The statistics id so we can record what's going on.
	*
	* @var Int
	*/
	var $statid = 0;

	/**
	* The name(s) of the list(s) we're sending to.
	*
	* @var Array
	*/
	var $listnames = Array();

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
	* Bounce
	* The constructor sets up the required objects, checks for imap support and loads the language files which also load up the bounce rules.
	*
	* @return Mixed Returns false if there is no imap support. Otherwise returns true once all the sub-objects are set up for easy access.
	*/
	function Send_API()
	{
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
			$list = &new Lists_API();
			$this->Lists_API = &$list;
		}

		if (is_null($this->Stats_API)) {
			if (!class_exists('stats_api')) {
				require(dirname(__FILE__) . '/stats.php');
			}
			$stats = &new Stats_API();
			$this->Stats_API = &$stats;
		}

		if (is_null($this->Newsletters_API)) {
			if (!class_exists('newsletters_api')) {
				require(dirname(__FILE__) . '/newsletters.php');
			}
			$news = &new Newsletters_API();
			$this->Newsletters_API = &$news;
		}

		if (is_null($this->sendstudio_functions)) {
			if (!class_exists('sendstudio_functions')) {
				require(dirname(__FILE__) . '/../sendstudio_functions.php');
			}
			$ss_functions = &new Sendstudio_Functions();
			$this->sendstudio_functions = &$ss_functions;
		}

		$this->GetDb();

		return true;
	}

	function SetupJob($jobid=0, $queueid=0)
	{
		$is_queue = $this->IsQueue($queueid, 'send');
		if (!$is_queue) {
			return false;
		}

		// if we can't load the newsletter, pause it and immediately stop.
		$news_loaded = $this->Newsletters_API->Load($this->jobdetails['Newsletter']);
		if (!$news_loaded) {
			return false;
		}

		$this->user = &GetUser($this->jobowner);

		$this->newsletter = array();

		$this->newsletter['Format'] = $this->Newsletters_API->Get('format');
		$this->newsletter['Subject'] = $this->Newsletters_API->Get('subject');
		$this->newsletter['TextBody'] = $this->Newsletters_API->Get('textbody');
		$this->newsletter['HTMLBody'] = $this->Newsletters_API->Get('htmlbody');

		$this->newsletter['Attachments'] = $this->sendstudio_functions->GetAttachments('newsletters', $this->jobdetails['Newsletter'], true);

		$this->Email_API->ForgetEmail();

		$this->Email_API->SetSmtp(SENDSTUDIO_SMTP_SERVER, SENDSTUDIO_SMTP_USERNAME, @base64_decode(SENDSTUDIO_SMTP_PASSWORD), SENDSTUDIO_SMTP_PORT);

		$this->Email_API->Set('statid', $this->statid);
		$this->Email_API->Set('listids', $this->jobdetails['Lists']);

		if (SENDSTUDIO_FORCE_UNSUBLINK) {
			$this->Email_API->ForceLinkChecks(true);
		}

		if ($this->jobdetails['TrackLinks']) {
			$this->Email_API->TrackLinks(true);
		}

		if ($this->jobdetails['TrackOpens']) {
			$this->Email_API->TrackOpens(true);
		}

		$this->Email_API->Set('CharSet', $this->jobdetails['Charset']);

		if (!SENDSTUDIO_SAFE_MODE) {
			$this->Email_API->Set('imagedir', TEMP_DIRECTORY . '/send.' . $jobid . '.' . $queueid);
		} else {
			$this->Email_API->Set('imagedir', TEMP_DIRECTORY . '/send');
		}

		// clear out the attachments just to be safe.
		$this->Email_API->ClearAttachments();

		if ($this->newsletter['Attachments']) {
			$path = $this->newsletter['Attachments']['path'];
			$files = $this->newsletter['Attachments']['filelist'];
			foreach ($files as $p => $file) {
				$this->Email_API->AddAttachment($path . '/' . $file);
			}
		}

		$this->Email_API->Set('Subject', $this->newsletter['Subject']);

		$this->Email_API->Set('FromName', $this->jobdetails['SendFromName']);
		$this->Email_API->Set('FromAddress', $this->jobdetails['SendFromEmail']);
		$this->Email_API->Set('ReplyTo', $this->jobdetails['ReplyToEmail']);
		$this->Email_API->Set('BounceAddress', $this->jobdetails['BounceEmail']);

		$this->Email_API->Set('Multipart', $this->jobdetails['Multipart']);

		$this->Email_API->Set('EmbedImages', $this->jobdetails['EmbedImages']);

		$this->Email_API->Set('SentBy', $this->user->Get('userid'));

		if ($this->jobdetails['Multipart']) {
			if ($this->newsletter['TextBody'] && $this->newsletter['HTMLBody'] && $this->newsletter['Format'] == 'b') {
				$sent_format = 'm';
			} else {
				$this->Email_API->Set('Multipart', false);
			}
		}

		if ($this->newsletter['TextBody'] && in_array($this->newsletter['Format'], array('t', 'b'))) {
			$this->Email_API->AddBody('text', $this->newsletter['TextBody']);
			$this->Email_API->AppendBody('text', $this->user->Get('textfooter'));
			$this->Email_API->AppendBody('text', stripslashes(SENDSTUDIO_TEXTFOOTER));
		}

		if ($this->newsletter['HTMLBody'] && in_array($this->newsletter['Format'], array('h', 'b'))) {
			$this->Email_API->AddBody('html', $this->newsletter['HTMLBody']);
			$this->Email_API->AppendBody('html', $this->user->Get('htmlfooter'));
			$this->Email_API->AppendBody('html', stripslashes(SENDSTUDIO_HTMLFOOTER));
		}

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
		}

		$this->custom_fields_to_replace = $this->Email_API->GetCustomFields();

		$to_customfields = array();

		if ($this->jobdetails['To_FirstName']) {
			$to_customfields[] = $this->jobdetails['To_FirstName'];
		}

		if ($this->jobdetails['To_LastName']) {
			$to_customfields[] = $this->jobdetails['To_LastName'];
		}

		$this->to_customfields = array_unique($to_customfields);
		return true;
	}

	function SendToRecipient($recipient=0, $queueid=0)
	{
		$this->Email_API->ClearRecipients();
		$subscriberinfo = array();

		$subscriberinfo = $this->Subscriber_API->LoadSubscriberBasicInformation($recipient, $this->jobdetails['Lists']);

		if (empty($subscriberinfo) || !isset($subscriberinfo['subscribedate'])) {
			$this->RemoveFromQueue($queueid, 'send', $recipient);
			$mail_result['success'] = 0;
			return $mail_result;
		}

		if (sizeof($this->jobdetails['Lists']) == 1) {
			if (empty($this->listnames)) {
				$listid = current($this->jobdetails['Lists']);
				$this->Lists_API->Load($listid);
				$listname = $this->Lists_API->Get('name');
				$this->listnames = array($listid => $listname);
			}
		} else {
			// a subscriberid is unique per list and per system, so a subscriberid can only have one listid associated with it.
			// so we'll just get the listname from that.
			$listid = $subscriberinfo['listid'];
			if (!in_array($listid, array_keys($this->listnames))) {
				$this->Lists_API->Load($listid);
				$listname = $this->Lists_API->Get('name');
				$this->listnames[$listid] = $listname;
			}
		}

		$subscriberinfo['CustomFields'] = array();
		if (isset($this->all_customfields[$recipient])) {
			$subscriberinfo['CustomFields'] = $this->all_customfields[$recipient];
		} else {
			/**
			* If the subscriber has no custom fields coming from the database, then set up blank placeholders.
			* If they have no custom fields in the database, they have no records in the 'all_customfields' array - so we need to fill it up with blank entries.
			*/
			foreach ($this->custom_fields_to_replace as $fieldid => $fieldname) {
				$subscriberinfo['CustomFields'][] = array(
					'fieldid' => $fieldid,
					'fieldname' => $fieldname,
					'fieldtype' => 'text',
					'defaultvalue' => '',
					'fieldsettings' => '',
					'subscriberid' => $recipient,
					'data' => ''
				);
			}
		}

		$name = false;

		if ($this->jobdetails['To_FirstName']) {
			foreach ($subscriberinfo['CustomFields'] as $p => $details) {
				if ($details['fieldid'] == $this->jobdetails['To_FirstName'] && $details['data'] != '') {
					$name = $details['data'];
					break;
				}
			}
		}

		if ($this->jobdetails['To_LastName']) {
			foreach ($subscriberinfo['CustomFields'] as $p => $details) {
				if ($details['fieldid'] == $this->jobdetails['To_LastName'] && $details['data'] != '') {
					$name .= ' ' . $details['data'];
					break;
				}
			}
		}

		$send_format = $subscriberinfo['format'];
		if ($this->newsletter['Format'] == 't') {
			$send_format = 't';
		}

		$this->Email_API->AddRecipient($subscriberinfo['emailaddress'], $name, $send_format, $subscriberinfo['subscriberid']);

		$subscriberinfo['listname'] = $this->listnames[$subscriberinfo['listid']];
		$subscriberinfo['listid'] = $subscriberinfo['listid'];
		$subscriberinfo['newsletter'] = $this->jobdetails['Newsletter'];
		$subscriberinfo['statid'] = $this->statid;

		$this->Email_API->AddCustomFieldInfo($subscriberinfo['emailaddress'], $subscriberinfo);

		$mail_result = $this->Email_API->Send(true);

		if (!$this->Email_API->Get('Multipart')) {
			$sent_format = $subscriberinfo['format'];
		} else {
			$sent_format = 'multipart';
		}

		if ($mail_result['success'] > 0) {
			$this->Stats_API->UpdateRecipient($this->statid, $sent_format, 'n');
		}

		$this->emailssent++;

		$this->MarkAsProcessed($queueid, 'send', $recipient);
		return $mail_result;
	}

	function Pause()
	{
		if ($this->userpause > 0) {
			if ($this->userpause > 0 && $this->userpause < 1) {
				$p = ceil($this->userpause*1000000);
				usleep($p);
			} else {
				$p = ceil($this->userpause);
				sleep($p);
			}
		}
	}

	function CleanUp($queueid=0)
	{
		$this->Stats_API->MarkNewsletterFinished($this->statid, $this->queuesize);
		$this->ClearQueue($queueid, 'send');

		$this->Email_API->CleanupImages();
	}

	function ResetSend()
	{
		// unset the listname.
		$this->listnames = Array();

		// reset the 'pause' counter.
		$this->userpause = null;

		$this->emailssent = 0;
	}

	function SetupCustomFields($recipients)
	{
		$this->all_customfields = $this->Subscriber_API->GetAllSubscriberCustomFields($this->jobdetails['Lists'], $this->custom_fields_to_replace, $recipients, $this->to_customfields);

		// rather than using the customfield api to do all of this (which would require loading each item separately, then running GetRealValue) we'll do the "dodgy" and do it all here instead.

		foreach ($this->all_customfields as $subscriberid => $customfield_list) {
			foreach ($customfield_list as $p => $details) {

				switch ($details['fieldtype']) {
					case 'dropdown':
						$settings = unserialize($details['fieldsettings']);
						$data = $details['data'];
						$pos = array_search($data, $settings['Key']);
						if (is_numeric($pos)) {
							$this->all_customfields[$subscriberid][$p]['data'] = $settings['Value'][$pos];
						}
					break;

					case 'radiobutton':
						$settings = unserialize($details['fieldsettings']);
						$data = $details['data'];
						$pos = array_search($data, $settings['Key']);

						if (is_numeric($pos)) {
							$this->all_customfields[$subscriberid][$p]['data'] = $settings['Value'][$pos];
						}
					break;

					case 'checkbox':
						$settings = unserialize($details['fieldsettings']);
						$data = $details['data'];

						$value = unserialize($data);

						$return_value = array();
						foreach ($settings['Key'] as $pos => $key) {
							if (in_array($key, $value)) {
								$return_value[] = $settings['Value'][$pos];
							}
						}

						$data = implode(',', $return_value);

						$this->all_customfields[$subscriberid][$p]['data'] = $data;
					break;
				}
			}
		}
	}
}

?>
