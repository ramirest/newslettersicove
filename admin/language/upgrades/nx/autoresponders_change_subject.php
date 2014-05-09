<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class autoresponders_change_subject extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'autoresponders change Subject subject varchar(255)';
		$result = $this->Db->Query($query);
		return $result;
	}
}
