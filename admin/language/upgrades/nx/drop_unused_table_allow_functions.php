<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class drop_unused_table_allow_functions extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'drop table ' . SENDSTUDIO_TABLEPREFIX . 'allow_functions';
		$result = $this->Db->Query($query);

		return $result;
	}
}
