<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class lists_add_subscribecount extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'lists add column subscribecount int';
		$result = $this->Db->Query($query);
		return $result;
	}
}
