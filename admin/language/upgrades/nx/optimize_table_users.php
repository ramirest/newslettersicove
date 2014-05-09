<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class optimize_table_users extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'optimize table ' . SENDSTUDIO_TABLEPREFIX . 'users';
		$result = $this->Db->Query($query);
		return $result;
	}
}
