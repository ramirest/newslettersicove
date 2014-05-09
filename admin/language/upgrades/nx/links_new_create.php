<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class links_new_create extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = "DROP TABLE IF EXISTS " . SENDSTUDIO_TABLEPREFIX . "links_new";
		$result = $this->Db->Query($query);

		$query = "CREATE TABLE " . SENDSTUDIO_TABLEPREFIX . "links_new (
		  linkid int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
		  url varchar(255) default NULL
		)";
		$result = $this->Db->Query($query);
		return $result;
	}
}
