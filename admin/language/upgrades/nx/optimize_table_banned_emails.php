<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class optimize_table_banned_emails extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'optimize table ' . SENDSTUDIO_TABLEPREFIX . 'banned_emails';
		$result = $this->Db->Query($query);
		return $result;
	}
}
