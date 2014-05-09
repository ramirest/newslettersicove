<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class users_add_unlimitedmaxemails extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'users add column unlimitedmaxemails char(1)';
		$result = $this->Db->Query($query);
		return $result;
	}
}
