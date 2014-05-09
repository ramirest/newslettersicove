<?php
/**
* This is the bounce job system object.
*
* The rules for bounce processing are in the language/jobs_bounce.php file.
*
* @version     $Id: jobs_bounce.php,v 1.16 2007/05/03 07:09:15 chris Exp $

*
* @package API
* @subpackage Jobs
*/

/**
* Require the bounce api class.
* That includes the base api class so we don't have to worry about it.
*/
require(dirname(__FILE__) . '/bounce.php');

/**
* This class handles job processing. It will also handle notifying job owners of when things happen as necessary.
*
* The rules for bounce processing are in the language/jobs_bounce.php file.
*
* @package API
* @subpackage Jobs
*/
class Jobs_Bounce_API extends Bounce_API
{

	/**
	* The job details for the job we're running. This is used to save database queries.
	*
	* @see FetchJob
	* @see ProcessJob
	* @see ActionJob
	*
	* @var Array
	*/
	var $jobdetails = array();

	/**
	* FetchJob
	* Gets the next job in the queue for this job type ('bounce').
	* If there is no next job it returns false.
	*
	* @see jobstatus
	* @see jobdetails
	* @see jobowner
	*
	* @return Mixed Returns false if there is no next job. Otherwise sets up jobstatus, jobdetails and jobowner for easy user and returns the job id.
	*/
	function FetchJob()
	{
		$query = "SELECT * FROM " . SENDSTUDIO_TABLEPREFIX . "jobs WHERE jobtype='bounce' AND jobstatus ='w' AND jobtime < " . $this->GetServerTime() . " ORDER BY jobtime ASC LIMIT 1";
		$result = $this->Db->Query($query);
		if (!$result) {
			return false;
		}

		$row = $this->Db->Fetch($result);
		if (empty($row)) {
			return false;
		}

		$this->jobstatus = $row['jobstatus'];
		$this->jobdetails = unserialize($row['jobdetails']);
		$this->jobowner = $row['ownerid'];
		return $row['jobid'];
	}

	/**
	* ProcessJob
	* This starts the job, loads up the job owner (for emailing), actions the job and marks it complete. All of the work is done by actionjob.
	*
	* @param Int $jobid Jobid to process.
	*
	* @see user
	* @see StartJob
	* @see ActionJob
	* @see FinishJob
	*
	* @return Boolean Returns false if the job can't be started. Otherwise will action the job and finish the job and return true.
	*/
	function ProcessJob($jobid=0)
	{
		$this->user = &GetUser($this->jobowner);

		if (!$this->StartJob($jobid)) {
			return false;
		}

		$this->ActionJob($jobid);

		$this->FinishJob($jobid);

		return true;
	}

	/**
	* ActionJob
	* Does the work of the job.
	* This logs in to an email account, looks at each email and determines whether an email is a bounce email or not. It will update the database if necessary and finally delete the email from the email account.
	* If the email account can't be accessed, the job owner will be notified (eg bad login details)
	*
	* @param Int $jobid Jobid to process.
	*
	* @see jobdetails
	* @see ParseBody
	*
	* @return Boolean Returns false if the job can't be started. Otherwise will action the job and finish the job and return true.
	*/
	function ActionJob($jobid=0)
	{
		if (!isset($this->jobdetails['bounceserver']) || !isset($this->jobdetails['bounceusername']) || !isset($this->jobdetails['bouncepassword'])) {
			return false;
		}

		$this->Set('bounceserver', $this->jobdetails['bounceserver']);
		$this->Set('bounceuser', $this->jobdetails['bounceusername']);
		$this->Set('bouncepassword', $this->jobdetails['bouncepassword']);
		$this->Set('imapaccount', $this->jobdetails['imapaccount']);
		$this->Set('extramailsettings', $this->jobdetails['extramailsettings']);

		$inbox = $this->Login();

		if (!$inbox) {
			$subject = GetLang('BadLogin_Subject_Cron');
			$msg = sprintf(GetLang('BadLogin_Details_Cron'), $this->jobdetails['listname'], $this->ErrorMessage);
			$this->NotifyOwner($subject, $msg);
			return false;
		}

		$emailcount = $this->GetEmailCount();

		if ($emailcount <= 0) {
			$this->Logout();
			return true;
		}

		$job_listid = current($this->jobdetails['Lists']);

		for ($emailid = 1; $emailid <= $emailcount; $emailid++) {
			$processed = $this->ProcessEmail($emailid, $job_listid);

			// see api/bounce.php for what 'delete' means.
			if (in_array($processed, array('hard', 'soft', 'delete'))) {
				$this->DeleteEmail($emailid);
			}
		}

		$this->Logout(true);
		return true;
	}
}

?>
