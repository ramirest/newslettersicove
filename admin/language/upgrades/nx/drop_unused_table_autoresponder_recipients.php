<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class drop_unused_table_autoresponder_recipients extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'drop table ' . SENDSTUDIO_TABLEPREFIX . 'autoresponder_recipients';
		$result = $this->Db->Query($query);

		return $result;
	}
}
