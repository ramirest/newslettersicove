<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class drop_unused_table_sends_perhour extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'drop table ' . SENDSTUDIO_TABLEPREFIX . 'sends_perhour';
		$result = $this->Db->Query($query);

		return $result;
	}
}
