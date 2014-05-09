<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class list_subscribers_rename extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'members rename to ' . SENDSTUDIO_TABLEPREFIX . 'list_subscribers';
		$result = $this->Db->Query($query);

		return $result;
	}
}
