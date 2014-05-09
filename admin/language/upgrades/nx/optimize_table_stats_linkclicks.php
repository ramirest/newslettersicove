<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class optimize_table_stats_linkclicks extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'optimize table ' . SENDSTUDIO_TABLEPREFIX . 'stats_linkclicks';
		$result = $this->Db->Query($query);
		return $result;
	}
}
