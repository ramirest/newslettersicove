<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class list_subscribers_update_unsubscribecount extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'UPDATE ' . SENDSTUDIO_TABLEPREFIX . 'lists SET unsubscribecount=0';
		$update_result = $this->Db->Query($query);

		// update unsubscribe count.
		$query = 'SELECT listid, COUNT(subscriberid) AS count FROM ' . SENDSTUDIO_TABLEPREFIX . 'list_subscribers WHERE unsubscribed > 0 GROUP BY listid';
		$result = $this->Db->Query($query);
		while ($row = $this->Db->Fetch($result)) {
			$query = 'UPDATE ' . SENDSTUDIO_TABLEPREFIX . 'lists SET unsubscribecount=' . $row['count'] . ' WHERE listid=' . $row['listid'];
			$update_result = $this->Db->Query($query);
		}
		return true;
	}
}
