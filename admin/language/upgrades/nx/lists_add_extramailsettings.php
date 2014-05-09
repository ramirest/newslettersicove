<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class lists_add_extramailsettings extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'lists add column extramailsettings varchar(100)';
		$result = $this->Db->Query($query);
		return $result;
	}
}
