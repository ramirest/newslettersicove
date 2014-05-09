<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class users_drop_root extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'users drop column Root';
		$result = $this->Db->Query($query);
		return $result;
	}
}
