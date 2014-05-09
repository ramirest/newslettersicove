<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class newsletters_set_new_defaults extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'update ' . SENDSTUDIO_TABLEPREFIX . 'newsletters set active=1, archive=1';
		$result = $this->Db->Query($query);
		return $result;
	}
}
