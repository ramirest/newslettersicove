<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class newsletters_add_active extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'newsletters add column active int';
		$result = $this->Db->Query($query);
		return $result;
	}
}
