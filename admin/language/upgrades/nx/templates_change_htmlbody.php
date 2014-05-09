<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class templates_change_htmlbody extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'templates change HTMLContent htmlbody text';
		$result = $this->Db->Query($query);

		return $result;
	}
}
