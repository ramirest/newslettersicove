<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class drop_unused_table_sends extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'drop table ' . SENDSTUDIO_TABLEPREFIX . 'sends';
		$result = $this->Db->Query($query);

		return $result;
	}
}
