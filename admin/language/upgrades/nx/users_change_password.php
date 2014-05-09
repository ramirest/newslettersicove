<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class users_change_password extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'users change Password password varchar(32)';
		$result = $this->Db->Query($query);
		return $result;
	}
}
