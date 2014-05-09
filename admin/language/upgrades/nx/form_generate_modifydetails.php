<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class form_generate_modifydetails extends Upgrade_API
{
	function RunUpgrade()
	{
		require(SENDSTUDIO_API_DIRECTORY . '/forms.php');
		$api = &new Forms_API(0, false);

		$api->Set('Db', $this->Db);

		$query = "SELECT formid FROM " . SENDSTUDIO_TABLEPREFIX . "forms WHERE formtype='m'";
		$result = $this->Db->Query($query);
		while ($row = $this->Db->Fetch($result)) {
			$api->Load($row['formid']);
			$html = $api->GetHTML();
			$query = "UPDATE " . SENDSTUDIO_TABLEPREFIX . "forms SET formhtml='" . $this->Db->Quote($html) . "' WHERE formid='" . $row['formid'] . "'";
			$update_result = $this->Db->Query($query);
		}
		return true;
	}
}
