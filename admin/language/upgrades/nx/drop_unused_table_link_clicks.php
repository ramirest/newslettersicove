<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class drop_unused_table_link_clicks extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'drop table ' . SENDSTUDIO_TABLEPREFIX . 'link_clicks';
		$result = $this->Db->Query($query);

		return $result;
	}
}
