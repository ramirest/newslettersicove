<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class jobs_list_create extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'create table ' . SENDSTUDIO_TABLEPREFIX . 'jobs_lists (
		  jobid int(11) default NULL,
		  listid int(11) default NULL
		)';
		$result = $this->Db->Query($query);
		return $result;
	}
}
