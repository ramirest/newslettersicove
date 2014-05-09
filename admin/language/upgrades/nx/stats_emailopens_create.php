<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class stats_emailopens_create extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = "CREATE TABLE " . SENDSTUDIO_TABLEPREFIX . "stats_emailopens (
		  openid int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
		  subscriberid int(11) default NULL,
		  statid int(11) default NULL,
		  opentime int(11) default NULL,
		  openip varchar(20) default NULL
		)";
		$result = $this->Db->Query($query);
		return $result;
	}
}
