<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class customfields_change_owner extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'customfields change AdminID ownerid int';
		$result = $this->Db->Query($query);
		return $result;
	}
}
