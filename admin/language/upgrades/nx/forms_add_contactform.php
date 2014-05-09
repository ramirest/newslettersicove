<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class forms_add_contactform extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'ALTER TABLE ' . SENDSTUDIO_TABLEPREFIX . 'forms ADD COLUMN contactform char(1) DEFAULT 0';
		$result = $this->Db->Query($query);

		return $result;
	}
}
