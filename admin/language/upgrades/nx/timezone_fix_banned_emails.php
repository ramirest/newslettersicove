<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class timezone_fix_banned_emails extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'update ' . SENDSTUDIO_TABLEPREFIX . 'banned_emails set bandate=bandate' . $this->offset_query;
		$result = $this->Db->Query($query);

		return $result;
	}
}
