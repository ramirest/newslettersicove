<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class timezone_fix_autoresponders extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'UPDATE ' . SENDSTUDIO_TABLEPREFIX . 'autoresponders SET createdate=createdate' . $this->offset_query;
		$result = $this->Db->Query($query);

		return $result;
	}
}
