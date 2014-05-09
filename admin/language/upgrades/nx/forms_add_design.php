<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class forms_add_design extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'ALTER TABLE ' . SENDSTUDIO_TABLEPREFIX . 'forms ADD COLUMN design varchar(255)';
		$result = $this->Db->Query($query);

		return $result;
	}
}
