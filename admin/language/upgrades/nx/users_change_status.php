<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class users_change_status extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'users change Status status char(1)';
		$result = $this->Db->Query($query);
		return $result;
	}
}
