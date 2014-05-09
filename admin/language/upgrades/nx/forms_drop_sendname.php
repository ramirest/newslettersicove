<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class forms_drop_sendname extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'ALTER TABLE ' . SENDSTUDIO_TABLEPREFIX . 'forms DROP COLUMN SendName';
		$result = $this->Db->Query($query);

		return $result;
	}
}
