<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class users_change_name extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'users change AdminName fullname varchar(255)';
		$result = $this->Db->Query($query);
		return $result;
	}
}
