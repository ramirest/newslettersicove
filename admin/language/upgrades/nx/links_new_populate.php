<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class links_new_populate extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = "INSERT INTO " . SENDSTUDIO_TABLEPREFIX . "links_new(url) SELECT DISTINCT (URL) FROM " . SENDSTUDIO_TABLEPREFIX . "links";
		$result = $this->Db->Query($query);
		return $result;
	}
}
