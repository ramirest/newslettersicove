<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class stats_autoresponders_create extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = "CREATE TABLE " . SENDSTUDIO_TABLEPREFIX . "stats_autoresponders (
		  statid int(11) NOT NULL default '0',
		  htmlrecipients int(11) default '0',
		  textrecipients int(11) default '0',
		  multipartrecipients int(11) default '0',
		  bouncecount_soft int(11) default '0',
		  bouncecount_hard int(11) default '0',
		  bouncecount_unknown int(11) default '0',
		  unsubscribecount int(11) default '0',
		  autoresponderid int(11) default '0',
		  linkclicks int(11) default '0',
		  emailopens int(11) default '0',
		  emailforwards int(11) default '0',
		  emailopens_unique int(11) default '0',
		  hiddenby int default 0
		)";
		$result = $this->Db->Query($query);

		return $result;
	}
}
