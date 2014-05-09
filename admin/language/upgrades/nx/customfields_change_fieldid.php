<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class customfields_change_fieldid extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'customfields change FieldID fieldid int not null auto_increment';
		$result = $this->Db->Query($query);
		return $result;
	}
}
