<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class autoresponders_change_format extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'autoresponders change Format format char(1)';
		$result = $this->Db->Query($query);
		return $result;
	}
}
