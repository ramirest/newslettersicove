<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class drop_unused_table_attachments extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'drop table ' . SENDSTUDIO_TABLEPREFIX . 'attachments';
		$result = $this->Db->Query($query);

		return $result;
	}
}
