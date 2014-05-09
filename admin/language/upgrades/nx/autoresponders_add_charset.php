<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class autoresponders_add_charset extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'autoresponders add column charset varchar(255)';
		$result = $this->Db->Query($query);

		return $result;
	}
}
