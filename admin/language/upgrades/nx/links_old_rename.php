<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class links_old_rename extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'links rename to ' . SENDSTUDIO_TABLEPREFIX . 'old_links';
		$result = $this->Db->Query($query);

		return $result;
	}
}
