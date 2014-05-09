<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class stats_newsletter_lists_create extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = "CREATE TABLE " . SENDSTUDIO_TABLEPREFIX . "stats_newsletter_lists (
		  statid int(11) default NULL,
		  listid int(11) default NULL
		)";
		$result = $this->Db->Query($query);
		return $result;
	}
}
