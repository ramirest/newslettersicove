<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class users_rename extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'admins rename to ' . SENDSTUDIO_TABLEPREFIX . 'users';
		$result = $this->Db->Query($query);
		return $result;
	}
}
