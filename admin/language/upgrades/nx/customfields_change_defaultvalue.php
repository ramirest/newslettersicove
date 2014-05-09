<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class customfields_change_defaultvalue extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'customfields change DefaultValue defaultvalue varchar(255)';
		$result = $this->Db->Query($query);
		return $result;
	}
}
