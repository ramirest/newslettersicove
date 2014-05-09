<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class list_subscribers_add_confirmip extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'list_subscribers add column confirmip varchar(20)';
		$result = $this->Db->Query($query);

		return $result;
	}
}
