<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class newsletters_change_newsletterid extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'newsletters change ComposedID newsletterid int not null auto_increment';
		$result = $this->Db->Query($query);
		return $result;
	}
}
