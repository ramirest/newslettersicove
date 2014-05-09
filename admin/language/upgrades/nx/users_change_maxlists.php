<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class users_change_maxlists extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'users change MaxLists maxlists int';
		$result = $this->Db->Query($query);
		return $result;
	}
}
