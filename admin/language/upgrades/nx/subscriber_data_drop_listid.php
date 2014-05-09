<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class subscriber_data_drop_listid extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'subscribers_data drop column ListID';
		$result = $this->Db->Query($query);
		return $result;
	}
}
