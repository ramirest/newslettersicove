<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class list_subscribers_unsubscribe_create extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'create table ' . SENDSTUDIO_TABLEPREFIX . 'list_subscribers_unsubscribe (
		  subscriberid int(11) NOT NULL default 0,
		  unsubscribetime int(11) NOT NULL default 0,
		  unsubscribeip varchar(20) default NULL,
		  unsubscriberequesttime int(11) default 0,
		  unsubscriberequestip varchar(20) default NULL,
		  listid int(11) NOT NULL default 0,
		  statid int(11) default 0,
		  unsubscribearea varchar(20) default NULL
		)';
		$result = $this->Db->Query($query);

		return $result;
	}
}
