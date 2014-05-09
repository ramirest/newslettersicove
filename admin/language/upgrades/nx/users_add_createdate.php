<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class users_add_createdate extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'users add column createdate int';
		$result = $this->Db->Query($query);
		return $result;
	}
}
