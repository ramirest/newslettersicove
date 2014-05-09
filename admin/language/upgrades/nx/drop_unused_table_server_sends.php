<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class drop_unused_table_server_sends extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'drop table ' . SENDSTUDIO_TABLEPREFIX . 'server_sends';
		$result = $this->Db->Query($query);

		return $result;
	}
}
