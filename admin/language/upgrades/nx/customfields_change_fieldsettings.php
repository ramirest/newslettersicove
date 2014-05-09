<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class customfields_change_fieldsettings extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'customfields change AllValues fieldsettings text';
		$result = $this->Db->Query($query);
		return $result;
	}
}
