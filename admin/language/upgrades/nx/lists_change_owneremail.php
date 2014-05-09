<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class lists_change_owneremail extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'lists change WebmasterEmail owneremail varchar(100)';
		$result = $this->Db->Query($query);
		return $result;
	}
}
