<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class user_stats_emailsperhour_create extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = "CREATE TABLE " . SENDSTUDIO_TABLEPREFIX . "user_stats_emailsperhour (
			summaryid INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
			statid INT DEFAULT '0',
			sendtime INT DEFAULT '0',
			emailssent INT DEFAULT '0',
			userid INT DEFAULT '0'
		)";
		$result = $this->Db->Query($query);
		return $result;
	}
}
