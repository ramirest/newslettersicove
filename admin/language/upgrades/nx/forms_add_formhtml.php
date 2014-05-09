<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class forms_add_formhtml extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'ALTER TABLE ' . SENDSTUDIO_TABLEPREFIX . 'forms ADD COLUMN formhtml text';
		$result = $this->Db->Query($query);

		return $result;
	}
}
