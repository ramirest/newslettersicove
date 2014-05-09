<?php
/**
* The Stats API.
*
* @version     $Id: stats.php,v 1.61 2007/06/21 05:10:08 chris Exp $

*
* @package API
* @subpackage User_API
*/

/**
* Include the base api class if we need to.
*/
require_once(dirname(__FILE__) . '/api.php');

/**
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
* @package API
* @subpackage User_API
*/
class Stats_API extends API
{

	/**
	* Types of charts that are 'daily' views.
	*
	* @see Process
	* @see SetupChartDates
	* @see SetupChart_Subscribers
	* @see SetupChart
	*
	* @var Array
	*/
	var $daily_stats_types = array('today', 'yesterday', 'last24hours');

	/**
	* Types of charts that are 'monthly' views.
	*
	* @see Process
	* @see SetupChartDates
	* @see SetupChart_Subscribers
	* @see SetupChart
	*
	* @var Array
	*/
	var $monthly_stats_types = array('thismonth', 'lastmonth', 'last30days');

	/**
	* The type of stats we're looking at. This is passed to the stats api to work out queries.
	*
	* @var String
	*/
	var $stats_type = false;

	/**
	* The calendar type of stats we're looking at. This is used to work out dates and views for the stats we're displaying.
	*
	* @see SetupChartDates
	* @see SetupChart_Subscribers
	* @see SetupChart
	*
	* @var String
	*/
	var $calendar_type = false;

	/**
	* Constructor
	* Sets up the database object.
	*
	* @return True Always returns true.
	*/
	function Stats_API()
	{
		$this->GetDb();
		return true;
	}

	function Delete($statids=array(), $statstype='n')
	{
		if (!is_array($statids)) {
			$statids = array($statids);
		}

		$statids = $this->CheckIntVars($statids);
		if (empty($statids)) {
			return false;
		}

		$table = $this->GetStatsTable($statstype);
		if (!$table) {
			return false;
		}

		$query = "DELETE FROM " . SENDSTUDIO_TABLEPREFIX . $table . " WHERE statid IN (" . implode(',', $statids). ")";
		$result = $this->Db->Query($query);
		if (!$result) {
			return false;
		}

		if ($statstype == 'n') {
			$query = "DELETE FROM " . SENDSTUDIO_TABLEPREFIX . "stats_newsletter_lists WHERE statid IN (" . implode(',', $statids). ")";
			$result = $this->Db->Query($query);
		}

		/**
		* Clean up old links.
		*/
		$query = "DELETE FROM " . SENDSTUDIO_TABLEPREFIX . "stats_links WHERE statid IN (" . implode(',', $statids). ")";
		$result = $this->Db->Query($query);

		/**
		* Clean up old link clicks.
		*/
		$query = "DELETE FROM " . SENDSTUDIO_TABLEPREFIX . "stats_linkclicks WHERE statid IN (" . implode(',', $statids). ")";
		$result = $this->Db->Query($query);

		/**
		* Clean up opening records.
		*/
		$query = "DELETE FROM " . SENDSTUDIO_TABLEPREFIX . "stats_emailopens WHERE statid IN (" . implode(',', $statids). ")";
		$result = $this->Db->Query($query);

		/**
		* Clean up forwarding records.
		*/
		$query = "DELETE FROM " . SENDSTUDIO_TABLEPREFIX . "stats_emailforwards WHERE statid IN (" . implode(',', $statids). ")";
		$result = $this->Db->Query($query);

		if ($statstype == 'a') {
			$query = "UPDATE " . SENDSTUDIO_TABLEPREFIX . "autoresponders SET statid=0 WHERE statid IN (" . implode(',', $statids). ")";
			$result = $this->Db->Query($query);
		}

		return true;
	}

	/**
	* HideStats
	* Hiding statistics does not actually delete them.
	* Instead it records who called "Hide" (ie "delete" in the admin area) so we know who wanted to delete the stuff.
	* We do this instead of actually deleting them because deleting records here will affect user statistics
	* While the number of emails sent is recorded in a summary table, we use the stats_newsletters & stats_autoresponders tables to get the number of emails sent, number of emails opened and so on (both for user stats & mailing list stats).
	* So instead of trying to duplicate their work into summary tables, we "Hide" stats and they don't show up when you go to a specific area in the statistics section.
	*
	* @param Array $statids The statids you want to hide.
	* @param String $statstype The type of statistics you want to hide (newsletter / autoresponder)
	* @param Int $userid The userid of the person who requested the "hiding" of statistics.
	*
	* @return Boolean Returns false if statistics couldn't be hidden (invalid arguments passed in for example), otherwise returns true.
	*/
	function HideStats($statids, $statstype='n', $userid=0)
	{
		if (!is_array($statids)) {
			$statids = array($statids);
		}

		$statids = $this->CheckIntVars($statids);
		if (empty($statids)) {
			return false;
		}

		$table = $this->GetStatsTable($statstype);
		if (!$table) {
			return false;
		}

		$userid = (int)$userid;
		if ($userid <= 0) {
			return false;
		}

		$query = "UPDATE " . SENDSTUDIO_TABLEPREFIX . $table . " SET hiddenby='" . $userid . "' WHERE statid IN (" . implode(',', $statids). ")";
		$result = $this->Db->Query($query);
		if (!$result) {
			list($error, $level) = $this->Db->GetError();
			trigger_error($error, $level);
			return false;
		}

		if ($statstype == 'n') {
			$query = "UPDATE " . SENDSTUDIO_TABLEPREFIX . "stats_newsletter_lists SET hiddenby='" . $userid . "' WHERE statid IN (" . implode(',', $statids). ")";
			$result = $this->Db->Query($query);
		}

		if ($statstype == 'a') {
			$query = "UPDATE " . SENDSTUDIO_TABLEPREFIX . "autoresponders SET statid=0 WHERE statid IN (" . implode(',', $statids). ")";
			$result = $this->Db->Query($query);
		}

		return true;
	}

	function GetStatsTable($statstype=false)
	{
		$statstype = strtolower(substr($statstype, 0, 1));
		switch ($statstype) {
			case 'n':
				$table = 'stats_newsletters';
			break;
			case 'a':
				$table = 'stats_autoresponders';
			break;
			default:
				$table = false;
		}
		return $table;
	}

	function FetchStats($statid=0, $statstype=false)
	{
		$statid = (int)$statid;
		if ($statid <= 0) {
			return false;
		}

		$table = $this->GetStatsTable($statstype);
		if (!$table) {
			return false;
		}

		$query = "SELECT * FROM " . SENDSTUDIO_TABLEPREFIX . $table . " WHERE statid='" . $statid . "'";

		$result = $this->Db->Query($query);
		$statsdetails = $this->Db->Fetch($result);

		if ($statstype{0} == 'a') {
			$query = "SELECT listid FROM " . SENDSTUDIO_TABLEPREFIX . "autoresponders WHERE autoresponderid='" . (int)$statsdetails['autoresponderid'] . "'";
			$result = $this->Db->Query($query);
			$lists[] = $this->Db->FetchOne($result, 'listid');
		} else {
			$lists = array();
			$listtable = substr($table, 0, -1) . "_lists"; // take the "S" off the end of the table name.
			$query = "SELECT listid FROM " . SENDSTUDIO_TABLEPREFIX . $listtable . " WHERE statid='" . $statid . "'";
			$result = $this->Db->Query($query);
			while ($row = $this->Db->Fetch($result)) {
				$lists[] = $row['listid'];
			}
		}

		$statsdetails['Lists'] = $lists;
		return $statsdetails;
	}

	function GetBounceGraphData($stats_type=false, $calendar_restrictions='', $statids=array())
	{
		if (!is_array($statids)) {
			$statids = array($statids);
		}

		$statids = $this->CheckIntVars($statids);
		if (empty($statids)) {
			return array();
		}

		// then bounces
		$bounces_query = "SELECT count(bounceid) AS count, bouncetype, ";
		$bounces_query .= $this->CalculateGroupBy($stats_type, 'bouncetime');
		$bounces_query .= " FROM " . SENDSTUDIO_TABLEPREFIX . "list_subscriber_bounces";
		$bounces_query .= " WHERE statid IN (" . implode(',', $statids) . ")";
		if ($calendar_restrictions) {
			$bounces_query .= $calendar_restrictions;
		}

		switch ($stats_type) {
			case 'daily':
				$general_query = ' GROUP BY hr';
			break;

			case 'last7days':
				$general_query = ' GROUP BY dow';
			break;

			case 'last30days':
			case 'monthly':
				$general_query = ' GROUP BY dom';
			break;

			default:
				$general_query = ' GROUP BY mth, yr';
		}

		$general_query .= ', bouncetype';

		$result = $this->Db->Query($bounces_query . $general_query);

		while ($row = $this->Db->Fetch($result)) {
			$row['bouncetype'] = $row['bouncetype'];
			$return[] = $row;
		}
		return $return;
	}

	function GetSubscriberGraphData($stats_type=false, $restrictions=array(), $listid=0)
	{
		$return = array(
			'subscribes' => array(),
			'unsubscribes' => array(),
			'bounces' => array(),
			'forwards' => array()
		);

		// first we'll do subscribes.
		$subscribes_query = "SELECT COUNT(subscriberid) AS count, ";
		$subscribes_query .= $this->CalculateGroupBy($stats_type, 'subscribedate');
		$subscribes_query .= " FROM " . SENDSTUDIO_TABLEPREFIX . "list_subscribers";
		$subscribes_query .= " WHERE listid='" . $listid . "' AND (bounced = 0 AND unsubscribed = 0)";
		if ($restrictions['subscribes']) {
			$subscribes_query .= $restrictions['subscribes'];
		}

		// then unsubscribes
		$unsubscribes_query = "SELECT COUNT(subscriberid) AS count, ";
		$unsubscribes_query .= $this->CalculateGroupBy($stats_type, 'unsubscribetime');
		$unsubscribes_query .= " FROM " . SENDSTUDIO_TABLEPREFIX . "list_subscribers_unsubscribe";
		$unsubscribes_query .= " WHERE listid='" . $listid . "' AND unsubscribetime > 0";
		if ($restrictions['unsubscribes']) {
			$unsubscribes_query .= $restrictions['unsubscribes'];
		}

		// then bounces
		$bounces_query = "SELECT COUNT(subscriberid) AS count, ";
		$bounces_query .= $this->CalculateGroupBy($stats_type, 'bouncetime');
		$bounces_query .= " FROM " . SENDSTUDIO_TABLEPREFIX . "list_subscriber_bounces";
		$bounces_query .= " WHERE listid='" . $listid . "'";
		if ($restrictions['bounces']) {
			$bounces_query .= $restrictions['bounces'];
		}

		// then forwards
		$forwards_query = "SELECT COUNT(subscriberid) AS count, ";
		$forwards_query .= $this->CalculateGroupBy($stats_type, 'forwardtime');
		$forwards_query .= " FROM " . SENDSTUDIO_TABLEPREFIX . "stats_emailforwards";
		$forwards_query .= " WHERE listid='" . $listid . "'";
		if ($restrictions['forwards']) {
			$forwards_query .= $restrictions['forwards'];
		}

		switch ($stats_type) {
			case 'daily':
				$general_query = ' GROUP BY hr';
			break;

			case 'last7days':
				$general_query = ' GROUP BY dow';
			break;

			case 'last30days':
			case 'monthly':
				$general_query = ' GROUP BY dom';
			break;

			default:
				$general_query = ' GROUP BY mth, yr';
		}

		$result = $this->Db->Query($subscribes_query . $general_query);

		while ($row = $this->Db->Fetch($result)) {
			$return['subscribes'][] = $row;
		}

		$result = $this->Db->Query($unsubscribes_query . $general_query);

		while ($row = $this->Db->Fetch($result)) {
			$return['unsubscribes'][] = $row;
		}

		$result = $this->Db->Query($bounces_query . $general_query);

		while ($row = $this->Db->Fetch($result)) {
			$return['bounces'][] = $row;
		}

		$result = $this->Db->Query($forwards_query . $general_query);

		while ($row = $this->Db->Fetch($result)) {
			$return['forwards'][] = $row;
		}

		return $return;
	}

	function GetSubscriberDomainGraphData($restrictions=array(), $listid=0, $limit=10)
	{
		$return = array(
			'subscribes' => array(),
			'unsubscribes' => array(),
			'bounces' => array(),
			'forwards' => array()
		);

		if (SENDSTUDIO_DATABASE_TYPE == 'mysql') {
			$subscribes_query  = "SELECT COUNT(l.subscriberid) AS count, SUBSTRING(l.emailaddress, LOCATE('@', l.emailaddress) + 1) AS domainname";
			$subscribes_query .= " FROM " . SENDSTUDIO_TABLEPREFIX . "list_subscribers l";
		#	$subscribes_query .= " WHERE l.listid='" . $listid . "' AND (bounced = 0 AND unsubscribed = 0)";
			$subscribes_query .= " WHERE l.listid='" . $listid . "'";
			if ($restrictions['subscribes']) {
				$subscribes_query .= $restrictions['subscribes'];
			}

			$general_query = " GROUP BY SUBSTRING(l.emailaddress, LOCATE('@', l.emailaddress) + 1)";
		}

		if (SENDSTUDIO_DATABASE_TYPE == 'pgsql') {
			$subscribes_query  = "SELECT COUNT(l.subscriberid) AS count, SUBSTRING(l.emailaddress, POSITION('@' IN l.emailaddress) + 1) AS domainname";
			$subscribes_query .= " FROM " . SENDSTUDIO_TABLEPREFIX . "list_subscribers l";
			$subscribes_query .= " WHERE l.listid='" . $listid . "' AND (bounced = 0 AND unsubscribed = 0)";
			if ($restrictions['subscribes']) {
				$subscribes_query .= $restrictions['subscribes'];
			}

			$general_query = " GROUP BY SUBSTRING(l.emailaddress, POSITION('@' IN l.emailaddress) + 1)";

		}

		$general_query .= " ORDER BY count DESC LIMIT " . $limit;

		$domain_general_query = '';

		$general_start_query = "SELECT COUNT(l.subscriberid) AS count FROM " . SENDSTUDIO_TABLEPREFIX . "list_subscribers l";
		$general_start_query .= " WHERE l.listid='" . $listid . "'";

		$result = $this->Db->Query($subscribes_query . $general_query);
		while ($row = $this->Db->Fetch($result)) {
			$domain_general_query .= "l.emailaddress LIKE '%" . $this->Db->Quote($row['domainname']) . "' AND ";

			$row['domainname'] = $row['domainname'];
			$return['subscribes'][] = $row;
		}
		$domain_general_query .= ' 1=1';

		$unsubscribes_query = $general_start_query . " AND unsubscribed > 0 AND " . $domain_general_query;
		if ($restrictions['unsubscribes']) {
			$unsubscribes_query .= str_replace('unsubscribetime', 'unsubscribed', $restrictions['unsubscribes']);
		}

		$result = $this->Db->Query($unsubscribes_query . $general_query);
		while ($row = $this->Db->Fetch($result)) {
			$return['unsubscribes'][] = $row;
		}

		$bounces_query = $general_start_query . " AND bounced > 0 AND " . $domain_general_query;
		if ($restrictions['bounces']) {
			$bounces_query .= str_replace('bouncetime', 'bounced', $restrictions['bounces']);
		}

		$result = $this->Db->Query($bounces_query . $general_query);
		while ($row = $this->Db->Fetch($result)) {
			$return['bounces'][] = $row;
		}

		$forwards_query = "SELECT COUNT(l.subscriberid) AS count FROM " . SENDSTUDIO_TABLEPREFIX . "stats_emailforwards l";
		$forwards_query .= " WHERE l.listid='" . $listid . "'";
		$forwards_query .= " AND " . $domain_general_query;
		if ($restrictions['forwards']) {
			$forwards_query .= $restrictions['forwards'];
		}

		$result = $this->Db->Query($forwards_query . $general_query);
		while ($row = $this->Db->Fetch($result)) {
			$return['forwards'][] = $row;
		}
		return $return;
	}

	function GetGraphData($statids=array(), $stats_type=false, $restrictions='', $chart_type='')
	{
		if (!is_array($statids)) {
			$statids = array($statids);
		}

		$statids = $this->CheckIntVars($statids);
		if (empty($statids)) {
			return array();
		}

		switch ($chart_type) {
			case 'openchart':
				$countid = 'openid';
				$table = 'stats_emailopens';
				$field_restrictor = 'opentime';
			break;

			case 'unsubscribechart':
				$countid = 'subscriberid';
				$table = 'list_subscribers_unsubscribe';
				$field_restrictor = 'unsubscribetime';
				$restrictions .= " AND unsubscribetime > 0";
			break;

			case 'forwardschart':
				$countid = 'forwardid';
				$table = 'stats_emailforwards';
				$field_restrictor = 'forwardtime';
			break;

			case 'linkschart':
				$countid = 'clickid';
				$table = 'stats_linkclicks';
				$field_restrictor = 'clicktime';
			break;
		}

		$query = "SELECT COUNT(" . $countid . ") AS count, ";
		$query .= $this->CalculateGroupBy($stats_type, $field_restrictor);
		$query .= " FROM " . SENDSTUDIO_TABLEPREFIX . $table;

		$query .= " WHERE statid IN(" . implode(',', $statids) . ") ";

		if ($restrictions) {
			$query .= $restrictions;
		}

		switch ($stats_type) {
			case 'daily':
				$query .= ' GROUP BY hr';
			break;

			case 'last7days':
				$query .= ' GROUP BY dow';
			break;

			case 'monthly':
				$query .= ' GROUP BY dom';
			break;

			default:
				$query .= ' GROUP BY mth, yr';
		}

		$return = array();

		$result = $this->Db->Query($query);
		while ($row = $this->Db->Fetch($result)) {
			$return[] = $row;
		}
		return $return;
	}

	function CalculateGroupBy($stats_type=false, $fieldname='')
	{
		if (SENDSTUDIO_DATABASE_TYPE == 'mysql') {
			switch ($stats_type) {
				case 'today':
				case 'yesterday':
				case 'daily':
				case 'last24hours':
					$query = " EXTRACT(hour FROM FROM_UNIXTIME(" . $fieldname . ")) AS hr";
				break;

				case 'last7days':
					$query = " DATE_FORMAT(FROM_UNIXTIME(" . $fieldname . "), '%w') AS dow";
				break;

				case 'last30days':
				case 'thismonth':
				case 'lastmonth':
				case 'monthly':
					$query = " EXTRACT(day FROM FROM_UNIXTIME(" . $fieldname . ")) AS dom";
				break;
				default:
					$query = " EXTRACT(month FROM FROM_UNIXTIME(" . $fieldname . ")) AS mth, EXTRACT(year FROM FROM_UNIXTIME(" . $fieldname . ")) AS yr";
			}
		}

		if (SENDSTUDIO_DATABASE_TYPE == 'pgsql') {
			switch ($stats_type) {
				case 'today':
				case 'yesterday':
				case 'daily':
				case 'last24hours':
					$query = "EXTRACT(hour FROM TIMESTAMP WITH TIME ZONE 'epoch' + " . $fieldname . " * INTERVAL '1 second') AS hr";
				break;

				case 'last7days':
					$query = "EXTRACT(dow FROM TIMESTAMP WITH TIME ZONE 'epoch' + " . $fieldname . " * INTERVAL '1 second') AS dow";
				break;

				case 'last30days':
				case 'thismonth':
				case 'lastmonth':
				case 'monthly':
					$query = "EXTRACT(day FROM TIMESTAMP WITH TIME ZONE 'epoch' + " . $fieldname . " * INTERVAL '1 second') AS dom";
				break;

				default:
					$query = "EXTRACT(month FROM TIMESTAMP WITH TIME ZONE 'epoch' + " . $fieldname . " * INTERVAL '1 second') AS mth, EXTRACT(year FROM TIMESTAMP WITH TIME ZONE 'epoch' + " . $fieldname . " * INTERVAL '1 second') AS yr";
			}
		}
		return $query;
	}

	function GetMostPopularLink($statids=array(), $linkid='a', $calendar_restrictions='')
	{
		if (!is_array($statids)) {
			$statids = array($statids);
		}

		$statids = $this->CheckIntVars($statids);
		if (empty($statids)) {
			$statids = array('0');
		}

		$query = "SELECT l.url AS url, COUNT(clickid) AS linkcount FROM " . SENDSTUDIO_TABLEPREFIX . "links l, " . SENDSTUDIO_TABLEPREFIX . "stats_linkclicks lc WHERE l.linkid=lc.linkid AND lc.statid IN (" . implode(',', $statids) . ") " . $calendar_restrictions;

		if (is_numeric($linkid)) {
			$query .= " AND l.linkid='" . $linkid . "'";
		}

		$query .= " GROUP BY l.url ORDER BY linkcount DESC LIMIT 1";

		$result = $this->Db->Query($query);
		$row = $this->Db->Fetch($result);
		$url = str_replace(array('"', "'"), '', $row['url']);
		return $url;
	}

	function GetUniqueClicks($statids=array(), $linkid='a', $calendar_restrictions='')
	{
		if (!is_array($statids)) {
			$statids = array($statids);
		}

		$statids = $this->CheckIntVars($statids);
		if (empty($statids)) {
			$statids = array('0');
		}

		$query = "SELECT COUNT(DISTINCT lc.linkid) AS count FROM " . SENDSTUDIO_TABLEPREFIX . "stats_linkclicks lc, " . SENDSTUDIO_TABLEPREFIX . "links ml WHERE ml.linkid=lc.linkid AND lc.statid IN(" . implode(',', $statids) . ") " . $calendar_restrictions;

		if (is_numeric($linkid)) {
			$query .= " AND ml.linkid='" . $linkid . "'";
		}

		$result = $this->Db->Query($query);
		return $this->Db->FetchOne($result, 'count');
	}

	function GetClicks($statids=array(), $start=0, $perpage=10, $linkid='a', $calendar_restrictions='', $count_only=false)
	{
		if (!is_array($statids)) {
			$statids = array($statids);
		}

		$statids = $this->CheckIntVars($statids);
		if (empty($statids)) {
			$statids = array('0');
		}

		if ($count_only) {
			$query = "SELECT COUNT(l.emailaddress) AS count FROM " . SENDSTUDIO_TABLEPREFIX . "list_subscribers l, " . SENDSTUDIO_TABLEPREFIX . "stats_linkclicks lc, " . SENDSTUDIO_TABLEPREFIX . "links ml WHERE ml.linkid=lc.linkid AND l.subscriberid=lc.subscriberid AND lc.statid IN(" . implode(',', $statids) . ") " . $calendar_restrictions;

			if (is_numeric($linkid)) {
				$query .= " AND ml.linkid='" . $linkid . "'";
			}
			$result = $this->Db->Query($query);
			return $this->Db->FetchOne($result, 'count');
		}

		$query = "SELECT l.emailaddress, clicktime, clickip, url FROM " . SENDSTUDIO_TABLEPREFIX . "list_subscribers l, " . SENDSTUDIO_TABLEPREFIX . "stats_linkclicks lc, " . SENDSTUDIO_TABLEPREFIX . "links ml WHERE ml.linkid=lc.linkid AND l.subscriberid=lc.subscriberid AND lc.statid IN(" . implode(',', $statids) . ") " . $calendar_restrictions;

		if (is_numeric($linkid)) {
			$query .= " AND ml.linkid='" . $linkid . "'";
		}

		$query .= " ORDER BY clicktime DESC ";

		if ($start || $perpage) {
			$query .= $this->Db->AddLimit($start, $perpage);
		}

		$result = $this->Db->Query($query);

		$return = array();
		while ($row = $this->Db->Fetch($result)) {
			$return[] = $row;
		}
		return $return;
	}

	function GetBounceCounts($statids=array(), $calendar_restrictions='') {
		if (!is_array($statids)) {
			$statids = array($statids);
		}

		$statids = $this->CheckIntVars($statids);
		if (empty($statids)) {
			$statids = array('0');
		}

		$query = "SELECT count(bounceid) AS count, bouncetype FROM " . SENDSTUDIO_TABLEPREFIX . "list_subscriber_bounces WHERE statid IN (" . implode(',', $statids) . ") " . $calendar_restrictions . " GROUP BY bouncetype";

		$bouncecounts = array('soft' => 0, 'hard' => 0, 'total' => 0);

		$result = $this->Db->Query($query);

		while ($row = $this->Db->Fetch($result)) {
			$bouncecounts[$row['bouncetype']] += $row['count'];
		}
		$bouncecounts['total'] = $bouncecounts['soft'] + $bouncecounts['hard'];
		return $bouncecounts;
	}

	function GetBounces($statids=array(), $start=0, $perpage=10, $bounce_type=false, $calendar_restrictions='', $count_only=false)
	{
		if (!is_array($statids)) {
			$statids = array($statids);
		}

		$statids = $this->CheckIntVars($statids);
		if (empty($statids)) {
			$statids = array('0');
		}

		if ($bounce_type == 'any') {
			$bounce_type = false;
		}

		if ($count_only) {
			$query = "SELECT COUNT(l.emailaddress) AS count FROM " . SENDSTUDIO_TABLEPREFIX . "list_subscribers l, " . SENDSTUDIO_TABLEPREFIX . "list_subscriber_bounces b WHERE l.subscriberid=b.subscriberid AND b.statid IN(" . implode(',', $statids) . ") " . $calendar_restrictions;
			if ($bounce_type) {
				$query .= " AND bouncetype='" . $this->Db->Quote($bounce_type) . "'";
			}
			$result = $this->Db->Query($query);
			return $this->Db->FetchOne($result, 'count');
		}

		$query = "SELECT l.emailaddress, bouncetime, bouncetype, bouncerule FROM " . SENDSTUDIO_TABLEPREFIX . "list_subscribers l, " . SENDSTUDIO_TABLEPREFIX . "list_subscriber_bounces b WHERE l.subscriberid=b.subscriberid AND b.statid IN(" . implode(',', $statids) . ") " . $calendar_restrictions;

		if ($bounce_type) {
			$query .= " AND bouncetype='" . $this->Db->Quote($bounce_type) . "'";
		}

		if ($start || $perpage) {
			$query .= $this->Db->AddLimit($start, $perpage);
		}

		$result = $this->Db->Query($query);

		$return = array();
		while ($row = $this->Db->Fetch($result)) {
			$return[] = $row;
		}
		return $return;
	}

	function GetForwards($statids=array(), $start=0, $perpage=10, $calendar_restrictions='', $count_only=false, $new_signups=false)
	{

		if (!is_array($statids)) {
			$statids = array($statids);
		}

		$statids = $this->CheckIntVars($statids);
		if (empty($statids)) {
			$statids = array('0');
		}

		if ($count_only) {
			$query = "SELECT COUNT(l.emailaddress) AS count FROM " . SENDSTUDIO_TABLEPREFIX . "list_subscribers l, " . SENDSTUDIO_TABLEPREFIX . "stats_emailforwards f WHERE l.subscriberid=f.subscriberid AND f.statid IN(" . implode(',', $statids) . ") " . $calendar_restrictions;

			if ($new_signups) {
				$query .= " AND subscribed > 0";
			}
			$result = $this->Db->Query($query);
			return $this->Db->FetchOne($result, 'count');
		}

		$query = "SELECT l.emailaddress AS forwardedby, forwardtime, forwardip, f.emailaddress AS forwardedto, subscribed FROM " . SENDSTUDIO_TABLEPREFIX . "list_subscribers l, " . SENDSTUDIO_TABLEPREFIX . "stats_emailforwards f WHERE l.subscriberid=f.subscriberid AND f.statid IN(" . implode(',', $statids) . ") " . $calendar_restrictions;

		if ($new_signups) {
			$query .= " AND subscribed > 0";
		}

		$query .= " ORDER BY forwardtime DESC ";

		if ($start || $perpage) {
			$query .= $this->Db->AddLimit($start, $perpage);
		}

		$result = $this->Db->Query($query);

		$return = array();
		while ($row = $this->Db->Fetch($result)) {
			$return[] = $row;
		}
		return $return;
	}

	function GetUnsubscribes($statids=array(), $start=0, $perpage=10, $calendar_restrictions='', $count_only=false)
	{

		if (!is_array($statids)) {
			$statids = array($statids);
		}

		$statids = $this->CheckIntVars($statids);
		if (empty($statids)) {
			$statids = array('0');
		}

		if ($count_only) {
			$query = "SELECT COUNT(l.emailaddress) AS count FROM " . SENDSTUDIO_TABLEPREFIX . "list_subscribers l, " . SENDSTUDIO_TABLEPREFIX . "list_subscribers_unsubscribe lsu WHERE l.subscriberid=lsu.subscriberid AND lsu.statid IN(" . implode(',', $statids) . ") " . $calendar_restrictions;
			$result = $this->Db->Query($query);
			return $this->Db->FetchOne($result, 'count');
		}

		$query = "SELECT l.emailaddress, unsubscribetime, unsubscribeip FROM " . SENDSTUDIO_TABLEPREFIX . "list_subscribers l, " . SENDSTUDIO_TABLEPREFIX . "list_subscribers_unsubscribe lsu WHERE l.subscriberid=lsu.subscriberid AND lsu.statid IN(" . implode(',', $statids) . ") " . $calendar_restrictions . " ORDER BY unsubscribetime DESC ";

		if ($start || $perpage) {
			$query .= $this->Db->AddLimit($start, $perpage);
		}

		$result = $this->Db->Query($query);

		$return = array();
		while ($row = $this->Db->Fetch($result)) {
			$return[] = $row;
		}
		return $return;
	}

	function GetOpens($statids=array(), $start=0, $perpage=10, $only_unique=false, $calendar_restrictions='', $count_only=false)
	{
		if (!is_array($statids)) {
			$statids = array($statids);
		}

		$statids = $this->CheckIntVars($statids);
		if (empty($statids)) {
			$statids = array('0');
		}

		if (!$only_unique) {
			if ($count_only) {
				$query = "SELECT COUNT(l.emailaddress) AS count FROM " . SENDSTUDIO_TABLEPREFIX . "list_subscribers l, " . SENDSTUDIO_TABLEPREFIX . "stats_emailopens o WHERE l.subscriberid=o.subscriberid AND o.statid IN(" . implode(',', $statids) . ") " . $calendar_restrictions;
				$result = $this->Db->Query($query);
				return $this->Db->FetchOne($result, 'count');
			}

			$query = "SELECT l.emailaddress, opentime, openip FROM " . SENDSTUDIO_TABLEPREFIX . "list_subscribers l, " . SENDSTUDIO_TABLEPREFIX . "stats_emailopens o WHERE l.subscriberid=o.subscriberid AND o.statid IN(" . implode(',', $statids) . ") " . $calendar_restrictions . " ORDER BY opentime DESC ";
		} else {
			if ($count_only) {
				$query = "SELECT COUNT(DISTINCT l.emailaddress) AS count FROM " . SENDSTUDIO_TABLEPREFIX . "list_subscribers l, " . SENDSTUDIO_TABLEPREFIX . "stats_emailopens o WHERE l.subscriberid=o.subscriberid AND o.statid IN(" . implode(',', $statids) . ") " . $calendar_restrictions;
				$result = $this->Db->Query($query);
				return $this->Db->FetchOne($result, 'count');
			}

			// mysql lets you only group by one field in the select list, so we'll take the easy way out.
			// also only v4.1+ supports subselects so we're out of luck doing it that way anyway.
			if (SENDSTUDIO_DATABASE_TYPE == 'mysql') {
				$query = "SELECT l.emailaddress, opentime, openip FROM " . SENDSTUDIO_TABLEPREFIX . "list_subscribers l, " . SENDSTUDIO_TABLEPREFIX . "stats_emailopens o WHERE l.subscriberid=o.subscriberid AND o.statid IN(" . implode(',', $statids) . ") " . $calendar_restrictions . " GROUP BY l.emailaddress ORDER BY opentime DESC ";
			} else {
				// postgres supports subselects and won't let you group by only one field in the select list, so we have to do it this way.
				// this will get the latest opentime for an email open (in the subselect) and use that as the joining criteria.
				$query = "SELECT l.emailaddress, oo.opentime, oo.openip FROM " . SENDSTUDIO_TABLEPREFIX . "list_subscribers l, " . SENDSTUDIO_TABLEPREFIX . "stats_emailopens oo WHERE (SELECT opentime FROM " . SENDSTUDIO_TABLEPREFIX . "stats_emailopens o WHERE o.subscriberid=l.subscriberid AND o.statid IN(" . implode(',', $statids) . ") ORDER BY opentime DESC LIMIT 1)=oo.opentime AND l.subscriberid=oo.subscriberid " . $calendar_restrictions . " ORDER BY opentime DESC ";
			}
		}

		if ($start || $perpage) {
			$query .= $this->Db->AddLimit($start, $perpage);
		}

		$result = $this->Db->Query($query);

		$return = array();
		while ($row = $this->Db->Fetch($result)) {
			$return[] = $row;
		}
		return $return;
	}

	function GetMostOpens($statids=array(), $restrictions='')
	{
		if (!is_array($statids)) {
			$statids = array($statids);
		}

		$statids = $this->CheckIntVars($statids);
		if (empty($statids)) {
			$statids = array('0');
		}

		$this->CalculateStatsType();

		$qry = "SELECT SUM(openid) AS count, ";
		$qry .= $this->CalculateGroupBy($this->calendar_type, 'opentime');
		$qry .= " FROM " . SENDSTUDIO_TABLEPREFIX . "stats_emailopens";
		$qry .= " WHERE statid IN(" . implode(',', $statids) . ")";
		if ($restrictions) {
			$qry .= $restrictions;
		}

		switch ($this->calendar_type) {
			case 'today':
			case 'yesterday':
			case 'daily':
			case 'last24hours':
				$general_query = ' GROUP BY hr';
			break;

			case 'last7days':
				$general_query = ' GROUP BY dow';
			break;

			case 'last30days':
			case 'thismonth':
			case 'lastmonth':
			case 'monthly':
				$general_query = ' GROUP BY dom';
			break;

			default:
				$general_query = ' GROUP BY mth, yr';
		}

		$qry .= $general_query;

		$qry .= " ORDER BY count DESC LIMIT 1";

		$result = $this->Db->Query($qry);
		if (!$result) {
			return false;
		}

		$row = $this->Db->Fetch($result);
		return $row;
	}

	function GetMostUnsubscribes($statids=array(), $restrictions='')
	{
		if (!is_array($statids)) {
			$statids = array($statids);
		}

		$statids = $this->CheckIntVars($statids);
		if (empty($statids)) {
			$statids = array('0');
		}

		$this->CalculateStatsType();

		$qry = "SELECT COUNT(unsubscribetime) AS count, ";
		$qry .= $this->CalculateGroupBy($this->calendar_type, 'unsubscribetime');
		$qry .= " FROM " . SENDSTUDIO_TABLEPREFIX . "list_subscribers_unsubscribe";
		$qry .= " WHERE statid IN(" . implode(',', $statids) . ")";
		if ($restrictions) {
			$qry .= $restrictions;
		}

		switch ($this->calendar_type) {
			case 'today':
			case 'yesterday':
			case 'daily':
			case 'last24hours':
				$general_query = ' GROUP BY hr';
			break;

			case 'last7days':
				$general_query = ' GROUP BY dow';
			break;

			case 'last30days':
			case 'thismonth':
			case 'lastmonth':
			case 'monthly':
				$general_query = ' GROUP BY dom';
			break;

			default:
				$general_query = ' GROUP BY mth, yr';
		}

		$qry .= $general_query;

		$qry .= " ORDER BY count DESC LIMIT 1";

		$result = $this->Db->Query($qry);
		if (!$result) {
			return false;
		}

		$row = $this->Db->Fetch($result);
		return $row;
	}

	function CheckStatsSequence()
	{
		$queue_sequence_ok = $this->Db->CheckSequence(SENDSTUDIO_TABLEPREFIX . 'stats_sequence');
		if (!$queue_sequence_ok) {
			$qry = "SELECT statid FROM " . SENDSTUDIO_TABLEPREFIX . "stats_newsletters
			UNION
			SELECT statid FROM " . SENDSTUDIO_TABLEPREFIX . "stats_autoresponders
			ORDER BY statid DESC LIMIT 1";
			$id = $this->Db->FetchOne($qry, 'statid');
			$new_id = $id + 1;
			$reset_ok = $this->Db->ResetSequence(SENDSTUDIO_TABLEPREFIX . 'stats_sequence', $new_id);
			if (!$reset_ok) {
				return false;
			}
		}
		return true;
	}

	function SaveNewsletterStats($newsletterdetails)
	{
		if (!$this->CheckStatsSequence()) {
			return false;
		}
		$statid = $this->Db->NextId(SENDSTUDIO_TABLEPREFIX . 'stats_sequence');

		$start_time = $this->GetServerTime();

		$query = "INSERT INTO " . SENDSTUDIO_TABLEPREFIX . "stats_newsletters(statid, queueid, starttime, finishtime, htmlrecipients, textrecipients, multipartrecipients, trackopens, tracklinks, newsletterid, sendfromname, sendfromemail, bounceemail, replytoemail, charset, sendinformation, sendsize, sentby, notifyowner, bouncecount_soft, bouncecount_hard, bouncecount_unknown, unsubscribecount, emailopens, emailforwards) VALUES ('" . $statid . "', '" . (int)$newsletterdetails['Queue'] . "', '" . $start_time . "', 0, 0, 0, 0, '" . $this->Db->Quote($newsletterdetails['TrackOpens']) . "', '" . $this->Db->Quote($newsletterdetails['TrackLinks']) . "', '" . (int)$newsletterdetails['Newsletter'] . "', '" . $this->Db->Quote($newsletterdetails['SendFromName']) . "', '" . $this->Db->Quote($newsletterdetails['SendFromEmail']) . "', '" . $this->Db->Quote($newsletterdetails['BounceEmail']) . "', '" . $this->Db->Quote($newsletterdetails['ReplyToEmail']) . "', '" . $this->Db->Quote($newsletterdetails['Charset']) . "', '" . $this->Db->Quote(serialize($newsletterdetails['SendCriteria'])) . "', '" . (int)$newsletterdetails['SendSize'] . "', '" . (int)$newsletterdetails['SentBy'] . "', '" . $this->Db->Quote($newsletterdetails['NotifyOwner']) . "', 0, 0, 0, 0, 0, 0)";

		$this->Db->Query($query);

		foreach ($newsletterdetails['Lists'] as $p => $listid) {
			$query = "INSERT INTO " . SENDSTUDIO_TABLEPREFIX . "stats_newsletter_lists(statid, listid) VALUES ('" . $statid . "', '" . (int)$listid . "')";
			$this->Db->Query($query);
		}
		return $statid;
	}

	function SaveAutoresponderStats($autoresponderdetails)
	{
		if (!$this->CheckStatsSequence()) {
			return false;
		}

		$statid = $this->Db->NextId(SENDSTUDIO_TABLEPREFIX . 'stats_sequence');

		$query = "INSERT INTO " . SENDSTUDIO_TABLEPREFIX . "stats_autoresponders(statid, autoresponderid) VALUES ('" . $statid . "', '" . (int)$autoresponderdetails['autoresponderid'] . "')";
		$this->Db->Query($query);
		return $statid;
	}

	function UpdateRecipient($statid=0, $format=false, $statstype='n')
	{
		$statid = (int)$statid;
		if (!$format || $statid <= 0) {
			return false;
		}

		$table = $this->GetStatsTable($statstype);
		if (!$table) {
			return false;
		}

		switch (strtolower(substr($format, 0, 1))) {
			case 'm':
				$subquery = 'multipartrecipients=multipartrecipients + 1';
			break;
			case 'h':
				$subquery = 'htmlrecipients=htmlrecipients + 1';
			break;

			case 't':
				$subquery = 'textrecipients=textrecipients + 1';
			break;

			default:
				$subquery = false;
		}

		if ($subquery) {
			$query = "UPDATE " . SENDSTUDIO_TABLEPREFIX . $table . " SET " . $subquery . " WHERE statid='" . $statid . "'";
			$this->Db->Query($query);
		}
	}

	function MarkNewsletterFinished($statid=0, $original_queuesize=0, $optimize_table=true)
	{

		$endtime = $this->GetServerTime();

		$statid = (int)$statid;

		$original_queuesize = (int)$original_queuesize;

		$query = "UPDATE " . SENDSTUDIO_TABLEPREFIX . "stats_newsletters SET finishtime='" . (int)$endtime . "' WHERE statid='" . $statid . "'";
		$this->Db->Query($query);

		/**
		* Get the total recipient count so we can update the user stats to show exactly how many were sent.
		*/
		$query = "SELECT (multipartrecipients + htmlrecipients + textrecipients) AS totalrecipients, queueid, sentby FROM " . SENDSTUDIO_TABLEPREFIX . "stats_newsletters WHERE statid='" . $statid . "'";
		$result = $this->Db->Query($query);
		$row = $this->Db->Fetch($result);

		$queueid = $row['queueid'];
		$total_recipients = $row['totalrecipients'];
		$userid = $row['sentby'];

		$query = "UPDATE " . SENDSTUDIO_TABLEPREFIX . "stats_users SET queuesize='" . (int)$total_recipients . "' WHERE statid='" . $statid . "'";

		$result = $this->Db->Query($query);

		if ($original_queuesize > $total_recipients) {
			$credits = $original_queuesize - $total_recipients;
			$query = "UPDATE " . SENDSTUDIO_TABLEPREFIX . "users SET maxemails = maxemails + " . $credits . " WHERE userid='" . (int)$userid . "'";
			$this->Db->Query($query);
		} else {
			$difference = $total_recipients - $original_queuesize;
			if ($difference > 0) {
				$query = "UPDATE " . SENDSTUDIO_TABLEPREFIX . "users SET maxemails = maxemails - " . $difference . " WHERE userid='" . (int)$userid . "'";
				$this->Db->Query($query);
			}
		}

		$this->SummarizeEmailSend($queueid, $statid, $userid, $optimize_table);
	}

	function GetNewsletterStats($listids=array(), $sortinfo=array(), $countonly=false, $start=0, $perpage=10, $newsletterid=0)
	{
		$start = (int)$start;
		$perpage = (int)$perpage;

		if (!is_array($listids)) {
			$listids = array($listids);
		}

		$listids = $this->CheckIntVars($listids);

		if (empty($listids)) {
			$listids = array('0');
		}

		if ($countonly) {
			$query = "SELECT COUNT(sn.statid) AS count FROM " . SENDSTUDIO_TABLEPREFIX . "stats_newsletter_lists snl, " . SENDSTUDIO_TABLEPREFIX . "stats_newsletters sn, " . SENDSTUDIO_TABLEPREFIX . "lists l, " . SENDSTUDIO_TABLEPREFIX . "newsletters n WHERE sn.newsletterid=n.newsletterid AND l.listid=snl.listid AND snl.statid=sn.statid AND snl.listid IN (" . implode(',', $listids) . ") AND hiddenby=0";
			if ($newsletterid) {
				$query .= " AND n.newsletterid='" . (int)$newsletterid . "'";
			}

			$result = $this->Db->Query($query);

			return $this->Db->FetchOne($result, 'count');
		}

		$orderby = 'finishtime';
		$orderdirection = 'desc';

		if (strtolower($sortinfo['Direction']) == 'up' || strtolower($sortinfo['Direction']) == 'asc') {
			$orderdirection = 'asc';
		}

		$valid_sorts = array(
			'newsletter' => 'LOWER(n.name)',
			'list' => 'LOWER(l.name)',
			'startdate' => 'starttime',
			'finishdate' => 'finishtime',
			'recipients' => '(htmlrecipients + textrecipients + multipartrecipients)',
			'unsubscribes' => 'sn.unsubscribecount',
			'bounces' => '(bouncecount_soft + bouncecount_hard + bouncecount_unknown)'
		);

		if (in_array(strtolower($sortinfo['SortBy']), array_keys($valid_sorts))) {
			$orderby = $valid_sorts[$sortinfo['SortBy']];
		}

		$query = "SELECT snl.statid, starttime, finishtime, htmlrecipients, textrecipients, multipartrecipients, sendsize, bouncecount_soft, bouncecount_hard, bouncecount_unknown, sn.unsubscribecount, l.name AS listname, n.name AS newslettername FROM " . SENDSTUDIO_TABLEPREFIX . "stats_newsletter_lists snl, " . SENDSTUDIO_TABLEPREFIX . "stats_newsletters sn, " . SENDSTUDIO_TABLEPREFIX . "lists l, " . SENDSTUDIO_TABLEPREFIX . "newsletters n WHERE sn.newsletterid=n.newsletterid AND l.listid=snl.listid AND snl.statid=sn.statid AND snl.listid IN (" . implode(',', $listids) . ") AND hiddenby=0";
		if ($newsletterid) {
			$query .= " AND n.newsletterid='" . (int)$newsletterid . "'";
		}
		$query .= " ORDER BY " . $orderby . " " . $orderdirection;

		if ($start || $perpage) {
			$query .= $this->Db->AddLimit($start, $perpage);
		}

		$result = $this->Db->Query($query);

		if (!$result) {
			list($error, $level) = $this->Db->GetError();
			trigger_error($error, $level);
			return false;
		}
		$stats = array();
		while ($row = $this->Db->Fetch($result)) {
			$stats[] = $row;
		}
		return $stats;
	}

	/**
	* skip_subareas is used by stats::printreport so it only gets the summary, not the last 5 clicks etc.
	*/
	function GetNewsletterSummary($statid=0, $skip_subareas=false, $subarea_limit=10)
	{
		$statid = (int)$statid;

		$query = "SELECT sn.*, n.name AS newslettername, n.subject AS newslettersubject, n.newsletterid AS newsletterid, u.username, u.fullname, u.emailaddress
		FROM " . SENDSTUDIO_TABLEPREFIX . "newsletters n, " . SENDSTUDIO_TABLEPREFIX . "stats_newsletters sn
		LEFT OUTER JOIN " . SENDSTUDIO_TABLEPREFIX . "users u ON (u.userid=sn.sentby)
		WHERE sn.statid='" . $statid . "'
		AND sn.newsletterid=n.newsletterid
		AND sn.hiddenby=0";

		$result = $this->Db->Query($query);
		$stats = $this->Db->Fetch($result);

		$lists = array();

		$query = "SELECT l.listid, l.name AS listname FROM " . SENDSTUDIO_TABLEPREFIX . "lists l, " . SENDSTUDIO_TABLEPREFIX . "stats_newsletter_lists snl WHERE snl.listid=l.listid AND snl.statid='" . $statid . "'";
		$result = $this->Db->Query($query);

		while ($row = $this->Db->Fetch($result)) {
			$lists[$row['listid']] = $row['listname'];
		}
		$stats['lists'] = $lists;

		if ($skip_subareas) {
			return $stats;
		}

		$clicks = array();

		$query = "SELECT lc.clickid, ls.emailaddress, lc.clicktime, l.url FROM " . SENDSTUDIO_TABLEPREFIX . "links l, " . SENDSTUDIO_TABLEPREFIX . "list_subscribers ls, " . SENDSTUDIO_TABLEPREFIX . "stats_linkclicks lc WHERE ls.subscriberid=lc.subscriberid AND l.linkid=lc.linkid AND lc.statid='" . $statid . "' ORDER BY clicktime DESC LIMIT " . $subarea_limit;
		$result = $this->Db->Query($query);

		while ($row = $this->Db->Fetch($result)) {
			$clicks[$row['clickid']] = array('emailaddress' => $row['emailaddress'], 'clicktime' => $row['clicktime'], 'url' => $row['url']);
		}
		$stats['clicks'] = $clicks;

		$opens = array();

		$query = "SELECT o.openid, l.emailaddress, o.opentime FROM " . SENDSTUDIO_TABLEPREFIX . "stats_emailopens o, " . SENDSTUDIO_TABLEPREFIX . "list_subscribers l WHERE ls.subscriberid=o.subscriberid AND o.statid='" . $statid . "' ORDER BY opentime DESC LIMIT " . $subarea_limit;

		$result = $this->Db->Query($query);
		while ($row = $this->Db->Fetch($result)) {
			$opens[$row['openid']] = array('emailaddress' => $row['emailaddress'], 'opentime' => $row['opentime']);
		}
		$stats['opens'] = $opens;


		$unsubscribes = array();

		$query = "SELECT u.subscriberid, l.emailaddress, unsubscribetime, unsubscribeip FROM " . SENDSTUDIO_TABLEPREFIX . "list_subscribers_unsubscribe u, " . SENDSTUDIO_TABLEPREFIX . "list_subscribers l WHERE l.subscriberid=u.subscriberid AND statid='" . $statid . "' ORDER BY unsubscribetime DESC LIMIT " . $subarea_limit;
		$result = $this->Db->Query($query);

		while ($row = $this->Db->Fetch($result)) {
			$unsubscribes[$row['subscriberid']] = array('emailaddress' => $row['emailaddress'], 'unsubscribetime' => $row['unsubscribetime'], 'unsubscribeip' => $row['unsubscribeip']);
		}
		$stats['unsubscribes'] = $unsubscribes;

		return $stats;
	}

	function IsFinished($statid=0, $statstype='n')
	{
		$statid = (int)$statid;
		if ($statid <= 0) {
			return false;
		}

		$table = $this->GetStatsTable($statstype);
		if (!$table) {
			return false;
		}

		$query = "SELECT finishtime FROM " . SENDSTUDIO_TABLEPREFIX . $table . " WHERE statid='" . $statid. "'";
		$result = $this->Db->Query($query);
		$finish_time = $this->Db->FetchOne($result, 'finishtime');

		if ($finish_time > 0) {
			return true;
		}
		return false;
	}

	function GetAutoresponderStats($listids=array(), $sortinfo=array(), $countonly=false, $start=0, $perpage=10, $autoresponderid=0)
	{
		$start = (int)$start;
		$perpage = (int)$perpage;

		if (!is_array($listids)) {
			$listids = array($listids);
		}

		$listids = $this->CheckIntVars($listids);

		if (empty($listids)) {
			$listids = array('0');
		}

		if ($countonly) {
			$query = "SELECT COUNT(a.autoresponderid) AS count FROM " . SENDSTUDIO_TABLEPREFIX . "lists l, " . SENDSTUDIO_TABLEPREFIX . "autoresponders a LEFT OUTER JOIN " . SENDSTUDIO_TABLEPREFIX . "stats_autoresponders sa ON (sa.autoresponderid=a.autoresponderid) WHERE l.listid=a.listid AND l.listid IN (" . implode(',', $listids) . ") AND hiddenby=0";
			if ($autoresponderid) {
				$query .= " AND a.autoresponderid='" . (int)$autoresponderid . "'";
			}

			$result = $this->Db->Query($query);
			return $this->Db->FetchOne($result, 'count');
		}

		$orderby = 'LOWER(a.name)';
		$orderdirection = 'desc';

		if (strtolower($sortinfo['Direction']) == 'up' || strtolower($sortinfo['Direction']) == 'asc') {
			$orderdirection = 'asc';
		}

		$valid_sorts = array(
			'autoresponder' => 'LOWER(a.name)',
			'list' => 'LOWER(l.name)',
			'recipients' => '(htmlrecipients + textrecipients + multipartrecipients)',
			'unsubscribes' => 'sa.unsubscribecount',
			'bounces' => '(bouncecount_soft + bouncecount_hard + bouncecount_unknown)'
		);

		if (in_array(strtolower($sortinfo['SortBy']), array_keys($valid_sorts))) {
			$orderby = $valid_sorts[$sortinfo['SortBy']];
		}

		$query = "SELECT sa.statid AS statid, a.autoresponderid AS autoresponderid, a.name as autorespondername, l.name as listname, (htmlrecipients + textrecipients + multipartrecipients) as sendsize, sa.unsubscribecount, bouncecount_soft, bouncecount_hard, bouncecount_unknown, hoursaftersubscription FROM " . SENDSTUDIO_TABLEPREFIX . "lists l, " . SENDSTUDIO_TABLEPREFIX . "autoresponders a LEFT OUTER JOIN " . SENDSTUDIO_TABLEPREFIX . "stats_autoresponders sa ON (sa.autoresponderid=a.autoresponderid) WHERE l.listid=a.listid AND l.listid IN (" . implode(',', $listids) . ") AND hiddenby=0";
		if ($autoresponderid) {
			$query .= " AND a.autoresponderid='" . (int)$autoresponderid . "'";
		}
		$query .= " ORDER BY " . $orderby . " " . $orderdirection;

		if ($start || $perpage) {
			$query .= $this->Db->AddLimit($start, $perpage);
		}

		$result = $this->Db->Query($query);
		if (!$result) {
			list($error, $level) = $this->Db->GetError();
			trigger_error($error, $level);
			return false;
		}
		$stats = array();
		while ($row = $this->Db->Fetch($result)) {
			$stats[] = $row;
		}
		return $stats;
	}

	/**
	* skip_subareas is used by stats::printreport so it only gets the summary, not the last 5 clicks etc.
	*/
	function GetAutoresponderSummary($autoresponderid=0, $skip_subareas=false, $subarea_limit=10)
	{
		$autoresponderid = (int)$autoresponderid;

		$query = "SELECT sa.*, a.*, a.subject AS autorespondersubject, a.name AS autorespondername, u.username, u.fullname, u.emailaddress, l.name AS listname FROM " . SENDSTUDIO_TABLEPREFIX . "users u, " . SENDSTUDIO_TABLEPREFIX . "lists l, " . SENDSTUDIO_TABLEPREFIX . "autoresponders a LEFT OUTER JOIN " . SENDSTUDIO_TABLEPREFIX . "stats_autoresponders sa ON sa.autoresponderid=a.autoresponderid WHERE a.autoresponderid='" . $autoresponderid . "' AND a.listid=l.listid AND u.userid=a.ownerid AND sa.hiddenby=0";

		$result = $this->Db->Query($query);
		$stats = $this->Db->Fetch($result);
		if ($skip_subareas) {
			return $stats;
		}

		$clicks = array();

		$query = "SELECT lc.clickid, s.emailaddress, lc.clicktime, l.url FROM " . SENDSTUDIO_TABLEPREFIX . "links l, " . SENDSTUDIO_TABLEPREFIX . "list_subscribers ls, " . SENDSTUDIO_TABLEPREFIX . "stats_linkclicks lc WHERE ls.subscriberid=lc.subscriberid AND l.linkid=lc.linkid AND lc.statid='" . $stats['statid'] . "' ORDER BY clicktime DESC LIMIT " . $subarea_limit;

		$result = $this->Db->Query($query);

		while ($row = $this->Db->Fetch($result)) {
			$clicks[$row['clickid']] = array('emailaddress' => $row['emailaddress'], 'clicktime' => $row['clicktime'], 'url' => $row['url']);
		}
		$stats['clicks'] = $clicks;

		$opens = array();

		$query = "SELECT o.openid, l.emailaddress, o.opentime FROM " . SENDSTUDIO_TABLEPREFIX . "stats_emailopens o, " . SENDSTUDIO_TABLEPREFIX . "list_subscribers l WHERE l.subscriberid=o.subscriberid AND o.statid='" . $stats['statid'] . "' ORDER BY opentime DESC LIMIT " . $subarea_limit;

		$result = $this->Db->Query($query);

		while ($row = $this->Db->Fetch($result)) {
			$opens[$row['openid']] = array('emailaddress' => $row['emailaddress'], 'opentime' => $row['opentime']);
		}
		$stats['opens'] = $opens;


		$unsubscribes = array();

		$query = "SELECT u.subscriberid, l.emailaddress, unsubscribetime, unsubscribeip FROM " . SENDSTUDIO_TABLEPREFIX . "list_subscribers_unsubscribe u, " . SENDSTUDIO_TABLEPREFIX . "list_subscribers l WHERE l.subscriberid=u.subscriberid AND statid='" . $stats['statid'] . "' ORDER BY unsubscribetime DESC LIMIT " . $subarea_limit;

		$result = $this->Db->Query($query);

		while ($row = $this->Db->Fetch($result)) {
			$unsubscribes[$row['subscriberid']] = array('emailaddress' => $row['emailaddress'], 'unsubscribetime' => $row['unsubscribetime'], 'unsubscribeip' => $row['unsubscribeip']);
		}
		$stats['unsubscribes'] = $unsubscribes;

		return $stats;
	}

	function GetUniqueLinks($statids=array())
	{

		if (!is_array($statids)) {
			$statids = array($statids);
		}

		$statids = $this->CheckIntVars($statids);
		if (empty($statids)) {
			$statids = array('0');
		}

		$query = "SELECT l.linkid AS linkid, url FROM " . SENDSTUDIO_TABLEPREFIX . "stats_links sl, " . SENDSTUDIO_TABLEPREFIX . "links l WHERE sl.linkid=l.linkid AND sl.statid IN (" . implode(',', $statids) . ") GROUP BY url, l.linkid";

		$result = $this->Db->Query($query);

		$return = array();
		while ($row = $this->Db->Fetch($result)) {
			$row['url'] = $row['url'];
			$return[] = $row;
		}
		return $return;
	}

	/**
	* @see Subscribers_API::UnsubscribeSubscriber
	*/
	function Unsubscribe($statid, $statstype)
	{
		$statid = (int)$statid;

		if ($statid <= 0) {
			return false;
		}

		$table = $this->GetStatsTable($statstype);

		if (!$table) {
			return false;
		}

		$query = "UPDATE " . SENDSTUDIO_TABLEPREFIX . $table . " SET unsubscribecount=unsubscribecount + 1 WHERE statid='" . $statid . "'";
		$result = $this->Db->Query($query);

		/**
		* We don't keep a separate table for unsubscribes, this is done in Subscribers_API::UnsubscribeSubscriber
		*/
		return $result;
	}

	function RecordOpen($open_details=array(), $statstype)
	{
		if (!isset($open_details['subscriberid'])) {
			return false; // if there's no subscriber id, it's probably an invalid array passed in.
		}

		$table = $this->GetStatsTable($statstype);
		if (!$table) {
			return false;
		}

		$query = "INSERT INTO " . SENDSTUDIO_TABLEPREFIX . "stats_emailopens(opentime, openip, subscriberid, statid) VALUES('" . (int)$open_details['opentime'] . "', '" . $this->Db->Quote($open_details['openip']) . "', '" . (int)$open_details['subscriberid'] . "', '" . (int)$open_details['statid'] . "')";
		$result = $this->Db->Query($query);

		$query = "SELECT COUNT(openid) AS count FROM " . SENDSTUDIO_TABLEPREFIX . "stats_emailopens WHERE subscriberid='" . (int)$open_details['subscriberid'] . "' AND statid='" . (int)$open_details['statid'] . "'";
		$result = $this->Db->Query($query);
		$unique_count = $this->Db->FetchOne($result, 'count');

		$query = "UPDATE " . SENDSTUDIO_TABLEPREFIX . $table . " SET emailopens=emailopens + 1";

		// if there is only one open for this subscriber after just adding that record, update the unique counter as well.
		if ($unique_count == 1) {
			$query .= ", emailopens_unique=emailopens_unique+1";
		}
		$query .= " WHERE statid='" . (int)$open_details['statid'] . "'";
		$result = $this->Db->Query($query);
	}

	function RecordLinkClick($clickdetails=array(), $statstype)
	{
		if (!isset($clickdetails['subscriberid'])) {
			return false; // if there's no subscriber id, it's probably an invalid array passed in.
		}

		$table = $this->GetStatsTable($statstype);
		if (!$table) {
			return false;
		}

		$query = "INSERT INTO " . SENDSTUDIO_TABLEPREFIX . "stats_linkclicks(clicktime, clickip, subscriberid, statid, linkid) VALUES('" . (int)$clickdetails['clicktime'] . "', '" . $this->Db->Quote($clickdetails['clickip']) . "', '" . (int)$clickdetails['subscriberid'] . "', '" . (int)$clickdetails['statid'] . "', '" . (int)$clickdetails['linkid'] . "')";
		$this->Db->Query($query);

		$query = "UPDATE " . SENDSTUDIO_TABLEPREFIX . $table . " SET linkclicks=linkclicks + 1 WHERE statid='" . (int)$clickdetails['statid'] . "'";
		$result = $this->Db->Query($query);
	}

	function RecordForward($forward_details=array(), $statstype)
	{
		if (!isset($forward_details['subscriberid'])) {
			return false; // if there's no subscriber id, it's probably an invalid array passed in.
		}

		$table = $this->GetStatsTable($statstype);
		if (!$table) {
			return false;
		}

		$query = "INSERT INTO " . SENDSTUDIO_TABLEPREFIX . "stats_emailforwards(forwardtime, forwardip, subscriberid, statid, listid, subscribed, emailaddress) VALUES('" . (int)$forward_details['forwardtime'] . "', '" . $this->Db->Quote($forward_details['forwardip']) . "', '" . (int)$forward_details['subscriberid'] . "', '" . (int)$forward_details['statid'] . "', '" . (int)$forward_details['listid'] . "', 0, '" . $this->Db->Quote($forward_details['emailaddress']) . "')";
		$result = $this->Db->Query($query);

		$query = "UPDATE " . SENDSTUDIO_TABLEPREFIX . $table . " SET emailforwards=emailforwards + 1";
		$query .= " WHERE statid='" . (int)$forward_details['statid'] . "'";
		$result = $this->Db->Query($query);
	}

	function RecordForwardSubscribe($emailaddress='', $subscriber_id=0, $lists=array())
	{
		$lists = $this->CheckIntVars($lists);

		if (!$emailaddress || !is_numeric($subscriber_id) || empty($lists)) {
			return false;
		}

		$query = "UPDATE " . SENDSTUDIO_TABLEPREFIX . "stats_emailforwards SET subscribed='" . (int)$subscriber_id . "' WHERE emailaddress='" . $this->Db->Quote($emailaddress) . "' AND listid IN (" . implode(',', $lists) . ")";
		$result = $this->Db->Query($query);
		return $result;
	}

	function ReCheckUserStats(&$user_object, $original_queuesize=0, $new_queuesize=0, $queuetime=0, $statid=0)
	{
		$max_emails = $user_object->Get('maxemails');
		$per_month = $user_object->Get('permonth');

		$unlimitedmaxemails = $user_object->Get('unlimitedmaxemails');

		$userid = $user_object->Get('userid');

		/**
		* If they have no limits, then no need to do any other checks.
		*/
		if ($unlimitedmaxemails && $per_month <= 0) {
			return array(true, false);
		}

		if (!$unlimitedmaxemails) {
			if ($new_queuesize > $original_queuesize) {
				// since the original_queuesize was taken off the max_emails previously
				// we need to re-calculate with that queuesize back on top again
				if ($new_queuesize > ($max_emails + $original_queuesize)) {

					$size_difference = $new_queuesize - $original_queuesize;

					if ($size_difference > SENDSTUDIO_MAXOVERSIZE) {
						$this->NotifyAdmin($userid, $size_difference, $queuetime, 'OverLimit_MaxEmails');
						return array(false, 'OverLimit_MaxEmails');
					}
					$this->NotifyAdmin($userid, $size_difference, $queuetime, 'OverLimit_MaxEmails');
				}
			}
		}

		if ($per_month <= 0) {
			return array(true, false);
		}

		$thismonth = mktime (0,0,1,date('m', $queuetime),1,date('Y', $queuetime));
		$nextmonth = mktime (0,0,1,(date('m', $queuetime)+1),1,date('Y', $queuetime));

		$query = "SELECT SUM(queuesize) AS queuesize FROM " . SENDSTUDIO_TABLEPREFIX . "stats_users WHERE userid='" . $userid . "' AND queuetime >= '" . $thismonth . "' AND queuetime < '" . $queuetime . "' AND statid != '" . (int)$statid . "'";

		$result = $this->Db->Query($query);

		$existing_queuesize = $this->Db->FetchOne($result);

		if (($per_month > 0) && ($new_queuesize > $per_month)) {

			$size_difference = (($new_queuesize + SENDSTUDIO_MAXOVERSIZE) - $per_month);

			// if they are already over their limit, don't worry about checking the maxoversize limit - just reject it altogether.
			if ($existing_queuesize >= $per_month) {
				$this->NotifyAdmin($userid, ($size_difference - SENDSTUDIO_MAXOVERSIZE), $queuetime, 'OverLimit_PerMonth');
				return array(false, 'OverLimit_PerMonth');
			}

			if ($size_difference > 0) {
				/**
				* If there is a max oversize limit, then check if we're still going to go over that limit.
				*/
				if (SENDSTUDIO_MAXOVERSIZE > 0) {

					/**
					* If we are still going over the limit, don't allow this to be sent.
					*/
					if ($new_queuesize > (SENDSTUDIO_MAXOVERSIZE + $per_month)) {
						$this->NotifyAdmin($userid, ($size_difference - SENDSTUDIO_MAXOVERSIZE), $queuetime, 'OverLimit_PerMonth');
						return array(false, 'OverLimit_PerMonth');
					}

					/**
					* If we are over our max-emails but under the limit, then notify the admin user, but let the newsletter/autoresponder through.
					*/
					$this->NotifyAdmin($userid, ($size_difference - SENDSTUDIO_MAXOVERSIZE), $queuetime, 'OverLimit_PerMonth');
				} else {
					$this->NotifyAdmin($userid, ($size_difference - SENDSTUDIO_MAXOVERSIZE), $queuetime, 'OverLimit_PerMonth');
					return array(false, 'OverLimit_PerMonth');
				}
			}
		}
		return array(true, false);
	}

	/**
	* CheckUserStats
	* Checks whether this user is allowed to send these emails or not.
	*
	* @param Object $user_object User Object to check.
	* @param Int $queuesize The size of the queue to check.
	* @param Int $queuetime The time when you are trying to send / schedule the queue.
	*
	* @return Array Returns an array of status and a language variable describing why it can't be sent. This allows us to differentiate between whether it's a "maxemails" issue or a "per month" issue.
	*/
	function CheckUserStats(&$user_object, $queuesize=0, $queuetime=0, $only_check_permonth=false)
	{
		$max_emails = $user_object->Get('maxemails');
		$per_month = $user_object->Get('permonth');

		$unlimitedmaxemails = $user_object->Get('unlimitedmaxemails');

		$userid = $user_object->Get('userid');

		/**
		* If they have no limits, then no need to do any other checks.
		*/
		if ($unlimitedmaxemails && $per_month <= 0) {
			return array(true, false);
		}

		if (!$only_check_permonth) {
			/**
			* If the queue size is more than the user limit, that's bad!
			* If it's over, then we'll see if there is a 'maxoversize' limit we can use.
			*
			* The user 'maxemails' actually gets updated by UpdateRecipient
			*/
			if (!$unlimitedmaxemails && ($queuesize > $max_emails)) {

				// if they are already over their limit, don't worry about checking the maxoversize limit - just reject it altogether.
				if ($max_emails <= 0) {
					return array(false, 'OverLimit_MaxEmails');
				}

				$size_difference = (($queuesize + SENDSTUDIO_MAXOVERSIZE) - $max_emails);

				if ($size_difference > 0) {

					/**
					* If there is a max oversize limit, then check if we're still going to go over that limit.
					*/
					if (SENDSTUDIO_MAXOVERSIZE > 0) {

						/**
						* If we are still going over the limit, don't allow this to be sent.
						*/
						if (($queuesize) > ($max_emails + SENDSTUDIO_MAXOVERSIZE)) {
							return array(false, 'OverLimit_MaxEmails');
						}

						/**
						* If we are over our max-emails but under the limit, then notify the admin user, but let the newsletter/autoresponder through.
						*/
						$this->NotifyAdmin($userid, ($size_difference - SENDSTUDIO_MAXOVERSIZE), $queuetime, 'OverLimit_MaxEmails');
					} else {
						return array(false, 'OverLimit_MaxEmails');
					}
				}
			}
		}

		if ($per_month <= 0) {
			return array(true, false);
		}

		$thismonth = mktime (0,0,1,date('m', $queuetime),1,date('Y', $queuetime));
		$nextmonth = mktime (0,0,1,(date('m', $queuetime)+1),1,date('Y', $queuetime));

		$query = "SELECT SUM(queuesize) AS queuesize FROM " . SENDSTUDIO_TABLEPREFIX . "stats_users WHERE userid='" . $userid . "' AND queuetime >= '" . $thismonth . "' AND queuetime < '" . $nextmonth . "'";

		$result = $this->Db->Query($query);

		$existing_queuesize = $this->Db->FetchOne($result);

		/**
		* If the queue size is more than the user per-month limit, that's bad!
		* If it's over, then we'll see if there is a 'maxoversize' limit we can use.
		*
		* The user 'per_month' actually gets updated by UpdateRecipient
		*/
		$new_queuesize = $existing_queuesize + $queuesize;

		if (($per_month > 0) && ($new_queuesize > $per_month)) {

			// if they are already over their limit, don't worry about checking the maxoversize limit - just reject it altogether.
			if ($existing_queuesize >= $per_month) {
				return array(false, 'OverLimit_PerMonth');
			}

			$size_difference = (($queuesize + SENDSTUDIO_MAXOVERSIZE) - $per_month);

			if ($size_difference > 0) {
				/**
				* If there is a max oversize limit, then check if we're still going to go over that limit.
				*/
				if (SENDSTUDIO_MAXOVERSIZE > 0) {

					/**
					* If we are still going over the limit, don't allow this to be sent.
					*/
					if ($new_queuesize > (SENDSTUDIO_MAXOVERSIZE + $per_month)) {
						$this->NotifyAdmin($userid, ($size_difference - SENDSTUDIO_MAXOVERSIZE), $queuetime, 'OverLimit_PerMonth');
						return array(false, 'OverLimit_PerMonth');
					}

					/**
					* If we are over our max-emails but under the limit, then notify the admin user, but let the newsletter/autoresponder through.
					*/
					$this->NotifyAdmin($userid, ($size_difference - SENDSTUDIO_MAXOVERSIZE), $queuetime, 'OverLimit_PerMonth');
				} else {
					return array(false, 'OverLimit_PerMonth');
				}
			}
		}
		return array(true, false);
	}

	function RecordBounceInfo($subscriberid=0, $statid=0, $bouncetype='soft')
	{
		$statid = (int)$statid;

		$query = "SELECT statid, 'stats_autoresponders' AS tabletype FROM " . SENDSTUDIO_TABLEPREFIX . "stats_autoresponders WHERE statid='" . $statid . "' UNION SELECT statid, 'stats_newsletters' AS tabletype FROM " . SENDSTUDIO_TABLEPREFIX . "stats_newsletters WHERE statid='" . $statid . "'";

		$result = $this->Db->Query($query);
		if (!$result) {
			return false;
		}

		$row = $this->Db->Fetch($result);
		if (empty($row)) {
			return false;
		}

		$table = $row['tabletype'];

		$bouncetype = strtolower(substr($bouncetype, 0, 4));

		$bounce_table = "bouncecount_" . $bouncetype;
		if (!in_array($bouncetype, array('soft', 'hard'))) {
			$bounce_table = 'bouncecount_unknown';
		}

		$stats_update_query = "UPDATE " . SENDSTUDIO_TABLEPREFIX . $table . " SET " . $bounce_table . "=" . $bounce_table . "+1 WHERE statid='" . (int)$statid . "'";
		$result = $this->Db->Query($stats_update_query);
		return true;
	}

	function UpdateUserStats($userid, $jobid, $statid)
	{
		$query = "UPDATE " . SENDSTUDIO_TABLEPREFIX . "stats_users SET statid='" . (int)$statid . "' WHERE userid='" . (int)$userid . "' AND jobid='" . (int)$jobid . "'";
		$result = $this->Db->Query($query);
		return true;
	}

	function DeleteUserStats($userid=0, $jobid=0)
	{
		$userid = (int)$userid;
		$jobid = (int)$jobid;
		if ($userid <= 0 || $jobid <= 0) {
			return false;
		}

		$query = "DELETE FROM " . SENDSTUDIO_TABLEPREFIX . "stats_users WHERE userid='" . $userid . "' AND jobid='" . $jobid . "'";
		$result = $this->Db->Query($query);

		return true;
	}

	function GetSentNewsletterStats($queueid=0)
	{
		$query = "SELECT SUM(htmlrecipients + textrecipients + multipartrecipients) AS total_sent FROM " . SENDSTUDIO_TABLEPREFIX . "stats_newsletters WHERE queueid='" . (int)$queueid . "'";
		return $this->Db->FetchOne($query, 'total_sent');
	}

	function ChangeUserStats($userid=0, $jobid=0, $new_size=0)
	{
		$query = "UPDATE " . SENDSTUDIO_TABLEPREFIX . "stats_users SET queuesize='" . (int)$new_size . "' WHERE userid='" . (int)$userid . "' AND jobid='" . (int)$jobid . "'";
		$this->Db->Query($query);
	}

	/**
	* @see CheckUserStats
	*/
	function RecordUserStats($userid=0, $jobid=0, $queuesize=0, $queuetime=0, $statid=0)
	{
		$userid = (int)$userid;
		$jobid = (int)$jobid;
		$queuesize = (int)$queuesize;

		if ($queuetime == 0) {
			$queuetime = $this->GetServerTime();
		}

		if ($userid <= 0 || $jobid <= 0 || $queuesize <= 0) {
			return false;
		}

		$query = "INSERT INTO " . SENDSTUDIO_TABLEPREFIX . "stats_users(userid, statid, jobid, queuesize, queuetime) VALUES ('" . $userid . "', '" . (int)$statid . "', '" . $jobid . "', '" . $queuesize . "', '" . $queuetime . "')";
		$result = $this->Db->Query($query);

		return true;
	}

	function GetStatsIds($type=null, $listids=array())
	{
		$stats_type = strtolower(substr($type, 0, 1));

		$listids = $this->CheckIntVars($listids);

		switch ($stats_type) {
			case 'n':
				$query = "SELECT snl.statid AS statid FROM " . SENDSTUDIO_TABLEPREFIX . "stats_newsletter_lists snl, " . SENDSTUDIO_TABLEPREFIX . "stats_newsletters sn, " . SENDSTUDIO_TABLEPREFIX . "lists l, " . SENDSTUDIO_TABLEPREFIX . "newsletters n WHERE sn.newsletterid=n.newsletterid AND l.listid=snl.listid AND snl.statid=sn.statid AND snl.listid IN (" . implode(',', array_keys($listids)) . ") ORDER BY sn.statid DESC";
			break;
		}

		$result = $this->Db->Query($query);

		$return_list = array();
		while ($row = $this->Db->Fetch($result)) {
			$return_list[] = $row['statid'];
		}

		return $return_list;
	}

	function GetQuickStats($user_object)
	{

		$listids = $user_object->GetLists();

		$lids = array_keys($listids);
		$lids = $this->CheckIntVars($lids);

		$stats = array(
			'subscribes' => array(
				'total' => 0,
				'today' => 0,
				'this_week' => 0,
				'this_month' => 0,
				'this_year' => 0
			),
			'unsubscribes' => array(
				'total' => 0,
				'today' => 0,
				'this_week' => 0,
				'this_month' => 0,
				'this_year' => 0
			),
			'lists' => array(
				'total' => sizeof($listids)
			)
		);

		if (empty($lids)) {
			return $stats;
		}

		$today = AdjustTime(array(0, 0, 1, date('m'), date('d'), date('Y')));

		$dow = date('w');
		$this_week = $today - ($dow * 86400);

		$this_month = AdjustTime(array(0, 0, 1, date('m'), 1, date('Y')));

		$this_year = AdjustTime(array(0, 0, 1, 1, 1, date('Y')));

		$queries = array(
			'total' => "SELECT SUM(subscribecount) AS count FROM " . SENDSTUDIO_TABLEPREFIX . "lists WHERE 1=1",
			'today' => "SELECT COUNT(*) AS count FROM " . SENDSTUDIO_TABLEPREFIX . "list_subscribers WHERE subscribedate >= " . $today . " AND unsubscribed=0 AND bounced=0",
			'this_week' => "SELECT COUNT(*) AS count FROM " . SENDSTUDIO_TABLEPREFIX . "list_subscribers WHERE subscribedate >= " . $this_week . " AND unsubscribed=0 AND bounced=0",
			'this_month' => "SELECT COUNT(*) AS count FROM " . SENDSTUDIO_TABLEPREFIX . "list_subscribers WHERE subscribedate >= " . $this_month . " AND unsubscribed=0 AND bounced=0",
			'this_year' => "SELECT COUNT(*) AS count FROM " . SENDSTUDIO_TABLEPREFIX . "list_subscribers WHERE subscribedate >= " . $this_year . " AND unsubscribed=0 AND bounced=0"
		);

		foreach ($queries as $type => $query) {
			if (!$user_object->Admin()) {
				$query .= " AND listid IN (" . implode(',', $lids) . ")";
			}
			$count = $this->Db->FetchOne($query, 'count');
			$stats['subscribes'][$type] = $count;
		}

		$queries = array(
			'total' => "SELECT SUM(unsubscribecount) AS count FROM " . SENDSTUDIO_TABLEPREFIX . "lists WHERE 1=1",
			'today' => "SELECT COUNT(*) AS count FROM " . SENDSTUDIO_TABLEPREFIX . "list_subscribers_unsubscribe WHERE unsubscribetime >= " . $today,
			'this_week' => "SELECT COUNT(*) AS count FROM " . SENDSTUDIO_TABLEPREFIX . "list_subscribers_unsubscribe WHERE unsubscribetime >= " . $this_week,
			'this_month' => "SELECT COUNT(*) AS count FROM " . SENDSTUDIO_TABLEPREFIX . "list_subscribers_unsubscribe WHERE unsubscribetime >= " . $this_month,
			'this_year' => "SELECT COUNT(*) AS count FROM " . SENDSTUDIO_TABLEPREFIX . "list_subscribers_unsubscribe WHERE unsubscribetime >= " . $this_year
		);


		foreach ($queries as $type => $query) {
			if (!$user_object->Admin()) {
				$query .= " AND listid IN (" . implode(',', $lids) . ")";
			}
			$count = $this->Db->FetchOne($query, 'count');
			$stats['unsubscribes'][$type] = $count;
		}

		return $stats;
	}

	function CalculateStatsType()
	{
		$thisuser = &GetUser();
		$calendar_settings = $thisuser->GetSettings('Calendar');

		if (empty($calendar_settings)) {
			$calendar_settings['DateType'] = 'alltime';
		}

		$calendar_type = strtolower($calendar_settings['DateType']);
		$this->calendar_type = $calendar_type;

		if (in_array($calendar_type, $this->daily_stats_types)) {
			$this->stats_type = 'daily';
		}

		if ($calendar_type == 'custom') {
			// if they are exactly the same day, show the daily graph.
			if ($calendar_settings['From']['Day'] == $calendar_settings['To']['Day'] &&
				$calendar_settings['From']['Mth'] == $calendar_settings['To']['Mth'] &&
				$calendar_settings['From']['Yr'] == $calendar_settings['To']['Yr']) {
					$this->stats_type = 'daily';
				}

			// if they are the same mth & year, then check whether the it's more than 7 days.
			// if it's more than 7 days, it's a monthly graph.
			// if it's less than 7 days, it's a last7days graph.
			if ($calendar_settings['From']['Mth'] == $calendar_settings['To']['Mth'] && $calendar_settings['From']['Yr'] == $calendar_settings['To']['Yr']) {
				if (($calendar_settings['To']['Day'] - $calendar_settings['From']['Day']) > 7) {
					$this->stats_type = 'monthly';
				} else {
					$this->stats_type = 'last7days';
				}
			}
		}

		if ($calendar_type == 'last7days') {
			$this->stats_type = $calendar_type;
		}

		if (in_array($calendar_type, $this->monthly_stats_types)) {
			$this->stats_type = 'monthly';
		}
	}

	function GetLastNewsletterSent($userid=0)
	{
		$userid = (int)$userid;
		if ($userid <= 0) {
			return false;
		}
		$query = "SELECT starttime FROM " . SENDSTUDIO_TABLEPREFIX . "stats_newsletters WHERE sentby='" . $this->Db->Quote($userid) . "' ORDER BY starttime DESC LIMIT 1";
		$result = $this->Db->Query($query);
		return $this->Db->FetchOne($result, 'starttime');
	}

	function GetUserMailingLists($userid=0)
	{
		$userid = (int)$userid;
		if ($userid <= 0) {
			return false;
		}
		$query = "SELECT COUNT(listid) AS count FROM " . SENDSTUDIO_TABLEPREFIX . "lists WHERE ownerid='" . $this->Db->Quote($userid) . "'";
		$result = $this->Db->Query($query);
		return $this->Db->FetchOne($result, 'count');
	}

	function GetUserAutoresponders($userid=0)
	{
		$userid = (int)$userid;
		if ($userid <= 0) {
			return false;
		}
		$query = "SELECT COUNT(autoresponderid) AS count FROM " . SENDSTUDIO_TABLEPREFIX . "autoresponders WHERE ownerid='" . $this->Db->Quote($userid) . "'";
		$result = $this->Db->Query($query);
		return $this->Db->FetchOne($result, 'count');
	}

	function GetUserNewsletterStats($userid=0)
	{
		$userid = (int)$userid;
		if ($userid <= 0) {
			return false;
		}

		$return = array(
			'newsletters_sent' => 0,
			'total_emails_sent' => 0,
			'unique_opens' => 0,
			'total_opens' => 0,
			'total_bounces' => 0
		);

		$query = "SELECT COUNT(statid) AS newsletters_sent, SUM(htmlrecipients + textrecipients + multipartrecipients) AS total_emails_sent, SUM(emailopens_unique) AS unique_opens, SUM(emailopens) AS total_opens, SUM(bouncecount_soft + bouncecount_hard + bouncecount_unknown) AS total_bounces FROM " . SENDSTUDIO_TABLEPREFIX . "stats_newsletters WHERE sentby='" . $this->Db->Quote($userid) . "'";

		$result = $this->Db->Query($query);

		$return = $this->Db->Fetch($result);

		return $return;
	}

	function SummarizeEmailSend($queueid=0, $statid=0, $userid=0, $optimize_table=true)
	{
		if (SENDSTUDIO_DATABASE_TYPE == 'mysql') {
			$query = "SELECT COUNT(queueid) AS count, FROM_DAYS(TO_DAYS(processtime)) AS dte, HOUR(processtime) AS hr FROM " . SENDSTUDIO_TABLEPREFIX . "queues WHERE queueid='" . $this->Db->Quote($queueid) . "' GROUP BY to_days(processtime), hr";
		}

		if (SENDSTUDIO_DATABASE_TYPE == 'pgsql') {
			$query = "SELECT COUNT(queueid) AS count, CAST(DATE_TRUNC('day', processtime) AS date) AS dte, EXTRACT('hour' from processtime) as hr FROM " . SENDSTUDIO_TABLEPREFIX . "queues WHERE queueid='" . $this->Db->Quote($queueid) . "' GROUP BY dte, hr";
		}

		$userid = (int)$userid;

		$result = $this->Db->Query($query);
		while ($row = $this->Db->Fetch($result)) {

			$time = strtotime($row['dte'] . ' ' . $row['hr'] . ':00:00');

			$query = "INSERT INTO " . SENDSTUDIO_TABLEPREFIX . "user_stats_emailsperhour(statid, sendtime, emailssent, userid) VALUES ('" . $this->Db->Quote($statid) . "', '" . $this->Db->Quote($time) . "', '" . $this->Db->Quote($row['count']) . "', '" . $userid . "')";

			$this->Db->Query($query);
		}

		if ($optimize_table) {
			// optimize / analyze the queues table so it's kept up to date and reasonably fast.
			$this->Db->OptimizeTable(SENDSTUDIO_TABLEPREFIX . "queues");
		}
	}

	function GetUserSendSummary($userid=0, $stats_type, $restrictions=false)
	{
		$qry = "SELECT SUM(emailssent) AS count, ";
		$qry .= $this->CalculateGroupBy($stats_type, 'sendtime');
		$qry .= " FROM " . SENDSTUDIO_TABLEPREFIX . "user_stats_emailsperhour";
		$qry .= " WHERE userid='" . (int)$userid . "'";
		if ($restrictions) {
			$qry .= $restrictions;
		}

		switch ($stats_type) {
			case 'daily':
				$general_query = ' GROUP BY hr';
			break;

			case 'last7days':
				$general_query = ' GROUP BY dow';
			break;

			case 'last30days':
			case 'monthly':
				$general_query = ' GROUP BY dom';
			break;

			default:
				$general_query = ' GROUP BY mth, yr';
		}

		$qry .= $general_query;

		$return_results = array();

		$result = $this->Db->Query($qry);
		while ($row = $this->Db->Fetch($result)) {
			$return_results[] = $row;
		}

		return $return_results;
	}

	function GetListSummary($listid=0)
	{
		$summary = array(
			'emails_sent' => 0,
			'bouncecount_soft' => 0,
			'bouncecount_hard' => 0,
			'bouncecount_unknown' => 0,
			'unsubscribecount' => 0,
			'emailopens' => 0,
			'emailforwards' => 0,
			'linkclicks' => 0
		);

		$listid = (int)$listid;

		if ($listid <= 0) {
			$summary['emailopens_unique'] = 0;
			$summary['statids'] = array();
			return $summary;
		}

		// this is used by both autoresponders & newsletters.
		$select_query = "SELECT SUM(htmlrecipients + textrecipients + multipartrecipients) AS emails_sent,";
		$select_query .= "SUM(bouncecount_soft) AS bouncecount_soft, SUM(bouncecount_hard) AS bouncecount_hard, SUM(bouncecount_unknown) AS bouncecount_unknown,";
		$select_query .= "SUM(unsubscribecount) AS unsubscribecount,";
		$select_query .= "SUM(emailopens) AS emailopens,";
		$select_query .= "SUM(emailforwards) AS emailforwards,";
		$select_query .= "SUM(linkclicks) AS linkclicks";

		$newsletter_query = $select_query;
		$newsletter_query .= " FROM " . SENDSTUDIO_TABLEPREFIX . "stats_newsletters sn, ";
		$newsletter_query .= SENDSTUDIO_TABLEPREFIX . "stats_newsletter_lists snl";
		$newsletter_query .= " WHERE snl.statid=sn.statid";
		$newsletter_query .= " AND snl.listid='" . $listid . "'";

		$newsletter_result = $this->Db->Query($newsletter_query);
		// there will only ever be one row, no need to do a loop here.
		$newsletter_row = $this->Db->Fetch($newsletter_result);

		// add the info to the summary.
		foreach ($summary as $p => $item) {
			$summary[$p] += $newsletter_row[$p];
		}

		$autoresponder_query = $select_query;
		$autoresponder_query .= " FROM " . SENDSTUDIO_TABLEPREFIX . "stats_autoresponders sa, ";
		$autoresponder_query .= SENDSTUDIO_TABLEPREFIX . "autoresponders a";
		$autoresponder_query .= " WHERE sa.autoresponderid=a.autoresponderid";
		$autoresponder_query .= " AND a.listid='" . $listid . "'";

		$autoresponder_result = $this->Db->Query($autoresponder_query);
		// there will only ever be one row, no need to do a loop here.
		$autoresponder_row = $this->Db->Fetch($autoresponder_result);

		// add the info to the summary.
		foreach ($summary as $p => $item) {
			$summary[$p] += $autoresponder_row[$p];
		}

		$summary['statids'] = array();

		$statids_query = "SELECT statid FROM ";
		$statids_query .= SENDSTUDIO_TABLEPREFIX . "stats_autoresponders sa, " . SENDSTUDIO_TABLEPREFIX . "autoresponders a";
		$statids_query .= " WHERE sa.autoresponderid=a.autoresponderid AND a.listid='" . $listid . "'";
		$statids_query .= " UNION ALL ";
		$statids_query .= "SELECT statid FROM ";
		$statids_query .= SENDSTUDIO_TABLEPREFIX . "stats_newsletter_lists snl";
		$statids_query .= " WHERE snl.listid='" . $listid . "'";

		$result = $this->Db->Query($statids_query);
		while ($row = $this->Db->Fetch($result)) {
			$summary['statids'][] = $row['statid'];
		}

		$summary['emailopens_unique'] = 0;

		/**
		*
		we need to do this in case a subscriber has opened multiple newsletters or autoresponders on the same list. we can't just use the emailopens_unique number from the stats table because that doesn't take into account other newsletters/autoresponders sent to that list.
		so subscriber '1' could open newsletter '1' and autoresponder '1' - which would both be unique for their respective stats but would make an incorrect summary as it would be included twice.
		*/
		if (!empty($summary['statids'])) {
			$unique_opens_query = "SELECT COUNT(DISTINCT subscriberid) AS unique_count FROM " . SENDSTUDIO_TABLEPREFIX . "stats_emailopens WHERE statid IN (" . implode(',', $summary['statids']) . ")";
			$result = $this->Db->Query($unique_opens_query);
			$summary['emailopens_unique'] = $this->Db->FetchOne($result, 'unique_count');
		}

		return $summary;
	}

	function NotifyAdmin($userid, $size_difference, $queuetime, $langvar, $stopped_send=false)
	{
		$user = &GetUser($userid);
		$user_queuetime = AdjustTime($queuetime, false, GetLang('UserDateFormat'));

		if (!class_exists('email_api')) {
			require(dirname(__FILE__) . '/email.php');
		}

		$email_api = &new Email_API();

		$email_api->Set('Subject', GetLang('User_OverQuota_Subject'));

		$username = $user->Get('username');
		if ($user->fullname) {
			$username = $user->fullname;
			$email_api->Set('FromName', $user->fullname);
		} else {
			$email_api->Set('FromName', GetLang('SendingSystem'));
		}

		if ($user->emailaddress) {
			$email_api->Set('FromAddress', $user->emailaddress);
		} else {
			$email_api->Set('FromAddress', GetLang('SendingSystem_From'));
		}

		$over_size = number_format($size_difference, 0, GetLang('NumberFormat_Dec'), GetLang('NumberFormat_Thousands'));

		$extra_mail = '';
		if ($stopped_send) {
			$extra_mail = GetLang('User_OverQuota_StoppedSend');
		}

		$message = sprintf(GetLang('User_OverQuota_Email'), $username, $user->Get('emailaddress'), $user_queuetime, GetLang('User_'.$langvar), $over_size, $extra_mail);

		$email_api->Set('Multipart', false);

		$email_api->AddBody('text', $message);

		$email_api->ClearAttachments();
		$email_api->ClearRecipients();

		$email_api->AddRecipient(SENDSTUDIO_EMAIL_ADDRESS, '', 't');

		$email_api->Send();

		$email_api->ForgetEmail();

		// now send the user notification.

		$email_api->Set('Subject', GetLang('User_OverQuota_Subject'));

		$email_api->Set('FromName', '');

		$email_api->Set('FromAddress', SENDSTUDIO_EMAIL_ADDRESS);

		$message = sprintf(GetLang('User_OverQuota_ToUser_Email'), $user_queuetime, GetLang('User_'.$langvar), $over_size, $extra_mail);

		$email_api->Set('Multipart', false);

		$email_api->AddBody('text', $message);

		$email_api->ClearAttachments();
		$email_api->ClearRecipients();

		$email_api->AddRecipient($user->emailaddress, '', 't');

		$email_api->Send();

		$email_api->ForgetEmail();
	}

}

?>
