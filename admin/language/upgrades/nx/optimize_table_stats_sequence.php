<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class optimize_table_stats_sequence extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'optimize table ' . SENDSTUDIO_TABLEPREFIX . 'stats_sequence';
		$result = $this->Db->Query($query);
		return $result;
	}
}
