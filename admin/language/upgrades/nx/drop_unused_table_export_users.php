<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class drop_unused_table_export_users extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'drop table ' . SENDSTUDIO_TABLEPREFIX . 'export_users';
		$result = $this->Db->Query($query);

		return $result;
	}
}
