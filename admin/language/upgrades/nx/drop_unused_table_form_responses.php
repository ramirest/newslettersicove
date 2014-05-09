<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class drop_unused_table_form_responses extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'drop table ' . SENDSTUDIO_TABLEPREFIX . 'form_responses';
		$result = $this->Db->Query($query);

		return $result;
	}
}
