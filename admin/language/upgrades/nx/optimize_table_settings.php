<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class optimize_table_settings extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'optimize table ' . SENDSTUDIO_TABLEPREFIX . 'settings';
		$result = $this->Db->Query($query);
		return $result;
	}
}
