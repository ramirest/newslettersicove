<?php
/**
* This file handles printing a send-to-friend form.
* It uses the appropriate api's to check subscribers, custom field values and lists.
*
* @see Forms_API
* @see Lists_API
* @see Subscribers_API
* @see CustomFields_API
* @see Email_API
*
* @version     $Id: sendfriend.php,v 1.10 2007/05/15 07:03:41 rodney Exp $

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

$session = &GetSession();

$sendstudio_functions = & new Sendstudio_Functions();
$sendstudio_functions->LoadLanguageFile('frontend');
$sendstudio_functions->LoadLanguageFile('forms');

$subscriberapi = $sendstudio_functions->GetApi('Subscribers');

$formapi = $sendstudio_functions->GetApi('Forms');

$errors = array();

$foundparts = array();

foreach ($_GET as $key => $part) {
	$foundparts[strtolower($key)] = $part;
}

if (isset($foundparts['c'])) {
	$confirmcode = $foundparts['c'];
} else {
	BadForm();
}

if (isset($foundparts['f'])) {
	$form = (int)$foundparts['f'];
} else {
	BadForm();
}

$loaded = $formapi->Load($form);

if (!$loaded) {
	BadForm();
}

// this checks where it came from. It's either autoresponder or newsletter.
// This is checked a bit later on, we just need to make sure it's somewhere.
if (!isset($foundparts['i'])) {
	BadForm();
}

if (isset($foundparts['m'])) {
	$subscriber_id = (int)$foundparts['m'];
} else {
	BadForm();
}

$list = $foundparts['l'];

$subscriber_list_info = $subscriberapi->LoadSubscriberList($subscriber_id, $list);
if ($subscriber_list_info['confirmcode'] != $confirmcode) {
	BadForm();
}

$newsletter = $autoresponder = false;

if (isset($foundparts['a'])) {
	$statstype = 'auto';
	$statid = $foundparts['a'];
	$autoresponder = $foundparts['i'];
} else {
	$statstype = 'newsletter';
	$statid = $foundparts['n'];
	$newsletter = $foundparts['i'];
}

if (!$newsletter && !$autoresponder) {
	BadForm();
}

// so we know which placeholders to replace so we can pre-fill the form.
$placeholders = $placeholder_values = array();

$placeholders[] = '%%Email%%';
$placeholder_values[] = $subscriber_list_info['emailaddress'];

$session->Set('Form', $form);
$session->Set('List', $list);
$session->Set('Newsletter', $newsletter);
$session->Set('Statid', $statid);
$session->Set('Subscriber', $subscriber_id);
$session->Set('Autoresponder', $autoresponder);

$formhtml = $formapi->Get('formhtml');

$placeholders[] = '%%FORMACTION%%';
$placeholder_values[] = SENDSTUDIO_APPLICATION_URL . '/send_friend.php';

// pre-fill the form.
$formhtml = str_replace($placeholders, $placeholder_values, $formhtml);

// print 'er out!
echo $formhtml;

/**
* BadForm
* This is used to display a 'bad url' message all through out this file.
*
*/
function BadForm()
{
	echo GetLang('InvalidSendFriendURL');
	exit();
}
?>
