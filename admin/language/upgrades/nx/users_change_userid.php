<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class users_change_userid extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'users change AdminID userid int not null auto_increment';
		$result = $this->Db->Query($query);
		return $result;
	}
}
