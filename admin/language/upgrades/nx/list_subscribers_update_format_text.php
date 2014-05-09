<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class list_subscribers_update_format_text extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = "update " . SENDSTUDIO_TABLEPREFIX . "list_subscribers SET format='t' WHERE format ='1'";
		$result = $this->Db->Query($query);

		return $result;
	}
}
