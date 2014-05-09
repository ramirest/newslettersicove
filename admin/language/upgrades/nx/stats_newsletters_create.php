<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class stats_newsletters_create extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = "CREATE TABLE " . SENDSTUDIO_TABLEPREFIX . "stats_newsletters (
		  statid int(11) NOT NULL default '0',
		  queueid int(11) default NULL,
		  starttime int(11) default NULL,
		  finishtime int(11) default NULL,
		  htmlrecipients int(11) default '0',
		  textrecipients int(11) default '0',
		  multipartrecipients int(11) default '0',
		  trackopens char(1) default '0',
		  tracklinks char(1) default '0',
		  bouncecount_soft int(11) default '0',
		  bouncecount_hard int(11) default '0',
		  bouncecount_unknown int(11) default '0',
		  unsubscribecount int(11) default '0',
		  newsletterid int(11) default NULL,
		  sendfromname varchar(200) default NULL,
		  sendfromemail varchar(200) default NULL,
		  bounceemail varchar(200) default NULL,
		  replytoemail varchar(200) default NULL,
		  charset varchar(200) default NULL,
		  sendinformation text,
		  sendsize int(11) default NULL,
		  sentby int(11) default NULL,
		  notifyowner char(1) default NULL,
		  linkclicks int(11) default '0',
		  emailopens int(11) default '0',
		  emailforwards int(11) default '0',
		  emailopens_unique int(11) default '0',
		  hiddenby int default 0,
		  PRIMARY KEY (statid)
		)";
		$result = $this->Db->Query($query);
		return $result;
	}
}
