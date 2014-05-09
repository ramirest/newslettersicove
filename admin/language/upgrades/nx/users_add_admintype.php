<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class users_add_admintype extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'users add column admintype char(1)';
		$result = $this->Db->Query($query);
		return $result;
	}
}
