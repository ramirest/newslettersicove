<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class newsletters_change_createdate extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'newsletters change DateCreated createdate int';
		$result = $this->Db->Query($query);
		return $result;
	}
}
