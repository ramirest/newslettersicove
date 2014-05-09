<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class optimize_table_queues extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'optimize table ' . SENDSTUDIO_TABLEPREFIX . 'queues';
		$result = $this->Db->Query($query);
		return $result;
	}
}
