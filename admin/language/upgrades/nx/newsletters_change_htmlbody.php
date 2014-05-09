<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class newsletters_change_htmlbody extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'newsletters change HTMLBody htmlbody text';
		$result = $this->Db->Query($query);
		return $result;
	}
}
