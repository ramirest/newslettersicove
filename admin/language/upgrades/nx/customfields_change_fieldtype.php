<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class customfields_change_fieldtype extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'customfields change FieldType fieldtype varchar(100)';
		$result = $this->Db->Query($query);
		return $result;
	}
}
