<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class banned_emails_change_email extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'banned_emails change Email emailaddress varchar(255)';
		$result = $this->Db->Query($query);
		return $result;
	}
}
