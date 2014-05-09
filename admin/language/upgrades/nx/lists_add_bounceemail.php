<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class lists_add_bounceemail extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'lists add column bounceemail varchar(100)';
		$result = $this->Db->Query($query);
		return $result;
	}
}
