<?php
/**
* Language file variables for the autoresponders area.
*
* @see GetLang
*
* @version     $Id: autoresponders.php,v 1.26 2007/05/28 07:13:29 scott Exp $

*
* @package SendStudio
* @subpackage Language
*/

/**
* Here are all of the variables for the autoresponders area... Please backup before you start!
*/

define('LNG_AutorespondersManage', 'Manage Autoresponders');
define('LNG_Help_AutorespondersManage', 'Use the form below to review, edit and delete autoresponders.<br/> To create a new autoresponder, click on the "Create Autoresponder" button below.');

define('LNG_NoAutoresponders', 'No autoresponders have been created.%s');
define('LNG_AutoresponderCreate', '&nbsp;Click the "Create Autoresponder" button below to create one.');
define('LNG_AutoresponderAssign', '&nbsp;Please talk to the system administrator to assign you a mailing list.');

define('LNG_Autoresponder_Step1', 'Manage Autoresponders');
define('LNG_Autoresponder_Step1_Intro', 'To get started, please choose a mailing list to work with.');
define('LNG_Autoresponder_Step1_CancelPrompt', 'Are you sure you want to cancel managing your autoresponders?');

define('LNG_Autoresponders_Step2', LNG_Autoresponder_Step1);

define('LNG_CreateAutoresponder', 'Create Autoresponder');
define('LNG_CreateAutoresponder_Step1_Intro', 'Choose a mailing list to create an autoresponder for. Autoresponders created will be sent to users that subscribe to this mailing list.');

define('LNG_CreateAutoresponderButton', 'Create Autoresponder');
define('LNG_CreateAutoresponderIntro', 'Complete the form below to create a new autoresponder.');
define('LNG_CreateAutoresponderCancelButton', 'Are you sure you want to cancel creating a new autoresponder?');
define('LNG_CreateAutoresponderHeading', 'New Autoresponder Details');

define('LNG_CreateAutoresponderIntro_Step4', 'Enter the content of your autoresponder in the form below. Click the "Save & Exit" button when you are finished.');

define('LNG_EditAutoresponderIntro_Step4', 'Please update your content below. Click the "Save & Exit" button when you are finished.');

define('LNG_AutoresponderDetails', 'Autoresponder Details');

define('LNG_EditAutoresponder', 'Edit Autoresponder');
define('LNG_EditAutoresponderIntro', 'Complete the form below to edit the autoresponder.');
define('LNG_EditAutoresponderCancelButton', 'Are you sure you want to cancel updating this autoresponder?');
define('LNG_EditAutoresponderHeading', 'Autoresponder Details');

define('LNG_EnterAutoresponderName', 'Please enter a name for this autoresponder.');
define('LNG_PleaseEnterAutoresonderSubject', 'Please enter a subject for this autoresponder.');

define('LNG_AutoresponderName', 'Autoresponder Name');
define('LNG_HLP_AutoresponderName', 'Enter a name for this autoresponder. This name will be used to identify the autoresponder in the control panel and will not be shown to your subscribers.');

define('LNG_AutoresponderIncludeExisting', 'Existing Subscribers');
define('LNG_HLP_AutoresponderIncludeExisting', 'Choose this option to send the autoresponder to both new and existing subscribers for the selected mailing list.<br/>This option will not be remembered next time you edit this autoresponder. It will only add the existing subscribers to the list of recipients for the next time autoresponders are sent.');
define('LNG_AutoresponderIncludeExistingExplain', 'Yes, send to existing subscribers');

define('LNG_AutoresponderNameIsNotValid', 'Autoresponder Name is not Valid');
define('LNG_UnableToCreateAutoresponder', 'Unable to create autoresponder');
define('LNG_AutoresponderCreated', 'Your autoresponder email has been created successfully');

define('LNG_DeleteAutoresponderPrompt', 'Are you sure you want to delete this autoresponder?');

define('LNG_UnableToUpdateAutoresponder', 'Unable to update autoresponder');
define('LNG_AutoresponderUpdated', 'Autoresponder updated successfully');

define('LNG_AutoresponderDeleteFail', 'Unable to delete the autoresponder');
define('LNG_AutoresponderDeleteSuccess', 'Autoresponder deleted successfully');

define('LNG_AutoresponderFormat', 'Autoresponder Format');
define('LNG_HLP_AutoresponderFormat', 'What format should this autoresponder be?');
define('LNG_AutoresponderContent', 'Enter your autoresponder content below');

define('LNG_AutoresponderCopySuccess', 'Autoresponder was copied successfully.');
define('LNG_AutoresponderCopyFail', 'Autoresponder was not copied successfully.');

define('LNG_AutoresponderSubject', 'Email Subject');

define('LNG_HLP_AutoresponderSubject', 'The subject of the autoresponder email. You can include custom fields in the subject simply by copying the placeholder and placing it in the text box.');

define('LNG_Autoresponder_Edit_Disabled', 'You cannot edit this autoresponder, you do not have access.');
define('LNG_Autoresponder_Copy_Disabled', 'You cannot copy this autoresponder, you do not have access.');
define('LNG_Autoresponder_Delete_Disabled', 'You cannot delete this autoresponder, you do not have access.');

define('LNG_UnableToLoadAutoresponder', 'Unable to load autoresponder. Please try again.');

define('LNG_MatchEmail', 'Match Email');
define('LNG_HLP_MatchEmail', 'Autoresponders will only be sent to subscribers that match this email address. You can specify all or part of an email address. For example, to send to all hotmail email addresses, you can use \\\'@hotmail.com\\\'. To send to all email addresses, simply leave this blank.');

define('LNG_MatchFormat', 'Match Format');
define('LNG_HLP_MatchFormat', 'Autoresponders will only be sent to subscribers that have selected this subscription format. If you select \\\'HTML\\\' then this autoresponder will only be sent out to users that have selected \\\'HTML\\\' as their preferred format when subscribing to your mailing list.');

define('LNG_MatchConfirmedStatus', 'Match Confirmed Status');
define('LNG_HLP_MatchConfirmedStatus', 'Autoresponders will only be sent to subscribers that have confirmed their email subscription. When using double-optin subscription, your subscribers will be sent an email to confirm their subscription. If they have confirmed their subscription, then their status will be \\\'confirmed\\\'. It\\\'s usually best to only email confirmed subscribers.');

define('LNG_CreateAutoresponderIntro_Step3', 'Complete the form below to create a new autoresponder.');
define('LNG_EditAutoresponderIntro_Step3', 'Edit Autoresponder');

define('LNG_SendMultipart', 'Send Multipart');
define('LNG_HLP_SendMultipart', 'Sending a multipart email will let the subscribers email program decide which format (HTML or Text) to display the email in.<br/><br/>It is best to use this if you don\\\'t give your subscribers a choice to which format they receive (e.g. they all subscribe as HTML), when they receive the email their email software (eg. Outlook) will automatically show the correct format.<br/><br/>If unsure, leave this option ticked.');
define('LNG_SendMultipartExplain', 'Yes, send the email as multipart');

define('LNG_TrackOpens', 'Track Email Opens');
define('LNG_HLP_TrackOpens', 'Do you want to track opening of emails when a subscriber receives an email campaign? If so, you will be able to view reports from the statistics tab at the top of the page. This applies to HTML newsletters only.');
define('LNG_TrackOpensExplain', 'Yes, track opening of HTML emails');

define('LNG_TrackLinks', 'Track Links');
define('LNG_HLP_TrackLinks', 'Do you want to track all link clicks in this email campaign? If so, you will be able to view reports on link clicks from the statistics tab at the top of the page.');
define('LNG_TrackLinksExplain', 'Yes, track all links in this email campaign');

define('LNG_EmailFormat', 'Email Format');
define('LNG_HLP_EmailFormat', 'How will this autoresponder be composed and sent? Select HTML if you want to include colored text, images, tables, etc. Choose text to create and send your autoresponder in plain-text. Alternatively, you can choose \\\'Both HTML and Text\\\' to create 2 versions of your autoresponder. Subscribers who can view HTML will see the HTML version. Those that can\\\'t will see the plain-text version only.');

define('LNG_AutoresponderFile', 'Autoresponder File');
define('LNG_HLP_AutoresponderFile', 'Upload a html file from your computer to use as your autoresponder');
define('LNG_UploadAutoresponder', 'Upload');
define('LNG_AutoresponderFileEmptyAlert', 'Please choose a file from your computer before trying to upload it.');
define('LNG_AutoresponderFileEmpty', 'Please choose a file from your computer before trying to upload it.');

define('LNG_AutoresponderURL', 'Autoresponder URL');
define('LNG_HLP_AutoresponderURL', 'Import a autoresponder from a url');
define('LNG_ImportAutoresponder', 'Import');
define('LNG_AutoresponderURLEmptyAlert', 'Please enter a url to import the autoresponder from');
define('LNG_AutoresponderURLEmpty', 'Please enter a url to import the autoresponder from');

define('LNG_AutoresponderCronNotEnabled', 'Cron support has not been enabled. This is required to run Autoresponders. <a href="resources/tutorials/cron_intro.html" target="_blank">Please enable cron (in the system settings) and setup cron on your server</a> (or contact your administrator). This message will disappear once cron support has been enabled and the system has detected a successful cron job running successfully.');

define('LNG_AutoresponderActivatedSuccessfully', 'The selected autoresponder is now active.');
define('LNG_AutoresponderDeactivatedSuccessfully', 'The selected autoresponder is no longer active.');

define('LNG_Autoresponder_Title_Enable', 'Enable this autoresponder');
define('LNG_Autoresponder_Title_Disable', 'Disable this autoresponder');

define('LNG_ChooseAutoresponders', 'Choose some autoresponders first.');
define('LNG_ActivateAutoresponders', 'Activate');
define('LNG_DeactivateAutoresponders', 'Deactivate');

define('LNG_Autoresponder_Approved', '1 autoresponder has been approved successfully');
define('LNG_Autoresponders_Approved', '%s autoresponders have been approved successfully');

define('LNG_Autoresponder_NotApproved', '1 autoresponder was not approved. Please try again.');
define('LNG_Autoresponders_NotApproved', '%s autoresponders were not approved. Please try again.');

define('LNG_Autoresponder_Disapproved', '1 autoresponder has been disapproved successfully');
define('LNG_Autoresponders_Disapproved', '%s autoresponders have been disapproved successfully');

define('LNG_Autoresponder_NotDisapproved', '1 autoresponder was not disapproved. Please try again.');
define('LNG_Autoresponders_NotDisapproved', '%s autoresponders were not disapproved. Please try again.');

define('LNG_Autoresponder_Deleted', '1 autoresponder was deleted successfully');
define('LNG_Autoresponders_Deleted', '%s autoresponders were deleted successfully');

define('LNG_Autoresponder_NotDeleted', '1 autoresponder was not deleted. Please try again.');
define('LNG_Autoresponders_NotDeleted', '%s autoresponders were not deleted. Please try again.');

define('LNG_Autoresponder_Details', 'Autoresponder Details');

define('LNG_AutoresponderHasBeenDisabled', 'To prevent an incomplete autoresponder from being sent to subscribers, it has been marked as inactive.<br>To activate this autoresponder, click on the \'X\' in the active column.');
define('LNG_AutoresponderHasBeenDisabled_Save', 'To prevent an incomplete autoresponder from being sent to subscribers, it has been marked as inactive.<br>You will need to activate this autoresponder when you go to the "Manage Autoresponders" page.');

define('LNG_Autoresponder_OpenedNewsletter', LNG_OpenedNewsletter);
define('LNG_Autoresponder_YesFilterByOpenedNewsletter', LNG_YesFilterByOpenedNewsletter);
define('LNG_HLP_Autoresponder_OpenedNewsletter', 'This option will allow you to filter subscribers who have opened a particular email campaign sent to this mailing list. If selected, only subscribers who have opened the chosen email will be sent this autoresponder. To send to all subscribers, leave this option unticked.');

define('LNG_Autoresponder_ClickedOnLink', LNG_ClickedOnLink);
define('LNG_Autoresponder_YesFilterByLink', LNG_YesFilterByLink);
define('LNG_HLP_Autoresponder_ClickedOnLink', 'This option will allow you to filter subscribers who have clicked a particular link in an email campaign sent to this mailing list. If selected, only subscribers who have clicked the chosen link will be sent this autoresponder. To send to all subscribers, leave this option unticked.');

define('LNG_ChooseATime', 'Choose a time');
define('LNG_1Day', '1 Day');
define('LNG_2Days', '2 Days');
define('LNG_3Days', '3 Days');
define('LNG_4Days', '4 Days');
define('LNG_5Days', '5 Days');
define('LNG_6Days', '6 Days');
define('LNG_1Week', '1 Week');
define('LNG_2Weeks', '2 Weeks');
define('LNG_3Weeks', '3 Weeks');
define('LNG_1Month', '1 Month');
define('LNG_2Months', '2 Months');
define('LNG_3Months', '3 Months');
define('LNG_4Months', '4 Months');
define('LNG_5Months', '5 Months');
define('LNG_6Months', '6 Months');
define('LNG_7Months', '7 Months');
define('LNG_8Months', '8 Months');
define('LNG_9Months', '9 Months');
define('LNG_10Months', '10 Months');
define('LNG_11Months', '11 Months');
define('LNG_1Year', '1 Year');
define('LNG_2Years', '2 Years');
define('LNG_3Years', '3 Years');

/**
**************************
* Changed/added in NX1.0.5
**************************
*/
define('LNG_HoursDelayed', 'Hours After Subscription');
define('LNG_HLP_HoursDelayed', 'When subscribing to your mailing list, after how many hours should each subscriber receive this autoresponder? Enter 0 so they receive it right away.');

define('LNG_HTMLFormatDetails','HTML Format Details');
define('LNG_TemplateDetails','Template Details');

/**
**************************
* Changed/added in NX1.1.1
**************************
*/
define('LNG_AutoresponderFilesCopyFail', 'The images and/or attachments for this autoresponder were not copied successfully.');
?>
