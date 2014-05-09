<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class autoresponders_change_name extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'autoresponders change Name name varchar(255)';
		$result = $this->Db->Query($query);
		return $result;
	}
}
