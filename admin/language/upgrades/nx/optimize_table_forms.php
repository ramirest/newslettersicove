<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class optimize_table_forms extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'optimize table ' . SENDSTUDIO_TABLEPREFIX . 'forms';
		$result = $this->Db->Query($query);
		return $result;
	}
}
