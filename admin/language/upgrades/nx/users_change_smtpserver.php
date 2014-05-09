<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class users_change_smtpserver extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'users change EmailServer smtpserver varchar(255)';
		$result = $this->Db->Query($query);
		return $result;
	}
}
