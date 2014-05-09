<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class optimize_table_stats_links extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'optimize table ' . SENDSTUDIO_TABLEPREFIX . 'stats_links';
		$result = $this->Db->Query($query);
		return $result;
	}
}
