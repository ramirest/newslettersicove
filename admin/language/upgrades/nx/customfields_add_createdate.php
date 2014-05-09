<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class customfields_add_createdate extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'customfields add column createdate int';
		$result = $this->Db->Query($query);

		$query = 'update ' . SENDSTUDIO_TABLEPREFIX . 'customfields set createdate=unix_timestamp(now())';
		$result = $this->Db->Query($query);

		return $result;
	}
}
