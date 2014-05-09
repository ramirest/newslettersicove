<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class settings_set_new_defaults extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'insert into ' . SENDSTUDIO_TABLEPREFIX . 'settings(cronok, cronrun1, cronrun2) VALUES (0, 0, 0)';
		$result = $this->Db->Query($query);

		return $result;
	}
}
