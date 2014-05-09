<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class optimize_table_list_subscriber_bounces extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'optimize table ' . SENDSTUDIO_TABLEPREFIX . 'list_subscriber_bounces';
		$result = $this->Db->Query($query);
		return $result;
	}
}
