<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class users_add_settings extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'users add column settings text';
		$result = $this->Db->Query($query);
		return $result;
	}
}
