<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class lists_change_notifyowner extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'lists change NotifyOwner notifyowner char(1)';
		$result = $this->Db->Query($query);
		return $result;
	}
}
