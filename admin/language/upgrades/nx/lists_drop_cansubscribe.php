<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class lists_drop_cansubscribe extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'lists drop column CanSubscribe';
		$result = $this->Db->Query($query);
		return $result;
	}
}
