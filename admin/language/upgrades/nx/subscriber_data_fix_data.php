<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class subscriber_data_fix_data extends Upgrade_API
{
	function RunUpgrade()
	{
		// change them to the new 'format'
		$query = "SELECT subscriberid, d.fieldid, fieldtype, data FROM " . SENDSTUDIO_TABLEPREFIX . "subscribers_data d, " . SENDSTUDIO_TABLEPREFIX . "customfields c WHERE c.fieldid=d.fieldid AND c.fieldtype IN ('checkbox', 'multicheckbox', 'date')";
		$result = $this->Db->Query($query);
		while ($row = $this->Db->Fetch($result)) {
			switch ($row['fieldtype']) {
				case 'checkbox':
					if (strtolower($row['data']) == 'checked') {
						$row['data'] = '1';
					} else {
						$row['data'] = '0';
					}
					$options = array($row['data']);
					$new_value = serialize($options);
				break;

				case 'multicheckbox':
					$options = explode(':', $row['data']);
					$new_value = serialize($options);
				break;

				case 'date':
					$new_value = str_replace(':', '/', $row['data']);
				break;
			}

			$query = "UPDATE " . SENDSTUDIO_TABLEPREFIX . "subscribers_data SET data='" . $this->Db->Quote($new_value) . "' WHERE fieldid='" . $row['fieldid'] . "' AND subscriberid='" . $row['subscriberid'] . "'";
			$update_result = $this->Db->Query($query);
		}
		return true;
	}
}
