<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class lists_add_unsubscribecount extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'lists add column unsubscribecount int';
		$result = $this->Db->Query($query);
		return $result;
	}
}
