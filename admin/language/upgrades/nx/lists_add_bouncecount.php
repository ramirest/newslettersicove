<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class lists_add_bouncecount extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'lists add column bouncecount int';
		$result = $this->Db->Query($query);
		return $result;
	}
}
