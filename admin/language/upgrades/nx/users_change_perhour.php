<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class users_change_perhour extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'users change PerHour perhour int';
		$result = $this->Db->Query($query);
		return $result;
	}
}
