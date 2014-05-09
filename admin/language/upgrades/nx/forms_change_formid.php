<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class forms_change_formid extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'ALTER TABLE ' . SENDSTUDIO_TABLEPREFIX . 'forms CHANGE FormID formid int not null auto_increment';
		$result = $this->Db->Query($query);

		return $result;
	}
}
