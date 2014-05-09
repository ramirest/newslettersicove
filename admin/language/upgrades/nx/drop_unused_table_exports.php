<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class drop_unused_table_exports extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'drop table ' . SENDSTUDIO_TABLEPREFIX . 'exports';
		$result = $this->Db->Query($query);

		return $result;
	}
}
