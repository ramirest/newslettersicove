<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class lists_set_format_multipart extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = "update ". SENDSTUDIO_TABLEPREFIX . "lists SET format='b' WHERE format='3'";
		$result = $this->Db->Query($query);
		return $result;
	}
}
