<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class lists_drop_status extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'lists drop column Status';
		$result = $this->Db->Query($query);
		return $result;
	}
}
