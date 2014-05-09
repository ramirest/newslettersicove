<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class users_add_maxemails extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'users add column maxemails int default 0';
		$result = $this->Db->Query($query);
		return $result;
	}
}
