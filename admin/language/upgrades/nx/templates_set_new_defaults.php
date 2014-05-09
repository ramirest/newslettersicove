<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class templates_set_new_defaults extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'update ' . SENDSTUDIO_TABLEPREFIX . 'templates set active=1, isglobal=0';
		$result = $this->Db->Query($query);

		return $result;
	}
}
