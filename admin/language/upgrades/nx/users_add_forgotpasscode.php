<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class users_add_forgotpasscode extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'users add column forgotpasscode varchar(32) default \'\'';
		$result = $this->Db->Query($query);
		return $result;
	}
}
