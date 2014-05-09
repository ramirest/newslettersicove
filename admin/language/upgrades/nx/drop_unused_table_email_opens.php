<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class drop_unused_table_email_opens extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'drop table ' . SENDSTUDIO_TABLEPREFIX . 'email_opens';
		$result = $this->Db->Query($query);

		return $result;
	}
}
