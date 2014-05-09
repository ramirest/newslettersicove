<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class list_subscriber_bounces_create extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'create table ' . SENDSTUDIO_TABLEPREFIX . 'list_subscriber_bounces (
		  bounceid int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
		  subscriberid int(11) default NULL,
		  statid int(11) default NULL,
		  listid int(11) default NULL,
		  bouncetime int(11) default NULL,
		  bouncetype varchar(255) default NULL,
		  bouncerule varchar(255) default NULL,
		  bouncemessage text
		)';
		$result = $this->Db->Query($query);

		return $result;
	}
}
