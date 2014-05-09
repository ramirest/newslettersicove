<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class user_access_convert extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'INSERT INTO ' . SENDSTUDIO_TABLEPREFIX . 'user_access(userid, area, id) SELECT AdminID, \'lists\', ListID FROM ' . SENDSTUDIO_TABLEPREFIX . 'allow_lists';
		$result = $this->Db->Query($query);
		return $result;
	}
}
