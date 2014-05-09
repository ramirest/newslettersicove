<?php

/**
* Language file variables for the jobs bouncing area.
*
* @see GetLang
*
* @version     $Id: bounce.php,v 1.10 2007/05/04 04:28:13 rodney Exp $

*
* @package SendStudio
* @subpackage Language
*/

/**
* Here are all of the variables for the jobs bouncing area... Please backup before you start!
*/

// we need some variables from the lists language file.
require_once(dirname(__FILE__) . '/lists.php');

define('LNG_BadLogin_Details', 'Unable to log in using the details provided, the server returned this error message: %s<br/>Please check the details and try again.');

define('LNG_BounceAccountEmpty', 'No emails have been found in this email account.');

define('LNG_Bounce_No_ImapSupport_Heading', 'Process Bounced Emails');
define('LNG_Bounce_No_ImapSupport_Intro', 'Your server does not have the required modules install to process bounces. Please contact your host or system administrator and ask them to install the "PHP-IMAP" module.<br/>For more information, see <a href="http://www.php.net/imap" target="_blank">The PHP Website</a>');

define('LNG_Bounce_Step1', 'Process Bounced Emails');
define('LNG_Bounce_Step1_Intro', 'Please select a mailing list to process bounced emails for.');
define('LNG_Bounce_CancelPrompt', 'Are you sure you want to cancel processing bounced emails?');

define('LNG_Bounce_Step2', 'Process Bounced Emails');
define('LNG_Bounce_Step2_Intro', 'Enter the bounce email account information to continue. The system will check this email account for any bounced emails to process.<br/>To save your bounce account information on the "process bounced emails" page, edit your mailing list and enter in the bounce information there.');

define('LNG_Bounce_Step3', 'Process Bounced Emails');
define('LNG_Bounce_Step3_Intro', 'Click the Start Processing button to check for and process bounced emails in your email account.');
define('LNG_StartProcessing', 'Start Processing');

define('LNG_EnterBounceServer', 'Please enter the bounce server name.');
define('LNG_EnterBounceUsername', 'Please enter the bounce account username.');
define('LNG_EnterBouncePassword', 'Please enter the bounce account password.');
define('LNG_BounceUsername', LNG_ListBounceUsername);
define('LNG_HLP_BounceUsername', 'Enter the username for the bounce email account.<br/>This can either be \\\'username\\\' or \\\'username@domain.com\\\' depending on the host.');

define('LNG_BouncePassword', LNG_ListBouncePassword);
define('LNG_HLP_BouncePassword', 'Enter the password for the bounce email account.');

define('LNG_BounceResults_InProgress', 'Bounce Processing In Progress');
define('LNG_BounceResults_InProgress_Message', 'Please wait while we attempt to process the %s email(s) found in the account...');

define('LNG_BounceResults_InProgress_HardBounces_Many', '%s hard bounces have been found so far');
define('LNG_BounceResults_InProgress_HardBounces_One', '1  hard bounce have been found so far');

define('LNG_BounceResults_InProgress_SoftBounces_Many', '%s soft bounces have been found so far');
define('LNG_BounceResults_InProgress_SoftBounces_One', '1  soft bounce have been found so far');

define('LNG_BounceResults_InProgress_EmailsIgnored_Many', '%s emails have been ignored so far.');
define('LNG_BounceResults_InProgress_EmailsIgnored_One', '1  emails have been ignored so far.');

define('LNG_BounceResults_HardBounces_Many', '%s emails were processed as "hard bounces"');
define('LNG_BounceResults_HardBounces_One', '1 email was processed as a "hard bounce"');

define('LNG_BounceResults_SoftBounces_Many', '%s emails were processed as "soft bounces"');
define('LNG_BounceResults_SoftBounces_One', '1 email was processed as a "soft bounce"');

define('LNG_BounceResults_Finished', 'Process Bounced Emails');
define('LNG_BounceResults_Intro', 'The email account was processed successfully');
define('LNG_BounceResults_Message_Multiple', '%s emails were found in the email account.');
define('LNG_BounceResults_Message_One', '1 email was found in the email account.');

define('LNG_ViewBounceStatistics', 'View Bounce Statistics');

/**
**************************
* Changed/Added in NX1.0.5
**************************
*/
// these first two are used by cron bounce processing.
define('LNG_BadLogin_Subject_Cron', 'Invalid Login Details');
define('LNG_BadLogin_Details_Cron', 'Whilst trying to process bounces for list \'%s\' the details provided (username, password and / or email server name) are invalid. You will need to make sure these details are correct. You can update the bounce account details by editing the Mailing List from your control panel.' . "\n" . 'The error message received when trying to process bounces was: %s');

define('LNG_BounceServer', LNG_ListBounceServer);
define('LNG_HLP_BounceServer', 'Enter the email server name to connect to so bounced emails can be processed. This can be either in the format of just hostname or can include an alternate port with hostname:port');

define('LNG_AddOwnBounceRules', '<br/>You or your administrator can modify the bounce rules used by editing the admin/resources/user_bounce_rules.php file.');

define('LNG_SaveBounceServerDetails','Save Bounce Server Details');
define('LNG_SaveBounceServerDetailsExplain','Yes, save these details');
define('LNG_HLP_SaveBounceServerDetails','Save the Bounce Server details for this campaign so you do not have to enter them again.');

define('LNG_BounceDetailsSaved', 'Bounce details saved successfully.');

define('LNG_BounceResults_EmailsIgnored_Many', '%s emails were ignored in the email account. These could be autoresponders (for example, out of office messages), non-bounced messages (for example, spam) or they didn\'t match any of the bounce processing rules.' . LNG_AddOwnBounceRules);
define('LNG_BounceResults_EmailsIgnored_One', '1 email was ignored in the email account. This could be an autoresponder (for example, out of office messages), a non-bounced message (for example, spam) or it didn\'t match any of the bounce processing rules.' . LNG_AddOwnBounceRules);

/**
**************************
* Changed/Added in NX1.0.7
**************************
*/
define('LNG_BounceResults_InProgress_Progress', 'Processed %s of %s emails');

require(dirname(__FILE__) . '/../resources/bounce_rules.php');

?>
