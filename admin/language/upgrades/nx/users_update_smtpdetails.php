<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class users_update_smtpdetails extends Upgrade_API
{
	function RunUpgrade()
	{
		/**
		* Expand the old smtpserver which contained:
		* servername; username; password
		* into the separate fields.
		*/
		$query = 'SELECT userid, smtpserver FROM ' . SENDSTUDIO_TABLEPREFIX . 'users';
		$result = $this->Db->Query($query);
		while ($row = $this->Db->Fetch($result)) {
			if (strpos($row['smtpserver'], ';') !== false) {
				list($servername, $username, $password) = explode(';', str_replace(' ', '', $row['smtpserver']));

				$query = "UPDATE " . SENDSTUDIO_TABLEPREFIX . "users SET smtpserver='" . $this->Db->Quote($servername) . "', smtpusername='" . $this->Db->Quote($username) . "', smtppassword='" . $this->Db->Quote(base64_encode($password)) . "' WHERE userid='" . $row['userid'] . "'";

				$update_result = $this->Db->Query($query);
			}
		}
		return true;
	}
}
