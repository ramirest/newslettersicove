<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class customfield_lists_insert extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'INSERT INTO ' . SENDSTUDIO_TABLEPREFIX . 'customfield_lists (fieldid, listid) SELECT f.fieldid, l.listid FROM ' . SENDSTUDIO_TABLEPREFIX . 'list_fields f, ' . SENDSTUDIO_TABLEPREFIX . 'allow_lists l WHERE f.AdminID=l.AdminID';
		$result = $this->Db->Query($query);
		return $result;
	}
}
