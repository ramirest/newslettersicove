<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class queues_sequence_insert extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'insert into ' . SENDSTUDIO_TABLEPREFIX . 'queues_sequence(id) VALUES (1)';
		$result = $this->Db->Query($query);
		return $result;
	}
}
