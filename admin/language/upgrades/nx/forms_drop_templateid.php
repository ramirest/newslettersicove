<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class forms_drop_templateid extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'ALTER TABLE ' . SENDSTUDIO_TABLEPREFIX . 'forms DROP COLUMN TemplateID';
		$result = $this->Db->Query($query);

		return $result;
	}
}
