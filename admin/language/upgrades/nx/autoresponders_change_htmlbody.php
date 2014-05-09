<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class autoresponders_change_htmlbody extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'autoresponders change HTMLBody htmlbody text';
		$result = $this->Db->Query($query);
		return $result;
	}
}
