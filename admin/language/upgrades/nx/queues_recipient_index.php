<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class queues_recipient_index extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'create index ' . SENDSTUDIO_TABLEPREFIX . 'queues_id_type_recip_idx on ' . SENDSTUDIO_TABLEPREFIX . 'queues(queueid,queuetype,recipient)';
		$result = $this->Db->Query($query);
		return $result;
	}
}
