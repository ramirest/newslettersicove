<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class user_permissions_change_list extends Upgrade_API
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
		* Expand 'manage lists' permissions into 'create', 'edit', 'delete', 'bounce'.
		*/
		$query = 'insert into ' . SENDSTUDIO_TABLEPREFIX . 'user_permissions (userid, area, subarea)';
		$query .= ' SELECT AdminID, \'lists\', \'create\' FROM ' . SENDSTUDIO_TABLEPREFIX . 'allow_functions WHERE SectionID=2';

		$result = $this->Db->Query($query);
		$query = 'insert into ' . SENDSTUDIO_TABLEPREFIX . 'user_permissions (userid, area, subarea)';
		$query .= ' SELECT AdminID, \'lists\', \'delete\' FROM ' . SENDSTUDIO_TABLEPREFIX . 'allow_functions WHERE SectionID=2';

		$result = $this->Db->Query($query);
		$query = 'insert into ' . SENDSTUDIO_TABLEPREFIX . 'user_permissions (userid, area, subarea)';
		$query .= ' SELECT AdminID, \'lists\', \'edit\' FROM ' . SENDSTUDIO_TABLEPREFIX . 'allow_functions WHERE SectionID=2';

		$result = $this->Db->Query($query);
		$query = 'insert into ' . SENDSTUDIO_TABLEPREFIX . 'user_permissions (userid, area, subarea)';
		$query .= ' SELECT AdminID, \'lists\', \'bounce\' FROM ' . SENDSTUDIO_TABLEPREFIX . 'allow_functions WHERE SectionID=2';
		$result = $this->Db->Query($query);

		return $result;
	}
}
