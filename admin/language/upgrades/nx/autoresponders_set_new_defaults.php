<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class autoresponders_set_new_defaults extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = "update " . SENDSTUDIO_TABLEPREFIX . "autoresponders set active=1, queueid=0, charset='" . SENDSTUDIO_DEFAULTCHARSET . "', embedimages=1, to_firstname=0, to_lastname=0";
		$result = $this->Db->Query($query);

		$result = $this->Db->Query($query);
		return $result;
	}
}
