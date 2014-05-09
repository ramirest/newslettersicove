<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class banned_emails_change_dateadded extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'banned_emails change DateAdded bandate int';
		$result = $this->Db->Query($query);
		return $result;
	}
}
