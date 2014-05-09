<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class autoresponders_add_embedimages extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'autoresponders add column embedimages char(1) default 0';
		$result = $this->Db->Query($query);

		return $result;
	}
}
