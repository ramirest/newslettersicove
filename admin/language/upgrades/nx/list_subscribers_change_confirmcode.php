<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class list_subscribers_change_confirmcode extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'list_subscribers change ConfirmCode confirmcode varchar(32)';
		$result = $this->Db->Query($query);

		return $result;
	}
}
