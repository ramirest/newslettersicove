<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class subscriber_data_change_subscriberid extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'subscribers_data change UserID subscriberid int default 0';
		$result = $this->Db->Query($query);
		return $result;
	}
}
