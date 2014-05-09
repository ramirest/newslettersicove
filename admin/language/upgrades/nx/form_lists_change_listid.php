<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class form_lists_change_listid extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'ALTER TABLE ' . SENDSTUDIO_TABLEPREFIX . 'form_lists change ListID listid int';
		$result = $this->Db->Query($query);

		return $result;
	}
}
