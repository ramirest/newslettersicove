<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class list_subscribers_change_listid extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'list_subscribers change ListID listid int default 0';
		$result = $this->Db->Query($query);

		return $result;
	}
}
