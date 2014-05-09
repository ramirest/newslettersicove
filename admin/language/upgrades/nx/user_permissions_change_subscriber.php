<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class user_permissions_change_subscriber extends Upgrade_API
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
		* Expand 'manage subscriber' permissions into 'add', 'edit'.
		* Delete is handled separately.
		*/
		$query = 'insert into ' . SENDSTUDIO_TABLEPREFIX . 'user_permissions (userid, area, subarea)';
		$query .= ' SELECT AdminID, \'subscribers\', \'add\' FROM ' . SENDSTUDIO_TABLEPREFIX . 'allow_functions WHERE SectionID=1';
		$result = $this->Db->Query($query);

		$query = 'insert into ' . SENDSTUDIO_TABLEPREFIX . 'user_permissions (userid, area, subarea)';
		$query .= ' SELECT AdminID, \'subscribers\', \'edit\' FROM ' . SENDSTUDIO_TABLEPREFIX . 'allow_functions WHERE SectionID=1';
		$result = $this->Db->Query($query);

		/**
		* Convert 'removed subscriber' permissions
		*/
		$query = 'insert into ' . SENDSTUDIO_TABLEPREFIX . 'user_permissions (userid, area, subarea)';
		$query .= ' SELECT AdminID, \'subscribers\', \'delete\' FROM ' . SENDSTUDIO_TABLEPREFIX . 'allow_functions WHERE SectionID=19';
		$result = $this->Db->Query($query);

		/**
		* Convert 'import subscriber' permissions
		*/
		$query = 'insert into ' . SENDSTUDIO_TABLEPREFIX . 'user_permissions (userid, area, subarea)';
		$query .= ' SELECT AdminID, \'subscribers\', \'import\' FROM ' . SENDSTUDIO_TABLEPREFIX . 'allow_functions WHERE SectionID=4';
		$result = $this->Db->Query($query);

		/**
		* Convert 'export subscriber' permissions
		*/
		$query = 'insert into ' . SENDSTUDIO_TABLEPREFIX . 'user_permissions (userid, area, subarea)';
		$query .= ' SELECT AdminID, \'subscribers\', \'export\' FROM ' . SENDSTUDIO_TABLEPREFIX . 'allow_functions WHERE SectionID=5';
		$result = $this->Db->Query($query);

		/**
		* Convert 'banned subscriber' permissions
		*/
		$query = 'insert into ' . SENDSTUDIO_TABLEPREFIX . 'user_permissions (userid, area, subarea)';
		$query .= ' SELECT AdminID, \'subscribers\', \'banned\' FROM ' . SENDSTUDIO_TABLEPREFIX . 'allow_functions WHERE SectionID=6';
		$result = $this->Db->Query($query);
		return $result;
	}
}
