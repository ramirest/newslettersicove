<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class autoresponders_drop_attachmentids extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'alter table ' . SENDSTUDIO_TABLEPREFIX . 'autoresponders drop column AttachmentIDs';
		$result = $this->Db->Query($query);
		return $result;
	}
}
