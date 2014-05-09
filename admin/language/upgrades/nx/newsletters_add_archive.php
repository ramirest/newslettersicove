<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class newsletters_add_archive extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'newsletters add column archive int';
		$result = $this->Db->Query($query);
		return $result;
	}
}
