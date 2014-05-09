<?php
/**
* Language file variables for the frontend area.
*
* @see GetLang
*
* @version     $Id: frontend.php,v 1.11 2007/06/05 07:03:39 chris Exp $

*
* @package SendStudio
* @subpackage Language
*/


/**
* Frontend form stuff.
*/
define('LNG_FormFail_InvalidField', 'Field %s does not exist any more.');
define('LNG_FormFail_InvalidList', 'List %s does not exist any more.');
define('LNG_FormFail_AlreadySubscribedToList', 'You are already subscribed to list %s');
define('LNG_FormFail_NotOnList', 'You are not subscribed to list %s');

define('LNG_Form_NoLists_Subscribe', 'Please choose some mailing lists to subscribe to');
define('LNG_Form_NoLists_Unsubscribe', 'Please choose some mailing lists to unsubscribe from');
define('LNG_Form_NoLists_ModifyDetails', 'Please choose some mailing lists to update details for');

define('LNG_FormFail_InvalidData_Subscribe', 'Please enter valid data or choose valid options for the %s field.');
define('LNG_FormFail_InvalidData_Unsubscribe', 'You don\'t need to enter anything to unsubscribe.');
define('LNG_FormFail_InvalidData_ModifyDetails', 'Please enter valid data or choose valid options for the %s field.');

define('LNG_FormFail_PageTitle_Subscribe', 'Errors with subscription form');
define('LNG_FormFail_PageTitle_Confirm', 'Errors with confirming your details');
define('LNG_FormFail_PageTitle_Unsubscribe', 'Errors with unsubscribe form');
define('LNG_FormFail_PageTitle_ModifyDetails', 'Errors with modify details form');

define('LNG_Form_EmailEmpty_Subscribe', 'Please enter a valid email address.');
define('LNG_Form_EmailEmpty_Unsubscribe', 'Please enter a valid email address.');
define('LNG_Form_EmailEmpty_ModifyDetails', 'Please enter a valid email address.');

define('LNG_Form_CaptchaIncorrect', 'The answer you provided for the captcha image does not match the one in the form. Please try again.');

define('LNG_SubscriberNotification_Subject', 'A subscriber has joined your list');
define('LNG_SubscriberNotification_Body', 'A subscriber has joined your mailing list. Their details are listed below.' . "\n" . 'You can send them an email by hitting "Reply".' . "\n\n-----\n" . '%s');
define('LNG_SubscriberNotification_Field', '%s: %s' . "\n");
define('LNG_SubscriberNotification_Lists', 'Mailing List(s): %s' . "\n");

define('LNG_UnsubscribeNotification_Subject', 'A subscriber has unsubscribed your list');
define('LNG_UnsubscribeNotification_Body', 'A subscriber has been removed from your mailing list. Their details are listed below.' . "\n" . 'You can send them an email by hitting "Reply".' . "\n\n-----\n" . '%s');
define('LNG_UnsubscribeNotification_Field', '%s: %s' . "\n");

define('LNG_UnsubscribeFail_InvalidList', 'One of the lists you are trying to unsubscribe from doesn\'t exist.');

define('LNG_UnsubscribeFail_AlreadyUnsubscribed', 'You have already unsubscribed from list %s');

/**
* This is used for the default "confirmation" message. For example if you import subscribers and then send them a confirmation link, they will get shown this message (need this because they haven't subscribed through a form so need a final message to show).
*/
define('LNG_DefaultThanksMessage', 'Thanks for signing up to our mailing list.');

/**
* This is used for the default "error" message. For example if you import subscribers and then send them a confirmation link, if they change the confirmation link manually then this will be used.
*/
define('LNG_DefaultErrorMessage', 'There were some errors whilst checking your email address.<br/>%s');

define('LNG_InvalidUnsubscribeURL', 'The unsubscribe link you have clicked is invalid.');
define('LNG_InvalidSendFriendURL', 'The send-to-friend link you have clicked is invalid.');
define('LNG_InvalidModifyURL', 'The modify-details link you have clicked is invalid.');
define('LNG_InvalidConfirmURL', 'The confirm link you have clicked is invalid. You may have already confirmed your subscription.');

define('LNG_DefaultUnsubscribeMessage', 'You have been successfully unsubscribed.');

/**
* Check for banned subscribers.
*/
define('LNG_YouAreABannedSubscriber', 'You are banned from joining %s');
define('LNG_AllLists', 'all lists');
define('LNG_SpecificList', 'mailing list \'%s\'');
define('LNG_SpecificLists', 'mailing lists \'%s\'');

define('LNG_InvalidEmailAddress', 'You entered an invalid email address.');
define('LNG_ConfirmCodeDoesntMatch', 'You have already confirmed your email address on this list.');


define('LNG_ConfirmCodeDoesntMatch_Unsubscribe', 'The link you clicked on is not a valid link.');

/**
* Modify details changes.
*/
define('LNG_NewEmailAlreadyOnList', 'The new email address \'%s\' is already subscribed to list \'%s\'');

/**
* Send to friend stuff.
*/
define('LNG_FormFail_PageTitle_SendFriend', 'Send to Friend Errors');
define('LNG_EnterYourFriendsEmailAddress', 'Please enter your friends email address.');
define('LNG_EnterYourEmailAddress', 'Please enter your email address.');
define('LNG_NewsletterDoesntExistAnymore', 'This email doesn\'t exist any more, so we can\'t send it to your friend. Sorry!<br/>');

/**
* RSS Feeds
*/
define('LNG_NewsletterArchives', 'Email Campaign Archives');
define('LNG_NewsletterArchives_List', 'Email Campaign Archives for list \'%s\'');
define('LNG_NewsletterArchives_NoneSent', 'No email campaigns have been sent');

/**
**************************
* Changed/added in NX1.1.2
**************************
*/
define('LNG_SubscriberNotification_Subject_Lists', 'A subscriber has joined your mailing list \'%s\'');
define('LNG_UnsubscribeNotification_Subject_Lists', 'A subscriber has unsubscribed your list \'%s\'');

?>
