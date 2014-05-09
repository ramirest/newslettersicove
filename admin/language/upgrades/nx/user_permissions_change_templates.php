<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class user_permissions_change_templates extends Upgrade_API
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
		* Expand 'manage templates' permissions into 'create', 'edit', 'delete' and 'approve'.
		* A user does NOT get 'global' permissions in the upgrade process.
		*/
		$query = 'insert into ' . SENDSTUDIO_TABLEPREFIX . 'user_permissions (userid, area, subarea)';
		$query .= ' SELECT AdminID, \'templates\', \'create\' FROM ' . SENDSTUDIO_TABLEPREFIX . 'allow_functions WHERE SectionID=10';
		$result = $this->Db->Query($query);

		$query = 'insert into ' . SENDSTUDIO_TABLEPREFIX . 'user_permissions (userid, area, subarea)';
		$query .= ' SELECT AdminID, \'templates\', \'delete\' FROM ' . SENDSTUDIO_TABLEPREFIX . 'allow_functions WHERE SectionID=10';
		$result = $this->Db->Query($query);

		$query = 'insert into ' . SENDSTUDIO_TABLEPREFIX . 'user_permissions (userid, area, subarea)';
		$query .= ' SELECT AdminID, \'templates\', \'edit\' FROM ' . SENDSTUDIO_TABLEPREFIX . 'allow_functions WHERE SectionID=10';
		$result = $this->Db->Query($query);

		$query = 'insert into ' . SENDSTUDIO_TABLEPREFIX . 'user_permissions (userid, area, subarea)';
		$query .= ' SELECT AdminID, \'templates\', \'approve\' FROM ' . SENDSTUDIO_TABLEPREFIX . 'allow_functions WHERE SectionID=10';
		$result = $this->Db->Query($query);

		return $result;
	}
}
