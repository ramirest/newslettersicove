<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class lists_add_imapaccount extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'lists add column imapaccount char(1) default 0';
		$result = $this->Db->Query($query);
		return $result;
	}
}
