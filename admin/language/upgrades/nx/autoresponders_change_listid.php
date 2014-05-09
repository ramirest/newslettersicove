<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class autoresponders_change_listid extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'autoresponders change ListID listid int';
		$result = $this->Db->Query($query);
		return $result;
	}
}
