<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class settings_create extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'create table ' . SENDSTUDIO_TABLEPREFIX . 'settings (
		  cronok char(1) default 0,
		  cronrun1 int(11) default 0,
		  cronrun2 int(11) default 0,
		  database_version int default 0
		)';
		$result = $this->Db->Query($query);

		if ($result) {
			$query = "UPDATE " . SENDSTUDIO_TABLEPREFIX . "settings SET database_version='" . SENDSTUDIO_DATABASE_VERSION . "'";
			$result = $this->Db->Query($query);
		}

		return $result;
	}
}
