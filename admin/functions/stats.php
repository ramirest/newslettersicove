<?php
/**
* This file has the base subscriber functions in it. Each subprocess is handled separately.
*
* @version     $Id: stats.php,v 1.60 2007/06/04 07:01:10 scott Exp $

*
* @package SendStudio
* @subpackage SendStudio_Functions
*/

/**
* Include the base sendstudio functions.
*/
require(dirname(__FILE__) . '/sendstudio_functions.php');

/**
* Base class for subscribers processing. This simply hands the processing to subareas (eg adding, banning, exporting and so on).
*
* @package SendStudio
* @subpackage SendStudio_Functions
*/
class Stats extends SendStudio_Functions
{

	/**
	* Default sort.
	*
	* @var _DefaultSort
	*/
	var $_DefaultSort = 'finishtime';

	/**
	* Default direction for sorting.
	*
	* @var _DefaultDirection
	*/
	var $_DefaultDirection = 'down';

	/**
	* @var CalendarRestrictions Store the calendar restrictions in the class - easy reference.
	*
	* @see CalculateCalendarRestrictions
	*/
	var $CalendarRestrictions = array('opens' => '', 'clicks' => '', 'forwards' => '', 'bounces' => '', 'unsubscribes' => '', 'subscribers' => array('subscribes' => '', 'unsubscribes' => '', 'bounces' => ''));

	/**
	* Constructor
	* Loads the language file.
	*
	* @return Void Doesn't return anything.
	*/
	function Stats()
	{
		$this->LoadLanguageFile();
	}

	/**
	* Process
	* Standard process function. Works out what you're trying to do and passes action off to other functions.
	*
	* @return Void Doesn't return anything. Hands control off to other functions.
	*/
	function Process()
	{
		$action = (isset($_GET['Action'])) ? strtolower(urldecode($_GET['Action'])) : null;
		$user = &GetUser();
		$access = $user->HasAccess('Statistics');

		$subaction = (isset($_GET['SubAction'])) ? strtolower($_GET['SubAction']) : null;

		$popup = ($action == 'print') ? true : false;
		$this->PrintHeader($popup);

		if (!$access) {
			$this->DenyAccess();
			return;
		}

		$session = &GetSession();

		foreach (array('lc', 'uc', 'oc', 'bc', 'fc', '') as $k => $area) {
			if ($action == 'processpaging'.$area) {
				$perpage = (int)$_GET['PerPageDisplay'.$area];
				$display_settings = array('NumberToShow' => $perpage);
				$user->SetSettings('DisplaySettings', $display_settings);
				$action = $subaction;
				if (isset($_GET['NextAction'])) {
					$subaction = strtolower($_GET['NextAction']);
				}
				break;
			}
		}

		if ($action == 'processcalendar') {
			if (isset($_POST['Calendar'])) {
				$calendar_settings = $_POST['Calendar'];
				$user->SetSettings('Calendar', $calendar_settings);
			}

			$action = $subaction;
			if (isset($_GET['NextAction'])) {
				$subaction = strtolower($_GET['NextAction']);
			}
		}

		$this->CalculateCalendarRestrictions();
		$user->SetSettings('CalendarDates', $this->CalendarRestrictions);

		switch ($action) {
			case 'list':
				switch ($subaction) {
					case 'step2':
					case 'viewsummary':
						$listid = 0;
						if (isset($_GET['list'])) {
							$listid = (int)$_GET['list'];
						}

						$this->PrintListStats_Step2($listid);
					break;

					default:
						// if they have changed paging, we'll have a 'default' action but the userid will still be in the url.
						if (isset($_GET['list'])) {
							$this->PrintListStats_Step2($_GET['list']);
							break;
						}

						$session->Remove('ListStatistics');
						$this->PrintListStats_Step1();
				}
			break;

			case 'user':

				$session->Remove('ListStatistics');

				switch ($subaction) {
					case 'step2':
						$userid = 0;
						if (isset($_GET['user'])) {
							$userid = (int)$_GET['user'];
						}
						$this->PrintUserStats_Step2($userid);
					break;

					default:
						// if they have changed paging, we'll have a 'default' action but the userid will still be in the url.
						if (isset($_GET['user'])) {
							$this->PrintUserStats_Step2($_GET['user']);
							break;
						}
						$this->PrintUserStats_Step1();
				}
			break;

			case 'autoresponders':
				$session->Remove('ListStatistics');

				$this->LoadLanguageFile('Autoresponders');
				switch ($subaction) {
					case 'delete':
						$stats_to_delete = array();
						if (isset($_POST['stats']) && !empty($_POST['stats'])) {
							$stats_to_delete = $_POST['stats'];
						}

						if (isset($_GET['id'])) {
							$stats_to_delete[] = (int)$_GET['id'];
						}

						if (empty($stats_to_delete)) {
							$this->PrintAutoresponderStats_Step1();
							break;
						}

						$stats_api = $this->GetApi('Stats');
						$success = 0;
						$failure = 0;
						$cant_delete = 0;
						foreach ($stats_to_delete as $p => $statid) {
							if (!$statid) {
								$cant_delete++;
								continue;
							}
							$delete = $stats_api->HideStats($statid, 'autoresponder', $user->Get('userid'));
							if ($delete) {
								$success++;
							} else {
								$failure++;
							}
						}

						$msg = '';

						if ($failure > 0) {
							if ($failure == 1) {
								$GLOBALS['Error'] = GetLang('StatisticsDeleteFail_One');
							} else {
								$GLOBALS['Error'] = sprintf(GetLang('StatisticsDeleteFail_One'), $this->FormatNumber($failure));
							}
							$msg .= $this->ParseTemplate('ErrorMsg', true, false);
						}

						if ($success > 0) {
							if ($success == 1) {
								$msg .= $this->PrintSuccess('StatisticsDeleteSuccess_One');
							} else {
								$msg .= $this->PrintSuccess('StatisticsDeleteSuccess_Many', $this->FormatNumber($success));
							}
						}

						if ($cant_delete > 0) {
							if ($cant_delete == 1) {
								$msg .= $this->PrintSuccess('StatisticsDeleteNoStatistics_One');
							} else {
								$msg .= $this->PrintSuccess('StatisticsDeleteNoStatistics_Many', $this->FormatNumber($cant_delete));
							}
						}

						$GLOBALS['Message'] = $msg;
						$this->PrintAutoresponderStats_Step1();
					break;

					case 'step2':
					case 'viewsummary':
						$autoid = 0;
						if (isset($_GET['auto'])) {
							$autoid = (int)$_GET['auto'];
						}

						$this->PrintAutoresponderStats_Step2($autoid);
					break;

					default:
						$this->PrintAutoresponderStats_Step1();
				}
			break;

			default:
				$session->Remove('ListStatistics');
				switch ($subaction) {
					case 'delete':
						$stats_to_delete = array();
						if (isset($_POST['stats']) && !empty($_POST['stats'])) {
							$stats_to_delete = $_POST['stats'];
						}

						if (isset($_GET['id'])) {
							$stats_to_delete[] = (int)$_GET['id'];
						}

						if (empty($stats_to_delete)) {
							$this->PrintNewsletterStats_Step1();
						}

						$stats_api = $this->GetApi('Stats');
						$success = 0;
						$failure = 0;
						$cant_delete = 0;

						foreach ($stats_to_delete as $p => $statid) {
							$finished = $stats_api->IsFinished($statid, 'newsletter');
							if (!$finished) {
								$cant_delete++;
								continue;
							}
							$delete = $stats_api->HideStats($statid, 'newsletter', $user->Get('userid'));
							if ($delete) {
								$success++;
							} else {
								$failure++;
							}
						}

						$msg = '';

						if ($failure > 0) {
							if ($failure == 1) {
								$GLOBALS['Error'] = GetLang('StatisticsDeleteFail_One');
							} else {
								$GLOBALS['Error'] = sprintf(GetLang('StatisticsDeleteFail_One'), $this->FormatNumber($failure));
							}
							$msg .= $this->ParseTemplate('ErrorMsg', true, false);
						}

						if ($success > 0) {
							if ($success == 1) {
								$msg .= $this->PrintSuccess('StatisticsDeleteSuccess_One');
							} else {
								$msg .= $this->PrintSuccess('StatisticsDeleteSuccess_Many', $this->FormatNumber($success));
							}
						}

						if ($cant_delete > 0) {
							if ($cant_delete == 1) {
								$msg .= $this->PrintSuccess('StatisticsDeleteNotFinished_One');
							} else {
								$msg .= $this->PrintSuccess('StatisticsDeleteNotFinished_Many', $this->FormatNumber($cant_delete));
							}
						}

						$GLOBALS['Message'] = $msg;
						$this->PrintNewsletterStats_Step1();
					break;

					case 'viewsummary':
						$statid = 0;
						if (isset($_GET['id'])) {
							$statid = (int)$_GET['id'];
						}
						$this->PrintNewsletterStats_Step2($statid);
					break;

					default:
						$this->PrintNewsletterStats_Step1();
				}
		}
		$this->PrintFooter($popup);
	}


	/**
	* PrintNewsletterStats_Step1
	* This will show a list of newsletters that have been sent out according to which lists the user has access to.
	*
	* @see GetUser
	* @see User_API::GetLists
	* @see Stats_API::GetNewsletterStats
	*
	* @return Void Doesn't return anything. Prints out a list of the newsletters sent to the lists that the user has access to.
	*/
	function PrintNewsletterStats_Step1()
	{
		$user = &GetUser();
		$statsapi = $this->GetApi();

		$this->LoadLanguageFile('Newsletters');

		$perpage = $this->GetPerPage();

		$DisplayPage = (isset($_GET['DisplayPage'])) ? (int)$_GET['DisplayPage'] : 1;
		$start = ($DisplayPage - 1) * $perpage;

		$sortinfo = $this->GetSortDetails();

		$lists = $user->GetLists();
		$listids = array_keys($lists);

		$NumberOfStats = $statsapi->GetNewsletterStats($listids, $sortinfo, true, 0, 0);
		$mystats = $statsapi->GetNewsletterStats($listids, $sortinfo, false, $start, $perpage);

		if (!isset($GLOBALS['Message'])) {
			$GLOBALS['Message'] = '';
		}

		if ($NumberOfStats == 0) {
			$GLOBALS['Message'] .= $this->PrintSuccess('NoNewslettersHaveBeenSent');

			if ($user->HasAccess('Newsletters', 'Send')) {
				$GLOBALS['Newsletters_SendButton'] = $this->ParseTemplate('Newsletter_Send_Button', true, false);
			}
			$this->ParseTemplate('Stats_Newsletters_Empty');
			return;
		}

		$this->LoadLanguageFile('Lists');

		$GLOBALS['FormAction'] = 'Action=ProcessPaging&SubAction=Newsletters&NextAction=Step1';

		$GLOBALS['PAGE'] = 'Stats&Action=Newsletters&SubAction=Step1';

		$this->SetupPaging($NumberOfStats, $DisplayPage, $perpage);

		$paging = $this->ParseTemplate('Paging', true, false);

		$stats_manage = $this->ParseTemplate('Stats_Newsletter_Manage', true, false);

		$statsdisplay = '';

		foreach ($mystats as $pos => $statsdetails) {
			$GLOBALS['StatID'] = $statsdetails['statid'];
			$GLOBALS['Newsletter'] = htmlspecialchars($statsdetails['newslettername'], ENT_QUOTES, SENDSTUDIO_CHARSET);
			$GLOBALS['MailingList'] = htmlspecialchars($statsdetails['listname'], ENT_QUOTES, SENDSTUDIO_CHARSET);
			$GLOBALS['StartDate'] = $this->PrintTime($statsdetails['starttime'], true);

			$GLOBALS['StatsAction'] = '<a href="index.php?Page=Stats&Action=Newsletters&SubAction=ViewSummary&id=' . $statsdetails['statid'] . '">' . GetLang('ViewSummary') . '</a>&nbsp;&nbsp;';

			$finishtime = $statsdetails['finishtime'];
			if ($finishtime > 0) {
				$GLOBALS['StatsAction'] .= '<a href="javascript: ConfirmDelete(' . $statsdetails['statid'] . ');">' . GetLang('Delete') . '</a>';
				$GLOBALS['FinishDate'] = $this->PrintTime($finishtime, true);
			} else {
				$GLOBALS['StatsAction'] .= '<span class="disabled" title="' . GetLang('StatsDeleteDisabled') . '">' . GetLang('Delete') . '</a>';
				$GLOBALS['FinishDate'] = GetLang('NotFinishedSending');
			}

			$bounce_count = $statsdetails['bouncecount_soft'] + $statsdetails['bouncecount_hard'] + $statsdetails['bouncecount_unknown'];

			$GLOBALS['TotalRecipients'] = $this->FormatNumber($statsdetails['sendsize']);
			$GLOBALS['BounceCount'] = $this->FormatNumber($bounce_count);
			$GLOBALS['UnsubscribeCount'] = $this->FormatNumber($statsdetails['unsubscribecount']);

			$statsdisplay .= $this->ParseTemplate('Stats_Newsletter_Manage_Row', true, false);
		}
		$stats_manage = str_replace('%%TPL_Stats_Newsletter_Manage_Row%%', $statsdisplay, $stats_manage);
		$stats_manage = str_replace('%%TPL_Paging%%', $paging, $stats_manage);
		$stats_manage = str_replace('%%TPL_Paging_Bottom%%', $GLOBALS['PagingBottom'], $stats_manage);

		echo $stats_manage;
	}

	/**
	* PrintNewsletterStats_Step2
	* This displays summary information for a newsletter based on the statid passed in. This sets up the other tabs (opens, bounces, links and so on) as well but this particular function mainly sets up the summary page.
	*
	* @param Int $statid The statid to get information for.
	*
	* @see Stats_API::GetNewsletterSummary
	* @see DisplayNewsletterOpens
	* @see DisplayNewsletterLinks
	* @see DisplayNewsletterBounces
	* @see DisplayNewsletterUnsubscribes
	* @see DisplayNewsletterForwards
	*
	* @return Void Doesn't return anything - just prints out the summary information.
	*/
	function PrintNewsletterStats_Step2($statid=0)
	{

		include(dirname(__FILE__) . '/charts/charts.php');

		$perpage = $this->GetPerPage();

		$statsapi = $this->GetApi('Stats');
		$summary = $statsapi->GetNewsletterSummary($statid, true, $perpage);

		$GLOBALS['NewsletterID'] = $summary['newsletterid'];

		$sent_when = $GLOBALS['StartSending'] = $this->PrintTime($summary['starttime'], true);

		if ($summary['finishtime'] > 0) {
			$GLOBALS['FinishSending'] = $this->PrintTime($summary['finishtime'], true);
			$GLOBALS['SendingTime'] = $this->TimeDifference($summary['finishtime'] - $summary['starttime']);
		} else {
			$GLOBALS['FinishSending'] = GetLang('NotFinishedSending');
			$GLOBALS['SendingTime'] = GetLang('NotFinishedSending');
		}

		$sent_to = $summary['htmlrecipients'] + $summary['textrecipients'] + $summary['multipartrecipients'];

		$sent_size = $summary['sendsize'];

		$GLOBALS['SentToDetails'] = sprintf(GetLang('NewsletterStatistics_Snapshot_SendSize'), $this->FormatNumber($sent_to), $this->FormatNumber($sent_size));

		$GLOBALS['SummaryIntro'] = sprintf(GetLang('NewsletterStatistics_Snapshot_Summary'), htmlspecialchars($summary['newslettername'], ENT_QUOTES, SENDSTUDIO_CHARSET), $sent_when);

		$GLOBALS['NewsletterSubject'] = $summary['newslettersubject'];

		$GLOBALS['UserEmail'] = htmlspecialchars($summary['emailaddress'], ENT_QUOTES, SENDSTUDIO_CHARSET);
		$sent_by = $summary['username'];
		if ($summary['fullname']) {
			$sent_by = $summary['fullname'];
		}
		$GLOBALS['SentBy'] = htmlspecialchars($sent_by, ENT_QUOTES, SENDSTUDIO_CHARSET);

		if (sizeof($summary['lists']) > 1) {
			$GLOBALS['SentToLists'] = GetLang('SentToLists');
			$GLOBALS['MailingLists'] = '';
			foreach ($summary['lists'] as $listid => $listname) {
				$GLOBALS['MailingLists'] .= $listname . ',';
			}
			$GLOBALS['MailingLists'] = htmlspecialchars(substr($GLOBALS['MailingLists'], 0, -1), ENT_QUOTES, SENDSTUDIO_CHARSET);
		} else {
			$GLOBALS['SentToLists'] = GetLang('SentToList');
			$listname = current($summary['lists']);
			$GLOBALS['MailingLists'] = htmlspecialchars($listname, ENT_QUOTES, SENDSTUDIO_CHARSET);
		}

		$GLOBALS['UniqueOpens'] = sprintf(GetLang('EmailOpens_Unique'), $this->FormatNumber($summary['emailopens_unique']));
		$GLOBALS['TotalOpens'] = sprintf(GetLang('EmailOpens_Total'), $this->FormatNumber($summary['emailopens']));

		$total_bounces = $summary['bouncecount_unknown'] + $summary['bouncecount_hard'] + $summary['bouncecount_soft'];

		$GLOBALS['TotalBounces'] = $this->FormatNumber($total_bounces);

		// now for the opens page.
		// by default this is for all opens, not unique opens.
		$only_unique = false;
		if (isset($_GET['Unique'])) {
			$only_unique = true;
		}

		if (!isset($_GET['Unique'])) {
			$GLOBALS['OpensURL'] = 'javascript: void(0);" onclick="ShowTab(2);';

			$GLOBALS['UniqueOpensURL'] = 'index.php?Page=Stats&Action=Newsletters&SubAction=ViewSummary&id=' . $statid . '&tab=2&Unique';
		} else {
			$GLOBALS['UniqueOpensURL'] = 'javascript: void(0);" onclick="ShowTab(2);';

			$GLOBALS['OpensURL'] = 'index.php?Page=Stats&Action=Newsletters&SubAction=ViewSummary&id=' . $statid . '&tab=2';
		}

		$chosen_link = 'a';
		if (isset($_GET['link'])) {
			if (is_numeric($_GET['link'])) {
				$chosen_link = $_GET['link'];
			}
		}

		$chosen_bounce_type = '';
		if (isset($_GET['bouncetype'])) {
			$chosen_bounce_type = urldecode($_GET['bouncetype']);
		}

		// we need to process the opens page first because it sets the number of opens used in a calculation for the links page.
		$GLOBALS['OpensPage'] = $this->DisplayNewsletterOpens($statid, $summary, $only_unique);

		$GLOBALS['LinksPage'] = $this->DisplayNewsletterLinks($statid, $summary, $chosen_link);

		$GLOBALS['BouncesPage'] = $this->DisplayNewsletterBounces($statid, $summary, $chosen_bounce_type);

		$GLOBALS['UnsubscribesPage'] = $this->DisplayNewsletterUnsubscribes($statid, $summary);

		$GLOBALS['ForwardsPage'] = $this->DisplayNewsletterForwards($statid, $summary);

		$unopened = $sent_size - $summary['emailopens_unique'] - $total_bounces;
		if ($unopened < 0) {
			$unopened = 0;
		}

		// explicitly pass the sessionid across to the chart
		// since it's not the browser but the server making this request, it may get a different session id if we don't, which then means it can't load the data properly.
		// especially applies to windows servers.

		$chart_url = SENDSTUDIO_APPLICATION_URL . '/admin/functions/stats_chart.php?Opens='.$summary['emailopens_unique'].'&Unopened='.$unopened.'&Bounced='.$total_bounces.'&'.session_name().'='.session_id();

		// Newsletter Summary Chart

		$GLOBALS['SummaryChart'] = InsertChart(SENDSTUDIO_APPLICATION_URL . '/admin/functions/charts/charts.swf', SENDSTUDIO_APPLICATION_URL . '/admin/functions/charts/charts_library', $chart_url, "100%", 250, 'FFFFFF', false, 'J1XIJUMEW9L.HSK5T4Q79KLYCK07EK');

		// finally put it all together.
		$page = $this->ParseTemplate('Stats_Newsletters_Step3', true, false);

		if (isset($_GET['tab'])) {
			$page .= '
			<script language="javascript">
				ShowTab(' . $_GET['tab'] . ');
			</script>
			';
		}
		echo $page;
	}

	/**
	* DisplayNewsletterOpens
	* This displays the page of newsletter open information based on the details passed in.
	* It will work out the calendar information, graph, paging and so on.
	*
	* @param Int $statid The statid to get information for.
	* @param Array $summary The basic information - start time and total number of opens.
	* @param Boolean $unique_only Whether to only show unique opens or not. By default this will show all open information, not just unique opens.
	*
	* @see Stats_API::GetOpens
	* @see Stats_API::GetMostOpens
	* @see DisplayChart
	*
	* @return Void Doesn't return anything - just prints out the tab of information.
	*/
	function DisplayNewsletterOpens($statid, $summary=array(), $unique_only=false)
	{
		$sent_when = $this->PrintTime($summary['starttime'], true);

		$GLOBALS['DisplayOpensIntro'] = sprintf(GetLang('NewsletterStatistics_Snapshot_OpenHeading'), htmlspecialchars($summary['newslettername'], ENT_QUOTES, SENDSTUDIO_CHARSET), $sent_when);

		if ($unique_only) {
			$GLOBALS['DisplayOpensIntro'] = sprintf(GetLang('NewsletterStatistics_Snapshot_OpenHeading_Unique'), htmlspecialchars($summary['newslettername'], ENT_QUOTES, SENDSTUDIO_CHARSET), $sent_when);
		}

		$statsapi = $this->GetApi('Stats');

		$perpage = $this->GetPerPage();

		$GLOBALS['PPDisplayName'] = 'oc';

		$DisplayPage = (isset($_GET['DisplayPage' . $GLOBALS['PPDisplayName']])) ? (int)$_GET['DisplayPage' . $GLOBALS['PPDisplayName']] : 1;
		$start = ($DisplayPage - 1) * $perpage;

		$opens = array();

		$base_action = $GLOBALS['PPDisplayName'].'&SubAction=Newsletters&NextAction=ViewSummary&id=' . $statid . '&tab=2';

		if ($unique_only) {
			$base_action .= '&Unique';
		}

		$calendar_restrictions = $this->CalendarRestrictions['opens'];

		$GLOBALS['TabID'] = '2';

		$this->SetupCalendar('Action=ProcessCalendar&' . $base_action);

		// make sure unique opens are > 0 - if they aren't, something isn't tracking right anyway so no point trying anything else.
		if ($summary['emailopens_unique'] > 0) {
			$opens = $statsapi->GetOpens($statid, $start, $perpage, $unique_only, $calendar_restrictions);
		}

		/*
		* we can't rely on the counter in the summary table -
		* because you could delete subscribers.
		* and we don't want that to affect the summary table because it distorts statistics.
		*
		* So we do an actual count here for paging.
		*/
		$opencount = $statsapi->GetOpens($statid, 0, 0, $unique_only, $calendar_restrictions, true);

		// if we still don't have any opens, not sure how! but we display an error.
		if (empty($opens)) {
			if ($summary['trackopens']) {
				if ($summary['emailopens_unique'] > 0) {
					$GLOBALS['Error'] = GetLang('NewsletterHasNotBeenOpened_CalendarProblem');
				} else {
					$GLOBALS['Error'] = GetLang('NewsletterHasNotBeenOpened');
				}
			} else {
				$GLOBALS['Error'] = GetLang('NewsletterWasNotOpenTracked');
			}
			$GLOBALS['Message'] = $this->ParseTemplate('ErrorMsg', true, false);
			return $this->ParseTemplate('Stats_Step3_Opens_Empty', true, false);
		}

		$total_emails = $summary['htmlrecipients'] + $summary['textrecipients'] + $summary['multipartrecipients'];
		$GLOBALS['TotalEmails'] = $this->FormatNumber($total_emails);
		$GLOBALS['TotalOpens'] = $this->FormatNumber($summary['emailopens']);
		$GLOBALS['TotalUniqueOpens'] = $this->FormatNumber($summary['emailopens_unique']);

		$most_opens = $statsapi->GetMostOpens($statid, $calendar_restrictions);

		$now = getdate();

		if (isset($most_opens['mth'])) {
			$GLOBALS['MostOpens'] = $this->Months[$most_opens['mth']] . ' ' . $most_opens['yr'];
		}

		if (isset($most_opens['hr'])) {
			$GLOBALS['MostOpens'] = $this->PrintDate(mktime($most_opens['hr'], 1, 1, 1, 1, $now['year']), GetLang('Daily_Time_Display'));
		}

		if (isset($most_opens['dow'])) {
			$pos = array_search($most_opens['dow'], array_keys($this->days_of_week));
			// we need to add 1 hour here otherwise we get the wrong day from strtotime.
			$GLOBALS['MostOpens'] = $this->PrintDate(strtotime("last " . $this->days_of_week[$pos] . " +1 hour"), GetLang('Date_Display_Display'));
		}

		if (isset($most_opens['dom'])) {
			$month = $now['mon'];
			// if the day-of-month is after "today", it's going to be for "last month" so adjust the month accordingly.
			if ($most_opens['dom'] > $now['mday']) {
				$month = $now['mon'] - 1;
			}
			$GLOBALS['MostOpens'] = $this->PrintDate(mktime(0, 0, 1, $month, $most_opens['dom'], $now['year']), GetLang('Date_Display_Display'));
		}

		$avg_opens = 0;
		if ($total_emails > 0) {
			$avg_opens = $summary['emailopens'] / $total_emails;
		}
		$GLOBALS['AverageOpens'] = $this->FormatNumber($avg_opens, 1);

		$GLOBALS['FormAction'] = 'Action=ProcessPaging' . $base_action;

		$GLOBALS['PAGE'] = 'Stats&Action=Newsletters&SubAction=ViewSummary&id=' . $statid . '&tab=2';

		if ($unique_only) {
			$GLOBALS['PAGE'] .= '&Unique';
		}

		$this->SetupPaging($opencount, $DisplayPage, $perpage);

		$paging = $this->ParseTemplate('Paging', true, false);

		$GLOBALS['Paging'] = $paging;

		$open_list = '';
		foreach ($opens as $k => $opendetails) {
			$GLOBALS['EmailAddress'] = htmlspecialchars($opendetails['emailaddress'], ENT_QUOTES, SENDSTUDIO_CHARSET);
			$GLOBALS['DateOpened'] = $this->PrintTime($opendetails['opentime'], true);
			$open_list .= $this->ParseTemplate('Stats_Step3_Opens_Row', true, false);
		}
		$GLOBALS['Stats_Step3_Opens_List'] = $open_list;

		$this->DisplayChart('OpenChart', 'newsletter', $statid);

		$GLOBALS['NewsletterOpenCount'] = $opencount;

		return $this->ParseTemplate('Stats_Step3_Opens', true, false);
	}

	/**
	* DisplayNewsletterLinks
	* This displays the page of newsletter link information based on the details passed in.
	* It will work out the calendar information, graph, paging and so on.
	*
	* @param Int $statid The statid to get information for.
	* @param Array $summary The basic information - start time and total number of link clicks.
	* @param String $chosen_link If this is present, we are showing information for a specific link. If it's not present, combine all links into one.
	*
	* @see Stats_API::GetClicks
	* @see Stats_API::GetUniqueLinks
	* @see DisplayChart
	*
	* @return Void Doesn't return anything - just prints out the tab of information.
	*/
	function DisplayNewsletterLinks($statid, $summary=array(), $chosen_link='a')
	{
		$sent_when = $this->PrintTime($summary['starttime'], true);

		$GLOBALS['StatID'] = (int)$statid;

		$GLOBALS['LinkAction'] = 'Newsletter';

		if (!is_numeric($chosen_link)) {
			$chosen_link = 'a';
		}

		$GLOBALS['DisplayLinksIntro'] = sprintf(GetLang('NewsletterStatistics_Snapshot_LinkHeading'), htmlspecialchars($summary['newslettername'], ENT_QUOTES, SENDSTUDIO_CHARSET), $sent_when);

		$statsapi = $this->GetApi('Stats');

		$perpage = $this->GetPerPage();

		$GLOBALS['PPDisplayName'] = 'lc';

		$DisplayPage = (isset($_GET['DisplayPage' . $GLOBALS['PPDisplayName']])) ? (int)$_GET['DisplayPage' . $GLOBALS['PPDisplayName']] : 1;

		$start = ($DisplayPage - 1) * $perpage;

		$base_action = $GLOBALS['PPDisplayName'].'&SubAction=Newsletters&NextAction=ViewSummary&id=' . $statid . '&tab=3&link=' . $chosen_link;

		$calendar_restrictions = $this->CalendarRestrictions['clicks'];

		$GLOBALS['TabID'] = '3';

		$this->SetupCalendar('Action=ProcessCalendar&' . $base_action);

		$links = array();
		if ($summary['linkclicks'] > 0) {
			$links = $statsapi->GetClicks($statid, $start, $perpage, $chosen_link, $calendar_restrictions);
		}

		$all_links = $statsapi->GetUniqueLinks($statid);

		if (empty($all_links)) {
			$GLOBALS['DisplayStatsLinkList'] = 'none';
		}

		$all_links_list = '';
		foreach ($all_links as $p => $linkinfo) {
			$selected = '';
			if ($linkinfo['linkid'] == $chosen_link) {
				$selected = ' SELECTED';
			}
			$all_links_list .= '<option value="' . $linkinfo['linkid'] . '"' . $selected . '>' . str_replace(array("'", '"'), "", $linkinfo['url']) . '</option>';
		}

		$GLOBALS['StatsLinkList'] = $all_links_list;

		$GLOBALS['StatsLinkDropDown'] = $this->ParseTemplate('Stats_Step3_Links_List', true, false);

		if (empty($links)) {
			if ($summary['tracklinks']) {
				if (empty($all_links)) {
					$GLOBALS['Error'] = GetLang('NewsletterHasNotBeenClicked_NoLinksFound');
				} else {
					if ($summary['linkclicks'] > 0) {
						if (is_numeric($chosen_link)) {
							if ($calendar_restrictions != '') {
								$GLOBALS['Error'] = GetLang('NewsletterHasNotBeenClicked_CalendarLinkProblem');
							} else {
								$GLOBALS['Error'] = GetLang('NewsletterHasNotBeenClicked_LinkProblem');
							}
						} else {
							$GLOBALS['Error'] = GetLang('NewsletterHasNotBeenClicked_CalendarProblem');
						}
					} else {
						$GLOBALS['DisplayStatsLinkList'] = 'none';
						$GLOBALS['Error'] = GetLang('NewsletterHasNotBeenClicked');
					}
				}
			} else {
				$GLOBALS['Error'] = GetLang('NewsletterWasNotTracked_Links');
			}
			$GLOBALS['Message'] = $this->ParseTemplate('ErrorMsg', true, false);
			return $this->ParseTemplate('Stats_Step3_Links_Empty', true, false);
		}

		/*
		* we can't rely on the counter in the summary table -
		* because you could delete subscribers.
		* and we don't want that to affect the summary table because it distorts statistics.
		*
		* So we do an actual count here for paging.
		*/
		$summary['linkclicks'] = $statsapi->GetClicks($statid, 0, 0, $chosen_link, $calendar_restrictions, true);

		// build up the summary table.
		$GLOBALS['TotalClicks'] = $this->FormatNumber($summary['linkclicks']);

		$unique_clicks_count = $statsapi->GetUniqueClicks($statid, $chosen_link, $calendar_restrictions);
		$GLOBALS['TotalUniqueClicks'] = $this->FormatNumber($unique_clicks_count);

		$most_popular_link = $statsapi->GetMostPopularLink($statid, $chosen_link, $calendar_restrictions);

		$GLOBALS['MostPopularLink'] = htmlspecialchars($most_popular_link, ENT_QUOTES, SENDSTUDIO_CHARSET);
		$GLOBALS['MostPopularLink_Short'] = $this->TruncateName($most_popular_link, 20);

		$averageclicks = 0;
		if (isset($GLOBALS['NewsletterOpenCount']) && (int)$GLOBALS['NewsletterOpenCount'] > 0) {
			$open_count = (int)$GLOBALS['NewsletterOpenCount'];
			$averageclicks = $summary['linkclicks'] / $open_count;
		}
		$GLOBALS['AverageClicks'] = $this->FormatNumber($averageclicks, 1);

		$GLOBALS['FormAction'] = 'Action=ProcessPaging' . $base_action;

		$GLOBALS['PAGE'] = 'Stats&Action=Newsletters&SubAction=ViewSummary&id=' . $statid . '&tab=3&link=' . $chosen_link;

		$this->SetupPaging($summary['linkclicks'], $DisplayPage, $perpage);

		$paging = $this->ParseTemplate('Paging', true, false);

		$GLOBALS['Paging'] = $paging;

		$click_list = '';
		foreach ($links as $k => $clickdetails) {
			$GLOBALS['EmailAddress'] = htmlspecialchars($clickdetails['emailaddress'], ENT_QUOTES, SENDSTUDIO_CHARSET);
			$GLOBALS['DateClicked'] = $this->PrintTime($clickdetails['clicktime'], true);

			$GLOBALS['FullURL'] = $url = str_replace(array('"', "'"), "", $clickdetails['url']);
			$url = $this->TruncateName($url, 75);

			$GLOBALS['LinkClicked'] = $url;

			$click_list .= $this->ParseTemplate('Stats_Step3_Links_Row', true, false);
		}
		$GLOBALS['Stats_Step3_Links_List'] = $click_list;

		$this->DisplayChart('LinksChart', 'newsletter', $statid);

		return $this->ParseTemplate('Stats_Step3_Links', true, false);
	}

	/**
	* DisplayNewsletterBounces
	* This displays the page of newsletter bounce information based on the details passed in.
	* It will work out the calendar information, graph, paging and so on.
	*
	* @param Int $statid The statid to get information for.
	* @param Array $summary The basic information - start time and total number of bounces.
	* @param String $chosen_bounce_type If this is present, we are showing information for a specific bounce type (hard, soft or unknown). If it's not present, combine all types.
	*
	* @see Stats_API::GetBounces
	* @see DisplayChart
	*
	* @return Void Doesn't return anything - just prints out the tab of information.
	*/
	function DisplayNewsletterBounces($statid, $summary=array(), $chosen_bounce_type='')
	{
		$sent_when = $this->PrintTime($summary['starttime'], true);

		$GLOBALS['DisplayBouncesIntro'] = sprintf(GetLang('NewsletterStatistics_Snapshot_BounceHeading'), htmlspecialchars($summary['newslettername'], ENT_QUOTES, SENDSTUDIO_CHARSET), $sent_when);

		$GLOBALS['BounceAction'] = 'Newsletters';

		$bouncetypelist = '';
		$all_bounce_types = array('any', 'hard', 'soft');
		if (!in_array($chosen_bounce_type, $all_bounce_types)) {
			$chosen_bounce_type = 'any';
		}

		foreach ($all_bounce_types as $p => $bounce_type) {
			$selected = '';
			if ($bounce_type == $chosen_bounce_type) {
				$selected = ' SELECTED';
			}
			$bouncetypelist .= '<option value="' . $bounce_type . '"' . $selected . '>' . GetLang('Bounce_Type_' . $bounce_type) . '</option>';
		}
		$GLOBALS['StatsBounceList'] = $bouncetypelist;

		$GLOBALS['PPDisplayName'] = 'bc'; // bounce count

		$base_action = $GLOBALS['PPDisplayName'].'&SubAction=Newsletters&NextAction=ViewSummary&id=' . $statid . '&tab=4';

		$calendar_restrictions = $this->CalendarRestrictions['bounces'];

		$GLOBALS['TabID'] = '4';

		$this->SetupCalendar('Action=ProcessCalendar&' . $base_action);

		$statsapi = $this->GetApi('Stats');

		$perpage = $this->GetPerPage();

		$DisplayPage = (isset($_GET['DisplayPage' . $GLOBALS['PPDisplayName']])) ? (int)$_GET['DisplayPage' . $GLOBALS['PPDisplayName']] : 1;

		$start = ($DisplayPage - 1) * $perpage;

		$total_bounces = $summary['bouncecount_soft'] + $summary['bouncecount_hard'] + $summary['bouncecount_unknown'];

		$bounces = array();

		if ($total_bounces > 0) {
			$bounces = $statsapi->GetBounces($statid, $start, $perpage, $chosen_bounce_type, $calendar_restrictions);
		}

		if (empty($bounces)) {
			if ($calendar_restrictions != '') {
				if ($total_bounces > 0) {
					if (!$chosen_bounce_type || $chosen_bounce_type == 'any') {
						$GLOBALS['Error'] = GetLang('NewsletterHasNotBeenBounced_CalendarProblem');
					} else {
						$GLOBALS['Error'] = sprintf(GetLang('NewsletterHasNotBeenBounced_CalendarProblem_BounceType'), GetLang('Bounce_Type_' . $chosen_bounce_type));
					}
				} else {
					$GLOBALS['Error'] = GetLang('NewsletterHasNotBeenBounced');
				}
			} else {
				if ($total_bounces > 0 && (!$chosen_bounce_type || $chosen_bounce_type == 'any')) {
					$GLOBALS['Error'] = sprintf(GetLang('NewsletterHasNotBeenBounced_BounceType'), GetLang('Bounce_Type_' . $chosen_bounce_type));
				} else {
					$GLOBALS['Error'] = GetLang('NewsletterHasNotBeenBounced');
				}
			}
			$GLOBALS['Message'] = $this->ParseTemplate('ErrorMsg', true, false);
			return $this->ParseTemplate('Stats_Step3_Bounces_Empty', true, false);
		}

		/*
		* we can't rely on the counter in the summary table -
		* because you could delete bounced subscribers.
		* and we don't want that to affect the summary table because it distorts statistics.
		*
		* So we do an actual count here for paging.
		*/
		$total_bounces = $statsapi->GetBounces($statid, $start, $perpage, $chosen_bounce_type, $calendar_restrictions, true);

		$bounce_types_count = $statsapi->GetBounceCounts($statid, $calendar_restrictions);
		$GLOBALS['TotalBounceCount'] = $this->FormatNumber($bounce_types_count['total']);
		$GLOBALS['TotalSoftBounceCount'] = $this->FormatNumber($bounce_types_count['soft']);
		$GLOBALS['TotalHardBounceCount'] = $this->FormatNumber($bounce_types_count['hard']);

		$GLOBALS['FormAction'] = 'Action=ProcessPaging' . $base_action;

		$GLOBALS['PAGE'] = 'Stats&Action=Newsletters&SubAction=ViewSummary&id=' . $statid . '&tab=4&bouncetype=' . $chosen_bounce_type;

		$this->SetupPaging($total_bounces, $DisplayPage, $perpage);

		$paging = $this->ParseTemplate('Paging', true, false);

		$GLOBALS['Paging'] = $paging;

		$bounce_list = '';
		foreach ($bounces as $k => $bouncedetails) {
			$GLOBALS['EmailAddress'] = htmlspecialchars($bouncedetails['emailaddress'], ENT_QUOTES, SENDSTUDIO_CHARSET);
			$GLOBALS['BounceDate'] = $this->PrintTime($bouncedetails['bouncetime'], true);
			$GLOBALS['BounceType'] = GetLang('Bounce_Type_' . $bouncedetails['bouncetype']);
			$GLOBALS['BounceRule'] = GetLang('Bounce_Rule_' . $bouncedetails['bouncerule']);
			$bounce_list .= $this->ParseTemplate('Stats_Step3_Bounces_Row', true, false);
		}
		$GLOBALS['Stats_Step3_Bounces_List'] = $bounce_list;

		$this->DisplayChart('BounceChart', 'newsletter', $statid);

		return $this->ParseTemplate('Stats_Step3_Bounces', true, false);
	}

	/**
	* DisplayNewsletterUnsubscribes
	* This displays the page of newsletter unsubscribe information based on the details passed in.
	* It will work out the calendar information, graph, paging and so on.
	*
	* @param Int $statid The statid to get information for.
	* @param Array $summary The basic information - start time and total number of unsubscribes.
	*
	* @see Stats_API::GetUnsubscribes
	* @see DisplayChart
	*
	* @return Void Doesn't return anything - just prints out the tab of information.
	*/
	function DisplayNewsletterUnsubscribes($statid, $summary=array())
	{
		$sent_when = $this->PrintTime($summary['starttime'], true);

		$GLOBALS['DisplayUnsubscribesIntro'] = sprintf(GetLang('NewsletterStatistics_Snapshot_UnsubscribesHeading'), htmlspecialchars($summary['newslettername'], ENT_QUOTES, SENDSTUDIO_CHARSET), $sent_when);

		$GLOBALS['PPDisplayName'] = 'uc'; // unsubscribe count

		$base_action = $GLOBALS['PPDisplayName'].'&SubAction=Newsletters&NextAction=ViewSummary&id=' . $statid . '&tab=5';

		$calendar_restrictions = $this->CalendarRestrictions['unsubscribes'];

		$GLOBALS['TabID'] = '5';

		$this->SetupCalendar('Action=ProcessCalendar&' . $base_action);

		$statsapi = $this->GetApi('Stats');

		$perpage = $this->GetPerPage();

		$DisplayPage = (isset($_GET['DisplayPage' . $GLOBALS['PPDisplayName']])) ? (int)$_GET['DisplayPage' . $GLOBALS['PPDisplayName']] : 1;

		$start = ($DisplayPage - 1) * $perpage;

		$unsubscribes = array();

		if ($summary['unsubscribecount'] > 0) {
			$unsubscribes = $statsapi->GetUnsubscribes($statid, $start, $perpage, $calendar_restrictions);
		}

		if (empty($unsubscribes)) {
			if ($summary['unsubscribecount'] > 0) {
				$GLOBALS['Error'] = GetLang('NewsletterHasNoUnsubscribes_CalendarProblem');
			} else {
				$GLOBALS['Error'] = GetLang('NewsletterHasNoUnsubscribes');
			}
			$GLOBALS['Message'] = $this->ParseTemplate('ErrorMsg', true, false);
			return $this->ParseTemplate('Stats_Step3_Unsubscribes_Empty', true, false);
		}

		if ($calendar_restrictions != '') {
			$summary['unsubscribecount'] = $statsapi->GetUnsubscribes($statid, $start, $perpage, $calendar_restrictions, true);
		}

		$GLOBALS['TotalUnsubscribes'] = $this->FormatNumber($summary['unsubscribecount']);

		$most_unsubscribes = $statsapi->GetMostUnsubscribes($statid, $calendar_restrictions);

		$now = getdate();

		if (isset($most_unsubscribes['mth'])) {
			$GLOBALS['MostUnsubscribes'] = $this->Months[$most_unsubscribes['mth']] . ' ' . $most_unsubscribes['yr'];
		}

		if (isset($most_unsubscribes['hr'])) {
			$GLOBALS['MostUnsubscribes'] = $this->PrintDate(mktime($most_unsubscribes['hr'], 1, 1, 1, 1, $now['year']), GetLang('Daily_Time_Display'));
		}

		if (isset($most_unsubscribes['dow'])) {
			$pos = array_search($most_unsubscribes['dow'], array_keys($this->days_of_week));
			// we need to add 1 hour here otherwise we get the wrong day from strtotime.
			$GLOBALS['MostUnsubscribes'] = $this->PrintDate(strtotime("last " . $this->days_of_week[$pos] . " +1 hour"), GetLang('Date_Display_Display'));
		}

		if (isset($most_unsubscribes['dom'])) {
			$month = $now['mon'];
			// if the day-of-month is after "today", it's going to be for "last month" so adjust the month accordingly.
			if ($most_unsubscribes['dom'] > $now['mday']) {
				$month = $now['mon'] - 1;
			}
			$GLOBALS['MostUnsubscribes'] = $this->PrintDate(mktime(0, 0, 1, $month, $most_unsubscribes['dom'], $now['year']), GetLang('Date_Display_Display'));
		}

		$GLOBALS['FormAction'] = 'Action=ProcessPaging' . $base_action;

		$GLOBALS['PAGE'] = 'Stats&Action=Newsletters&SubAction=ViewSummary&id=' . $statid . '&tab=5';

		$this->SetupPaging($summary['unsubscribecount'], $DisplayPage, $perpage);

		$paging = $this->ParseTemplate('Paging', true, false);

		$GLOBALS['Paging'] = $paging;

		$unsub_list = '';
		foreach ($unsubscribes as $k => $unsubdetails) {
			$GLOBALS['EmailAddress'] = htmlspecialchars($unsubdetails['emailaddress'], ENT_QUOTES, SENDSTUDIO_CHARSET);
			$GLOBALS['UnsubscribeTime'] = $this->PrintTime($unsubdetails['unsubscribetime'], true);
			$unsub_list .= $this->ParseTemplate('Stats_Step3_Unsubscribes_Row', true, false);
		}
		$GLOBALS['Stats_Step3_Unsubscribes_List'] = $unsub_list;

		$this->DisplayChart('UnsubscribeChart', 'newsletter', $statid);

		return $this->ParseTemplate('Stats_Step3_Unsubscribes', true, false);
	}

	/**
	* DisplayNewsletterForwards
	* This displays the page of newsletter forwarding information based on the details passed in.
	* It will work out the calendar information, graph, paging and so on.
	*
	* @param Int $statid The statid to get information for.
	* @param Array $summary The basic information - start time and total number of forwards.
	*
	* @see Stats_API::GetForwards
	* @see DisplayChart
	*
	* @return Void Doesn't return anything - just prints out the tab of information.
	*/
	function DisplayNewsletterForwards($statid, $summary=array())
	{
		$sent_when = $this->PrintTime($summary['starttime'], true);

		$GLOBALS['DisplayForwardsIntro'] = sprintf(GetLang('NewsletterStatistics_Snapshot_ForwardsHeading'), htmlspecialchars($summary['newslettername'], ENT_QUOTES, SENDSTUDIO_CHARSET), $sent_when);

		$GLOBALS['PPDisplayName'] = 'fc'; // forward count

		$base_action = $GLOBALS['PPDisplayName'].'&SubAction=Newsletters&NextAction=ViewSummary&id=' . $statid . '&tab=6';

		$calendar_restrictions = $this->CalendarRestrictions['forwards'];

		$GLOBALS['TabID'] = '6';

		$this->SetupCalendar('Action=ProcessCalendar&' . $base_action);

		$perpage = $this->GetPerPage();

		$DisplayPage = (isset($_GET['DisplayPage' . $GLOBALS['PPDisplayName']])) ? (int)$_GET['DisplayPage' . $GLOBALS['PPDisplayName']] : 1;

		$start = ($DisplayPage - 1) * $perpage;

		$statsapi = $this->GetApi('Stats');

		$forwards = array();

		if ($summary['emailforwards'] > 0) {
			$forwards = $statsapi->GetForwards($statid, $start, $perpage, $calendar_restrictions);
		}

		if (empty($forwards)) {
			if ($summary['emailforwards'] > 0) {
				$GLOBALS['Error'] = GetLang('NewsletterHasNoForwards_CalendarProblem');
			} else {
				$GLOBALS['Error'] = GetLang('NewsletterHasNoForwards');
			}
			$GLOBALS['Message'] = $this->ParseTemplate('ErrorMsg', true, false);
			return $this->ParseTemplate('Stats_Step3_Forwards_Empty', true, false);
		}

		if ($calendar_restrictions != '') {
			$summary['emailforwards'] = $statsapi->GetForwards($statid, $start, $perpage, $calendar_restrictions, true);
		}

		$GLOBALS['TotalForwards'] = $this->FormatNumber($summary['emailforwards']);

		$new_signups = $statsapi->GetForwards($statid, $start, $perpage, $calendar_restrictions, true, true);

		$GLOBALS['TotalForwardSignups'] = $this->FormatNumber($new_signups);

		$GLOBALS['FormAction'] = 'Action=ProcessPaging' . $base_action;

		$GLOBALS['PAGE'] = 'Stats&Action=Newsletters&SubAction=ViewSummary&id=' . $statid . '&tab=6';

		$this->SetupPaging($summary['emailforwards'], $DisplayPage, $perpage);

		$paging = $this->ParseTemplate('Paging', true, false);

		$GLOBALS['Paging'] = $paging;

		$forward_list = '';
		foreach ($forwards as $k => $forwarddetails) {
			$GLOBALS['ForwardedTo'] = htmlspecialchars($forwarddetails['forwardedto'], ENT_QUOTES, SENDSTUDIO_CHARSET);
			$GLOBALS['ForwardedBy'] = htmlspecialchars($forwarddetails['forwardedby'], ENT_QUOTES, SENDSTUDIO_CHARSET);
			$GLOBALS['ForwardTime'] = $this->PrintTime($forwarddetails['forwardtime'], true);
			if ($forwarddetails['subscribed'] > 0) {
				$hassubscribed = GetLang('Yes');
			} else {
				$hassubscribed = GetLang('No');
			}
			$GLOBALS['HasSubscribed'] = $hassubscribed;
			$forward_list .= $this->ParseTemplate('Stats_Step3_Forwards_Row', true, false);
		}
		$GLOBALS['Stats_Step3_Forwards_List'] = $forward_list;

		$this->DisplayChart('ForwardsChart', 'newsletter', $statid);

		return $this->ParseTemplate('Stats_Step3_Forwards', true, false);
	}

	/**
	* PrintUserStats_Step1
	* This prints out a list of users that the user can see.
	* If they are not a useradmin, it will take them straight to viewing their own statistics.
	* If they are a useradmin, they will see a list of users to choose from.
	*
	* @see GetUser
	* @see User_API::HasAccess
	* @see User_API::UserAdmin
	*
	* @return Void Doesn't return anything. Prints a dropdown list of users if they are a useradmin. If they are not a useradmin, then this will take them straight to viewing their own statistics.
	*/
	function PrintUserStats_Step1()
	{
		$user = &GetUser();
		if (!$user->UserAdmin()) {
			$location = 'index.php?Page=Stats&Action=User&SubAction=Step2&user=' . $user->Get('userid');
			?>
			<script language="javascript">
				window.location = '<?php echo $location; ?>';
			</script>
			<?php
			exit();
		}
		$GLOBALS['Action'] = 'User';

		$this->LoadLanguageFile('Users');

		$GLOBALS['NoSelection'] = GetLang('Stats_Users_NoSelection');
		$GLOBALS['CancelPrompt'] = GetLang('Stats_Users_Cancel');

		$GLOBALS['Heading'] = GetLang('Stats_Users_Step1_Heading');
		$GLOBALS['Intro'] = GetLang('Stats_Users_Step1_Intro');

		$GLOBALS['SelectList_Heading'] = GetLang('Stats_Users_SelectList_Heading');
		$GLOBALS['SelectList_Intro'] = GetLang('Stats_Users_SelectList_Intro');

		$perpage = $this->GetPerPage();

		if (!isset($_GET['Direction'])) {
			$this->_DefaultDirection = 'up';
		}

		$DisplayPage = (isset($_GET['DisplayPage'])) ? (int)$_GET['DisplayPage'] : 1;

		$start = ($DisplayPage - 1) * $perpage;

		$sortinfo = $this->GetSortDetails();

		$userapi = $this->GetApi('User');
		$NumberOfUsers = $userapi->GetUsers(0, $sortinfo, true);
		$myusers = $userapi->GetUsers(0, $sortinfo, false, $start, $perpage);

		$GLOBALS['FormAction'] = 'Action=ProcessPaging&SubAction=User&NextAction=Step1';

		$GLOBALS['PAGE'] = 'Stats&Action=User&SubAction=Step1';

		$this->SetupPaging($NumberOfUsers, $DisplayPage, $perpage);

		$paging = $this->ParseTemplate('Paging', true, false);

		$stats_manage = $this->ParseTemplate('Stats_Users_Manage', true, false);

		$statsdisplay = '';

		foreach ($myusers as $pos => $userdetails) {
			$GLOBALS['UserID'] = $userdetails['userid'];
			$GLOBALS['UserName'] = htmlspecialchars($userdetails['username'], ENT_QUOTES, SENDSTUDIO_CHARSET);
			$GLOBALS['FullName'] = htmlspecialchars($userdetails['fullname'], ENT_QUOTES, SENDSTUDIO_CHARSET);
			if (!$userdetails['fullname']) {
				$GLOBALS['FullName'] = GetLang('N/A');
			}
			$GLOBALS['Status'] = ($userdetails['status'] == 1) ? GetLang('Active') : GetLang('Inactive');

			$usertype = $user->GetAdminType($userdetails['admintype']);
			if ($usertype == 'c') {
				$usertype = 'RegularUser';
			}

			$GLOBALS['UserType'] = GetLang('AdministratorType_' . $usertype);

			$statsdisplay .= $this->ParseTemplate('Stats_Users_Manage_Row', true, false);
		}
		$stats_manage = str_replace('%%TPL_Stats_Users_Manage_Row%%', $statsdisplay, $stats_manage);
		$stats_manage = str_replace('%%TPL_Paging%%', $paging, $stats_manage);
		$stats_manage = str_replace('%%TPL_Paging_Bottom%%', $GLOBALS['PagingBottom'], $stats_manage);

		echo $stats_manage;
	}

	/**
	* PrintUserStats_Step2
	* Prints user statistics for the selected user.
	* If the user is not a useradmin, then this also checks to make sure they are only checking their own statistics. If the userid's don't match, then it prints an error message and they can't go any further.
	*
	* @param Int $userid The userid to print statistics for.
	*
	* @return Void Prints out the list of statistics for this particular user. It doesn't return anything.
	*/
	function PrintUserStats_Step2($userid=0)
	{
		$this->LoadLanguageFile('Users');

		$userid = (int)$userid;

		$GLOBALS['Heading'] = GetLang('Stats_Users_Step3_Heading');

		$user = &GetUser();
		if (!$user->UserAdmin()) {
			if ($userid != $user->Get('userid')) {
				$GLOBALS['ErrorMessage'] = GetLang('Stats_Unknown_User');
				$this->DenyAccess();
				return;
			}
		}

		$thisuser = &GetUser();

		if ($thisuser->Get('userid') != $userid) {
			$stats_user = $this->GetApi('User');
			$stats_user->Load($userid, false);
		} else {
			$stats_user = $thisuser;
		}

		$name = $stats_user->Get('username');
		$fullname = $stats_user->Get('fullname');
		if ($fullname) {
			$name = $fullname;
		}

		$statsapi = $this->GetApi();

		$GLOBALS['SummaryIntro'] = sprintf(GetLang('Stats_Users_Step3_Intro'), $name);

		$GLOBALS['UserCreateDate'] = $this->PrintTime($stats_user->Get('createdate'), true);

		$lastlogindate = $stats_user->Get('lastloggedin');
		if ($lastlogindate == 0) {
			$GLOBALS['LastLoggedInDate'] = GetLang('UserHasNotLoggedIn');
		} else {
			$GLOBALS['LastLoggedInDate'] = $this->PrintTime($lastlogindate, true);
		}

		$last_newsletter_sent = $statsapi->GetLastNewsletterSent($userid);

		if ($last_newsletter_sent == 0) {
			$GLOBALS['LastNewsletterSentDate'] = GetLang('UserHasNotSentAnyNewsletters');
		} else {
			$GLOBALS['LastNewsletterSentDate'] = $this->PrintTime($last_newsletter_sent, true);
		}

		$list_count = $statsapi->GetUserMailingLists($userid);
		$GLOBALS['ListsCreated'] = $this->FormatNumber($list_count);

		$autoresponder_count = $statsapi->GetUserAutoresponders($userid);
		$GLOBALS['AutorespondersCreated'] = $this->FormatNumber($autoresponder_count);

		$calendar_dates = $user->GetSettings('CalendarDates');

		$restrictions = $calendar_dates['usersummary'];

		$statsapi->CalculateStatsType();

		$user_stats = $statsapi->GetUserNewsletterStats($userid);

		$data = $statsapi->GetUserSendSummary($userid, $statsapi->stats_type, $restrictions);

		$GLOBALS['NewslettersSent'] = $this->FormatNumber($user_stats['newsletters_sent']);
		$GLOBALS['EmailsSent'] = $this->FormatNumber($user_stats['total_emails_sent']);
		$GLOBALS['TotalBounces'] = $this->FormatNumber($user_stats['total_bounces']);
		$GLOBALS['UniqueOpens'] = $this->FormatNumber($user_stats['unique_opens']);
		$GLOBALS['TotalOpens'] = $this->FormatNumber($user_stats['total_opens']);

		include(dirname(__FILE__) . '/charts/charts.php');

		$unopened = $user_stats['total_emails_sent'] - $user_stats['unique_opens'] - $user_stats['total_bounces'];
		if ($unopened < 0) {
			$unopened = 0;
		}

		$chart_url = SENDSTUDIO_APPLICATION_URL . '/admin/functions/stats_chart.php?Heading=User&Opens='.$user_stats['unique_opens'].'&Unopened='.$unopened.'&Bounced='.$user_stats['total_bounces'].'&'.session_name().'='.session_id();

		$GLOBALS['SummaryChart'] = InsertChart(SENDSTUDIO_APPLICATION_URL . '/admin/functions/charts/charts.swf', SENDSTUDIO_APPLICATION_URL . '/admin/functions/charts/charts_library', $chart_url, "100%", 250, 'FFFFFF', false, 'J1XIJUMEW9L.HSK5T4Q79KLYCK07EK');

		$GLOBALS['EmailsSentIntro'] = sprintf(GetLang('Stats_Users_Step3_EmailsSent_Intro'), $name);

		$perpage = $this->GetPerPage();

		$DisplayPage = (isset($_GET['DisplayPage'])) ? (int)$_GET['DisplayPage'] : 1;
		$start = ($DisplayPage - 1) * $perpage;

		$base_action = '&SubAction=User&NextAction=Step2&user=' . $userid . '&tab=2';

		$calendar_restrictions = $this->CalendarRestrictions['usersummary'];

		$GLOBALS['TabID'] = '2';

		$this->SetupCalendar('Action=ProcessCalendar&' . $base_action);

		$GLOBALS['FormAction'] = 'Action=ProcessPaging' . $base_action;

		$GLOBALS['PAGE'] = 'Stats&Action=User&SubAction=Step2&User=' . $userid . '&tab=2';

		$this->SetupPaging(0, $DisplayPage, $perpage);

		$paging = $this->ParseTemplate('Paging', true, false);

		$GLOBALS['Paging'] = $paging;

		$session = &GetSession();
		$session->Set('userchart_data', $data);

		$this->DisplayChart('UserChart', 'user', $userid);

		$now = getdate();
		$today = $now['0'];

		$this_year = date('Y');

		$time_display = '';

		$total_sent = 0;

		switch ($statsapi->calendar_type) {
			case 'today':
			case 'yesterday':
				for ($i = 0; $i < 24; $i++) {

					$GLOBALS['Name'] = $this->PrintDate(mktime($i, 1, 1, 1, 1, $this_year), GetLang('Daily_Time_Display'));
					$GLOBALS['Count'] = 0;

					foreach ($data as $p => $details) {
						if ($details['hr'] == $i) {
							$GLOBALS['Count'] = $this->FormatNumber($details['count']);
							$total_sent += $details['count'];
							break;
						}
					}
					$time_display .= $this->ParseTemplate('Stats_Users_SendSummary_Step3_Row', true, false);
				}
			break;

			case 'last24hours':
				$hours_now = $now['hours'];

				$i = 24;
				while ($i > 0) {
					$yr = mktime($hours_now, 1, 1, 1, 1, $this_year);
					$GLOBALS['Name'] = $this->PrintDate($yr, GetLang('Daily_Time_Display'));

					$hour_check = date('G', $yr);

					$GLOBALS['Count'] = 0;

					foreach ($data as $p => $details) {
						if ($details['hr'] == $hour_check) {
							$GLOBALS['Count'] = $this->FormatNumber($details['count']);
							$total_sent += $details['count'];
							break;
						}
					}

					$time_display .= $this->ParseTemplate('Stats_Users_SendSummary_Step3_Row', true, false);

					$hours_now--;

					$i--;
				}
			break;

			case 'last7days':

				$date = $today;

				$i = 7;
				while ($i > 0) {
					$GLOBALS['Name'] = $this->PrintDate($date, GetLang('Date_Display_Display'));
					$GLOBALS['Count'] = 0;

					foreach ($data as $p => $details) {
						if ($details['dow'] == date('w', $date)) {
							$dow_count = $details['count'];
							$GLOBALS['Count'] = $this->FormatNumber($dow_count);
							$total_sent += $dow_count;
							break;
						}
					}

					$time_display .= $this->ParseTemplate('Stats_Users_SendSummary_Step3_Row', true, false);

					$date = $date - 86400; // take off one day each time.

					$i--;
				}
			break;

			case 'last30days':

				$date = $today;

				$i = 30;
				while ($i > 0) {
					$GLOBALS['Name'] = $this->PrintDate($date);
					$GLOBALS['Count'] = 0;

					foreach ($data as $p => $details) {
						if ($details['dom'] == date('j', $date)) {
							$GLOBALS['Count'] = $this->FormatNumber($details['count']);
							$total_sent += $details['count'];
							break;
						}
					}

					$time_display .= $this->ParseTemplate('Stats_Users_SendSummary_Step3_Row', true, false);

					$date = $date - 86400; // take off one day each time.

					$i--;
				}
			break;

			case 'thismonth':
			case 'lastmonth':
				if ($statsapi->calendar_type == 'thismonth') {
					$month = $now['mon'];
				} else {
					$month = $now['mon'] - 1;
				}

				$timestamp = mktime(1, 1, 1, $month, 1, $now['year']);

				$days_of_month = date('t', $timestamp);

				for ($i = 1; $i <= $days_of_month; $i++) {
					$GLOBALS['Name'] = $this->PrintDate($timestamp);
					$GLOBALS['Count'] = 0;

					foreach ($data as $p => $details) {
						if ($details['dom'] == $i) {
							$GLOBALS['Count'] = $this->FormatNumber($details['count']);
							$total_sent += $details['count'];
							break;
						}
					}

					$time_display .= $this->ParseTemplate('Stats_Users_SendSummary_Step3_Row', true, false);

					$timestamp += 86400;
				}
			break;

			default:
				for ($i = 1; $i <= 12; $i++) {
					$GLOBALS['Count'] = 0;

					foreach ($data as $p => $details) {
						$GLOBALS['Name'] = GetLang($this->Months[$i]) . ' ' . $details['yr'];
						if ($details['mth'] == $i) {
							$GLOBALS['Count'] = $this->FormatNumber($details['count']);
							$total_sent += $details['count'];
							break;
						}
					}

					$time_display .= $this->ParseTemplate('Stats_Users_SendSummary_Step3_Row', true, false);
				}
			break;
		}

		if ($total_sent <= 0) {
			$GLOBALS['Error'] = GetLang('UserHasNotSentAnyNewsletters');
			$GLOBALS['UserHasNotSentAnyNewsletters'] = $this->ParseTemplate('ErrorMsg', true, false);
			$GLOBALS['UsersSummaryPage'] = $this->ParseTemplate('Stats_Users_Sendsummary_Step3_Empty', true, false);
		} else {

			$GLOBALS['TotalEmailsSent'] = $this->FormatNumber($total_sent);

			$GLOBALS['Stats_Step3_EmailsSent_List'] = $time_display;
			$GLOBALS['UsersSummaryPage'] = $this->ParseTemplate('Stats_Users_Sendsummary_Step3', true, false);
		}

		// finally put it all together.
		$page = $this->ParseTemplate('Stats_Users_Step3', true, false);

		if (isset($_GET['tab'])) {
			$page .= '
			<script language="javascript">
				ShowTab(' . $_GET['tab'] . ');
			</script>
			';
		}
		echo $page;

	}

	/**
	* CalculateCalendarRestrictions
	* Returns a partial query which can be appended to an existing query to restrict searching to the dates you have searched before (which are retrieved from the session).
	*
	* @param Array $calendarinfo Pass in calendar info if you want to use that instead of the session information.
	* @param Boolean $enddateonly  Whether to only return the end-date. This is used for campaigns so we can calculate the number of days since the start of a campaign properly. Returns it as an integer (epoch time).
	*
	* @see GetSession
	* @see Session::Get
	* @see User::GetSettings
	* @see Campaigns::Process
	* @see ViewAll_Campaigns::Process
	*
	* @return String The partial query to be appended or the end date (as an int) depending on the second parameter.
	*/
	function CalculateCalendarRestrictions($calendarinfo=array(), $enddateonly=false) {
		$session = &GetSession();
		$user = &GetUser();

		if (!$calendarinfo) {
			$calendar_settings = $user->GetSettings('Calendar');
		} else {
			$calendar_settings = $calendarinfo;
		}

		if (!isset($calendar_settings['DateType'])) $calendar_settings['DateType'] = 'AllTime';

		$calendar_settings['DateType'] = strtolower($calendar_settings['DateType']);

		$rightnow = AdjustTime(0, true);

		$today = AdjustTime(array(0, 0, 0, date('m'), date('d'), date('Y')), true, null, true);
		$yesterday = AdjustTime(array(0, 0, 0, date('m'), date('d')-1, date('Y')), true, null, true);

		switch($calendar_settings['DateType']) {
			case 'today':
				$query = ' AND (%%TABLE%% >= ' . $today . ')';

				$enddate = $rightnow;
			break;

			case 'yesterday':
				$query = ' AND (%%TABLE%% >= ' . $yesterday . ' AND %%TABLE%% < ' . $today . ')';

				$enddate = $today;
			break;

			case 'last24hours':
				$enddate = $rightnow - 86400;

				// since "rightnow" is already adjusted, we don't need to adjust it again.

				$query  = ' AND (%%TABLE%% >= ' . $enddate . ' AND %%TABLE%% < ' . $rightnow . ')';

			break;

			case 'last7days':
				$time = AdjustTime(array(0, 0, 0, date('m'), date('d') - 7, date('Y')), true, null, true);

				$query = ' AND (%%TABLE%% >= ' . $time . ')';

				$enddate = $rightnow;
			break;

			case 'last30days':
				$time = AdjustTime(array(0, 0, 0, date('m'), date('d')-30, date('Y')), true, null, true);

				$query = ' AND (%%TABLE%% >= ' . $time . ')';
				$enddate = $rightnow;
			break;

			case 'thismonth':
				$time = AdjustTime(array(0, 0, 0, date('m'), 1, date('Y')), true, null, true);
				$query = ' AND (%%TABLE%% >= ' . $time . ')';
				$enddate = $rightnow;
			break;

			case 'lastmonth':
				$lastm = AdjustTime(array(0, 0, 0, date('m')-1, 1, date('Y')), true, null, true);
				$thism = AdjustTime(array(0, 0, 0, date('m'), 1, date('Y')), true, null, true);
				$query  = ' AND (%%TABLE%% >= ' . $lastm . ' AND %%TABLE%% < ' . $thism . ')';
				$enddate = $thism;
			break;

			case 'custom':
				$fromdate = AdjustTime(array(0, 0, 0, $calendar_settings['From']['Mth'], $calendar_settings['From']['Day'], $calendar_settings['From']['Yr']), true);

				// for the "to" part, we want the start of the next day.
				// so if you put From 1/1/04 and To 1/1/04 - it actually finds records from midnight 1/1/04 until 23.59.59 1/1/04 (easier to get the next day and make it before then)..
				$todate = AdjustTime(array(0, 0, 0, $calendar_settings['To']['Mth'], ($calendar_settings['To']['Day']+1), $calendar_settings['To']['Yr']), true);

				$query  = ' AND (%%TABLE%% >= ' . $fromdate . ' AND %%TABLE%% < ' . $todate . ')';
				$enddate = $todate;
			break;

			case 'alltime':
			default:
				$query = '';
			break;
		}

		$queries = array(
			'opens' => str_replace('%%TABLE%%', 'opentime', $query),
			'clicks' => str_replace('%%TABLE%%', 'clicktime', $query),
			'forwards' => str_replace('%%TABLE%%', 'forwardtime', $query),
			'bounces' => str_replace('%%TABLE%%', 'bouncetime', $query),
			'unsubscribes' => str_replace('%%TABLE%%', 'unsubscribetime', $query)
		);
		$queries['subscribers']['subscribes'] = str_replace('%%TABLE%%', 'subscribedate', $query);
		$queries['subscribers']['unsubscribes'] = str_replace('%%TABLE%%', 'unsubscribetime', $query);
		$queries['subscribers']['bounces'] = str_replace('%%TABLE%%', 'bouncetime', $query);
		$queries['subscribers']['forwards'] = str_replace('%%TABLE%%', 'forwardtime', $query);

		$queries['usersummary'] = str_replace('%%TABLE%%', 'sendtime', $query);

		if ($enddateonly) return $enddate;
		$this->CalendarRestrictions = $queries;
	}

	/**
	* SetupCalendar
	* This sets up the calendar according to what's already been shown. This way, the calendar is persistent across all pages.
	* It sets up the global variables ready for it to be parsed and printed.
	*
	* @param String $formaction The formaction for the calendar to use.
	* @param Array $calendarinfo An array of calendar information to use when setting up the calendar. If this is not present, calendar information is used from the session.
	*
	* @see ParseTemplate
	* @see GetSession
	* @see Session::Get
	* @see User::GetSettings
	* @see GetLang
	*
	* @return Void Doesn't return anything, sets up global variables and the global calendar.
	*/
	function SetupCalendar($formaction=null, $calendarinfo=array()) {
		unset($GLOBALS['PAGE']);

		$session = &GetSession();
		$thisuser = $session->Get('UserDetails');
		if (!empty($calendarinfo)) {
			$calendar_settings = $calendarinfo;
		} else {
			$calendar_settings = $thisuser->GetSettings('Calendar');
		}

		$userid = $thisuser->userid;
		$user = &GetUser($userid);
		$user->settings = $thisuser->settings;
		$user->SaveSettings();
		unset($user);

		$thisyear = date('Y');

		if (empty($calendar_settings)) {
			$calendar_settings = array(
					'DateType' => 'AllTime',
					'From' => array(
						'Day' => 1,
						'Mth' => 1,
						'Yr' => $thisyear
					),
					'To' => array(
						'Day' => 1,
						'Mth' => 1,
						'Yr' => $thisyear
					)
			);
		}

		$date_options = array('Today', 'Yesterday', 'Last24Hours', 'Last7Days', 'Last30Days', 'ThisMonth', 'LastMonth', 'AllTime', 'Custom');

		$date_format = GetLang('DateFormat');
		$time_format = GetLang('TimeFormat');

		$viewing_results_for = GetLang('ViewingResultsFor');
		$datetoshow = $viewing_results_for . ' ';

		$timenow = AdjustTime(0, true);

		switch($calendar_settings['DateType']) {
			case 'Today':
				$datetoshow .= $this->PrintDate($timenow, $date_format);
			break;

			case 'Yesterday':
				$yesterday = AdjustTime(array(0, 0, 0, date('m'), date('d')-1, date('Y')), true, null, true);
				$datetoshow .= $this->PrintDate($yesterday, $date_format);
			break;

			case 'Last24Hours':
				$tf_hours_ago = $timenow - 86400;
				$datetoshow .= '<br/>&nbsp;&nbsp;' . $this->PrintDate($tf_hours_ago, $time_format) . ' - ' . $this->PrintDate($timenow, $time_format);
			break;

			case 'Last7Days':
				$seven_daysago = AdjustTime(array(0, 0, 0, date('m'), date('d') - 7, date('Y')), true, null, true);
				$datetoshow .= $this->PrintDate($seven_daysago, $date_format);
				$datetoshow .= ' - ' . $this->PrintDate($timenow, $date_format);
			break;

			case 'Last30Days':
				$thirty_daysago = AdjustTime(array(0, 0, 0, date('m'), date('d') - 30, date('Y')), true, null, true);
				$datetoshow .= $this->PrintDate($thirty_daysago, $date_format);
				$datetoshow .= ' - ' . $this->PrintDate($timenow, $date_format);
			break;

			case 'ThisMonth':
				$startofmonth = AdjustTime(array(0, 0, 0, date('m'), 1, date('Y')), true, null, true);
				$datetoshow .= $this->PrintDate($startofmonth, $date_format);
				$datetoshow .= ' - ' . $this->PrintDate($timenow, $date_format);
			break;

			case 'LastMonth':
				$lastmonth = AdjustTime(array(0, 0, 0, date('m')-1, 1, date('Y')), true, null, true);
				$thismonth = AdjustTime(array(0, 0, 0, date('m'), 1, date('Y')), true, null, true);
				$datetoshow .= $this->PrintDate($lastmonth, $date_format);
				$datetoshow .= ' - ' . $this->PrintDate($thismonth, $date_format);
			break;

			case 'AllTime':
				$datetoshow = '';
			break;

			case 'Custom':
				$start = AdjustTime(array(0, 0, 0, $calendar_settings['From']['Mth'], $calendar_settings['From']['Day'], $calendar_settings['From']['Yr']), true);
				$end = AdjustTime(array(0, 0, 0, $calendar_settings['To']['Mth'], $calendar_settings['To']['Day'], $calendar_settings['To']['Yr']), true);
				$datetoshow .= $this->PrintDate($start, $date_format) . ' - ' . $this->PrintDate($end, $date_format);
			break;
		}

		$calendar_options = '';
		$CustomDateDisplay = 'none';
		$ShowDateDisplay = '';

		foreach ($date_options as $option) {
			$calendar_options .= '<option value="' . $option . '"';
			if ($calendar_settings['DateType'] == $option) {
				$calendar_options .= ' SELECTED';
			}
			$calendar_options .= '>' . GetLang($option) . '</option>';
		}

		if ($calendar_settings['DateType'] == 'Custom') {
			$CustomDateDisplay = '';
			$ShowDateDisplay = 'none';
		}

		if ($calendar_settings['DateType'] == 'AllTime') {
			$ShowDateDisplay = 'none';
		}

		// first we do the "From" stuff.
		$CustomDayFrom = '';
		for ($i = 1; $i <= 31; $i++) {
			$CustomDayFrom .= '<option value="' . $i . '"';
			if ($i == $calendar_settings['From']['Day']) $CustomDayFrom .= ' SELECTED';
			$CustomDayFrom .= '>' . $i . '</option>';
		}
		$CustomDayFrom .= '';

		$CustomMthFrom = '';
		for ($i = 1; $i <= 12; $i++) {
			$CustomMthFrom .= '<option value="' . $i . '"';
			if ($i == $calendar_settings['From']['Mth']) $CustomMthFrom .= ' SELECTED';
			$CustomMthFrom .= '>' . GetLang($this->Months[$i]) . '</option>';
		}
		$CustomMthFrom .= '</select>';

		$CustomYrFrom = '';
		for ($i = ($thisyear - 2); $i <= ($thisyear + 5); $i++) {
			$CustomYrFrom .= '<option value="' . $i . '"';
			if ($i == $calendar_settings['From']['Yr']) $CustomYrFrom .= ' SELECTED';
			$CustomYrFrom .= '>' . $i . '</option>';
		}
		$CustomYrFrom .= '';

		// now we do the "To" stuff.
		$CustomDayTo = '';
		for ($i = 1; $i <= 31; $i++) {
			$CustomDayTo .= '<option value="' . $i . '"';
			if ($i == $calendar_settings['To']['Day']) $CustomDayTo .= ' SELECTED';
			$CustomDayTo .= '>' . $i . '</option>';
		}
		$CustomDayTo .= '';

		$CustomMthTo = '';
		for ($i = 1; $i <= 12; $i++) {
			$CustomMthTo .= '<option value="' . $i . '"';
			if ($i == $calendar_settings['To']['Mth']) $CustomMthTo .= ' SELECTED';
			$CustomMthTo .= '>' . GetLang($this->Months[$i]) . '</option>';
		}
		$CustomMthTo .= '';

		$CustomYrTo = '';
		for ($i = ($thisyear - 2); $i <= ($thisyear + 5); $i++) {
			$CustomYrTo .= '<option value="' . $i . '"';
			if ($i == $calendar_settings['To']['Yr']) $CustomYrTo .= ' SELECTED';
			$CustomYrTo .= '>' . $i . '</option>';
		}
		$CustomYrTo .= '';

		$GLOBALS['CustomDayFrom'] = $CustomDayFrom;
		$GLOBALS['CustomMthFrom'] = $CustomMthFrom;
		$GLOBALS['CustomYrFrom'] = $CustomYrFrom;

		$GLOBALS['CustomDayTo'] = $CustomDayTo;
		$GLOBALS['CustomMthTo'] = $CustomMthTo;
		$GLOBALS['CustomYrTo'] = $CustomYrTo;

		$GLOBALS['ShowDateDisplay'] = $ShowDateDisplay;
		$GLOBALS['CustomDateDisplay'] = $CustomDateDisplay;
		$GLOBALS['CalendarOptions'] = $calendar_options;

		$GLOBALS['DateRange'] = $datetoshow;

		if (is_null($formaction)) {
			$GLOBALS['FormAction'] = 'Action=ProcessDate';
		} else {
			$GLOBALS['FormAction'] = $formaction;
		}
		$GLOBALS['Calendar'] = $this->ParseTemplate('calendar', true, false);
	}

	/**
	* GetCalendarInfo
	* Gets calendar information from the array passed in, makes it 'human-readable'.
	*
	* @param Array $calendar An array of calendar settings to process.
	* @param Boolean $dateonly Whether to get the date only (ignore whether it's yesterday, today etc).
	*
	* @return String The calendar date.
	*/
	function GetCalendarInfo($calendar=array(), $dateonly=false) {
		$session = &GetSession();
		$thisuser = $session->Get('UserDetails');
		if (!empty($calendar)) {
			$calendar_settings = $calendar;
		} else {
			$calendar_settings = $thisuser->GetSettings('Calendar');
		}

		$date_format = GetLang('DateFormat');

		$timenow = time();
		$timenow = AdjustTime($timenow);

		switch($calendar_settings['DateType']) {
			case 'Yesterday':
				$yesterday = mktime(0, 0, 0, date('m'), date('d')-1, date('Y'));
				$datetoshow = $this->PrintDate(AdjustTime($yesterday));
			break;

			case 'Last24Hours':
			case 'Today':
				$datetoshow = $this->PrintDate($timenow);
			break;

			case 'Last7Days':
				$seven_daysago = mktime(0, 0, 0, date('m'), date('d') - 7, date('Y'));
				$datetoshow = $this->PrintDate(AdjustTime($seven_daysago));
				$datetoshow .= ' - ' . $this->PrintDate($timenow);
			break;

			case 'Last30Days':
				$thirty_daysago = mktime(0, 0, 0, date('m'), date('d') - 30, date('Y'));
				$datetoshow = $this->PrintDate($thirty_daysago);
				$datetoshow .= ' - ' . $this->PrintDate($timenow);
			break;

			case 'ThisMonth':
				$startofmonth = mktime(0, 0, 0, date('m'), 1, date('Y'));
				$datetoshow = $this->PrintDate(AdjustTime($startofmonth));
				$datetoshow .= ' - ' . $this->PrintDate($timenow);
			break;

			case 'Custom':
				$start = mktime(0, 0, 0, date($calendar_settings['From']['Mth']), date($calendar_settings['From']['Day']), date($calendar_settings['From']['Yr']));
				$end = mktime(0, 0, 0, date($calendar_settings['To']['Mth']), date($calendar_settings['To']['Day']), date($calendar_settings['To']['Yr']));
				$datetoshow = $this->PrintDate(AdjustTime($start)) . ' - ' . $this->PrintDate(AdjustTime($end));
			break;

			case 'LastMonth':
				$lastmonth = mktime(0, 0, 0, date('m')-1, 1, date('Y'));
				$thismonth = mktime(0, 0, 0, date('m'), 1, date('Y'));
				$datetoshow = $this->PrintDate(AdjustTime($lastmonth));
				$datetoshow .= ' - ' . $this->PrintDate(AdjustTime($thismonth));
			break;

			case 'AllTime':
				$datetoshow = GetLang('AllTime');
			break;

		}

		if ($dateonly) {
			return $datetoshow;
		}

		$datetype = $calendar_settings['DateType'];

		if ($datetype == 'Custom' || $datetype == 'AllTime') {
			$readableformat = $datetoshow;
		} else {
			$readableformat = GetLang($datetype) . ' (' . $datetoshow . ')';
		}
		return GetLang('DateRange') . ': ' . $readableformat;
	}

	/**
	* DisplayChart
	* This sets up the chart in the tab ready for displaying.
	* It simply sets up the chart_url based on the criteria passed in and sets the global placeholder for the other functions to parse and display.
	*
	* @param String $chartname Name of the global variable chart placeholder.
	* @param String $chart_area The area we're viewing (eg unsubscribes, forwards).
	* @param Int $statid This is passed to the stats_chart.php file for loading / processing.
	*
	* @see stats_chart.php
	*
	* @return Void Doesn't return anything - sets up the global placeholder ready for replacement.
	*/
	function DisplayChart($chartname='', $chart_area='', $statid=0) {
		// explicitly pass the sessionid across to the chart
		// since it's not the browser but the server making this request, it may get a different session id if we don't, which then means it can't load the data properly.
		// especially applies to windows servers.

		$chart_url = SENDSTUDIO_APPLICATION_URL . '/admin/functions/stats_chart.php?graph=' . urlencode(strtolower($chartname)) . '&Area='.urlencode(strtolower($chart_area)) . '&statid=' . (int)$statid . '&' . session_name().'='.session_id();

		$GLOBALS[$chartname] = InsertChart(SENDSTUDIO_APPLICATION_URL . '/admin/functions/charts/charts.swf', SENDSTUDIO_APPLICATION_URL . '/admin/functions/charts/charts_library', $chart_url, '100%', 200, 'FFFFFF', false, 'J1XIJUMEW9L.HSK5T4Q79KLYCK07EK');
	}

	/**
	* PrintAutoresponderStats_Step1
	* This prints out a list of autoresponders whether they have statistics or not.
	* If there are no autoresponders, it will show an error message and give them the option to create one.
	*
	* @return Void Doesn't return anything. Prints a list of autoresponders to choose from if there are some available, or if there are none, it will check user permissions to see if they can create an autoresponder and print an appropriate message.
	*/
	function PrintAutoresponderStats_Step1()
	{
		$user = &GetUser();
		$lists = $user->GetLists();
		$statsapi = $this->GetApi('Stats');

		$listids = array_keys($lists);

		$perpage = $this->GetPerPage();

		$DisplayPage = (isset($_GET['DisplayPage'])) ? (int)$_GET['DisplayPage'] : 1;
		$start = ($DisplayPage - 1) * $perpage;

		$sortinfo = $this->GetSortDetails();

		$NumberOfStats = $statsapi->GetAutoresponderStats($listids, $sortinfo, true, 0, 0);
		$mystats = $statsapi->GetAutoresponderStats($listids, $sortinfo, false, $start, $perpage);

		if ($NumberOfStats == 0) {
			$autoresponder_api = $this->GetApi('Autoresponders');
			$auto_count = $autoresponder_api->GetAutoresponders($listids, array(), true);
			if ($auto_count == 0) {
				if ($user->HasAccess('Autoresponders', 'Create')) {
					$GLOBALS['Autoresponders_AddButton'] = $this->ParseTemplate('Autoresponder_Create_Button', true, false);
					$GLOBALS['Message'] = $this->PrintSuccess('NoAutoresponders', GetLang('AutoresponderCreate'));
				} else {
					$GLOBALS['Message'] = $this->PrintSuccess('NoAutoresponders', '');
				}
			} else {
				$GLOBALS['Message'] = $this->PrintSuccess('NoAutorespondersHaveBeenSent', '');
			}

			$this->ParseTemplate('Stats_Autoresponders_Empty');
			return;
		}

		$GLOBALS['FormAction'] = 'Action=ProcessPaging&SubAction=Autoresponders&NextAction=Step1';

		$GLOBALS['PAGE'] = 'Stats&Action=Autoresponders&SubAction=Step1';

		$this->SetupPaging($NumberOfStats, $DisplayPage, $perpage);

		$paging = $this->ParseTemplate('Paging', true, false);

		$stats_manage = $this->ParseTemplate('Stats_Autoresponders_Manage', true, false);

		$statsdisplay = '';

		foreach ($mystats as $pos => $statsdetails) {
			$GLOBALS['StatID'] = $statsdetails['statid'];
			$GLOBALS['Autoresponder'] = htmlspecialchars($statsdetails['autorespondername'], ENT_QUOTES, SENDSTUDIO_CHARSET);
			$GLOBALS['MailingList'] = htmlspecialchars($statsdetails['listname'], ENT_QUOTES, SENDSTUDIO_CHARSET);

			$GLOBALS['StatsAction'] = '<a href="index.php?Page=Stats&Action=Autoresponders&SubAction=ViewSummary&auto=' . $statsdetails['autoresponderid'] . '">' . GetLang('ViewSummary') . '</a>&nbsp;&nbsp;';

			if ($statsdetails['statid'] > 0) {
				$GLOBALS['StatsAction'] .= '<a href="javascript: ConfirmDelete(' . $statsdetails['statid'] . ');">' . GetLang('Delete') . '</a>';
			} else {
				$GLOBALS['StatsAction'] .= '<span class="disabled" title="' . GetLang('NoStatisticsToDelete') . '">' . GetLang('Delete') . '</a>';
			}

			$bounce_count = $statsdetails['bouncecount_soft'] + $statsdetails['bouncecount_hard'] + $statsdetails['bouncecount_unknown'];

			if ($statsdetails['hoursaftersubscription'] < 1) {
				$GLOBALS['SentWhen'] = GetLang('Immediately');
			} else {
				if ($statsdetails['hoursaftersubscription'] == 1) {
					$GLOBALS['SentWhen'] = GetLang('HoursAfter_One');
				} else {
					$GLOBALS['SentWhen'] = sprintf(GetLang('HoursAfter_Many'), $statsdetails['hoursaftersubscription']);
				}
			}

			$GLOBALS['TotalRecipients'] = $this->FormatNumber($statsdetails['sendsize']);
			$GLOBALS['BounceCount'] = $this->FormatNumber($bounce_count);
			$GLOBALS['UnsubscribeCount'] = $this->FormatNumber($statsdetails['unsubscribecount']);

			$statsdisplay .= $this->ParseTemplate('Stats_Autoresponders_Manage_Row', true, false);
		}
		$stats_manage = str_replace('%%TPL_Stats_Autoresponders_Manage_Row%%', $statsdisplay, $stats_manage);
		$stats_manage = str_replace('%%TPL_Paging%%', $paging, $stats_manage);
		$stats_manage = str_replace('%%TPL_Paging_Bottom%%', $GLOBALS['PagingBottom'], $stats_manage);

		echo $stats_manage;

	}

	/**
	* PrintAutoresponderStats_Step2
	* This displays summary information for an autoresponder based on the autoresponder passed in. This sets up the other tabs (opens, bounces, links and so on) as well but this particular function mainly sets up the summary page.
	*
	* @param Int $autoresponderid The autoresponderid to get information for.
	*
	* @see Stats_API::GetAutoresponderSummary
	* @see DisplayAutoresponderOpens
	* @see DisplayAutoresponderLinks
	* @see DisplayAutoresponderBounces
	* @see DisplayAutoresponderUnsubscribes
	* @see DisplayAutoresponderForwards
	*
	* @return Void Doesn't return anything - just prints out the summary information.
	*/
	function PrintAutoresponderStats_Step2($autoresponderid=0)
	{

		include(dirname(__FILE__) . '/charts/charts.php');

		$perpage = $this->GetPerPage();

		// this is all for the summary page.
		$autoresponderid = (int)$autoresponderid;

		$GLOBALS['AutoresponderID'] = $autoresponderid;

		$statsapi = $this->GetApi('Stats');

		$summary = $statsapi->GetAutoresponderSummary($autoresponderid, true, $perpage);

		$GLOBALS['SummaryIntro'] = sprintf(GetLang('AutoresponderStatistics_Snapshot_Summary'), htmlspecialchars($summary['autorespondername'], ENT_QUOTES, SENDSTUDIO_CHARSET));

		$GLOBALS['AutoresponderSubject'] = htmlspecialchars($summary['autorespondersubject'], ENT_QUOTES, SENDSTUDIO_CHARSET);

		$GLOBALS['UserEmail'] = $summary['emailaddress'];
		$created_by = $summary['username'];
		if ($summary['fullname']) {
			$created_by = $summary['fullname'];
		}
		$GLOBALS['CreatedBy'] = $created_by;

		$GLOBALS['MailingList'] = htmlspecialchars($summary['listname'], ENT_QUOTES, SENDSTUDIO_CHARSET);

		if ($summary['hoursaftersubscription'] < 1) {
			$GLOBALS['SentWhen'] = GetLang('Immediately');
		} else {
			if ($summary['hoursaftersubscription'] == 1) {
				$GLOBALS['SentWhen'] = GetLang('HoursAfter_One');
			} else {
				$GLOBALS['SentWhen'] = sprintf(GetLang('HoursAfter_Many'), $summary['hoursaftersubscription']);
			}
		}

		$total_sent = $summary['htmlrecipients'] + $summary['textrecipients'] + $summary['multipartrecipients'];
		$GLOBALS['SentToDetails'] = $this->FormatNumber($total_sent);

		$GLOBALS['UniqueOpens'] = sprintf(GetLang('EmailOpens_Unique'), $this->FormatNumber($summary['emailopens_unique']));
		$GLOBALS['TotalOpens'] = sprintf(GetLang('EmailOpens_Total'), $this->FormatNumber($summary['emailopens']));

		$total_bounces = $summary['bouncecount_unknown'] + $summary['bouncecount_hard'] + $summary['bouncecount_soft'];

		$GLOBALS['TotalBounces'] = $this->FormatNumber($total_bounces);

		// now for the opens page.
		// by default this is for all opens, not unique opens.
		$only_unique = false;
		if (isset($_GET['Unique'])) {
			$only_unique = true;
		}

		if (!isset($_GET['Unique'])) {
			$GLOBALS['OpensURL'] = 'javascript: void(0);" onclick="ShowTab(2);';

			$GLOBALS['UniqueOpensURL'] = 'index.php?Page=Stats&Action=Autoresponders&SubAction=ViewSummary&auto=' . $autoresponderid . '&tab=2&Unique';
		} else {
			$GLOBALS['UniqueOpensURL'] = 'javascript: void(0);" onclick="ShowTab(2);';

			$GLOBALS['OpensURL'] = 'index.php?Page=Stats&Action=Autoresponders&SubAction=ViewSummary&auto=' . $autoresponderid . '&tab=2';
		}

		$chosen_link = 'a';
		if (isset($_GET['link'])) {
			if (is_numeric($_GET['link'])) {
				$chosen_link = $_GET['link'];
			}
		}

		$chosen_bounce_type = '';
		if (isset($_GET['bouncetype'])) {
			$chosen_bounce_type = urldecode($_GET['bouncetype']);
		}

		$statid = $summary['statid'];

		$GLOBALS['OpensPage'] = $this->DisplayAutoresponderOpens($statid, $autoresponderid, $summary, $only_unique);

		$GLOBALS['LinksPage'] = $this->DisplayAutoresponderLinks($statid, $autoresponderid, $summary, $chosen_link);

		$GLOBALS['BouncesPage'] = $this->DisplayAutoresponderBounces($statid, $autoresponderid, $summary, $chosen_bounce_type);

		$GLOBALS['UnsubscribesPage'] = $this->DisplayAutoresponderUnsubscribes($statid, $autoresponderid, $summary);

		$GLOBALS['ForwardsPage'] = $this->DisplayAutoresponderForwards($statid, $autoresponderid, $summary);

		// explicitly pass the sessionid across to the chart
		// since it's not the browser but the server making this request, it may get a different session id if we don't, which then means it can't load the data properly.
		// especially applies to windows servers.

		$unopened = $total_sent - $summary['emailopens_unique'] - $total_bounces;
		if ($unopened < 0) {
			$unopened = 0;
		}

		$chart_url = SENDSTUDIO_APPLICATION_URL . '/admin/functions/stats_chart.php?Opens='.$summary['emailopens_unique'].'&Unopened='.$unopened.'&Bounced='.$total_bounces.'&Area=autoresponder&'.session_name().'='.session_id();

		$GLOBALS['SummaryChart'] = InsertChart(SENDSTUDIO_APPLICATION_URL . '/admin/functions/charts/charts.swf', SENDSTUDIO_APPLICATION_URL . '/admin/functions/charts/charts_library', $chart_url, "100%", 250, 'FFFFFF', false, 'J1XIJUMEW9L.HSK5T4Q79KLYCK07EK');

		// finally put it all together.
		$page = $this->ParseTemplate('Stats_Autoresponders_Step3', true, false);

		if (isset($_GET['tab'])) {
			$page .= '
			<script language="javascript">
				ShowTab(' . $_GET['tab'] . ');
			</script>
			';
		}
		echo $page;
	}

	/**
	* DisplayAutoresponderOpens
	* This displays the page of autoresponder open information based on the details passed in.
	* It will work out the calendar information, graph, paging and so on.
	*
	* @param Int $statid The statid to get information for.
	* @param Int $autoresponderid The autoresponderid to get information for.
	* @param Array $summary The basic information - start time and total number of opens.
	* @param Boolean $only_unique Whether to only show unique information or not. If this is false, all opens are shown.
	*
	* @see Stats_API::GetOpens
	* @see DisplayChart
	*
	* @return Void Doesn't return anything - just prints out the tab of information.
	*/
	function DisplayAutoresponderOpens($statid, $autoresponderid, $summary=array(), $only_unique=false)
	{
		$GLOBALS['DisplayOpensIntro'] = sprintf(GetLang('AutoresponderStatistics_Snapshot_OpenHeading'), htmlspecialchars($summary['autorespondername'], ENT_QUOTES, SENDSTUDIO_CHARSET));

		if ($only_unique) {
			$GLOBALS['DisplayOpensIntro'] = sprintf(GetLang('AutoresponderStatistics_Snapshot_OpenHeading_Unique'), htmlspecialchars($summary['autorespondername'], ENT_QUOTES, SENDSTUDIO_CHARSET));
		}

		$statsapi = $this->GetApi('Stats');

		$perpage = $this->GetPerPage();

		$GLOBALS['PPDisplayName'] = 'oc';

		$DisplayPage = (isset($_GET['DisplayPage' . $GLOBALS['PPDisplayName']])) ? (int)$_GET['DisplayPage' . $GLOBALS['PPDisplayName']] : 1;
		$start = ($DisplayPage - 1) * $perpage;

		$opens = array();

		$base_action = $GLOBALS['PPDisplayName'].'&SubAction=Autoresponders&NextAction=ViewSummary&auto=' . $autoresponderid . '&tab=2';

		if ($only_unique) {
			$base_action .= '&Unique';
		}

		$calendar_restrictions = $this->CalendarRestrictions['opens'];

		$GLOBALS['TabID'] = '2';

		$this->SetupCalendar('Action=ProcessCalendar&' . $base_action);

		// make sure unique opens are > 0 - if they aren't, something isn't tracking right anyway so no point trying anything else.
		if ($summary['emailopens_unique'] > 0) {
			$opens = $statsapi->GetOpens($statid, $start, $perpage, $only_unique, $calendar_restrictions);
		}

		/*
		* we can't rely on the counter in the summary table -
		* because you could delete subscribers.
		* and we don't want that to affect the summary table because it distorts statistics.
		*
		* So we do an actual count here for paging.
		*/
		$opencount = $statsapi->GetOpens($statid, 0, 0, $only_unique, $calendar_restrictions, true);

		// if we still don't have any opens, not sure how! but we display an error.
		if (empty($opens)) {
			if ($summary['trackopens']) {
				if ($summary['emailopens_unique'] > 0) {
					$GLOBALS['Error'] = GetLang('AutoresponderHasNotBeenOpened_CalendarProblem');
				} else {
					$GLOBALS['Error'] = GetLang('AutoresponderHasNotBeenOpened');
				}
			} else {
				$GLOBALS['Error'] = GetLang('AutoresponderWasNotOpenTracked');
			}
			$GLOBALS['Message'] = $this->ParseTemplate('ErrorMsg', true, false);
			return $this->ParseTemplate('Stats_Step3_Opens_Empty', true, false);
		}

		$emails_sent = $summary['htmlrecipients'] + $summary['textrecipients'] + $summary['multipartrecipients'];

		$GLOBALS['TotalEmails'] = $this->FormatNumber($emails_sent);
		$GLOBALS['TotalOpens'] = $this->FormatNumber($summary['emailopens']);
		$GLOBALS['TotalUniqueOpens'] = $this->FormatNumber($summary['emailopens_unique']);

		$most_opens = $statsapi->GetMostOpens($summary['statid'], $calendar_restrictions);

		$now = getdate();

		if (isset($most_opens['mth'])) {
			$GLOBALS['MostOpens'] = $this->Months[$most_opens['mth']] . ' ' . $most_opens['yr'];
		}

		if (isset($most_opens['hr'])) {
			$GLOBALS['MostOpens'] = $this->PrintDate(mktime($most_opens['hr'], 1, 1, 1, 1, $now['year']), GetLang('Daily_Time_Display'));
		}

		if (isset($most_opens['dow'])) {
			$pos = array_search($most_opens['dow'], array_keys($this->days_of_week));
			// we need to add 1 hour here otherwise we get the wrong day from strtotime.
			$GLOBALS['MostOpens'] = $this->PrintDate(strtotime("last " . $this->days_of_week[$pos] . " +1 hour"), GetLang('Date_Display_Display'));
		}

		if (isset($most_opens['dom'])) {
			$month = $now['mon'];
			// if the day-of-month is after "today", it's going to be for "last month" so adjust the month accordingly.
			if ($most_opens['dom'] > $now['mday']) {
				$month = $now['mon'] - 1;
			}
			$GLOBALS['MostOpens'] = $this->PrintDate(mktime(0, 0, 1, $month, $most_opens['dom'], $now['year']), GetLang('Date_Display_Display'));
		}

		$avg_opens = 0;
		if ($emails_sent > 0) {
			$avg_opens = $summary['emailopens'] / $emails_sent;
		}

		$GLOBALS['AverageOpens'] = $this->FormatNumber($avg_opens, 1);

		$GLOBALS['FormAction'] = 'Action=ProcessPaging' . $base_action;

		$GLOBALS['PAGE'] = 'Stats&Action=Autoresponders&SubAction=ViewSummary&auto=' . $autoresponderid . '&tab=2';

		if ($only_unique) {
			$GLOBALS['PAGE'] .= '&Unique';
		}

		$GLOBALS['AutoresponderOpenCount'] = $opencount;

		$this->SetupPaging($opencount, $DisplayPage, $perpage);

		$paging = $this->ParseTemplate('Paging', true, false);

		$GLOBALS['Paging'] = $paging;

		$open_list = '';
		foreach ($opens as $k => $opendetails) {
			$GLOBALS['EmailAddress'] = htmlspecialchars($opendetails['emailaddress'], ENT_QUOTES, SENDSTUDIO_CHARSET);
			$GLOBALS['DateOpened'] = $this->PrintTime($opendetails['opentime'], true);
			$open_list .= $this->ParseTemplate('Stats_Step3_Opens_Row', true, false);
		}
		$GLOBALS['Stats_Step3_Opens_List'] = $open_list;

		$this->DisplayChart('OpenChart', 'autoresponder', $statid);

		return $this->ParseTemplate('Stats_Step3_Opens', true, false);
	}

	/**
	* DisplayAutoresponderLinks
	* This displays the page of autoresponder link information based on the details passed in.
	* It will work out the calendar information, graph, paging and so on.
	*
	* @param Int $statid The statid to get information for.
	* @param Int $autoresponderid The autoresponderid to get information for.
	* @param Array $summary The basic information - start time and total number of link clicks.
	* @param String $chosen_link If this is present, we are showing information for a specific link. If it's not present, combine all links into one.
	*
	* @see Stats_API::GetClicks
	* @see Stats_API::GetUniqueLinks
	* @see DisplayChart
	*
	* @return Void Doesn't return anything - just prints out the tab of information.
	*/
	function DisplayAutoresponderLinks($statid, $autoresponderid, $summary=array(), $chosen_link='a')
	{
		$GLOBALS['AutoresponderID'] = (int)$autoresponderid;

		if (!is_numeric($chosen_link)) {
			$chosen_link = 'a';
		}

		$GLOBALS['StatID'] = (int)$statid;

		$GLOBALS['LinkAction'] = 'Autoresponders';
		$GLOBALS['LinkType'] = 'auto='.$autoresponderid;

		$GLOBALS['DisplayLinksIntro'] = sprintf(GetLang('AutoresponderStatistics_Snapshot_LinkHeading'), htmlspecialchars($summary['autorespondername'], ENT_QUOTES, SENDSTUDIO_CHARSET));

		$statsapi = $this->GetApi('Stats');

		$perpage = $this->GetPerPage();

		$GLOBALS['PPDisplayName'] = 'lc';

		$DisplayPage = (isset($_GET['DisplayPage' . $GLOBALS['PPDisplayName']])) ? (int)$_GET['DisplayPage' . $GLOBALS['PPDisplayName']] : 1;

		$start = ($DisplayPage - 1) * $perpage;

		$base_action = $GLOBALS['PPDisplayName'].'&SubAction=Autoresponders&NextAction=ViewSummary&auto=' . $autoresponderid . '&tab=3&link=' . $chosen_link;

		$calendar_restrictions = $this->CalendarRestrictions['clicks'];

		$GLOBALS['TabID'] = '3';

		$this->SetupCalendar('Action=ProcessCalendar&' . $base_action);

		$links = array();
		if ($summary['linkclicks'] > 0) {
			$links = $statsapi->GetClicks($statid, $start, $perpage, $chosen_link, $calendar_restrictions);
		}

		$all_links = $statsapi->GetUniqueLinks($statid);

		if (empty($all_links)) {
			$GLOBALS['DisplayStatsLinkList'] = 'none';
		}

		$all_links_list = '';
		foreach ($all_links as $p => $linkinfo) {
			$selected = '';
			if ($linkinfo['linkid'] == $chosen_link) {
				$selected = ' SELECTED';
			}
			$all_links_list .= '<option value="' . $linkinfo['linkid'] . '"' . $selected . '>' . str_replace(array("'", '"'), "", $linkinfo['url']) . '</option>';
		}
		$GLOBALS['StatsLinkList'] = $all_links_list;

		$GLOBALS['StatsLinkDropDown'] = $this->ParseTemplate('Stats_Step3_Links_List', true, false);

		if (empty($links)) {
			if ($summary['tracklinks']) {
				if (empty($all_links)) {
					$GLOBALS['Error'] = GetLang('AutoresponderHasNotBeenClicked_NoLinksFound');
				} else {
					if ($summary['linkclicks'] > 0) {
						if (is_numeric($chosen_link)) {
							if ($calendar_restrictions != '') {
								$GLOBALS['Error'] = GetLang('AutoresponderHasNotBeenClicked_CalendarLinkProblem');
							} else {
								$GLOBALS['Error'] = GetLang('AutoresponderHasNotBeenClicked_LinkProblem');
							}
						} else {
							$GLOBALS['Error'] = GetLang('AutoresponderHasNotBeenClicked_CalendarProblem');
						}
					} else {
						$GLOBALS['DisplayStatsLinkList'] = 'none';
						$GLOBALS['Error'] = GetLang('AutoresponderHasNotBeenClicked');
					}
				}
			} else {
				$GLOBALS['Error'] = GetLang('AutoresponderWasNotTracked_Links');
			}
			$GLOBALS['Message'] = $this->ParseTemplate('ErrorMsg', true, false);
			return $this->ParseTemplate('Stats_Step3_Links_Empty', true, false);
		}

		/*
		* we can't rely on the counter in the summary table -
		* because you could delete subscribers.
		* and we don't want that to affect the summary table because it distorts statistics.
		*
		* So we do an actual count here for paging.
		*/
		$summary['linkclicks'] = $statsapi->GetClicks($statid, 0, 0, $chosen_link, $calendar_restrictions, true);

		// build up the summary table.
		$GLOBALS['TotalClicks'] = $this->FormatNumber($summary['linkclicks']);

		$unique_clicks_count = $statsapi->GetUniqueClicks($statid, $chosen_link, $calendar_restrictions);
		$GLOBALS['TotalUniqueClicks'] = $this->FormatNumber($unique_clicks_count);

		$most_popular_link = $statsapi->GetMostPopularLink($statid, $chosen_link, $calendar_restrictions);

		$GLOBALS['MostPopularLink'] = htmlspecialchars($most_popular_link, ENT_QUOTES, SENDSTUDIO_CHARSET);
		$GLOBALS['MostPopularLink_Short'] = $this->TruncateName($most_popular_link, 20);

		$averageclicks = 0;
		if (isset($GLOBALS['AutoresponderOpenCount']) && (int)$GLOBALS['AutoresponderOpenCount'] > 0) {
			$open_count = (int)$GLOBALS['AutoresponderOpenCount'];
			$averageclicks = $summary['linkclicks'] / $open_count;
		}
		$GLOBALS['AverageClicks'] = $this->FormatNumber($averageclicks, 1);

		$GLOBALS['FormAction'] = 'Action=ProcessPaging' . $base_action;

		$GLOBALS['PAGE'] = 'Stats&Action=Autoresponders&SubAction=ViewSummary&auto=' . $autoresponderid . '&tab=3&link=' . $chosen_link;

		$this->SetupPaging($summary['linkclicks'], $DisplayPage, $perpage);

		$paging = $this->ParseTemplate('Paging', true, false);

		$GLOBALS['Paging'] = $paging;

		$click_list = '';
		foreach ($links as $k => $clickdetails) {
			$GLOBALS['EmailAddress'] = htmlspecialchars($clickdetails['emailaddress'], ENT_QUOTES, SENDSTUDIO_CHARSET);
			$GLOBALS['DateClicked'] = $this->PrintTime($clickdetails['clicktime'], true);

			$GLOBALS['FullURL'] = $url = str_replace(array('"', "'"), "", $clickdetails['url']);
			$url = $this->TruncateName($url, 75);

			$GLOBALS['LinkClicked'] = $url;

			$click_list .= $this->ParseTemplate('Stats_Step3_Links_Row', true, false);
		}
		$GLOBALS['Stats_Step3_Links_List'] = $click_list;

		$this->DisplayChart('LinksChart', 'autoresponder', $statid);

		return $this->ParseTemplate('Stats_Step3_Links', true, false);
	}

	/**
	* DisplayNewsletterBounces
	* This displays the page of autoresponder bounce information based on the details passed in.
	* It will work out the calendar information, graph, paging and so on.
	*
	* @param Int $statid The statid to get information for.
	* @param Int $autoresponderid The autoresponderid to get information for.
	* @param Array $summary The basic information - start time and total number of bounces.
	* @param String $chosen_bounce_type If this is present, we are showing information for a specific bounce type (hard, soft or unknown). If it's not present, combine all types.
	*
	* @see Stats_API::GetBounces
	* @see DisplayChart
	*
	* @return Void Doesn't return anything - just prints out the tab of information.
	*/
	function DisplayAutoresponderBounces($statid, $autoresponderid, $summary=array(), $chosen_bounce_type='')
	{
		$GLOBALS['DisplayBouncesIntro'] = sprintf(GetLang('AutoresponderStatistics_Snapshot_BounceHeading'), htmlspecialchars($summary['autorespondername'], ENT_QUOTES, SENDSTUDIO_CHARSET));

		$GLOBALS['BounceType'] = 'auto='.$autoresponderid;
		$GLOBALS['BounceAction'] = 'Autoresponders&auto='.$autoresponderid;

		$bouncetypelist = '';
		$all_bounce_types = array('any', 'hard', 'soft');
		if (!in_array($chosen_bounce_type, $all_bounce_types)) {
			$chosen_bounce_type = 'any';
		}

		foreach ($all_bounce_types as $p => $bounce_type) {
			$selected = '';
			if ($bounce_type == $chosen_bounce_type) {
				$selected = ' SELECTED';
			}
			$bouncetypelist .= '<option value="' . $bounce_type . '"' . $selected . '>' . GetLang('Bounce_Type_' . $bounce_type) . '</option>';
		}
		$GLOBALS['StatsBounceList'] = $bouncetypelist;

		$GLOBALS['PPDisplayName'] = 'bc'; // bounce count

		$base_action = $GLOBALS['PPDisplayName'].'&SubAction=Autoresponders&NextAction=ViewSummary&id=' . $statid . '&auto=' . $autoresponderid . '&tab=4';

		$calendar_restrictions = $this->CalendarRestrictions['bounces'];

		$GLOBALS['TabID'] = '4';

		$this->SetupCalendar('Action=ProcessCalendar&' . $base_action);

		$statsapi = $this->GetApi('Stats');

		$perpage = $this->GetPerPage();

		$DisplayPage = (isset($_GET['DisplayPage' . $GLOBALS['PPDisplayName']])) ? (int)$_GET['DisplayPage' . $GLOBALS['PPDisplayName']] : 1;

		$start = ($DisplayPage - 1) * $perpage;

		$total_bounces = $summary['bouncecount_soft'] + $summary['bouncecount_hard'] + $summary['bouncecount_unknown'];

		$bounces = array();

		if ($total_bounces > 0) {
			$bounces = $statsapi->GetBounces($statid, $start, $perpage, $chosen_bounce_type, $calendar_restrictions);
		}

		if (empty($bounces)) {
			if ($calendar_restrictions != '') {
				if ($total_bounces > 0) {
					if (!$chosen_bounce_type || $chosen_bounce_type == 'any') {
						$GLOBALS['Error'] = GetLang('AutoresponderHasNotBeenBounced_CalendarProblem');
					} else {
						$GLOBALS['Error'] = sprintf(GetLang('AutoresponderHasNotBeenBounced_CalendarProblem_BounceType'), GetLang('Bounce_Type_' . $chosen_bounce_type));
					}
				} else {
					$GLOBALS['Error'] = GetLang('AutoresponderHasNotBeenBounced');
				}
			} else {
				if ($total_bounces > 0 && (!$chosen_bounce_type || $chosen_bounce_type == 'any')) {
					$GLOBALS['Error'] = sprintf(GetLang('AutoresponderHasNotBeenBounced_BounceType'), GetLang('Bounce_Type_' . $chosen_bounce_type));
				} else {
					$GLOBALS['Error'] = GetLang('AutoresponderHasNotBeenBounced');
				}
			}
			$GLOBALS['Message'] = $this->ParseTemplate('ErrorMsg', true, false);
			return $this->ParseTemplate('Stats_Step3_Bounces_Empty', true, false);
		}

		/*
		* we can't rely on the counter in the summary table -
		* because you could delete bounced subscribers.
		* and we don't want that to affect the summary table because it distorts statistics.
		*
		* So we do an actual count here for paging.
		*/
		$total_bounces = $statsapi->GetBounces($statid, $start, $perpage, $chosen_bounce_type, $calendar_restrictions, true);

		$bounce_types_count = $statsapi->GetBounceCounts($statid, $calendar_restrictions);
		$GLOBALS['TotalBounceCount'] = $this->FormatNumber($bounce_types_count['total']);
		$GLOBALS['TotalSoftBounceCount'] = $this->FormatNumber($bounce_types_count['soft']);
		$GLOBALS['TotalHardBounceCount'] = $this->FormatNumber($bounce_types_count['hard']);

		$GLOBALS['FormAction'] = 'Action=ProcessPaging' . $base_action;

		$GLOBALS['PAGE'] = 'Stats&Action=Autoresponders&SubAction=ViewSummary&id=' . $statid . '&auto=' . $autoresponderid . '&tab=4&bouncetype=' . $chosen_bounce_type;

		$this->SetupPaging($total_bounces, $DisplayPage, $perpage);

		$paging = $this->ParseTemplate('Paging', true, false);

		$GLOBALS['Paging'] = $paging;

		$bounce_list = '';
		foreach ($bounces as $k => $bouncedetails) {
			$GLOBALS['EmailAddress'] = htmlspecialchars($bouncedetails['emailaddress'], ENT_QUOTES, SENDSTUDIO_CHARSET);
			$GLOBALS['BounceDate'] = $this->PrintTime($bouncedetails['bouncetime'], true);
			$GLOBALS['BounceType'] = GetLang('Bounce_Type_' . $bouncedetails['bouncetype']);
			$GLOBALS['BounceRule'] = GetLang('Bounce_Rule_' . $bouncedetails['bouncerule']);
			$bounce_list .= $this->ParseTemplate('Stats_Step3_Bounces_Row', true, false);
		}
		$GLOBALS['Stats_Step3_Bounces_List'] = $bounce_list;

		$this->DisplayChart('BounceChart', 'newsletter', $statid);

		return $this->ParseTemplate('Stats_Step3_Bounces', true, false);
	}

	/**
	* DisplayAutoresponderUnsubscribes
	* This displays the page of autoresponder unsubscribe information based on the details passed in.
	* It will work out the calendar information, graph, paging and so on.
	*
	* @param Int $statid The statid to get information for.
	* @param Int $autoresponderid The autoresponderid to get information for.
	* @param Array $summary The basic information - start time and total number of unsubscribes.
	*
	* @see Stats_API::GetUnsubscribes
	* @see DisplayChart
	*
	* @return Void Doesn't return anything - just prints out the tab of information.
	*/
	function DisplayAutoresponderUnsubscribes($statid, $autoresponderid, $summary=array())
	{
		$GLOBALS['DisplayUnsubscribesIntro'] = sprintf(GetLang('AutoresponderStatistics_Snapshot_UnsubscribesHeading'), htmlspecialchars($summary['autorespondername'], ENT_QUOTES, SENDSTUDIO_CHARSET));

		$GLOBALS['PPDisplayName'] = 'uc'; // unsubscribe count

		$base_action = $GLOBALS['PPDisplayName'].'&SubAction=Autoresponders&NextAction=ViewSummary&id=' . $statid . '&auto=' . $autoresponderid . '&tab=5';

		$calendar_restrictions = $this->CalendarRestrictions['unsubscribes'];

		$GLOBALS['TabID'] = '5';

		$this->SetupCalendar('Action=ProcessCalendar&' . $base_action);

		$statsapi = $this->GetApi('Stats');

		$perpage = $this->GetPerPage();

		$DisplayPage = (isset($_GET['DisplayPage' . $GLOBALS['PPDisplayName']])) ? (int)$_GET['DisplayPage' . $GLOBALS['PPDisplayName']] : 1;

		$start = ($DisplayPage - 1) * $perpage;

		$unsubscribes = array();

		if ($summary['unsubscribecount'] > 0) {
			$unsubscribes = $statsapi->GetUnsubscribes($statid, $start, $perpage, $calendar_restrictions);
		}

		if (empty($unsubscribes)) {
			if ($summary['unsubscribecount'] > 0) {
				$GLOBALS['Error'] = GetLang('AutoresponderHasNoUnsubscribes_CalendarProblem');
			} else {
				$GLOBALS['Error'] = GetLang('AutoresponderHasNoUnsubscribes');
			}
			$GLOBALS['Message'] = $this->ParseTemplate('ErrorMsg', true, false);
			return $this->ParseTemplate('Stats_Step3_Unsubscribes_Empty', true, false);
		}

		if ($calendar_restrictions != '') {
			$summary['unsubscribecount'] = $statsapi->GetUnsubscribes($statid, $start, $perpage, $calendar_restrictions, true);
		}

		$GLOBALS['TotalUnsubscribes'] = $this->FormatNumber($summary['unsubscribecount']);

		$most_unsubscribes = $statsapi->GetMostUnsubscribes($statid, $calendar_restrictions);

		$now = getdate();

		if (isset($most_unsubscribes['mth'])) {
			$GLOBALS['MostUnsubscribes'] = $this->Months[$most_unsubscribes['mth']] . ' ' . $most_unsubscribes['yr'];
		}

		if (isset($most_unsubscribes['hr'])) {
			$GLOBALS['MostUnsubscribes'] = $this->PrintDate(mktime($most_unsubscribes['hr'], 1, 1, 1, 1, $now['year']), GetLang('Daily_Time_Display'));
		}

		if (isset($most_unsubscribes['dow'])) {
			$pos = array_search($most_unsubscribes['dow'], array_keys($this->days_of_week));
			// we need to add 1 hour here otherwise we get the wrong day from strtotime.
			$GLOBALS['MostUnsubscribes'] = $this->PrintDate(strtotime("last " . $this->days_of_week[$pos] . " +1 hour"), GetLang('Date_Display_Display'));
		}

		if (isset($most_unsubscribes['dom'])) {
			$month = $now['mon'];
			// if the day-of-month is after "today", it's going to be for "last month" so adjust the month accordingly.
			if ($most_unsubscribes['dom'] > $now['mday']) {
				$month = $now['mon'] - 1;
			}
			$GLOBALS['MostUnsubscribes'] = $this->PrintDate(mktime(0, 0, 1, $month, $most_unsubscribes['dom'], $now['year']), GetLang('Date_Display_Display'));
		}

		$GLOBALS['FormAction'] = 'Action=ProcessPaging' . $base_action;

		$GLOBALS['PAGE'] = 'Stats&Action=Autoresponders&SubAction=ViewSummary&id=' . $statid . '&auto=' . $autoresponderid . '&tab=5';

		$this->SetupPaging($summary['unsubscribecount'], $DisplayPage, $perpage);

		$paging = $this->ParseTemplate('Paging', true, false);

		$GLOBALS['Paging'] = $paging;

		$unsub_list = '';
		foreach ($unsubscribes as $k => $unsubdetails) {
			$GLOBALS['EmailAddress'] = htmlspecialchars($unsubdetails['emailaddress'], ENT_QUOTES, SENDSTUDIO_CHARSET);
			$GLOBALS['UnsubscribeTime'] = $this->PrintTime($unsubdetails['unsubscribetime'], true);
			$unsub_list .= $this->ParseTemplate('Stats_Step3_Unsubscribes_Row', true, false);
		}
		$GLOBALS['Stats_Step3_Unsubscribes_List'] = $unsub_list;

		$this->DisplayChart('UnsubscribeChart', 'autoresponder', $statid);

		return $this->ParseTemplate('Stats_Step3_Unsubscribes', true, false);
	}

	/**
	* DisplayAutoresponderForwards
	* This displays the page of autoresponder forwarding information based on the details passed in.
	* It will work out the calendar information, graph, paging and so on.
	*
	* @param Int $statid The statid to get information for.
	* @param Int $autoresponderid The autoresponderid to get information for.
	* @param Array $summary The basic information - start time and total number of forwards.
	*
	* @see Stats_API::GetForwards
	* @see DisplayChart
	*
	* @return Void Doesn't return anything - just prints out the tab of information.
	*/
	function DisplayAutoresponderForwards($statid, $autoresponderid, $summary=array())
	{
		$GLOBALS['DisplayForwardsIntro'] = sprintf(GetLang('AutoresponderStatistics_Snapshot_ForwardsHeading'), htmlspecialchars($summary['autorespondername'], ENT_QUOTES, SENDSTUDIO_CHARSET));

		$GLOBALS['PPDisplayName'] = 'fc'; // forward count

		$base_action = $GLOBALS['PPDisplayName'].'&SubAction=Autoresponders&NextAction=ViewSummary&id=' . $statid . '&auto=' . $autoresponderid . '&tab=6';

		$calendar_restrictions = $this->CalendarRestrictions['forwards'];

		$GLOBALS['TabID'] = '6';

		$this->SetupCalendar('Action=ProcessCalendar&' . $base_action);

		$perpage = $this->GetPerPage();

		$DisplayPage = (isset($_GET['DisplayPage' . $GLOBALS['PPDisplayName']])) ? (int)$_GET['DisplayPage' . $GLOBALS['PPDisplayName']] : 1;

		$start = ($DisplayPage - 1) * $perpage;

		$statsapi = $this->GetApi('Stats');

		$forwards = array();

		if ($summary['emailforwards'] > 0) {
			$forwards = $statsapi->GetForwards($statid, $start, $perpage, $calendar_restrictions);
		}

		if (empty($forwards)) {
			if ($summary['emailforwards'] > 0) {
				$GLOBALS['Error'] = GetLang('AutoresponderHasNoForwards_CalendarProblem');
			} else {
				$GLOBALS['Error'] = GetLang('AutoresponderHasNoForwards');
			}
			$GLOBALS['Message'] = $this->ParseTemplate('ErrorMsg', true, false);
			return $this->ParseTemplate('Stats_Step3_Forwards_Empty', true, false);
		}

		if ($calendar_restrictions != '') {
			$summary['emailforwards'] = $statsapi->GetForwards($statid, $start, $perpage, $calendar_restrictions, true);
		}

		$GLOBALS['TotalForwards'] = $this->FormatNumber($summary['emailforwards']);

		$new_signups = $statsapi->GetForwards($statid, $start, $perpage, $calendar_restrictions, true, true);

		$GLOBALS['TotalForwardSignups'] = $this->FormatNumber($new_signups);

		$GLOBALS['FormAction'] = 'Action=ProcessPaging' . $base_action;

		$GLOBALS['PAGE'] = 'Stats&Action=Autoresponders&SubAction=ViewSummary&id=' . $statid . '&auto=' . $autoresponderid . '&tab=6';

		$this->SetupPaging($summary['emailforwards'], $DisplayPage, $perpage);

		$paging = $this->ParseTemplate('Paging', true, false);

		$GLOBALS['Paging'] = $paging;

		$forward_list = '';
		foreach ($forwards as $k => $forwarddetails) {
			$GLOBALS['ForwardedTo'] = htmlspecialchars($forwarddetails['forwardedto'], ENT_QUOTES, SENDSTUDIO_CHARSET);
			$GLOBALS['ForwardedBy'] = htmlspecialchars($forwarddetails['forwardedby'], ENT_QUOTES, SENDSTUDIO_CHARSET);
			$GLOBALS['ForwardTime'] = $this->PrintTime($forwarddetails['forwardtime'], true);
			if ($forwarddetails['subscribed'] > 0) {
				$hassubscribed = GetLang('Yes');
			} else {
				$hassubscribed = GetLang('No');
			}
			$GLOBALS['HasSubscribed'] = $hassubscribed;
			$forward_list .= $this->ParseTemplate('Stats_Step3_Forwards_Row', true, false);
		}
		$GLOBALS['Stats_Step3_Forwards_List'] = $forward_list;

		$this->DisplayChart('ForwardsChart', 'autoresponder', $statid);

		return $this->ParseTemplate('Stats_Step3_Forwards', true, false);
	}

	/**
	* PrintListStats_Step1
	* This prints out a list of mailing lists that the user can see.
	* If they can only see one list, it will take them straight to it.
	*
	* @see GetUser
	* @see User_API::HasAccess
	* @see User_API::UserAdmin
	*
	* @return Void Doesn't return anything. Prints a dropdown list of users if they are a useradmin. If they are not a useradmin, then this will take them straight to viewing their own statistics.
	*/
	function PrintListStats_Step1()
	{
		$user = &GetUser();
		$GLOBALS['Action'] = 'List';

		$user = &GetUser();
		$lists = $user->GetLists();

		$listids = array_keys($lists);

		if (sizeof($listids) < 1) {
			if ($user->CanCreateList()) {
				$GLOBALS['Message'] = $this->PrintSuccess('NoLists', GetLang('ListCreate'));
				$GLOBALS['Lists_AddButton'] = $this->ParseTemplate('List_Create_Button', true, false);
			} else {
				$GLOBALS['Message'] = $this->PrintSuccess('NoLists', GetLang('ListAssign'));
			}

			$this->ParseTemplate('Stats_List_Empty');
			return;
		}

		if (sizeof($listids) == 1) {
			$location = 'index.php?Page=Stats&Action=List&SubAction=Step2&list=' . current($listids);
			?>
			<script language="javascript">
				window.location = '<?php echo $location; ?>';
			</script>
			<?php
			exit();
		}

		$perpage = $this->GetPerPage();

		$DisplayPage = $this->GetCurrentPage();
		$start = ($DisplayPage - 1) * $perpage;

		$this->_DefaultSort = 'name';
		$this->_DefaultDirection = 'asc';

		$sortinfo = $this->GetSortDetails();

		$listapi = $this->GetApi('Lists');

		$NumberOfLists = count($listids);

		// if we're a list admin, no point checking the lists - we have access to everything.
		if ($user->ListAdmin()) {
			$check_lists = null;
		}

		$mylists = $listapi->GetLists($listids, $sortinfo, false, $start, $perpage);

		$GLOBALS['PAGE'] = 'Stats&Action=List&SubAction=Step1';

		$this->SetupPaging($NumberOfLists, $DisplayPage, $perpage);
		$GLOBALS['FormAction'] = 'Action=ProcessPaging';
		$paging = $this->ParseTemplate('Paging', true, false);

		$stats_manage = $this->ParseTemplate('Stats_Lists_Manage', true, false);

		$statsdisplay = '';

		foreach ($mylists as $pos => $listdetails) {
			$GLOBALS['ListID'] = $listdetails['listid'];
			$GLOBALS['MailingList'] = htmlspecialchars($listdetails['name'], ENT_QUOTES, SENDSTUDIO_CHARSET);
			$GLOBALS['CreateDate'] = $this->PrintDate($listdetails['createdate']);
			$GLOBALS['SubscribeCount'] = $this->FormatNumber($listdetails['subscribecount']);
			$GLOBALS['UnsubscribeCount'] = $this->FormatNumber($listdetails['unsubscribecount']);

			$GLOBALS['StatsAction'] = '<a href="index.php?Page=Stats&Action=List&SubAction=ViewSummary&list=' . $listdetails['listid'] . '">' . GetLang('ViewSummary') . '</a>';

			$statsdisplay .= $this->ParseTemplate('Stats_Lists_Manage_Row', true, false);
		}
		$stats_manage = str_replace('%%TPL_Stats_Lists_Manage_Row%%', $statsdisplay, $stats_manage);
		$stats_manage = str_replace('%%TPL_Paging%%', $paging, $stats_manage);
		$stats_manage = str_replace('%%TPL_Paging_Bottom%%', $GLOBALS['PagingBottom'], $stats_manage);

		echo $stats_manage;
	}

	/**
	* PrintListStats_Step2
	* Print mailing list statistics for the list selected. This includes all sorts of stuff.
	* It checks to make sure you're not trying to view someone elses statistics before anything else.
	*
	* @param Int $listid The listid to print statistics for.
	*
	* @see Stats_Chart::Process
	*
	* @return Void Prints out the list of statistics for this particular mailing list. It doesn't return anything.
	*/
	function PrintListStats_Step2($listid=0)
	{
		$listid = (int)$listid;

		$GLOBALS['Heading'] = GetLang('Stats_List_Step2_Heading');

		$user = &GetUser();
		$lists = $user->GetLists();
		if (!in_array($listid, array_keys($lists))) {
			$GLOBALS['SummaryIntro'] = sprintf(GetLang('Stats_List_Step2_Intro'), GetLang('Unknown_List'));
			$this->DenyAccess();
			return;
		}

		$session = &GetSession();

		include(dirname(__FILE__) . '/charts/charts.php');

		$listapi = $this->GetApi('Lists');
		$listapi->Load($listid);

		$GLOBALS['SummaryIntro'] = sprintf(GetLang('Stats_List_Step2_Intro'), htmlspecialchars($listapi->Get('name'), ENT_QUOTES, SENDSTUDIO_CHARSET));

		$GLOBALS['TabID'] = '7';

		$base_action = 'SubAction=List&NextAction=ViewSummary&list=' . $listid;

		$this->SetupCalendar('Action=ProcessCalendar&' . $base_action);

		$statsapi = $this->GetApi();

		$calendar_dates = $user->GetSettings('CalendarDates');

		$statsapi->CalculateStatsType();

		$restrictions = $calendar_dates['subscribers'];

		$summary = $statsapi->GetListSummary($listid);

		$session->Set('ListStatistics', $summary['statids']);

		$data = $statsapi->GetSubscriberGraphData($statsapi->stats_type, $restrictions, $listid);

		$domain_data = $statsapi->GetSubscriberDomainGraphData($restrictions, $listid);

		$session->Set('SubscriberGraphData', $data);

		$chart_url = SENDSTUDIO_APPLICATION_URL . '/admin/functions/stats_chart.php?Area=list&list='.$listid .'&graph=subscribersummary&'.session_name().'='.session_id();

		$GLOBALS['SummaryChart'] = InsertChart(SENDSTUDIO_APPLICATION_URL . '/admin/functions/charts/charts.swf', SENDSTUDIO_APPLICATION_URL . '/admin/functions/charts/charts_library', $chart_url, "100%", 200, 'FFFFFF', false, 'J1XIJUMEW9L.HSK5T4Q79KLYCK07EK');

		$areas = array('subscribes', 'unsubscribes', 'bounces', 'forwards');
		$now = getdate();
		$today = $now['0'];

		$totals = array('subscribes' => 0, 'unsubscribes' => 0, 'forwards' => 0, 'bounces' => 0);
		$domain_totals = array('subscribes' => 0, 'unsubscribes' => 0, 'forwards' => 0, 'bounces' => 0);

		$time_display = '';

		$this_year = date('Y');

		switch ($statsapi->calendar_type) {
			case 'today':
			case 'yesterday':
				for ($i = 0; $i < 24; $i++) {

					$server_time = AdjustTime(array($i, 1, 1, 1, 1, $this_year), true);
					$GLOBALS['Name'] = $this->PrintDate($server_time, GetLang('Daily_Time_Display'));

					foreach ($areas as $k => $area) {
						$GLOBALS[$area] = 0;
						foreach ($data[$area] as $p => $details) {
							if ($details['hr'] == $i) {
								$GLOBALS[$area] = $this->FormatNumber($details['count']);
								$totals[$area] += $details['count'];
								break;
							}
						}
						if (empty($data)) {
							break;
						}
					}
					$time_display .= $this->ParseTemplate('Stats_List_Step3_Row', true, false);
				}
			break;

			case 'last24hours':
				$hours_now = $now['hours'];

				$i = 24;
				while ($i > 0) {
					$yr = AdjustTime(array($hours_now, 1, 1, 1, 1, $this_year), true, null, true);
					$GLOBALS['Name'] = $this->PrintDate($yr, GetLang('Daily_Time_Display'));

					$hour_check = date('G', $yr);

					foreach ($areas as $k => $area) {
						$GLOBALS[$area] = 0;
						foreach ($data[$area] as $p => $details) {
							if ($details['hr'] == $hour_check) {
								$GLOBALS[$area] = $this->FormatNumber($details['count']);
								$totals[$area] += $details['count'];
								break;
							}
						}
						if (empty($data)) {
								$time_display .= $this->ParseTemplate('Stats_List_Step3_Row', true, false);
							break 2;
						}
					}
					$time_display .= $this->ParseTemplate('Stats_List_Step3_Row', true, false);

					$hours_now--;

					$i--;
				}
			break;

			case 'last7days':

				$date = AdjustTime($today, true, null, true);

				$i = 7;
				while ($i > 0) {
					$GLOBALS['Name'] = $this->PrintDate($date, GetLang('DOW_Word_Full_Display'));

					foreach ($areas as $k => $area) {
						$GLOBALS[$area] = 0;
						foreach ($data[$area] as $p => $details) {
							if ($details['dow'] == date('w', $date)) {
								$GLOBALS[$area] = $this->FormatNumber($details['count']);
								$totals[$area] += $details['count'];
								break;
							}
						}
						if (empty($data)) {
							$time_display .= $this->ParseTemplate('Stats_List_Step3_Row', true, false);
							break 2;
						}
					}
					$time_display .= $this->ParseTemplate('Stats_List_Step3_Row', true, false);

					$date = $date - 86400; // take off one day each time.

					$i--;
				}
			break;

			case 'last30days':

				$date = $today;

				$i = 30;
				while ($i > 0) {
					$GLOBALS['Name'] = $this->PrintDate($date);

					foreach ($areas as $k => $area) {
						$GLOBALS[$area] = 0;
						foreach ($data[$area] as $p => $details) {
							if ($details['dom'] == date('j', $date)) {
								$GLOBALS[$area] = $this->FormatNumber($details['count']);
								$totals[$area] += $details['count'];
								break;
							}
						}
						if (empty($data)) {
								$time_display .= $this->ParseTemplate('Stats_List_Step3_Row', true, false);
							break 2;
						}
					}
					$time_display .= $this->ParseTemplate('Stats_List_Step3_Row', true, false);

					$date = $date - 86400; // take off one day each time.

					$i--;
				}
			break;

			case 'thismonth':
			case 'lastmonth':
				if ($statsapi->calendar_type == 'thismonth') {
					$month = $now['mon'];
				} else {
					$month = $now['mon'] - 1;
				}

				$timestamp = AdjustTime(array(1, 1, 1, $month, 1, $now['year']), true);

				$days_of_month = date('t', $timestamp);

				for ($i = 1; $i <= $days_of_month; $i++) {
					$GLOBALS['Name'] = $this->PrintDate($timestamp);

					foreach ($areas as $k => $area) {
						$GLOBALS[$area] = 0;
						foreach ($data[$area] as $p => $details) {
							if ($details['dom'] == $i) {
								$GLOBALS[$area] = $this->FormatNumber($details['count']);
								$totals[$area] += $details['count'];
								break;
							}
						}
					}

					$time_display .= $this->ParseTemplate('Stats_List_Step3_Row', true, false);

					$timestamp += 86400;
				}
			break;

			default:
				if (!empty($data['subscribes'])) {
					for ($i = 1; $i <= 12; $i++) {
						$found_stats = false;
						foreach ($areas as $k => $area) {
							$GLOBALS[$area] = 0;
							foreach ($data[$area] as $p => $details) {
								if ($details['mth'] != $i) {
									continue;
								}
								$GLOBALS['Name'] = GetLang($this->Months[$i]) . ' ' . $details['yr'];

								$GLOBALS[$area] = $this->FormatNumber($details['count']);
								$totals[$area] += $details['count'];
								$found_stats = true;
							}
						}

						if (!$found_stats) {
							continue;
						}

						$time_display .= $this->ParseTemplate('Stats_List_Step3_Row', true, false);
					}
				} else {
					$GLOBALS['Name'] = '&nbsp;';
					$time_display .= $this->ParseTemplate('Stats_List_Step3_Row', true, false);
				}
			break;
		}

		$GLOBALS['DisplayList'] = $time_display;

		$domain_lines = array();

		foreach($areas as $k => $area) {
			foreach($domain_data[$area] as $p => $details) {
				if (isset($details['domainname'])) {
					$domain = $details['domainname'];
					if (!isset($domain_lines[$domain])) {
						$domain_lines[$domain] = array('subscribes' => 0, 'unsubscribes' => 0, 'forwards' => 0, 'bounces' => 0);
					}
					$domain_lines[$domain][$area] = $details['count'];
				}
			}
		}

		$graph_details = array();

		$domain_display = '';
		if (!empty($domain_lines)) {
			foreach($domain_lines as $domain_name => $domain_info) {
				$GLOBALS['Name'] = htmlspecialchars($domain_name, ENT_QUOTES, SENDSTUDIO_CHARSET);
				foreach($domain_info as $area => $count) {
					$GLOBALS[$area] = $this->FormatNumber($count);
					$domain_totals[$area] += $count;
					if ($area == 'subscribes') {
						if (!isset($graph_details[$domain_name])) {
							$graph_details[$domain_name] = 0;
						}
						$graph_details[$domain_name] += $count;
						continue;
					}
				}
				$domain_display .= $this->ParseTemplate('Stats_List_Step3_Row', true, false);
			}
		} else {
			$GLOBALS['Name'] = '';
			foreach($areas as $k => $area) {
				$GLOBALS[$area] = 0;
			}
			$domain_display .= $this->ParseTemplate('Stats_List_Step3_Row', true, false);
		}

		$session->Set('SubscriberDomains', $graph_details);

		foreach ($areas as $k => $area) {
			$GLOBALS['Total_' . $area] = $this->FormatNumber($totals[$area]);
			$GLOBALS['Total_domain_' . $area] = $this->FormatNumber($domain_totals[$area]);
		}

		$page = $this->ParseTemplate('Stats_List_Step3', true, false);

		$base_action = 'SubAction=List&NextAction=ViewSummary&list=' . $listid . '&tab=7';

		$this->SetupCalendar('Action=ProcessCalendar&' . $base_action);

		$GLOBALS['DisplayDomainList'] = $domain_display;

		if (!empty($domain_lines)) {
			$this->DisplayChart('DomainChart', 'SubscriberDomains', '0');
		}

		$subscriber_count = $listapi->Get('subscribecount');

		if ($subscriber_count <= 0) {
			$GLOBALS['Error'] = GetLang('Stats_NoSubscribersOnList');
			$GLOBALS['Message'] = $this->ParseTemplate('ErrorMsg', true, false);
			$domain_page = $this->ParseTemplate('Stats_List_Step3_Domains_Empty', true, false);
		} elseif (empty($domain_lines)) {
			$GLOBALS['Error'] = GetLang('Stats_NoSubscribersOnList_DateRange');
			$GLOBALS['Message'] = $this->ParseTemplate('ErrorMsg', true, false);
			$domain_page = $this->ParseTemplate('Stats_List_Step3_Domains_Empty', true, false);
		} else {
			$domain_page = $this->ParseTemplate('Stats_List_Step3_Domains', true, false);
		}

		$page = str_replace('%%TPL_DomainPage%%', $domain_page, $page);

		// by default this is for all opens, not unique opens.
		$unique_only = false;
		if (isset($_GET['Unique'])) {
			$unique_only = true;
		}

		$chosen_bounce_type = '';
		if (isset($_GET['bouncetype'])) {
			$chosen_bounce_type = urldecode($_GET['bouncetype']);
		}

		$chosen_link = 'a';
		if (isset($_GET['link'])) {
			if (is_numeric($_GET['link'])) {
				$chosen_link = $_GET['link'];
			}
		}

		$summary['listname'] = $listapi->Get('name');

		// we need to process the opens page first because it sets the number of opens used in a calculation for the links page.
		$open_page = $this->DisplayListOpens($listid, $summary, $unique_only);

		$links_page = $this->DisplayListLinks($listid, $summary, $chosen_link);

		$bounces_page = $this->DisplayListBounces($listid, $summary, $chosen_bounce_type);
		$unsubscribes_page = $this->DisplayListUnsubscribes($listid, $summary);
		$forwards_page = $this->DisplayListForwards($listid, $summary);

		$page = str_replace(array('%%TPL_OpensPage%%', '%%TPL_LinksPage%%', '%%TPL_BouncesPage%%', '%%TPL_UnsubscribesPage%%', '%%TPL_ForwardsPage%%'), array($open_page, $links_page, $bounces_page, $unsubscribes_page, $forwards_page), $page);

		if (isset($_GET['tab'])) {
			$page .= '
			<script language="javascript">
				ShowTab(' . $_GET['tab'] . ');
			</script>
			';
		}
		echo $page;
	}

	function DisplayListOpens($listid=0, $summary, $unique_only=false)
	{
		$GLOBALS['DisplayOpensIntro'] = sprintf(GetLang('ListStatistics_Snapshot_OpenHeading'), htmlspecialchars($summary['listname'], ENT_QUOTES, SENDSTUDIO_CHARSET));

		if ($unique_only) {
			$GLOBALS['DisplayOpensIntro'] = sprintf(GetLang('ListStatistics_Snapshot_OpenHeading_Unique'), $summary['ListStatistics_Snapshot_OpenHeading']);
		}

		$statsapi = $this->GetApi('Stats');

		$perpage = $this->GetPerPage();

		$GLOBALS['PPDisplayName'] = 'oc';

		$DisplayPage = (isset($_GET['DisplayPage' . $GLOBALS['PPDisplayName']])) ? (int)$_GET['DisplayPage' . $GLOBALS['PPDisplayName']] : 1;
		$start = ($DisplayPage - 1) * $perpage;

		$opens = array();

		$base_action = $GLOBALS['PPDisplayName'].'&SubAction=List&NextAction=ViewSummary&list=' . $listid . '&tab=2';

		if ($unique_only) {
			$base_action .= '&Unique';
		}

		$calendar_restrictions = $this->CalendarRestrictions['opens'];

		$GLOBALS['TabID'] = '2';

		$this->SetupCalendar('Action=ProcessCalendar&' . $base_action);

		/*
		* we can't rely on the counter in the summary table -
		* because you could delete subscribers.
		* and we don't want that to affect the summary table because it distorts statistics.
		*
		* So we do an actual count here for paging.
		*/
		$open_count = $statsapi->GetOpens($summary['statids'], $start, $perpage, $unique_only, $calendar_restrictions, true);

		$opens = $statsapi->GetOpens($summary['statids'], $start, $perpage, $unique_only, $calendar_restrictions);

		// if we still don't have any opens, we display an error.
		if (empty($opens)) {
			if ($summary['emailopens_unique'] > 0) {
				$GLOBALS['Error'] = GetLang('ListOpenStatsHasNotBeenOpened_CalendarProblem');
			} else {
				$GLOBALS['Error'] = GetLang('ListOpenStatsHasNotBeenOpened');
			}
			$GLOBALS['Message'] = $this->ParseTemplate('ErrorMsg', true, false);
			return $this->ParseTemplate('Stats_Step3_Opens_Empty', true, false);
		}

		$GLOBALS['TotalEmails'] = $this->FormatNumber($summary['emails_sent']);
		$GLOBALS['TotalOpens'] = $this->FormatNumber($summary['emailopens']);
		$GLOBALS['TotalUniqueOpens'] = $this->FormatNumber($summary['emailopens_unique']);

		$most_opens = $statsapi->GetMostOpens($summary['statids'], $calendar_restrictions);

		$now = getdate();

		if (isset($most_opens['mth'])) {
			$GLOBALS['MostOpens'] = $this->Months[$most_opens['mth']] . ' ' . $most_opens['yr'];
		}

		if (isset($most_opens['hr'])) {
			$GLOBALS['MostOpens'] = $this->PrintDate(mktime($most_opens['hr'], 1, 1, 1, 1, $now['year']), GetLang('Daily_Time_Display'));
		}

		if (isset($most_opens['dow'])) {
			$pos = array_search($most_opens['dow'], array_keys($this->days_of_week));
			// we need to add 1 hour here otherwise we get the wrong day from strtotime.
			$GLOBALS['MostOpens'] = $this->PrintDate(strtotime("last " . $this->days_of_week[$pos] . " +1 hour"), GetLang('Date_Display_Display'));
		}

		if (isset($most_opens['dom'])) {
			$month = $now['mon'];
			// if the day-of-month is after "today", it's going to be for "last month" so adjust the month accordingly.
			if ($most_opens['dom'] > $now['mday']) {
				$month = $now['mon'] - 1;
			}
			$GLOBALS['MostOpens'] = $this->PrintDate(mktime(0, 0, 1, $month, $most_opens['dom'], $now['year']), GetLang('Date_Display_Display'));
		}

		$avg_opens = 0;
		if ($summary['emails_sent'] > 0) {
			$avg_opens = $summary['emailopens'] / $summary['emails_sent'];
		}

		$GLOBALS['AverageOpens'] = $this->FormatNumber($avg_opens, 1);

		$GLOBALS['FormAction'] = 'Action=ProcessPaging' . $base_action;

		$GLOBALS['PAGE'] = 'Stats&Action=List&SubAction=ViewSummary&list=' . $listid . '&tab=2';

		if ($unique_only) {
			$GLOBALS['PAGE'] .= '&Unique';
		}

		$GLOBALS['ListStatsOpenCount'] = $open_count;

		$this->SetupPaging($open_count, $DisplayPage, $perpage);

		$paging = $this->ParseTemplate('Paging', true, false);

		$GLOBALS['Paging'] = $paging;

		$open_list = '';
		foreach ($opens as $k => $opendetails) {
			$GLOBALS['EmailAddress'] = $opendetails['emailaddress'];
			$GLOBALS['DateOpened'] = $this->PrintTime($opendetails['opentime'], true);
			$open_list .= $this->ParseTemplate('Stats_Step3_Opens_Row', true, false);
		}
		$GLOBALS['Stats_Step3_Opens_List'] = $open_list;

		$this->DisplayChart('OpenChart', 'list', $listid);

		return $this->ParseTemplate('Stats_Step3_Opens', true, false);
	}

	function DisplayListLinks($listid, $summary=array(), $chosen_link='a')
	{
		$GLOBALS['ListID'] = (int)$listid;

		$GLOBALS['LinkAction'] = 'List';
		$GLOBALS['LinkType'] = 'list='.$listid;

		if (!is_numeric($chosen_link)) {
			$chosen_link = 'a';
		}

		$GLOBALS['DisplayLinksIntro'] = sprintf(GetLang('ListStatistics_Snapshot_LinkHeading'), htmlspecialchars($summary['listname'], ENT_QUOTES, SENDSTUDIO_CHARSET));

		$statsapi = $this->GetApi('Stats');

		$perpage = $this->GetPerPage();

		$GLOBALS['PPDisplayName'] = 'lc';

		$DisplayPage = (isset($_GET['DisplayPage' . $GLOBALS['PPDisplayName']])) ? (int)$_GET['DisplayPage' . $GLOBALS['PPDisplayName']] : 1;

		$start = ($DisplayPage - 1) * $perpage;

		$base_action = $GLOBALS['PPDisplayName'].'&SubAction=List&NextAction=ViewSummary&list=' . $listid . '&tab=3&link=' . $chosen_link;

		$calendar_restrictions = $this->CalendarRestrictions['clicks'];

		$GLOBALS['TabID'] = '3';

		$this->SetupCalendar('Action=ProcessCalendar&' . $base_action);

		$links = array();
		if ($summary['linkclicks'] > 0) {
			$links = $statsapi->GetClicks($summary['statids'], $start, $perpage, $chosen_link, $calendar_restrictions);
		}

		$all_links = $statsapi->GetUniqueLinks($summary['statids']);

		if (empty($all_links)) {
			$GLOBALS['DisplayStatsLinkList'] = 'none';
		}

		$all_links_list = '';
		foreach ($all_links as $p => $linkinfo) {
			$selected = '';
			if ($linkinfo['linkid'] == $chosen_link) {
				$selected = ' SELECTED';
			}
			$all_links_list .= '<option value="' . $linkinfo['linkid'] . '"' . $selected . '>' . str_replace(array("'", '"'), "", $linkinfo['url']) . '</option>';
		}

		$GLOBALS['StatsLinkList'] = $all_links_list;

		$GLOBALS['StatsLinkDropDown'] = $this->ParseTemplate('Stats_Step3_Links_List', true, false);

		if (empty($links)) {
			if (empty($all_links)) {
				$GLOBALS['Error'] = GetLang('ListLinkStatsHasNotBeenClicked_NoLinksFound');
			} else {
				if ($summary['linkclicks'] > 0) {
					if (is_numeric($chosen_link)) {
						if ($calendar_restrictions != '') {
							$GLOBALS['Error'] = GetLang('ListLinkStatsHasNotBeenClicked_CalendarLinkProblem');
						} else {
							$GLOBALS['Error'] = GetLang('ListLinkStatsHasNotBeenClicked_LinkProblem');
						}
					} else {
						$GLOBALS['Error'] = GetLang('ListLinkStatsHasNotBeenClicked_CalendarProblem');
					}
				} else {
					$GLOBALS['DisplayStatsLinkList'] = 'none';
					$GLOBALS['Error'] = GetLang('ListLinkStatsHasNotBeenClicked');
				}
			}
			$GLOBALS['Message'] = $this->ParseTemplate('ErrorMsg', true, false);
			return $this->ParseTemplate('Stats_Step3_Links_Empty', true, false);
		}

		/*
		* we can't rely on the counter in the summary table -
		* because you could delete subscribers.
		* and we don't want that to affect the summary table because it distorts statistics.
		*
		* So we do an actual count here for paging.
		*/
		$summary['linkclicks'] = $statsapi->GetClicks($summary['statids'], 0, 0, $chosen_link, $calendar_restrictions, true);

		// build up the summary table.
		$GLOBALS['TotalClicks'] = $this->FormatNumber($summary['linkclicks']);

		$unique_clicks_count = $statsapi->GetUniqueClicks($summary['statids'], $chosen_link, $calendar_restrictions);
		$GLOBALS['TotalUniqueClicks'] = $this->FormatNumber($unique_clicks_count);

		$most_popular_link = $statsapi->GetMostPopularLink($summary['statids'], $chosen_link, $calendar_restrictions);

		$GLOBALS['MostPopularLink'] = htmlspecialchars($most_popular_link, ENT_QUOTES, SENDSTUDIO_CHARSET);
		$GLOBALS['MostPopularLink_Short'] = $this->TruncateName($most_popular_link, 20);

		$averageclicks = 0;
		if (isset($GLOBALS['ListStatsOpenCount']) && (int)$GLOBALS['ListStatsOpenCount'] > 0) {
			$open_count = (int)$GLOBALS['ListStatsOpenCount'];
			$averageclicks = $summary['linkclicks'] / $open_count;
		}
		$GLOBALS['AverageClicks'] = $this->FormatNumber($averageclicks, 1);

		$GLOBALS['FormAction'] = 'Action=ProcessPaging' . $base_action;

		$GLOBALS['PAGE'] = 'Stats&Action=List&SubAction=ViewSummary&list=' . $listid . '&tab=3&link=' . $chosen_link;

		$this->SetupPaging($summary['linkclicks'], $DisplayPage, $perpage);

		$paging = $this->ParseTemplate('Paging', true, false);

		$GLOBALS['Paging'] = $paging;

		$click_list = '';
		foreach ($links as $k => $clickdetails) {
			$GLOBALS['EmailAddress'] = htmlspecialchars($clickdetails['emailaddress'], ENT_QUOTES, SENDSTUDIO_CHARSET);
			$GLOBALS['DateClicked'] = $this->PrintTime($clickdetails['clicktime'], true);

			$GLOBALS['FullURL'] = $url = str_replace(array('"', "'"), "", $clickdetails['url']);
			$url = $this->TruncateName($url, 75);

			$GLOBALS['LinkClicked'] = $url;

			$click_list .= $this->ParseTemplate('Stats_Step3_Links_Row', true, false);
		}
		$GLOBALS['Stats_Step3_Links_List'] = $click_list;

		$this->DisplayChart('LinksChart', 'list', $listid);

		return $this->ParseTemplate('Stats_Step3_Links', true, false);
	}

	function DisplayListBounces($listid, $summary, $chosen_bounce_type) {
		$GLOBALS['DisplayBouncesIntro'] = sprintf(GetLang('ListStatistics_Snapshot_BounceHeading'), htmlspecialchars($summary['listname'], ENT_QUOTES, SENDSTUDIO_CHARSET));

		$GLOBALS['BounceAction'] = 'List&list=' . $listid;

		$bouncetypelist = '';
		$all_bounce_types = array('any', 'hard', 'soft');
		if (!in_array($chosen_bounce_type, $all_bounce_types)) {
			$chosen_bounce_type = 'any';
		}
		foreach ($all_bounce_types as $p => $bounce_type) {
			$selected = '';
			if ($bounce_type == $chosen_bounce_type) {
				$selected = ' SELECTED';
			}
			$bouncetypelist .= '<option value="' . $bounce_type . '"' . $selected . '>' . GetLang('Bounce_Type_' . $bounce_type) . '</option>';
		}
		$GLOBALS['StatsBounceList'] = $bouncetypelist;

		$GLOBALS['PPDisplayName'] = 'bc'; // bounce count

		$base_action = $GLOBALS['PPDisplayName'].'&SubAction=List&NextAction=ViewSummary&list=' . $listid . '&tab=4';

		$calendar_restrictions = $this->CalendarRestrictions['bounces'];

		$GLOBALS['TabID'] = '4';

		$this->SetupCalendar('Action=ProcessCalendar&' . $base_action);

		$statsapi = $this->GetApi('Stats');

		$perpage = $this->GetPerPage();

		$DisplayPage = (isset($_GET['DisplayPage' . $GLOBALS['PPDisplayName']])) ? (int)$_GET['DisplayPage' . $GLOBALS['PPDisplayName']] : 1;

		$start = ($DisplayPage - 1) * $perpage;

		$total_bounces = $summary['bouncecount_soft'] + $summary['bouncecount_hard'] + $summary['bouncecount_unknown'];

		$bounces = array();

		if ($total_bounces > 0) {
			$bounces = $statsapi->GetBounces($summary['statids'], $start, $perpage, $chosen_bounce_type, $calendar_restrictions);
		}

		if (empty($bounces)) {
			if ($calendar_restrictions != '') {
				if ($total_bounces > 0) {
					if (!$chosen_bounce_type || $chosen_bounce_type == 'any') {
						$GLOBALS['Error'] = GetLang('ListStatsHasNotBeenBounced_CalendarProblem');
					} else {
						$GLOBALS['Error'] = sprintf(GetLang('ListStatsHasNotBeenBounced_CalendarProblem_BounceType'), GetLang('Bounce_Type_' . $chosen_bounce_type));
					}
				} else {
					$GLOBALS['Error'] = GetLang('ListStatsHasNotBeenBounced');
				}
			} else {
				if ($total_bounces > 0 && (!$chosen_bounce_type || $chosen_bounce_type == 'any')) {
					$GLOBALS['Error'] = sprintf(GetLang('ListStatsHasNotBeenBounced_BounceType'), GetLang('Bounce_Type_' . $chosen_bounce_type));
				} else {
					$GLOBALS['Error'] = GetLang('ListStatsHasNotBeenBounced');
				}
			}
			$GLOBALS['Message'] = $this->ParseTemplate('ErrorMsg', true, false);
			return $this->ParseTemplate('Stats_Step3_Bounces_Empty', true, false);
		}

		/*
		* we can't rely on the counter in the summary table -
		* because you could delete bounced subscribers.
		* and we don't want that to affect the summary table because it distorts statistics.
		*
		* So we do an actual count here for paging.
		*/
		$total_bounces = $statsapi->GetBounces($summary['statids'], $start, $perpage, $chosen_bounce_type, $calendar_restrictions, true);

		$bounce_types_count = $statsapi->GetBounceCounts($summary['statids'], $calendar_restrictions);
		$GLOBALS['TotalBounceCount'] = $this->FormatNumber($bounce_types_count['total']);
		$GLOBALS['TotalSoftBounceCount'] = $this->FormatNumber($bounce_types_count['soft']);
		$GLOBALS['TotalHardBounceCount'] = $this->FormatNumber($bounce_types_count['hard']);

		$GLOBALS['FormAction'] = 'Action=ProcessPaging' . $base_action;

		$GLOBALS['PAGE'] = 'Stats&Action=List&SubAction=ViewSummary&list=' . $listid . '&tab=4&bouncetype=' . $chosen_bounce_type;

		$this->SetupPaging($total_bounces, $DisplayPage, $perpage);

		$paging = $this->ParseTemplate('Paging', true, false);

		$GLOBALS['Paging'] = $paging;

		$bounce_list = '';
		foreach ($bounces as $k => $bouncedetails) {
			$GLOBALS['EmailAddress'] = htmlspecialchars($bouncedetails['emailaddress'], ENT_QUOTES, SENDSTUDIO_CHARSET);
			$GLOBALS['BounceDate'] = $this->PrintTime($bouncedetails['bouncetime'], true);
			$GLOBALS['BounceType'] = GetLang('Bounce_Type_' . $bouncedetails['bouncetype']);
			$GLOBALS['BounceRule'] = GetLang('Bounce_Rule_' . $bouncedetails['bouncerule']);
			$bounce_list .= $this->ParseTemplate('Stats_Step3_Bounces_Row', true, false);
		}
		$GLOBALS['Stats_Step3_Bounces_List'] = $bounce_list;

		$this->DisplayChart('BounceChart', 'newsletter', $summary['statids']);

		return $this->ParseTemplate('Stats_Step3_Bounces', true, false);
	}

	function DisplayListUnsubscribes($listid, $summary)
	{
		$GLOBALS['DisplayUnsubscribesIntro'] = sprintf(GetLang('ListStatistics_Snapshot_UnsubscribesHeading'), htmlspecialchars($summary['listname'], ENT_QUOTES, SENDSTUDIO_CHARSET));

		$GLOBALS['PPDisplayName'] = 'uc'; // unsubscribe count

		$base_action = $GLOBALS['PPDisplayName'].'&SubAction=List&NextAction=ViewSummary&list=' . $listid . '&tab=5';

		$calendar_restrictions = $this->CalendarRestrictions['unsubscribes'];

		$GLOBALS['TabID'] = '5';

		$this->SetupCalendar('Action=ProcessCalendar&' . $base_action);

		$statsapi = $this->GetApi('Stats');

		$perpage = $this->GetPerPage();

		$DisplayPage = (isset($_GET['DisplayPage' . $GLOBALS['PPDisplayName']])) ? (int)$_GET['DisplayPage' . $GLOBALS['PPDisplayName']] : 1;

		$start = ($DisplayPage - 1) * $perpage;

		$unsubscribes = array();

		if ($summary['unsubscribecount'] > 0) {
			$unsubscribes = $statsapi->GetUnsubscribes($summary['statids'], $start, $perpage, $calendar_restrictions);
		}

		if (empty($unsubscribes)) {
			if ($summary['unsubscribecount'] > 0) {
				$GLOBALS['Error'] = GetLang('ListHasNoUnsubscribes_CalendarProblem');
			} else {
				$GLOBALS['Error'] = GetLang('ListHasNoUnsubscribes');
			}
			$GLOBALS['Message'] = $this->ParseTemplate('ErrorMsg', true, false);
			return $this->ParseTemplate('Stats_Step3_Unsubscribes_Empty', true, false);
		}

		if ($calendar_restrictions != '') {
			$summary['unsubscribecount'] = $statsapi->GetUnsubscribes($summary['statids'], $start, $perpage, $calendar_restrictions, true);
		}

		$GLOBALS['TotalUnsubscribes'] = $this->FormatNumber($summary['unsubscribecount']);

		$most_unsubscribes = $statsapi->GetMostUnsubscribes($summary['statids'], $calendar_restrictions);

		$now = getdate();

		if (isset($most_unsubscribes['mth'])) {
			$GLOBALS['MostUnsubscribes'] = $this->Months[$most_unsubscribes['mth']] . ' ' . $most_unsubscribes['yr'];
		}

		if (isset($most_unsubscribes['hr'])) {
			$GLOBALS['MostUnsubscribes'] = $this->PrintDate(mktime($most_unsubscribes['hr'], 1, 1, 1, 1, $now['year']), GetLang('Daily_Time_Display'));
		}

		if (isset($most_unsubscribes['dow'])) {
			$pos = array_search($most_unsubscribes['dow'], array_keys($this->days_of_week));
			// we need to add 1 hour here otherwise we get the wrong day from strtotime.
			$GLOBALS['MostUnsubscribes'] = $this->PrintDate(strtotime("last " . $this->days_of_week[$pos] . " +1 hour"), GetLang('Date_Display_Display'));
		}

		if (isset($most_unsubscribes['dom'])) {
			$month = $now['mon'];
			// if the day-of-month is after "today", it's going to be for "last month" so adjust the month accordingly.
			if ($most_unsubscribes['dom'] > $now['mday']) {
				$month = $now['mon'] - 1;
			}
			$GLOBALS['MostUnsubscribes'] = $this->PrintDate(mktime(0, 0, 1, $month, $most_unsubscribes['dom'], $now['year']), GetLang('Date_Display_Display'));
		}

		$GLOBALS['FormAction'] = 'Action=ProcessPaging' . $base_action;

		$GLOBALS['PAGE'] = 'Stats&Action=List&SubAction=ViewSummary&list=' . $listid . '&tab=5';

		$this->SetupPaging($summary['unsubscribecount'], $DisplayPage, $perpage);

		$paging = $this->ParseTemplate('Paging', true, false);

		$GLOBALS['Paging'] = $paging;

		$unsub_list = '';
		foreach ($unsubscribes as $k => $unsubdetails) {
			$GLOBALS['EmailAddress'] = $unsubdetails['emailaddress'];
			$GLOBALS['UnsubscribeTime'] = $this->PrintTime($unsubdetails['unsubscribetime'], true);
			$unsub_list .= $this->ParseTemplate('Stats_Step3_Unsubscribes_Row', true, false);
		}
		$GLOBALS['Stats_Step3_Unsubscribes_List'] = $unsub_list;

		$this->DisplayChart('UnsubscribeChart', 'list', $summary['statids']);

		return $this->ParseTemplate('Stats_Step3_Unsubscribes', true, false);
	}

	function DisplayListForwards($listid, $summary)
	{
		$GLOBALS['DisplayForwardsIntro'] = sprintf(GetLang('ListStatistics_Snapshot_ForwardsHeading'), htmlspecialchars($summary['listname'], ENT_QUOTES, SENDSTUDIO_CHARSET));

		$GLOBALS['PPDisplayName'] = 'fc'; // forward count

		$base_action = $GLOBALS['PPDisplayName'].'&SubAction=List&NextAction=ViewSummary&list=' . $listid . '&tab=6';

		$calendar_restrictions = $this->CalendarRestrictions['forwards'];

		$GLOBALS['TabID'] = '6';

		$this->SetupCalendar('Action=ProcessCalendar&' . $base_action);

		$perpage = $this->GetPerPage();

		$DisplayPage = (isset($_GET['DisplayPage' . $GLOBALS['PPDisplayName']])) ? (int)$_GET['DisplayPage' . $GLOBALS['PPDisplayName']] : 1;

		$start = ($DisplayPage - 1) * $perpage;

		$statsapi = $this->GetApi('Stats');

		$forwards = array();

		if ($summary['emailforwards'] > 0) {
			$forwards = $statsapi->GetForwards($summary['statids'], $start, $perpage, $calendar_restrictions);
		}

		if (empty($forwards)) {
			if ($summary['emailforwards'] > 0) {
				$GLOBALS['Error'] = GetLang('ListHasNoForwards_CalendarProblem');
			} else {
				$GLOBALS['Error'] = GetLang('ListHasNoForwards');
			}
			$GLOBALS['Message'] = $this->ParseTemplate('ErrorMsg', true, false);
			return $this->ParseTemplate('Stats_Step3_Forwards_Empty', true, false);
		}

		if ($calendar_restrictions != '') {
			$summary['emailforwards'] = $statsapi->GetForwards($summary['statids'], $start, $perpage, $calendar_restrictions, true);
		}

		$GLOBALS['TotalForwards'] = $this->FormatNumber($summary['emailforwards']);

		$new_signups = $statsapi->GetForwards($summary['statids'], $start, $perpage, $calendar_restrictions, true, true);

		$GLOBALS['TotalForwardSignups'] = $this->FormatNumber($new_signups);

		$GLOBALS['FormAction'] = 'Action=ProcessPaging' . $base_action;

		$GLOBALS['PAGE'] = 'Stats&Action=List&SubAction=ViewSummary&list=' . $listid . '&tab=6';

		$this->SetupPaging($summary['emailforwards'], $DisplayPage, $perpage);

		$paging = $this->ParseTemplate('Paging', true, false);

		$GLOBALS['Paging'] = $paging;

		$forward_list = '';
		foreach ($forwards as $k => $forwarddetails) {
			$GLOBALS['ForwardedTo'] = htmlspecialchars($forwarddetails['forwardedto'], ENT_QUOTES, SENDSTUDIO_CHARSET);
			$GLOBALS['ForwardedBy'] = htmlspecialchars($forwarddetails['forwardedby'], ENT_QUOTES, SENDSTUDIO_CHARSET);
			$GLOBALS['ForwardTime'] = $this->PrintTime($forwarddetails['forwardtime'], true);
			if ($forwarddetails['subscribed'] > 0) {
				$hassubscribed = GetLang('Yes');
			} else {
				$hassubscribed = GetLang('No');
			}
			$GLOBALS['HasSubscribed'] = $hassubscribed;
			$forward_list .= $this->ParseTemplate('Stats_Step3_Forwards_Row', true, false);
		}
		$GLOBALS['Stats_Step3_Forwards_List'] = $forward_list;

		$this->DisplayChart('ForwardsChart', 'list', $summary['statids']);

		return $this->ParseTemplate('Stats_Step3_Forwards', true, false);
	}
}
?>
