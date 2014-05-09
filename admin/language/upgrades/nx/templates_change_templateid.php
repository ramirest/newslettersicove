<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class templates_change_templateid extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'templates change TemplateID templateid int not null auto_increment';
		$result = $this->Db->Query($query);

		return $result;
	}
}
