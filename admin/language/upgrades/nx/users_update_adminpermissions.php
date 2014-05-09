<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class users_update_adminpermissions extends Upgrade_API
{
	function RunUpgrade()
	{
		/**
		* Change 'Manager', 'Root' into 'admintype', 'listadmintype' permissions.
		*/
		$query = 'SELECT userid, Manager, Root FROM ' . SENDSTUDIO_TABLEPREFIX . 'users';
		$result = $this->Db->Query($query);
		while ($row = $this->Db->Fetch($result)) {
			// set admintype to 'c'ustom by default.
			$admintype = $listadmintype = $templateadmintype = 'c';

			// if they are a manager and a root user, they are a full system administrator.
			if ($row['Manager'] == 1 && $row['Root'] == 1) {
				$admintype = 'a';
				$listadmintype = 'a';
				$templateadmintype = 'a';
			}

			// if they are a manager but not a root user, they are a list administrator.
			if ($row['Manager'] == 1 && $row['Root'] != 1) {
				$listadmintype = 'a';
			}

			$query = "UPDATE " . SENDSTUDIO_TABLEPREFIX . "users SET admintype='" . $admintype . "', listadmintype='" . $listadmintype . "', templateadmintype='" . $templateadmintype . "' WHERE userid='" . $row['userid'] . "'";

			$update_result = $this->Db->Query($query);
		}
		return true;
	}
}
