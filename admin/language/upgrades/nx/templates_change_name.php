<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class templates_change_name extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'templates change TemplateName name varchar(255)';
		$result = $this->Db->Query($query);

		return $result;
	}
}
