<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class timezone_fix_newsletter_stats extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = "UPDATE " . SENDSTUDIO_TABLEPREFIX . "stats_newsletters SET starttime=starttime" . $this->offset_query . ", finishtime=finishtime" . $this->offset_query;
		$result = $this->Db->Query($query);

		return $result;
	}
}
