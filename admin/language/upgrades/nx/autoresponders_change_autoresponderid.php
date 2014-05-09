<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class autoresponders_change_autoresponderid extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'autoresponders change AutoresponderID autoresponderid int not null auto_increment';
		$result = $this->Db->Query($query);
		return $result;
	}
}
