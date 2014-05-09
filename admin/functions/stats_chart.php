<?php
/**
* This file does the charting for the statistics areas.
*
* @version     $Id: stats_chart.php,v 1.30 2007/06/14 07:14:41 chris Exp $

*
* @package SendStudio
* @subpackage SendStudio_Functions
*/

/**
* Since we are calling this file differently, we need to include init ourselves and then include the base sendstudio functions.
*/
require(dirname(__FILE__) . '/init.php');
require(dirname(__FILE__) . '/sendstudio_functions.php');

/**
* This file does the charting for the main index page.
* The class is called in this file (chart wouldn't work by passing it like other sendstudio pages).
* Doing it this way means easy access to all regular sendstudio functions and restrictions (eg userid's etc).
*
* @package SendStudio
* @subpackage SendStudio_Functions
*/
class StatsChart extends SendStudio_Functions
{

	/**
	* Contains an array of chart information that is used to display the chart.
	* This is shared by a few functions so it's easier to reference a class variable rather than a global variable or passing it around everywhere.
	*
	* @see Process
	* @see SetupChartDates
	* @see SetupChart_Subscribers
	* @see SetupChart
	*
	* @var Array
	*/
	var $chart = array();

	/**
	* More chart information.
	* This holds descriptions and so on for the chart.
	*
	* @see Process
	* @see SetupChartDates
	* @see SetupChart_Subscribers
	* @see SetupChart
	*/
	var $chart_details = array('bold' => false);

	/**
	* A reference to the stats api. This is used for easy access.
	*
	* @see SetupChartDates
	* @see SetupChart_Subscribers
	* @see SetupChart
	*
	* @var Object
	*/
	var $stats_api = null;

	/**
	* Constructor
	* Sets up the database connection.
	* Checks you are logged in before anything else.
	* If you are not logged in, you are kicked back to the login screen.
	*
	* @see GetSession
	* @see Session::LoggedIn
	* @see GetDatabase
	*
	* @return Void Doesn't return anything
	*/
	function StatsChart()
	{

		$session = &GetSession();
		if (!$session->LoggedIn()) {
			header('Location: ' . SENDSTUDIO_APPLICATION_URL . '/admin/index.php');
			exit();
		}

		$db = &GetDatabase();
		$this->Db = &$db;
		$this->LoadLanguageFile('Stats');

		$stats_api = $this->GetApi('Stats');
		$this->stats_api = &$stats_api;

		$this->chart['series_switch'] = false;

		$this->chart['chart_data'] = array();

		$this->chart['chart_data'][0][0] = '';
		$this->chart['chart_data'][1] = array();
		$this->chart['chart_data'][1][0] = 'Totals';

		$this->chart['chart_value_text'] = array();
		$this->chart['chart_value_text'][1] = array();

		$this->chart['chart_grid_h'] = array(
			'thickness' => 1
		);

		$this->chart['chart_grid_v'] = array(
			'thickness' => 1
		);

		$this->chart['chart_type'] = 'column';

		$this->chart['axis_category']['skip'] = 4;

		$this->chart_details['hide_zero'] = false;

		$this->chart_details['chart_position'] = 'inside';

		$this->chart_details['prefix'] = '';

		$this->chart[ 'legend_label' ] = array ( 'layout'=>"horizontal", 'bullet'=>"circle", 'font'=>"arial", 'bold'=>true, 'size'=>10, 'color'=>"000000", 'alpha'=>85 );

		$this->chart[ 'legend_rect' ] = array ( 'y'=> 170 );
		$this->chart[ 'chart_rect' ] = array ( 'y'=>10 );

		$this->chart['chart_bg'] = array (
			'positive_color'=>"000000",
			'positive_alpha'=>0,
			'negative_color'=>"FFFFFF",
			'negative_alpha'=>0
		);

		$this->chart [ 'chart_border' ] = array (
			'top_thickness'     =>  0,
			'bottom_thickness'  =>  0,
			'left_thickness'    =>  0,
			'right_thickness'   =>  0,
			'color'             =>  "CCCCCC"
		);

		$this->chart['series_color'] = array ("F98F25", "FFBE21", "84B221", "6379AD", "F74F25", "2579F7", "A925F5", "E54572", "429CBD", "9CBD42");

	}

	/**
	* Process
	* Does all of the work. Includes the chart, works out the data, prints it out.
	* It works out the type of calendar you're viewing (monthly, daily, weekly etc) and sets appropriate variables.
	* The stats api works out what type of calendar it is. It is done there so the stats file can make use of it as well for displaying date/time information.
	*
	* @see GetSession
	* @see calendar_type
	* @see daily_stats_type
	* @see stats_type
	* @see chart_details
	* @see SetupChartDates
	* @see SetupChart_Subscribers
	* @see SetupChart
	* @see Stats_API::GetSubscriberGraphData
	* @see Stats_API::GetGraphData
	* @see Stats_API::CalculateStatsType
	* @see chart
	*
	* @return Void Prints out the chard, doesn't return anything.
	*/
	function Process()
	{
		$session = &GetSession();
		$thisuser = $session->Get('UserDetails');

		$this->LoadLanguageFile('Stats');

		$this->stats_api->CalculateStatsType();

		$calendar_dates = $thisuser->GetSettings('CalendarDates');

		// http://www.maani.us/charts/index.php?menu=Reference
		include(dirname(__FILE__) . '/charts/charts.php');

		$statid = 0;
		if (isset($_GET['statid'])) {
			$statid = (int)$_GET['statid'];
		}

		$chart_area = false;
		if (isset($_GET['Area'])) {
			$chart_area = strtolower($_GET['Area']);
		}

		switch ($chart_area) {
			case 'autoresponder':
			case 'list':
			case 'subscriberdomains':
				$chart_area = ucwords($chart_area);
			break;

			default:
				$chart_area = 'Newsletter';
		}

		$chart_type = false;
		if (isset($_GET['graph'])) {
			$chart_type = strtolower($_GET['graph']);
		}

		$list_statistics = $session->Get('ListStatistics');

		if ($list_statistics) {
			$statid = $list_statistics;
		}

		switch ($chart_type) {
			case 'bouncechart':
				$restrictions = $calendar_dates['bounces'];
				$this->chart['chart_data'][1][0] = GetLang('Stats_TotalBouncedEmails');

				$this->chart['chart_type'] = 'column';
				$this->chart['chart_data'][1][0] = GetLang('SoftBounces');
				$this->chart['chart_data'][2][0] = GetLang('HardBounces');

				$this->chart [ 'chart_border' ] = array (
					'top_thickness'     =>  1,
					'bottom_thickness'  =>  1,
					'left_thickness'    =>  1,
					'right_thickness'   =>  1,
					'color'             =>  "CCCCCC"
				);

				$this->chart [ 'chart_pref' ] = array (
					//line chart preferences
					'line_thickness'  =>  1,
					'point_shape'     =>  "none",
					'fill_shape'      =>  false
				);

				$this->chart['series_color'] = array ("84B221", "E94646");
			break;

			case 'userchart':
				$restrictions = $calendar_dates['usersummary'];
				$this->chart['chart_data'][1][0] = GetLang('Stats_TotalEmailsSent');
			break;

			case 'openchart':
				$restrictions = $calendar_dates['opens'];
				$this->chart['chart_data'][1][0] = GetLang('Stats_TotalOpens');
			break;

			case 'forwardschart':
				$restrictions = $calendar_dates['forwards'];
				$this->chart['chart_data'][1][0] = GetLang('Stats_TotalForwards');
			break;

			case 'unsubscribechart':
				$restrictions = $calendar_dates['unsubscribes'];
				$this->chart['chart_data'][1][0] = GetLang('Stats_TotalUnsubscribes');
			break;

			case 'linkschart':
				$restrictions = $calendar_dates['clicks'];
				$this->chart['chart_data'][1][0] = GetLang('Stats_TotalClicks');
			break;

			case 'subscribersummary':
				$restrictions = $calendar_dates['subscribers'];

				$this->chart['chart_type'] = 'column';
				$this->chart['chart_data'][1][0] = GetLang('Subscribes');
				$this->chart['chart_data'][2][0] = GetLang('Unsubscribes');
				$this->chart['chart_data'][3][0] = GetLang('Bounces');
				$this->chart['chart_data'][4][0] = GetLang('Forwards');

				$this->chart [ 'chart_border' ] = array (
					'top_thickness'     =>  1,
					'bottom_thickness'  =>  1,
					'left_thickness'    =>  1,
					'right_thickness'   =>  1,
					'color'             =>  "CCCCCC"
				);

				$this->chart [ 'chart_pref' ] = array (
					//line chart preferences
					'line_thickness'  =>  1,
					'point_shape'     =>  "none",
					'fill_shape'      =>  false
				);

				$this->chart['series_color'] = array ("84B221", "E94646", "FFBE21", "6379AD");

				$list = 0;
				if (isset($_GET['list'])) {
					$list = (int)$_GET['list'];
				}
			break;

			default:
				// this is for the "summary" pages where it breaks down opens/unopened/bounces
				// the summary pages are all pie charts.
				$chart_type = false;

				$this->chart['axis_category']['skip'] = 0;

				$this->chart['chart_grid_h']['thickness'] = 0;
				$this->chart['chart_grid_v']['thickness'] = 0;
				$this->chart[ 'legend_rect' ] = array ( 'y'=> 20, 'height' => '60' );
				$this->chart[ 'chart_rect' ] = array ( 'y'=> 20 );

				$this->chart['chart_type'] = 'pie';

				if (strtolower($chart_area) == 'subscriberdomains') {
					$chart_title = GetLang('ListStatistics_Snapshot_PerDomain');

					$domain_details = $session->Get('SubscriberDomains');

					$total = array_sum($domain_details);

					$graph_pos = 1;

					if ($total == 0) {
						$this->chart['chart_type'] = 'column';
					} else {
						foreach ($domain_details as $domain_name => $count) {
							$percent = 0;
							if ($total > 0) {
								$percent = $this->FormatNumber(($count / $total) * 100);
							}

							$this->chart['chart_data'][0][$graph_pos] = sprintf(GetLang('Summary_Domain_Name'), $domain_name, $percent);

							$this->chart['chart_data'][1][$graph_pos] = $count;

							$this->chart['chart_value_text'][1][$graph_pos] = $this->FormatNumber($count);

							$graph_pos++;
						}
					}

					break;
				}

				$opens = $unopened = $bounces = 0;

				if (isset($_GET['Opens'])) {
					$opens = (int)$_GET['Opens'];
				}

				if (isset($_GET['Unopened'])) {
					$unopened = (int)$_GET['Unopened'];
				}

				if (isset($_GET['Bounced'])) {
					$bounces = (int)$_GET['Bounced'];
				}

				if (isset($_GET['Heading']) && $_GET['Heading'] == 'User') {
					$chart_title = GetLang('User_Summary_Graph');
				} else {
					$chart_title = GetLang($chart_area . '_Summary_Graph');
				}

				if ($opens == 0 && $bounces == 0 && $unopened == 0) {
					$unopened = 1;
				}

				$total = $opens + $unopened + $bounces;

				$opens_percent = $unopened_percent = $bounces_percent = 0;

				if ($total > 0) {
					$opens_percent = $this->FormatNumber(($opens / $total) * 100);
					$unopened_percent = $this->FormatNumber(($unopened / $total) * 100);
					$bounces_percent = $this->FormatNumber(($bounces / $total) * 100);
				}

				$this->chart['chart_data'][0][1] = sprintf(GetLang('Summary_Graph_Opened'), $opens_percent);
				$this->chart['chart_data'][0][2] = sprintf(GetLang('Summary_Graph_Unopened'), $unopened_percent);
				$this->chart['chart_data'][0][3] = sprintf(GetLang('Summary_Graph_Bounced'), $bounces_percent);

				$this->chart['chart_data'][1][1] = $opens;
				$this->chart['chart_data'][1][2] = $unopened;
				$this->chart['chart_data'][1][3] = $bounces;

				if ($opens == 0 && $unopened == 0 && $bounces == 0) {
					$this->chart['chart_type'] = 'column';
				}

				$opens_percent = $opens / 100;

				$this->chart['chart_value_text'][1][1] = $this->FormatNumber($opens);
				$this->chart['chart_value_text'][1][2] = $this->FormatNumber($unopened);
				$this->chart['chart_value_text'][1][3] = $this->FormatNumber($bounces);

		}

		if ($chart_type) {
			$chart_title = GetLang($chart_area . '_Summary_Graph_' . $chart_type);

			$this->SetupChartDates($chart_type);

			switch($chart_type) {
				case 'bouncechart':
					$data = $this->stats_api->GetBounceGraphData($this->stats_api->stats_type, $restrictions, $statid);
					$this->SetupChart_BounceSummary($data);
				break;

				case 'subscribersummary':
					$data = $session->Get('SubscriberGraphData');

					$this->SetupChart_SubscriberSummary($data);
				break;

				case 'userchart':
					$data = $session->Get('userchart_data');
					$this->SetupChart($data);
				break;

				default:
					$data = $this->stats_api->GetGraphData($statid, $this->stats_api->stats_type, $restrictions, $chart_type);

					$this->SetupChart($data);
			}
		}

		$this->chart['chart_value'] = array(
			'prefix' => $this->chart_details['prefix'],
			'decimals' => 0,
			'decimal_char' => GetLang('NumberFormat_Dec'),
			'separator' => GetLang('NumberFormat_Thousands'),
			'bold' => $this->chart_details['bold'],
			'position' => $this->chart_details['chart_position'],
			'hide_zero' => $this->chart_details['hide_zero']
		);

		$this->chart['chart_value']['size'] = 10;

		$this->chart['axis_value'] = array ('size' => 10);

		$this->chart['axis_category']['size'] = 10;

		SendChartData($this->chart);
	}

	/**
	* SetupChartDates
	* This sets default values for the charts, works out 'skip' criteria and puts the basic chart information together, ready for SetupChart_Subscribers and SetupChart to use.
	*
	* @see calendar_type
	* @see chart
	* @see SetupChart_SubscriberSummary
	* @see SetupChart
	*
	* @return Void Doesn't return anything, updates data in the chart class variable.
	*/
	function SetupChartDates($chart_type=false)
	{
		$num_areas = 1;

		if ($chart_type == 'subscribersummary') {
			$num_areas = 4;
		}

		if ($chart_type == 'bouncechart') {
			$num_areas = 2;
		}

		$now = getdate();

		switch ($this->stats_api->calendar_type) {
			case 'last24hours':
				$this->chart['axis_category']['skip'] = 2;
				/**
				* Here we go backwards so "now" is on the far right hand side
				* and yesterday is on the left
				*/
				$hours_now = $now['hours'];

				$server_time = AdjustTime(array($hours_now, 1, 1, 1, 1, $now['year']), true, null, true);

				$this->chart['chart_data'][0][24] = $this->PrintDate($server_time, GetLang('Daily_Time_Display'));

				for ($i = 1; $i <= $num_areas; $i++) {
					$this->chart['chart_data'][$i][24] = 0;
					$this->chart['chart_value_text'][$i][24] = 0;
				}

				$i = 23;
				while ($i > 0) {
					$hours_now--;

					$server_time = AdjustTime(array($hours_now, 1, 1, 1, 1, $now['year']), true, null, true);
					$this->chart['chart_data'][0][$i] = $this->PrintDate($server_time, GetLang('Daily_Time_Display'));

					for ($x = 1; $x <= $num_areas; $x++) {
						$this->chart['chart_data'][$x][$i] = 0;
						$this->chart['chart_value_text'][$x][$i] = 0;
					}

					$i--;
				}
			break;

			case 'today':
			case 'yesterday':
				$this->chart['axis_category']['skip'] = 2;

				for ($i = 1; $i <= 24; $i++) {
					$server_time = AdjustTime(array($i, 1, 1, 1, 1, $now['year']), true);
					$this->chart['chart_data'][0][$i] = $this->PrintDate($server_time, GetLang('Daily_Time_Display'));

					for ($x = 1; $x <= $num_areas; $x++) {
						$this->chart['chart_data'][$x][$i] = 0;
						$this->chart['chart_value_text'][$x][$i] = 0;
					}
				}
			break;

			case 'last7days':
				$this->chart['axis_category']['skip'] = 0;

				$today = $now['0'];

				$this->chart['chart_data'][0][7] = GetLang($this->days_of_week[$now['wday']]);

				for ($t = 1; $t <= $num_areas; $t++) {
					$this->chart['chart_data'][$t][7] = 0;
					$this->chart['chart_value_text'][$t][7] = 0;
				}

				$date = $today;
				$i = 6;
				while ($i > 0) {
					$date = $date - 86400; // take off one day each time.
					$datenow = getdate($date);
					$this->chart['chart_data'][0][$i] = GetLang($this->days_of_week[$datenow['wday']]);

					for ($x = 1; $x <= $num_areas; $x++) {
						$this->chart['chart_data'][$x][$i] = 0;
						$this->chart['chart_value_text'][$x][$i] = 0;
					}
					$i--;
				}
			break;

			case 'last30days':
				$this->chart['axis_category']['skip'] = 1;

				$today = $now['0'];
				$this->chart['chart_data'][0][30] = $this->PrintDate($today, GetLang('DOM_Number_Display'));

				for ($x = 1; $x <= $num_areas; $x++) {
					$this->chart['chart_data'][$x][30] = 0;
					$this->chart['chart_value_text'][$x][30] = 0;
				}

				$date = $today;
				$i = 29;
				while ($i > 0) {
					$date = $date - 86400; // take off one day each time.
					$this->chart['chart_data'][0][$i] = $this->PrintDate($date, GetLang('DOM_Number_Display'));

					for ($x = 1; $x <= $num_areas; $x++) {
						$this->chart['chart_data'][$x][$i] = 0;
						$this->chart['chart_value_text'][$x][$i] = 0;
					}

					$i--;
				}
			break;

			case 'thismonth':
			case 'lastmonth':
				if ($this->stats_api->calendar_type == 'thismonth') {
					$month = $now['mon'];
				} else {
					$month = $now['mon'] - 1;
				}

				$timestamp = AdjustTime(array(1, 1, 1, $month, 1, $now['year']), true);

				$days_of_month = date('t', $timestamp);

				for ($i = 1; $i <= $days_of_month; $i++) {
					$this->chart['chart_data'][0][$i] = $this->PrintDate($timestamp, GetLang('DOM_Number_Display'));

					for ($x = 1; $x <= $num_areas; $x++) {
						$this->chart['chart_data'][$x][$i] = 0;
						$this->chart['chart_value_text'][$x][$i] = 0;
					}

					$timestamp += 86400;
				}
			break;

			default:
				$this->chart['axis_category']['skip'] = 0;
				for ($i = 1; $i <= 12; $i++) {
					$this->chart['chart_data'][0][$i] = GetLang($this->Months[$i]);

					for ($x = 1; $x <= $num_areas; $x++) {
						$this->chart['chart_data'][$x][$i] = 0;
						$this->chart['chart_value_text'][$x][$i] = 0;
					}
				}
			break;
		}
	}

	/**
	* SetupChart_SubscriberSummary
	* This goes through the 4 areas (subscribes, unsubscribes, bounces and forwards) and fills in the chart data based on the data that is passed in.
	* The data is fetched by the Process function based on different criteria and passed here.
	*
	* @var Array $data The array of data to fill the graph up with. This will be a multidimensional array containing 'subscribes', 'unsubscribes', 'bounces' and 'forwards' (anything else is ignored).
	*
	* @see Process
	* @see calendar_type
	* @see days_of_week
	*
	* @return Void Doesn't return anything. Puts everything in the chart class variable.
	*/
	function SetupChart_SubscriberSummary($data=array())
	{
		$areas = array('subscribes', 'unsubscribes', 'bounces', 'forwards');
		$now = getdate();

		switch ($this->stats_api->calendar_type) {
			case 'today':
			case 'yesterday':
			case 'last24hours':
				// we have to work out which element we're updating and the easiest way is based on the "name" of the item (eg 4pm).
				foreach ($areas as $k => $area) {
					foreach ($data[$area] as $p => $details) {
						$hr = $details['hr'];
						$count = $details['count'];
						$hr_date = $this->PrintDate(mktime($hr, 1, 1, 1, 1, $now['year']), GetLang('Daily_Time_Display'));
						$pos = array_search($hr_date, $this->chart['chart_data'][0]);
						$this->chart['chart_data'][($k+1)][$pos] = $count;
						$this->chart['chart_value_text'][($k+1)][$pos] = $this->FormatNumber($count);
					}
				}

			break;

			case 'last7days':
			// we have to work out which element we're updating and the easiest way is based on the "name" of the item (eg "Sun" or "Mon").
				foreach ($areas as $k => $area) {
					foreach ($data[$area] as $p => $details) {
						$count = $details['count'];
						$dow = $details['dow'];
						$text_dow = GetLang($this->days_of_week[$dow]);
						$pos = array_search($text_dow, $this->chart['chart_data'][0]);

						$this->chart['chart_data'][($k+1)][$pos] = $count;
						$this->chart['chart_value_text'][($k+1)][$pos] = $this->FormatNumber($count);
					}
				}
			break;

			case 'thismonth':
			case 'lastmonth':
			case 'last30days':
				// we have to work out which element we're updating and the easiest way is based on the "name" of the item (eg 4pm).
				foreach ($areas as $k => $area) {
					foreach ($data[$area] as $p => $details) {
						$dom = $details['dom'];
						$count = $details['count'];
						$pos = array_search($dom, $this->chart['chart_data'][0]);
						$this->chart['chart_data'][($k+1)][$pos] = $count;
						$this->chart['chart_value_text'][($k+1)][$pos] = $this->FormatNumber($count);
					}
				}
			break;

			default:
				foreach ($areas as $k => $area) {
					foreach ($data[$area] as $p => $details) {
						$mth = $details['mth'];
						$count = $details['count'];
						$this->chart['chart_data'][($k+1)][$mth] = $count;
						$this->chart['chart_value_text'][($k+1)][$mth] = $this->FormatNumber($count);
					}
				}
			break;
		}
	}

	/**
	* SetupChart_BounceSummary
	* This goes through the 2 areas (hard, soft) and fills in the chart data based on the data that is passed in.
	* The data is fetched by the Process function based on different criteria and passed here.
	*
	* @var Array $data The array of data to fill the graph up with. This will be a multidimensional array containing 'hard' and 'soft' (anything else is ignored).
	*
	* @see Process
	* @see calendar_type
	* @see days_of_week
	*
	* @return Void Doesn't return anything. Puts everything in the chart class variable.
	*/
	function SetupChart_BounceSummary($data=array())
	{
		$now = getdate();

		switch ($this->stats_api->calendar_type) {
			case 'today':
			case 'yesterday':
			case 'last24hours':
				foreach ($data as $p => $details) {
					if ($details['bouncetype'] == 'soft') {
						$k = 1;
					} else {
						$k = 2;
					}

					$hr = $details['hr'];
					$count = $details['count'];
					$hr_date = $this->PrintDate(mktime($hr, 1, 1, 1, 1, $now['year']), GetLang('Daily_Time_Display'));
					$pos = array_search($hr_date, $this->chart['chart_data'][0]);

					$this->chart['chart_data'][$k][$pos] = $count;
					$this->chart['chart_value_text'][$k][$pos] = $this->FormatNumber($count);
				}
			break;

			case 'last7days':
				foreach ($data as $p => $details) {
					if ($details['bouncetype'] == 'soft') {
						$k = 1;
					} else {
						$k = 2;
					}

					$dow = $details['dow'];
					$count = $details['count'];
					$text_dow = GetLang($this->days_of_week[$dow]);
					$pos = array_search($text_dow, $this->chart['chart_data'][0]);

					$this->chart['chart_data'][$k][$pos] = $count;
					$this->chart['chart_value_text'][$k][$pos] = $this->FormatNumber($count);
				}
			break;

			case 'thismonth':
			case 'lastmonth':
			case 'last30days':
				foreach ($data as $p => $details) {
					if ($details['bouncetype'] == 'soft') {
						$k = 1;
					} else {
						$k = 2;
					}
					$count = $details['count'];

					$dom = $details['dom'];
					$pos = array_search($dom, $this->chart['chart_data'][0]);

					$this->chart['chart_data'][$k][$pos] = $count;
					$this->chart['chart_value_text'][$k][$pos] = $this->FormatNumber($count);
				}
			break;

			default:
				foreach ($data as $p => $details) {
					if ($details['bouncetype'] == 'soft') {
						$k = 1;
					} else {
						$k = 2;
					}

					$mth = $details['mth'];
					$count = $details['count'];
					$this->chart['chart_data'][$k][$mth] = $count;
					$this->chart['chart_value_text'][$k][$mth] = $this->FormatNumber($count);
				}
			break;
		}
	}

	/**
	* SetupChart
	* This goes through the data passed in and fills in the graph info.
	* The data is fetched by the Process function based on different criteria and passed here.
	*
	* @var Array $data The array of data to fill the graph up with.
	*
	* @see Process
	* @see calendar_type
	* @see days_of_week
	*
	* @return Void Doesn't return anything. Puts everything in the chart class variable.
	*/
	function SetupChart($data=array())
	{
		$now = getdate();

		switch ($this->stats_api->calendar_type) {
			case 'today':
			case 'yesterday':
			case 'last24hours':
				// we have to work out which element we're updating and the easiest way is based on the "name" of the item (eg 4pm).
				foreach ($data as $p => $details) {
					$hr = $details['hr'];
					$count = $details['count'];
					$hr_date = $this->PrintDate(mktime($hr, 1, 1, 1, 1, $now['year']), GetLang('Daily_Time_Display'));
					$pos = array_search($hr_date, $this->chart['chart_data'][0]);
					$this->chart['chart_data'][1][$pos] = $count;
					$this->chart['chart_value_text'][1][$pos] = $this->FormatNumber($count);
				}
			break;

			case 'last7days':
				// we have to work out which element we're updating and the easiest way is based on the "name" of the item (eg 4pm).
				foreach ($data as $p => $details) {
					$count = $details['count'];

					$dow = $details['dow'];
					$text_dow = GetLang($this->days_of_week[$dow]);
					$pos = array_search($text_dow, $this->chart['chart_data'][0]);

					$this->chart['chart_data'][1][$pos] = $count;
					$this->chart['chart_value_text'][1][$pos] = $this->FormatNumber($count);
				}
			break;

			case 'thismonth':
			case 'lastmonth':
			case 'last30days':
				// we have to work out which element we're updating and the easiest way is based on the "name" of the item (eg 4pm).
				foreach ($data as $p => $details) {
					$dom = $details['dom'];
					$count = $details['count'];
					$pos = array_search($dom, $this->chart['chart_data'][0]);
					$this->chart['chart_data'][1][$pos] = $count;
					$this->chart['chart_value_text'][1][$pos] = $this->FormatNumber($count);
				}
			break;

			default:
				foreach ($data as $p => $details) {
					$mth = $details['mth'];
					$count = $details['count'];
					$this->chart['chart_data'][1][$mth] = $count;
					$this->chart['chart_value_text'][1][$mth] = $this->FormatNumber($count);
				}
			break;
		}
	}

}

/**
* We need to call the chart ourselves because of the way the chart needs to get the data.
*/
$SSChart = &new StatsChart();
$SSChart->Process();

?>
