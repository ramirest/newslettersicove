<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class list_subscribers_change_format extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'list_subscribers change Format format char(1)';
		$result = $this->Db->Query($query);

		return $result;
	}
}
