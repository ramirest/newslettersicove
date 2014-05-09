<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class forms_set_new_defaults extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = "UPDATE " . SENDSTUDIO_TABLEPREFIX . "forms SET contactform=0, usecaptcha=0, changeformat=0, chooseformat='c', design='Classic White (Default)', FormType=substring(FormType, 1, 1)";
		$result = $this->Db->Query($query);

		return $result;
	}
}
