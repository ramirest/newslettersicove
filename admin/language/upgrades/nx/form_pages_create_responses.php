<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class form_pages_create_responses extends Upgrade_API
{
	function RunUpgrade()
	{
		$pages = array();

		$query = "SELECT * FROM " . SENDSTUDIO_TABLEPREFIX . "form_responses fr, " . SENDSTUDIO_TABLEPREFIX . "forms f WHERE f.formid=fr.formid ORDER BY fr.FormID";
		$result = $this->Db->Query($query);
		while ($row = $this->Db->Fetch($result)) {
			$formid = $row['FormID'];

			if (!isset($pages[$formid])) {
				$pages[$formid] = array();
			}

			switch ($row['ResponseName']) {
				case 'ConfirmEmail':
					$pagetype = 'ConfirmPage';
					$pages[$formid]['ConfirmPage']['emailhtml'] = $row['ResponseData'];
					$pages[$formid]['ConfirmPage']['emailtext'] = strip_tags($row['ResponseData']);
				break;
				case 'ConfirmPage':
					$pagetype = 'ConfirmPage';
					$pages[$formid]['ConfirmPage']['html'] = $row['ResponseData'];
				break;
				case 'ConfirmSubject':
					$pagetype = 'ConfirmPage';
					$pages[$formid]['ConfirmPage']['emailsubject'] = $row['ResponseData'];
				break;
				case 'ConfirmURL':
					$pagetype = 'ConfirmPage';
					$pages[$formid]['ConfirmPage']['url'] = $row['ResponseData'];
				break;


				case 'ErrorPage':
					$pagetype = 'ErrorPage';
					$pages[$formid]['ErrorPage']['html'] = $row['ResponseData'];
				break;
				case 'ErrorURL':
					$pagetype = 'ErrorPage';
					$pages[$formid]['ErrorPage']['url'] = $row['ResponseData'];
				break;


				case 'ThanksEmail':
					$pagetype = 'ThanksPage';
					$pages[$formid]['ThanksPage']['emailhtml'] = $row['ResponseData'];
					$pages[$formid]['ThanksPage']['emailtext'] = strip_tags($row['ResponseData']);
				break;
				case 'ThanksPage':
					$pagetype = 'ThanksPage';
					$pages[$formid]['ThanksPage']['html'] = $row['ResponseData'];
				break;
				case 'ThanksSubject':
					$pagetype = 'ThanksPage';
					$pages[$formid]['ThanksPage']['emailsubject'] = $row['ResponseData'];
				break;
				case 'ThanksURL':
					$pagetype = 'ThanksPage';
					$pages[$formid]['ThanksPage']['url'] = $row['ResponseData'];
				break;
			}

			$pages[$formid][$pagetype]['sendfromname'] = $row['SendName'];
			$pages[$formid][$pagetype]['sendfromemail'] = $row['SendEmail'];
			$pages[$formid][$pagetype]['replytoemail'] = $row['SendEmail'];
			$pages[$formid][$pagetype]['bounceemail'] = $row['SendEmail'];
		}

		foreach ($pages as $formid => $details) {
			foreach ($details as $pagetype => $info) {
				foreach(array('html', 'url', 'sendfromname', 'sendfromemail', 'replytoemail', 'bounceemail', 'emailsubject', 'emailhtml', 'emailtext') as $k => $v) {
					$$v = '';
					if (isset($info[$v])) {
						$$v = $this->Db->Quote($info[$v]);
					}
				}

				$query = "INSERT INTO " . SENDSTUDIO_TABLEPREFIX . "form_pages(formid, pagetype, html, url, sendfromname, sendfromemail, replytoemail, bounceemail, emailsubject, emailhtml, emailtext) VALUES ('" . $formid . "', '" . $pagetype . "', '" . $html . "', '" . $url . "', '" . $sendfromname . "', '" . $sendfromemail . "', '" . $replytoemail . "', '" . $bounceemail . "', '" . $emailsubject . "', '" . $emailhtml . "', '" . $emailtext . "')";
				$result = $this->Db->Query($query);
			}
		}
		return true;
	}
}
