<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class stats_links_create extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = "CREATE TABLE " . SENDSTUDIO_TABLEPREFIX . "stats_links (
		  statid int(11) default 0,
		  linkid int(11) default 0
		)";
		$result = $this->Db->Query($query);
		return $result;
	}
}
