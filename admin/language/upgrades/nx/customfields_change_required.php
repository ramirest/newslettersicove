<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class customfields_change_required extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'customfields change Required required char(1)';
		$result = $this->Db->Query($query);
		return $result;
	}
}
