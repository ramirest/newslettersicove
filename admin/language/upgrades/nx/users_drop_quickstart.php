<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class users_drop_quickstart extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'users drop column KillQuickStart';
		$result = $this->Db->Query($query);
		return $result;
	}
}
