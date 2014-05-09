<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class forms_add_usecaptcha extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'ALTER TABLE ' . SENDSTUDIO_TABLEPREFIX . 'forms ADD COLUMN usecaptcha char(1) DEFAULT 0';
		$result = $this->Db->Query($query);

		return $result;
	}
}
