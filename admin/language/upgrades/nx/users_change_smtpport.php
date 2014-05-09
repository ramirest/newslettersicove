<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class users_change_smtpport extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'users change EmailServerPort smtpport int';
		$result = $this->Db->Query($query);
		return $result;
	}
}
