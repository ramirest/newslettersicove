<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class users_add_templateadmintype extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'users add column templateadmintype char(1)';
		$result = $this->Db->Query($query);
		return $result;
	}
}
