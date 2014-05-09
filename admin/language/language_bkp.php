<?php
/**
* Language file variables. These are used all over the place - menus, paging, searching, templates, newsletters and so on.
*
* @see GetLang
*
* @version     $Id: language.php,v 1.91 2007/06/22 02:39:16 chris Exp $

*
* @package SendStudio
* @subpackage Language
*/

/**
* Here are the general language variables. These are used all over the place - menus, custom fields, paging, searching, templates etc etc.
*/
ini_set('display_errors', true);
error_reporting(E_ALL);

define('LNG_EmailAddress', 'Email');

define('LNG_ControlPanel', "Painel de Controle");
define('LNG_Users', "Usu&aacute;rios");
define('LNG_Statistics', "Estat&iacute;sticas");
define('LNG_Forms', 'Formul&aacute;rios Website');
define('LNG_Settings', "Configura&ccedil;&otilde;es");
define('LNG_Go', 'Ir');
define('LNG_ShowHelp', 'Mostrar Ajuda');
define('LNG_HideHelp', 'Ocultar Ajuda');
define('LNG_Logout', 'Sair');
define('LNG_To', 'Para');
define('LNG_From', 'De');
define('LNG_N/A', 'N/A');

define('LNG_Action', 'A&ccedil;&atilde;o');

define('LNG_ViewAll', 'Ver Todos');

define('LNG_Page', 'P&aacute;gina');
define('LNG_Of', 'de');

define('LNG_GoToFirst', 'Ir Para Primeira P&aacute;gina');
define('LNG_GoToLast', 'Ir Para &Uacute;ltima P&aacute;gina');

define('LNG_UnsubLinkPlaceholder', 'Cancelar minha assinatura desta lista de email');

define('LNG_Delete_Selected', 'Apagar Selecionados');

define('LNG_NumberFormat_Dec', '.');
define('LNG_NumberFormat_Thousands', ',');
define('LNG_DateFormat', 'd M Y');
define('LNG_Quickstats_DateFormat', 'd-M-Y');
define('LNG_TimeFormat', 'F j Y, g:i a');
define('LNG_UserDateHeader', 'A hora atual do seu sistema &eacute;: %s - (%s)');
define('LNG_UserDateFormat', 'g:i a, d M Y');
define('LNG_Stats_TimeFormat', 'g:i a');

define('LNG_Yesterday_Date', 'Ontem');
define('LNG_Today_Date', 'Hoje');
define('LNG_Tomorrow_Date', 'Amanh&atilde;');

define('LNG_Yesterday_Time', 'Ontem @ %s');
define('LNG_Today_Time', 'Hoje @ %s');
define('LNG_Tomorrow_Time', 'Amanh&atilde; @ %s');

define('LNG_ViewingResultsFor', 'Visualizando resultados para');

define('LNG_Home', "Home");

define('LNG_Either_Confirmed', 'Ambos confirmados e n&atilde;o confirmados');
define('LNG_Either_Format', 'Qualquer Formato');
define('LNG_Either_Status', 'Qualquer Status');

define('LNG_Total', "Total");

define('LNG_Yes', 'Sim');
define('LNG_No', 'N&atilde;o');

define('LNG_ShowMore', 'Mostrar Mais');
define('LNG_HideMore', 'Ocultar');

define('LNG_Created', 'Criado');
define('LNG_Date_Created', 'Data de Cria&ccedil;&atilde;o');
define('LNG_Subscribers', 'Assinantes');

define('LNG_Mon', 'Segunda');
define('LNG_Tue', 'Ter&ccedil;a');
define('LNG_Wed', 'Quarta');
define('LNG_Thu', 'Quinta');
define('LNG_Fri', 'Sexta');
define('LNG_Sat', 'S&aacute;bado');
define('LNG_Sun', 'Domingo');

define('LNG_Jan', 'Jan');
define('LNG_Feb', 'Fev');
define('LNG_Mar', 'Mar');
define('LNG_Apr', 'Abr');
define('LNG_May', 'Mai');
define('LNG_Jun', 'Jun');
define('LNG_Jul', 'Jul');
define('LNG_Aug', 'Ago');
define('LNG_Sep', 'Set');
define('LNG_Oct', 'Out');
define('LNG_Nov', 'Nov');
define('LNG_Dec', 'Dez');

define('LNG_HoldMouseOver', "Segure o mouse sobre o texto sublinhado para mais informa&ccedil;&otilde;es");

define('LNG_PagingNext', "Pr&oacute;ximo");
define('LNG_PagingBack', "Voltar");

define('LNG_Next', "Next >>");
define('LNG_Back', "Back");
define('LNG_ResultsPerPage',"Results per page");

define('LNG_ErrCouldntLoadTemplate', 'Unable to load template: %s');
define('LNG_PageTitle', 'Control Panel');
define('LNG_Status', 'Status');
define('LNG_Edit', 'Edit');
define('LNG_Delete', 'Delete');
define('LNG_Save', 'Save');
define('LNG_SaveAndExit', 'Save And Exit');
define('LNG_Cancel', 'Cancel');
define('LNG_Copy', 'Copy');

define('LNG_Password', 'Password');
define('LNG_PasswordConfirm', 'Password (Confirm)');
define('LNG_PasswordConfirmAlert', 'Please confirm your new password');
define('LNG_PasswordsDontMatch', 'Your passwords don\'t match. Please try again.');

define('LNG_GoBack', 'Go Back');
define('LNG_NoAccess', 'Permission denied. You do not have access to this area or to perform the action requested. Please contact your administrator.');

define('LNG_ConfirmCancel', 'Are you sure you want to cancel?');

define('LNG_NoLists', 'No mailing lists are available. %s');
define('LNG_ListCreate', '&nbsp;Click the "Create Mailing List" button below to create one.');
define('LNG_ListAssign', '&nbsp;Please contact your system administrator to assign you a mailing list.');
define('LNG_CreateListButton', 'Create Mailing List');

define('LNG_MyAccount', 'My Account');

define('LNG_Format', 'Format');

define('LNG_Format_Text', 'Text');
define('LNG_Format_HTML', 'HTML');
define('LNG_Format_TextAndHTML', 'HTML and Text');
define('LNG_FilterFormat', LNG_Format);
define('LNG_HLP_FilterFormat', 'This option will allow you to search for subscribers who are subscribed in a particular format. To search for all subscribers, leave this option set to \\\'Either Format\\\'');

define('LNG_HTMLContent', 'HTML Content');
define('LNG_TextContent', 'Text Content');

define('LNG_HTMLPreview', 'HTML Preview');
define('LNG_TextPreview', 'Text Preview');

define('LNG_Step1', 'Step 1');
define('LNG_Step2', 'Step 2');
define('LNG_Step3', 'Step 3');
define('LNG_Step4', 'Step 4');

define('LNG_CustomFieldRequired_Popup', '* ');
define('LNG_PopupCloseWindow', '[ x Close ]');

define('LNG_View', 'View');

define('LNG_Menu_MailingLists_Manage', 'Manage&nbsp;Mailing&nbsp;Lists');
define('LNG_Menu_MailingLists_Create', 'Create&nbsp;Mailing&nbsp;List');
define('LNG_Menu_MailingLists_CustomFields', 'Manage&nbsp;Custom&nbsp;Fields');
define('LNG_Menu_MailingLists_Title', 'Create, manage and delete your mailing lists.');
define('LNG_Menu_MailingLists_Bounce', 'Process Bounced Emails');

define('LNG_Menu_Members_Manage', 'Manage&nbsp;Subscribers');
define('LNG_Menu_Members_Import', 'Import&nbsp;Subscribers');
define('LNG_Menu_Members_Export', 'Export&nbsp;Subscribers');
define('LNG_Menu_Members_Add', 'Add&nbsp;Subscriber');
define('LNG_Menu_Members_Remove', 'Remove&nbsp;Subscribers');
define('LNG_Menu_Members_Banned_Manage', 'Manage&nbsp;Banned&nbsp;Emails');
define('LNG_Menu_Members_Banned_Add', 'Add&nbsp;Banned&nbsp;Email');
define('LNG_Menu_Members_Title', 'Create, manage and delete subscribers.');

define('LNG_Menu_Templates_Create', 'Create Templates');
define('LNG_Menu_Templates_Manage', 'Manage Templates');
define('LNG_Menu_Templates_Title', 'Create, manage and delete your templates.');
define('LNG_Menu_Templates_Manage_BuiltIn', 'Built In Templates');
define('LNG_Templates_BuiltIn', LNG_Menu_Templates_Manage_BuiltIn);
define('LNG_Templates_User', 'User Templates');

define('LNG_Menu_Newsletters_Create', 'Create Email Campaign');
define('LNG_Menu_Newsletters_Manage', 'Manage Email Campaigns');
define('LNG_Menu_Newsletters_Send', 'Send Email Campaign');
define('LNG_Menu_Newsletters_ManageSchedule', 'Manage Email Scheduling');
define('LNG_Menu_Newsletters_Title', 'Create, manage and delete your email campaigns.');

define('LNG_Menu_Statistics_Title', 'View Statistics');
define('LNG_Menu_Statistics_Newsletters', 'Email Campaign Statistics');
define('LNG_Menu_Statistics_Users', 'User Statistics');
define('LNG_Menu_Statistics_Lists', 'Mailing List Statistics');
define('LNG_Menu_Statistics_Autoresponders', 'Autoresponder Statistics');

define('LNG_Menu_Autoresponders_Title', 'Create, manage and delete your autoresponders.');
define('LNG_Menu_Autoresponders_Manage', 'Manage Autoresponders');
define('LNG_Menu_Autoresponders_Create', 'Create Autoresponder');

define('LNG_RSS', 'RSS');

define('LNG_Subscriber_Count_Many', ' (%s subscribers)');
define('LNG_Subscriber_Count_One', ' (1 subscriber)');

define('LNG_Email', 'Email Address');
define('LNG_HLP_Email', 'Email Address');

define('LNG_FilterEmailAddress', LNG_Email);
define('LNG_HLP_FilterEmailAddress', 'This option will allow you to search for subscribers with a particular domain name or part of their email address. To search for all subscribers, leave this option empty.');

define('LNG_ConfirmedStatus', 'Confirmation Status');
define('LNG_FilterConfirmedStatus', LNG_ConfirmedStatus);
define('LNG_HLP_FilterConfirmedStatus', 'This option will allow you to search for subscribers based on whether they have confirmed their subscription or not. To search for all subscribers, leave this option set to \\\'Both Confirmed and Unconfirmed\\\'');

define('LNG_Active', 'Active');
define('LNG_Inactive', 'Inactive');

define('LNG_Confirmed', 'Confirmed');
define('LNG_Unconfirmed', 'Unconfirmed');

define('LNG_UnableToOpenFile', 'Unable to open file \'%s\'');
define('LNG_EmptyFile', 'File \'%s\' is empty');

define('LNG_FilterSearch', 'Search Criteria');
define('LNG_Subscribers_Search_Step2', 'Find subscribers that match the search criteria below. Leave the options blank to locate all subscribers.');

define('LNG_Copyright', 'Interspire SendStudio NX &copy; 2007 Interspire</a>');

define('LNG_OK', 'OK');

define('LNG_Preview', 'Preview');
define('LNG_SelectTemplate', 'Please select a template to preview.');
define('LNG_ChooseTemplate', 'Email Template');
define('LNG_HLP_ChooseTemplate', 'Choose a pre-designed email template as the basis of your email campaign. To create a new template, use the template menu at the top of the page.');

define('LNG_Preview_Template', 'Preview Selected Template');
define('LNG_Template_Preview', 'Template Preview');

define('LNG_SelectAll', 'Select All');
define('LNG_UnselectAll', 'Unselect All');

define('LNG_PleaseChooseAction', 'Please choose an action first.');
define('LNG_ConfirmSubscriberChanges', 'Are you sure you want to perform the selected action? It cannot be undone.');
define('LNG_ChangeFormat_Text', 'Change to Text Format');
define('LNG_ChangeFormat_HTML', 'Change to HTML Format');
define('LNG_BulkAction', 'Bulk Action');
define('LNG_ChooseAction', 'Choose an action');

define('LNG_ConfirmChanges', 'Are you sure you want to perform the selected action? It cannot be undone.');
define('LNG_NextButton', 'Next &raquo;');

define('LNG_FileNotUploadedSuccessfully', 'File was not uploaded successfully. Please try again.');
define('LNG_FileNotUploadedSuccessfully_TooBig', 'File was not uploaded successfully. It may be too large to upload through your browser.');

define('LNG_None', 'None');

define('LNG_CopyPrefix', 'Copy of '); // this is used for lists, templates and newsletters.

define('LNG_MergePrefix', 'Merge of '); // this is used for lists.

define('LNG_Bounced', 'Bounced');
define('LNG_Unsubscribed', 'Unsubscribed');
define('LNG_AllStatus', 'Any Status');

define('LNG_Attachments', 'Attachments');
define('LNG_HLP_Attachments', 'To add an attachment, click the browse button and select a file from your computer. Once you\\\'ve selected a file, you can then add another attachment and so on. You can upload 5 attachments at any one time.');
define('LNG_ExistingAttachments', 'Existing Attachments:');

define('LNG_UnableToCreateDirectory', 'Unable to create directory to save attachments. Please check your server permissions or contact your administrator.');

define('LNG_FileUploadSuccessful_One', 'Successfully uploaded one file.');
define('LNG_FileUploadSuccessful_Many', 'Successfully uploaded %s files.');
define('LNG_FileUploadFailure', 'Unable to upload the following files:');
define('LNG_FileExtensionNotValid', 'File extension %s is not allowed to be uploaded.');
define('LNG_NotUploadedFile', 'This file was not uploaded through the browser.');
define('LNG_FileTooBig', 'File is too big (%s). Must be less than %s');
define('LNG_UnableToUploadFile', 'Unable to upload the file.');
define('LNG_DirectoryNotWritable', 'The destination directory \'%s\' is not writable. Please check the permissions on that directory and try again or contact your administrator.');

define('LNG_HLP_DeleteAttachment', 'Check the box to delete this attachment. The attachment will be deleted after you have saved your work. This cannot be undone.');
define('LNG_DeleteAttachment', 'Delete Attachment? ');

define('LNG_FileDeleteSuccessful_One', 'Successfully deleted one file.');
define('LNG_FileDeleteSuccessful_Many', 'Successfully deleted %s files.');
define('LNG_FileDeleteFailure', 'Unable to delete the following files:');
define('LNG_FileNotFound', 'File not found');

define('LNG_ChangeStatus_Active', 'Change Status (Active)');
define('LNG_ChangeStatus_Inactive', 'Change Status (Inactive)');

define('LNG_ChangeStatus_Confirm', 'Change Status (Confirmed)');
define('LNG_ChangeStatus_Unconfirm', 'Change Status (Unconfirmed)');

define('LNG_Status_Active', 'Active');
define('LNG_Status_Inactive', 'Inactive');
define('LNG_Status_Confirmed', 'Confirmed');
define('LNG_Status_Unconfirmed', 'Unconfirmed');

define('LNG_FilterStatus', LNG_Status);
define('LNG_HLP_FilterStatus', 'This option will allow you to search for subscribers based on their status on the mailing list.<br/>Active subscribers are those who have not bounced and have not unsubscribed from the mailing list.<br/>The \\\'bounced\\\' status is for those who have been disabled on the mailing list because they have had too many messages bounce from their email address, or have been detected as a hard bounce.<br/>The \\\'unsubscribed\\\' status is for those who have specifically unsubscribed from the mailing list.<br/><br/>To search for all subscribers, set this option set to \\\'Any Status\\\'');

// used in forms.
define('LNG_MailingLists', 'Mailing Lists');

define('LNG_NoTemplate', 'No Template');

define('LNG_Global', 'Global');

// used anywhere to do with lists.
define('LNG_MailingListDetails', 'Mailing List Details');
define('LNG_MailingList', 'Mailing List');
define('LNG_CustomFields', 'Custom Fields');
define('LNG_CustomFields_Manage', 'Manage Custom Fields');
define('LNG_HLP_MailingList', 'To get started, please choose a mailing list to work with. You can also select a mailing list by double clicking on an option.');

define('LNG_SelectList', 'Please select a mailing list before continuing.');

// used for preview emails.
define('LNG_SendPreview', 'Send Preview');
define('LNG_HLP_SendPreview', 'Enter your email address and click the \\\'Send Preview\\\' button to receive a copy of this email.<br/><br/>If you have uploaded new attachments, they will not be included as part of the preview email.');

define('LNG_EnterPreviewEmail', 'Please enter an email address.');
define('LNG_NoContentToEmail', 'No content has been entered, so no preview email has been sent.');
define('LNG_NoEmailAddressSupplied', 'No email address was supplied. Please try again.');

define('LNG_PreviewEmailSent', 'A preview has been sent to the email address %s.');
define('LNG_Preview_CustomFieldsNotAltered', '<b>Please note:</b> Custom fields, unsubscribe links and recently selected attachments will not show up in this preview because they are subscriber specific.<br><br>To test your emails with custom fields, attachments and unsubscribe links, create a test mailing list with yourself as a subscriber and send the email to that list.');

define('LNG_Send', 'Send');
define('LNG_Resume', 'Resume');
define('LNG_Pause', 'Pause');

define('LNG_DefaultUnsubscribeFooter_html', '<br/><a href="%%UNSUBSCRIBELINK%%">Click here to unsubscribe</a>');
// need to use " so \n gets processed correctly.
define('LNG_DefaultUnsubscribeFooter_text', "\nClick this link to unsubscribe: %%UNSUBSCRIBELINK%%");

define('LNG_DefaultModifyFooter_html', '<br/><a href="%%MODIFYLINK%%">Click here to update your details</a>');
// need to use " so \n gets processed correctly.
define('LNG_DefaultModifyFooter_text', "\nClick this link to update your details: %%MODIFYLINK%%");

define('LNG_TimeTaken_Seconds_One', '1 second');
define('LNG_TimeTaken_Seconds_Many', '%s seconds');

define('LNG_TimeTaken_Minutes_One', '1 minute');
define('LNG_TimeTaken_Minutes_Many', '%s minutes');

define('LNG_TimeTaken_Hours_One', '1 hour');
define('LNG_TimeTaken_Hours_One_Minutes', '1 hour, %s minutes');
define('LNG_TimeTaken_Hours_Many', '%s hours');
define('LNG_TimeTaken_Hours_Many_Minutes', '%s hours, %s minutes');

define('LNG_TimeTaken_Days_One', '1 day');
define('LNG_TimeTaken_Days_One_Hours', '1 day, %s hours');
define('LNG_TimeTaken_Days_Many', '%s days');
define('LNG_TimeTaken_Days_Many_Hours', '%s days, %s hours');

define('LNG_TimeTaken_Months_One', '1 month');
define('LNG_TimeTaken_Months_One_Days', '1 month, %s days');
define('LNG_TimeTaken_Months_Many', '%s months');
define('LNG_TimeTaken_Months_Many_Days', '%s months, %s days');

define('LNG_TimeTaken_Years_One', '1 year');
define('LNG_TimeTaken_Years_Many', '%s years');

define('LNG_CronNotEnabled', 'Cron sending has not been enabled. Please speak to your administrator about setting this up.');

define('LNG_CronNotSetup', 'You have enabled cron support, but the system has not yet detected a successful cron job running. <a href="resources/tutorials/cron_intro.html" target="_blank">Please make sure cron has been setup on your server and is running correctly</a>. This message will disappear once the system detects that cron job has run successfully.');

define('LNG_Custom', 'Custom');

define('LNG_ShowCustomFields', 'Insert Custom Fields');
define('LNG_InsertUnsubscribeLink', 'Insert Unsubscribe Link');

define('LNG_Approve', 'Approve');
define('LNG_Approved', 'Approved');
define('LNG_Disapprove', 'Disapprove');
define('LNG_Disapproved', 'Disapproved');

define('LNG_NewsletterSubject', 'Email Subject');
define('LNG_Subject', 'Subject');
define('LNG_Name', 'Name');

define('LNG_YesFilterByCustomDate', 'Yes, filter by field \'%s\'');

define('LNG_AlreadySentTo_Heading', 'Last Send Information');
define('LNG_AlreadySentTo_SoFar', 'Sent to %s / %s so far');

// used by "manage schedule" page.
define('LNG_AlreadySentTo', ' (Sent to %s / %s)');

define('LNG_ShowFilteringOptions', 'Show Filtering Options');
define('LNG_ShowFilteringOptionsExplain', 'Yes, show filtering options on the next page');
define('LNG_HLP_ShowFilteringOptions', 'Tick this option to show filtering options on the next screen. Filtering options let you locate subscribers that match search criteria, such as specific email addresses or domain name. You can also filter based on custom fields.');

/**
* Common custom field stuff.
* This is used by searching, exporting.
*/
define('LNG_FilterByDate', 'Filter By Date');
define('LNG_YesFilterByDate', 'Yes, filter by date subscribed');
define('LNG_After', 'After');
define('LNG_Before', 'Before');
define('LNG_Between', 'Between');
define('LNG_Exactly', 'Exactly');
define('LNG_AND', 'AND');
define('LNG_HLP_FilterByDate', 'This option will allow you to filter subscribers who have subscribed before, after or between particular dates. To search for all subscribers, leave this option unticked.');


define('LNG_ExportFileDeleted', 'Export file deleted successfully.');
define('LNG_ExportFileNotDeleted', 'Export file not deleted successfully. Please try again.');

/**
* Jobs
*/
define('LNG_Waiting', 'Waiting');
define('LNG_Job_Waiting', 'Sending in');
define('LNG_Job_Complete', 'Complete');
define('LNG_Job_InProgress', 'In Progress');
define('LNG_Job_Paused', 'Paused');
define('LNG_WaitingToSend', 'Waiting to send'); // this is used if 2 cron jobs have not run yet, so we can't work out the time difference.
define('LNG_ImapSupportMissing', 'IMAP support is not available. Bounced emails cannot be processed without IMAP support.');


define('LNG_AnyList', '-- All Lists --');

/**
* Subscriber stuff.
*/
define('LNG_UserChooseFormat', 'Choose Format');
define('LNG_Unknown', 'Unknown');
define('LNG_SubscribeRequestDate', 'Subscriber Request Date');
define('LNG_HLP_SubscribeRequestDate', 'The date and time that this subscriber requested to join this mailing list.');
define('LNG_SubscribeRequestIP', 'Subscriber Request IP');
define('LNG_HLP_SubscribeRequestIP', 'The I.P. address of the computer from which this subscriber requested to join this mailing list.');
define('LNG_SubscribeConfirmDate', 'Subscriber Confirm Date');
define('LNG_HLP_SubscribeConfirmDate', 'The date and time that this subscriber confirmed his request to join this mailing list.');
define('LNG_SubscribeConfirmIP', 'Subscriber Confirm IP');
define('LNG_HLP_SubscribeConfirmIP', 'The I.P. address of the computer from which this subscriber confirmed joining this mailing list.');

define('LNG_Subscriber_NotSubscribed', 'The email address \'%s\' is not subscribed to this list');

define('LNG_NoSubscribersOnList', 'There are no subscribers on the list(s) you selected.');
define('LNG_NoSubscribersMatch', 'No subscribers match your search criteria. Please try again.');

define('LNG_ViewSchedule', 'View Sending Schedule');

/**
* Handles importing / uploading of a template / newsletter / autoresponder.
*/
define('LNG_UploadedFileEmpty', 'Uploaded file is empty. Please try again.');
define('LNG_UploadedFileBad', 'Unable to upload file. Please try again.');
define('LNG_UploadFileTooBig', 'Unable to upload file. It is too large. Please try a smaller file.');

define('LNG_UploadedFileCantBeRead', 'Unable to read uploaded file. Please try again.');
define('LNG_URLIsEmpty', 'URL is empty. Please try again.');
define('LNG_URLCantBeRead', 'Unable to fetch url. Please make sure it is valid and then try again.');
define('LNG_NoCurlOrFopen', 'Unfortunately your server cannot open remote files.<br/>Please ask your system administrator to enable curl or remote fopen support.');

/**
* Used for the settings page and the users page.
*/
define('LNG_UseSMTP', 'Use SMTP Server');
define('LNG_UseSMTPExplain', 'Yes, use an SMTP server');
define('LNG_HLP_UseSMTP', 'Check this option to specify an external smtp server. If unchecked, the default php mail system will be used to send emails.');

define('LNG_SmtpServer', 'SMTP Server');
define('LNG_HLP_SmtpServer', 'Choose to use the default SMTP server specified from the settings page, or choose to use a custom SMTP server for this client.');
define('LNG_SmtpDefault', 'Use default SMTP server');
define('LNG_SmtpCustom', 'Specify a custom SMTP server');
define('LNG_SmtpServerIntro', 'Mail Server Details');
define('LNG_SmtpServerName', 'SMTP Hostname');
define('LNG_HLP_SmtpServerName', 'Enter the SMTP host name here, such as &quot;192.168.0.50&quot; or &quot;mail.mysite.com&quot;');
define('LNG_SmtpServerUsername', 'SMTP Username');
define('LNG_HLP_SmtpServerUsername', 'If your SMTP server requires authentication, enter the username here.');
define('LNG_SmtpServerPassword', 'SMTP Password');
define('LNG_HLP_SmtpServerPassword', 'If your SMTP server requires authentication, enter the password here.');
define('LNG_SmtpServerPort', 'SMTP Port');
define('LNG_HLP_SmtpServerPort', 'To use a non-standard SMTP port (the default is 25), enter it here.');

define('LNG_OverLimit_MaxEmails', 'You have gone over the number of emails you are allowed to send.<br/>Please restrict who you are sending to, or speak to your administrator about increasing this limit.');
define('LNG_OverLimit_PerMonth', 'You have gone over the number of emails you are allowed to send.<br/>Please restrict who you are sending to, or speak to your administrator about increasing this limit.');

/**
* Used by the email class and testsmtp scripts.
*/
define('LNG_UnableToConnectToEmailServer', 'Unable to connect to mail server: %s');
define('LNG_UnableToSendEmail_MailFrom', 'Unable to set mail from address.');
define('LNG_UnableToSendEmail_RcptTo', 'Unable to set receipt to address.');
define('LNG_UnableToSendEmail_Data', 'Unable to set data.');
define('LNG_UnableToSendEmail_DataWriting', 'Unable to send data.');
define('LNG_UnableToSendEmail_DataFinished', 'Unable to send "." to server.');
define('LNG_UnableToConnectToMailServer_EHLO', 'Unable to send "EHLO" command.');
define('LNG_UnableToConnectToMailServer_RequiresAuthentication', 'Server requires authentication but no username or password has been set.');
define('LNG_UnableToConnectToMailServer_AuthLogin', 'Unable to send "auth login" to server.');
define('LNG_UnableToConnectToMailServer_AuthLoginNotSupported', 'Unable to authenticate with server. Doesn\'t support "AUTH LOGIN"');
define('LNG_UnableToConnectToMailServer_UsernameNotWritten', 'Unable to authenticate with server. Username not written.');
define('LNG_UnableToConnectToMailServer_PasswordNotWritten', 'Unable to authenticate with server. Password not written.');

/**
* Used with newsletters, templates, autoresponders, form creation
*/
define('LNG_ImportWebsite', 'Import');

define('LNG_HTML_Using_Editor', 'Create content using the WYSIWYG editor below');
define('LNG_Editor_Upload_File', 'Upload a file from my hard drive');
define('LNG_Editor_Import_Website', 'Import a file from a web site');
define('LNG_Editor_Use_URL', 'Use an existing URL');
define('LNG_Text_Type', 'Type text into the text box below');
define('LNG_Editor_Import_File_Wait', 'Importing file, please wait...');
define('LNG_Editor_Import_Website_Wait', 'Importing website, please wait...');
define('LNG_Editor_ProblemImportingWebsite', 'There was a problem importing from the specified url. Please try again.');
define('LNG_Editor_ChooseFileToUpload', 'Please choose a file to upload');
define('LNG_Editor_ChooseWebsiteToImport', 'Please type in the full url of the website you want to import.');
define('LNG_Editor_ImportButton', 'Import');

define('LNG_SendNewsletterButton', 'Send Email Campaign');

/**
* Used for sending and autoresponders
*/
define('LNG_EmbedImages', 'Embed Images');
define('LNG_HLP_EmbedImages', 'This will embed the images from the content inside the email the subscribers receive. This may make the email significantly larger but will allow subscribers to view the content offline.');
define('LNG_EmbedImagesExplain', 'Yes, embed images in the content');

define('LNG_SendTo_FirstName', 'Send To First Name');
define('LNG_HLP_SendTo_FirstName', 'If you have a custom field for the \\\'first name\\\' of the subscriber, choose it here so the newsletter can be addressed to the person individually.<br/>If you have a combined custom field for the persons name (that is, just one field called \\\'name\\\') then choose that custom field here.');

define('LNG_SendTo_LastName', 'Send To Last Name');
define('LNG_HLP_SendTo_LastName', 'If you have a custom field for the \\\'last name\\\' of the subscriber, choose it here so the newsletter can be addressed to the person individually.<br/>If you have a combined custom field for the persons name (that is, just one field called \\\'name\\\') then leave this option empty.');

define('LNG_SelectNameOption', 'Please select your "name" custom field');

// used all over the place with newsletters
define('LNG_CreateNewsletterButton', 'Create Email Campaign');
define('LNG_NoNewsletters', 'No email campaigns have been created.%s');
define('LNG_NoNewsletters_HasAccess', ' Please click the "Create Email Campaign" button to create one.');

// used by autoresponders & stats
define('LNG_SentWhen', 'Sent');
define('LNG_Immediately', 'Immediately after signup');
define('LNG_HoursAfter_One', '1 hour after signup');
define('LNG_HoursAfter_Many', '%s hours after signup');

define('LNG_ClickedOnLink', 'Clicked On Link');
define('LNG_YesFilterByLink', 'Yes, filter by link');
define('LNG_LoadingMessage', 'Loading, please wait...');
define('LNG_FilterAnyLink', 'Any Link');
define('LNG_HLP_ClickedOnLink', 'This option will allow you to filter subscribers who have clicked on a particular link from an email campaign or autoresponder sent to this mailing list. To search for all subscribers, leave this option unticked.');

define('LNG_OpenedNewsletter', 'Opened Email Campaign');
define('LNG_YesFilterByOpenedNewsletter', 'Yes, filter by opened email campaign');
define('LNG_FilterAnyNewsletter', 'Any Email Campaign');
define('LNG_HLP_OpenedNewsletter', 'This option will allow you to filter subscribers who have opened a particular email campaign or autoresponder sent to this mailing list. To search for all subscribers, leave this option unticked.');

define('LNG_UnableToOpenPopupWindow', 'Error: Could not open required web browser window. Please check that you have disabled your popup blocker and you don\'t have Norton internet security, ZoneAlarm or any other \'security\' script that could be blocking the web browser window from opening and then try again.');


define('LNG_NoUnsubscribeLinkInHTMLContent', 'No unsubscribe link was found in the html version of your email. It is recommended you add one so subscribers can easily remove themselves from your mailing list.');

define('LNG_NoUnsubscribeLinkInTextContent', 'No unsubscribe link was found in the text version of your email. It is recommended you add one so subscribers can easily remove themselves from your mailing list.');


/**
* used by sending and forms.
*/
define('LNG_SendFromName', 'Send From Name');
define('LNG_HLP_SendFromName', 'Which person or company should be shown in the \\\'From Name\\\' field for this email?');

define('LNG_SendFromEmail', 'Send From Email');
define('LNG_HLP_SendFromEmail', 'Which email address should be shown in the \\\'From Email\\\' field for this email?');

define('LNG_ReplyToEmail', 'Reply-To Email');
define('LNG_HLP_ReplyToEmail', 'When a subscriber receives your email and clicks reply, which email address should that reply be sent to?');

define('LNG_BounceEmail', 'Bounce Email');
define('LNG_HLP_BounceEmail', 'When an email bounces, or is rejected by the mail server, which email address should the error be sent to? If you plan to use the bounce handler, then make sure no other emails will be sent to this address.');

/**
* Searching custom fields.
*/
define('LNG_Filter_Number', 'Filter Number Field');
define('LNG_HLP_Filter_Number', 'To restrict filtering of this field, you can use >, = and <. For example, to search for subscribers who are under 25, enter < 25.');

define('LNG_Filter_Checkbox', 'Filter Checkbox Field');
define('LNG_HLP_Filter_Checkbox', 'To restrict filtering of this field, tick the options you want to search for.');

define('LNG_Filter_Date', 'Filter Date Field');

define('LNG_Filter_Dropdown', 'Filter Dropdown Field');
define('LNG_HLP_Filter_Dropdown', 'To filter searching of this field, choose an option you want to search for.');

define('LNG_Filter_Radiobutton', 'Filter Radio Button Field');
define('LNG_HLP_Filter_Radiobutton', 'To filter searching of this field, choose an option you want to search for.');

define('LNG_Filter_Text', 'Filter Text Field');
define('LNG_HLP_Filter_Text', 'To filter searching of this field, type in some text that should appear. This will be found in any of the text, this does not do an exact match.');

define('LNG_Link_MailingListArchives', 'Link to Mailing List Archives');
define('LNG_Link_WebVersion', 'Web Version of Email');
define('LNG_Link_Unsubscribe', 'Unsubscribe Link');


define('LNG_SendingSystem', 'Sendstudio');
define('LNG_SendingSystem_From', 'email@domain.com');
define('LNG_UserLimitReached', 'You have reached your maximum number of users and cannot create any more.');

define('LNG_User_OverQuota_Email', 'Hi,
This email is to notify you that user \'%s\' (email address %s) has scheduled a newsletter to go out on %s. They have gone over their %s limit by %s email(s).

%s

You can email them by clicking "Reply" in your email program.
');
define('LNG_User_OverLimit_MaxEmails', 'maximum number of emails');
define('LNG_User_OverLimit_PerMonth', 'maximum number of emails per month');
define('LNG_User_OverQuota_StoppedSend', 'The send has been disapproved.');
define('LNG_User_OverQuota_Subject', 'User over-quota notification');

define('LNG_User_OverQuota_ToUser_Email', 'Hi,
This email is to notify you that your send, scheduled to go out on %s has gone over your %s limit by %s email(s).

%s

You can email the administrator by clicking "Reply" in your email program.
');

define('LNG_User_OverQuota_ToUser_Subject', 'Over-quota notification');

define('LNG_RunningVersion', 'You are running version ');

/**
**************************
* Changed/Added in NX1.0.5
**************************
*/

/**
* these MUST be space separated.
* This is only used to display the date/time options when sending a newsletter
* and has to be in the correct format and only contain the 3 options mentioned:
* - 'd' for day
* - 'm' for month
* - 'y' for year
* The order does not matter but the values do.
*/
define('LNG_DateTimeBoxFormat', 'd m y');

/**
**************************
* Changed/Added in NX1.0.7
**************************
*/
define('LNG_HLP_Filter_Date', 'Choose the date or date range to filter subscribers.<br/>To search between months, put * as the year.<br/>This only works when searching between dates.');

define('LNG_User_Total_CreditsLeft', '<br/>You have %s email credits left in total');
define('LNG_User_Monthly_CreditsLeft', '<br/>You have %s email credits left in %s');


/**
**************************
* Changed/Added in NX1.1.3
**************************
*/
define('LNG_PreviewEmailNotSent', 'A preview couldn\'t be sent to the email address %s: %s');

?>
