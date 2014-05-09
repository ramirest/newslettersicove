<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class lists_add_bounceusername extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'lists add column bounceusername varchar(100)';
		$result = $this->Db->Query($query);
		return $result;
	}
}
