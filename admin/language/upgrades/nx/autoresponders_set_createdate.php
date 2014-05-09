<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class autoresponders_set_createdate extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'update ' . SENDSTUDIO_TABLEPREFIX . 'autoresponders set createdate=unix_timestamp(now()) where createdate=0';
		$result = $this->Db->Query($query);
		return $result;
	}
}
