<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class newsletters_change_ownerid extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'newsletters change AdminID ownerid int';
		$result = $this->Db->Query($query);
		return $result;
	}
}
