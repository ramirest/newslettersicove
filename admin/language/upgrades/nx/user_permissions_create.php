<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class user_permissions_create extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'create table ' . SENDSTUDIO_TABLEPREFIX . 'user_permissions (
		  userid int default \'0\',
		  area varchar(255),
		  subarea varchar(255)
		)';
		$result = $this->Db->Query($query);
		return $result;
	}
}
