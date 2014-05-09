<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class autoresponders_change_sendfromname extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'autoresponders change SendName sendfromname varchar(255)';
		$result = $this->Db->Query($query);
		return $result;
	}
}
