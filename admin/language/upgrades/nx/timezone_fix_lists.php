<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class timezone_fix_lists extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'update ' . SENDSTUDIO_TABLEPREFIX . 'lists set createdate=createdate' . $this->offset_query;
		$result = $this->Db->Query($query);

		return $result;
	}
}
