<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class queues_sequence_create extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'create table ' . SENDSTUDIO_TABLEPREFIX . 'queues_sequence (
		  id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY
		)';
		$result = $this->Db->Query($query);
		return $result;
	}
}
