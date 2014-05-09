<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class links_new_rename extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'links_new rename to ' . SENDSTUDIO_TABLEPREFIX . 'links';
		$result = $this->Db->Query($query);

		return $result;
	}
}
