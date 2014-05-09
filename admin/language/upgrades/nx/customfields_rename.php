<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class customfields_rename extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'list_fields rename to ' . SENDSTUDIO_TABLEPREFIX . 'customfields';
		$result = $this->Db->Query($query);
		return $result;
	}
}
