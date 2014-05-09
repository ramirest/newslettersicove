<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class users_drop_attachments extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'users drop column Attachments';
		$result = $this->Db->Query($query);
		return $result;
	}
}
