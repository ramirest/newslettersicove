<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class form_customfields_drop_adminid extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'form_customfields DROP COLUMN AdminID';
		$result = $this->Db->Query($query);

		return $result;
	}
}
