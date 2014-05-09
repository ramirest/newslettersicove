<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class users_update_defaults extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'update ' . SENDSTUDIO_TABLEPREFIX . 'users set infotips=1, usertimezone=\'GMT\', editownsettings=0, unlimitedmaxemails=1, createdate=unix_timestamp(now())';
		$result = $this->Db->Query($query);
		return $result;
	}
}
