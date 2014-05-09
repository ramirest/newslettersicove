<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class newsletters_drop_attachmentids extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'newsletters drop column AttachmentIDs';
		$result = $this->Db->Query($query);
		return $result;
	}
}
