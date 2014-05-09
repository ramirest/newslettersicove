<?php
/**
* Language file variables for the mailing lists area. This includes creating, editing, deleting, managing.
*
* @see GetLang
*
* @version     $Id: lists.php,v 1.27 2007/05/30 02:21:20 chris Exp $

*
* @package SendStudio
* @subpackage Language
*/

/**
* Here are all of the variables for the mailing lists area... Please backup before you start!
*/
define('LNG_CreateMailingList', 'Create Mailing List');
define('LNG_CreateMailingListIntro', 'Complete the form below to create a new mailing list.');
define('LNG_CreateListCancelButton', 'Are you sure you want to cancel creating a new mailing list?');
define('LNG_CreateMailingListHeading', 'New List Details');

define('LNG_EditMailingList', 'Edit Mailing List');
define('LNG_EditMailingListIntro', 'Complete the form below to update the selected mailing list.');
define('LNG_EditListCancelButton', 'Are you sure you want to cancel updating this mailing list?');
define('LNG_EditMailingListHeading', 'Mailing List Details');

define('LNG_ListsManage', 'Manage Mailing Lists');
define('LNG_Help_ListsManage', 'Use the form below to manage your lists.');
define('LNG_Help_ListsManage_HasAccess', ' To create a new mailing list, click on the "Create Mailing List" button below.');

define('LNG_EnterListName', 'Please enter a list name');
define('LNG_EnterOwnerName', 'Please enter the name of the person who owns this mailing list.');
define('LNG_EnterOwnerEmail', 'Please enter the email address of the person who owns this mailing list.');
define('LNG_EnterReplyToEmail', 'Please enter the default \'Reply-To\' address for this mailing list.');
define('LNG_EnterBounceEmail', 'Please enter the default \'Bounce\' address for this mailing list.');

define('LNG_ListName', 'List Name');
define('LNG_ListOwnerName', 'List Owners Name');
define('LNG_ListOwnerEmail', 'List Owners Email');
define('LNG_ListBounceEmail', 'List Bounce Email');
define('LNG_ListReplyToEmail', 'List Reply-To Email');

define('LNG_NotifyOwner', 'Notify List Owner');
define('LNG_NotifyOwnerExplain', 'Yes, notify the list owner');
define('LNG_HLP_NotifyOwner', 'Should we notify the list owner when someone subscribes or unsubscribes from this mailing list?');

define('LNG_ListNameIsNotValid', 'List Name is not Valid');
define('LNG_ListOwnerNameIsNotValid', 'List Owner Name is not Valid');
define('LNG_ListOwnerEmailIsNotValid', 'List Owner Email is not Valid');
define('LNG_ListBounceEmailIsNotValid', 'List Bounce Email is not Valid');
define('LNG_ListReplyToEmailIsNotValid', 'List Reply-To Email is not Valid');
define('LNG_UnableToCreateList', 'Unable to create list');
define('LNG_ListCreated', 'Your mailing list has been saved successfully');

define('LNG_DeleteListPrompt', 'Are you sure you want to delete this mailing list?');

define('LNG_HLP_ListName', 'The name of the list as it will appear both in the control panel and on your subscription forms.');
define('LNG_HLP_ListOwnerName', 'The name of the person who owns this list.');
define('LNG_HLP_ListOwnerEmail', 'The email address of the person who owns this list.');
define('LNG_HLP_ListBounceEmail', 'The email address where emails will bounce to if an invalid email address is subscribed.');
define('LNG_HLP_ListReplyToEmail', 'The email address where replies will be sent.');

define('LNG_UnableToUpdateList', 'Unable to update list');
define('LNG_ListUpdated', 'The selected mailing list has been updated successfully');

define('LNG_ListDeleteFail', 'An error occurred while trying to delete the selected mailing list.');
define('LNG_ListsDeleteFail', 'An error occurred while trying to delete the selected mailing lists.');

define('LNG_ListDeleteSuccess', 'The selected mailing list has been deleted successfully');
define('LNG_ListsDeleteSuccess', 'The selected mailing lists have been deleted successfully');

define('LNG_TooManyLists', 'You have too many lists and have reached your maximum. Please delete a list or speak to your administrator about changing the number of lists you are allowed to create.');

define('LNG_DeleteAllSubscribers', 'Delete All Subscribers');
define('LNG_DeleteAllSubscribersPrompt', 'Are you sure you want to delete all subscribers from this mailing list?');
define('LNG_ListDeleteAllSubscribersFail', 'Unable to delete all subscribers from this mailing list');
define('LNG_ListDeleteAllSubscribersSuccess', 'All subscribers from this mailing list deleted successfully');

define('LNG_ListsDeleteAllSubscribersFail', 'Unable to delete all subscribers from these mailing lists');
define('LNG_ListsDeleteAllSubscribersSuccess', 'All subscribers from these mailing list deleted successfully');

define('LNG_AllListSubscribersChangedFormat', 'All subscribers have been updated to receive email campaigns in \'%s\' format.');
define('LNG_AllListSubscribersNotChangedFormat', 'All subscribers could not been changed to receive email campaigns in \'%s\' format. Please try again.');

define('LNG_ChooseList', 'Please choose one or more lists first.');
define('LNG_ChooseMultipleLists', 'To perform this action, you need to choose more than one list.');

define('LNG_MergeLists', 'Merge Lists');

define('LNG_ListCopySuccess', 'The selected list has been copied successfully');
define('LNG_ListCopyFail', 'The selected list couldn\\\'t be copied.');

define('LNG_AllListSubscribersChangedStatus', 'All subscribers have had their status changed to status \'%s\'.');

define('LNG_AllListSubscribersChangedConfirm', 'All subscribers have had their status changed to \'%s\'.');
define('LNG_AllListSubscribersNotChangedConfirm', 'All subscribers have not had their status changed to \'%s\'.');

define('LNG_ListBounceServer', 'Bounce Email Server Name');
define('LNG_HLP_ListBounceServer', 'This is used for processing bounced emails. If you enter your email server, username and password, you can process bounces using a cron job.');

define('LNG_ListBounceUsername', 'Bounce Email User Name');
define('LNG_HLP_ListBounceUsername', 'This is used for processing bounced emails. If you enter your email server, username and password, you can process bounces using a cron job.');

define('LNG_ListBouncePassword', 'Bounce Email Password');
define('LNG_HLP_ListBouncePassword', 'This is used for processing bounced emails. If you enter your email server, username and password, you can process bounces using a cron job.');

define('LNG_IMAPAccount', 'IMAP Email Account');
define('LNG_IMAPAccountExplain', 'Yes, this is an IMAP Account');
define('LNG_HLP_IMAPAccount', 'Is the bounce email account an imap account? If it is not an imap account, it is a POP3 account.');

define('LNG_UseExtraMailSettingsExplain', 'Yes, use extra mail settings');
define('LNG_HLP_UseExtraMailSettings', 'You may need to set extra options to connect to an email account for bounce processing. If so, enable this option and fill in the required information below. If unsure, leave this unticked.');

define('LNG_ExtraMailSettings', 'Extra Mail Settings');
define('LNG_HLP_ExtraMailSettings', 'Please add any extra options that may be required to properly connect to a bounce email account. For example \\\'/notls\\\' or \\\'/nossl\\\'.');

define('LNG_MergeSuccessful', '%s lists have been merged together successfully');
define('LNG_MergeUnsuccessful', '%s lists couldn\\\'t be merged together.');
define('LNG_MergeDuplicatesRemoved_Success', 'Successfully removed %s duplicate subscriber(s) from the new merged list.');
define('LNG_MergeDuplicatesRemoved_Fail', 'Failed to remove %s duplicate subscriber(s) from the new merged list.');

define('LNG_Lists_DeleteAllSubscribers_Disabled', 'You cannot delete subscribers from this because you do not have access.');
define('LNG_ListCopyDisabled', 'You cannot copy this list because you do not have access.');
define('LNG_ListEditDisabled', 'You cannot edit this list because you do not have access.');
define('LNG_ListDeleteDisabled', 'You cannot delete this list because you do not have access.');
define('LNG_ListCopyDisabled_TooMany', 'You cannot copy this list, you have reached the maximum you can create');

define('LNG_BounceAccountDetails', 'Bounce Account Details');

define('LNG_RSS_Tip', 'Click here to view archives of email campaigns sent to this mailing list.');
define('LNG_ArchiveLists', 'Archive');

/**
**************************
* Changed/added in NX1.0.5
**************************
*/
define('LNG_UseExtraMailSettings', 'Use Extra Mail Settings');

/**
**************************
* Changed/added in NX1.1.1
**************************
*/
define('LNG_AllListSubscribersNotChangedStatus', 'All subscribers have not had their status changed to \'%s\'.');

?>
