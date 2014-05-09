<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class users_add_listadmintype extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'users add column listadmintype char(1)';
		$result = $this->Db->Query($query);
		return $result;
	}
}
