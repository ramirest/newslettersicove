<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class stats_sequence_insert extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = "INSERT INTO " . SENDSTUDIO_TABLEPREFIX . "stats_sequence(id) VALUES (1)";
		$result = $this->Db->Query($query);
		return $result;
	}
}
