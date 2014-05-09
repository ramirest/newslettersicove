<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class users_add_htmlfooter extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'users add column htmlfooter varchar(255)';
		$result = $this->Db->Query($query);
		return $result;
	}
}
