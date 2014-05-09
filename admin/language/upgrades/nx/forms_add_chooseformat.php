<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class forms_add_chooseformat extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'ALTER TABLE ' . SENDSTUDIO_TABLEPREFIX . 'forms ADD COLUMN chooseformat char(2)';
		$result = $this->Db->Query($query);

		return $result;
	}
}
