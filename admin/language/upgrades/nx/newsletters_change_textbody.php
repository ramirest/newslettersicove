<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class newsletters_change_textbody extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'newsletters change TextBody textbody text';
		$result = $this->Db->Query($query);
		return $result;
	}
}
