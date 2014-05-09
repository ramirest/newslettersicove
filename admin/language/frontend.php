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
define('LNG_FormFail_InvalidField', 'O Campo %s n&atilde;o existe mais.');
define('LNG_FormFail_InvalidList', 'A Lista %s n&atilde;o existe mais.');
define('LNG_FormFail_AlreadySubscribedToList', 'Voc&ecirc; j&aacute; est&aacute; inscrito na lista %s');
define('LNG_FormFail_NotOnList', 'Voc&ecirc; n&atilde;o se inscriveu na lista %s');

define('LNG_Form_NoLists_Subscribe', 'Por favor escolha alguma lista de email para se inscrever');
define('LNG_Form_NoLists_Unsubscribe', 'Por favor escolha alguma lista de email para cancelar a inscri&ccedil;&atilde;o');
define('LNG_Form_NoLists_ModifyDetails', 'Por favor escolha alguma lista de email para atualizar seus detalhes');

define('LNG_FormFail_InvalidData_Subscribe', 'Por favor insira dados v&aacute;lidos ou escolha op&ccedil;&otilde;es v&aacute;lidas para o campo %s.');
define('LNG_FormFail_InvalidData_Unsubscribe', 'Voc&ecirc; n&atilde;o precisa digitar nada para cancelar a inscri&ccedil;&atilde;o.');
define('LNG_FormFail_InvalidData_ModifyDetails', 'Por favor insira dados v&aacute;lidos ou escolha op&ccedil;&otilde;es v&aacute;lidas para o campo %s.');

define('LNG_FormFail_PageTitle_Subscribe', 'Erros com o formul&aacute;rio de inscri&ccedil;&atilde;o');
define('LNG_FormFail_PageTitle_Confirm', 'Erros com a confirma&ccedil;&atilde;o de detalhes');
define('LNG_FormFail_PageTitle_Unsubscribe', 'Erros com o formul&aacute;rio de cancelamento de inscri&ccedil;&atilde;o');
define('LNG_FormFail_PageTitle_ModifyDetails', 'Erros com o formul&aacute;rio de modifica&ccedil;&atilde;o de detalhes');

define('LNG_Form_EmailEmpty_Subscribe', 'Por favor digite um email v&aacute;lido.');
define('LNG_Form_EmailEmpty_Unsubscribe', 'Por favor digite um email v&aacute;lido.');
define('LNG_Form_EmailEmpty_ModifyDetails', 'Por favor digite um email v&aacute;lido.');

define('LNG_Form_CaptchaIncorrect', 'The answer you provided for the captcha image does not match the one in the form. Please try again.');

define('LNG_SubscriberNotification_Subject', 'Uma pessoa assinou sua lista');
define('LNG_SubscriberNotification_Body', 'Uma pessoa assinou sua lista de emails. Seus detalhes est&atilde;o listados abaixo.' . "\n" . 'You can send them an email by hitting "Reply".' . "\n\n-----\n" . '%s');
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
define('LNG_NewsletterArchives', 'Arquivos da Campanha de Email');
define('LNG_NewsletterArchives_List', 'Arquivos da Campanha de Email para a lista \'%s\'');
define('LNG_NewsletterArchives_NoneSent', 'Nenhuma campanha de email foi enviada');

/**
**************************
* Changed/added in NX1.1.2
**************************
*/
define('LNG_SubscriberNotification_Subject_Lists', 'A subscriber has joined your mailing list \'%s\'');
define('LNG_UnsubscribeNotification_Subject_Lists', 'A subscriber has unsubscribed your list \'%s\'');

?>
