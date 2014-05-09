<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class list_subscribers_update_subscribecount extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'UPDATE ' . SENDSTUDIO_TABLEPREFIX . 'lists SET subscribecount=0';
		$update_result = $this->Db->Query($query);

		// update subscribe count.
		$query = 'SELECT listid, COUNT(subscriberid) AS count FROM ' . SENDSTUDIO_TABLEPREFIX . 'list_subscribers WHERE unsubscribed=0 AND bounced=0 GROUP BY listid';
		$result = $this->Db->Query($query);
		$found = false;
		while ($row = $this->Db->Fetch($result)) {
			$found = true;
			$query = 'UPDATE ' . SENDSTUDIO_TABLEPREFIX . 'lists SET subscribecount=' . $row['count'] . ' WHERE listid=' . $row['listid'];
			$update_result = $this->Db->Query($query);
		}
		return true;
	}
}
