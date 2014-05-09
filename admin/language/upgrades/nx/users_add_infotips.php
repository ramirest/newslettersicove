<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class users_add_infotips extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'users add column infotips char(1)';
		$result = $this->Db->Query($query);
		return $result;
	}
}
