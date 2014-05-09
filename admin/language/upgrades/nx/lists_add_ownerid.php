<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class lists_add_ownerid extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'lists add column ownerid int';
		$result = $this->Db->Query($query);
		return $result;
	}
}
