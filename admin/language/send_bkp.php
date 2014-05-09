<?php
/**
* Language file variables for the send management area.
*
* @see GetLang
*
* @version     $Id: send.php,v 1.20 2007/05/29 06:48:35 chris Exp $

*
* @package SendStudio
* @subpackage Language
*/

/**
* Here are all of the variables for the send area... Please backup before you start!
*/

define('LNG_NoLiveNewsletters', 'None of your email campaigns are active.%s');
define('LNG_NoLiveNewsletters_HasAccess', ' Please go to the <a href="index.php?Page=Newsletters">Manage Email Campaigns</a> page and make the email campaign active.');

define('LNG_Send_CancelPrompt', 'Are you sure you want to cancel sending an email campaign?');


define('LNG_Send_Step1', 'Sending Email Campaign');
define('LNG_Send_Step1_Intro', 'Before you can send an email campaign, please select which mailing list(s) you want to send to.');

define('LNG_SendMailingList', LNG_MailingList);
define('LNG_HLP_SendMailingList', 'To send to multiple lists at once, hold down the CTRL key and click the lists.<br/>To unselect a list, hold down CTRL and click the list.');

define('LNG_Send_Step2', 'Send Email Campaign');
define('LNG_Send_Step2_Intro', 'Use the form below to choose the recipients to receive this email campaign.');

define('LNG_Send_Step3', 'Send Email Campaign');
define('LNG_Send_Step3_Intro', 'Use the form below to setup sending options for this mailing.');

define('LNG_NewsletterDetails', 'Email Campaign Details');
define('LNG_SendNewsletter', 'Send Email Campaign');
define('LNG_HLP_SendNewsletter', 'Which email campaign would you like to send to your subscribers?');

define('LNG_SendMultipart', 'Send Multipart');
define('LNG_HLP_SendMultipart', 'Sending a multipart email will let the subscribers email program decide which format (HTML or Text) to display the email in.<br/><br/>It is best to use this if you don\\\'t give your subscribers a choice to which format they receive (e.g. they all subscribe as HTML), when they receive the email their email program will automatically show the correct format.<br/><br/>If unsure, leave this option ticked.');
define('LNG_SendMultipartExplain', 'Yes, send the email as multipart');

define('LNG_TrackOpens', 'Track Email Opens');
define('LNG_HLP_TrackOpens', 'Track opening of emails when a subscriber receives an email. This only applies to HTML email campaigns.');
define('LNG_TrackOpensExplain', 'Yes, track opening of HTML emails');

define('LNG_TrackLinks', 'Track Links');
define('LNG_HLP_TrackLinks', 'Track all link clicks in this email. After the email is sent, you can view link click details from the statistics page.');
define('LNG_TrackLinksExplain', 'Yes, track all links in this email campaign');

define('LNG_SelectNewsletterToSend', 'Please select an email campaign');

define('LNG_SendImmediately', 'Send Immediately');
define('LNG_HLP_SendImmediately', 'Do you want to send this email campaign immediately or schedule it to be sent at a later date?');
define('LNG_SendImmediatelyExplain', 'Yes, send this email campaign immediately');

define('LNG_SelectNewsletterPreviewPrompt', 'Please select an email campaign first.');
define('LNG_SelectNewsletterPrompt', 'Select an email campaign to send from the list.');
define('LNG_SendSize_One', 'This email campaign will be sent to approximately 1 subscriber.');

define('LNG_SendSize_Many', 'This email campaign will be sent to approximately %s subscribers.');

define('LNG_ReadMore', 'Read More');
define('LNG_ReadMoreWhyApprox', 'If you are scheduling this email campaign to be sent at a later date, then the number of people it is sent to may change as people subscribe or unsubscribe from your mailing list.');

define('LNG_EnterSendFromName','Please enter a \\\'From name\\\'');
define('LNG_EnterSendFromEmail','Please enter a \\\'From email\\\'');
define('LNG_EnterReplyToEmail','Please enter a \\\'Reply-To email\\\'');
define('LNG_EnterBounceEmail','Please enter a \\\'Bounce email\\\'');

define('LNG_CronSendOptions', 'Sending Options');
define('LNG_SendTime', 'Send Time');
define('LNG_SendDate', 'Send Date');
define('LNG_HLP_SendTime', 'Select the time and date when you wish to send your email campaign.');
define('LNG_NotifyOwner', 'Notify Owner');
define('LNG_HLP_NotifyOwner', 'Notify the list owner(s) when a scheduled send starts and when it finishes?');
define('LNG_NotifyOwnerExplain', 'Yes, notify owner(s) via email');

define('LNG_StartSending', 'Start Sending');
define('LNG_Send_Step4', 'Send Email Campaign');
define('LNG_Send_Step4_Intro', 'This email campaign will be sent immediately. Click on the \'Start Sending\' button below to start sending.');

define('LNG_Send_NewsletterName', 'Email Campaign Name: %s');
define('LNG_Send_SubscriberList', 'Subscriber List(s): %s');
define('LNG_Send_TotalRecipients', 'Total Recipient(s): %s');


define('LNG_Send_Step4_CronIntro', 'This email campaign will be scheduled to send using the scheduled sending system if you click "Yes" below.<br/>Please check the information below before continuing.');

define('LNG_Send_Step4_CannotSendInPast', 'You have tried to schedule the email campaign to send in the past. Please choose a date in the future.');

define('LNG_Send_Step5', 'Sending Email Campaign...');
define('LNG_Send_NumberLeft_One', 'There is 1 email left to send. Please wait..');
define('LNG_Send_NumberLeft_Many', 'There are %s emails left to send. Please wait..');

define('LNG_Send_NumberSent_One', '1 email has been sent.');
define('LNG_Send_NumberSent_Many', '%s emails have been sent.');

define('LNG_Send_TimeSoFar', 'Time taken so far (approx): <b>%s</b>');
define('LNG_Send_TimeLeft', 'Time until completion (approx): <b>%s</b>');

define('LNG_Send_Finished_Heading', 'Send Email Campaign');
define('LNG_Send_Finished', 'The selected email campaign has been sent. It took %s to complete.');
define('LNG_SendReport_Intro', 'The selected email campaign has been sent. It took %s to complete.');

define('LNG_SendReport_Success_One', 'The selected email campaign was sent to 1 subscriber successfully');
define('LNG_SendReport_Success_Many', 'The selected email campaign was sent to %s subscribers successfully');

define('LNG_SendReport_Failure_One', 'The selected email campaign was not sent to 1 subscriber successfully.');
define('LNG_SendReport_Failure_Many', 'The selected email campaign was not sent to %s subscribers successfully.');

define('LNG_PauseSending', 'Pause Sending. You can always resume later.');
define('LNG_Send_Paused_Heading', 'Sending Paused');
define('LNG_Send_Paused_Success', 'Sending your email campaign has been paused successfully.');
define('LNG_Send_Paused_Failure', 'Sending your email campaign has not been paused successfully.');
define('LNG_Send_Paused', 'You can resume sending your email campaign from the "Manage Email Campaigns" page.<br/>');

define('LNG_JobScheduled', 'Your job has been scheduled to run at %s');
define('LNG_JobNotScheduled', 'Your job has not been scheduled to run at %s');

define('LNG_SendFinished', 'Your email campaign has finished sending.');

define('LNG_SendToTestListWarning', 'This email campaign has not been sent before. It is recommended that you send to a test mailing list before sending to your live list.');
define('LNG_ApproveScheduledSend', 'Yes, send this email campaign');
define('LNG_CancelScheduledSend', 'Do not send this email campaign');

/**
* different helptips for sending a newsletter for "date subscribed", "opened newsletter" and "clicked link".
*/
define('LNG_Send_FilterByDate', LNG_FilterByDate);
define('LNG_HLP_Send_FilterByDate', 'This option will allow you to only send to subscribers who have subscribed before, after or between particular dates. To send to all subscribers, leave this option unticked.');

define('LNG_Send_OpenedNewsletter', LNG_OpenedNewsletter);
define('LNG_HLP_Send_OpenedNewsletter', 'This option will allow you to send only to subscribers who have opened a particular email campaign or autoresponder sent to this mailing list. To send to all subscribers, leave this option unticked.');

define('LNG_Send_ClickedOnLink', LNG_ClickedOnLink);
define('LNG_HLP_Send_ClickedOnLink', 'This option will allow you to send only to subscribers who have clicked on a particular link in an email campaign or autoresponder that was sent to this mailing list. To search for all subscribers, leave this option unticked.');


/**
**************************
* Changed/Added in NX1.1.1
**************************
*/
define('LNG_Send_Subscribers_Search_Step2', 'Use the form below to find subscribers to send your email campaign to. You can choose to send an email to specific subscribers based on your search criteria.<br/>If you would like to send to all subscribers, leave these options blank and click the Next button.');

?>
