<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class users_add_textfooter extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'users add column textfooter varchar(255)';
		$result = $this->Db->Query($query);
		return $result;
	}
}
