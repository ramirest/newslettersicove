<?php
/**
* This file handles bounce processing by itself. It's main task is to set up the required information (classes), load up the database and so on, then pass control over to the jobs_bounce.php file which runs everything from there.
* This can be run as a separate cron job to the jobs.php file to allow more specific / frequent scheduling of just bounce processing.
*
* @version     $Id: bounce.php,v 1.19 2007/05/31 04:49:38 chris Exp $

*
* @package SendStudio
*/

$time_start = time();

/**
* Require the base sendstudio functions and set up variables.
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

/**
* The rules for bounce processing are in the language/jobs_bounce.php file.
* It is included in Jobs_Bounce_API
*
* @see Jobs_Bounce_API::Jobs_Bounce_API
*/

/**
* Include the job api. This will let us fetch jobs from the queue and start them up.
*/
require(SENDSTUDIO_API_DIRECTORY . '/jobs_bounce.php');

/**
* Set up the Bounce Jobs API.
*/
$JobsAPI = &new Jobs_Bounce_API();

/**
* We create the jobs for just before now so we can find / process them properly.
*/
$timenow = $JobsAPI->GetServerTime() - 5;

/**
* We're going to create the bounce jobs ourselves.
* We can run this once a day and it'll go through every list to check for bounce details.
* We only search for lists that have the server name, username and password specified.
*
* We only look for email accounts that haven't been processed for 10 minutes - there's no need to process them more often than that.
*
* @see JobsAPI::Create
*/

$jobs_created = array();

$found_job = false;

$query = "SELECT listid, name, bounceserver, bounceusername, bouncepassword, imapaccount, extramailsettings, ownerid FROM " . SENDSTUDIO_TABLEPREFIX . "lists WHERE bounceserver != '' and bounceusername != '' and bouncepassword != ''";
$result = $JobsAPI->Db->Query($query);
while($listrow = $JobsAPI->Db->Fetch($result)) {
	$found_job = true;

	$details = array(
		'Lists' => array($listrow['listid']),
		'listname' => $listrow['name'],
		'bounceserver' => $listrow['bounceserver'],
		'bounceusername' => $listrow['bounceusername'],
		'bouncepassword' => $listrow['bouncepassword'],
		'extramailsettings' => $listrow['extramailsettings'],
		'imapaccount' => $listrow['imapaccount']
	);
	$jobcreated = $JobsAPI->Create('bounce', $timenow, $listrow['ownerid'], $details, 'bounce', $listrow['listid'], $listrow['listid']);
	$jobs_created[] = $jobcreated;
}

if (!$found_job) {
	exit;
}

/**
* Check for imap support before we go any further.
*/
if (!function_exists('imap_open')) {
	foreach ($jobs_created as $p => $jobid) {
		$JobsAPI->Delete($jobid);
	}
	echo GetLang('ImapSupportMissing');
	exit();
}


/**
* Now we go through each job we have just created and process it.
*
* @see Jobs_Bounce_API::FetchJob
* @see Jobs_Bounce_API::ProcessJob
* @see Jobs_Bounce_API::Delete
*/
while($job = $JobsAPI->FetchJob('bounce')) {
	$result = $JobsAPI->ProcessJob($job);
	if (!$result) {
		echo "*** WARNING *** bounce job " . $job . " couldn't be processed.\n";
		break;
	}
	// we delete these because we're only temporarily creating them (there's no management area in sendstudio for bounce jobs).
	$JobsAPI->Delete($job);
}

$time_end = time();
$time_taken = ($time_end - $time_start);

?>
