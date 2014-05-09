<?php
/**
* This is the job system object. This allows you to create, modify, delete jobs and notify owners when things happen.
*
* @version     $Id: jobs.php,v 1.24 2007/05/15 07:03:56 rodney Exp $

*
* @package API
* @subpackage Jobs
*/

/**
* Require the base API class.
*/
require_once(dirname(__FILE__) . '/api.php');

/**
* Require the base sendstudio functions. This allows us to get attachments easily for each area.
*/
require_once(SENDSTUDIO_FUNCTION_DIRECTORY.'/sendstudio_functions.php');

/**
* This class handles job processing. It will also handle notifying job owners of when things happen as necessary.
*
* @package API
* @subpackage Jobs
*/
class Jobs_API extends API
{

	/**
	* This is used to hold a reference to the subjob that is being run. This allows us to quickly see whether the file has been included, whether the object has been set up etc. When the subjob returns nothing (ie there are no more jobs) this gets reset to null so next time it can be set up again.
	*
	* @var Object
	*/
	var $SubJob = null;

	/**
	* This is used to remember what type of subjob we're trying to run. This can be used to make sure we're not going to set up the wrong type and try to run the wrong thing.
	*
	* @var String
	*/
	var $SubJobType = null;

	/**
	* An array of valid job types. This is basically an integrity check to make sure you don't try to crash the script.
	*
	* @var Array
	*/
	var $JobTypes = array('bounce', 'autoresponder', 'send');

	/**
	* The owner of the job (their userid).
	*
	* @var Int
	*/
	var $jobowner = 0;

	/**
	* The time now. Used for debugging only.
	*
	* @var Int
	*/
	var $timenow = 0;

	/**
	* A reference to the subscriber api. Saves us having to re-create it all the time.
	*
	* @see Jobs_Send_API::Jobs_Send_API
	* @see Jobs_Bounce_API::Jobs_Bounce_API
	* @see ProcessJob
	* @see ActionJob
	*
	* @var Object
	*/
	var $Subscriber_API = null;

	/**
	* A reference to the email api. Saves us having to re-create it all the time.
	*
	* @see Jobs_Send_API::Jobs_Send_API
	* @see Jobs_Bounce_API::Jobs_Bounce_API
	* @see ProcessJob
	* @see ActionJob
	*
	* @var Object
	*/
	var $Email_API = null;

	/**
	* A reference to the mailing lists api. Saves us having to re-create it all the time.
	*
	* @see Jobs_Send_API::Jobs_Send_API
	* @see Jobs_Bounce_API::Jobs_Bounce_API
	* @see ProcessJob
	* @see ActionJob
	*
	* @var Object
	*/
	var $Lists_API = null;

	/**
	* A reference to the stats api. Saves us having to re-create it all the time.
	*
	* @see Jobs_Send_API::Jobs_Send_API
	* @see Jobs_Bounce_API::Jobs_Bounce_API
	* @see ProcessJob
	* @see ActionJob
	*
	* @var Object
	*/
	var $Stats_API = null;

	/**
	* Constructor
	* Sets up the database object for easy use.
	*
	* @param Boolean $mark_cron_as_run Whether to mark this is a cron job running. This will allow the settings page in the admin area to see whether cron jobs are set up correctly or not.
	*
	* @return Void Doesn't return anything.
	*/
	function Jobs_API($mark_cron_as_run=false)
	{
		$this->GetDb();
		if ($mark_cron_as_run) {
			$query = "UPDATE " . SENDSTUDIO_TABLEPREFIX . "settings SET cronok='1', cronrun1=cronrun2, cronrun2='" . $this->GetServerTime() . "'";
			$this->Db->Query($query);
		}
	}


	/**
	* Create
	* This creates a job in the database based on the details you provide.
	*
	* @param String $jobtype The type of job you are creating (can be export, send, import and so on).
	* @param Int $when The timestamp of when to run the job.
	* @param Int $ownerid The owner of the job - so they can be notified when it finishes.
	* @param Array $details The details of the job. For example, who to send the email to (search criteria)
	* @param String $fktype The type of relationship. If you send a newsletter, this is 'newsletter'. If it's an autoresponder, this is 'autoresponder'. This allows for quick matching of database fields.
	* @param Int $fkid The foreign key id (eg newsletterid).
	* @param Array $lists An array of lists this job applies to (this allows quick searching for jobs when we have to display the list of scheduled tasks).
	* @param Int $approved Whether the job is approved or not. This allows sending to not approve jobs (in case a person gets to the end of the process and change their mind about when to send the newsletter). This is not 'true' or 'false' - rather it is the userid of the person who approved the send.
	*
	* @see _CheckJobType
	*
	* @return Mixed Returns false if there is no owner or it's an invalid job type. It also returns false if the query can't be run. Otherwise, it returns the new job id.
	*/
	function Create($jobtype=null, $when=0, $ownerid=0, $details=array(), $fktype='newsletter', $fkid=0, $lists=array(), $approved=0)
	{
		if (!$this->_CheckJobType($jobtype)) {
			return false;
		}

		$ownerid = (int)$ownerid;
		if ($ownerid <= 0) {
			return false;
		}

		$fkid = (int)$fkid;

		if (!is_array($lists)) {
			$lists = array($lists);
		}

		if (!is_array($details)) {
			$details = array($details);
		}
		$details = serialize($details);

		$query = "INSERT INTO " . SENDSTUDIO_TABLEPREFIX . "jobs (jobtype, jobstatus, jobtime, jobdetails, fktype, fkid, ownerid, approved) VALUES ('" . $this->Db->Quote($jobtype) . "', 'w', " . (int)$when . ", '" . $this->Db->Quote($details) . "', '" . $this->Db->Quote($fktype) . "', " . $fkid . ", " . $ownerid . ", '" . $this->Db->Quote((int)$approved) . "')";

		$result = $this->Db->Query($query);
		if (!$result) {
			return false;
		}

		$jobid = $this->Db->LastId(SENDSTUDIO_TABLEPREFIX . 'jobs_sequence');

		foreach ($lists as $p => $listid) {
			$query = "INSERT INTO " . SENDSTUDIO_TABLEPREFIX . "jobs_lists(jobid, listid) VALUES('" . $jobid . "', '" . (int)$listid . "')";
			$this->Db->Query($query);
		}
		return $jobid;
	}

	/**
	* ApproveJob
	* This approves a previously unapproved job. This is used by scheduled sending - the last step sets up the job and leaves it unapproved. The user is presented with an option about whether to really send the newsletter or not. If they choose "yes" the job is approved. If they choose no, it is deleted / cleaned up.
	*
	* @param Int $jobid The job that needs approving.
	* @param Int $userid The user who approved the job.
	*
	* @return Boolean Returns true if the update worked. Returns false if the job or user passed in are invalid or if the query to change the job didn't work.
	*/
	function ApproveJob($jobid=0, $userid=0, $authorisedtosend=0)
	{
		$jobid = (int)$jobid;
		$userid = (int)$userid;
		if ($jobid <= 0 || $userid <= 0) {
			return false;
		}
		$query = "UPDATE " . SENDSTUDIO_TABLEPREFIX . "jobs SET approved='" . $this->Db->Quote($userid) . "', authorisedtosend='" . (int)$authorisedtosend . "' WHERE jobid='" . $this->Db->Quote($jobid) . "'";

		$result = $this->Db->Query($query);
		if (!$result) {
			return false;
		}
		return true;
	}

	/**
	* Delete
	* This deletes a job from the database.
	* It cleans up the queue of recipients and will also update statistics based on the number of recipients left in the queue.
	* A job can only be deleted if it hasn't been started.
	*
	* @param Int $jobid The job to delete.
	*
	* @see DeleteUserStats
	* @see LoadJob
	* @see JobStarted
	* @see QueueSize
	* @see ClearQueue
	*
	* @return Boolean Returns false if there is no owner or it's an invalid job. It also returns false if the query can't be run. Otherwise, it returns true.
	*/
	function Delete($jobid=0)
	{
		$jobid = (int)$jobid;
		if ($this->JobStarted($jobid)) {
			return false;
		}

		$job = $this->LoadJob($jobid);
		if (empty($job)) {
			return false;
		}

		$queue = (int)$job['queueid'];

		// if we're deleting a "send" job, we need to update statistics!!
		if ($job['jobtype'] == 'send') {
			$queuesize = $this->QueueSize($queue, 'send');

			// if the queue is empty and there was a queue before hand, then we don't need to do anything.
			if ($queuesize == 0 && $queue > 0) {
			}

			// if the queue is empty and there was no queue, delete all.
			if ($queuesize == 0 && $queue == 0) {
				$delete_all = true;
				$this->DeleteUserStats($job['ownerid'], $jobid, 0, true);
			}

			$jobdetails = $job['jobdetails'];
			$sendsize = $jobdetails['SendSize'];

			$jobowner_user = &GetUser($job['ownerid']);
			// if the user doesn't have unlimited emails, change their maxemails count.
			// but only if the job is not complete.
			if ($job['jobstatus'] != 'c') {
				if (!$jobowner_user->Get('unlimitedemails') && $sendsize > $queuesize) {
					$credits = $sendsize - $queuesize;
					$query = "UPDATE " . SENDSTUDIO_TABLEPREFIX . "users SET maxemails = maxemails + " . $credits . " WHERE userid='" . (int)$job['ownerid'] . "'";
					$this->Db->Query($query);
				}
			}
		}

		$this->ClearQueue($queue, $job['jobtype']);

		$query = "DELETE FROM " . SENDSTUDIO_TABLEPREFIX . "jobs WHERE jobid='" . $jobid . "'";
		$result = $this->Db->Query($query);
		if ($result) {
			$query = "DELETE FROM " . SENDSTUDIO_TABLEPREFIX . "jobs_lists WHERE jobid='" . $jobid . "'";
			$result = $this->Db->Query($query);
		}
		return $result;
	}

	/**
	* StartJob
	* Updates the job status in the database to mark it as 'in progress' (i).
	* Checks to make sure the job hasn't already been started by another process.
	*
	* @param Int $jobid The job id to start.
	*
	* @see JobStarted
	*
	* @return Boolean Returns false if the job id is invalid or the job has already been started. Otherwise returns the database update attempt result.
	*/
	function StartJob($jobid=0)
	{
		$jobid = (int)$jobid;
		if ($jobid <= 0) {
			return false;
		}

		if ($this->JobStarted($jobid)) {
			return false;
		}

		$time = $this->GetServerTime();

		$query = "UPDATE " . SENDSTUDIO_TABLEPREFIX . "jobs SET jobstatus='i', lastupdatetime='" . $time . "' WHERE jobid='" . $jobid . "'";
		$result = $this->Db->Query($query);
		return $result;
	}

	/**
	* PauseJob
	* Updates the job status in the database to mark it as 'paused' (p).
	* Checks to make sure the job has been started before continuing.
	*
	* @param Int $jobid The job id to pause.
	*
	* @see JobStarted
	*
	* @return Boolean Returns false if the job id is invalid or the job has not already been started. Otherwise returns the database update attempt result.
	*/
	function PauseJob($jobid=0)
	{
		$jobid = (int)$jobid;
		if ($jobid <= 0) {
			return false;
		}

	#	if (!$this->JobStarted($jobid)) {
	#		return false;
	#	}

		$query = "UPDATE " . SENDSTUDIO_TABLEPREFIX . "jobs SET jobstatus='p' WHERE jobid='" . $jobid . "'";
		$result = $this->Db->Query($query);
		return $result;
	}

	/**
	* ResumeJob
	* Updates the job status in the database to mark it as 'waiting' (w).
	* Checks to make sure the job has not been started before continuing.
	*
	* @param Int $jobid The job id to resume.
	*
	* @see JobStarted
	*
	* @return Boolean Returns false if the job id is invalid or the job has already been started. Otherwise returns the database update attempt result.
	*/
	function ResumeJob($jobid=0)
	{
		$jobid = (int)$jobid;
		if ($jobid <= 0) {
			return false;
		}

		if ($this->JobStarted($jobid)) {
			return false;
		}

		$query = "UPDATE " . SENDSTUDIO_TABLEPREFIX . "jobs SET jobstatus='w' WHERE jobid='" . $jobid . "'";
		$result = $this->Db->Query($query);
		return $result;
	}

	/**
	* LoadJobDetails
	* Loads just the job details from the database and returns them. This allows us to quickly fetch sending criteria for example for newsletter sending.
	*
	* @param Int $jobid The job id to load.
	*
	* @return Array Returns an empty array if there is no job or the query can't run. Otherwise returns an array of the job details.
	*/
	function LoadJobDetails($jobid=0)
	{
		$query = "SELECT jobdetails FROM " . SENDSTUDIO_TABLEPREFIX . "jobs WHERE jobid='" . (int)$jobid . "'";
		$result = $this->Db->Query($query);
		$row = $this->Db->Fetch($result);
		if (empty($row)) {
			return array();
		}

		return unserialize($row['jobdetails']);
	}

	/**
	* LoadJob
	* Loads the whole job from the database and returns it. This includes the owner, fktype, fkid, job details and so on.
	*
	* @param Int $jobid The job id to load.
	*
	* @return Array Returns an empty array if there is no job or the query can't run. Otherwise returns an array of the job information.
	*/
	function LoadJob($jobid=0)
	{
		$query = "SELECT * FROM " . SENDSTUDIO_TABLEPREFIX . "jobs WHERE jobid='" . (int)$jobid . "'";
		$result = $this->Db->Query($query);
		$row = $this->Db->Fetch($result);
		if (empty($row)) {
			return array();
		}

		$row['jobdetails'] = unserialize($row['jobdetails']);
		return $row;
	}

	/**
	* FindJob
	* Works out whether a job has been paused for the jobtype, queuetype and foreign key id.
	*
	* @param String $jobtype The type of job to check for (send, export etc)
	* @param String $queuetype The specific area to check (for example 'newsletter')
	* @param Int $fkid The specific id to check. Used with the area to check (eg checking for a particular newsletter to see whether it has been paused and can be resumed)
	* @param Boolean $only_approved Whether to only get approved jobs or not. Scheduled newsletter sends have to be "approved" before they are sent, this flag stops unapproved jobs from showing up on the "manage newsletters" page before they have been cleaned up.
	* @param Boolean $include_complete_jobs Whether to include complete jobs in the list that are being fetched. If this is false, complete jobs are ignored.
	*
	* @return Mixed Returns false if the query can't run or there are no jobs paused. Otherwise returns the id of the paused job.
	*/
	function FindJob($jobtype='send', $queuetype='newsletter', $fkid=0, $only_approved=false, $include_complete_jobs=true)
	{
		$query = "SELECT jobid, jobstatus FROM " . SENDSTUDIO_TABLEPREFIX . "jobs WHERE jobtype='" . $this->Db->Quote($jobtype) . "' AND fkid='" . $this->Db->Quote($fkid) . "' AND fktype='" . $this->Db->Quote($queuetype) . "'";
		if ($only_approved) {
			$query .= " AND approved > 0";
		}

		if (!$include_complete_jobs) {
			$query .= " AND jobstatus != 'c'";
		}

		$query .= " ORDER BY jobtime ASC limit 1";
		$result = $this->Db->Query($query);
		$row = $this->Db->Fetch($result);
		if (empty($row)) {
			return false;
		}

		return $row;
	}

	/**
	* FinishJob
	* Marks a job as 'complete' (c) in the database.
	* Makes sure the job has been started before trying to complete it.
	*
	* @param Int $jobid The job id to finish.
	*
	* @return Mixed Returns false if the query can't run or the job hasn't been started, otherwise true.
	*/
	function FinishJob($jobid=0)
	{
		$jobid = (int)$jobid;
		if ($jobid <= 0) {
			return false;
		}

		if (!$this->JobStarted($jobid)) {
			return false;
		}

		$query = "UPDATE " . SENDSTUDIO_TABLEPREFIX . "jobs SET jobstatus='c' WHERE jobid='" . $jobid . "'";
		$result = $this->Db->Query($query);
		return $result;
	}

	/**
	* JobStarted
	* Checks whether a job has been started by checking the 'jobstatus' in the database.
	* If the jobstatus is 'i' (in progress), the job has been started. If it's anything but 'i', the job has not been started.
	*
	* @param Int $jobid The job id to check.
	*
	* @return Boolean Returns true if the jobstatus is 'i', otherwise returns false.
	*/
	function JobStarted($jobid=0)
	{
		$jobid = (int)$jobid;
		if ($jobid <= 0) {
			return false;
		}

		$query = "SELECT jobstatus FROM " . SENDSTUDIO_TABLEPREFIX . "jobs WHERE jobid='" . $jobid . "'";
		$result = $this->Db->Query($query);
		$row = $this->Db->Fetch($result);
		if (empty($row)) {
			return false;
		}

		if ($row['jobstatus'] == 'i') {
			return true;
		}

		return false;
	}

	/**
	* JobPaused
	* Checks whether a job has been paused by checking the 'jobstatus' in the database.
	*
	* @param Int $jobid The job id to check.
	*
	* @return Boolean Returns true if the jobstatus is 'p', otherwise returns false.
	*/
	function JobPaused($jobid=0)
	{
		$jobid = (int)$jobid;
		if ($jobid <= 0) {
			return false;
		}

		$query = "SELECT jobstatus FROM " . SENDSTUDIO_TABLEPREFIX . "jobs WHERE jobid='" . $jobid . "'";
		$result = $this->Db->Query($query);
		$row = $this->Db->Fetch($result);
		if (empty($row)) {
			return false;
		}

		if ($row['jobstatus'] == 'p') {
			return true;
		}

		return false;
	}

	/**
	* JobQueue
	* Sets the queue reference for the job.
	* We can't set the queue reference once a job has been started, so this is done before starting a job.
	*
	* @param Int $jobid The job id to set the queue reference for.
	* @param Int $queueid The queue to use for this job.
	*
	* @see JobStarted
	*
	* @return Boolean Returns false if the job id is invalid or the job has already been started. Otherwise returns true.
	*/
	function JobQueue($jobid=0, $queueid=0)
	{
		$jobid = (int)$jobid;
		if ($jobid <= 0) {
			return false;
		}

		if (!$this->JobStarted($jobid)) {
			return false;
		}

		$query = "UPDATE " . SENDSTUDIO_TABLEPREFIX . "jobs SET queueid='" . (int)$queueid . "' WHERE jobid='" . $jobid . "'";
		$result = $this->Db->Query($query);
		return $result;
	}

	/**
	* GetJobQueue
	* Gets the queue reference for the job. If the job has not been started, can't get the queue.
	*
	* @param Int $jobid The job id to get the queue for.
	*
	* @see JobStarted
	*
	* @return Mixed Returns false if the job has not been started. Otherwise returns the queue reference.
	*/
	function GetJobQueue($jobid=0)
	{
		$jobid = (int)$jobid;
		if ($jobid <= 0) {
			return false;
		}

		if (!$this->JobStarted($jobid)) {
			return false;
		}

		$query = "SELECT queueid FROM " . SENDSTUDIO_TABLEPREFIX . "jobs WHERE jobid='" . $jobid . "'";
		$result = $this->Db->Query($query);
		$row = $this->Db->Fetch($result);
		return $row['queueid'];
	}

	/**
	* GetJobQueueSize
	* Gets the queue reference for the job and the size of the queue if it is available. If the job has not been started, can't get the queue.
	* This is used by the 'manage schedule' page so we can see how many have been sent to quickly.
	*
	* @param Int $jobid The job id to get the queue for.
	*
	* @see JobStarted
	*
	* @return Mixed Returns false if the job has not been started. Otherwise returns the queue reference and the size of the queue.
	*/
	function GetJobQueueSize($jobid=0)
	{
		$jobid = (int)$jobid;
		if ($jobid <= 0) {
			return array();
		}

		$query = "SELECT htmlrecipients + textrecipients + multipartrecipients AS totalsent, sendsize FROM " . SENDSTUDIO_TABLEPREFIX . "jobs j, " . SENDSTUDIO_TABLEPREFIX . "stats_newsletters n WHERE j.jobid='" . $jobid . "' AND n.queueid=j.queueid";
		$result = $this->Db->Query($query);
		$row = $this->Db->Fetch($result);
		return array('totalsent' => $row['totalsent'], 'sendsize' => $row['sendsize']);
	}

	/**
	* FetchJob
	* Fetches the next job from the queue.
	* If you don't specify a jobtype, it will get the next jobtype in the queue.
	* If you do specify a jobtype, it will set up the subclass object and that then handles fetching and processing of the job.
	*
	* @param String $jobtype The jobtype to fetch (eg 'send' to send scheduled newsletters).
	*
	* @see GetNextJobType
	* @see SubJob
	* @see SubJobType
	* @see _CheckJobType
	* @see ProcessJob
	*
	* @return Mixed Returns false if there is no next jobtype (if not specified). Returns false if the jobtype is invalid, or if the subjob type can't be set up. Otherwise, returns whatever the subclass object sets up and returns (most likely boolean).
	*/
	function FetchJob($jobtype=null)
	{
		if (is_null($jobtype)) {
			$jobtype = $this->GetNextJobType();
			// if there's no next jobtype - return.
			if (!$jobtype) {
				return false;
			}
			return $this->FetchJob($jobtype);
		}

		/**
		* What's this for? In case we're running a few different job types at once (an export, send and so on) - makes sure the object set up is the "right" one.
		*/
		if (!is_null($this->SubJob)) {
			if ($this->SubJobType != strtolower($jobtype)) {
				$this->SubJob = null;
				$this->SubJobType = null;
			}
		}

		if (!is_null($this->SubJob)) {
			return $this->SubJob->FetchJob();
		}

		if (!$this->_CheckJobType($jobtype)) {
			return false;
		}

		$jobtype = strtolower($jobtype);
		$jobtype_file = SENDSTUDIO_API_DIRECTORY.'/jobs_' . $jobtype . '.php';
		if (!is_file($jobtype_file)) {
			return false;
		}

		require_once($jobtype_file);
		$jobtype = 'Jobs_' . ucwords($jobtype) . '_API';
		$subjob = &new $jobtype();
		$this->SubJob = &$subjob;
		$this->SubJobType = strtolower($jobtype);

		return $this->SubJob->FetchJob();
	}

	/**
	* ProcessJob
	* Processes the job based on the id passed in.
	* The default method returns false if there is no subjob set up.
	* The subclasses must override this method.
	*
	* @param Int $jobid The job to process.
	*
	* @see FetchJob
	*
	* @return Mixed If there is a subjob set up, it will return whatever that subjob returns. By default however it returns false.
	*/
	function ProcessJob($jobid=0)
	{
		if (!is_null($this->SubJob)) {
			return $this->SubJob->ProcessJob($jobid);
		}
		return false;
	}

	/**
	* GetNextJobType
	* Gets the next job type in the queue. This allows it to set up and run different jobs from the one script.
	*
	* @see FetchJob
	*
	* @return Mixed Returns false if there is no next job or the query can't be run, otherwise returns the jobtype.
	*/
	function GetNextJobType()
	{
		$query = "SELECT jobtype FROM " . SENDSTUDIO_TABLEPREFIX . "jobs WHERE jobstatus ='w' AND jobtime < " . $this->GetServerTime() . " ORDER BY jobtime ASC LIMIT 1";
		$result = $this->Db->Query($query);
		if (!$result) {
			return false;
		}

		$row = $this->Db->Fetch($result);
		if (empty($row)) {
			return false;
		}
		return strtolower($row['jobtype']);
	}

	/**
	* _CheckJobType
	* Checks the job type against the list of jobtypes we are allowed to run.
	*
	* @param String $jobtype The jobtype to check to make sure it's valid.
	*
	* @see JobTypes
	*
	* @return Boolean Returns false if the jobtype isn't listed in the JobTypes array. Returns true if you don't specify a jobtype or if it is in the array.
	*/
	function _CheckJobType($jobtype=null)
	{
		if (is_null($jobtype)) {
			return true;
		}

		if (in_array(strtolower($jobtype), $this->JobTypes)) {
			return true;
		}

		return false;
	}


	/**
	* GetJobList
	* Gets a list of jobs based on the criteria passed in. It can either return a number only or a list (array) of the job details.
	*
	* @param String $jobtype The type of job to get a list for. This can be send or export.
	* @param String $queuetype The type of queue to fetch a list for. This isn't used currently.
	* @param Array $ids A list of id's to get the job list for. Depending on the queuetype this might refer to listid's or something else. Currently only listid's are accepted.
	* @param Boolean $countonly Whether to return only a count or a full list of jobs.
	* @param Int $start Used for paging, this is where to start in the list of jobs.
	* @param Int $perpage The number of jobs to fetch. Used for paging.
	* @param Boolean $include_unapproved Whether to include unapproved jobs or not. If a user is a full administrator then they will include unapproved jobs in case someone goes over their sending limit and they want to allow the extra emails to go through.
	*
	*/
	function GetJobList($jobtype='send', $queuetype='newsletter', $ids=array(), $countonly=false, $start=0, $perpage=0, $include_unapproved=false)
	{
		$ids = $this->CheckIntVars($ids);
		if (empty($ids)) {
			$ids = array('0');
		}

		switch ($queuetype) {
			default:
				if ($countonly) {
					$query = "SELECT COUNT(j.jobid) AS count FROM " . SENDSTUDIO_TABLEPREFIX . "lists l, " . SENDSTUDIO_TABLEPREFIX . "jobs_lists jl, " . SENDSTUDIO_TABLEPREFIX . "jobs j LEFT OUTER JOIN " . SENDSTUDIO_TABLEPREFIX . "newsletters n ON n.newsletterid=j.fkid WHERE jl.jobid=j.jobid AND jl.listid IN (" . implode(',', $ids) . ") AND jl.listid=l.listid AND j.jobtype='" . $this->Db->Quote($jobtype) . "' AND j.fktype='" . $this->Db->Quote($queuetype) . "'";
					if (!$include_unapproved) {
						$query .= " AND j.approved > 0";
					}
					$result = $this->Db->Query($query);
					$count = $this->Db->FetchOne($result, 'count');
					return $count;
				}

				$query = "SELECT j.jobid, n.name AS name, l.name AS listname, jobstatus, jobtime, jobdetails, queueid, j.approved AS approved, CASE WHEN jobstatus='c' THEN 1 ELSE 0 END AS jobstatus_check FROM " . SENDSTUDIO_TABLEPREFIX . "lists l, " . SENDSTUDIO_TABLEPREFIX . "jobs_lists jl, " . SENDSTUDIO_TABLEPREFIX . "jobs j LEFT OUTER JOIN " . SENDSTUDIO_TABLEPREFIX . "newsletters n ON n.newsletterid=j.fkid WHERE jl.listid=l.listid AND jl.jobid=j.jobid AND jl.listid IN (" . implode(',', $ids) . ") AND j.jobtype='" . $this->Db->Quote($jobtype) . "' AND j.fktype='" . $this->Db->Quote($queuetype) . "'";

				if (!$include_unapproved) {
					$query .= " AND j.approved > 0";
				}

				$query .= " ORDER BY jobstatus_check ASC, jobtime ASC, l.name DESC";

				if ($start || $perpage) {
					$query .= $this->Db->AddLimit($start, $perpage);
				}

				$result = $this->Db->Query($query);
				$jobs = array();
				while ($row = $this->Db->Fetch($result)) {
					$row['name'] = $row['name'];
					$row['listname'] = $row['listname'];
					$jobs[] = $row;
				}
				$this->Db->FreeResult($result);
		}
		return $jobs;
	}

	/**
	* GetJobStatus
	* Returns 'human readable' job status language variable based on the status passed in.
	* If the status is invalid, then this returns false.
	* Otherwise, it returns the defined language variable.
	*
	* @param Char $status The status to get in human readable format.
	*
	* @return False|String Returns false if the status is invalid, otherwise a string with the real status description.
	*/
	function GetJobStatus($status='c')
	{
		$return = false;
		switch (strtolower($status)) {
			case 'c':
				$return = GetLang('Job_Complete');
			break;
			case 'i':
				$return = GetLang('Job_InProgress');
			break;
			case 'p':
				$return = GetLang('Job_Paused');
			break;
			case 'w':
				$return = GetLang('Job_Waiting');
			break;
		}
		return $return;
	}

	/**
	* UpdateTime
	* Updates the time for a job to run. This assumes it has already been converted to 'server' time rather than 'user' time.
	* If the job has already been started, this will return false.
	*
	* @param Int $jobid The job to update.
	* @param Int $newtime The new time to update the job to.
	*
	* @see Schedule::Process
	*
	* @return Boolean Returns false if the job has already been started, otherwise it returns the status from the update query.
	*/
	function UpdateTime($jobid=0, $newtime=0)
	{
		if ($this->JobStarted($jobid)) {
			return false;
		}

		$query = "UPDATE " . SENDSTUDIO_TABLEPREFIX . "jobs SET jobtime='" . $newtime . "' WHERE jobid='" . (int)$jobid . "'";
		$result = $this->Db->Query($query);
		return $result;
	}

	/**
	* NotifyOwner
	* This will notify the list owner(s) of job runs.
	* This will send the appropriate message depending on the state of the job, the message is passed in so this function doesn't need to work anything out.
	*
	* @param String $subject Subject of the email to send.
	* @param String $message The message to send the owner.
	*
	* @see Jobs_Send::ActionJob
	* @see Jobs_Bounce::ActionJob
	*
	* @return Void Doesn't return anything.
	*/
	function NotifyOwner($subject=false, $message=false)
	{
		$this->Email_API->ForgetEmail();

		$owner = &GetUser($this->jobowner);

		$this->Email_API->Set('Subject', $subject);
		if ($owner->fullname) {
			$this->Email_API->Set('FromName', $owner->fullname);
		} else {
			$this->Email_API->Set('FromName', GetLang('SendingSystem'));
		}

		if ($owner->emailaddress) {
			$this->Email_API->Set('FromAddress', $owner->emailaddress);
		} else {
			$this->Email_API->Set('FromAddress', GetLang('SendingSystem_From'));
		}

		$this->Email_API->Set('Multipart', false);

		$this->Email_API->AddBody('text', $message);

		$this->Email_API->ClearAttachments();
		$this->Email_API->ClearRecipients();

		$query = "SELECT listid, ownername, owneremail FROM " . SENDSTUDIO_TABLEPREFIX . "lists WHERE listid IN(" . implode(',', $this->jobdetails['Lists']) . ")";
		$result = $this->Db->Query($query);

		while ($row = $this->Db->Fetch($result)) {
			$this->Email_API->AddRecipient($row['owneremail'], $row['ownername'], 't');
		}

		$this->Email_API->Send();

		$this->Email_API->ForgetEmail();
	}

	/**
	* LoadStats
	* Loads up the statsid so we know where to record our information against.
	*
	* @param Int $jobid Job to load stats for.
	*
	* @return Int Returns the statsid from the stats_newsletters table for the appropriate job.
	*/
	function LoadStats($jobid=0)
	{
		$jobid = (int)$jobid;

		$query = "SELECT statid FROM " . SENDSTUDIO_TABLEPREFIX . "jobs j, " . SENDSTUDIO_TABLEPREFIX . "stats_newsletters n WHERE j.queueid=n.queueid AND j.jobid='" . (int)$jobid . "'";
		$result = $this->Db->Query($query);
		return $this->Db->FetchOne($result, 'statid');
	}

	function UnapproveJob($jobid=0)
	{
		$jobid = (int)$jobid;

		$query = "UPDATE " . SENDSTUDIO_TABLEPREFIX . "jobs SET approved=0 WHERE jobid='" . (int)$jobid . "'";
		$result = $this->Db->Query($query);
		if ($result) {
			return true;
		}
		return false;
	}

}

?>
