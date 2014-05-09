<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class list_subscribers_add_bounced extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'list_subscribers add column bounced int default 0';
		$result = $this->Db->Query($query);
		return $result;
	}
}
