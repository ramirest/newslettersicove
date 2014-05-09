<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class user_permissions_change_statistics extends Upgrade_API
{
	function RunUpgrade()
	{
		/**
		** NOTE **
		* For all permissions, they are separate queries because a union will truncate the field length
		* to the smallest field.
		* So 'add' union 'edit' becomes 'add' and 'edi'.
		*/

		/**
		* Expand 'statistics' permissions into 'autoresponder', 'list', 'newsletter', 'user'.
		*/
		$query = 'insert into ' . SENDSTUDIO_TABLEPREFIX . 'user_permissions (userid, area, subarea)';
		$query .= ' SELECT AdminID, \'statistics\', \'autoresponder\' FROM ' . SENDSTUDIO_TABLEPREFIX . 'allow_functions WHERE SectionID=14';
		$result = $this->Db->Query($query);

		$query = 'insert into ' . SENDSTUDIO_TABLEPREFIX . 'user_permissions (userid, area, subarea)';
		$query .= ' SELECT AdminID, \'statistics\', \'list\' FROM ' . SENDSTUDIO_TABLEPREFIX . 'allow_functions WHERE SectionID=14';
		$result = $this->Db->Query($query);

		$query = 'insert into ' . SENDSTUDIO_TABLEPREFIX . 'user_permissions (userid, area, subarea)';
		$query .= ' SELECT AdminID, \'statistics\', \'newsletter\' FROM ' . SENDSTUDIO_TABLEPREFIX . 'allow_functions WHERE SectionID=14';
		$result = $this->Db->Query($query);

		$query = 'insert into ' . SENDSTUDIO_TABLEPREFIX . 'user_permissions (userid, area, subarea)';
		$query .= ' SELECT AdminID, \'statistics\', \'user\' FROM ' . SENDSTUDIO_TABLEPREFIX . 'allow_functions WHERE SectionID=14';
		$result = $this->Db->Query($query);

		return $result;
	}
}
