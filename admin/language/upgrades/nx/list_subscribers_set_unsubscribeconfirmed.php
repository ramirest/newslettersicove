<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class list_subscribers_set_unsubscribeconfirmed extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'update ' . SENDSTUDIO_TABLEPREFIX . 'list_subscribers set unsubscribeconfirmed=1 where Status=0';
		$result = $this->Db->Query($query);
		return $result;
	}
}
