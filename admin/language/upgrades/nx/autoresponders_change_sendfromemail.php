<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class autoresponders_change_sendfromemail extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'autoresponders change SendFrom sendfromemail varchar(255)';
		$result = $this->Db->Query($query);
		return $result;
	}
}
