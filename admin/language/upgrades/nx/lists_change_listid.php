<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class lists_change_listid extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'lists change ListID listid int not null auto_increment';
		$result = $this->Db->Query($query);
		return $result;
	}
}
