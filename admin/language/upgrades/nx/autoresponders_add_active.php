<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class autoresponders_add_active extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'autoresponders add column active int default 0';
		$result = $this->Db->Query($query);
		return $result;
	}
}
