<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class templates_add_active extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'templates add column active int default 1';
		$result = $this->Db->Query($query);

		return $result;
	}
}
