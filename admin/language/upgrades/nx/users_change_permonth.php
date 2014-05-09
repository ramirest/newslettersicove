<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class users_change_permonth extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'users change PerMonth permonth int';
		$result = $this->Db->Query($query);
		return $result;
	}
}
