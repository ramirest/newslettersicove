<?php
/**
* This file has the sending page in it. This only handles sending and scheduling of newsletters.
*
* @version     $Id: schedule.php,v 1.32 2007/05/30 02:51:25 chris Exp $

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
class Schedule extends SendStudio_Functions
{

	/**
	* Constructor
	* Loads the schedule, newsletters and send language files.
	*
	* @see LoadLanguageFile
	*
	* @return Void Doesn't return anything.
	*/
	function Schedule()
	{
		$this->LoadLanguageFile();
		$this->LoadLanguageFile('Newsletters');
		$this->LoadLanguageFile('Send');
	}

	/**
	* Process
	* This handles working out what stage you are up to and so on with workflow.
	* Handles editing of schedules, pausing, resuming and deleting of schedules.
	* Deleting a scheduled event (especially) needs to update statistics if there are any emails left over in the queue.
	*
	* @see GetUser
	* @see User_API::HasAccess
	* @see SENDSTUDIO_CRON_ENABLED
	* @see GetSession
	* @see GetApi
	* @see Jobs_API::PauseJob
	* @see Jobs_API::ResumeJob
	* @see Jobs_API::LoadJob
	* @see ManageSchedules
	* @see CheckJob
	* @see AdjustTime
	*/
	function Process()
	{
		$action = (isset($_GET['Action'])) ? strtolower(urldecode($_GET['Action'])) : null;
		$user = &GetUser();
		$access = $user->HasAccess('Newsletters', 'Send');

		$popup = (in_array($action, $this->PopupWindows)) ? true : false;
		$this->PrintHeader($popup);

		if (!$access) {
			$this->DenyAccess();
			return;
		}

		if (!SENDSTUDIO_CRON_ENABLED) {
			$GLOBALS['Error'] = GetLang('CronNotEnabled');
			$this->ParseTemplate('ErrorMsg');
			$this->PrintFooter();
			return;
		}

		if ($action == 'processpaging') {
			$perpage = (int)$_GET['PerPageDisplay'];
			$display_settings = array('NumberToShow' => $perpage);
			$user->SetSettings('DisplaySettings', $display_settings);
			$action = '';
		}

		$session = &GetSession();

		$jobapi = $this->GetApi('Jobs');

		$approve_job = $session->Get('ApproveJob');
		if ($approve_job) {
			if (isset($_GET['A'])) {
				$jobapi->ApproveJob($approve_job, $user->Get('userid'));
				$GLOBALS['Message'] = $this->PrintSuccess('JobScheduledOK');
				$session->Remove('ApproveJob');
			}
		}

		switch ($action) {
			case 'pause':
				$job = (isset($_GET['job'])) ? (int)$_GET['job'] : 0;
				$result = $jobapi->PauseJob($job);
				if ($result) {
					$GLOBALS['Message'] = $this->PrintSuccess('JobPausedSuccess');
				} else {
					$GLOBALS['Error'] = GetLang('JobPausedFail');
					$GLOBALS['Message'] = $this->ParseTemplate('ErrorMsg', true, false);
				}
				$this->ManageSchedules();
			break;

			case 'resume':
				$job = (isset($_GET['job'])) ? (int)$_GET['job'] : 0;
				$result = $jobapi->ResumeJob($job);
				if ($result) {
					$GLOBALS['Message'] = $this->PrintSuccess('JobResumedSuccess');
				} else {
					$GLOBALS['Error'] = GetLang('JobResumedFail');
					$GLOBALS['Message'] = $this->ParseTemplate('ErrorMsg', true, false);
				}
				$this->ManageSchedules();
			break;

			case 'approve':
				$job = (isset($_GET['job'])) ? (int)$_GET['job'] : 0;
				if (!$user->Admin()) {
					$GLOBALS['Error'] = GetLang('JobApprovedFail_NotAdmin');
					$GLOBALS['Message'] = $this->ParseTemplate('ErrorMsg', true, false);
				} else {
					$result = $jobapi->ApproveJob($job, $user->Get('userid'), $user->Get('userid'));
					if ($result) {
						$GLOBALS['Message'] = $this->PrintSuccess('JobApprovedSuccess');
					} else {
						$GLOBALS['Error'] = GetLang('JobApprovedFail');
						$GLOBALS['Message'] = $this->ParseTemplate('ErrorMsg', true, false);
					}
				}
				$this->ManageSchedules();

			break;

			case 'delete':
				$job = (isset($_GET['job'])) ? (int)$_GET['job'] : 0;

				$multi_jobs = array();

				if (isset($_POST['jobs'])) {
					$multi_jobs = $_POST['jobs'];
				} else {
					$multi_jobs[] = $job;
				}

				$multi_jobs = $jobapi->CheckIntVars($multi_jobs);

				$stats_api = $this->GetApi('Stats');

				// in case the schedule is for multiple lists, we only want the unique job ids.
				$multi_jobs = array_unique($multi_jobs);

				$reload_user = false;

				$success = 0; $fail = 0;
				foreach ($multi_jobs as $p => $jobid) {
					$in_progress = $jobapi->JobStarted($jobid);
					if ($in_progress) {
						$fail++;
						continue;
					}

					$jobinfo = $jobapi->LoadJob($jobid);

					if ($jobinfo['ownerid'] == $user->Get('userid')) {
						// so max-emails can be reloaded into the session
						$reload_user = true;
					}

					$statid = $jobapi->LoadStats($jobid);

					$result = $jobapi->Delete($jobid);

					if ($result) {
						// if a send was started but not completed, then deleted, mark it as complete.
						if ($statid > 0 && $jobinfo['jobstatus'] != 'c') {
							$stats_api->MarkNewsletterFinished($statid, $jobinfo['jobdetails']['SendSize'], false);
						}
						$success++;
					} else {
						$fail++;
					}
				}
				$msg = '';
				if ($success > 0) {
					if ($success == 1) {
						$msg .= $this->PrintSuccess('JobDeleteSuccess');
					} else {
						$msg .= $this->PrintSuccess('JobsDeleteSuccess', $success);
					}
					$stats_api->Db->OptimizeTable(SENDSTUDIO_TABLEPREFIX . "queues");
				}
				if ($fail > 0) {
					if ($fail == 1) {
						$GLOBALS['Error'] = GetLang('JobDeleteFail');
						$msg .= $this->ParseTemplate('ErrorMsg', true, false);
					} else {
						$GLOBALS['Error'] = sprintf(GetLang('JobsDeleteFail'), $fail);
						$msg .= $this->ParseTemplate('ErrorMsg', true, false);
					}
				}

				if ($reload_user) {
					$myuser = &GetUser($user->Get('userid'));
					$session->Set('UserDetails', $myuser);
				}

				$GLOBALS['Message'] = $msg;
				$this->ManageSchedules();
			break;

			case 'edit':
				$job = (isset($_GET['job'])) ? (int)$_GET['job'] : 0;
				$this->EditJob($job);
			break;

			case 'update':
				$jobid = (isset($_GET['job'])) ? (int)$_GET['job'] : 0;

				$check = $this->CheckJob($jobid);
				if (!$check) {
					break;
				}

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

				$scheduletime = AdjustTime(array($hr, $minute, 0, (int)$_POST['datetime']['month'], (int)$_POST['datetime']['day'], (int)$_POST['datetime']['year']), true);

				$jobinfo = $jobapi->LoadJob($jobid);

				// see if they haev changed the send time. If they have, then we need to check stats again to make sure it's ok to do that.
				if ($jobinfo['jobtime'] != $scheduletime) {

					$now = $jobapi->GetServerTime();

					// you are trying to schedule before now (well, 5 minutes ago), then don't allow it.
					if ($scheduletime < ($now - (5 * 60))) {
						$GLOBALS['Error'] = GetLang('Send_Step4_CannotSendInPast');
						$GLOBALS['Message'] = $this->ParseTemplate('ErrorMsg', true, false);
						$this->EditJob($jobid);
						break;
					}

					if ($jobinfo['queueid'] > 0) {
						$GLOBALS['Error'] = GetLang('CannotChangeAScheduleOnceItHasStarted');
						$GLOBALS['Message'] = $this->ParseTemplate('ErrorMsg', true, false);
						$this->EditJob($jobid);
						break;
					}

					$schedulemonth = AdjustTime(array(0,0,1,date('m', $scheduletime),1,date('Y', $scheduletime)), true);

					$original_schedulemonth = AdjustTime(array(0,0,1, date('m', $jobinfo['jobtime']), 1, date('Y', $jobinfo['jobtime'])), true);

					if ($schedulemonth != $original_schedulemonth) {

						$statsapi = $this->GetApi('Stats');

						/**
						* We need to check user stats for when this is scheduled to send.
						*/
						$check_stats = $statsapi->CheckUserStats($user, $jobinfo['jobdetails']['SendSize'], $scheduletime, true);

						list($ok_to_send, $not_ok_to_send_reason) = $check_stats;

						if (!$ok_to_send) {
							$GLOBALS['Error'] = GetLang($not_ok_to_send_reason);
							$GLOBALS['Message'] = $this->ParseTemplate('ErrorMsg', true, false);
							$this->EditJob($jobid);
							break;
						}

						$new_size = $jobinfo['jobdetails']['SendSize'];

						$statsapi->DeleteUserStats($user->userid, $jobid);

						$statsapi->RecordUserStats($user->userid, $jobid, $new_size, $scheduletime);
					}
				}

				$result = $jobapi->UpdateTime($jobid, $scheduletime);
				if ($result) {
					$GLOBALS['Message'] = $this->PrintSuccess('JobScheduled', $this->PrintTime($scheduletime));
				} else {
					$GLOBALS['Error'] = sprintf(GetLang('JobNotScheduled'), $this->PrintTime($scheduletime));
					$GLOBALS['Message'] = $this->ParseTemplate('ErrorMsg', true, false);
				}
				$this->ManageSchedules();
			break;

			default:
				$this->ManageSchedules();
		}
		$this->PrintFooter($popup);
	}

	/**
	* ManageSchedules
	* Prints a list of scheduled events (only newsletters at this stage but could be expanded).
	* This allows you to edit, delete, pause and resume events.
	*
	* @see GetSession
	* @see GetUser
	* @see GetApi
	* @see GetPerPage
	* @see GetSortDetails
	* @see User_API::GetLists
	* @see User_API::HasAccess
	* @see Jobs_API::GetJobList
	* @see SetupPaging
	* @see Jobs_API::GetJobStatus
	* @see PrintDate
	*/
	function ManageSchedules()
	{
		$session = &GetSession();
		$user = &GetUser();
		$jobsApi = $this->GetApi('Jobs');

		$settingsApi = $this->GetApi('Settings');
		$cron_check = $settingsApi->CheckCron();

		$cron_run1 = $settingsApi->Get('cronrun1');
		$cron_run2 = $settingsApi->Get('cronrun2');

		if ($cron_run1 > 0 && $cron_run2 > 0) {
			$cron_diff = $cron_run2 - $cron_run1;
			$next_run = $cron_run2 + $cron_diff;
			$now = $jobsApi->GetServerTime();
			$time_to_wait = $next_run - $now;
			$update_cron_time = 'true';
		} else {
			$time_to_wait = GetLang('Unknown');
			$cron_diff = 0;
			$next_run = 0;
			$update_cron_time = 'false';
		}

		$this->DisplayCronWarning(false);

		$GLOBALS['UpdateCronTime'] = $update_cron_time;
		$GLOBALS['CronTimeLeft'] = $time_to_wait;
		$GLOBALS['CronTimeDifference'] = $cron_diff;

		$perpage = $this->GetPerPage();

		$DisplayPage = (isset($_GET['DisplayPage'])) ? (int)$_GET['DisplayPage'] : 1;
		$start = ($DisplayPage - 1) * $perpage;

		$lists = $user->GetLists();

		$listids = array_keys($lists);

		if ($user->HasAccess('Newsletters', 'Send')) {
			$GLOBALS['Newsletters_SendButton'] = $this->ParseTemplate('Newsletter_Send_Button', true, false);
		}

		$include_unapproved = false;
		if ($user->Admin()) {
			$include_unapproved = true;
		}

		$numJobs = $jobsApi->GetJobList('send', 'newsletter', $listids, true, 0, 0, $include_unapproved);

		if ($numJobs < 1) {
			if ($user->HasAccess('Newsletters', 'Send')) {
				$msg = $this->PrintSuccess('Schedule_Empty', GetLang('NoSchedules_HasAccess'));
			} else {
				$msg = $this->PrintSuccess('Schedule_Empty', '');
			}
			if (!isset($GLOBALS['Message'])) {
				$GLOBALS['Message'] = '';
			}
			$GLOBALS['Message'] .= $msg;
			echo $this->ParseTemplate('Schedule_Manage_Row_Empty', true, false);
			return;
		}

		$jobs = $jobsApi->GetJobList('send', 'newsletter', $listids, false, $start, $perpage, $include_unapproved);

		$this->SetupPaging($numJobs, $DisplayPage, $perpage);
		$GLOBALS['FormAction'] = 'Action=ProcessPaging';
		$paging = $this->ParseTemplate('Paging', true, false);

		$manage_row = '';

		$timenow = AdjustTime();

		$rid = 0;

		foreach ($jobs as $p => $details) {
			$rid++;
			$GLOBALS['JobID'] = (int)$details['jobid'];
			$GLOBALS['Status'] = $jobsApi->GetJobStatus($details['jobstatus']);
			$GLOBALS['JobTime'] = $this->PrintTime($details['jobtime'], true);
			$GLOBALS['ListName'] = htmlspecialchars($details['listname'], ENT_QUOTES, SENDSTUDIO_CHARSET);
			$GLOBALS['NewsletterName'] = htmlspecialchars($details['name'], ENT_QUOTES, SENDSTUDIO_CHARSET);

			$action = '';

			$GLOBALS['RowID'] = $rid;

			$GLOBALS['RefreshLink'] = $GLOBALS['AlreadySent'] = '';
			if ($details['jobstatus'] == 'w') {

				if ($user->Admin() && $details['approved'] <= 0) {
					$GLOBALS['TipName'] = $this->GetRandomId();
					$GLOBALS['ScheduleTip'] = GetLang('WaitingForApproval_Description');

					$need_approval_tip = $this->ParseTemplate('Schedule_Needs_Approval_Tip', true, false);
					$GLOBALS['Status'] = $need_approval_tip;

					$action .= $this->ParseTemplate('Schedule_Manage_Row_ApproveLink', true, false);

					$action .= $this->ParseTemplate('Schedule_Manage_Row_DeleteLink', true, false);

					$GLOBALS['Action'] = $action;

					$manage_row .= $this->ParseTemplate('Schedule_Manage_Row', true, false);
					continue;
				}

				if ($next_run > 0) {
					$send_in_time_difference = $details['jobtime'] - AdjustTime();

					// if we're below 0, that means we should send next time cron runs.
					if ($send_in_time_difference < 0) {
						$send_in_time_difference = $time_to_wait;
					}

					if ($next_run > 0 && $details['jobtime'] <= $next_run) {
						$send_in_time_difference = $next_run - AdjustTime();
					}

					if ($send_in_time_difference < 0) {
						$send_in_time_difference = $cron_diff;
					}

					if ($send_in_time_difference > 0) {
						$have_refreshed = (isset($_GET['R'])) ? 1 : 0;

						$GLOBALS['RefreshLink'] = "<script>UpdateMyTimer('" . $GLOBALS['JobID'] . "_" . $rid . "', " . (int)$send_in_time_difference . ", " . $have_refreshed . ");</script>";
					} else {
						$GLOBALS['Status'] = GetLang('WaitingToSend');
					}
				} else {
					$GLOBALS['Status'] = GetLang('WaitingToSend');
				}
			}

			if ($details['jobstatus'] == 'i') {
				if ($user->Admin() && $details['approved'] <= 0) {
					$GLOBALS['TipName'] = $this->GetRandomId();
					$GLOBALS['ScheduleTip'] = GetLang('WaitingForApproval_Description');

					$need_approval_tip = $this->ParseTemplate('Schedule_Needs_Approval_Tip', true, false);
					$GLOBALS['Status'] = $need_approval_tip;

					$action .= $this->ParseTemplate('Schedule_Manage_Row_ApproveLink', true, false);

					$action .= $this->ParseTemplate('Schedule_Manage_Row_DeleteLink', true, false);

					$GLOBALS['Action'] = $action;

					$manage_row .= $this->ParseTemplate('Schedule_Manage_Row', true, false);
					continue;
				}

				$queueinfo = $jobsApi->GetJobQueueSize($details['jobid']);
				if (!empty($queueinfo)) {
					$GLOBALS['AlreadySent'] = sprintf(GetLang('AlreadySentTo'), $this->FormatNumber($queueinfo['totalsent']), $this->FormatNumber($queueinfo['sendsize']));
				}
				$GLOBALS['RefreshDisplayPage'] = $DisplayPage;
				$action .= $this->ParseTemplate('Schedule_Manage_Row_RefreshLink', true, false);
			}

			// if the job is paused, we can resume it.
			if ($details['jobstatus'] == 'p') {
				$action .= $this->ParseTemplate('Schedule_Manage_Row_ResumeLink', true, false);
			}

			// if the job is in progress we can pause it.
			// or if it's in progress.
			// that will allow us to delay it without losing all of the info....
			if ($details['jobstatus'] == 'i' || $details['jobstatus'] == 'w') {
				$action .= $this->ParseTemplate('Schedule_Manage_Row_PauseLink', true, false);
			}

			// if it's not in progress, we can edit or delete the scheduled event.
			// but only if it has not started yet (the queueid will be > 0 for started events).
			if ($details['jobstatus'] != 'i') {
				if ($details['queueid'] == 0 && $details['jobstatus'] != 'c') {
					$action .= $this->ParseTemplate('Schedule_Manage_Row_EditLink', true, false);
				}
				$action .= $this->ParseTemplate('Schedule_Manage_Row_DeleteLink', true, false);
			}

			$GLOBALS['Action'] = $action;

			$manage_row .= $this->ParseTemplate('Schedule_Manage_Row', true, false);
		}

		$template = $this->ParseTemplate('Schedule_Manage', true, false);
		$template = str_replace('%%TPL_Schedule_Manage_Row%%', $manage_row, $template);

		$template = str_replace('%%TPL_Paging%%', $paging, $template);
		$template = str_replace('%%TPL_Paging_Bottom%%', $GLOBALS['PagingBottom'], $template);

		echo $template;
	}

	/**
	* EditJob
	* Allows you to edit a job's send time and view other details (which list(s), newsletter).
	*
	* @param Int $jobid JobId to edit.
	*
	* @see CheckJob
	* @see GetApi
	* @see Jobs_API::Load
	* @see GetUser
	* @see Newsletters_API::Load
	* @see Lists_API::Load
	* @see CreateDateTimeBox
	*
	* @return Void Prints out the form for editing, doesn't return anything.
	*/
	function EditJob($jobid=0)
	{
		$check = $this->CheckJob($jobid);
		if (!$check) {
			return false;
		}

		$api = $this->GetApi('Jobs');
		$job = $api->LoadJob($jobid);

		$user = &GetUser();

		$newslettername = '';
		$newsletterApi = $this->GetApi('Newsletters');
		$newsletterApi->Load($job['jobdetails']['Newsletter']);
		$newslettername = $newsletterApi->Get('name');

		$GLOBALS['JobID'] = $jobid;

		$listdetails = array();
		$listApi = $this->GetApi('Lists');
		foreach ($job['jobdetails']['Lists'] as $l => $listid) {
			$listApi->Load($listid);
			$listdetails[] = $listApi->Get('name');
		}
		$listnames = implode(', ', $listdetails);

		$GLOBALS['NewsletterID'] = $newsletterApi->Get('newsletterid');
		$GLOBALS['Send_NewsletterName'] = sprintf(GetLang('Send_NewsletterName'), $newslettername);

		$GLOBALS['Send_SubscriberList'] = sprintf(GetLang('Send_SubscriberList'), $listnames);

		$GLOBALS['SendTimeBox'] = $this->CreateDateTimeBox($job['jobtime']);

		$this->ParseTemplate('Schedule_Edit');
	}

	/**
	* CheckJob
	* Makes sure the job you're trying to access is valid. It also makes sure it's not in progress.
	*
	* @param Int $jobid The job to check.
	*
	* @see ManageSchedules
	* @see GetApi
	* @see Jobs_API::LoadJob
	*
	* @return Boolean Returns false if there is a problem with the job (it's not valid or we can't load it). It also returns false if the job is in progress - it will print a message out and then print the schedule list.
	*/
	function CheckJob($jobid=0)
	{
		$jobid = (int)$jobid;
		if ($jobid <= 0) {
			$GLOBALS['Error'] = GetLang('UnableToLoadJob');
			$GLOBALS['Message'] = $this->ParseTemplate('ErrorMsg', true, false);
			$this->ManageSchedules();
			return false;
		}
		$api = $this->GetApi('Jobs');
		$job = $api->LoadJob($jobid);

		if (empty($job)) {
			$GLOBALS['Error'] = GetLang('UnableToLoadJob');
			$GLOBALS['Message'] = $this->ParseTemplate('ErrorMsg', true, false);
			$this->ManageSchedules();
			return false;
		}

		if ($job['jobstatus'] == 'i') {
			$GLOBALS['Error'] = GetLang('UnableToEditJob_InProgress');
			$GLOBALS['Message'] = $this->ParseTemplate('ErrorMsg', true, false);
			$this->ManageSchedules();
			return false;
		}
		return true;
	}

}
?>
