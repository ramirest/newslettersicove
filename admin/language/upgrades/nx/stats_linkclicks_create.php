<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class stats_linkclicks_create extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = "CREATE TABLE " . SENDSTUDIO_TABLEPREFIX . "stats_linkclicks (
		  clickid int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
		  clicktime int(11) default NULL,
		  clickip varchar(20) default NULL,
		  subscriberid int(11) default NULL,
		  statid int(11) default NULL,
		  linkid int(11) default NULL
		)";
		$result = $this->Db->Query($query);
		return $result;
	}
}
