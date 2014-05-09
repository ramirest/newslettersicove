<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class form_customfields_rename extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'ALTER TABLE ' . SENDSTUDIO_TABLEPREFIX . 'form_fields RENAME TO ' . SENDSTUDIO_TABLEPREFIX . 'form_customfields';
		$result = $this->Db->Query($query);

		return $result;
	}
}
