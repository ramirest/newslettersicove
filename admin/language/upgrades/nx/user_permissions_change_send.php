<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class user_permissions_change_send extends Upgrade_API
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
		* Change 'send' permissions.
		*/
		$query = 'insert into ' . SENDSTUDIO_TABLEPREFIX . 'user_permissions (userid, area, subarea)';
		$query .= ' SELECT AdminID, \'newsletters\', \'send\' FROM ' . SENDSTUDIO_TABLEPREFIX . 'allow_functions WHERE SectionID=12 OR SectionID=18';
		$result = $this->Db->Query($query);

		return $result;
	}
}
