<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class user_access_create extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'create table ' . SENDSTUDIO_TABLEPREFIX . 'user_access (
		  userid int(11) default 0,
		  area varchar(255) default NULL,
		  id int(11) default 0
		)';
		$result = $this->Db->Query($query);
		return $result;
	}
}
