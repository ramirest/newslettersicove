<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class users_add_editownsettings extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'users add column editownsettings char(1)';
		$result = $this->Db->Query($query);
		return $result;
	}
}
