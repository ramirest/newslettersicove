<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class banned_emails_change_listid extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'banned_emails change ListID list varchar(10)';
		$result = $this->Db->Query($query);
		return $result;
	}
}
