<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class stats_users_create extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = "CREATE TABLE " . SENDSTUDIO_TABLEPREFIX . "stats_users (
		  userid int(11) default NULL,
		  statid int(11) default NULL,
		  jobid int(11) default NULL,
		  queuesize int(11) default NULL,
		  queuetime int(11) default NULL
		)";
		$result = $this->Db->Query($query);
		return $result;
	}
}
