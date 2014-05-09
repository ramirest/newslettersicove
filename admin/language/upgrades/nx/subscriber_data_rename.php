<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class subscriber_data_rename extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'list_field_values rename to ' . SENDSTUDIO_TABLEPREFIX . 'subscribers_data';
		$result = $this->Db->Query($query);
		return $result;
	}
}
