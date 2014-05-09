<?php
/**
* This file handles link tracking and processing. It will record the link click and then redirect to the proper location.
*
* @version     $Id: link.php,v 1.12 2007/06/15 04:29:49 chris Exp $

*
* @package SendStudio
*/

/**
* Displaying an open image does not need a session.
*/
define('SENDSTUDIO_NO_SESSION', true);

/**
* Require base sendstudio functionality. This connects to the database, sets up our base paths and so on.
*/
require(dirname(__FILE__) . '/admin/functions/init.php');

if (!SENDSTUDIO_IS_SETUP) {
	exit();
}

/**
* This file lets us get api's, load language files and parse templates.
*/
require(dirname(__FILE__) . '/admin/functions/sendstudio_functions.php');

$sendstudio_functions = & new Sendstudio_Functions();

$statsapi = $sendstudio_functions->GetApi('Stats');
$subscriberapi = $sendstudio_functions->GetApi('Subscribers');

$foundparts = array();

foreach ($_GET as $p => $part) {
	$foundparts[strtolower($p)] = $part;
}

/**
* No link? Exit.
*/
if (!isset($foundparts['l'])) {
	echo __LINE__ . '<br/>';
	echo 'Invalid Link.<br>';
	exit();
}
$linkid = (int)$foundparts['l'];

/**
* No "member" info? Exit.
*/
if (!isset($foundparts['m'])) {
	echo __LINE__ . '<br/>';
	echo 'Invalid Link.<br>';
	exit();
}
$subscriberid = (int)$foundparts['m'];

if (isset($foundparts['a'])) {
	$statstype = 'auto';
	$statid = $foundparts['a'];
} else {
	$statstype = 'newsletter';
	$statid = $foundparts['n'];
}

$url = $statsapi->FetchLink($linkid, $statid);
if (!$url) {
	echo 'Invalid Link.<br>';
	exit();
}

// make sure it's a full url.
if (substr($url, 0, 4) != 'http') {
	$url = 'http://' . $url;
}

$stats_info = $statsapi->FetchStats($statid, $statstype);

$lists = $stats_info['Lists'];

$listinfo = $subscriberapi->IsSubscriberOnList(null, $lists, $subscriberid, false, false, true);
$subscriberinfo = $subscriberapi->LoadSubscriberList($subscriberid, $listinfo['listid'], true);

$url = $statsapi->CleanVersion($url, $subscriberinfo);

/**
* IE doesn't like redirecting to urls with an anchor on the end - so we'll strip it off.
$newurl = parse_url($url);
$url = $newurl['scheme'] . '://' . $newurl['host'];
if (isset($newurl['path'])) {
	$url .= $newurl['path'];
	if (isset($newurl['query'])) {
		$url .= '?' . $newurl['query'];
	}
}
*/

$clicktime = $statsapi->GetServerTime();
$clickip = $statsapi->GetRealIp();

$click_details = array(
	'clicktime' => $clicktime,
	'clickip' => $clickip,
	'subscriberid' => $subscriberid,
	'statid' => $statid,
	'linkid' => $linkid
);

$statsapi->RecordLinkClick($click_details, $statstype);

header('Location: ' . $url);

?>
