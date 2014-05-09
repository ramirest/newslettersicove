<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class forms_drop_status extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'ALTER TABLE ' . SENDSTUDIO_TABLEPREFIX . 'forms DROP COLUMN Status';
		$result = $this->Db->Query($query);

		return $result;
	}
}
