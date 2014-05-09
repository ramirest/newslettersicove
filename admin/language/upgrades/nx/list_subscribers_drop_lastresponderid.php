<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class list_subscribers_drop_lastresponderid extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'list_subscribers drop column LastResponderID';
		$result = $this->Db->Query($query);

		return $result;
	}
}
