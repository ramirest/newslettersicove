<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class newsletters_change_format extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'newsletters change Format format char(1)';
		$result = $this->Db->Query($query);
		return $result;
	}
}
