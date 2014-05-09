<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class users_change_emailaddress extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'users change Email emailaddress varchar(100)';
		$result = $this->Db->Query($query);
		return $result;
	}
}
