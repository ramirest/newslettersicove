<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class timezone_fix_open_stats extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = "UPDATE " . SENDSTUDIO_TABLEPREFIX . "stats_emailopens SET opentime=opentime" . $this->offset_query;
		$result = $this->Db->Query($query);

		return $result;
	}
}
