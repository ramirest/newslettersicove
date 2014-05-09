<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class customfield_lists_create extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'create table ' . SENDSTUDIO_TABLEPREFIX . 'customfield_lists (
		  cflid int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
		  fieldid int(11) NOT NULL default \'0\',
		  listid int(11) NOT NULL default \'0\'
		)';
		$result = $this->Db->Query($query);
		return $result;
	}
}
