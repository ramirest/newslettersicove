<?php
/**
* Language file variables for the newsletters area. (Now referred to as email campaigns)
*
* @see GetLang
*
* @version     $Id: newsletters.php,v 1.23 2007/05/28 06:57:15 scott Exp $

*
* @package SendStudio
* @subpackage Language
*/

/**
* Here are all of the variables for the newsletters area... Please backup before you start!
*/
define('LNG_CreateNewsletter', 'Create Email Campaign');
define('LNG_CreateNewsletterIntro', 'Enter the details of the email campaign in the form below.');
define('LNG_CreateNewsletterCancelButton', 'Are you sure you want to cancel creating a new email campaign?');
define('LNG_CreateNewsletterHeading', 'Email Campaign Details');

define('LNG_CreateNewsletterIntro_Step2', 'Complete the form below to create an email campaign. When you are done, click on the \'Save\' button.');
define('LNG_Newsletter_Details', 'Email Campaign Details');

define('LNG_EditNewsletter', 'Update Email Campaign');
define('LNG_EditNewsletterIntro', 'Complete the form below to update the email campaign.');
define('LNG_EditNewsletterCancelButton', 'Are you sure you want to cancel updating this email campaign?');
define('LNG_EditNewsletterHeading', 'Email Campaign Details');

define('LNG_EditNewsletterIntro_Step2', 'Please update your content below. Hit "Save" when you are finished.');

define('LNG_NewslettersManage', 'Manage Email Campaigns');
define('LNG_Help_NewslettersManage', 'Use the form below to review, edit and delete email campaigns.');
define('LNG_Help_NewslettersManage_HasAccess', ' To create an email campaign, click on the "Create Email Campaign" button below.');
define('LNG_EnterNewsletterName', 'Please enter an email campaign name');

define('LNG_NewsletterName', 'Email Campaign Name');

define('LNG_NewsletterNameIsNotValid', 'Email Campaign name is not Valid');
define('LNG_UnableToCreateNewsletter', 'Unable to create an email campaign');
define('LNG_NewsletterCreated', 'The new email campaign has been saved successfully');

define('LNG_HLP_NewsletterName', 'The name of the email campaign. This is for your reference only and will not be included in the email when its sent out.');

define('LNG_UnableToUpdateNewsletter', 'Unable to update email campaign');
define('LNG_NewsletterUpdated', 'Email campaign updated successfully');

define('LNG_NoNewslettersToDelete', 'No email campaigns have been selected. Please try again.');
define('LNG_Newsletter_NotDeleted', 'Unable to delete the selected email campaign');
define('LNG_Newsletters_NotDeleted', 'Unable to delete the %s selected email campaigns');
define('LNG_Newsletter_Deleted', '1 email campaign has been deleted successfully');
define('LNG_Newsletters_Deleted', '%s email campaigns have been deleted successfully');

define('LNG_Newsletter_NotDeleted_SendInProgress', 'Unable to delete email campaign \'%s\' - it is currently being sent.');
define('LNG_Newsletters_NotDeleted_SendInProgress', 'Unable to delete the following email campaigns - \'%s\' - they are currently being sent.');

define('LNG_NoNewslettersToAction', LNG_NoNewslettersToDelete);
define('LNG_InvalidNewsletterAction', 'Invalid email campaign action. Please try again.');

define('LNG_Newsletter_NotApproved', 'Unable to approve the selected email campaign');
define('LNG_Newsletters_NotApproved', 'Unable to approve the %s selected email campaigns');
define('LNG_Newsletter_Approved', '1 email campaign has been activated successfully');
define('LNG_Newsletters_Approved', '%s email campaigns have been activated successfully');

define('LNG_Newsletter_NotDisapproved', 'Unable to deactivate the selected email campaign');
define('LNG_Newsletters_NotDisapproved', 'Unable to deactivate the %s selected email campaigns');
define('LNG_Newsletter_Disapproved', '1 email campaign has been deactivated successfully');
define('LNG_Newsletters_Disapproved', '%s email campaigns have been deactivated successfully');

define('LNG_Newsletter_NotArchived', 'Unable to archive the selected email campaign');
define('LNG_Newsletters_NotArchived', 'Unable to archive the %s selected email campaigns');
define('LNG_Newsletter_Archived', '1 email campaign has been archived successfully');
define('LNG_Newsletters_Archived', '%s email campaigns have been archived successfully');

define('LNG_Newsletter_NotUnarchived', 'Unable to unarchive the selected email campaign');
define('LNG_Newsletters_NotUnarchived', 'Unable to unarchive the %s selected email campaigns');
define('LNG_Newsletter_Unarchived', '1 email campaign has been unarchived successfully');
define('LNG_Newsletters_Unarchived', '%s email campaigns have been unarchived successfully');

define('LNG_NewsletterFormat', 'Email Campaign Format');
define('LNG_NewsletterContent', 'Enter your email campaign content below');

define('LNG_NewsletterCopySuccess', 'Email campaign was copied successfully.');
define('LNG_NewsletterCopyFail', 'Email campaign was not copied successfully.');

// newslettersubject is in language.php
define('LNG_PleaseEnterNewsletterSubject', 'Please enter the email campaign subject.');
define('LNG_HLP_NewsletterSubject', 'The subject line of the email. For most email clients, they will see the subject line before they see the content of the email.<br /><br />You can include custom fields in the subject line by clicking on the \\\'Insert Custom Fields\\\' link below the editor and copy/pasting them into the subject text box.');

define('LNG_Newsletter_Send_Disabled_Inactive', 'You cannot send this email campaign because it is inactive.');
define('LNG_Newsletter_Send_Disabled', 'You cannot send this email campaign, you do not have access.');
define('LNG_Newsletter_Edit_Disabled', 'You cannot edit this email campaign, you do not have access.');
define('LNG_Newsletter_Copy_Disabled', 'You cannot copy this email campaign, you do not have access.');
define('LNG_Newsletter_Delete_Disabled', 'You cannot delete this email campaign, you do not have access.');
define('LNG_Newsletter_Delete_Disabled_SendInProgress', 'You cannot delete a email campaign while it is being sent.');

define('LNG_Archive', 'Archive');

define('LNG_DeleteNewsletterPrompt', 'Are you sure you want to delete this email campaign?');

define('LNG_ArchiveNewsletters', 'Archive');
define('LNG_UnarchiveNewsletters', 'Unarchive');
define('LNG_ApproveNewsletters', 'Activate');
define('LNG_DisapproveNewsletters', 'Deactivate');

define('LNG_Newsletter_Title_Enable', 'Enable this email campaign');
define('LNG_Newsletter_Title_Disable', 'Disable this email campaign');

define('LNG_Newsletter_Title_Archive_Enable', 'Enable archiving this email campaign');
define('LNG_Newsletter_Title_Archive_Disable', 'Disable archiving this email campaign');

define('LNG_NewsletterArchive', 'Archive Email Campaign');
define('LNG_NewsletterArchiveExplain', 'Yes, archive this email campaign');
define('LNG_HLP_NewsletterArchive', 'Should this email campaign be archived? If so, it will appear in the archives for the mailing list it is being sent to. You can then publish the archives on your website so your website visitors can read them ');

define('LNG_NewsletterIsActive', 'Activate Email Campaign');
define('LNG_NewsletterIsActiveExplain', 'Yes, this email campaign is active');
define('LNG_HLP_NewsletterIsActive', 'Should this email campaign be marked as active? Inactive email campaigns cannot be sent to a mailing list and must be approved first.');

define('LNG_NewsletterCannotBeInactiveAndArchive', 'This email will not be included in your archive as it has been deactivated. Once it has been reactivated it will be included in your archive.');

define('LNG_UnableToLoadNewsletter', 'Unable to load email campaign. Please try again.');

define('LNG_NewsletterFile', 'Email Campaign File');
define('LNG_HLP_NewsletterFile', 'Upload a html file from your computer to use as your email campaign');
define('LNG_UploadNewsletter', 'Upload');
define('LNG_NewsletterFileEmptyAlert', 'Please choose a file from your computer before trying to upload it.');
define('LNG_NewsletterFileEmpty', 'Please choose a file from your computer before trying to upload it.');

define('LNG_NewsletterURL', 'Email Campaign URL');
define('LNG_HLP_NewsletterURL', 'Import an email campaign from a url');
define('LNG_NewsletterURLEmptyAlert', 'Please enter a URL to import the email campaign from');
define('LNG_NewsletterURLEmpty', 'Please enter a URL to import the email campaign from');

define('LNG_NewsletterActivatedSuccessfully', 'Email campaign has been activated successfully');
define('LNG_NewsletterDeactivatedSuccessfully', 'Email campaign has been deactivated successfully');

define('LNG_NewsletterArchive_ActivatedSuccessfully', 'Email campaign has been archive activated successfully');
define('LNG_NewsletterArchive_DeactivatedSuccessfully', 'Email campaign has been archive deactivated successfully');

define('LNG_ChooseNewsletters', 'Please choose one or more email campaigns first.');

define('LNG_MiscellaneousOptions', 'Miscellaneous Options');

define('LNG_LastSent', 'Last Sent');
define('LNG_NotSent', 'Not Sent');

define('LNG_Newsletter_Edit_Disabled_SendInProgress', 'You cannot edit an email campaign while it is being sent');
define('LNG_Newsletter_ChangeActive_Disabled_SendInProgress', 'You cannot change this status while this email campaign is being sent');

/**
**************************
* Changed/added in NX1.0.7
**************************
*/
define('LNG_HLP_NewsletterFormat', 'In what format would you like to create this email? Select Text to create a plain text newsletter. Choose HTML to include links and graphics. Choose HTML and Text to create both a text and HTML version. If you are unsure, you should send a HTML and Text format of your email.');

/**
**************************
* Changed/added in NX1.1.1
**************************
*/
define('LNG_NewsletterFilesCopyFail', 'The images and/or attachments for this email campaign were not copied successfully.');

?>
