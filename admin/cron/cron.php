<?php
/**
* This file handles all cron jobs together. It does:
*
* scheduled sending
* autoresponders
* bounce processing
*
* in that order.
*
* @version     $Id: cron.php,v 1.9 2007/03/23 03:30:02 chris Exp $

*
* @package SendStudio
*/

/**
* Set a variable so the other jobs know whether to include the base init.php file or not.
*/
define('SINGLE_JOB', true);

/**
* This tells the session whether it needs to start or not.
*/
define('SENDSTUDIO_CRON_JOB', true);

/**
* Easy reference to this directory.
*/
$mydir = dirname(__FILE__);

/**
* Include the base init file.
*/
require(dirname($mydir) . '/functions/init.php');

/**
* Then run each of the other jobs in order.
*/
require($mydir . '/send.php');
require($mydir . '/autoresponders.php');
require($mydir . '/bounce.php');

?>
