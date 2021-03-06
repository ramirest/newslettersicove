<?php
/**
* This is the bounce system api. This connects to the email account, gets the number of messages, logs out and so on.
* It also handles parsing the bounce message according to the bounce rules.
*
* The rules for bounce processing are in the language/jobs_bounce.php file.
*
* @version     $Id: bounce.php,v 1.29 2007/06/15 02:07:32 chris Exp $

*
* @package API
* @subpackage Bounce_API
*/

/**
* Require the base jobs class.
*/
require_once(dirname(__FILE__) . '/jobs.php');

class Bounce_API extends Jobs_API
{

	/**
	* Whether debug mode for bounce processing is on or off.
	* Switching this on will use 'LogFile' to save log messages as it goes through the processing routine.
	*
	* @see LogFile
	*/
	var $Debug = false;

	/**
	* Where to save debug messages.
	*
	* @see Debug
	* @see Bounce_API
	*/
	var $LogFile = null;

	/**
	* ErrorMessage contains the last imap_error message or possibly if imap support is enabled on the server.
	*
	* @see Bounce
	* @see Login
	*
	* @var String $ErrorMessage
	*/
	var $ErrorMessage = '';

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
	* bounceuser The bounce username to log in with.
	*
	* @see Login
	*
	* @var String
	*/
	var $bounceuser = null;

	/**
	* bouncepassword The bounce password to log in with.
	*
	* @see Login
	*
	* @var String
	*/
	var $bouncepassword = null;

	/**
	* bounceserver The server name to log in to.
	*
	* @see Login
	*
	* @var String
	*/
	var $bounceserver = null;

	/**
	* imapaccount Whether we are trying to log in to an imap account or a regular pop3 account.
	*
	* @see Login
	*
	* @var Boolean
	*/
	var $imapaccount = false;

	/**
	* extramailsettings Any extra email account settings we may need to use to log in.
	* For example '/notls' or '/nossl'
	*
	* @see Login
	*
	* @var String
	*/
	var $extramailsettings = null;

	/**
	* connection Temporarily store the connection to the email account here for easy use.
	*
	* @see Login
	* @see Logout
	* @see Delete
	* @see GetEmailCount
	* @see GetHeader
	* @see GetMessage
	*
	* @var Resource
	*/
	var $connection = null;

	/**
	* Bounce
	* The constructor sets up the required objects, checks for imap support and loads the language files which also load up the bounce rules.
	*
	* @return Mixed Returns false if there is no imap support. Otherwise returns true once all the sub-objects are set up for easy access.
	*/
	function Bounce_API()
	{
		if (is_null($this->LogFile)) {
			if (defined('TEMP_DIRECTORY')) {
				$this->LogFile = TEMP_DIRECTORY . '/bounce.debug.log';
			}
		}

		require_once(SENDSTUDIO_LANGUAGE_DIRECTORY . '/jobs_bounce.php');

		require_once(SENDSTUDIO_LANGUAGE_DIRECTORY . '/jobs_send.php');

		if (is_null($this->Email_API)) {
			if (!class_exists('email_api')) {
				require(dirname(__FILE__) . '/email.php');
			}
			$email = &new Email_API();
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

		$this->GetDb();
		return true;
	}

	/**
	* Login
	* Logs in to the email account using the settings provided.
	*
	* @see bounceuser
	* @see bouncepassword
	* @see bounceserver
	* @see imapaccount
	* @see extramailsettings
	* @see ErrorMessage
	* @see connection
	*
	* @return Boolean Returns true if any of the required parameters are missing (bounceuser, bouncepassword, bounceserver) or if a connection cannot be made. If the details are all present but we can't connect, sets the last error message in ErrorMessage for checking.
	*/
	function Login()
	{
		if (is_null($this->bounceuser) || is_null($this->bouncepassword) || is_null($this->bounceserver)) {
			return false;
		}

		if ($this->imapaccount) {
			if (strpos($this->bounceserver, ':') === false) {
				$connection = '{' . $this->bounceserver . ':143';
			} else {
				$connection = '{' . $this->bounceserver;
			}
		} else {
			if (strpos($this->bounceserver, ':') === false) {
				$connection = '{' . $this->bounceserver . ':110/pop3';
			} else {
				$connection = '{' . $this->bounceserver . '/pop3';
			}
		}

		if ($this->extramailsettings != '') {
			$connection .= $this->extramailsettings;
		}
		$connection .= '}INBOX';

		$password = @base64_decode($this->bouncepassword);

		if ($this->Debug) {
			error_log('Line ' . __LINE__ . '; connection string: ' . $connection ."\n", 3, $this->LogFile);
			error_log('Line ' . __LINE__ . '; bounceuser: ' . $this->bounceuser ."\n", 3, $this->LogFile);
			error_log('Line ' . __LINE__ . '; password: ' . $password ."\n", 3, $this->LogFile);
		}

		$inbox = @imap_open($connection, $this->bounceuser, $password);

		if ($this->Debug) {
			error_log('Line ' . __LINE__ . '; inbox: ' . $inbox ."\n", 3, $this->LogFile);
		}

		if (!$inbox) {
			$errormsg = imap_last_error();

			$errors = imap_errors();

			if (is_array($errors) && !empty($errors)) {
				$errormsg = array_shift($errors);
			} else {
				$alerts = imap_alerts();
				if (is_array($alerts) && !empty($alerts)) {
					$errormsg = array_shift($alerts);
				}
			}

			$this->ErrorMessage = $errormsg;

			if ($this->Debug) {
				error_log('Line ' . __LINE__ . '; imap_errors: ' . print_r(imap_errors(), true) ."\n", 3, $this->LogFile);
				error_log('Line ' . __LINE__ . '; imap_alerts: ' . print_r(imap_alerts(), true) ."\n", 3, $this->LogFile);
			}
			imap_alerts();
			return false;
		}
		imap_errors();
		imap_alerts();

		$this->connection = $inbox;
		return true;
	}

	/**
	* GetEmailCount
	* Gets the number of emails in the account based on the current connection.
	*
	* @see connection
	*
	* @return Mixed Returns false if the connection has not been established, otherwise gets the number of emails and returns that.
	*/
	function GetEmailCount()
	{
		if (is_null($this->connection)) {
			return false;
		}

		$display_errors = @ini_get('display_errors');
		@ini_set('display_errors', false);

		$count = imap_num_msg($this->connection);
		@ini_set('display_errors', $display_errors);
		return $count;
	}

	/**
	* Logout
	* Logs out of the established connection and optionally deletes messages that have been marked for deletion. Also resets the class connection variable.
	*
	* @param Boolean $delete_messages Whether to delete messages that have been marked for deletion or not.
	*
	* @see connection
	* @see Delete
	*
	* @return Boolean Returns false if the connection has not been established previously, otherwise returns true.
	*/
	function Logout($delete_messages=false)
	{
		if (is_null($this->connection)) {
			return false;
		}

		if ($delete_messages) {
			// delete any emails marked for deletion.
			imap_expunge($this->connection);
		}

		imap_close($this->connection);
		$this->connection = null;

		imap_errors();
		imap_alerts();

		return true;
	}

	/**
	* DeleteEmail
	* Marks a message for deletion when logging out of the account.
	*
	* @param Int $messageid The message to delete when logging out.
	*
	* @see connection
	* @see Logout
	*
	* @return Boolean Returns false if there is an invalid message number passed in or if there is no previous connection. Otherwise marks the message for deletion and returns true.
	*/
	function DeleteEmail($messageid=0)
	{
		$messageid = (int)$messageid;
		if ($messageid <= 0) {
			return false;
		}

		if (is_null($this->connection)) {
			return false;
		}

		imap_delete($this->connection, $messageid);
		return true;
	}

	/**
	* GetHeader
	* Gets the email header(s) for a particular message.
	*
	* @param Int $messageid The message to get the header(s) for.
	*
	* @return Mixed Returns false if the messageid is invalid or if there is no established connection, otherwise returns an object of the headers (per imap_header)
	*/
	function GetHeader($messageid=0)
	{
		$messageid = (int)$messageid;
		if ($messageid <= 0) {
			return false;
		}

		if (is_null($this->connection)) {
			return false;
		}

		$header = imap_header($this->connection, $messageid);

		if (isset($header->from)) {
			return $header;
		}

		if ($this->Debug) {
			error_log('Line ' . __LINE__ . '; the email headers werent returned properly. See if they have a utf-8 byte-order-mark in them.' . "\n", 3, $this->LogFile);
		}

		$body = imap_body($this->connection, $messageid);

		// in some bizarre cases, hotmail returns an email with utf-8 BOM (byte-order-mark) at the start of their bounces.
		// so, in that case we have to do something a little different.
		$headers = preg_match("%^(.*?)\r\n\r\n%s", $body, $matches);

		unset($body);

		if (empty($matches) || !isset($matches[1])) {
			return false;
		}

		$header = $matches[1];
		$imap_headers = imap_rfc822_parse_headers(str_replace('﻿', '', $header));

		if (empty($imap_headers)) {
			if ($this->Debug) {
				error_log('Line ' . __LINE__ . '; the email headers are completely invalid - imap cant parse them. Returning false.' . "\n", 3, $this->LogFile);
			}
			return false;
		}

		return $imap_headers;
	}

	/**
	* GetBounceTime
	* Gets the bounce time using the udate header from the header object passed in.
	*
	* @param Object $header The header object to look for the udate property in.
	*
	* @return Mixed Returns false if the header is invalid, otherwise returns the udate property which is in unix-timestamp format.
	*/
	function GetBounceTime($header=false)
	{
		if (!is_object($header)) {
			return false;
		}

		$bounce_time = 0;
		if (isset($header->udate)) {
			$bounce_time = $header->udate;
		}
		return $bounce_time;
	}

	/**
	* GetBounceFrom
	* Constructs and returns the from header based on the object passed in.
	*
	* @param Object $header The object to find the from details in.
	*
	* @return Mixed Returns false if an invalid header object is passed in, otherwise constructs the from address and returns it as a string.
	*/
	function GetBounceFrom($header=false)
	{
		if (!is_object($header)) {
			return false;
		}

		if (!isset($header->from) || empty($header->from)) {
			return false;
		}

		reset($header->from);
		// we can't juse use header->fromaddress because this might contain a name or something.
		// so we reconstruct it ourselves.
		$from_header = current($header->from);

		// If the from header doesn't have a required property return false also
		if (!isset($from_header->mailbox) || !isset($from_header->host)) {
			if ($this->Debug) {
				error_log('Line ' . __LINE__ . '; the email from_header object didnt have a required property '.print_r($from_header, true)."\n", 3, $this->LogFile);
			}
			return false;
		}

		$fromaddress = strtolower($from_header->mailbox . '@' . $from_header->host);

		return $fromaddress;
	}

	/**
	* GetBounceMailbox
	* Returns the 'mailbox' or the first part of an email address (eg 'mailer-daemon') from the header object passed in.
	*
	* @param Object $header The header object to get the mailbox details from.
	*
	* @return Mixed Returns false if an invalid header object is passed in, otherwise gets the mailbox part of the email address and returns it as a string.
	*/
	function GetBounceMailbox($header=false)
	{
		if (!is_object($header)) {
			return false;
		}

		if (!isset($header->from) || empty($header->from)) {
			return false;
		}

		reset($header->from);
		$from_header = current($header->from);
		$mailbox = strtolower($from_header->mailbox);

		return $mailbox;
	}

	/**
	* GetMessage
	* Returns the message body based on the messageid passed in.
	*
	* @param Int $messageid The message number to get the email body for.
	*
	* @return Mixed Returns false if an invalid message number is passed in or there is an invalid connection. Otherwise returns the whole message body.
	*/
	function GetMessage($messageid=0)
	{
		$messageid = (int)$messageid;
		if ($messageid <= 0) {
			return false;
		}

		if (is_null($this->connection)) {
			return false;
		}

		return imap_body($this->connection, $messageid);
	}

	/**
	* GetBounceList
	* Gets the listid from the bounced email's body (passed in).
	*
	* @param String $body The message body to look for the listid in.
	*
	* @return Mixed Returns false if there is no message body passed in, otherwise returns the x-mailer-listid it found in the body.
	*/
	function GetBounceList($body=false)
	{
		if (!$body) {
			return false;
		}

		$bounce_listids = Array();

		if (preg_match('%x-mailer-lid: (.*)%i', $body, $match)) {
			if ($this->Debug) {
				error_log('Line ' . __LINE__ . '; Found a lid match '.print_r($match, true)."\n", 3, $this->LogFile);
			}
			$bounce_listids = trim($match[1]);
			if (strpos($bounce_listids, ',') !== false) {
				$bounce_listids = explode(',', str_replace(' ', '', $bounce_listids));
			} else {
				$bounce_listids = Array($bounce_listids);
			}
		}

		if (empty($bounce_listids)) {
			if (preg_match('%x-mailer-listid: (.*)%i', $body, $match)) {
				if ($this->Debug) {
					error_log('Line ' . __LINE__ . '; Found a lid match '.print_r($match, true)."\n", 3, $this->LogFile);
				}
				$bounce_listid = trim($match[1]);
				$bounce_listids = array($bounce_listid);
			}
		}

		if ($this->Debug) {
			error_log('Line ' . __LINE__ . '; before calling checkint vars the listids array is '.print_r($bounce_listids, true)."\n", 3, $this->LogFile);
		}

		$bounce_listids = $this->CheckIntVars($bounce_listids);

		if ($this->Debug) {
			error_log('Line ' . __LINE__ . '; after calling checkint vars the listids array is '.print_r($bounce_listids, true)."\n", 3, $this->LogFile);
		}

		return $bounce_listids;
	}

	/**
	* GetBounceStat
	* Gets the statid from the bounced email's body (passed in).
	*
	* @param String $body The message body to look for the statid in.
	*
	* @return Mixed Returns false if there is no message body passed in, otherwise returns the x-mailer-sid it found in the body.
	*/
	function GetBounceStat($body=false)
	{
		if (!$body) {
			return false;
		}

		$bounce_statid = 0;
		if (preg_match('%x-mailer-sid: (.*)%i', $body, $match)) {
			$bounce_statid = trim($match[1]);
		}

		return $bounce_statid;
	}

	/**
	* ParseBody
	* This trims the original message from the body passed in, then goes through the body of the email and works out what type of bounce it is.
	* The only way to know is to use a series of regular expressions (in the GLOBALS['BOUNCE_RULES'] array) and try to match one against the body passed in.
	* This returns a triple entry array.
	* The first entry is the bounce type (hard/soft bounce).
	* The second entry is the bounce group (hard bounce - doesntexist, relayerror, inactive; soft bounce - overquota).
	* The third entry is the email address that it found to bounce.
	* If no regular expressions match, then each part is returned as 'false'.
	*
	* @param String $body The body to parse and try to match the bounce rules against.
	*
	* @return Array Returns a triple element array with the bounce type, bounce group and email address.
	*/
	function ParseBody($body)
	{
		/**
		* Don't care what the original message is, get rid of it.
		*/
		$body = preg_replace('%--- Below this line is a copy of the message.(.*)%is', '', $body);
		$body = preg_replace('%------ This is a copy (.*)%is', '', $body);
		$body = preg_replace('%----- Transcript of session follows -----(.*)%is', '', $body);

		/**
		* postfix is different (of course).
		*/
		$body = preg_replace('%Content-Description: Delivery report(.*)%is', '', $body);

		$body = str_replace("\r", "", $body);

		if ($this->Debug) {
			error_log('Line ' . __LINE__ . '; processing body: ' . $body . "\n", 3, $this->LogFile);
		}

		/**
		* in case the body put extra spacing after newlines, get rid of them.
		*/
		$body = preg_replace('%\s+%', ' ', $body);
		foreach ($GLOBALS['BOUNCE_RULES'] as $bounce_type => $bounce_rule) {
			foreach ($bounce_rule as $bounce_group => $rules) {
				foreach ($rules as $p => $target_string) {

					if ($this->Debug) {
						error_log('Line ' . __LINE__ . '; Processing bounce type ' . $bounce_type . '; rule: ' . $target_string . "\n", 3, $this->LogFile);
					}

					if (preg_match('%' . preg_quote($target_string) . '%is', $body)) {
						if (preg_match_all("%\b([\w\.\%\+'-]+@[a-zA-Z0-9\.-]+\.[a-zA-Z]{2,4})\b%is", $body, $email_matches)) {

							if ($this->Debug) {
								error_log('Line ' . __LINE__ . '; email_matches: ' . print_r($email_matches, true) . "\n", 3, $this->LogFile);
							}

							$emails_to_return = Array();
							foreach ($email_matches[1] as $p => $emailaddress) {
								if (strpos($emailaddress, 'postmaster') !== false) {
									continue;
								}
								if (!in_array($emailaddress, $emails_to_return)) {
									$emails_to_return[] = $emailaddress;
								}
							}
							return array($bounce_type, $bounce_group, $emails_to_return);
						}
						if ($this->Debug) {
							error_log('Line ' . __LINE__ . '; no email_matches: ' . print_r($email_matches, true) . "\n", 3, $this->LogFile);
						}
					} else {
						if ($this->Debug) {
							error_log('Line ' . __LINE__ . '; no matches found for rule ' . $target_string . "\n", 3, $this->LogFile);
						}
					}
				}
			}
		}
		return array(false, false, false);
	}

	function ProcessEmail($emailid=0, $listid=0)
	{

		if ($this->Debug) {
			error_log("\n---------\n", 3, $this->LogFile);
			error_log('Line ' . __LINE__ . '; Date: ' . date('r') . "\n", 3, $this->LogFile);
			error_log('Line ' . __LINE__ . '; Processing emailid: ' . $emailid . '; listid: ' . $listid . "\n", 3, $this->LogFile);
		}

		$header = $this->GetHeader($emailid);

		if (!$header || empty($header)) {
			if ($this->Debug) {
				error_log('Line ' . __LINE__ . '; no proper email headers found. Returning ignore' . "\n", 3, $this->LogFile);
			}
			return 'ignore';
		}

		$bounce_time = $this->GetBounceTime($header);

		if ($this->Debug) {
			error_log('Line ' . __LINE__ . '; bounce_time: ' . $bounce_time . "\n", 3, $this->LogFile);
		}

		if ($bounce_time == 0) {
			if ($this->Debug) {
				error_log('Line ' . __LINE__ . '; ignoring emails with bounce time of 0' . "\n", 3, $this->LogFile);
			}
			return 'ignore';
		}

		$fromaddress = $this->GetBounceFrom($header);

		if ($this->Debug) {
			error_log('Line ' . __LINE__ . '; From Address: ' . $fromaddress . "\n", 3, $this->LogFile);
		}

		$mailbox = $this->GetBounceMailbox($header);

		if ($this->Debug) {
			error_log('Line ' . __LINE__ . '; Mailbox: ' . $mailbox . "\n", 3, $this->LogFile);
		}

		if (
			$mailbox != 'mailer-daemon' &&
			$mailbox != 'postmaster'
		) {
			$on_list = $this->Subscriber_API->IsSubscriberOnList($fromaddress, $listid, 0, true);
			if ($on_list) {

				if ($this->Debug) {
					error_log('Line ' . __LINE__ . '; fromaddress is on the list but the mailbox is not mailer-daemon or postmaster. Returning "ignore"' . "\n", 3, $this->LogFile);
				}

				return 'ignore';
			}
		}

		$body = $this->GetMessage($emailid);

		$bounce_listids = $this->GetBounceList($body);

		if ($this->Debug) {
			error_log('Line ' . __LINE__ . '; bounce_listids: ' . print_r($bounce_listids, true) . "\n", 3, $this->LogFile);
		}

		// different list? keep going - it will pick it up and record as necessary.
		// we care what list it's on so we can see the bounces per list.
		if (empty($bounce_listids) || !in_array($listid, $bounce_listids)) {
			if ($this->Debug) {
				error_log('Line ' . __LINE__ . '; The bounce_listid ['.print_r($bounce_listids, true).'] from the message is different to the listid ['.$listid.'] we are trying to process. Returning "ignore"' . "\n", 3, $this->LogFile);
			}
			return 'ignore';
		}

		$bounce_statid = $this->GetBounceStat($body);

		if ($this->Debug) {
			error_log('Line ' . __LINE__ . '; bounce_statid: ' . $bounce_statid . "\n", 3, $this->LogFile);
		}

		// now go through the bounce types and work out what it is.
		list($bounce_type, $bounce_rule, $bounce_emails) = $this->ParseBody($body);

		if ($this->Debug) {
			error_log('Line ' . __LINE__ . '; bounce_type: ' . $bounce_type . '; bounce_rule: '. $bounce_rule . '; bounce_email: ' . print_r($bounce_emails, true) . "\n", 3, $this->LogFile);
		}

		if (empty($bounce_emails) || (!$bounce_type && !$bounce_rule)) {
			if ($this->Debug) {
				error_log('Line ' . __LINE__ . '; The bounce_email or bounce_type or bounce_rule dont exist. Returning "ignore"' . "\n", 3, $this->LogFile);
			}
			return 'ignore';
		}

		foreach ($bounce_emails as $bep => $bounce_email) {
			$subscriber_id = false;
			$bounce_listid = 0;

			// Never unsubscribe the list owner. Sometimes a bounce might include
			// the from address which would be detected as an address to bounce
			$this->Lists_API->Load($listid);
			if ($bounce_email == $this->Lists_API->owneremail) {
				continue;
			}

			if ($this->Debug) {
				error_log('Line ' . __LINE__ . '; checking email ' . $bounce_email . ' against listids ' . print_r($bounce_listids, true) . "\n", 3, $this->LogFile);
			}

			$subscriber_info = $this->Subscriber_API->IsSubscriberOnList($bounce_email, $bounce_listids, 0, false, true, true);

			if (is_array($subscriber_info)) {
				$subscriber_id = $subscriber_info['subscriberid'];
				$bounce_listid = $subscriber_info['listid'];
			}

			if ($this->Debug) {
				error_log('Line ' . __LINE__ . '; subscriber_id: ' . $subscriber_id . "\n", 3, $this->LogFile);
				error_log('Line ' . __LINE__ . '; bounce_listid: ' . $bounce_listid . "\n", 3, $this->LogFile);
			}

			if (!$subscriber_id || !$bounce_listid) {
				if ($this->Debug) {
					error_log('Line ' . __LINE__ . '; subscriber_id is not on list.' . "\n", 3, $this->LogFile);
				}

				if (in_array($listid, $bounce_listids)) {
					if ($this->Debug) {
						error_log('Line ' . __LINE__ . '; listid: ' . $listid . ' is in the bounce listids: ' . print_r($bounce_listids, true) . ' but we are unable to find the subscriber on the list - so we will return the bounce type (' . $bounce_type . ') and delete the email.' . "\n", 3, $this->LogFile);
					}
					// dont return a bounce type
					// otherwise it gets included in the popup window report.
					return 'delete';
				}

				if ($this->Debug) {
					error_log('Line ' . __LINE__ . '; skipping email.' . "\n", 3, $this->LogFile);
				}
				continue;
			}

			$already_bounced = $this->Subscriber_API->AlreadyBounced($subscriber_id, $bounce_statid, $bounce_listid);
			if ($this->Debug) {
				error_log('Line ' . __LINE__ . '; already_bounced returned ' . $already_bounced . "\n", 3, $this->LogFile);
			}

			if ($already_bounced) {
				if ($this->Debug) {
					error_log('Line ' . __LINE__ . '; a bounce has already been recorded so returning bounce type ' . $bounce_type . "\n", 3, $this->LogFile);
				}
				return $bounce_type;
			}

			if ($this->Debug) {
				error_log('Line ' . __LINE__ . '; a bounce has not been recorded. Recording info. bounce_type: ' . $bounce_type . '; bounce_rule: '. $bounce_rule . "\n", 3, $this->LogFile);
			}

			$this->Stats_API->RecordBounceInfo($subscriber_id, $bounce_statid, $bounce_type);

			$this->Subscriber_API->RecordBounceInfo($subscriber_id, $bounce_statid, $bounce_listid, $bounce_type, $bounce_rule, $body, $bounce_time);

			if ($this->Debug) {
				error_log('Line ' . __LINE__ . '; returning bounce type ' . $bounce_type . "\n", 3, $this->LogFile);
			}

			return $bounce_type;
		}
		return 'ignore';
	}

}

?>