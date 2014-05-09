<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class forms_change_sendthanks extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'ALTER TABLE ' . SENDSTUDIO_TABLEPREFIX . 'forms CHANGE SendThankyou sendthanks char(1)';
		$result = $this->Db->Query($query);

		return $result;
	}
}
