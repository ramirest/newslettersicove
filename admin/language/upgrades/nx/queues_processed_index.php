<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class queues_processed_index extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'create index ' . SENDSTUDIO_TABLEPREFIX . 'queues_id_type_processed_idx on ' . SENDSTUDIO_TABLEPREFIX . 'queues(queueid,queuetype,processed)';
		$result = $this->Db->Query($query);
		return $result;
	}
}
