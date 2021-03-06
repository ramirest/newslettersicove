<?php
/**
* This file handles processing of unsubscribe forms.
* It uses the appropriate api's to check subscribers, custom field values and lists.
*
* @see Forms_API
* @see Lists_API
* @see Subscribers_API
* @see CustomFields_API
* @see Email_API
*
* @version     $Id: unsubform.php,v 1.17 2007/06/19 04:44:12 chris Exp $

*
* @package SendStudio
*/

/**
* Require base sendstudio functionality. This connects to the database, sets up our base paths and so on.
*/
require(dirname(__FILE__) . '/admin/functions/init.php');

/**
* This file lets us get api's, load language files and parse templates.
*/
require(dirname(__FILE__) . '/admin/functions/sendstudio_functions.php');

/**
* Make sure we have a valid form id.
*/
$form = 0;
if (isset($_GET['form'])) {
	$form = (int)$_GET['form'];
}
if ($form <= 0) {
	echo 'Invalid Form.';
	exit();
}

$sendstudio_functions = & new Sendstudio_Functions();
$sendstudio_functions->LoadLanguageFile('frontend');

$formapi = $sendstudio_functions->GetApi('Forms');
$listapi = $sendstudio_functions->GetApi('Lists');
$emailapi = $sendstudio_functions->GetApi('Email');
$emailapi->SetSmtp(SENDSTUDIO_SMTP_SERVER, SENDSTUDIO_SMTP_USERNAME, @base64_decode(SENDSTUDIO_SMTP_PASSWORD), SENDSTUDIO_SMTP_PORT);

$subscriberapi = $sendstudio_functions->GetApi('Subscribers');
$customfieldsapi = $sendstudio_functions->GetApi('CustomFields');

$loaded = $formapi->Load($form);
if (!$loaded) {
	echo 'Invalid Form.';
	exit();
}

/**
* See if the user has an smtp server set.
*/
$user = &GetUser($formapi->ownerid);
if ($user->smtpserver) {
	$emailapi->SetSmtp($user->smtpserver, $user->smtpusername, $user->smtppassword, $user->smtpport);
}

$errors = array();

$formtype = $formapi->GetFormType($formapi->Get('formtype'));

$lists = array();

/**
* Check we're posting a proper form and have a list to unsubscribe from.
* If the 'lists' variable isn't in the posted form, check the form from sendstudio.
* If it only has one list associated with it, then that's what you are unsubscribing from.
* If it has multiple lists, then show an error message.
*/
if (!isset($_POST['lists'])) {
	$form_lists = $formapi->Get('lists');
	if (sizeof($form_lists) > 1) {
		$errors[] = GetLang('Form_NoLists_' . $formtype);
	} else {
		$lists = $form_lists;
	}
} else {
	$lists = $_POST['lists'];
	if (!is_array($lists)) {
		$lists = array($lists);
	}
}

/**
* Now make sure we're including an email address on our form.
*/
if (!isset($_POST['email']) || $_POST['email'] == '') {
	$errors[] = GetLang('Form_EmailEmpty_' . $formtype);
	$email = '';
} else {
	$email = $_POST['email'];
}

$subscriberinfo = array();

$subscriber_ids = array();

$not_on_list = array();

/**
* Go through and make sure we're actually on the list(s)..
*/
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

	if (!$check) {
		$not_on_list[] = $listname;
		continue;
	}

	$subscriber_ids[$listid] = $check;

	$subscriberlistinfo = $subscriberapi->LoadSubscriberList($check, $listid);
	$subscriberinfo[$listid] = $subscriberlistinfo;
}

// if we're not on any of the available lists, then show error messages appropriately.
if (sizeof($not_on_list) == sizeof($lists)) {
	foreach ($not_on_list as $p => $listname) {
		$errors[] = sprintf(GetLang('FormFail_NotOnList'), $listname);
	}
}

/**
* We have errors? No point doing anything else. Print out the errors and stop.
*/
if (!empty($errors)) {
	$pagetitle = GetLang('FormFail_PageTitle_' . $formtype);
	$errorlist = '<br/>-' . implode('<br/>-', $errors);
	$errorurl = $formapi->GetPage('ErrorPage', 'url');
	if ($errorurl) {
		header('Location: ' . $errorurl . '?Errors=' . urlencode($errorlist));
	} else {
		$errorpage = $formapi->GetPage('ErrorPage', 'html');
		echo str_replace(array('%%GLOBAL_ErrorTitle%%', '%%GLOBAL_Errors%%', '%ERRORLIST%'), array($pagetitle, $errorlist, $errorlist), $errorpage);
	}
	exit();
}

/**
* If there are no errors, let's do the rest of the work.
*/
$ipaddress = $subscriberapi->GetRealIp();
$subscriberapi->Set('unsubscriberequestip', $ipaddress);

/**
* If the form needs us to confirm our unsubscribe request, set it up appropriately.
*/
if ($formapi->Get('requireconfirm') == 'y' || $formapi->Get('requireconfirm') == '1') {
	$subscriberapi->Set('unsubscribeconfirmed', 0);
} else {
	$subscriberapi->Set('unsubscribeconfirmed', 1);
	$subscriberapi->Set('unsubscribeip', $ipaddress);
}

/**
* Mark the request per list in the database.
* This also handles if we don't need to confirm (ie it will mark them as unsubscribed in the db).
*/
foreach ($lists as $p => $listid) {
	// if we're only subscribed to one list of the options available,
	// this won't be set for all.
	if (!isset($subscriber_ids[$listid])) {
		$subscriberapi->Set('formid', 0);
		// make sure the form is set to 0 - so it's not picked up by the confirmation process.
		//$subscriberapi->SetForm($subscriber_ids[$listid]);
		continue;
	}
	/**
	* Set the formid so the confirmation process can check it and act accordingly.
	*/
	$subscriberapi->Set('formid', $form);
	$subscriberapi->UnsubscribeRequest($subscriber_ids[$listid], $listid);
	$subscriberapi->SetForm($subscriber_ids[$listid]);
}

$subscriber['CustomFields'] = array();

/**
* Put this into a 'subscriber' array so the email api can access it.
*/
foreach ($subscriberinfo as $p => $subinfo) {
	foreach ($subscriberinfo[$p]['CustomFields'] as $k => $details) {
		$fieldvalue = $details['data'];
		$fieldname = $details['fieldname'];
		$fieldid = $details['fieldid'];
		$subscriber['CustomFields'][$fieldid] = array('name' => $details['fieldname'], 'value' => $fieldvalue);
	}
	$confcode = $subscriberinfo[$p]['confirmcode'];
}

$subscriber['subscriberid'] = 0;
$subscriber['emailaddress'] = $email;

// save the confirmation code.
$subscriber['confirmcode'] = $confcode;

$emailformat = 't';

$emailapi->Set('forcechecks', false);

// if we need to confirm the subscriber's request, do it here.
if ($formapi->Get('requireconfirm') == 'y' || $formapi->Get('requireconfirm') == '1') {

	$emailapi->Set('Subject', $formapi->GetPage('ConfirmPage', 'emailsubject'));
	$emailapi->Set('FromName', $formapi->GetPage('ConfirmPage', 'sendfromname'));
	$emailapi->Set('FromAddress', $formapi->GetPage('ConfirmPage', 'sendfromemail'));
	$emailapi->Set('ReplyTo', $formapi->GetPage('ConfirmPage', 'replytoemail'));
	$emailapi->Set('BounceAddress', $formapi->GetPage('ConfirmPage', 'bounceemail'));

	$emailapi->AddBody('text', $formapi->GetPage('ConfirmPage', 'emailtext'));
	$emailapi->AddBody('html', $formapi->GetPage('ConfirmPage', 'emailhtml'));

	$emailapi->AddRecipient($email, false, $emailformat);

	reset($subscriberinfo);
	$subinfo = current($subscriberinfo);
	$emailapi->AddCustomFieldInfo($email, $subinfo);
	$emailapi->Set('Multipart', true);

	$mail_results = $emailapi->Send(true);

	$confirmurl = $formapi->GetPage('ConfirmPage', 'url');
	if ($confirmurl) {
		header('Location: ' . $confirmurl);
	} else {
		$html = $formapi->GetPage('ConfirmPage', 'html');
		echo $formapi->CleanVersion($html, $subinfo);
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
	$emailapi->Set('Subject', GetLang('UnsubscribeNotification_Subject'));
	$emailapi->Set('FromName', false);
	$emailapi->Set('FromAddress', $email);
	$emailapi->Set('ReplyTo', $email);
	$emailapi->Set('BounceAddress', false);

	$body = '';
	$body .= sprintf(GetLang('UnsubscribeNotification_Field'), GetLang('EmailAddress'), $email);

	foreach ($subscriber['CustomFields'] as $fieldid => $details) {
		$fieldvalue = $details['value'];
		$fieldname = $details['name'];
		$body .= sprintf(GetLang('UnsubscribeNotification_Field'), $fieldname, $fieldvalue);
	}
	$emailbody = sprintf(GetLang('UnsubscribeNotification_Body'), $body);

	$emailapi->AddBody('text', $emailbody);
	$emailapi->Send(false);
}

/**
* If we need to send a thanks (sorry?) email to the subscriber, do it here.
*/
if ($formapi->Get('sendthanks') == 1) {
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

	reset($subscriberinfo);
	$subinfo = current($subscriberinfo);
	$emailapi->AddCustomFieldInfo($email, $subinfo);

	$emailapi->AddRecipient($email, false, $emailformat);
	$emailapi->Set('Multipart', true);
	$mail_results = $emailapi->Send(true);
}

/**
* Finally, show the "Thanks/Sorry" page to the subscriber.
*/
$thanksurl = $formapi->GetPage('ThanksPage', 'url');
if ($thanksurl) {
	header('Location: ' . $thanksurl);
} else {
	reset($subscriberinfo);
	echo $formapi->CleanVersion($formapi->GetPage('ThanksPage', 'html'), current($subscriberinfo));
}
?>
