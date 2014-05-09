<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class optimize_table_jobs extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'optimize table ' . SENDSTUDIO_TABLEPREFIX . 'jobs';
		$result = $this->Db->Query($query);
		return $result;
	}
}
