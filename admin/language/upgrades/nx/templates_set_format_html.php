<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class templates_set_format_html extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = "update ". SENDSTUDIO_TABLEPREFIX . "templates SET format='h' WHERE format='2'";
		$result = $this->Db->Query($query);

		return $result;
	}
}
