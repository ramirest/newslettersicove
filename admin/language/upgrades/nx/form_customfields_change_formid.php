<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class form_customfields_change_formid extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'form_customfields CHANGE FormID formid int';
		$result = $this->Db->Query($query);

		return $result;
	}
}
