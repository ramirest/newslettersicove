<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class form_customfields_change_fieldid extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'form_customfields CHANGE FieldID fieldid varchar(10)';
		$result = $this->Db->Query($query);

		return $result;
	}
}
