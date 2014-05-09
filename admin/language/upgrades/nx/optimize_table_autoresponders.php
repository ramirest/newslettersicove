<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class optimize_table_autoresponders extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'optimize table ' . SENDSTUDIO_TABLEPREFIX . 'autoresponders';
		$result = $this->Db->Query($query);
		return $result;
	}
}
