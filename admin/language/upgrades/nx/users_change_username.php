<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class users_change_username extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'users change Username username varchar(255)';
		$result = $this->Db->Query($query);
		return $result;
	}
}
