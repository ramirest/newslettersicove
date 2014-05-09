<?php
/**
* Language file variables for the settings area.
*
* @see GetLang
*
* @version     $Id: settings.php,v 1.31 2007/06/22 02:39:16 chris Exp $

*
* @package SendStudio
* @subpackage Language
*/

/**
* Here are all of the variables for the settings area... Please backup before you start!
*/

define('LNG_Help_Settings', 'Update the settings in the form below and click "Save", or click "Cancel" to keep the current settings.');

define('LNG_SettingsSaved', 'The modified settings have been saved successfully.');
define('LNG_SettingsNotSaved', 'The modified settings have been not been saved.');

define('LNG_DatabaseIntro', 'Database Details');
define('LNG_DatabaseType', 'Database Type');
define('LNG_DatabaseUser', 'Database User');
define('LNG_DatabasePassword', 'Database Password');
define('LNG_DatabaseHost', 'Database Hostname');
define('LNG_DatabaseName', 'Database Name');
define('LNG_DatabaseTablePrefix', 'Database Table Prefix');

define('LNG_Miscellaneous', 'Miscellaneous Settings');
define('LNG_ApplicationURL', 'Application URL');

define('LNG_LicenseKeyIntro', 'License Key Details');
define('LNG_LicenseKey', 'License Key');
define('LNG_LicenseKeyUpdated', 'Your license key has been updated. You will be logged out completely and you will need to log in again for the change to take effect.');

define('LNG_HLP_DatabaseUser','Username used to login to the database.');
define('LNG_HLP_DatabasePassword','Password used to login to the database.');
define('LNG_HLP_DatabasePasswordConfirm','Re-type your database password to confirm that it is correct.');
define('LNG_HLP_DatabaseHost', 'Hostname or IP address of the database server. eg. localhost.');
define('LNG_HLP_DatabaseName', 'The name of the database being used.');
define('LNG_HLP_DatabaseTablePrefix', 'Optional text to be prepended to tables. This is recommended if you are using a database that contains other tables.');

define('LNG_HLP_ApplicationURL', 'Full URL where this application is setup eg. http://www.domain.com/newsletter/');
define('LNG_HLP_LicenseKey', 'The license key generated when you purchased this product. If unsure please contact your administrator or the seller of this product.');

define('LNG_GlobalHTMLFooter', 'Global HTML Footer');
define('LNG_HLP_GlobalHTMLFooter', 'Anything you type here will be added to the end of every HTML email (after the users HTML footer). To add a line break, type <br/>\\\'&lt; br&gt;\\\'');

define('LNG_GlobalTextFooter', 'Global Text Footer');
define('LNG_HLP_GlobalTextFooter', 'Anything you type here will be added to the end of every text email (after the users text footer).');

define('LNG_ForceUnsubLink', 'Force Unsubscribe Link');
define('LNG_HLP_ForceUnsubLink', 'This option will force all email campaigns and autoresponders to contain a mandatory unsubscribe link before they are sent.<br/><br/>If an unsubscribe link is not detected in an email, it will automatically be added. If an unsubscribe link is detected, this option will be ignored.');
define('LNG_ForceUnsubLinkExplain', 'Yes, force an unsubscribe link');

define('LNG_MaxHourlyRate', 'Max Hourly Rate');
define('LNG_HLP_MaxHourlyRate', 'The maximum number of emails that will be sent per send per hour. The per hour rate for users can be less than this, but not more.<br/><br/>This will limit each send, it will not limit the total number of emails sent per hour.<br/><br/>Set to 0 for unlimited.');

define('LNG_MaxOverSize', 'Monthly Leeway Allowance');
define('LNG_HLP_MaxOverSize', 'If a user tries to send more emails than they are allowed, the monthly leeway allowance will let them send a few more.<br/><br/>For example, if a user has a per-month limit of 100, but schedules an email campaign 3 days in advance and it has to send to 110 subscribers, it would normally be blocked. If they had a leeway allowance of 10 or more, it would sent.<br/><br/>Both the user and administrator will be notified when this takes effect.<br/><br/>Set to 0 for no leeway.');

define('LNG_CronEnabled', 'Cron Support');
define('LNG_CronEnabledExplain', 'Yes, cron support is enabled');
define('LNG_HLP_CronEnabled', 'This option will let you schedule emails in advance, send autoresponders and process bounced emails quickly. You must have cron installed and setup correctly on your server for this to work.');

define('LNG_IpTracking', 'IP Address Tracking');
define('LNG_IpTrackingExplain', 'Yes, ip address tracking is enabled');
define('LNG_HLP_IpTracking', 'This option will let you enable or disable storing ip addresses when subscribers subscribe or unsubscribe from mailing lists.');

define('LNG_SendTestIntro', 'Test Sending');
define('LNG_TestEmailAddress', 'Test Sending System');
define('LNG_HLP_TestEmailAddress', 'Enter an email address to test the sending system (uses the SMTP server details provided above if applicable).');
define('LNG_TestSendingSubject', 'Test Email System');
define('LNG_TestSendingEmail', "Hi,\nThis is a test of the emailing system. If you received this ok, then everything is working as it should."); // this is in double quotes because of the newline.

define('LNG_TestEmailSent', 'A test email has been successfully sent to the email address %s.');

define('LNG_ApplicationEmail', 'Contact Email Address');
define('LNG_HLP_ApplicationEmail', 'The return email address for \\\'Forgot Password\\\' requests.');

define('LNG_CronPath', 'Cron Path');
define('LNG_HLP_CronPath', 'This is the required path for cron jobs to work. This includes scheduled sending, autoresponders and automatic bounce processing. You will need this path when setting up your server for cron support.');

/**
**************************
* Changed/added in NX1.0.5
**************************
*/
define('LNG_MaxImageWidth', 'Maximum Image Width');
define('LNG_HLP_MaxImageWidth', 'Set the maximum width of images that users are allowed to upload. This should be set to stop users from uploading extremely large images in email campaigns.');
define('LNG_MaxImageHeight', 'Maximum Image Height');
define('LNG_HLP_MaxImageHeight', 'Set the maximum height of images that users are allowed to upload. This should be set to stop users from uploading extremely large images in email campaigns.');

/**
**************************
* Changed/added in NX1.0.7
**************************
*/
define('LNG_BounceAccountIntro', 'Default Bounce Details');
define('LNG_SetDefaultBounceAccountDetails', 'Default Bounce Details');
define('LNG_HLP_SetDefaultBounceAccountDetails', 'Complete the details below to specify default bounce handling information when a new mailing list is created.<br/><br/>If you don\\\'t specify bounce information, all bounced emails will be returned to the \\\'From\\\' address setup for a particular mailing list.');
define('LNG_SetDefaultBounceAccountDetailsExplain', 'Yes, set default bounce details');

define('LNG_DefaultBounceAddress', 'Default Bounce Address');
define('LNG_HLP_DefaultBounceAddress', 'This is the address where emails will be returned to if they bounce, which means they haven\\\'t reached the intended recipient.');

define('LNG_DefaultBounceServer', 'Default Bounce Server');
define('LNG_HLP_DefaultBounceServer', 'Enter the email server name to connect to so bounced emails can be processed. This can be either in the format of just hostname or can include an alternate port with hostname:port.<br/><br/>This setting is only used when a new mailing list is created, if the \\\'Hide Bounce Information\\\' permission is not checked then the user will be able to change this information for their mailing list.');

define('LNG_DefaultBounceUsername', 'Default Bounce Username');
define('LNG_HLP_DefaultBounceUsername', 'This is used for processing bounced emails. If you enter your email server, username and password, you can process bounces using a cron job.<br/><br/>This setting is only used when a new mailing list is created, if the \\\'Hide Bounce Information\\\' permission is not checked then the user will be able to change this information for their mailing list.');

define('LNG_DefaultBouncePassword', 'Default Bounce Password');
define('LNG_HLP_DefaultBouncePassword', 'This is used for processing bounced emails. If you enter your email server, username and password, you can process bounces using a cron job.<br/><br/>This setting is only used when a new mailing list is created, if the \\\'Hide Bounce Information\\\' permission is not checked then the user will be able to change this information for their mailing list.');

define('LNG_DefaultBounceImap', 'IMAP Email Account');
define('LNG_DefaultBounceImapExplain', 'Yes, this is an imap account');
define('LNG_HLP_DefaultBounceImap', 'Is the bounce email account an imap account? If it is not an imap account, it is a POP3 account.<br/><br/>This setting is only used when a new mailing list is created, if the \\\'Hide Bounce Information\\\' permission is not checked then the user will be able to change this information for their mailing list.');

define('LNG_UseDefaultBounceExtraSettings', 'Use Extra Mail Settings');
define('LNG_UseDefaultBounceExtraSettingsExplain', 'Yes, use extra mail settings');
define('LNG_HLP_UseDefaultBounceExtraSettings', 'You may need to set extra options to connect to an email account for bounce processing. If so, enable this option and fill in the required information below. If unsure, leave this unticked.<br/><br/>This setting is only used when a new mailing list is created, if the \\\'Hide Bounce Information\\\' permission is not checked then the user will be able to change this information for their mailing list.');

define('LNG_DefaultBounceExtraSettings', 'Extra Mail Settings');
define('LNG_HLP_DefaultBounceExtraSettings', 'Please add any extra options that may be required to properly connect to a bounce email account. For example \\\'/notls\\\' or \\\'/nossl\\\'.<br/><br/>This setting is only used when a new mailing list is created, if the \\\'Hide Bounce Information\\\' permission is not checked then the user will be able to change this information for their mailing list.');

define('LNG_Charset', 'Character Set');
define('LNG_HLP_Charset', 'This is the character set used when sending out an email campaign or autoresponder.<br/><br/>This cannot be changed because it causes issues with already encoded database information (for example accented characters).');

define('LNG_EmailSettings', 'Email Settings');
define('LNG_CurrentServerTime', 'Current Server Time');

define('LNG_ServerTimeZone', 'Server Timezone');
define('LNG_HLP_ServerTimeZone', 'This is the timezone of your webserver (if this is incorrect or if you are unsure, please contact your webhost).<br/>There is no need for this setting to be changed unless you are sure it is incorrect.<br/>To change the timezone of individual users please edit the time setting in the User section.');


/**
**************************
* Changed/Added in NX1.1.3
**************************
*/
define('LNG_DatabaseUpgraded', 'Your database has been upgraded to version %s.');
define('LNG_DatabaseUpgradesFailed', 'Your database was not upgraded successfully. The following errors occurred:');

/**
* Do not change the <a href onclick> event! It will stop the upgrade from working!
*/
define('LNG_DatabaseUpgradeIntro', '<font color="red"><b>There is a database upgrade required. Your database is currently at version %s, the latest version is %s.</b></font>&nbsp;<a href="#" onclick="UpgradeDb(); return false;">Click here to upgrade the database.</a>');

define('LNG_TestEmailNotSent', 'A test email has not been successfully sent to the email address %s: %s');

?>
