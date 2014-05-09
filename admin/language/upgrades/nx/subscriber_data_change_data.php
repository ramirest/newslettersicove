<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class subscriber_data_change_data extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'subscribers_data change Value data text';
		$result = $this->Db->Query($query);
		return $result;
	}
}
