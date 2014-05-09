<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class timezone_fix_user_stats_perhour extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = "UPDATE " . SENDSTUDIO_TABLEPREFIX . "user_stats_emailsperhour SET sendtime=sendtime" . $this->offset_query;
		$result = $this->Db->Query($query);

		return $result;
	}
}
