<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class forms_change_name extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'ALTER TABLE ' . SENDSTUDIO_TABLEPREFIX . 'forms CHANGE FormName name varchar(255)';
		$result = $this->Db->Query($query);

		return $result;
	}
}
