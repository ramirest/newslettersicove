<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class forms_change_ownerid extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'ALTER TABLE ' . SENDSTUDIO_TABLEPREFIX . 'forms CHANGE AdminID ownerid int';
		$result = $this->Db->Query($query);

		return $result;
	}
}
