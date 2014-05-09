<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class autoresponders_add_queueid extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'autoresponders add column queueid int';
		$result = $this->Db->Query($query);
		return $result;
	}
}
