<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class stats_emailforwards_create extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = "CREATE TABLE " . SENDSTUDIO_TABLEPREFIX . "stats_emailforwards (
		  forwardid INT AUTO_INCREMENT PRIMARY KEY,
		  forwardtime INT,
		  forwardip VARCHAR(20),
		  subscriberid INT,
		  statid INT,
		  subscribed INT,
		  listid INT,
		  emailaddress VARCHAR(255)
		)";
		$result = $this->Db->Query($query);
		return $result;
	}
}
