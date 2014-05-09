<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class optimize_table_subscribers_data extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'optimize table ' . SENDSTUDIO_TABLEPREFIX . 'subscribers_data';
		$result = $this->Db->Query($query);
		return $result;
	}
}
