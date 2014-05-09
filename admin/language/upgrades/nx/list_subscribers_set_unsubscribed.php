<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class list_subscribers_set_unsubscribed extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'update ' . SENDSTUDIO_TABLEPREFIX . 'list_subscribers set unsubscribed=unix_timestamp(now()) where Status=0';
		$result = $this->Db->Query($query);
		return $result;
	}
}
