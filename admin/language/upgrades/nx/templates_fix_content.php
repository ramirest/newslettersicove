<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class templates_fix_content extends Upgrade_API
{
	function RunUpgrade()
	{
		$ok = true;

		$query = "SELECT * FROM " . SENDSTUDIO_TABLEPREFIX . "templates";
		$result = $this->Db->Query($query);
		while ($row = $this->Db->Fetch($result)) {
			$htmlbody = html_entity_decode(stripslashes($row['htmlbody']));
			$htmlbody = str_replace(SENSTUDIO_BASE_APPLICATION_URL.'/temp/images', SENDSTUDIO_TEMP_URL.'/user', $htmlbody);

			$update_query = "UPDATE " . SENDSTUDIO_TABLEPREFIX . "templates SET name='" . $this->Db->Quote(stripslashes($row['name'])) . "', htmlbody='" . $this->Db->Quote($htmlbody) . "', textbody='" . $this->Db->Quote(stripslashes($row['textbody'])) . "' WHERE templateid='" . $row['templateid'] . "'";
			$update_result = $this->Db->Query($update_query);
			if ($ok) {
				$ok = $update_result;
			}
		}

		return $ok;
	}
}
