<?php
/**
* Language file variables for the user management area.
*
* @see GetLang
*
* @version     $Id: users.php,v 1.31 2007/05/15 04:32:45 chris Exp $

*
* @package SendStudio
* @subpackage Language
*/

/**
* Here are all of the variables for the user area... Please backup before you start!
*/
define('LNG_UserDetails', 'User Details');
define('LNG_UserAdd', 'Create User');
define('LNG_UserName', 'Username');
define('LNG_FullName', 'Full Name');

define('LNG_UserType', 'User Type');

define('LNG_Help_Users', 'Use the form below to manage your user accounts. You can add a user by clicking on the &#39;Create User&#39; button below.');

define('LNG_Help_CreateUser', 'Enter the details of the new user in the form below and click "Save".');

define('LNG_EditUser', 'Edit User');
define('LNG_Help_EditUser', 'Modify the details of the user below and click "Save".');

define('LNG_Admin', 'Administrator');
define('LNG_YesIsActive','Yes, this user is active');

define('LNG_SupplyUserPassword', 'Please supply a password');
define('LNG_SupplyUserUsername', 'Please supply a username');

define('LNG_UserUpdated', 'User details updated successfully.');
define('LNG_UserNotUpdated', 'User details NOT updated successfully.');

define('LNG_UserCreated', 'User has been created successfully.');
define('LNG_UserNotCreated', 'An Error occurred. User has not been created.');
define('LNG_UserAlreadyExists', 'A user with that username already exists. Please enter a different username.');

define('LNG_EnterUsername', 'Please enter a username for this user.');
define('LNG_EnterEmailaddress', 'Please enter the users email address.');

define('LNG_DeleteUserPrompt', 'Are you sure you want to delete this user?');
define('LNG_User_CantDeleteOwn', 'You cannnot delete your own user account.');
define('LNG_UserDeleted', 'The selected user accounts have been deleted successfully.');

define('LNG_Help_MyAccount','Update your account details and click "Save" to continue.');
define('LNG_Help_DisplayMyAccount','Your details are shown below. Please contact your administrator to update any information');

define('LNG_HLP_Active', 'An inactive user will still exist in the system but will not be able to login. This can be used to temporarily suspend access to a particular user.');

define('LNG_AdministratorType', 'Administrator Type');
define('LNG_HLP_AdministratorType', 'Choose which areas this particular user has access to.<br/>You can choose from the default types of administrators or choose custom permissions.');

define('LNG_LastActiveUser', 'Unable to make this user inactive. This is the last active user');

define('LNG_LastAdminUser', 'Unable to remove admin status. This is the last admin user');

define('LNG_RegularUser', 'Regular User');

define('LNG_CreateUser', 'Create User');

define('LNG_EditOwnSettings', 'Edit Own Settings');
define('LNG_HLP_EditOwnSettings', 'Should this user be able to edit his/her own account settings? They will be able to edit everything except permissions.');
define('LNG_YesEditOwnSettings', 'Yes, let this user edit their own settings');

define('LNG_ShowInfoTips', 'Show Info Tips');
define('LNG_HLP_ShowInfoTips', 'If yes, this client will see email marketing tips across the top of the screen when they login. Each tip links to an article with more information.');
define('LNG_YesShowInfoTips', 'Yes, show info tips');

define('LNG_TimeZone', 'User Timezone');
define('LNG_HLP_TimeZone', 'The timezone where your client is located. When your client is viewing reports and stats, all times and dates will be converted to their time zone.');

define('LNG_UserRestrictions', 'User Restrictions');

define('LNG_LimitLists', 'Number of Mailing Lists');
define('LNG_LimitListsExplain', 'Unlimited mailing lists');
define('LNG_HLP_LimitLists', 'Tick this option to set the maximum number of mailing lists this user can create.');
define('LNG_MaximumLists', 'Maximum Number of Mailing Lists');
define('LNG_HLP_MaximumLists', 'The number of mailing lists that this user can create.');

define('LNG_LimitEmailsPerHour', 'Emails Per Hour');
define('LNG_LimitEmailsPerHourExplain', 'Unlimited emails per hour');
define('LNG_HLP_LimitEmailsPerHour', 'Tick this option to set the maximum number of emails this user can send per send per hour.');
define('LNG_EmailsPerHour', 'Maximum Number of Emails Per Hour');
define('LNG_HLP_EmailsPerHour', 'The maximum number of emails this user can send per send per hour.<br/><br/>If two email campaigns are scheduled in the same hour, this will not affect the total number of emails sent, it will only affect each email campaign separately.');

define('LNG_LimitEmailsPerMonth', 'Emails Per Month');
define('LNG_LimitEmailsPerMonthExplain', 'Unlimited emails per month');
define('LNG_HLP_LimitEmailsPerMonth', 'Tick this option to set the maximum number of emails this user can send each month.');
define('LNG_EmailsPerMonth', 'Maximum Number of Emails Per Month');
define('LNG_HLP_EmailsPerMonth', 'The maximum number of emails this user can send each month.');

define('LNG_LimitMaximumEmails', 'Total Number of Emails');
define('LNG_LimitMaximumEmailsExplain', 'Unlimited emails');
define('LNG_HLP_LimitMaximumEmails', 'Tick this option to set the maximum number of emails this user can send');
define('LNG_MaximumEmails', 'Total Maximum Number of Emails');
define('LNG_HLP_MaximumEmails', 'The maximum number of emails this user can send. This is not time limited. For example, if you specify 500, then this user will only ever be able to send up to 500 emails.<br/><br/>As emails get sent, this number will change to reflect the number of emails the user can still send.');

define('LNG_TextFooter', 'Text Footer');
define('LNG_HLP_TextFooter', 'Any text you type here will be added to the end of every text-based email that this client sends.');

define('LNG_HTMLFooter', 'HTML Footer');
define('LNG_HLP_HTMLFooter', 'Any text you type here will be added to the end of every HTML-based email that this client sends.');

define('LNG_HLP_EmailAddress', 'If your client forgets his/her password, it will be sent to this email address.');


define('LNG_AccessPermissions', 'Access Permissions');
define('LNG_NewsletterPermissions', 'Email Campaign Permissions');
define('LNG_CreateNewsletters', 'Create Email Campaign');
define('LNG_EditNewsletters', 'Edit Email Campaign');
define('LNG_DeleteNewsletters', 'Delete Email Campaign');
define('LNG_ApproveNewsletters', 'Approve Email Campaign');
define('LNG_SendNewsletters', 'Send Email Campaign');

define('LNG_TemplatePermissions', 'Template Permissions');
define('LNG_CreateTemplates', 'Create Templates');
define('LNG_HLP_CreateTemplates', 'Let this user add new templates that can be used for email campaigns and autoresponders?');
define('LNG_EditTemplates', 'Edit Templates');
define('LNG_DeleteTemplates', 'Delete Templates');
define('LNG_ApproveTemplates', 'Approve Templates');
define('LNG_GlobalTemplates', 'Global Templates');
define('LNG_HLP_GlobalTemplates', 'When creating a template, will this user be able to make it global and share it will all other users?');

define('LNG_SubscriberPermissions', 'Subscriber Permissions');
define('LNG_AddSubscribers', 'Add subscribers');
define('LNG_EditSubscribers', 'Edit Subscribers');
define('LNG_DeleteSubscribers', 'Delete Subscribers');
define('LNG_ManageBannedSubscribers', 'Manage Banned Subscribers');
define('LNG_ImportSubscribers', 'Import Subscribers');
define('LNG_ExportSubscribers', 'Export Subscribers');

define('LNG_AdminPermissions', 'Administrator Permissions');
define('LNG_SystemAdministrator', 'System Administrator');
define('LNG_HLP_SystemAdministrator', 'A system administrator will have access to the settings page as well as all lists and all users');
define('LNG_UserAdministrator', 'User Administrator');
define('LNG_ListAdministrator', 'List Administrator');
define('LNG_HLP_ListAdministrator', 'A list administrator will be able to perform certain functions for <i><u>all</u></i> mailing lists, including editing and deleting.');
define('LNG_TemplateAdministrator', 'Template Administrator');
define('LNG_HLP_TemplateAdministrator', 'A template administrator will be able to perform certain functions for <i><u>all</u></i> templates, including editing and deleting.');

define('LNG_ListPermissions', 'List Permissions');
define('LNG_CreateMailingLists', 'Create Mailing Lists');
define('LNG_EditMailingLists', 'Edit Mailing Lists');
define('LNG_DeleteMailingLists', 'Delete Mailing Lists');
define('LNG_MailingListsBounce', 'Process Bounced Emails');

define('LNG_CustomFieldPermissions', 'Custom Field Permissions');
define('LNG_CreateCustomFields', 'Create Custom Fields');
define('LNG_EditCustomFields', 'Edit Custom Fields');
define('LNG_DeleteCustomFields', 'Delete Custom Fields');

define('LNG_FormPermissions', 'Website Form Permissions');
define('LNG_CreateForms', 'Create Website Forms');
define('LNG_EditForms', 'Edit Website Forms');
define('LNG_DeleteForms', 'Delete Website Forms');

define('LNG_MailingListAccessPermissions', 'Mailing List Access Permissions');
define('LNG_ChooseMailingLists', 'Mailing Lists');
define('LNG_HLP_ChooseMailingLists', 'Which mailing lists will this client have access to? The permissions selected above will be applied to these lists.');
define('LNG_AllMailingLists', 'All Mailing Lists');

define('LNG_AutoresponderPermissions', 'Autoresponder Permissions');
define('LNG_CreateAutoresponders', 'Create Autoresponders');
define('LNG_EditAutoresponders', 'Edit Autoresponders');
define('LNG_DeleteAutoresponders', 'Delete Autoresponders');
define('LNG_ApproveAutoresponders', 'Approve Autoresponders');


define('LNG_TemplateAccessPermissions', 'Template Access Permissions');
define('LNG_ChooseTemplates', 'Templates');
define('LNG_HLP_ChooseTemplates', 'Which templates does this user have access to? The permissions selected above will be applied to these templates.');
define('LNG_AllTemplates', 'All Templates');


define('LNG_AdministratorType_SystemAdministrator', 'System Administrator');
define('LNG_AdministratorType_ListAdministrator', 'List Administrator');
define('LNG_AdministratorType_NewsletterAdministrator', 'Email Campaign Administrator');
define('LNG_AdministratorType_TemplateAdministrator', 'Template Administrator');
define('LNG_AdministratorType_UserAdministrator', 'User Administrator');
define('LNG_AdministratorType_RegularUser', 'Regular User');
define('LNG_AdministratorType_Custom', 'Custom');

define('LNG_ListAdministratorType_AllLists', 'All mailing lists');

define('LNG_TemplateAdministratorType_AllTemplates', 'All templates');

define('LNG_OtherPermissions', 'Other Permissions');
define('LNG_NewsletterStatistics', 'View Email Campaign Statistics');
define('LNG_HLP_NewsletterStatistics', 'View Statistics for email campaigns and mailing lists');

define('LNG_UserStatistics', 'User Statistics');
define('LNG_HLP_UserStatistics', 'View statistics for users. If this user is not a user administrator, they will only see their own statistics.');

define('LNG_AutoresponderStatistics', 'View Autoresponder Statistics');
define('LNG_HLP_AutoresponderStatistics', 'View Statistics for autoresponders and mailing lists');

define('LNG_ListStatistics', 'Mailing List Statistics');

define('LNG_NoListsAvailable', '<font color=gray>[No mailing lists have been created]</font>');

define('LNG_NoTemplatesAvailable', '<font color=gray>[No templates have been created]</font>');

define('LNG_Delete_User_Selected', 'Delete Selected');
define('LNG_ChooseUsersToDelete', 'Choose users to delete first.');
define('LNG_ConfirmRemoveUsers', 'Are you sure you want to delete these users? This cannot be undone.');

define('LNG_UserDeleteFail', 'User \'%s\' could not be deleted: %s');
define('LNG_UserDeleteSuccess_One', '1 user was deleted successfully.');
define('LNG_UserDeleteSuccess_Many', '%s users were deleted successfully.');

define('LNG_User_Delete_Disabled', 'You cannot delete this user.');
define('LNG_User_Delete_Own_Disabled', 'You cannot delete your own user account.');

/**
**************************
* Changed/added in NX1.0.5
**************************
*/
define('LNG_ListAdministratorType_Custom', 'Users own lists plus the following lists');
define('LNG_TemplateAdministratorType_Custom', 'Users own templates plus the following');

/**
**************************
* Changed/added in NX1.0.7
**************************
*/
define('LNG_StatisticsPermissions', 'Statistics Permissions');
define('LNG_User_SMTP', 'User SMTP Settings');
define('LNG_HLP_User_SMTP', 'A user can edit their own smtp settings if they can manage their account.');

define('LNG_BuiltInTemplates', 'Show Built In Templates');

define('LNG_MailingListsBounceSettings', 'Edit Bounce Information');

?>
