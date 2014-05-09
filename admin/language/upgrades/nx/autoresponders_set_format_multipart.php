<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class autoresponders_set_format_multipart extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = "update ". SENDSTUDIO_TABLEPREFIX . "autoresponders SET format='b' WHERE format='3'";
		$result = $this->Db->Query($query);

		return $result;
	}
}
