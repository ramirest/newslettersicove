<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class newsletters_rename extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'composed_emails rename to ' . SENDSTUDIO_TABLEPREFIX . 'newsletters';
		$result = $this->Db->Query($query);
		return $result;
	}
}
