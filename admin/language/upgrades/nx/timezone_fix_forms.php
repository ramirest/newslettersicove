<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class timezone_fix_forms extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'UPDATE ' . SENDSTUDIO_TABLEPREFIX . 'forms SET createdate=createdate' . $this->offset_query;
		$result = $this->Db->Query($query);

		return $result;
	}
}
