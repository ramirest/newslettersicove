<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class lists_change_ownername extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'lists change WebmasterName ownername varchar(255)';
		$result = $this->Db->Query($query);
		return $result;
	}
}
