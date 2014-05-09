<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class banned_emails_add_banid extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'banned_emails add column banid int not null auto_increment primary key';
		$result = $this->Db->Query($query);
		return $result;
	}
}
