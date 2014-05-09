<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class lists_set_new_defaults extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'update ' . SENDSTUDIO_TABLEPREFIX . 'lists set imapaccount=0, bounceemail=owneremail, replytoemail=owneremail';
		$result = $this->Db->Query($query);
		return $result;
	}
}
