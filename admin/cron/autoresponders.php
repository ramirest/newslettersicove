<?php
/**
* This file handles autoresponder sending by itself. It's main task is to set up the required information (classes), load up the database and so on, then pass control over to the jobs_autoresponders.php file which runs everything from there.
* This can be run as a separate cron job to the jobs.php file to allow more specific / frequent scheduling of just autoresponders.
*
* @version     $Id: autoresponders.php,v 1.12 2007/05/31 04:49:38 chris Exp $

*
* @package SendStudio
*/

$time_start = time();

/**
* Include the base init file which connects to the database, sets up correct locations etc.
*/
if (!defined('SINGLE_JOB')) {
	define('SENDSTUDIO_CRON_JOB', true);
	require(dirname(dirname(__FILE__)) . '/functions/init.php');
}

$disabled_functions = explode(',', SENDSTUDIO_DISABLED_FUNCTIONS);

if (!SENDSTUDIO_SAFE_MODE && !in_array('set_time_limit', $disabled_functions)) {
	set_time_limit(0);
}

/**
* Sendstudio isn't set up? Quit.
*/
if (!defined('SENDSTUDIO_IS_SETUP') || !SENDSTUDIO_IS_SETUP) {
	exit();
}

/**
* Sendstudio isn't supposed to use cron? Quit.
*/
if (SENDSTUDIO_CRON_ENABLED != 1) {
	exit();
}

if (!class_exists('sendstudio_functions')) {
	require(SENDSTUDIO_FUNCTION_DIRECTORY.'/sendstudio_functions.php');
}

/**
* So we can fetch attachments.
*/
$GLOBALS['SendStudio_Functions'] = &new SendStudio_Functions();

/**
* Include the job api. This will let us fetch jobs from the queue and start them up.
*/
require(SENDSTUDIO_API_DIRECTORY . '/jobs_autoresponders.php');

/**
* Set up the Autoresponder Jobs API.
*/
$JobsAPI = &new Jobs_Autoresponders_API();

while($job = $JobsAPI->FetchJob('autoresponder')) {
	$result = $JobsAPI->ProcessJob($job);
	if (!$result) {
		echo "*** WARNING *** autoresponder job " . $job . " couldn't be processed.\n";
	}
}

$time_end = time();
$time_taken = ($time_end - $time_start);

?>
