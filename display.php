<?php
/**
* This file handles displaying archived newsletters. This displays individual archives rather than a list.
*
* @version     $Id: display.php,v 1.7 2007/05/15 07:03:41 rodney Exp $

*
* @package SendStudio
*/

/**
* Displaying an archive does not need a session to be started.
*/
define('SENDSTUDIO_NO_SESSION', true);

/**
* Require base sendstudio functionality. This connects to the database, sets up our base paths and so on.
*/
require(dirname(__FILE__) . '/admin/functions/init.php');

if (SENDSTUDIO_IS_SETUP != 1) {
	exit();
}

/**
* This file lets us get api's, load language files and parse templates.
*/
require(dirname(__FILE__) . '/admin/functions/sendstudio_functions.php');

$sendstudio_functions = & new Sendstudio_Functions();

$listapi = $sendstudio_functions->GetApi('Lists');
$newsletterapi = $sendstudio_functions->GetApi('Newsletters');
$autoapi = $sendstudio_functions->GetApi('Autoresponders');

$subscriberapi = $sendstudio_functions->GetApi('Subscribers');

$listid = 0;
if (isset($_GET['List'])) {
	$listid = (int)$_GET['List'];
} else {
	if (isset($_GET['L'])) {
		$listid = (int)$_GET['L'];
	}
}

$newsletterid = (isset($_GET['N'])) ? (int)$_GET['N'] : 0;
$autoresponderid = (isset($_GET['A'])) ? (int)$_GET['A'] : 0;

$subscriberid = 0;
$confirmcode = false;
$subscriberinfo = array();

if (isset($_GET['M'])) {
	if (!isset($_GET['C'])) {
		// found a member id but no confirm code? Eek!
		echo 'Invalid archive link.';
		exit();
	}
	$subscriberid = (int)$_GET['M'];
	$confirmcode = $_GET['C'];
}

/**
* Since we're displaying a specific newsletter we can check for the list before anything else.
* If it's not valid, we can abort.
*/
if (!$listid || (!$newsletterid && !$autoresponderid)) {
	echo 'Invalid archive link.';
	exit();
}
$list_loaded = $listapi->Load($listid);

if (!$list_loaded) {
	echo 'Invalid archive link.';
	exit();
}

if ($newsletterid) {
	$id = $newsletterid;
	$api = $newsletterapi;
} else {
	$id = $autoresponderid;
	$api = $autoapi;
}

$loaded = $api->Load($id);

if (!$loaded) {
	echo 'Invalid archive link.';
	exit();
}

/**
* Make sure the newsletter is ok to be displayed.
* If it's not in "archive" mode or "active" mode, don't show anything.
*/
if (!$api->Archive() || !$api->Active()) {
	echo 'Invalid archive link.';
	exit();
}

$format = $api->Get('format');
if ($format == 't') {
	$description = nl2br($api->GetBody('text'));
} else {
	$description = $api->GetBody('html');
}

if ($subscriberid > 0 && $confirmcode) {
	$sub_listinfo = $subscriberapi->LoadSubscriberList($subscriberid, $listid, true, true, true);
	if (isset($sub_listinfo['confirmcode']) && $sub_listinfo['confirmcode'] == $confirmcode) {
		$subscriberinfo = $sub_listinfo;
		$subscriberinfo['listid'] = $listid;
		$subscriberinfo['listname'] = $listapi->Get('name');
		if ($newsletterid) {
			$subscriberinfo['newsletter'] = $newsletterid;
		}
	}
}

header('Content-type: text/html; charset='.SENDSTUDIO_CHARSET);
echo $api->CleanVersion($description, $subscriberinfo);

?>
