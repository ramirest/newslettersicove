<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class form_pages_create extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'CREATE TABLE ' . SENDSTUDIO_TABLEPREFIX . 'form_pages (
			pageid int(11) NOT NULL auto_increment PRIMARY KEY,
			formid int(11) default NULL,
			pagetype varchar(100) default NULL,
			html text,
			url varchar(255) default NULL,
			sendfromname varchar(255) default NULL,
			sendfromemail varchar(255) default NULL,
			replytoemail varchar(255) default NULL,
			bounceemail varchar(255) default NULL,
			emailsubject varchar(255) default NULL,
			emailhtml text,
			emailtext text,
			KEY ss_form_pages_formid_idx (formid)
		)';
		$result = $this->Db->Query($query);

		return $result;
	}
}
