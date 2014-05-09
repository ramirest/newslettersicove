<?php
/**
* This file has the first welcome page functions, including quickstats.
*
* @version     $Id: index.php,v 1.19 2007/06/21 05:10:08 chris Exp $

*
* @package SendStudio
* @subpackage SendStudio_Functions
*/

/**
* Include the base sendstudio functions.
*/
require(dirname(__FILE__) . '/sendstudio_functions.php');

/**
* Class for the welcome page. Includes quickstats and so on.
*
* @package SendStudio
* @subpackage SendStudio_Functions
*/
class Index extends SendStudio_Functions
{

	/**
	* Constructor
	* Loads the language file.
	*
	* @see LoadLanguageFile
	*
	* @return Void Doesn't return anything.
	*/
	function Index()
	{
		$this->LoadLanguageFile();
	}

	/**
	* Process
	* Sets up the main page. Checks access levels and so on before printing out each option. Once the areas are set up, it simply calls the parent process function to do everything.
	*
	* @see GetSession
	* @see GetUser
	* @see User_API::HasAccess
	* @see SendStudio_Functions::Process
	*
	* @return Void Prints out the main page, doesn't return anything.
	*/
	function Process()
	{
		$session = &GetSession();
		$user = &GetUser();
		$mainareas = array(
			'lists' => array('manage', 'create', 'customfields'),
			'newsletters' => array('manage', 'create', 'send'),
			'autoresponders' => array('manage', 'create'),
			'subscribers' => array('manage', 'add'),
			'templates' => array('manage', 'create'),
			'forms' => array('manage', 'create')
		);

		$panels = array();

		foreach ($mainareas as $area => $menuitems) {
			$GLOBALS['Image'] = 'm_' . strtolower($area) . '.gif';
			$GLOBALS['PanelDesc'] = GetLang(ucwords($area) . '_Description');
			$submenu_items = '';
			foreach ($menuitems as $p => $item) {
				// handle 'customfields' separately so we can display it under the "mailing lists" submenu.
				if ($item == 'customfields') {
					$GLOBALS['PanelLink'] = 'index.php?Page=CustomFields&Action=Manage';
					$GLOBALS['PanelName'] = GetLang('CustomFields_Manage');
					if ($user->HasAccess('CustomFields', 'Manage')) {
						$submenu = $this->ParseTemplate('IndexPanelLine', true);
					} else {
						$submenu = $this->ParseTemplate('IndexPanelLine_Disabled', true);
					}
					$submenu_items .= $submenu;
					continue;
				}

				$GLOBALS['PanelLink'] = 'index.php?Page=' . $area . '&Action=' . $item;
				$GLOBALS['PanelName'] = GetLang(ucwords($area) . '_' . ucwords($item));

				if ($item == 'manage') {
					$item = null;
				}

				if ($user->HasAccess($area, $item)) {
					$submenu = $this->ParseTemplate('IndexPanelLine', true);
				} else {
					$submenu = $this->ParseTemplate('IndexPanelLine_Disabled', true);
				}
				$submenu_items .= $submenu;
			}
			$panel = $this->ParseTemplate('indexpanel', true, false);
			$subpanel = str_replace('%%TPL_IndexPanelLine%%', $submenu_items, $panel);
			$panels[ucwords($area).'Panel'] = $subpanel;
		}
		foreach ($panels as $area => $panel) {
			$GLOBALS[$area] = $panel;
		}

		$GLOBALS['QuickStats'] = $this->DisplayQuickStats();

		if (isset($_GET['Action']) && strtolower($_GET['Action']) == 'cleanupexport') {
			$session = &GetSession();
			$exportinfo = $session->Get('ExportInfo');

			if (!empty($exportinfo)) {
				$api = $this->GetApi('Jobs');

				$queueid = $exportinfo['ExportQueue'];
				if ($queueid) {
					$api->ClearQueue($queueid, 'export');
				}

				$exportfile = $exportinfo['ExportFile'];

				if (is_file(TEMP_DIRECTORY . '/' . $exportfile)) {
					if (@unlink(TEMP_DIRECTORY . '/' . $exportfile)) {
						$GLOBALS['Message'] = $this->PrintSuccess('ExportFileDeleted') . '<br/>';
						$session->Remove('ExportInfo');
					} else {
						$GLOBALS['Error'] = GetLang('ExportFileNotDeleted');
						$GLOBALS['Message'] = $this->ParseTemplate('ErrorMsg', true, false) . '<br/>';
					}
				}
			}
		}

		SendStudio_Functions::Process();
	}

	/**
	* DisplayQuickStats
	* This shows the quickstats on the right side of the main page.
	*
	* The stats may be a little off on a week where daylight savings start.
	* The calculation to get the start of week isn't quite right.
	* So what will happen is:
	* Now = Tuesday, 8pm.
	* "Last Sunday" is getting calculated as "Saturday 11pm"
	* This will only happen when daylight savings starts
	*
	* When daylight savings ends, it will go the other way and adjust it to "Sunday 1am".
	* Not a huge deal but worth noting anyway.
	*
	* @see GetSession
	* @see GetUser
	* @see GetAPI
	* @see User_API::GetLists
	* @see Stats_API::GetQuickStats
	*
	* @return Void Doesn't return anything. Prints out the stats and that's it.
	*/
	function DisplayQuickStats()
	{
		$user = &GetUser();

		$stats_api = $this->GetApi('Stats');
		$quickstats = $stats_api->GetQuickStats($user);

		foreach ($quickstats as $type => $stat) {
			foreach ($stat as $desc => $number) {
				$GLOBALS[ucwords($type).'_'.ucwords($desc)] = $this->FormatNumber($number);
			}
		}

		$now = getdate();

		$today = AdjustTime(0, true, null, true);

		$this_year = AdjustTime(array(0, 0, 1, 1, 1, $now['year']), true, null, true);
		$this_year_formatted = AdjustTime($this_year, false, GetLang('Quickstats_DateFormat'));

		$dow = date('w');

		$sunday_time = AdjustTime(array(0, 0, 1, $now['mon'], ($now['mday'] - $dow), $now['year']), true, null, true);
		$sunday = AdjustTime($sunday_time, false, GetLang('Quickstats_DateFormat'));

		$sat_timestamp = ($today + ((6 - $dow) * 86400));
		$sat_time = getdate($sat_timestamp);

		$saturday_time = AdjustTime(array(0,0,1,$sat_time['mon'], $sat_time['mday'], $sat_time['year']), true, null, true);

		$saturday = AdjustTime($saturday_time, false, GetLang('Quickstats_DateFormat'));

		$start_month_time = AdjustTime(array(0, 0, 0, $now['mon'], 1, $now['year']), true, null, true);
		$start_month = AdjustTime($start_month_time, false, GetLang('Quickstats_DateFormat'));

		$end_month = AdjustTime($today, false, GetLang('Quickstats_DateFormat'));

		$today_formatted = AdjustTime($today, false, GetLang('Quickstats_DateFormat'));

		$GLOBALS['QuickStats_Subscribes_This_week_HelpText'] = sprintf(GetLang('QuickStats_Subscribes_HelpText'), $sunday, $today_formatted);

		$GLOBALS['QuickStats_Subscribes_This_month_HelpText'] = sprintf(GetLang('QuickStats_Subscribes_HelpText'), $start_month, $end_month);

		$GLOBALS['QuickStats_Subscribes_This_year_HelpText'] = sprintf(GetLang('QuickStats_Subscribes_HelpText'), $this_year_formatted, $today_formatted);

		$GLOBALS['QuickStats_Unsubscribes_This_week_HelpText'] = sprintf(GetLang('QuickStats_Unsubscribes_HelpText'), $sunday, $today_formatted);

		$GLOBALS['QuickStats_Unsubscribes_This_month_HelpText'] = sprintf(GetLang('QuickStats_Unsubscribes_HelpText'), $start_month, $end_month);

		$GLOBALS['QuickStats_Unsubscribes_This_year_HelpText'] = sprintf(GetLang('QuickStats_Unsubscribes_HelpText'), $this_year_formatted, $today_formatted);

		return $this->ParseTemplate('QuickStats', true, false);
	}
}
?>
