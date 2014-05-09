<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class lists_set_format_text extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = "update " . SENDSTUDIO_TABLEPREFIX . "lists SET format='t' WHERE format='1'";
		$result = $this->Db->Query($query);
		return $result;
	}
}
