<?php
/**
* This file handles confirming subscribers.
* It uses the appropriate api's to check lists and the subscriber.
*
* @see Lists_API
* @see Subscribers_API
* @see Email_API
*
* @version     $Id: confirm.php,v 1.21 2007/06/05 07:03:39 chris Exp $

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

$sendstudio_functions = & new Sendstudio_Functions();
$sendstudio_functions->LoadLanguageFile('frontend');

$listapi = $sendstudio_functions->GetApi('Lists');
$emailapi = $sendstudio_functions->GetApi('Email');
$emailapi->SetSmtp(SENDSTUDIO_SMTP_SERVER, SENDSTUDIO_SMTP_USERNAME, @base64_decode(SENDSTUDIO_SMTP_PASSWORD), SENDSTUDIO_SMTP_PORT);

$subscriberapi = $sendstudio_functions->GetApi('Subscribers');
$customfieldsapi = $sendstudio_functions->GetApi('CustomFields');
$formapi = $sendstudio_functions->GetApi('Forms');
$statsapi = $sendstudio_functions->GetApi('Stats');

if (!isset($_GET['E'])) {
	echo 'No email address.';
	exit();
}

$email = $_GET['E'];

if (!isset($_GET['C'])) {
	echo 'No confirmation code.';
	exit();
}

$confirmcode = $_GET['C'];

/**
* If we're just confirming for a particular list, then let's remember it.
*/
if (isset($_GET['L'])) {
	$list = (int)$_GET['L'];
	$lists = array($list);
}

/**
* This saves us re-loading the custom field info later on if we need to notify the list owner about the signup.
*/
$subscriberinfo = array();

$ipaddress = $subscriberapi->GetRealIp();
$confirmdate = $subscriberapi->GetServerTime();

$thankspage = false;
$sendthanks = false;
$errorpage = false;

$errors = array();

$subscriberids = $subscriberapi->GetSubscriberIdsToConfirm($email, $confirmcode);
if (empty($subscriberids)) {
	$lists = array();
	$subscriber_id = 0;
	$errors[] = GetLang('InvalidConfirmURL');
} else {
	$subscriber_id = $subscriberids[0];
}

$form = $subscriberapi->GetForm($subscriber_id);

$formtype = 's';

/**
* If we're confirming from a form, load up the details.
*/
if ($form) {
	$loaded = $formapi->Load($form);
	if (!$loaded) {
		echo 'Form doesn\'t exist anymore.<br/>';
		exit();
	}
	$form_lists = $formapi->Get('lists');
	$errorpage = $formapi->GetPage('ErrorPage');
	$thankspage = $formapi->GetPage('ThanksPage');

	if ($formapi->Get('sendthanks') == 1) {
		$sendthanks = true;
	}
	$formtype = $formapi->Get('formtype');
	$subscriber_lists = $subscriberapi->GetListsByForm($email, $form);

	// now, since we know which lists are on the form and which lists the subscriber has set (based on the form), intersect them to make sure there are some in common.
	$lists = array_intersect($subscriber_lists, $form_lists);

	/**
	* See if the user has an smtp server set.
	*/
	$user = &GetUser($formapi->ownerid);
	if ($user->smtpserver) {
		$emailapi->SetSmtp($user->smtpserver, $user->smtpusername, $user->smtppassword, $user->smtpport);
	}
}

$has_confirmed = false;

$listdetails = array();

foreach ($lists as $p => $listid) {
	$listload = $listapi->Load($listid);
	if (!$listload) {
		continue;
	}

	$listdetails[$listid] = $listapi;

	$subscriber_id = $subscriberapi->IsSubscriberOnList($email, $listid);

	if (!$subscriber_id) {
		unset($lists[$p]);
		continue;
	}

	$subscriberlistinfo = $subscriberapi->LoadSubscriberForm($subscriber_id, $listid);

	$subscriberlistinfo['listid'] = $listid;

	$subscriberinfo[$listid] = $subscriberlistinfo;

	$listname = $listapi->Get('name');

	if ($subscriberlistinfo['confirmcode'] == $confirmcode) {
		$has_confirmed = true;
		if ($formtype == 's') {
			$subscriberapi->Set('confirmip', $ipaddress);
			$subscriberapi->Set('confirmdate', $confirmdate);
			$subscriberapi->Set('confirmed', 1);
			$subscriberapi->ListConfirm($listid, $subscriber_id);
		}
		if ($formtype == 'u') {
			$subscriberapi->Set('unsubscribeconfirmed', 1);
			$subscriberapi->Set('unsubscribeip', $ipaddress);
			$subscriberapi->UnsubscribeSubscriber(false, $listid, $subscriber_id, true, 'form', 0);
		}
		$subscriberinfo['Lists'][] = $listname;
	} else {
		$errors[] = sprintf(GetLang('ConfirmCodeDoesntMatch'), $listname);
		unset($lists[$p]); // take this list off the "notification" check list.
	}
}

// if one of the confirm codes have matched, either we're signing up to multiple lists at once
// or we're unsubscribing from multiple lists.
if ($has_confirmed == true) {
	$errors = array();
}

/**
* If we have errors (and only signing up to one list), don't try to send a thanks email.
*/
if (empty($lists)) {
	$sendthanks = false;
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
* We need this in case we need to send a 'thanks' email to the new subscriber.
*/
$subscriber = array('emailaddress' => $email, 'subscriberid' => $subscriber_id, 'confirmcode' => '');
$subscriber['CustomFields'] = array();

/**
* If we need to send an email notification, lets set up the email here and send it off.
*/
if ($send_notification) {
	if ($formtype =='s') {
		$subject = GetLang('SubscriberNotification_Subject');
		$fieldnametype = 'SubscriberNotification_Field';
		$bodyname = 'SubscriberNotification_Body';
	}
	if ($formtype =='u') {
		$subject = GetLang('UnsubscribeNotification_Subject');
		$fieldnametype = 'UnsubscribeNotification_Field';
		$bodyname = 'UnsubscribeNotification_Body';
	}
	$emailapi->Set('FromName', false);
	$emailapi->Set('FromAddress', $email);
	$emailapi->Set('ReplyTo', $email);
	$emailapi->Set('BounceAddress', false);

	$body = '';
	$body .= sprintf(GetLang($fieldnametype), GetLang('EmailAddress'), $email);

	foreach ($subscriberinfo as $p => $subinfo) {

		// make sure we don't include the same info (customfield) multiple times
		// especially if the form supports multiple lists and the same fields.
		$details_already_added = array();

		if (!isset($subscriberinfo[$p]['CustomFields'])) {
			continue;
		}

		foreach ($subscriberinfo[$p]['CustomFields'] as $k => $details) {
			if (in_array($details['fieldid'], $details_already_added)) {
				continue;
			}

			$customfieldsapi->Load($details['fieldid']);
			$sub_field = $customfieldsapi->LoadSubField();

			$fieldvalue = $sub_field->GetRealValue($details['data']);
			$fieldname = $details['fieldname'];
			$body .= sprintf(GetLang($fieldnametype), $fieldname, $fieldvalue);

			$subscriber['CustomFields'][] = $details;
			$details_already_added[] = $details['fieldid'];
		}
		$confcode = $subscriberinfo[$p]['confirmcode'];
	}

	if (!empty($subscriberinfo['Lists'])) {
		$lists = implode(',', $subscriberinfo['Lists']);
		$body .= sprintf(GetLang('SubscriberNotification_Lists'), $lists);

		if ($formtype == 's') {
			$subject = sprintf(GetLang('SubscriberNotification_Subject_Lists'), $lists);
		}

		if ($formtype == 'u') {
			$subject = sprintf(GetLang('UnsubscribeNotification_Subject_Lists'), $lists);
		}
	}

	$emailapi->Set('Subject', $subject);

	$emailbody = sprintf(GetLang($bodyname), $body);

	$emailapi->AddBody('text', $emailbody);
	$emailapi->Send(false);

	// save the confirmation code.
	$subscriber['confirmcode'] = $confcode;
}

if ($sendthanks) {
	$emailapi->ClearRecipients();
	$emailapi->ForgetEmail();
	$emailapi->Set('forcechecks', false);

	$emailapi->Set('Subject', $thankspage['emailsubject']);
	$emailapi->Set('FromName', $thankspage['sendfromname']);
	$emailapi->Set('FromAddress', $thankspage['sendfromemail']);
	$emailapi->Set('ReplyTo', $thankspage['replytoemail']);
	$emailapi->Set('BounceAddress', $thankspage['bounceemail']);

	$emailapi->AddBody('text', $thankspage['emailtext']);
	$emailapi->AddBody('html', $thankspage['emailhtml']);

	$emailapi->AddRecipient($email, false, 'h');

	reset($subscriberinfo);
	$emailapi->AddCustomFieldInfo($email, current($subscriberinfo));

	$emailapi->Set('Multipart', true);
	$mail_results = $emailapi->Send(true);
}

if (!empty($errors)) {
	$errorlist = '<br/>-' . implode('<br/>-', $errors);
	if ($errorpage !== false) {
		$pagetitle = GetLang('FormFail_PageTitle_Confirm');
		$errorurl = $formapi->GetPage('ErrorPage', 'url');
		if ($errorurl) {
			header('Location: ' . $errorurl . '?Errors=' . urlencode($errorlist));
		} else {
			$errorpage = $formapi->GetPage('ErrorPage', 'html');
			echo str_replace(array('%%GLOBAL_ErrorTitle%%', '%%GLOBAL_Errors%%', '%ERRORLIST%'), array($pagetitle, $errorlist, $errorlist), $errorpage);
		}
	} else {
		$GLOBALS['DisplayMessage'] = sprintf(GetLang('DefaultErrorMessage'), $errorlist);
		$sendstudio_functions->ParseTemplate('Default_Form_Message');
	}
	exit();
}

/**
* See whether we need to check whether this subscriber is a referral or not so we can update statistics accordingly.
*/
$forward_lists = array_keys($listdetails);
$statsapi->RecordForwardSubscribe($email, $subscriber_id, $forward_lists);

if ($thankspage !== false) {
	$thanksurl = $formapi->GetPage('ThanksPage', 'url');
	if ($thanksurl) {
		header('Location: ' . $thanksurl);
	} else {
		reset($subscriberinfo);
		echo $formapi->CleanVersion($thankspage['html'], current($subscriberinfo));
	}
	exit();
}

$GLOBALS['DisplayMessage'] = GetLang('DefaultThanksMessage');
$sendstudio_functions->ParseTemplate('Default_Form_Message');

?>
