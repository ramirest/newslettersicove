<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class optimize_table_form_pages extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'optimize table ' . SENDSTUDIO_TABLEPREFIX . 'form_pages';
		$result = $this->Db->Query($query);
		return $result;
	}
}
