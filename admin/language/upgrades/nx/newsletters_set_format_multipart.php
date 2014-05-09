<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class newsletters_set_format_multipart extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'update ' . SENDSTUDIO_TABLEPREFIX . 'newsletters set format=\'b\' where format=\'3\'';
		$result = $this->Db->Query($query);
		return $result;
	}
}
