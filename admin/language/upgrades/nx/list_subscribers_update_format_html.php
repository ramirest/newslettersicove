<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class list_subscribers_update_format_html extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = "update ". SENDSTUDIO_TABLEPREFIX . "list_subscribers SET format='h' WHERE format in (2,3)";
		$result = $this->Db->Query($query);

		return $result;
	}
}
