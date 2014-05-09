<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class forms_add_changeformat extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'ALTER TABLE ' . SENDSTUDIO_TABLEPREFIX . 'forms ADD COLUMN changeformat char(1)';
		$result = $this->Db->Query($query);

		return $result;
	}
}
