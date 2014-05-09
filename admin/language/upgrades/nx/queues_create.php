<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class queues_create extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'create table ' . SENDSTUDIO_TABLEPREFIX . 'queues (
		  queueid int(11) default 0,
		  queuetype varchar(255) default NULL,
		  ownerid int(11) default NULL,
		  recipient int(11) default NULL,
		  processed char(1) default 0,
		  sent char(1) default 0,
		  processtime datetime
		)';
		$result = $this->Db->Query($query);
		return $result;
	}
}
