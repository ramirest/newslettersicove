<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class list_subscribers_add_to_unsubscribe extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'INSERT INTO ' . SENDSTUDIO_TABLEPREFIX . 'list_subscribers_unsubscribe (subscriberid, unsubscribetime, unsubscribeip, unsubscriberequesttime, unsubscriberequestip, listid, statid, unsubscribearea) SELECT subscriberid, unsubscribed, null, 0, null, listid, 0, \'n\' FROM ' . SENDSTUDIO_TABLEPREFIX . 'list_subscribers WHERE Status=0';
		$result = $this->Db->Query($query);
		return $result;
	}
}
