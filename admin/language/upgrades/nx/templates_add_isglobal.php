<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class templates_add_isglobal extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'templates add column isglobal int default 0';
		$result = $this->Db->Query($query);

		return $result;
	}
}
