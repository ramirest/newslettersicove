<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class form_customfields_change_fieldorder extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'form_customfields CHANGE FieldOrder fieldorder int';
		$result = $this->Db->Query($query);

		return $result;
	}
}
