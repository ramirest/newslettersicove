<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class list_subscribers_add_confirmdate extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'list_subscribers add column confirmdate int default null';
		$result = $this->Db->Query($query);
		return $result;
	}
}
