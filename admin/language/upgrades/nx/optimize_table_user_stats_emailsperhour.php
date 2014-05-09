<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class optimize_table_user_stats_emailsperhour extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'optimize table ' . SENDSTUDIO_TABLEPREFIX . 'user_stats_emailsperhour';
		$result = $this->Db->Query($query);
		return $result;
	}
}
