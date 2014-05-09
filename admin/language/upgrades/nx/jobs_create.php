<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class jobs_create extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'create table ' . SENDSTUDIO_TABLEPREFIX . 'jobs (
		  jobid int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
		  jobtype varchar(255) default NULL,
		  jobstatus char(1) default NULL,
		  jobtime int(11) default NULL,
		  jobdetails text,
		  fkid int(11) default 0,
		  lastupdatetime int(11) default 0,
		  fktype varchar(255) default NULL,
		  queueid int(11) default 0,
		  ownerid int(11) default NULL,
		  approved int default 0,
		  authorisedtosend int default 0
		)';
		$result = $this->Db->Query($query);
		return $result;
	}
}
