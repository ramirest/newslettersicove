<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class newsletters_set_format_text extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'update ' . SENDSTUDIO_TABLEPREFIX . 'newsletters set format=\'t\' where format=\'1\'';
		$result = $this->Db->Query($query);
		return $result;
	}
}
