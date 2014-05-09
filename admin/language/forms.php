<?php
/**
* Language file variables for the forms area.
*
* @see GetLang
*
* @version     $Id: forms.php,v 1.31 2007/05/08 06:48:29 chris Exp $

*
* @package SendStudio
* @subpackage Language
*/

/**
* Here are all of the variables for the forms area... Please backup before you start!
*/

define('LNG_FormConfirmPage_Unubscribe_Subject', 'Unsubscribe Request');
define('LNG_FormConfirmPage_Unsubscribe_HTML', 'please confirm your request to unsubscribe from this mailing list.');
define('LNG_FormThanksPage_Unubscribe_Subject', '');

define('LNG_Form_Edit_Disabled', 'You cannot edit this form because you do not have access.');
define('LNG_Form_Delete_Disabled', 'You cannot delete this form because you do not have access.');
define('LNG_Form_Copy_Disabled', 'You cannot copy this form because you do not have access.');

define('LNG_Preview_Form', 'Preview Form');

define('LNG_CreateForm', 'Create Website Form');
define('LNG_CreateFormButton', 'Create Form');
define('LNG_CreateFormIntro', 'Here you can create forms to place on your website or inside emails. These forms are used to allow your visitors to subscribe, unsubscribe, modify details or send your newsletter to their friend.');
define('LNG_CreateFormCancelButton', 'Are you sure you want to cancel creating a new form?');
define('LNG_CreateFormHeading', 'New Form Details');

define('LNG_EditForm', 'Edit Form');
define('LNG_EditFormIntro', 'Complete the form below to update your existing form.');
define('LNG_EditFormCancelButton', 'Are you sure you want to cancel updating your form?');
define('LNG_EditFormHeading', 'Edit Form Details');

define('LNG_FormDetails', 'Form Details');

define('LNG_NoSuchFormDesign', 'That form design does not exist. Please try again.');
define('LNG_NoSuchForm', 'That form no longer exists. Please try again.');

define('LNG_FormsManage', 'Manage Website Forms');
define('LNG_Help_FormsManage', 'Here you can manage your different types of website forms, including Subscribe and Unsubscribe, Modify details and Send to a Friend.<br>To create a new form click on the \'Create Form\' button.');

define('LNG_EnterFormName', 'Please enter a name for this form.');
define('LNG_ChooseFormLists', 'Please choose some mailing lists to include on this form.');

define('LNG_EnterSendFromName', 'Enter a name to display in the \'From\' field');
define('LNG_EnterSendFromEmail', 'Enter an email address to send the emails from.');
define('LNG_EnterReplyToEmail', 'Enter an email address for the subscriber to reply to.');
define('LNG_EnterBouceEmail', 'Enter an email address  in case the form bounces back from the subscriber.');
define('LNG_EnterConfirmSubject', 'Enter a subject for the confirmation email.');
define('LNG_EnterThanksSubject', 'Enter a subject for the thanks email.');

define('LNG_FormName', 'Form Name');

define('LNG_FormNameIsNotValid', 'Form Name is not Valid');
define('LNG_FormChooseList', 'Choose lists to include on this form');
define('LNG_UnableToCreateForm', 'Unable to create form');
define('LNG_FormCreated', 'The new form has been created successfully');

define('LNG_DeleteFormPrompt', 'Are you sure you want to delete this form?');

define('LNG_FormDeleteSuccess_One', 'The selected form has been deleted successfully. Make sure you remove it from your web site as it will no longer work.');
define('LNG_FormDeleteSuccess_Many', '%s forms deleted successfully. Please make sure you remove them from your websites.');
define('LNG_FormDeleteFail_One', 'Form not deleted successfully. Please try again.');
define('LNG_FormDeleteFail_Many', '%s form not deleted successfully. Please try again.');

define('LNG_ConfirmRemoveForms', 'Are you sure you want to remove these forms?');
define('LNG_ChooseFormsToDelete', 'Please choose one or more forms first.');
define('LNG_Delete_Form_Selected', 'Delete Selected');

define('LNG_HLP_FormName', 'The name of the form. This is only used in the management area, not on your website.');

define('LNG_UnableToUpdateForm', 'Unable to update form');
define('LNG_FormUpdated', 'Form updated successfully');

define('LNG_NoForms', 'No forms have been created.%s');
define('LNG_NoForms_HasAccess', ' Click the "Create Form" button below to create one.');

define('LNG_FormCopySuccess', 'Form was copied successfully.');
define('LNG_FormCopyFail', 'Form was not copied successfully.');

define('LNG_SubscriberChooseFormat', 'Format Options');
define('LNG_HLP_SubscriberChooseFormat', 'Would you like to give your subscribers the option to choose which format they will receive your email campaigns in?');
define('LNG_ChooseFormat', 'Allow Subscriber to Choose');
define('LNG_ForceHTML', 'HTML');
define('LNG_ForceText', 'Text');

define('LNG_SubscriberChangeFormat', 'Change Format');
define('LNG_HLP_SubscriberChangeFormat', 'Would you like your subscribers to be able to change which type of emails they receive? They will be able to either switch from html to text, or text to html.');
define('LNG_SubscriberChangeFormatExplain', 'Yes, allow the subscriber to change their email format.');

define('LNG_FormType', 'Form Type');
define('LNG_HLP_FormType', 'Choose the type of form you will be creating. <br><br>A <i>subscription</i> form lets visitors subscribe to your mailing list.<br><br>An <i>unsubscribe</i> form allow visitors to unsubscribe from your mailing list. This is optional, and an unsubscribe link can be added to your email campaigns automatically instead.<br><br>A <i>modify details</i> form allows subscribers to modify their subscription information.<br><br>Finally, a <i>send to a friend</i> form lets users share your email campaign with their friends.');
define('LNG_FormType_Subscribe', 'Subscription');
define('LNG_FormType_Unsubscribe', 'Unsubscribe');
define('LNG_FormType_ModifyDetails', 'Modify Details');
define('LNG_FormType_SendToFriend', 'Send to Friend');

define('LNG_ContactForm', 'Contact Form');
define('LNG_ContactFormExplain', 'Yes, emulate a contact form.');
define('LNG_HLP_ContactForm', 'This subscription form will also act as a contact form. You will receive an email with the contents of the form once it has been filled in, the user will be subscribed to your list and sent to your thank you page.<br/>If they are already subscribed to your list, they will be shown the thank you page instead of an error message.');

define('LNG_UseCaptcha', 'Include Captcha');
define('LNG_UseCaptchaExplain', 'Yes, include captcha on this form.');
define('LNG_HLP_UseCaptcha', 'Captcha (an acronym for \\\'completely automated public Turing test to tell computers and humans apart\\\') is a type of challenge-response test used in computing to determine whether or not the user is human. This helps prevent automated submission of your forms. If this is on, the form will ask for a \\\'security code\\\' to be entered in before the user can complete the website form.');

define('LNG_RequireConfirmation', 'Require Confirmation');
define('LNG_HLP_RequireConfirmation', 'Does the form require a confirmation email to be sent before the subscriber is added/removed from your list?');
define('LNG_RequireConfirmationExplain', 'Yes, this form requires confirmation');

define('LNG_SendThanks', 'Send thank you email');
define('LNG_HLP_SendThanks', 'If yes, a thank you email will be sent to the subscriber after they complete the form.');
define('LNG_SendThanksExplain', 'Yes, send a thank you email to the subscriber.');

define('LNG_ListsToInclude', 'Lists Available For Subscription');
define('LNG_IncludeLists', 'Mailing Lists');
define('LNG_HLP_IncludeLists', 'Which lists should the visitor be able to subscribe to/unsubscribe from on this form?');

define('LNG_FormDesign', 'Form Design');
define('LNG_HLP_FormDesign', 'This will give you an idea of how your form will look on your site. You can modify the HTML code if you would like to change this later.');

define('LNG_ChooseCustomFields', 'Custom Fields For \'%s\' Mailing List');
define('LNG_OrderCustomFields', 'Sort custom fields');
define('LNG_HLP_OrderCustomFields', 'You can change the order your custom fields appear in your form.<br/>To move something up or down, highlight the field name and click the Up or Down arrow.');
define('LNG_Email_Required_For_Form', 'Email (Obrigat&oacute;rio)');
define('LNG_SubscriberFormat_For_Form', 'Formato de Assinatura');
define('LNG_ChooseList_For_Form', 'Lista de Escolhas');
define('LNG_ChooseCustomFieldsToInclude', 'Por favor escolha o campo personalizado para incluir em seu formul&aacute;rio.');
define('LNG_ChooseCustomFieldToOrder', 'Por favor escolha o campo personalizado que voc&ecirc; quer reordenar');

define('LNG_FormSubmit', 'Enviar');
define('LNG_FormClear', 'Limpar');

define('LNG_FormOptions', 'Op&ccedil;&otilde;es do Formul&aacute;rio');

define('LNG_FormSendFromName', 'Nome do Remetente');
define('LNG_FormSendFromEmail', 'Email do Rementente');
define('LNG_FormReplyToEmail', 'Email p/ Resposta');
define('LNG_FormBounceEmail', 'Email p/ Retorno');

define('LNG_ConfirmPageIntro', 'Choose form page and email settings.');
define('LNG_ThanksPageIntro', 'Choose thank you settings.');

define('LNG_ConfirmSubject', 'Email Subject');
define('LNG_HLP_ConfirmSubject', 'The subject of the confirmation email sent to the new subscriber.');

define('LNG_ConfirmPageHTML', 'Confirm Page HTML');
define('LNG_ConfirmPageURL', '<b><i>OR</i></b> &nbsp;Confirm Page URL');

define('LNG_ConfirmTextVersion', 'Confirmation Email (Text)');
define('LNG_ConfirmHTMLVersion', 'Confirmation Email (HTML)');

define('LNG_ThanksPageHTML', 'Thank You Page HTML');
define('LNG_HLP_ThanksPageHTML', 'Enter the content that should appear on the thank you page.');
define('LNG_ThanksPageURL', '<b><i>OR</i></b>&nbsp; Thank You Page URL');
define('LNG_HLP_ThanksPageURL', 'If you have already uploaded your thank you page, enter the URL to that file here and subscribers will be taken to that page instead.');

define('LNG_ThanksSubject', 'Email Subject');
define('LNG_HLP_ThanksSubject', 'The subject of the thanks email sent to the new subscriber.');
define('LNG_ThanksTextVersion', 'Thank You Email (Text)');
define('LNG_ThanksHTMLVersion', 'Thank You Email (HTML)');

define('LNG_FormFormNameIsNotValid', 'Form name is not valid.');
define('LNG_FormFormDesignIsNotValid', 'Form design is not valid.');
define('LNG_FormFormTypeIsNotValid', 'Form type is not valid.');
define('LNG_FormRequireConfirmationIsNotValid', 'Please choose whether this form requires confirmation or not.');
define('LNG_FormSendThanksIsNotValid', 'Please choose whether this form requires a thanks email to be sent to the subscriber.');
define('LNG_FormSubscriberChooseFormatIsNotValid', 'Please choose whether this form allows the subscriber to choose their format.');
define('LNG_FormIncludeListsIsNotValid', 'Please choose some mailing lists for this form to use.');

define('LNG_FormDisplayPageOptions', 'Form Display Options');
define('LNG_FormConfirmEmailOptions', 'Confirmation Email Options');
define('LNG_FormThanksEmailOptions', 'Thanks Email Options');

define('LNG_FinalPageIntro', 'Choose thank you page and error settings.');

define('LNG_FormThanksPageOptions', 'Thank You Page Options');
define('LNG_FormErrorPageOptions', 'Error Page Options');
define('LNG_ErrorPageHTML', 'Error Page HTML');
define('LNG_HLP_ErrorPageHTML', 'Enter the content that should appear on the error page.');
define('LNG_ErrorPageURL', '<b><i>OR</i></b>&nbsp; Error Page URL');
define('LNG_HLP_ErrorPageURL', 'If you have already uploaded your error page, enter the URL to that file here and subscribers will be taken to that page instead.');

define('LNG_Form_ChooseFormat', 'Preferred Format');
define('LNG_Form_EmailAddress', 'Your email address');
define('LNG_GetHTML', 'Get HTML');

define('LNG_FormGetHTML_Heading', 'Get HTML Code');
define('LNG_FormGetHTML_Introduction', 'The HTML code for your website form is shown below. Simply copy the code below and paste it into your website.<br>You can modify the HTML code to match your website look and feel, however the form and its objects must remain intact.');
define('LNG_FormGetHTML_Options', 'HTML Form Code');
define('LNG_FormHTML', 'Form HTML');
define('LNG_HLP_FormHTML', 'This is the code you place on your website to let your visitors subscribe to your email campaigns. Simply select all of the code, right click in the text box, choose copy. Then edit your web page, and paste the code where you want to display the signup form.');

/**
* Confirmation options.
*/
define('LNG_FormConfirmPage_Subscribe_HTML',
'
<html>
<head>
<style>
body {
	margin: 0px;
}

#content {
	border: 1px solid #EFECBA;
	width: 300px;
	height: 150px;
	background-color: #FBFAE7;
	padding:20px;
	top: 50%;
	left: 50%;
	position: relative;
}

#container  {
	width: 100%;
	height: 100%;
	font: 11px tahoma;
	position: absolute;
	top: -75px;
	left: -150px;
}

</style>
</head>
<body>
<div id="container">
	<div id="content">
		<b>Your email subscription is almost complete...</b><br><br>
		An email has been sent to the email address you entered. In this email is a confirmation link. Please click on this link to confirm your subscription.<br><br>
		Once you\'ve done this your subscription will be complete.<br><br>
		<a href="javascript:history.back()">&laquo Go Back</a>
	</div>
</div>
</body>
</html>');


define('LNG_FormConfirmPage_Subscribe_Subject', 'Confirm your subscription');

define('LNG_FormConfirmPage_Subscribe_Email_Text', "Thank you for subscribing to our newsletter.\n\nTo finalize your subscription, please click on the confirmation link below. Once you've done this, your subscription will be complete.\n\n%%CONFIRMLINK%%\n\n");

define('LNG_FormConfirmPage_Subscribe_Email_HTML', "
<html>
<body style='font:12px tahoma'>
<b>Please confirm your subscription</b>
<br><br>
Thank you for subscribing to our newsletter.<br><br>To finalize your subscription, please click on the confirmation link below. Once you've done this, your subscription will be complete.<br><br>
<a href='%%CONFIRMLINK%%' target='_blank'>Please click here to confirm your subscription</a><br><br>or copy and paste the following URL into your browser:<br>
%%CONFIRMLINK%%");

define('LNG_FormConfirmPage_Unubscribe_HTML', 'Please confirm you want to be removed from the list before we action it.<br/>');
define('LNG_FormConfirmPage_Unsubscribe_Subject', 'Please confirm you want to unsubscribe');
define('LNG_FormConfirmPage_Unsubscribe_Email_Text', "Please confirm you want to unsubscribe by clicking on the link below:\n\n%BASIC:CONFIRMUNSUBLINK%\n\nWe need to do this before removing you from the mailing list.");
define('LNG_FormConfirmPage_Unsubscribe_Email_HTML', "Please confirm want to unsubscribe by clicking on <a href='%BASIC:CONFIRMUNSUBLINK%' target='_blank'>this link</a>. We need to do this before removing you from the mailing list.");

/**
* Some form options are disabled.
*/
define('LNG_GetHTML_ModifyDetails_Disabled', 'You cannot Get HTML for the modify details form.');
define('LNG_GetHTML_ModifyDetails_Disabled_Alert', 'You cannot place a modify details form on your website. To use this form, edit an email campaign or autoresponder and click the Insert Custom Field link at the bottom of the editor to include a link to this form.');
define('LNG_GetHTML_SendFriend_Disabled', 'You cannot place a send to friend form on your website. To use this form, edit an email campaign or autoresponder and click the Insert Custom Field link at the bottom of the editor to include a link to this form.');
define('LNG_GetHTML_SendFriend_Disabled_Alert', 'You cannot place a send-to-friend form on your website.\nTo use this form, edit an email campaign or autoresponder and include a link to the form.');

/**
* For modify details and send-to-friend forms, we have extra html editing options.
*/
define('LNG_FormEditHTMLOptions', 'Edit Form HTML');
define('LNG_EditFormHTML', 'Edit Form HTML');
define('LNG_HLP_EditFormHTML', 'Customize the way your form looks by modifying the default HTML code.<br/><br/>You must leave the form tag, the field names and the placeholders as they are.');

define('LNG_FormHasBeenChanged', 'Warning - the form has been changed. New HTML code will be generated for this form.\nDo you wish to continue?');

define('LNG_FormDisplaySendFriendOptions', 'Forwarded email headers');
/**
* Thanks email options.
*/
define('LNG_FormThanksPage_Subscribe_HTML', '
<html>
<head>
<style>
body {
	margin: 0px;
}

#content {
	border: 1px solid #EFECBA;
	width: 300px;
	height: 150px;
	background-color: #FBFAE7;
	padding:20px;
	top: 50%;
	left: 50%;
	position: relative;
}

#container  {
	width: 100%;
	height: 100%;
	font: 11px tahoma;
	position: absolute;
	top: -75px;
	left: -150px;
}

</style>
</head>
<body>
<div id="container">
	<div id="content">
		<b>Your subscription is now complete.</b><br><br>
		Thank you for subscribing to our mailing list. Your subscription is now complete.<br><br>
	</div>
</div>
</body>
</html>');

define('LNG_FormThanksPage_Subscribe_Subject', 'Your subscription is now complete.');
define('LNG_FormThanksPage_Subscribe_Email_Text', "Thank you for subscribing to our mailing list.\n\nYour subscription is now complete. If you have any questions you can contact us by replying to this email.");

define('LNG_FormThanksPage_Subscribe_Email_HTML', "
<html>
<body style='font: 12px tahoma'>
<b>Your subscription is complete</b><br><br>
Thank you for subscribing to our mailing list. Your subscription is now complete. If you have any questions you can contact us by replying to this email.
</body>
</html>
");

/**
* These are used if the signup form is a contact form as well.
*/
define('LNG_FormThanksPage_Subscribe_Subject_Contact', 'Thank you for signing up to our mailing list');
define('LNG_FormThanksPage_Subscribe_Email_Text_Contact', "Thank you for signing up to our mailing list and/or contacting us. If you have any problems you can contact us by replying to this email.");
define('LNG_FormThanksPage_Subscribe_Email_HTML_Contact', "Thank you for signing up to our mailing list and/or contacting us. If you have any problems you can contact us by replying to this email.");

define('LNG_FormThanksPage_Unsubscribe_Subject', 'You have been unsubscribed.');
define('LNG_FormThanksPage_Unsubscribe_Email_Text', "Hi,\nYou have been unsubscribed from our mailing list.\nSorry to see you go!");
define('LNG_FormThanksPage_Unsubscribe_Email_HTML', "Hi,<br/>You have been unsubscribed from our mailing list.<br/>Sorry to see you go!");

/**
* Thanks page options.
*/
define('LNG_FormThanksPageHTML_Subscribe', '
<html>
<head>
<style>
body {
	margin: 0px;
}

#content {
	border: 1px solid #EFECBA;
	width: 300px;
	height: 150px;
	background-color: #FBFAE7;
	padding:20px;
	top: 50%;
	left: 50%;
	position: relative;
}

#container  {
	width: 100%;
	height: 100%;
	font: 11px tahoma;
	position: absolute;
	top: -75px;
	left: -150px;
}

</style>
</head>
<body>
<div id="container">
	<div id="content">
		<b>Your subscription is now complete.</b><br><br>
		Thank you for subscribing to our mailing list. Your subscription is now complete.<br><br>
	</div>
</div>
</body>
</html>
');

define('LNG_FormThanksPageHTML_Unsubscribe', 'Sorry to see you go!');

/**
* Error page
*/
define('LNG_FormErrorPageHTML_Subscribe',
'<html>
<head>
<style>
body {
	margin: 0px;
}

#content {
	border: 1px solid #EFECBA;
	width: 300px;
	height: 150px;
	background-color: #FBFAE7;
	padding:20px;
	top: 50%;
	left: 50%;
	position: relative;
}

#container  {
	width: 100%;
	height: 100%;
	font: 11px tahoma;
	position: absolute;
	top: -75px;
	left: -150px;
}

</style>
</head>
<body>
<div id="container">
	<div id="content">
		<b>An error has occurred.</b><br><br>
		An error(s) has occurred while trying to subscribe you to our mailing list:<br>
		%%GLOBAL_Errors%%
		<br><br>
		<a href="javascript:history.back()">&laquo Go Back</a>
	</div>
</div>
</body>
</html>');



define('LNG_FormThanksPageHTML_Modify', '
<html>
<head>
<style>
body {
	margin: 0px;
}

#content {
	border: 1px solid #EFECBA;
	width: 300px;
	height: 150px;
	background-color: #FBFAE7;
	padding:20px;
	top: 50%;
	left: 50%;
	position: relative;
}

#container  {
	width: 100%;
	height: 100%;
	font: 11px tahoma;
	position: absolute;
	top: -75px;
	left: -150px;
}

</style>
</head>
<body>
<div id="container">
	<div id="content">
		<b>Your modifications have been completed successfully.</b><br><br>
		The changes made to your details stored with us have been completed successfully.
		<br><br>
	</div>
</div>
</body>
</html>
');


define('LNG_FormErrorPageHTML_Modify', '
<html>
<head>
<style>
body {
	margin: 0px;
}

#content {
	border: 1px solid #EFECBA;
	width: 300px;
	height: 150px;
	background-color: #FBFAE7;
	padding:20px;
	top: 50%;
	left: 50%;
	position: relative;
}

#container  {
	width: 100%;
	height: 100%;
	font: 11px tahoma;
	position: absolute;
	top: -75px;
	left: -150px;
}

</style>
</head>
<body>
<div id="container">
	<div id="content">
		<b>An error has occurred.</b><br><br>
		An error(s) has occurred trying to change your details:
		%%GLOBAL_Errors%%
		<br><br>
		<a href="javascript:history.back()">&laquo Go Back</a>
	</div>
</div>
</body>
</html>
');

define('LNG_FormErrorPageHTML_Unsubscribe',
'<html>
<head>
<style>
body {
	margin: 0px;
}

#content {
	border: 1px solid #EFECBA;
	width: 300px;
	height: 150px;
	background-color: #FBFAE7;
	padding:20px;
	top: 50%;
	left: 50%;
	position: relative;
}

#container  {
	width: 100%;
	height: 100%;
	font: 11px tahoma;
	position: absolute;
	top: -75px;
	left: -150px;
}

</style>
</head>
<body>
<div id="container">
	<div id="content">
		<b>An error has occurred.</b><br><br>
		An error(s) has occurred trying to unsubscribe you from our mailing list:
		%%GLOBAL_Errors%%
		<br><br>
		<a href="javascript:history.back()">&laquo Go Back</a>
	</div>
</div>
</body>
</html>
');

/**
* Send-to-Friend stuff.
*/
define('LNG_SendFriendPageIntro', 'A Send to a Friend form is used to allow subscribers to forward your email onto their friends. This form can only be included inside an email and is auto-generated when a user clicks it.');
define('LNG_FormThanksPageHTML_SendFriend', '<html>
<head>
<style>
body {
	margin: 0px;
}

#content {
	border: 1px solid #EFECBA;
	width: 300px;
	height: 150px;
	background-color: #FBFAE7;
	padding:20px;
	top: 50%;
	left: 50%;
	position: relative;
}

#container  {
	width: 100%;
	height: 100%;
	font: 11px tahoma;
	position: absolute;
	top: -75px;
	left: -150px;
}

</style>
</head>
<body>
<div id="container">
	<div id="content">
		<b>Your email was forwarded successfully.</b><br><br>
		Thank you for forwarding this email. It has been sent to your friend.
	</div>
</div>
</body>
</html>');
define('LNG_FormErrorPageHTML_SendFriend', '<html>
<head>
<style>
body {
	margin: 0px;
}

#content {
	border: 1px solid #EFECBA;
	width: 300px;
	height: 150px;
	background-color: #FBFAE7;
	padding:20px;
	top: 50%;
	left: 50%;
	position: relative;
}

#container  {
	width: 100%;
	height: 100%;
	font: 11px tahoma;
	position: absolute;
	top: -75px;
	left: -150px;
}

</style>
</head>
<body>
<div id="container">
	<div id="content">
		<b>An error has occurred.</b><br><br>
		An error(s) has occurred trying to send this email to your friend:
		%%GLOBAL_Errors%%
		<br><br>
		<a href="javascript:history.back()">&laquo Go Back</a>
	</div>
</div>
</body>
</html>');
define('LNG_FormSendFriendPage_Email_HTML', '<div style="padding: 5px; border: 1px solid #EFECBA; background-color: #FBFAE7; text-align: center; font-family: tahoma; font-size: 11px;">This email was forwarded to you by %REFERRER_EMAIL%.</div>');
define('LNG_FormSendFriendPage_Email_Text', "This email was forwarded to you by %REFERRER_EMAIL%.");
define('LNG_SendFriendTextVersion', 'Email Header (Text)');
define('LNG_HLP_SendFriendTextVersion', 'This text is placed at the beginning of the email your subscriber is forwarding.<br/><br/>You should include a link to your subscription form on your web site so the recipient can sign up if they want to.');
define('LNG_SendFriendHTMLVersion', 'Email Header (HTML)');
define('LNG_HLP_SendFriendHTMLVersion', 'This HTML is placed at the beginning of the email your subscriber is forwarding.<br/><br/>You should include a link to your email campaign subscription form on your web site so the recipient can sign up if they want to.');


/**
* Javascript/customfield stuff.
*/
define('LNG_Form_Javascript_Field', 'Please enter a value for field %s');
define('LNG_Form_Javascript_Field_Choose', 'Please choose an option for field %s');
define('LNG_Form_Javascript_Field_Choose_Multiple', 'Please choose one or more options for field %s');
define('LNG_Form_Javascript_Field_NumberCheck', 'Please enter a numeric value for field %s');
define('LNG_Form_Javascript_EnterEmailAddress', 'Please enter your email address.');
define('LNG_Form_Javascript_ChooseFormat', 'Please choose a format to receive your email campaigns in');
define('LNG_Form_Javascript_ChooseLists', 'Please choose some mailing lists to subscribe to');
define('LNG_Form_Javascript_EnterCaptchaAnswer', 'Please enter the security code shown');
define('LNG_Form_EnterCaptcha', 'Enter the security code shown');

/**
* Buttons etc for form designs.
*/
define('LNG_Form_Subscribe_Button', 'Subscribe');

define('LNG_Form_Unsubscribe_Button', 'Unsubscribe');

define('LNG_Form_ModifyDetails_Button', 'Update your details');

define('LNG_Form_SendFriend_Button', 'Send to your friend');
define('LNG_Form_SendFriend_YourName', 'Your Name : ');
define('LNG_Form_SendFriend_YourEmailAddress', 'Your Email Address : ');
define('LNG_Form_SendFriend_FriendsName', 'Your Friends Name : ');
define('LNG_Form_SendFriend_FriendsEmailAddress', 'Your Friends Email Address : ');
define('LNG_Form_SendFriend_Introduction', 'Hey, I found this really interesting newsletter that I thought you might like to read for yourself.');

/**
**************************
* Changed/added in NX1.0.7
**************************
*/
define('LNG_FormContentsHaveChanged', 'Warning - the form has been changed. New HTML code has been generated for this form. <a href="index.php?Page=Forms&Action=View&id=%d" target="_blank">View the old html code.</a>');
?>
