<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class timezone_fix_link_stats extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = "UPDATE " . SENDSTUDIO_TABLEPREFIX . "stats_linkclicks SET clicktime=clicktime" . $this->offset_query;
		$result = $this->Db->Query($query);

		return $result;
	}
}
