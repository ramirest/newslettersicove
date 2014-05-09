<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class newsletters_fix_content extends Upgrade_API
{
	function RunUpgrade()
	{
		$ok = true;
		$query = "SELECT * FROM " . SENDSTUDIO_TABLEPREFIX . "newsletters";
		$result = $this->Db->Query($query);
		while ($row = $this->Db->Fetch($result)) {
			$htmlbody = html_entity_decode(stripslashes($row['htmlbody']));
			$htmlbody = str_replace(SENSTUDIO_BASE_APPLICATION_URL.'/temp/images', SENDSTUDIO_TEMP_URL.'/user', $htmlbody);

			$update_query = "UPDATE " . SENDSTUDIO_TABLEPREFIX . "newsletters SET name='" . $this->Db->Quote(stripslashes($row['name'])) . "', subject='" . $this->Db->Quote(stripslashes($row['subject'])) . "', htmlbody='" . $this->Db->Quote($htmlbody) . "', textbody='" . $this->Db->Quote(stripslashes($row['textbody'])) . "' WHERE newsletterid='" . $row['newsletterid'] . "'";
			$update_result = $this->Db->Query($update_query);
			if ($ok) {
				$ok = $update_result;
			}
		}

		return $ok;
	}
}
