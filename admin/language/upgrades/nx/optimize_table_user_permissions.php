<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class optimize_table_user_permissions extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'optimize table ' . SENDSTUDIO_TABLEPREFIX . 'user_permissions';
		$result = $this->Db->Query($query);
		return $result;
	}
}
