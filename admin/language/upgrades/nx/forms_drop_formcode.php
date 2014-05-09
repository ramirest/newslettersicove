<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class forms_drop_formcode extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'ALTER TABLE ' . SENDSTUDIO_TABLEPREFIX . 'forms DROP COLUMN FormCode';
		$result = $this->Db->Query($query);

		return $result;
	}
}
