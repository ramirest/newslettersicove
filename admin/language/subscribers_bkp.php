<?php
/**
* Language file variables for the subscribers area (including adding, importing, removing, exporting, managing).
*
* @see GetLang
*
* @version     $Id: subscribers.php,v 1.45 2007/04/29 23:53:13 chris Exp $

*
* @package SendStudio
* @subpackage Language
*/

/**
* Here are all of the variables for the subscribers area... Please backup before you start!
*/

define('LNG_Subscribers_Manage', 'Manage Subscribers');
define('LNG_Subscribers_Manage_Intro', 'To get started, please choose a mailing list to work with.');
define('LNG_Subscribers_Manage_CancelPrompt', 'Are you sure you want to cancel managing subscribers?');

define('LNG_Subscribers_Add', 'Add Subscribers');
define('LNG_Subscribers_Add_Step1', LNG_Subscribers_Add);
define('LNG_Subscribers_Add_Step1_Intro', 'Choose a mailing list to add a subscriber to.');
define('LNG_Subscribers_Add_CancelPrompt', 'Are you sure you want to cancel adding a new subscriber?');

define('LNG_Subscribers_EnterEmailAddress', 'Please enter an email address for this subscriber.');
define('LNG_ChooseValueForCustomField', 'Choose a value for custom field \'%s\'');
define('LNG_EnterValueForCustomField', 'Enter a value for custom field \'%s\'');
define('LNG_ChooseOptionForCustomField', 'Choose an option for custom field \'%s\'');

define('LNG_Subscribers_Add_Step2', 'Add Subscriber');
define('LNG_Subscribers_Add_Step2_Intro', 'Complete the form below to add a single subscriber to your mailing list.');
define('LNG_NewSubscriberDetails', 'New Subscriber Details');

define('LNG_SubscriberAddFail', 'Subscriber was not added successfully');
define('LNG_SubscriberAddFail_Duplicate', 'A subscriber with the email address \'%s\' already exists.');
define('LNG_SubscriberAddFail_Unsubscribed', 'A subscriber with the email address \'%s\' has unsubscribed from this mailing list. To reactivate, edit the subscriber and change their status to "Active".');
define('LNG_SubscriberAddFail_Banned', 'The email address \'%s\' is banned from joining this mailing list.');
define('LNG_SubscriberAddFail_InvalidEmailAddress', 'A subscriber with email address \'%s\' cannot be added to this list. It is an invalid email address.');

define('LNG_SubscriberAddSuccessful', 'Subscriber added successfully.');
define('LNG_SubscriberAddSuccessfulList', 'Subscriber added to mailing list \'%s\' successfully.');

define('LNG_Subscribers_Remove_Heading', 'Remove Subscribers');
define('LNG_RemoveOptions', 'Remove Options');
define('LNG_HLP_RemoveOptions', 'What do you want to happen to the list of email addresses?<br><br/>Choose \\\'Delete from List\\\' to remove them from the mailing list completely.<br/><br/>Choose \\\'Unsubscribe\\\' to move them to the unsubscribe list.');
define('LNG_EnterEmailAddressesToRemove', 'Please enter some email addresses to remove or choose a file to upload');

define('LNG_Unsubscribe', 'Unsubscribe');

define('LNG_Subscribers_Remove', 'Remove Subscribers');
define('LNG_Subscribers_Remove_Intro', 'Choose a mailing list to remove subscribers from.');
define('LNG_Subscribers_Remove_CancelPrompt', 'Are you sure you want to cancel removing subscribers?');

define('LNG_Subscribers_Remove_Step2', 'Remove Subscribers');
define('LNG_Subscribers_Remove_Step2_Intro', 'Choose removal options using the form below.');

define('LNG_RemoveEmails', 'Remove Emails');
define('LNG_HLP_RemoveEmails', 'Type or paste the list of email addresses that you want to remove here. You should put each email address on a new line.<br><br/>Use this option if you have a small number of email addresses to remove.');

define('LNG_RemoveFile', 'Remove File');
define('LNG_HLP_RemoveFile', 'Choose a file to upload that contains the email addresses to remove. The file should contain one email address per line.');

define('LNG_EmptyRemoveList', 'The file that you uploaded contains no email addresses.');

define('LNG_MassUnsubscribeFailed', 'The following email addresses were unable to be unsubscribed or deleted:<br/>');
define('LNG_MassUnsubscribeSuccessful', '%s email addresses were removed from the list successfully');
define('LNG_MassUnsubscribeSuccessful_Single', '1 email address was removed from the list successfully');

define('LNG_Subscribers_RemoveMore', 'Remove More Subscribers');

define('LNG_Subscribers_FoundOne', 'Your search returned 1 subscriber. Details are shown below.');
define('LNG_Subscribers_FoundMany', 'Your search returned %s subscribers. They are shown below.');
define('LNG_SubscribersManage', 'Manage Subscribers For List \'%s\'');
define('LNG_SubscribersManageAnyList', 'Manage Subscribers');
define('LNG_Help_SubscribersManage', 'Use the form below to manage your subscribers.');
define('LNG_SubscriberEmailaddress', 'Email Address');
define('LNG_DateSubscribed', 'Subscribed');
define('LNG_SubscriberFormat', 'Format');
define('LNG_DeleteSubscriberPrompt', 'Are you sure you want to delete this subscriber?');

define('LNG_NoSubscribersToDelete', 'No subscribers to delete. Please try again.');

define('LNG_Subscriber_Deleted', '1 subscriber was deleted successfully');
define('LNG_Subscribers_Deleted', '%s subscribers were deleted successfully');

define('LNG_Subscriber_NotDeleted', '1 subscriber was not deleted.');
define('LNG_Subscribers_NotDeleted', '%s subscribers were not deleted.');

define('LNG_SubscriberStatus', 'Status');
define('LNG_SubscriberConfirmed', 'Confirmed');

define('LNG_NoSubscribersToChangeFormat', 'There are no subscribers to change email formats for.');

define('LNG_Subscriber_NotChangedFormat', '1 subscriber was not changed to receive emails in %s format.');
define('LNG_Subscribers_NotChangedFormat', '%s subscribers were not changed to receive emails in %s format.');

define('LNG_Subscriber_ChangedFormat', '1 subscriber was changed to receive emails in %s format.');
define('LNG_Subscribers_ChangedFormat', '%s subscribers were changed to receive emails in %s format.');

define('LNG_NoSubscribersToChangeStatus', 'There are no subscribers to change status.');

define('LNG_Subscriber_NotChangedStatus', '1 subscriber was not changed to status %s');
define('LNG_Subscribers_NotChangedStatus', '%s subscribers were not changed to status %s');

define('LNG_Subscriber_ChangedStatus', '1 subscriber was changed to status %s');
define('LNG_Subscribers_ChangedStatus', '%s subscribers were changed to status %s');

define('LNG_NoSubscribersToChangeConfirm', 'There are no subscribers to change confirmation status.');

define('LNG_Subscriber_NotChangedConfirm', '1 subscriber was not changed to confirmation status \'%s\'.');
define('LNG_Subscribers_NotChangedConfirm', '%s subscribers were not changed to confirmation status \'%s\'.');

define('LNG_Subscriber_ChangedConfirm', '1 subscriber was changed to confirmation status \'%s\'.');
define('LNG_Subscribers_ChangedConfirm', '%s subscribers were changed to confirmation status \'%s\'.');

define('LNG_Subscribers_Edit', 'Edit Subscriber');
define('LNG_Subscribers_Edit_Intro', 'Modify the details of the subscriber in the form below and click on the \'Save\' button.');
define('LNG_Subscribers_Edit_CancelPrompt', 'Are you sure you want to cancel editing this subscriber?');
define('LNG_EditSubscriberDetails', 'Edit Subscriber Details');

define('LNG_SubscriberAddFail_InvalidData', 'Invalid data was entered for the custom field \'%s\'.');
define('LNG_SubscriberAddFail_EmptyData_ChooseOption', '\'%s\' is a required field. Please choose an option.');
define('LNG_SubscriberAddFail_EmptyData_EnterData', '\'%s\' is a required field. Please fill in the field below.');

define('LNG_SubscriberEditFail_Duplicate', 'Someone is already subscribed to this list with the email address \'%s\'.');
define('LNG_SubscriberEditSuccess', 'The selected subscriber was updated successfully');
define('LNG_SubscriberEditFail', 'Unable to update subscriber information. Please try again.');
define('LNG_SubscriberEditFail_InvalidData', 'Invalid data was entered for the custom field \'%s\'.');
define('LNG_ChooseSubscribers', 'Please choose at least one subscriber first.');

define('LNG_Save_AddAnother', 'Save & Add Another');

define('LNG_UnsubscribeTime', 'Unsubscribe Time');
define('LNG_HLP_UnsubscribeTime', 'When the subscriber unsubscribed from the mailing list.');
define('LNG_UnsubscribeIP', 'Unsubscribe IP');
define('LNG_HLP_UnsubscribeIP', 'The ip address of the subscriber when they unsubscribed from the mailing list.');

define('LNG_HLP_ConfirmedStatus', 'The confirmed option is usually used for the double-optin process where users confirm there subscription by clicking a link in a confirmation email. If you select unconfirmed you can send the subscribers an email at a later date which contains a confirmation link to make sure they want to be included your mailing list.');

define('LNG_HLP_Format', 'Which email format should these subscribers be \\\'flagged\\\' to receive by default? HTML or Text? HTML subscribers can receive both HTML and Text emails, but Text subscribers can only receive Text emails.<br><br>If you are unsure, select HTML.');

define('LNG_HLP_SubscriberStatus', 'Active subscribers are those who have not bounced and have not unsubscribed from the mailing list.<br/>The \\\'bounced\\\' status is for those who have been disabled on the mailing list because they have had too many messages bounce from their email address, or have been detected as a hard bounce.<br/>The \\\'unsubscribed\\\' status is for those who have specifically unsubscribed from the mailing list.');

/**
* Import Subscriber language variables.
*/
define('LNG_Subscribers_Import', 'Import Subscribers');
define('LNG_Subscribers_Import_Intro', 'Choose a mailing list to import subscribers into.');
define('LNG_Import_From_file', 'File');
define('LNG_Subscribers_Import_Step2', 'Import Subscribers');
define('LNG_Subscribers_Import_Step2_Intro', 'Specify import settings in the form below. Click the \'Next\' button to continue..');
define('LNG_ImportTutorialLink', 'For a tutorial on how to import subscribers, click here.');
define('LNG_ImportType', 'Import Type');
define('LNG_ImportDetails', 'Import Details');
define('LNG_HLP_ImportType', 'How will you be importing your list of subscribers?');
define('LNG_Subscribers_Import_CancelPrompt', 'Are you sure you want to cancel importing subscribers?');
define('LNG_ImportStatus', 'Status');
define('LNG_HLP_ImportStatus', 'When these subscribers are imported, what should their status be?');
define('LNG_ImportConfirmedStatus', 'Confirmed');
define('LNG_HLP_ImportConfirmedStatus', 'Should imported subscribers be marked as confirmed? The confirmed option is usually used for the double-optin process where users confirm there subscription by clicking a link in a confirmation email. If you select unconfirmed you can send the subscribers an email at a later date which contains a confirmation link to make sure they want to be included your mailing list.');

define('LNG_Subscribers_Import_Step3', 'Import Subscribers');
define('LNG_Subscribers_Import_Step3_Intro', 'Use the form below to define which import fields should map to which subscriber fields.');
define('LNG_ImportFormat', 'Format');
define('LNG_HLP_ImportFormat', 'Which email format should these subscribers be \\\'flagged\\\' to receive by default? HTML or Text? HTML subscribers can receive both HTML and Text emails, but Text subscribers can only receive Text emails. If your import file contains a field to specify formatting, it will override this setting.<br><br>If you are unsure, select HTML.');

define('LNG_OverwriteExistingSubscriber', 'Overwrite Existing');
define('LNG_YesOverwriteExistingSubscriber', 'Yes, overwrite existing subscribers');
define('LNG_HLP_OverwriteExistingSubscriber', 'If a subscriber already exists in the current mailing list with the same email address, should we overwrite their current details?');

define('LNG_IncludeAutoresponder', 'Autoresponders');
define('LNG_YesIncludeAutoresponder', 'Yes, add subscribers to autoresponders');
define('LNG_HLP_IncludeAutoresponder', 'Should we add the subscribers to any autoresponders that have already been setup for the mailing list to which they\\\'re being subscribed?');

define('LNG_ImportFileDetails', 'File Details');
define('LNG_ContainsHeaders', 'Contains Headers');
define('LNG_YesContainsHeaders', 'Yes, this file contains headers');
define('LNG_HLP_ContainsHeaders', 'Does the first line of your import file contain headers? If so, each header should be separated with a field separator, such as:<br><br>Email, Name, Sex.');
define('LNG_FieldSeparator', 'Field Separator');
define('LNG_EnterFieldSeparator', 'Please enter a field separator');

define('LNG_HLP_FieldSeparator', 'What is the character used in your import file that separates the contents of each new field in a record?<br/>If you wish to use the tab character enter the word &quot;TAB&quot; here.');
define('LNG_FieldEnclosed', 'Field Enclosure');
define('LNG_HLP_FieldEnclosed', 'Which character is each field enclosed by? All fields must have this character around them. For example, a record might look like this:<br><br>&quot;test@test.com&quot;, &quot;21&quot;, &quot;Male&quot;');
define('LNG_RecordSeparator', 'Record Separator');
define('LNG_HLP_RecordSeparator', 'What is the character used in your import file that separates one record from the next?');
define('LNG_ImportFile', 'Import File');
define('LNG_HLP_ImportFile', 'Choose a file to upload that contains the subscriber details that you want to import. This should be a plain text file.');
define('LNG_ImportFile_FieldSeparatorEmpty', 'Please enter a field separator.');
define('LNG_ImportFile_RecordSeparatorEmpty', 'Please enter a record separator.');
define('LNG_ImportFile_FileEmpty', 'Please choose a file to import.');

define('LNG_MatchOption', 'Match option \'%s\' to field');
define('LNG_ImportFields', 'Link Import Fields');
define('LNG_EmailAddressNotLinked', 'The subscriber email address field is not linked. We cannot proceed without this being linked.');

define('LNG_MappingOption', 'Map Field');
define('LNG_HLP_MappingOption', 'Which database field does the \\\'%s\\\' field from the file relate to?');

define('LNG_Subscribers_Import_Step4', 'Import Subscribers');
define('LNG_Subscribers_Import_Step4_Intro', 'Click the "Start Import" button below to start importing your subscribers.');
define('LNG_ImportStart', 'Start Import');

define('LNG_ImportSubscribers_success_Many', '%s subscribers were imported successfully');
define('LNG_ImportSubscribers_success_One', '1 subscriber was imported successfully');

define('LNG_ImportSubscribers_updates_Many', '%s subscribers were updated successfully');
define('LNG_ImportSubscribers_updates_One', '1 subscriber was updated successfully');

define('LNG_ImportSubscribers_duplicates_Many', '%s subscribers contained duplicate email addresses');
define('LNG_ImportSubscribers_duplicates_Many_Link', '<a href="#" onclick="javascript: ShowReport(\'%s\'); return false;">%s subscribers contained duplicate email addresses</a>');
define('LNG_ImportSubscribers_duplicates_One_Link', '<a href="#" onclick="javascript: ShowReport(\'%s\'); return false;">1 subscriber contained a duplicate email address</a>');

define('LNG_InvalidSubscribeDate', ' <-- Invalid Subscribe Date');
define('LNG_InvalidCustomFieldData', ' <-- Invalid custom field data for field \'%s\'');
define('LNG_InvalidSubscriberEmailAddress', ' <-- Invalid Email Address');
define('LNG_InvalidSubscriberImportLine_NotEnough', ' <-- Missing a delimiter');
define('LNG_InvalidSubscriberImportLine_TooMany', ' <-- Too many delimiters');

define('LNG_InvalidReportURL', 'You have accessed an invalid report url. Please try again.');
define('LNG_ImportResults_Report_Invalid_Heading', 'Invalid Report URL.');
define('LNG_ImportResults_Report_Invalid_Intro', 'You have accessed an invalid url. Please close this window and try again.');

define('LNG_ImportResults_Report_Duplicates_Heading', 'Duplicate email addresses found');
define('LNG_ImportResults_Report_Duplicates_Intro', 'The following email addresses were already on your mailing list or in the file you uploaded multiple times and were not imported again.');

define('LNG_ImportResults_Report_Failures_Heading', 'Failure to import email addresses');
define('LNG_ImportResults_Report_Failures_Intro', 'The following email addresses were not able to be imported. Please try again.');

define('LNG_ImportSubscribers_failures_Many', '%s subscribers were not imported successfully');
define('LNG_ImportSubscribers_failures_Many_Link', '<a href="#" onclick="javascript: ShowReport(\'%s\'); return false;">%s subscribers were not imported successfully</a>');
define('LNG_ImportSubscribers_failures_One_Link', '<a href="#" onclick="javascript: ShowReport(\'%s\'); return false;">1 subscriber was not imported successfully</a>');

define('LNG_ImportResults_Report_Unsubscribed_Heading', 'Unsubscribed Email Addresses');
define('LNG_ImportResults_Report_Unsubscribed_Intro', 'The following email addresses were not able to be imported because they have unsubscribed from this mailing list.');

define('LNG_ImportSubscribers_unsubscribes_Many', '%s subscribers are unsubscribed');
define('LNG_ImportSubscribers_unsubscribes_Many_Link', '<a href="#" onclick="javascript: ShowReport(\'%s\'); return false;">%s subscribers are unsubscribed from this list</a>');
define('LNG_ImportSubscribers_unsubscribes_One_Link', '<a href="#" onclick="javascript: ShowReport(\'%s\'); return false;">1 subscriber is unsubscribed from this list</a>');

define('LNG_ImportResults_Report_Banned_Heading', 'Banned email addresses');
define('LNG_ImportResults_Report_Banned_Intro', 'The following email addresses were not able to be imported because they are banned from joining this mailing list.');

define('LNG_ImportSubscribers_bans_Many', '%s subscribers are banned from joining this mailing list');
define('LNG_ImportSubscribers_bans_Many_Link', '<a href="#" onclick="javascript: ShowReport(\'%s\'); return false;">%s subscribers are banned from joining this mailing list</a>');
define('LNG_ImportSubscribers_bans_One_Link', '<a href="#" onclick="javascript: ShowReport(\'%s\'); return false;">1 subscriber is banned from joining this mailing list</a>');

define('LNG_ImportResults_Heading', 'Import Subscribers');
define('LNG_ImportResults_Intro', 'The subscriber import has been completed successfully');

define('LNG_DuplicateReport', '<b>Duplicate email addresses</b>');
define('LNG_FailureReport', '<b>Unable to subscribe these email addresses</b>');

define('LNG_ImportResults_InProgress', 'Import In Progress');
define('LNG_ImportResults_InProgress_Message', 'Please wait while we attempt to import the %s subscriber record(s)...');

define('LNG_ImportSubscribers_InProgress_success_Many', '%s subscribers have been imported so far');
define('LNG_ImportSubscribers_InProgress_success_One', '1 subscriber has been imported so far');

define('LNG_ImportSubscribers_InProgress_updates_Many', '%s subscribers have been updated so far');
define('LNG_ImportSubscribers_InProgress_updates_One', '1 subscriber has been updated so far');

define('LNG_ImportSubscribers_InProgress_duplicates_Many', '%s duplicate subscribers have been found so far');
define('LNG_ImportSubscribers_InProgress_duplicates_One', '1 duplicate subscriber has been found so far');

define('LNG_ImportSubscribers_InProgress_failures_Many', '%s subscribers have not been imported so far');
define('LNG_ImportSubscribers_InProgress_failures_One', '1 subscriber has not been imported so far');

define('LNG_ImportSubscribers_InProgress_unsubscribes_Many', '%s lines contain unsubscribed email addresses so far');
define('LNG_ImportSubscribers_InProgress_unsubscribes_One', '1 line contains an unsubscribed email address so far');

define('LNG_ImportSubscribers_InProgress_bans_Many', '%s lines contain banned email addresses or domains so far');
define('LNG_ImportSubscribers_InProgress_bans_One', '1 line contains a banned email address or domain so far');


/**
* Export Subscribers.
*/
define('LNG_Subscribers_Export', 'Export Subscribers');
define('LNG_Subscribers_Export_Intro', 'Choose a mailing list to export subscribers from.');
define('LNG_Subscribers_Export_CancelPrompt', 'Are you sure you want to cancel exporting subscribers?');
define('LNG_Subscribers_Export_Step3', 'Export Subscribers');
define('LNG_Subscribers_Export_Step3_Intro', 'Select your export options to export your subscribers to a file.');
define('LNG_ExportStart', 'Start Export');
define('LNG_Subscribers_Export_FoundOne', '1 subscriber matched your search and can be exported.');
define('LNG_Subscribers_Export_FoundMany', '%s subscribers matched your search and can be exported.');
define('LNG_IncludeFields', 'Fields to Include');
define('LNG_ExportOptions', 'Export Options');

define('LNG_IncludeHeader', 'Include Field Headers?');
define('LNG_HLP_IncludeHeader', 'Should this export include field headers? If so, the first line of the file will look something like this:<br><br>Email, Status, Format');

define('LNG_FieldEnclosedBy', 'Field Enclosed By');
define('LNG_HLP_FieldEnclosedBy', 'Which character (if any) should each field be wrapped in? For example, if you enter a quote, then a sample record might look like this:<br><br>&quot;test@test.com&quot;, &quot;21&quot;, &quot;James&quot;');

define('LNG_Export_FieldSeparator', LNG_FieldSeparator);
define('LNG_HLP_Export_FieldSeparator', 'What character should be added to this export file to separate the contents of each new field in a record?');

define('LNG_ExportField', 'Field #%s');
define('LNG_SubscriberNone', 'None');
define('LNG_ExportSummary_FoundOne', 'Click the "Start Export" button below to start exporting your subscriber.');
define('LNG_ExportSummary_FoundMany', 'Click the "Start Export" button below to start exporting your %s subscribers.');
define('LNG_Subscribers_Export_Step4', 'Export Subscribers');
define('LNG_Subscribers_Export_Step4_Intro', 'Click the "Start Export" button to start exporting.');

define('LNG_ExportResults_InProgress_Message', 'Please wait while we attempt to export your %s subscriber(s).');

define('LNG_ExportResults_InProgress_Status', '%s of %s subscribers have been exported so far...');

define('LNG_ExportResults_Heading', 'Export Results');
define('LNG_ExportResults_Intro', 'The selected subscribers have been exported successfully. <a href=%s target=_blank>Click here to download the export file</a>. You should delete this file once you have finished downloading.');
define('LNG_ExportResults_Link', 'Click here to download your exported subscribers.');
define('LNG_ExportResults_InProgress', 'Exporting Subscribers');

define('LNG_SubscriberEmail', 'Email Address');

define('LNG_SubscribeDate_DMY', 'Subscribe Date (dd/mm/yyyy)');
define('LNG_SubscribeDate_MDY', 'Subscribe Date (mm/dd/yyyy)');
define('LNG_SubscribeDate_YMD', 'Subscribe Date (yyyy/mm/dd)');

define('LNG_IncludeField', 'Include this field?');
define('LNG_HLP_IncludeField', 'Do you want to include this field in the export of your mailing list? If not, set this option to \\\'None\\\'');

define('LNG_DeleteExportFile', 'Delete export file');



/**
* Now for banned subscribers.
*/
define('LNG_BannedEmailDetails', 'Banned Email/Domain Details');
define('LNG_Subscribers_Banned_Add', 'Add Banned Email');
define('LNG_Subscribers_Banned_Add_Intro', 'Use the form or upload a file below to add one or more email addresses to your banned email list. <br>Each email address should be placed on a new line. You can also ban an entire domain using @DOMAINNAME. For example, @Hotmail.com.');
define('LNG_BannedEmails', 'Email(s) to Ban');
define('LNG_HLP_BannedEmails', 'Enter the list of emails addresses to ban here. Separate each email address with a new line. If you would like to ban a whole domain, simply enter @DOMAINNAME. For example, to ban everyone at Hotmail, enter @hotmail.com.');

define('LNG_BanSingleEmail', 'Email to Ban');
define('LNG_HLP_BanSingleEmail', 'Enter the email address to ban here. If you would like to ban a whole domain, simply enter @DOMAINNAME. For example, to ban everyone at Hotmail, enter @hotmail.com.');

define('LNG_BannedEmailsChooseList', 'Ban From Mailing List');
define('LNG_HLP_BannedEmailsChooseList', 'Choose a list to ban these email addresses from.');

// we duplicate it here so we can use a different helptip.
define('LNG_BannedEmailsChooseList_Edit', LNG_BannedEmailsChooseList);
define('LNG_HLP_BannedEmailsChooseList_Edit', 'Choose the mailing list to ban this email address or domain name from.');

define('LNG_BannedFile', 'Email Ban File');
define('LNG_HLP_BannedFile', 'Choose a file to upload that contains a list of email addresses to ban. The file should contain one email address per line.');
define('LNG_Subscribers_GlobalBan', '-- Global Ban (All Lists) --');
define('LNG_Subscribers_Banned_CancelPrompt', 'Are you sure you want to cancel banning emails?');
define('LNG_Banned_Add_EmptyList', 'Please enter an email address or domain name to ban.');
define('LNG_Banned_Add_EmptyFile', 'Please select a file that contains email addresses you want to ban.');
define('LNG_Banned_Add_ChooseList', 'Please choose a list to ban these email addresses from.');
define('LNG_EmptyBannedList', 'The file that you uploaded contains no email addresses.');
define('LNG_MassBanSuccessful', '%s email addresses were successfully added to your banned email list.');
define('LNG_MassBanSuccessful_Single', '1 email address has been banned successfully');
define('LNG_MassBanFailed', '<br>An error occurred while trying to ban the following email addresses:<br/>');
define('LNG_Subscriber_AlreadyBanned', 'Email address is already banned');

define('LNG_Subscribers_Banned', 'Manage Banned Emails');
define('LNG_Subscribers_Banned_Intro', 'Choose a mailing list to find email addresses that are banned from that list. <br>The \'Global Ban\' contains email addresses that are banned from all existing and future mailing lists.');
define('LNG_Subscribers_BannedManage_CancelPrompt', 'Are you sure you want to cancel managing your banned emails?');

define('LNG_Banned_Subscribers_FoundOne', 'Found 1 banned email address.');
define('LNG_Banned_Subscribers_FoundMany', 'Found %s banned email addresses.');

define('LNG_SubscribersManageBanned', 'Manage Banned Emails (For List \'%s\')');
define('LNG_Manage_Banned_Intro', 'Use the form below to manage banned email addresses.');
define('LNG_BannedSubscriberEmail', 'Email Address');
define('LNG_Delete_Banned_Selected', 'Delete Selected');
define('LNG_BannedDate', 'Date Banned');
define('LNG_DeleteBannedSubscriberPrompt', 'Are you sure you want to remove this ban?');
define('LNG_ConfirmBannedSubscriberChanges', 'Are you sure you want to make these changes?\nThis action cannot be undone.');
define('LNG_ConfirmRemoveBannedSubscribers', 'Are you sure you want to remove these banned emails?');
define('LNG_ChooseBannedSubscribers', 'Please choose some bans to remove.');

define('LNG_BannedAddButton', 'Add Banned Email');
define('LNG_NoBannedSubscribersOnList', 'The mailing list \'%s\' does not contain any banned email addresses.');

define('LNG_Subscriber_Ban_NotDeleted_One', '1 banned email address was not deleted from the list \'%s\'.');
define('LNG_Subscriber_Ban_Deleted_One', '1 banned email was deleted successfully from list \'%s\'.');

define('LNG_Subscriber_Ban_NotDeleted_Many', '%s banned email addresses bans were not deleted from list \'%s\'.');
define('LNG_Subscriber_Ban_Deleted_Many', '%s banned email addresses were deleted successfully from list \'%s\'.');

define('LNG_Subscribers_Banned_Edit', 'Edit Banned Email');
define('LNG_Subscribers_Banned_Edit_Intro', 'Modify the details of the banned email address in the form below and click on the \'Save\' button.');
define('LNG_Subscribers_Banned_Edit_CancelPrompt', 'Are you sure you want to cancel editing this banned email address?');
define('LNG_Banned_Edit_Empty', 'Please enter an email address to ban.');
define('LNG_Banned_Edit_ChooseList', 'Please choose a list to ban this email address from.');

define('LNG_SubscriberBan_Updated', 'The banned email has been updated successfully');
define('LNG_SubscriberBan_NotUpdated', 'Banned email address has not been updated.');
define('LNG_SubscriberBan_UnableToUpdate', 'Unable to update the banned information. Please try again.');

define('LNG_SubscriberBanListEmpty', '\'%s\' has no banned email addresses');

define('LNG_Ban_Count_Many', ' (%s bans)');
define('LNG_Ban_Count_One', ' (1 ban)');

/**
**************************
* Changed/added in NX1.0.5
**************************
*/
define('LNG_ImportResults_Report_Bads_Heading', 'Bad data was found');
define('LNG_ImportResults_Report_Bads_Intro', 'The following lines with the email addresses listed below in the import file were found to contain bad data.');

define('LNG_ImportSubscribers_bads_One', '1 subscriber contains bad data');
define('LNG_ImportSubscribers_bads_Many', '%s subscribers contained bad data');
define('LNG_ImportSubscribers_bads_Many_Link', '<a href="#" onclick="javascript: ShowReport(\'%s\'); return false;">%s subscribers contained bad data</a>');
define('LNG_ImportSubscribers_bads_One_Link', '<a href="#" onclick="javascript: ShowReport(\'%s\'); return false;">1 subscriber contained bad data</a>');

define('LNG_ImportSubscribers_InProgress_bads_Many', '%s lines contain bad data so far');
define('LNG_ImportSubscribers_InProgress_bads_One', '1 line contains bad data so far');

?>
