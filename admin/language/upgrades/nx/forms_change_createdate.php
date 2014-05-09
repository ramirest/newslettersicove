<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class forms_change_createdate extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'ALTER TABLE ' . SENDSTUDIO_TABLEPREFIX . 'forms CHANGE DateCreated createdate int';
		$result = $this->Db->Query($query);

		return $result;
	}
}
