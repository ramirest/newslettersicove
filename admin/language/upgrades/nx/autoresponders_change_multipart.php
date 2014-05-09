<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class autoresponders_change_multipart extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'autoresponders change Multipart multipart char(1)';
		$result = $this->Db->Query($query);
		return $result;
	}
}
