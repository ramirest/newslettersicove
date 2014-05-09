<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class timezone_fix_user_stats extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = "UPDATE " . SENDSTUDIO_TABLEPREFIX . "stats_users SET queuetime=queuetime" . $this->offset_query;
		$result = $this->Db->Query($query);

		return $result;
	}
}
