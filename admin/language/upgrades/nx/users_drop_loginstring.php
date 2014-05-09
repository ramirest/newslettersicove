<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class users_drop_loginstring extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'users drop column LoginString';
		$result = $this->Db->Query($query);
		return $result;
	}
}
