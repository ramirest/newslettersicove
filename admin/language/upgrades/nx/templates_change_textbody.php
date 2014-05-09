<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class templates_change_textbody extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'templates change TextContent textbody text';
		$result = $this->Db->Query($query);

		return $result;
	}
}
