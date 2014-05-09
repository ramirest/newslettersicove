<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class users_change_lastloggedin extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'users change LoginTime lastloggedin int default 0';
		$result = $this->Db->Query($query);
		return $result;
	}
}
