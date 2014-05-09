<?php
/**
* This file handles b/c for forms.
*
* @version     $Id: form.php,v 1.2 2007/06/21 05:27:18 chris Exp $

*
* @package SendStudio
*/

/**
* Require base sendstudio functionality. This connects to the database, sets up our base paths and so on.
*/
require(dirname(__FILE__) . '/../admin/functions/init.php');

/**
* This file lets us get api's, load language files and parse templates.
*/
require(dirname(__FILE__) . '/../admin/functions/sendstudio_functions.php');

if (!isset($_GET['FormID'])) {
	echo 'Invalid Form.';
	exit;
}

$form = (int)$_GET['FormID'];
if ($form <= 0) {
	echo 'Invalid Form.';
	exit;
}

$lists = array();
if (isset($_POST['SelectLists'])) {
	$lists = array_keys($_POST['SelectLists']);
}

$multiple_lists = false;
if (sizeof($lists) > 1) {
	$multiple_lists = true;
}

$email = false;
if (isset($_POST['Email'])) {
	$email = $_POST['Email'];
}

$form_format = 'h';
if (isset($_POST['Format'])) {
	$form_format = (int)$_POST['Format'];
	if ($form_format == 2) {
		$form_format = 'h';
	} elseif ($form_format == 1) {
		$form_format = 't';
	}
} else {
	$form_format = str_replace('f', '', $formapi->Get('chooseformat'));
}

$sendstudio_functions = & new Sendstudio_Functions();
$sendstudio_functions->LoadLanguageFile('frontend');

$formapi = $sendstudio_functions->GetApi('Forms');
$listapi = $sendstudio_functions->GetApi('Lists');
$emailapi = $sendstudio_functions->GetApi('Email');
$emailapi->SetSmtp(SENDSTUDIO_SMTP_SERVER, SENDSTUDIO_SMTP_USERNAME, @base64_decode(SENDSTUDIO_SMTP_PASSWORD), SENDSTUDIO_SMTP_PORT);

$subscriberapi = $sendstudio_functions->GetApi('Subscribers');
$customfieldsapi = $sendstudio_functions->GetApi('CustomFields');
$statsapi = $sendstudio_functions->GetApi('Stats');

$loaded = $formapi->Load($form);
if (!$loaded) {
	echo 'Invalid Form.';
	exit();
}

$errors = array();

$formtype = $formapi->GetFormType($formapi->Get('formtype'));

if (!$email) {
	$errors[] = GetLang('Form_EmailEmpty_' . $formtype);
}

/**
* See if the user has an smtp server set.
*/
$user = &GetUser($formapi->ownerid);
if ($user->smtpserver) {
	$emailapi->SetSmtp($user->smtpserver, $user->smtpusername, $user->smtppassword, $user->smtpport);
}

/**
* This is used when a form handles multiple mailing lists.
* This remembers the lists we are already on so you don't get added again.
* It contains an array of listid => listname
* in case we are already subscribed to all lists.
*/
$already_subscribed_lists = array();

/**
* We keep both for easy references later on.
*/
$banned_lists = $banned_listids = array();

if ($email) {
	/**
	* See if our email address is valid.
	*/
	$validemail = $subscriberapi->ValidEmail($email);
	if (!$validemail) {
		$errors[] = sprintf(GetLang('InvalidEmailAddress'));
	} else {
		/**
		* If it's valid, make sure we're not a banned subscriber.
		*/
		$banned_lists = $subscriberapi->IsBannedSubscriber($email, $lists, true);

		if (!empty($banned_lists)) {
			if (in_array('globalban', $banned_lists)) {
				$banned = GetLang('AllLists');
			} else {
				if (sizeof(array_keys($banned_lists)) == 1) {
					$banned = sprintf(GetLang('SpecificList'), $banned_lists[0]['listname']);
				} else {
					$banned_listnames = array();
					foreach ($banned_lists as $p => $bandetails) {
						if (!$bandetails['listname']) {
							$bandetails['listname'] = GetLang('AllLists');
						}
						$banned_listnames[] = $bandetails['listname'];
					}
					$banned = sprintf(GetLang('SpecificLists'), implode('\',\'', $banned_listnames));
				}
			}
			$errors[] = sprintf(GetLang('YouAreABannedSubscriber'), $banned);
		}
	}
}

/**
* This stores the subscriber info ready for Subscribers_API::SaveSubscriberCustomField to save.
* It only keeps the fieldid and the value, it doesn't keep the fieldname.
*/
$subscriber = array();
$subscriber['CustomFields'] = array();

/**
* This is used to store extra custom field info that 'subscriber' doesn't store (both the value and the field name).
* This saves us re-loading the custom field info later on if we need to notify the list owner about the signup.
*/
$subscriberinfo = array();
$subscriberinfo['CustomFields'] = array();
$subscriberinfo['Lists'] = array();

/**
* This is used so we can remove any custom fields for lists that we aren't being subscribed to.
* If this is a contact form and we are already on a list, then the list is removed from the list of fields to update.
*/
$customfield_lists = array();

$customfields = $formapi->Get('customfields');

if ($customfields) {
	foreach ($customfields as $p => $fieldid) {
		if (!is_numeric($fieldid)) {
			continue;
		}

		$customfieldload = $customfieldsapi->Load($fieldid);

		if (!$customfieldload) {
			$errors[] = sprintf(GetLang('FormFail_InvalidField'), $fieldid);
			continue;
		}

		$customfields_api = $customfieldsapi->LoadSubField();

		// check customfield -> list associations.
		foreach ($customfields_api->Get('Associations') as $p => $listid) {
			if (!in_array($listid, array_keys($customfield_lists))) {
					$customfield_lists[$listid] = array();
			}
			$customfield_lists[$listid][] = $fieldid;
		}

		$postvalue = null;
		if (isset($_POST['Fields'][$fieldid])) {
			$postvalue = $_POST['Fields'][$fieldid];
		}

		$subscriber['CustomFields'][$fieldid] = $postvalue;

		$subscriberinfo['CustomFields'][$fieldid] = array('data' => $customfields_api->GetRealValue($postvalue), 'fieldname' => $customfields_api->GetFieldName(), 'defaultvalue' => $customfields_api->GetDefaultValue());

		$required = $customfields_api->IsRequired();

		if (is_null($postvalue) && !$required) {
			continue;
		}

		if ($customfields_api->ValidData($postvalue)) {
			continue;
		}

		$errors[] = sprintf(GetLang('FormFail_InvalidData_' . $formtype), $customfieldsapi->GetFieldName());
	}
}

/**
* We've checked the data, now lets check whether the subscriber is a duplicate or not.
*/
$listdetails = array();

if (empty($errors)) {
	foreach ($lists as $p => $listid) {
		$listid = (int)$listid;
		$check = $subscriberapi->IsSubscriberOnList($email, $listid, 0, true);

		$listload = $listapi->Load($listid);

		if (!$listload) {
			$errors[] = sprintf(GetLang('FormFail_InvalidList'), $listid);
			continue;
		}

		$listname = $listapi->Get('name');
		$listdetails[$listid] = $listapi;

		/**
		* If it's a contact form, make sure we're not banned from subscribing.
		* If we are, forget the '$listid' and keep going.
		*/
		if ($multiple_lists) {
			if (in_array($listid, $banned_listids) || in_array('g', $banned_listids)) {
				unset($customfield_lists[$listid]);
				if ($multiple_lists) {
					$already_subscribed_lists[$listid] = $listname;
				}
				continue;
			}
		}

		$subscriberinfo['Lists'][] = $listname;

		if ($check) {
			/**
			* If it's a contact form, or the form handles multiple lists
			* we remember that they are already subscribed to list '$listid' so later on we don't add them again.
			*/
			if ($multiple_lists) {
				unset($customfield_lists[$listid]);
				$already_subscribed_lists[$listid] = $listname;
				continue;
			}

			$errors[] = sprintf(GetLang('FormFail_AlreadySubscribedToList'), $listname);
			continue;
		}
	}
}

// see if we're subscribed to all lists.
if (sizeof($lists) == sizeof($already_subscribed_lists)) {
	foreach ($already_subscribed_lists as $listid => $listname) {
		$errors[] = sprintf(GetLang('FormFail_AlreadySubscribedToList'), $listname);
	}
}

/**
* We have errors? No point doing anything else. Print out the errors and stop.
*/
if (!empty($errors)) {
	DisplayErrorPage($formapi, $formtype, $errors);
	exit();
}

/**
* If there are no errors, let's do the rest of the work.
*/
$ipaddress = $subscriberapi->GetRealIp();
$subscriberapi->Set('requestip', $ipaddress);

if ($formapi->Get('requireconfirm') == true) {
	$subscriberapi->Set('confirmed', 0);
} else {
	$subscriberapi->Set('confirmed', 1);
}

/**
* Set this in case the person is filling in a contact form and they are already on all of the list(s).
*/
$subscriber_id = 0;
$subscriber['subscriberid'] = 0;

/**
* Set the formid so the confirmation process can check it and act accordingly.
*/
$subscriberapi->Set('formid', $form);

/**
* Go through each list and see which format they should be added as (whether it's their choice, or whether the list only allows one format).
*/
foreach ($lists as $p => $listid) {

	// if they are on the 'already subscribed' list, keep going and don't add them to this particular list.
	if (in_array($listid, array_keys($already_subscribed_lists))) {
		continue;
	}

	// check which formats the list supports.
	$subscriberformat = $listdetails[$listid]->Get('format');

	// if it's 'b' (both) then let the subscriber make the choice.
	if ($subscriberformat == 'b') {
		$subscriberformat = $form_format;
	}

	$subscriberapi->Set('format', $subscriberformat);

	// if they are not confirmed, we should remove them then re-add them (so they get a new confirm code and new confirmation email).
	$subscriberapi->DeleteSubscriber($email, $listid);
	$subscriber_id = $subscriberapi->AddToList($email, $listid, true, true);

	$subscriber['subscriberid'] = $subscriber_id;

	if (in_array($listid, array_keys($customfield_lists))) {
		$fields = $customfield_lists[$listid];
		foreach ($fields as $f => $fieldid) {
			$fieldvalue = $subscriber['CustomFields'][$fieldid];
			$subscriberapi->SaveSubscriberCustomField($subscriber_id, $fieldid, $fieldvalue);
		}
	}
}

$emailformat = 't';

$emailapi->Set('forcechecks', false);

$subscriberinfo['emailaddress'] = $email;
$subscriberinfo['confirmcode'] = $subscriberapi->Get('confirmcode');
$subscriberinfo['subscriberid'] = $subscriber['subscriberid'];

$subscriberinfo['listname'] = implode(',', $subscriberinfo['Lists']);

// if we need to confirm the new subscriber, do it here.
if ($formapi->Get('requireconfirm') == true) {

	$emailapi->Set('Subject', $formapi->GetPage('ConfirmPage', 'emailsubject'));
	$emailapi->Set('FromName', $formapi->GetPage('ConfirmPage', 'sendfromname'));
	$emailapi->Set('FromAddress', $formapi->GetPage('ConfirmPage', 'sendfromemail'));
	$emailapi->Set('ReplyTo', $formapi->GetPage('ConfirmPage', 'replytoemail'));
	$emailapi->Set('BounceAddress', $formapi->GetPage('ConfirmPage', 'bounceemail'));

	$emailapi->AddBody('text', $formapi->GetPage('ConfirmPage', 'emailtext'));
	$emailapi->AddBody('html', $formapi->GetPage('ConfirmPage', 'emailhtml'));

	$emailapi->AddCustomFieldInfo($email, $subscriberinfo);

	$emailapi->AddRecipient($email, false, $emailformat);
	$emailapi->Set('Multipart', true);
	$mail_results = $emailapi->Send(true);

	$confirmurl = $formapi->GetPage('ConfirmPage', 'url');
	if ($confirmurl) {
		header('Location: ' . $confirmurl);
	} else {
		$html = $formapi->GetPage('ConfirmPage', 'html');
		echo $formapi->CleanVersion($html, $subscriberinfo);
	}
	exit();
}

/**
* Do we need to send the list owner a notification? Let's check!
*/
$send_notification = false;

/**
* Clear out the email and recipients just in case.
*/
$emailapi->ClearRecipients();
$emailapi->ForgetEmail();
$emailapi->Set('forcechecks', false);

foreach ($lists as $p => $listid) {
	$notifyowner = $listdetails[$listid]->Get('notifyowner');
	if (!$notifyowner) {
		continue;
	}
	$send_notification = true;

	$listowneremail = $listdetails[$listid]->Get('owneremail');
	$listownername = $listdetails[$listid]->Get('ownername');
	$emailapi->AddRecipient($listowneremail, $listownername, 't', 0);
}

/**
* If we need to send an email notification, lets set up the email here and send it off.
*/
if ($send_notification) {
	$emailapi->Set('Subject', GetLang('SubscriberNotification_Subject'));
	$emailapi->Set('FromName', false);
	$emailapi->Set('FromAddress', $email);
	$emailapi->Set('ReplyTo', $email);
	$emailapi->Set('BounceAddress', false);

	$body = '';
	$body .= sprintf(GetLang('SubscriberNotification_Field'), GetLang('EmailAddress'), $email);

	$details_already_added = array();

	foreach ($subscriberinfo['CustomFields'] as $fieldid => $details) {
		if (in_array($fieldid, $details_already_added)) {
			continue;
		}
		$fieldvalue = $details['data'];
		$fieldname = $details['fieldname'];
		$body .= sprintf(GetLang('SubscriberNotification_Field'), $fieldname, $fieldvalue);
		$details_already_added[] = $fieldid;
	}

	if (!empty($subscriberinfo['Lists'])) {
		$lists = implode(',', $subscriberinfo['Lists']);
		$body .= sprintf(GetLang('SubscriberNotification_Lists'), $lists);
		$emailapi->Set('Subject', sprintf(GetLang('SubscriberNotification_Subject_Lists'), $lists));
	}

	$emailbody = sprintf(GetLang('SubscriberNotification_Body'), $body);

	$emailapi->AddBody('text', $emailbody);
	$emailapi->Send(false);
}

/**
* If we need to send a thanks email to the new subscriber, do it.
*/
if ($formapi->Get('sendthanks') == true) {
	$emailapi->ClearRecipients();
	$emailapi->ForgetEmail();
	$emailapi->Set('forcechecks', false);

	$emailapi->Set('Subject', $formapi->GetPage('ThanksPage', 'emailsubject'));
	$emailapi->Set('FromName', $formapi->GetPage('ThanksPage', 'sendfromname'));
	$emailapi->Set('FromAddress', $formapi->GetPage('ThanksPage', 'sendfromemail'));
	$emailapi->Set('ReplyTo', $formapi->GetPage('ThanksPage', 'replytoemail'));
	$emailapi->Set('BounceAddress', $formapi->GetPage('ThanksPage', 'bounceemail'));

	$emailapi->AddBody('text', $formapi->GetPage('ThanksPage', 'emailtext'));
	$emailapi->AddBody('html', $formapi->GetPage('ThanksPage', 'emailhtml'));

	$emailapi->AddCustomFieldInfo($email, $subscriberinfo);

	$emailapi->AddRecipient($email, false, $emailformat);
	$emailapi->Set('Multipart', true);
	$mail_results = $emailapi->Send(true);
}

/**
* See whether we need to check whether this subscriber is a referral or not so we can update statistics accordingly.
* If it's a contact form, we only want to check the new ones they are subscribing to.
*/
$subscribe_lists = array_keys($customfield_lists);
$statsapi->RecordForwardSubscribe($email, $subscriber_id, $subscribe_lists);

/**
* Finally, show the "Thanks" page to the subscriber.
*/
$thanksurl = $formapi->GetPage('ThanksPage', 'url');
if ($thanksurl) {
	header('Location: ' . $thanksurl);
} else {
	$html = $formapi->GetPage('ThanksPage', 'html');
	echo $formapi->CleanVersion($html, $subscriberinfo);
}


function DisplayErrorPage($formapi, $formtype, $errors)
{
	$pagetitle = GetLang('FormFail_PageTitle_' . $formtype);
	$errorlist = '<br/>-' . implode('<br/>-', $errors);
	$errorurl = $formapi->GetPage('ErrorPage', 'url');
	if ($errorurl) {
		header('Location: ' . $errorurl . '?Errors=' . urlencode($errorlist));
	} else {
		$errorpage = $formapi->GetPage('ErrorPage', 'html');
		echo str_replace(array('%%GLOBAL_ErrorTitle%%', '%%GLOBAL_Errors%%', '%ERRORLIST%'), array($pagetitle, $errorlist, $errorlist), $errorpage);
	}
}

?>
