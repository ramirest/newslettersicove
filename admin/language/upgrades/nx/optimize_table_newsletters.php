<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class optimize_table_newsletters extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'optimize table ' . SENDSTUDIO_TABLEPREFIX . 'newsletters';
		$result = $this->Db->Query($query);
		return $result;
	}
}
