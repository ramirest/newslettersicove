<?php
/**
* This file handles open tracking and processing. It will record the opening of a newsletter/autoresponder and display an empty image when it's done.
*
* @version     $Id: open.php,v 1.9 2007/05/09 01:13:37 chris Exp $

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
	DisplayImage();
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

$areas_to_check = array('M', 'L');
foreach ($areas_to_check as $p => $key) {
	if (!isset($_GET[$key])) {
		DisplayImage();
		exit();
	}
	$foundparts[strtolower($key)] = $_GET[$key];
}

if (isset($_GET['N'])) {
	$foundparts['n'] = (int)$_GET['N'];
}

if (isset($_GET['A'])) {
	$foundparts['a'] = (int)$_GET['A'];
}

if (!isset($foundparts['a']) && !isset($foundparts['n'])) {
	DisplayImage();
	exit();
}

if (isset($foundparts['m'])) {
	$subscriber_id = $foundparts['m'];
} else {
	DisplayImage();
	exit();
}

/**
* Find which lists this item was sent to.
*/
if (isset($foundparts['a'])) {
	$statstype = 'auto';
	$statid = $foundparts['a'];
} else {
	$statstype = 'newsletter';
	$statid = $foundparts['n'];
}

$send_details = $statsapi->FetchStats($statid, $statstype);

if (empty($send_details['Lists'])) {
	DisplayImage();
	exit();
}

$opentime = $statsapi->GetServerTime();
$openip = $statsapi->GetRealIp();

$open_details = array(
	'opentime' => $opentime,
	'openip' => $openip,
	'subscriberid' => $subscriber_id,
	'statid' => $statid
);

$statsapi->RecordOpen($open_details, $statstype);

DisplayImage();
exit();

/**
* DisplayImage
* Loads up the 'openimage' and displays it. It will exit after displaying the image.
*
* @return Void Doesn't return anything.
*/
function DisplayImage()
{
	// open the file in a binary mode
	$name = SENDSTUDIO_IMAGE_DIRECTORY . '/open.gif';
	$fp = fopen($name, 'rb');

	// send the right headers
	header("Content-Type: image/gif");
	header("Content-Length: " . filesize($name));

	// dump the picture and stop the script
	fpassthru($fp);
	exit(0);
}

?>
