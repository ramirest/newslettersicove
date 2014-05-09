<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class newsletters_set_format_html extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'update ' . SENDSTUDIO_TABLEPREFIX . 'newsletters set format=\'h\' where format=\'2\'';
		$result = $this->Db->Query($query);
		return $result;
	}
}
