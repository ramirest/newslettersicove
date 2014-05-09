<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class list_subscribers_change_emailaddress extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'list_subscribers change Email emailaddress varchar(200)';
		$result = $this->Db->Query($query);

		return $result;
	}
}
