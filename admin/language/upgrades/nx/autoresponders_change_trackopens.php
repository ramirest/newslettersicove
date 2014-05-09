<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class autoresponders_change_trackopens extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'autoresponders change TrackOpens trackopens char(1)';
		$result = $this->Db->Query($query);
		return $result;
	}
}
