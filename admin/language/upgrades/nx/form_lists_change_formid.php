<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class form_lists_change_formid extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'ALTER TABLE ' . SENDSTUDIO_TABLEPREFIX . 'form_lists change FormID formid int';
		$result = $this->Db->Query($query);

		return $result;
	}
}
