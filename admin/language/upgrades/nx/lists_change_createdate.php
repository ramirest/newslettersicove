<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class lists_change_createdate extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'lists change CreatedOn createdate int';
		$result = $this->Db->Query($query);
		return $result;
	}
}
