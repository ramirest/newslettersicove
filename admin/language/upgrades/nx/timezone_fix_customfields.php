<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class timezone_fix_customfields extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'update ' . SENDSTUDIO_TABLEPREFIX . 'customfields set createdate=createdate' . $this->offset_query;
		$result = $this->Db->Query($query);

		return $result;
	}
}
