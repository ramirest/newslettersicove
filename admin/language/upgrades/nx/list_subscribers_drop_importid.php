<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class list_subscribers_drop_importid extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'list_subscribers drop column ImportID';
		$result = $this->Db->Query($query);

		return $result;
	}
}
