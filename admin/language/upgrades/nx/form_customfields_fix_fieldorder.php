<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class form_customfields_fix_fieldorder extends Upgrade_API
{
	function RunUpgrade()
	{
		// add 'e'mail to the form_customfields table.
		$query = "insert into " . SENDSTUDIO_TABLEPREFIX . "form_customfields(formid, fieldid, fieldorder) select formid, 'e', 0 FROM " . SENDSTUDIO_TABLEPREFIX . "forms";
		$result = $this->Db->Query($query);

		// add 'cf' (choose format) to the form_customfields table.
		// we can't do this as a union query because mysql truncates the field names to be the smaller length, so this would become 'c' instead of 'cf'.
		$query = "insert into " . SENDSTUDIO_TABLEPREFIX . "form_customfields(formid, fieldid, fieldorder) select formid, 'cf', 0 FROM " . SENDSTUDIO_TABLEPREFIX . "forms";
		$result = $this->Db->Query($query);

		// add 'cl' (choose lists) to the customfields table where appropriate.
		// this query finds any forms that need it (they are associated with multiple lists)
		$query = "SELECT formid FROM " . SENDSTUDIO_TABLEPREFIX . "form_lists GROUP BY formid HAVING COUNT(formid) > 1";
		$result = $this->Db->Query($query);
		while ($row = $this->Db->Fetch($result)) {
			$insert_query = "insert into " . SENDSTUDIO_TABLEPREFIX . "form_customfields(formid, fieldid, fieldorder) values ('" . $row['formid'] . "', 'cl', 0)";
			$insert_result = $this->Db->Query($insert_query);
		}
		return true;
	}
}
