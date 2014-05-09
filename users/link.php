<?php
/**
* This file is here for b/c reasons. It will only redirect the subscriber to the right link. It will not record statistics.
*
* @version     $Id: link.php,v 1.3 2007/05/09 01:12:57 chris Exp $

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
require(dirname(__FILE__) . '/../admin/functions/init.php');

if (!SENDSTUDIO_IS_SETUP) {
	exit();
}

$LinkID = 0;
if (isset($_REQUEST['LinkID'])) {
	$LinkID = (int)$_REQUEST['LinkID'];
}
if (isset($_REQUEST['L'])) {
	$LinkID = (int)$_REQUEST['L'];
}

$db = &GetDatabase();
$query = "SELECT URL FROM " . SENDSTUDIO_TABLEPREFIX . "old_links WHERE linkid='" . $LinkID . "'";
$result = $db->Query($query);

$url = $db->FetchOne($result, 'URL');

if (!$url) {
	echo 'Invalid Link.<br>';
	exit();
}

$url = str_replace(array('&#38;', '&amp;', ' '), array('&', '&', '%20'), stripslashes($url));

// make sure it's a full url.
if (substr($url, 0, 4) != 'http') {
	$url = 'http://' . $url;
}

header('Location: ' . $url);

?>
