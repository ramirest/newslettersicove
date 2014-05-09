<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class list_subscribers_change_subscribedate extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'list_subscribers change SubscribeDate subscribedate int';
		$result = $this->Db->Query($query);

		return $result;
	}
}
