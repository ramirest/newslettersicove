<?php
/**
* This file has the sending page in it. This only handles sending and scheduling of email campaigns.
*
* @version     $Id: send.php,v 1.49 2007/05/28 05:37:57 chris Exp $

*
* @package SendStudio
* @subpackage SendStudio_Functions
*/

/**
* Include the base sendstudio functions.
*/
require(dirname(__FILE__) . '/sendstudio_functions.php');

/**
* Class for management of sending newsletters.
*
* @package SendStudio
* @subpackage SendStudio_Functions
*/
class Send extends SendStudio_Functions
{

	/**
	* ValidSorts
	* An array of sorts you can use with send management.
	*
	* @var Array
	*/
	var $ValidSorts = array('name', 'createdate');

	/**
	* Constructor
	* Loads the language file.
	*
	* @see LoadLanguageFile
	* @see PrintHeader
	* @see PrintFooter
	*
	* @return Void Loads up the language file and adds 'send' as a valid popup window type.
	*/
	function Send()
	{
		$this->PopupWindows[] = 'send';
		$this->LoadLanguageFile();
	}

	/**
	* Process
	* This works out where you are up to in the send process and takes the appropriate action. Most is passed off to other methods in this class for processing and displaying the right forms.
	*
	* @return Void Doesn't return anything.
	*/
	function Process()
	{
		$action = (isset($_GET['Action'])) ? strtolower(urldecode($_GET['Action'])) : null;
		$user = &GetUser();
		$access = $user->HasAccess('Newsletters', 'send');

		$popup = (in_array($action, $this->PopupWindows)) ? true : false;
		$this->PrintHeader($popup);

		if (!$access) {
			$this->DenyAccess();
			return;
		}

		if ($action == 'processpaging') {
			$perpage = (int)$_GET['PerPageDisplay'];
			$display_settings = array('NumberToShow' => $perpage);
			$user->SetSettings('DisplaySettings', $display_settings);
			$action = '';
		}

		$session = &GetSession();

		switch ($action) {
			case 'pausesend':
				$send_details = $session->Get('SendDetails');
				$job = (int)$_GET['Job'];
				$api = $this->GetApi('Jobs');
				$paused = $api->PauseJob($job);
				if ($paused) {
					$GLOBALS['Message'] = $this->PrintSuccess('Send_Paused_Success');
				} else {
					$GLOBALS['Error'] = GetLang('Send_Paused_Failure');
					$GLOBALS['Message'] = $this->ParseTemplate('ErrorMsg', true, false);
				}
				$this->ParseTemplate('Send_Step5_Paused');
			break;

			case 'sendfinished':
				$job = (int)$_GET['Job'];
				$send_details = $session->Get('SendDetails');

				$statsapi = $this->GetApi('Stats');

				$statsapi->MarkNewsletterFinished($send_details['StatID'], $send_details['SendSize']);

				$timetaken = $send_details['SendEndTime'] - $send_details['SendStartTime'];
				$timedifference = $this->TimeDifference($timetaken);

				$GLOBALS['SendReport_Intro'] = sprintf(GetLang('SendReport_Intro'), $timedifference);

				$sendreport = '';
				if ($send_details['EmailResults']['success'] > 0) {
					if ($send_details['EmailResults']['success'] == 1) {
						$sendreport .= $this->PrintSuccess('SendReport_Success_One');
					} else {
						$sendreport .= $this->PrintSuccess('SendReport_Success_Many', $this->FormatNumber($send_details['EmailResults']['success']));
					}
				}

				$failure_count = sizeof($send_details['EmailResults']['failure']);
				if ($failure_count > 0) {
					if ($failure_count == 1) {
						$GLOBALS['Error'] = GetLang('SendReport_Failure_One');
					} else {
						$GLOBALS['Error'] = sprintf(GetLang('SendReport_Failure_Many'), $this->FormatNumber($failure_count));
					}
					$sendreport .= $this->ParseTemplate('ErrorMsg', true, false);
				}
				$GLOBALS['Send_Report'] = $sendreport;

				$api = $this->GetApi('Jobs');

				$api->FinishJob($job);
				$this->ParseTemplate('Send_Step5_Finished');
			break;

			case 'send':
				$jobid = (int)$_GET['Job'];
				$subscriberApi = $this->GetApi('Subscribers');

				$jobApi = $this->GetApi('Jobs');

				if (!isset($_GET['Started'])) {
					$jobApi->StartJob($jobid);
				}

				$sendqueue = $jobApi->GetJobQueue($jobid);
				$queuesize = $jobApi->QueueSize($sendqueue, 'send');

				$send_details = $session->Get('SendDetails');

				$timenow = AdjustTime(0, true, null, true);

				$timediff = ($timenow - $send_details['SendStartTime']);

				$time_so_far = $this->TimeDifference($timediff);

				$num_left_to_send = $send_details['SendSize'] - $queuesize;

				if ($num_left_to_send > 0) {
					$timeunits = $timediff / ($num_left_to_send);
					$timediff = ($timeunits * $queuesize);
				} else {
					$timediff = 0;
				}
				$timewaiting = $this->TimeDifference($timediff);

				$GLOBALS['SendTimeSoFar'] = sprintf(GetLang('Send_TimeSoFar'), $time_so_far);
				$GLOBALS['SendTimeLeft'] = sprintf(GetLang('Send_TimeLeft'), $timewaiting);

				if ($queuesize <= 0) {

					$email = $this->GetApi('Email');
					if (SENDSTUDIO_SAFE_MODE) {
						$email->Set('imagedir', TEMP_DIRECTORY . '/send');
					} else {
						$email->Set('imagedir', TEMP_DIRECTORY . '/send.' . $jobid . '.' . $sendqueue);
					}
					$email->CleanupImages();

					$send_details['SendEndTime'] = AdjustTime(0, true, null, true);
					$session->Set('SendDetails', $send_details);

					$GLOBALS['Send_NumberLeft'] = GetLang('SendFinished');
					$this->ParseTemplate('Send_Step5');
					?>
						<script language="javascript">
							window.opener.focus();
							window.opener.document.location = 'index.php?Page=Send&Action=SendFinished&Job=<?php echo $jobid; ?>';
							window.close();
						</script>
					<?php
					break;
				}

				if ($queuesize == 1) {
					$GLOBALS['Send_NumberLeft'] = GetLang('Send_NumberLeft_One');
				} else {
					$GLOBALS['Send_NumberLeft'] = sprintf(GetLang('Send_NumberLeft_Many'), $this->FormatNumber($queuesize));
				}

				if ($num_left_to_send == 1) {
					$GLOBALS['Send_NumberAlreadySent'] = GetLang('Send_NumberSent_One');
				} else {
					$GLOBALS['Send_NumberAlreadySent'] = sprintf(GetLang('Send_NumberSent_Many'), $this->FormatNumber($num_left_to_send));
				}

				$send_api = $this->GetApi('Send');

				$job = $jobApi->LoadJob($jobid);

				$send_api->Set('statid', $send_api->LoadStats($jobid));

				$send_api->Set('jobdetails', $job['jobdetails']);
				$send_api->Set('jobowner', $job['ownerid']);
				$send_api->SetupJob($jobid, $sendqueue);

				$recipients = $send_api->FetchFromQueue($sendqueue, 'send', 1, 1);

				$send_api->SetupCustomFields($recipients);

				foreach ($recipients as $p => $recipientid) {
					$send_results = $send_api->SendToRecipient($recipientid, $sendqueue);

					// we should only need to pause if we successfully sent.
					if ($send_results['success'] > 0) {
						$send_api->Pause();
					}

					$send_details['EmailResults']['success'] += $send_results['success'];
					$send_details['EmailResults']['failure'] += $send_results['fail'];
					$send_details['EmailResults']['total']++;
				}

				$session->Set('SendDetails', $send_details);

				$GLOBALS['JobID'] = $jobid;

				$template = $this->ParseTemplate('Send_Step5', true);
				$template .= $this->PrintFooter(true, true);
				echo $template;
				exit();
			break;

			case 'step4':
				$send_details = $session->Get('SendDetails');

				$send_details['Multipart'] = (isset($_POST['sendmultipart'])) ? 1 : 0;
				$send_details['TrackOpens'] = (isset($_POST['trackopens'])) ? 1 : 0;
				$send_details['TrackLinks'] = (isset($_POST['tracklinks'])) ? 1 : 0;
				$send_details['EmbedImages'] = (isset($_POST['embedimages'])) ? 1 : 0;
				$send_details['Newsletter'] = $_POST['newsletter'];
				$send_details['SendFromName'] = $_POST['sendfromname'];
				$send_details['SendFromEmail'] = $_POST['sendfromemail'];
				$send_details['ReplyToEmail'] = (isset($_POST['replytoemail'])) ? $_POST['replytoemail'] : $send_details['SendFromEmail'];
				$send_details['BounceEmail'] = (isset($_POST['bounceemail'])) ? $_POST['bounceemail'] : $send_details['SendFromEmail'];

				$to_firstname = false;
				if (isset($_POST['to_firstname']) && (int)$_POST['to_firstname'] > 0) {
					$to_firstname = (int)$_POST['to_firstname'];
				}

				$send_details['To_FirstName'] = $to_firstname;

				$to_lastname = false;
				if (isset($_POST['to_lastname']) && (int)$_POST['to_lastname'] > 0) {
					$to_lastname = (int)$_POST['to_lastname'];
				}

				$send_details['To_LastName'] = $to_lastname;

				$send_details['Charset'] = SENDSTUDIO_CHARSET;

				$send_details['NotifyOwner'] = (isset($_POST['notifyowner'])) ? 1 : 0;

				$send_details['SendStartTime'] = AdjustTime(0, true, null, true);

				$send_details['EmailResults']['success'] = 0;
				$send_details['EmailResults']['total'] = 0;
				$send_details['EmailResults']['failure'] = array();

				$jobapi = $this->GetApi('Jobs');

				$scheduletime = AdjustTime(0, true, null, true);

				$statsapi = $this->GetApi('Stats');

				$session->Set('SendDetails', $send_details);

				$subscriber_count = $send_details['SendSize'];

				$approved = $user->Get('userid');

				if (SENDSTUDIO_CRON_ENABLED) {
					$sendtime = $_POST['sendtime'];

					/*
					* the sendtime is in this format:
					* hr:minAM
					* so we need to look at the character positions rather than exploding on the separator.
					*/
					$hr = substr($sendtime, 0, 2);
					$minute = substr($sendtime, 3, 2);
					$ampm = substr($sendtime, -2);

					if (strtolower($ampm) == 'pm') {
						if ($hr != 12) {
							$hr = $hr + 12;
						}
					}

					if (strtolower($ampm) == 'am' && $hr == 12) {
						$hr = 0;
					}

					if ($hr > 23) {
						$hr = $hr - 24;
					}

					$check_schedule_time = AdjustTime(array($hr, $minute, 0, (int)$_POST['datetime']['month'], (int)$_POST['datetime']['day'], (int)$_POST['datetime']['year']), true);

					$five_mins_ago = $statsapi->GetServerTime() - (5*60);

					if ($check_schedule_time < $five_mins_ago) {
						$this->SelectNewsletter('Send_Step4_CannotSendInPast');
						break;
					}

					$send_criteria = $send_details['SendCriteria'];

					$server_schedule_time = array($hr, $minute, 0, (int)$_POST['datetime']['month'], (int)$_POST['datetime']['day'], (int)$_POST['datetime']['year']);

					$scheduletime = AdjustTime($server_schedule_time, true);

					/**
					* Since we're using scheduled sending, we need to check user stats for when this is scheduled to send.
					*/
					$check_stats = $statsapi->CheckUserStats($user, $subscriber_count, $scheduletime);

					list($ok_to_send, $not_ok_to_send_reason) = $check_stats;

					if (!$ok_to_send) {
						$this->FilterRecipients($send_details['Lists'], GetLang($not_ok_to_send_reason));
						break;
					}

					$send_details['SendStartTime'] = $scheduletime;

					$approved = 0;
				}

				$jobcreated = $jobapi->Create('send', $scheduletime, $user->userid, $send_details, 'newsletter', $send_details['Newsletter'], $send_details['Lists'], $approved);

				/**
				* Record the user stats for this send.
				* We have to do it here so you can't schedule multiple sends and then it records everything.
				*/
				$statsapi->RecordUserStats($user->userid, $jobcreated, $subscriber_count, $scheduletime);

				$new_max = $user->Get('maxemails') - $subscriber_count;
				if ($new_max < 0) {
					$new_max = 0;
				}
				$user->ReduceEmails($subscriber_count);
				$user->Set('maxemails', $new_max);
				$session->Set('UserDetails', $user);

				// if we're not using scheduled sending, create the queue and start 'er up!
				if (!SENDSTUDIO_CRON_ENABLED) {
					$subscriberApi = $this->GetApi('Subscribers');

					$sendqueue = $subscriberApi->CreateQueue('Send');

					$jobapi->StartJob($jobcreated);

					$queuedok = $jobapi->JobQueue($jobcreated, $sendqueue);

					$send_criteria = $send_details['SendCriteria'];

					$queueinfo = array('queueid' => $sendqueue, 'queuetype' => 'send', 'ownerid' => $user->userid);

					$subscriberApi->GetSubscribers($send_criteria, array(), false, $queueinfo);

					$subscriberApi->RemoveDuplicatesInQueue($sendqueue, 'send');

					$subscriberApi->RemoveBannedEmails($send_details['Lists'], $sendqueue, 'send');

					$subscriberApi->RemoveUnsubscribedEmails($send_details['Lists'], $sendqueue, 'send');

					$subscriberApi->Db->OptimizeTable(SENDSTUDIO_TABLEPREFIX . "queues");

					$send_details['SendSize'] = $subscriberApi->QueueSize($sendqueue, 'send');

					$newsletterstats = $send_details;
					$newsletterstats['Queue'] = $sendqueue;
					$newsletterstats['SentBy'] = $queueinfo['ownerid'];

					$statid = $statsapi->SaveNewsletterStats($newsletterstats);

					$send_details['StatID'] = $statid;

					/**
					* So we can link user stats to send stats, we need to update it.
					*/
					$statsapi->UpdateUserStats($user->userid, $jobcreated, $statid);

					$session->Set('SendDetails', $send_details);

					$GLOBALS['JobID'] = $jobcreated;
				}

				$newslettername = '';
				$newsletterApi = $this->GetApi('Newsletters');
				$newsletterApi->Load($send_details['Newsletter']);
				$newslettername = $newsletterApi->Get('name');

				$listdetails = array();
				$listApi = $this->GetApi('Lists');
				foreach ($send_details['Lists'] as $l => $listid) {
					$listApi->Load($listid);
					$listdetails[] = $listApi->Get('name');
				}
				$listnames = implode(', ', $listdetails);

				$GLOBALS['Send_NewsletterName'] = sprintf(GetLang('Send_NewsletterName'), htmlspecialchars($newslettername, ENT_QUOTES, SENDSTUDIO_CHARSET));

				$GLOBALS['Send_SubscriberList'] = sprintf(GetLang('Send_SubscriberList'), htmlspecialchars($listnames, ENT_QUOTES, SENDSTUDIO_CHARSET));

				$last_sent_details = $newsletterApi->GetLastSent($send_details['Newsletter']);

				$last_sent = $last_sent_details['starttime'];
				if ($last_sent <= 0 && $send_details['SendSize'] > 5) {
					$GLOBALS['SentToTestListWarning'] = $this->PrintWarning('SendToTestListWarning') . '<br/>';
				}

				if (SENDSTUDIO_CRON_ENABLED) {
					$session->Set('ApproveJob', $jobcreated);
					$GLOBALS['Send_ScheduleTime'] = sprintf(GetLang('JobScheduled'), $this->PrintTime($scheduletime));
					$GLOBALS['Send_TotalRecipients'] = sprintf(GetLang('Send_TotalRecipients'), $this->FormatNumber($send_details['SendSize']));

					$this->ParseTemplate('Send_Step4_Cron');
					break;
				}

				$GLOBALS['Send_TotalRecipients'] = sprintf(GetLang('Send_TotalRecipients'), $this->FormatNumber($subscriberApi->QueueSize($sendqueue, 'send')));

				$this->ParseTemplate('Send_Step4');
			break;

			case 'step3':
				$send_details = $session->Get('SendDetails');
				$subscriberApi = $this->GetApi('Subscribers');

				$send_criteria = array();
				if (isset($_POST['emailaddress']) && $_POST['emailaddress'] != '') {
					$send_criteria['Email'] = $_POST['emailaddress'];
				}

				if (isset($_POST['format']) && $_POST['format'] != '-1') {
					$send_criteria['Format'] = $_POST['format'];
				}

				if (isset($_POST['confirmed']) && $_POST['confirmed'] != '-1') {
					$send_criteria['Confirmed'] = $_POST['confirmed'];
				}

				if (isset($_POST['datesearch']) && isset($_POST['datesearch']['filter'])) {
					$send_criteria['DateSearch'] = $_POST['datesearch'];

					$send_criteria['DateSearch']['StartDate'] = AdjustTime(array(0, 0, 1, $_POST['datesearch']['mm_start'], $_POST['datesearch']['dd_start'], $_POST['datesearch']['yy_start']));

					$send_criteria['DateSearch']['EndDate'] = AdjustTime(array(0, 0, 1, $_POST['datesearch']['mm_end'], $_POST['datesearch']['dd_end'], $_POST['datesearch']['yy_end']));
				}

				$customfields = array();
				if (isset($_POST['CustomFields']) && !empty($_POST['CustomFields'])) {
					$customfields = $_POST['CustomFields'];
				}

				if (isset($_POST['clickedlink']) && isset($_POST['linkid'])) {
					$send_criteria['Link'] = $_POST['linkid'];
				}

				if (isset($_POST['openednewsletter']) && isset($_POST['newsletterid'])) {
					$send_criteria['Newsletter'] = $_POST['newsletterid'];
				}

				$send_criteria['CustomFields'] = $customfields;

				$send_criteria['List'] = $send_details['Lists'];

				// can only send to active subscribers.
				$send_criteria['Status'] = 'a';

				$sortinfo = array();
				$subscriber_count = $subscriberApi->GetSubscribers($send_criteria, $sortinfo, true);

				if ($subscriber_count < 1) {
					$lists = $user->GetLists();
					$user_list_ids = array_keys($lists);
					$list_subscriber_count = 0;
					foreach ($send_details['Lists'] as $p => $listid) {
						if (in_array($listid, $user_list_ids)) {
							$list_subscriber_count += $lists[$listid]['subscribecount'];
							// we only need to keep going until we find a count > 0
							// as soon as we do, get out of this loop.
							if ($list_subscriber_count > 0) {
								break;
							}
						}
					}

					if ($list_subscriber_count == 0) {
						$GLOBALS['Error'] = GetLang('NoSubscribersOnList');
						$GLOBALS['Message'] = $this->ParseTemplate('ErrorMsg', true, false);
						$this->ChooseList('Send', 'step2', false);
					} else {
						$displaymsg = GetLang('NoSubscribersMatch');
						$this->FilterRecipients($send_details['Lists'], $displaymsg);
					}
					break;
				}

				$send_details['SendSize'] = $subscriber_count;
				$send_details['SendCriteria'] = $send_criteria;
				$session->Set('SendDetails', $send_details);

				/**
				* If we're not using scheduled sending, then we check the stats here.
				*/
				if (!SENDSTUDIO_CRON_ENABLED) {
					$stats_api = $this->GetApi('Stats');

					$check_stats = $stats_api->CheckUserStats($user, $subscriber_count, $stats_api->GetServerTime());

					list($ok_to_send, $not_ok_to_send_reason) = $check_stats;

					if (!$ok_to_send) {
						$GLOBALS['Error'] = GetLang($not_ok_to_send_reason);
						$GLOBALS['Message'] = $this->ParseTemplate('ErrorMsg', true, false);
						$this->FilterRecipients($send_details['Lists'], GetLang($not_ok_to_send_reason));
						break;
					}
				}

				$this->SelectNewsletter();
			break;

			case 'step2':
				$lists = array();
				if (isset($_POST['lists'])) {
					$lists = $_POST['lists'];
				}

				if (isset($_GET['list'])) {
					$lists = array((int)$_GET['list']);
				}

				$show_filtering_options = (isset($_POST['ShowFilteringOptions'])) ? 1 : 2;
				$user->SetSettings('ShowFilteringOptions', $show_filtering_options);

				$this->FilterRecipients($lists);
			break;

			case 'resumesend':
				$this->ResumeSend();
			break;

			default:
				$session->Remove('SendDetails');

				$id = (isset($_GET['id'])) ? (int)$_GET['id'] : 0;
				$senddetails['NewsletterChosen'] = $id;

				$session->Set('SendDetails', $senddetails);

				$newsletterapi = $this->GetApi('Newsletters');

				$newsletterowner = ($user->Admin()) ? 0 : $user->userid;

				$newsletters = $newsletterapi->GetLiveNewsletters($newsletterowner);
				if (empty($newsletters)) {
					$all_newsletters = $newsletterapi->GetNewsletters($newsletterowner, array(), true);
					if ($all_newsletters < 1) {
						if ($user->HasAccess('Newsletters', 'Create')) {
							$GLOBALS['Message'] = $this->PrintSuccess('NoNewsletters', GetLang('NoNewsletters_HasAccess'));

							$GLOBALS['Newsletters_AddButton'] = $this->ParseTemplate('Newsletter_Create_Button', true, false);

						} else {
							$GLOBALS['Message'] = $this->PrintSuccess('NoNewsletters', '');
						}
					} else {
						if ($user->HasAccess('Newsletters', 'Approve')) {
							$GLOBALS['Message'] = $this->PrintSuccess('NoLiveNewsletters', GetLang('NoLiveNewsletters_HasAccess'));
						} else {
							$GLOBALS['Message'] = $this->PrintSuccess('NoLiveNewsletters', '');
						}
					}
					$this->ParseTemplate('Newsletters_Send_Empty');
					break;
				}
				$this->ChooseList('Send', 'step2');
			break;
		}
		$this->PrintFooter($popup);
	}

	/**
	* FilterRecipients
	* Prints out the search forms to restrict the subscribers you want to send a newsletter to. This includes custom fields, format and so on.
	*
	* @param Array $listids An array of listid's the user is sending to, this is used to print a list of custom fields for more restrictive searching to be done.
	*
	* @see CheckListAccess
	* @see GetApi
	* @see Lists_API::Load
	* @see Lists_API::GetListFormat
	* @see Lists_API::GetCustomFields
	* @see Search_Display_CustomField
	* @see PrintSubscribeDate
	*
	* @return Void Doesn't return anything. Prints the search form and that's it.
	*/
	function FilterRecipients($listids=array(), $msg=false)
	{
		$session = &GetSession();
		$send_details = $session->Get('SendDetails');
		$send_details['Lists'] = $listids;
		$session->Set('SendDetails', $send_details);

		if ($msg) {
			$GLOBALS['Error'] = $msg;
			$GLOBALS['Message'] = $this->ParseTemplate('ErrorMsg', true, false);
		}

		$listApi = $this->GetApi('Lists');

		$all_customfields = array();

		$format_either  = '<option value="-1">' . GetLang('Either_Format') . '</option>';
		$format_html    = '<option value="h">' . GetLang('Format_HTML') . '</option>';
		$format_text    = '<option value="t">' . GetLang('Format_Text') . '</option>';

		$format_list = array('h' => $format_html, 't' => $format_text, 'b' => $format_either . $format_html . $format_text);

		$formats_found = array();
		$format = '';

		foreach ($listids as $listid) {
			$listApi->Load($listid);
			$listformat = $listApi->GetListFormat();
			if (!in_array($listformat, $formats_found)) {
				$formats_found[] = $listformat;
			}

			$customfields = $listApi->GetCustomFields($listid);
			if (!empty($customfields)) {
				$all_customfields[$listid] = $customfields;
			}
		}

		// if we only found one format, we only need to display the one option.
		// if there is more than one format, then we need to display the list. It doesn't matter what formats the list(s) support - there will always be one or both (text/html) available.
		if (sizeof($formats_found) == 1) {
			$f = array_pop($formats_found);
			$format = $format_list[$f];
		} else {
			$format = $format_list['b'];
		}
		$GLOBALS['FormatList'] = $format;

		$this->PrintSubscribeDate();

		$CustomFieldInfo = '';
		foreach ($all_customfields as $listid => $customfields) {
			if (!empty($customfields)) {
				if ($CustomFieldInfo == '') {
					$customfield_display = $this->ParseTemplate('Subscriber_Search_Step2_CustomFields', true, false);
				} else {
					$customfield_display = '';
				}
				foreach ($customfields as $pos => $customfield_info) {
					$manage_display = $this->Search_Display_CustomField($customfield_info);
					$customfield_display .= $manage_display;
				}
				$CustomFieldInfo .= $customfield_display;
			}
		}
		$GLOBALS['CustomFieldInfo'] = $CustomFieldInfo;
		$this->ParseTemplate('Send_Step2');

		$user = &GetUser();

		$user_lists = $user->GetLists();

		if (sizeof(array_keys($user_lists)) == 1) {
			return;
		}

		if (!$msg && $user->GetSettings('ShowFilteringOptions') == 2) {
			?>
			<script>
				document.forms[0].submit();
			</script>
			<?php
			exit();
		}

	}

	/**
	* SelectNewsletter
	* Displays a list of newsletters you can send.
	* Only gets live newsletters.
	* If cron scheduling is enabled, then you get extra options to choose from (whether to notify the owner and of course what time to send the newsletter).
	* You can also choose the character set for the send to use.
	*
	* @see GetSession
	* @see Session::Get
	* @see GetApi
	* @see Newsletters_API::GetLiveNewsletters
	* @see CreateDateTimeBox
	* @see CharsetList
	* @see SENDSTUDIO_CRON_ENABLED
	*
	* @return Void Doesn't return anything, prints out the step where you select the newsletter you want to send to your list(s).
	*/
	function SelectNewsletter($errormsg=false)
	{
		$session = &GetSession();
		$send_details = $session->Get('SendDetails');
		$user = $session->Get('UserDetails');
		$newsletterapi = $this->GetApi('Newsletters');

		$sendsize = $send_details['SendSize'];
		if ($sendsize == 1) {
			$sendSizeInfo = GetLang('SendSize_One');
		} else {
			$sendSizeInfo = sprintf(GetLang('SendSize_Many'), $this->FormatNumber($sendsize));
		}

		if (SENDSTUDIO_CRON_ENABLED) {
			$sendSizeInfo .= sprintf(' <a href="javascript:void(0)" onClick="alert(\'%s\')">%s</a>', GetLang('ReadMoreWhyApprox'), GetLang('ReadMore'));
		}

		$GLOBALS['Success'] = $sendSizeInfo;
		$GLOBALS['Message'] = $this->ParseTemplate('SuccessMsg', true, false);

		if ($errormsg) {
			$GLOBALS['Error'] = GetLang($errormsg);
			$GLOBALS['Message'] .= $this->ParseTemplate('ErrorMsg', true, false);
		}

		$newsletterowner = ($user->Admin()) ? 0 : $user->userid;

		$newsletters = $newsletterapi->GetLiveNewsletters($newsletterowner);
		$newsletterlist = '';
		$count = sizeof(array_keys($newsletters));
		$newsletterlist = '<option value="0">' . GetLang('SelectNewsletterToSend') . '</option>';

		foreach ($newsletters as $pos => $newsletterinfo) {
			$chosen = '';
			if ($newsletterinfo['newsletterid'] == $send_details['NewsletterChosen']) {
				$chosen = ' SELECTED';
			}
			$newsletterlist .= '<option value="' . $newsletterinfo['newsletterid'] . '"' . $chosen . '>' . htmlspecialchars($newsletterinfo['name'], ENT_QUOTES, SENDSTUDIO_CHARSET) . '</option>';
		}

		$list = $send_details['Lists'][0]; // always choose the first list. doesn't matter if there are multiple lists to choose from.
		$listapi = $this->GetApi('Lists');
		$listapi->Load($list);

		$customfields = $listapi->GetCustomFields($send_details['Lists']);
		if (empty($customfields)) {
			$GLOBALS['DisplayNameOptions'] = 'none';
		} else {
			$GLOBALS['NameOptions'] = '';
			foreach ($customfields as $p => $details) {
				$GLOBALS['NameOptions'] .= "<option value='" . $details['fieldid'] . "'>" . htmlspecialchars($details['name'], ENT_QUOTES, SENDSTUDIO_CHARSET) . "</option>";
			}
		}

		$GLOBALS['SendFromEmail'] = $listapi->Get('owneremail');
		$GLOBALS['SendFromName'] = $listapi->Get('ownername');
		$GLOBALS['ReplyToEmail'] = $listapi->Get('replytoemail');
		$GLOBALS['BounceEmail'] = $listapi->Get('bounceemail');

		$GLOBALS['ShowBounceInfo'] = 'none';

		if ($user->HasAccess('Lists', 'BounceSettings')) {
			$GLOBALS['ShowBounceInfo'] = '';
		}

		$GLOBALS['SendCharset'] = SENDSTUDIO_CHARSET;

		$GLOBALS['SendTimeBox'] = $this->CreateDateTimeBox(0, 'datetime', true);

		$GLOBALS['NewsletterList'] = $newsletterlist;

		$cron_options = '';
		if (SENDSTUDIO_CRON_ENABLED) {
			$cron_options = $this->ParseTemplate('Send_Step3_Cron', true);
		}
		$GLOBALS['CronOptions'] = $cron_options;
		$template = $this->ParseTemplate('Send_Step3');
	}

	/**
	* ResumeSend
	* Sets up the session information ready to send the newsletter again.
	*
	* @see GetSession
	* @see Session::Get
	* @see Session::Set
	* @see Session::Remove
	* @see GetApi
	* @see Jobs_API::LoadJob
	* @see API::QueueSize
	* @see API::LoadStats
	* @see Newsletters_API::Load
	* @see Lists_API::Load
	*
	* @return Void This doesn't return anything, it handles it all itself.
	*/
	function ResumeSend()
	{
		$job = (int)$_GET['Job'];
		$jobApi = $this->GetApi('Jobs');

		$session = &GetSession();
		$session->Remove('SendDetails');
		$jobinfo = $jobApi->LoadJob($job);
		$send_details = $jobinfo['jobdetails'];

		$GLOBALS['JobID'] = $job;

		$sendqueue = $jobinfo['queueid'];
		$queuesize = $jobApi->QueueSize($sendqueue, 'send');

		$statsid = $jobApi->LoadStats($job);

		$send_details['StatID'] = $statsid;

		$newslettername = '';
		$newsletterApi = $this->GetApi('Newsletters');
		$newsletterApi->Load($send_details['Newsletter']);
		$newslettername = $newsletterApi->Get('name');

		$listdetails = array();
		$listApi = $this->GetApi('Lists');
		foreach ($send_details['Lists'] as $l => $listid) {
			$listApi->Load($listid);
			$listdetails[] = $listApi->Get('name');
		}
		$listnames = implode(', ', $listdetails);

		$GLOBALS['Send_NewsletterName'] = sprintf(GetLang('Send_NewsletterName'), $newslettername);

		$GLOBALS['Send_SubscriberList'] = sprintf(GetLang('Send_SubscriberList'), $listnames);

		$GLOBALS['Send_TotalRecipients'] = sprintf(GetLang('Send_TotalRecipients'), $this->FormatNumber($jobApi->QueueSize($sendqueue, 'send')));

		$session->Set('SendDetails', $send_details);

		$this->ParseTemplate('Send_Step4');
	}
}
?>
