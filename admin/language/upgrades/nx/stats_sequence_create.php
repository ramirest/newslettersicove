<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class stats_sequence_create extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = "CREATE TABLE " . SENDSTUDIO_TABLEPREFIX . "stats_sequence (
		  id int(11) NOT NULL auto_increment,
		  PRIMARY KEY (id)
		)";
		$result = $this->Db->Query($query);
		return $result;
	}
}
