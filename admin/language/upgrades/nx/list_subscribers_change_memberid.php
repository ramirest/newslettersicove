<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class list_subscribers_change_memberid extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'list_subscribers change MemberID subscriberid int not null auto_increment';
		$result = $this->Db->Query($query);

		return $result;
	}
}
