<?php
/**
* Language file variables for the stats area.
*
* @see GetLang
*
* @version     $Id: stats.php,v 1.40 2007/06/14 06:29:21 chris Exp $

*
* @package SendStudio
* @subpackage Language
*/

/**
* Here are all of the variables for the stats area... Please backup before you start!
*/

define('LNG_Stats_PrintReport', 'Print Report');
define('LNG_Stats_ExportReport', 'Export Report');

define('LNG_Stats_TotalDelivered', 'Total Delivered');
define('LNG_Stats_TotalForwards', 'Total Forwards');
define('LNG_Stats_TotalOpens', 'Total Opens');
define('LNG_Stats_TotalUniqueOpens', 'Total Unique Opens');
define('LNG_Stats_TotalLinkClicks', 'Total Link Clicks');
define('LNG_Stats_TotalClicks', 'Total Clicks');
define('LNG_Stats_TotalUniqueClicks', 'Total Unique Clicks');
define('LNG_Stats_MostPopularLink', 'Most Popular');
define('LNG_Stats_AverageClicks', 'Average Clicks (Per Email Open)');
define('LNG_LinksClickedChart', 'Links Clicked Chart');
define('LNG_OpensChart', 'Opens Chart');
define('LNG_ForwardsChart', 'Forwards Chart');
define('LNG_Stats_TotalUnsubscribes', 'Total Unsubscribes');
define('LNG_Stats_MostUnsubscribes', 'Most Unsubscribes');
define('LNG_UnsubscribesChart', 'Unsubscribes Chart');
define('LNG_UnsubscribeDate', 'Unsubscribe Date');

define('LNG_Stats_ViewSummary', 'View Report');

define('LNG_Unsubscribe_Summary', 'Unsubscribe Summary');
define('LNG_LinkClicks_Summary', 'Link Clicks Summary');
define('LNG_Opens_Summary', 'Opens Summary');
define('LNG_Forwards_Summary', 'Forwards Summary');

define('LNG_StatsDeleteDisabled', 'You cannot delete statistics while an email campaign is still being sent.');
define('LNG_Delete_Stats_Selected', 'Delete Statistics');
define('LNG_ChooseStatsToDelete', 'Choose some statistics to delete');
define('LNG_DeleteStatsPrompt', 'Are you sure you want to delete these statistics?\nOnce they are deleted they cannot be retrieved.');

define('LNG_StatisticsDeleteSuccess_One', 'Statistics deleted successfully.');
define('LNG_StatisticsDeleteSuccess_Many', '%s statistics deleted successfully.');

define('LNG_StatisticsDeleteFail_One', 'Statistics not deleted successfully. Please try again.');
define('LNG_StatisticsDeleteFail_Many', '%s statistics not deleted successfully. Please try again.');

define('LNG_StatisticsDeleteNotFinished_One', 'Statistics cannot be deleted while an email campaign is still being sent.');
define('LNG_StatisticsDeleteNotFinished_Many', '%s statistics cannot be deleted while an email campaign is still being sent.');

define('LNG_DateStarted', 'Date Started');
define('LNG_DateFinished', 'Date Finished');
define('LNG_TotalRecipients', 'Recipients');
define('LNG_UnsubscribeCount', 'Unsubscribes');
define('LNG_ExportReport', 'Export Report');
define('LNG_PrintReport', 'Print Report');
define('LNG_ViewSummary', 'View');

define('LNG_TotalEmails', 'Total Emails: ');
define('LNG_TotalOpens', 'Total Opens: ');
define('LNG_TotalUniqueOpens', 'Total Unique Opens: ');
define('LNG_AverageOpens', 'Average Opens: ');
define('LNG_MostOpens', 'Most Opens (Date/Time): ');

/**
* Newsletter statistics language variables.
*/
define('LNG_Stats_NewsletterStatistics', 'Email Campaign Statistics');
define('LNG_Stats_ChooseNewsletter', '-- Choose an email campaign from the list below --');
define('LNG_Stats_Newsletters_Step1_Heading', 'Email Campaign Statistics');
define('LNG_Stats_Newsletters_Step1_Intro', 'To get started, please choose an email campaign to view statistics for.');
define('LNG_Stats_Newsletters_Cancel', 'Are you sure you want to cancel viewing email campaign statistics?');
define('LNG_Stats_Newsletters_SelectList_Heading', 'Email Campaign Details');
define('LNG_Stats_Newsletters_SelectList_Intro', 'Email Campaign');
define('LNG_Stats_Newsletters_NoSelection', 'Please choose an email campaign.');
define('LNG_NoNewslettersHaveBeenSent', 'No email campaign statistics are available as no newsletters have been sent.');
define('LNG_Stats_Newsletters_Step2_Heading', 'Email Campaign Statistics');
define('LNG_Stats_Newsletters_Step2_Intro', 'View statistics for email campaign \'%s\'');
define('LNG_NoNewsletterStats', 'No statistics for email campaign \'%s\' have been recorded.');
define('LNG_NewsletterSummaryChart', 'Email Campaign Summary Chart');



/**
* User statistics language variables.
*/

// this is used if someone tries to view another users statistics. This is so they can't get someone elses' username.
// whether the other user actually exists is not displayed.
define('LNG_Stats_Unknown_User', 'Either the user does not exist or you do not have access to view their information.');
define('LNG_Stats_UserStatistics', 'User Statistics');
define('LNG_Stats_ChooseUser', '-- Choose a user from the list below --');
define('LNG_Stats_Users_Step1_Heading', 'User Statistics');
define('LNG_Stats_Users_Step1_Intro', 'To get started, please choose a user to view statistics for.');
define('LNG_Stats_Users_Cancel', 'Are you sure you want to cancel viewing user statistics?');
define('LNG_Stats_Users_SelectList_Heading', 'User Details');
define('LNG_Stats_Users_SelectList_Intro', 'Username');
define('LNG_Stats_Users_NoSelection', 'Please choose a user first.');

define('LNG_User_Summary_Graph', 'User Summary Graph');
define('LNG_UserStatisticsSnapshot', 'Statistics Snapshot');
define('LNG_Stats_Users_Step3_Heading', 'User Statistics');
define('LNG_Stats_Users_Step3_Intro', 'View a summary for user \'%s\'');
define('LNG_UserStatistics_Snapshot_EmailsSent', 'Emails Sent');
define('LNG_Stats_UserCreateDate', 'User Created');
define('LNG_Stats_UserLastLoggedIn', 'User Last Logged In');
define('LNG_UserLastNewsletterSent', 'Last Email Campaign Sent');
define('LNG_UserNewslettersSent', 'Email Campaigns Sent');
define('LNG_UserAutorespondersCreated', 'Autoresponders Created');
define('LNG_Stats_TotalLists', 'Mailing Lists');
define('LNG_Stats_TotalSubscribers', 'Total Subscribers');
define('LNG_Stats_TotalEmailsSent', 'Emails Sent');

define('LNG_UserHasNotSentAnyNewsletters', 'The selected user has not sent any email campaigns.');
define('LNG_UserHasNotLoggedIn', 'User has not logged in');

define('LNG_Stats_ViewNewsletterStatistics_User', 'View statistics for user \'%s\'');
define('LNG_Stats_ViewNewsletterStatistics_Introduction_User', 'View statistics for user \'%s\'');
define('LNG_Stats_Users_Step3_EmailsSent_Intro', 'View the number of emails sent for user \'%s\'.<br/><i>This does not include email campaigns that are still being sent. (In progress)</i>');

/**
* Autoresponder statistics language variables.
*/
define('LNG_Stats_AutoresponderStatistics', 'Autoresponder Statistics');
define('LNG_Stats_ChooseAutoresponder', '-- Choose an autoresponder from the list below --');
define('LNG_Stats_Autoresponders_Step1_Heading', 'Autoresponder Statistics');
define('LNG_Stats_Autoresponders_Step1_Intro', 'To get started, please choose an autoresponder to view statistics for.');
define('LNG_Stats_Autoresponders_Cancel', 'Are you sure you want to cancel viewing autoresponder statistics?');
define('LNG_Stats_Autoresponders_SelectList_Heading', 'Autoresponder Details');
define('LNG_Stats_Autoresponders_SelectList_Intro', 'Autoresponder');
define('LNG_Stats_Autoresponders_NoSelection', 'Please choose an autoresponder.');

define('LNG_Stats_Autoresponders_Step2_Heading', 'Autoresponder Statistics');
define('LNG_Stats_Autoresponders_Step2_Intro', 'View statistics for autoresponder \'%s\'');
define('LNG_NoAutoresponderStats', 'No statistics for autoresponder \'%s\' have been recorded.');
define('LNG_NoStatisticsToDelete', 'There are no statistics to delete.');
define('LNG_StatisticsDeleteNoStatistics_One', 'Cannot delete statistics when none are available.');
define('LNG_StatisticsDeleteNoStatistics_Many', 'Cannot delete statistics when none are available.');
define('LNG_NoAutorespondersHaveBeenSent', 'No autoresponder statistics have been recorded.');

/**
* Subscriber statistics language variables.
*/
define('LNG_Stats_ListStatistics', 'Mailing List Statistics');
define('LNG_Stats_List_Step1_Heading', 'Mailing List Statistics');
define('LNG_Stats_List_Step1_Intro', 'To get started, please choose a mailing list to view statistics for.');

define('LNG_Stats_List_Step2_Heading', 'Mailing List Statistics');
define('LNG_Stats_List_Step2_Intro', 'View statistics for mailing list \'%s\'');
define('LNG_NoSubscribersStats', 'No subscribers are on mailing list \'%s\'');


/**
* Newsletter Stats Snapshot
*/
define('LNG_Stats_Newsletter_Summary_Graph', 'Email Campaign Summary Information');

define('LNG_Newsletter_Summary_Graph_openchart', 'Email Campaign Opens');
define('LNG_Newsletter_Summary_Graph_unsubscribechart', 'Email Campaign Unsubscribes');
define('LNG_Newsletter_Summary_Graph_forwardschart', 'Email Campaign Forwards');
define('LNG_Newsletter_Summary_Graph_linkschart', 'Email Campaign Links');
define('LNG_Newsletter_Summary_Graph_bouncechart', 'Email Campaign Bounces');
define('LNG_Newsletter_Summary_Graph_userchart', 'Emails Sent');

define('LNG_NewsletterStatistics_Snapshot', 'Statistics Snapshot');
define('LNG_NewsletterStatistics_Snapshot_OpenStats', 'Open Stats');
define('LNG_NewsletterStatistics_Snapshot_LinkStats', 'Link Stats');
define('LNG_NewsletterStatistics_Snapshot_UnsubscribeStats', 'Unsubscribe Stats');
define('LNG_NewsletterStatistics_Snapshot_ForwardStats', 'Forwarding Stats');
define('LNG_NewsletterStatistics_Snapshot_Summary', 'View a summary for email campaign \'%s\', sent %s');
define('LNG_NewsletterStatistics_Snapshot_Heading', 'Statistics Snapshot');
define('LNG_NewsletterStatistics_StartSending', 'Start Sending');
define('LNG_NewsletterStatistics_FinishSending', 'Finished Sending');
define('LNG_NewsletterStatistics_SendingTime', 'Sending Time');
define('LNG_NewsletterStatistics_SentTo', 'Sent To');
define('LNG_NewsletterStatistics_SentBy', 'Sent By');
define('LNG_NewsletterStatistics_Opened', 'Opened');
define('LNG_NotFinishedSending', 'Not finished sending');
define('LNG_NewsletterStatistics_Snapshot_SendSize', '%s of %s');
Define('LNG_EmailOpens_Unique', '%s Unique Opens');
Define('LNG_EmailOpens_Total', '%s Total Opens');
define('LNG_PreviewThisNewsletter', 'Preview this email campaign');
define('LNG_SentToLists', 'Mailing Lists');
define('LNG_SentToList', 'Mailing List');

define('LNG_DateOpened', 'Date Opened');
define('LNG_NewsletterStatistics_Snapshot_OpenHeading', 'View open rates and email addresses for email campaign \'%s\', sent %s');
define('LNG_NewsletterStatistics_Snapshot_OpenHeading_Unique', 'View <b>unique</b> open rates and email addresses for email campaign \'%s\', sent %s');

define('LNG_NewsletterHasNotBeenOpened', 'This email campaign has not yet been opened by any recipients.');
define('LNG_NewsletterHasNotBeenOpened_CalendarProblem', 'This email campaign has not yet been opened by any recipients during the selected date range.');


define('LNG_NewsletterStatistics_Snapshot_LinkHeading', 'View link click statistics for email campaign \'%s\', sent %s');
define('LNG_NewsletterWasNotOpenTracked', 'Open tracking has been disabled for this email campaign.');
define('LNG_NewsletterHasNotBeenClicked', 'No links have been clicked by any subscribers yet.');
define('LNG_NewsletterHasNotBeenClicked_NoLinksFound', 'No links were found in this email campaign.');
define('LNG_NewsletterHasNotBeenClicked_CalendarProblem', 'No links have been clicked by any subscribers during the selected date range.');
define('LNG_NewsletterHasNotBeenClicked_LinkProblem', 'The link chosen has not been clicked by any subscribers.');
define('LNG_NewsletterHasNotBeenClicked_CalendarLinkProblem', 'The link chosen has not been clicked by any subscribers during the selected date range.');
define('LNG_NewsletterWasNotTracked_Links', 'Link tracking has been disabled for this email campaign.');

define('LNG_NewsletterStatistics_Snapshot_UnsubscribesHeading', 'View unsubscribe rates and email addresses for newsletter \'%s\', sent %s');
define('LNG_NewsletterHasNoUnsubscribes', 'This email campaign has not yet received any unsubscribe requests.');
define('LNG_NewsletterHasNoUnsubscribes_CalendarProblem', 'This email campaign has not yet received any unsubscribe requests during the selected date range.');

define('LNG_NewsletterStatistics_Snapshot_ForwardsHeading', 'View email forwarding details for email campaign \'%s\', sent %s');
define('LNG_NewsletterHasNoForwards', 'This email campaign has not yet been forwarded or did not include a send-to-friend link.');
define('LNG_NewsletterHasNoForwards_CalendarProblem', 'This email campaign has not been forwarded to anyone during the selected date range or did not contain a send-to-friend link.');


/**
* autoresponder stuff
*/
define('LNG_AutoresponderStatistics_Snapshot', 'Statistics Snapshot');
define('LNG_AutoresponderStatistics_Snapshot_OpenStats', 'Open Stats');
define('LNG_AutoresponderStatistics_Snapshot_LinkStats', 'Link Stats');
define('LNG_AutoresponderStatistics_Snapshot_UnsubscribeStats', 'Unsubscribe Stats');
define('LNG_AutoresponderStatistics_Snapshot_ForwardStats', 'Forwarding Stats');
define('LNG_AutoresponderStatistics_Snapshot_Summary', 'View a summary for autoresponder \'%s\'');
define('LNG_AutoresponderStatistics_Snapshot_Heading', 'Statistics Snapshot');
define('LNG_AutoresponderStatistics_StartSending', 'Start Sending');
define('LNG_AutoresponderStatistics_FinishSending', 'Finished Sending');
define('LNG_AutoresponderStatistics_SendingTime', 'Sending Time');
define('LNG_AutoresponderStatistics_SentTo', 'Sent To');
define('LNG_AutoresponderStatistics_SentBy', 'Sent By');
define('LNG_AutoresponderStatistics_Opened', 'Opened');
define('LNG_AutoresponderStatistics_Snapshot_SendSize', '%s of %s');
define('LNG_PreviewThisAutoresponder', 'Preview this autoresponder');
define('LNG_AutoresponderStatistics_Snapshot_OpenHeading', 'View open rates and email addresses for autoresponder \'%s\'');
define('LNG_AutoresponderStatistics_Snapshot_OpenHeading_Unique', 'View <b>unique</b> open rates and email addresses for autoresponder \'%s\'');

define('LNG_AutoresponderHasNotBeenOpened', 'The autoresponder has not been opened by any subscribers yet.');
define('LNG_AutoresponderHasNotBeenOpened_CalendarProblem', 'The autoresponder has not been opened by any subscribers during the selected date range.');


define('LNG_AutoresponderStatistics_Snapshot_LinkHeading', 'View link click statistics for autoresponder \'%s\'');
define('LNG_AutoresponderWasNotOpenTracked', 'Open tracking has been disabled for this autoresponder.');
define('LNG_AutoresponderHasNotBeenClicked', 'No links have been clicked by any subscribers yet.');
define('LNG_AutoresponderHasNotBeenClicked_NoLinksFound', 'No links were found in this autoresponder.');
define('LNG_AutoresponderHasNotBeenClicked_CalendarProblem', 'No links have been clicked by any subscribers during the selected date range.');
define('LNG_AutoresponderHasNotBeenClicked_LinkProblem', 'The link chosen has not been clicked by any subscribers.');
define('LNG_AutoresponderHasNotBeenClicked_CalendarLinkProblem', 'The link chosen has not been clicked by any subscribers during the selected date range.');
define('LNG_AutoresponderWasNotTracked_Links', 'Link tracking has been disabled for this autoresponder.');

define('LNG_AutoresponderStatistics_Snapshot_UnsubscribesHeading', 'View unsubscribe rates and email addresses for autoresponder \'%s\'');
define('LNG_AutoresponderHasNoUnsubscribes', 'The autoresponder has not had any unsubscribe requests yet.');
define('LNG_AutoresponderHasNoUnsubscribes_CalendarProblem', 'The autoresponder has not had any unsubscribe requests during the selected date range.');

define('LNG_AutoresponderStatistics_Snapshot_ForwardsHeading', 'View email forwarding details for autoresponder \'%s\'');
define('LNG_AutoresponderHasNoForwards', 'The autoresponder has not been forwarded to anyone yet, or did not have a send-to-friend link in it.');
define('LNG_AutoresponderHasNoForwards_CalendarProblem', 'The autoresponder has not been forwarded to anyone during the selected date range.');

define('LNG_AutoresponderStatistics_CreatedBy', 'Created By');
define('LNG_AutoresponderStatistics_SentWhen', 'Sent When');

define('LNG_ForwardedBy', 'Forwarded By');
define('LNG_ForwardedTo', 'Forwarded To');
define('LNG_ForwardTime', 'Date Forwarded');
define('LNG_HasSubscribed', 'Is Subscribed to List?');

define('LNG_LinkClicked', 'Link Clicked');
define('LNG_LinkClickTime', 'Click Time');
define('LNG_AnyLink', '-- View stats for all links --');

define('LNG_Today', 'Today');
define('LNG_Yesterday', 'Yesterday');
define('LNG_Last24Hours', 'Last 24 Hours');
define('LNG_Last7Days', 'Last 7 Days');
define('LNG_Last30Days', 'Last 30 Days');
define('LNG_ThisMonth', 'This Month');
define('LNG_LastMonth', 'Last Month');
define('LNG_AllTime', 'All Time');
define('LNG_DateRange', 'Date Range');

define('LNG_Newsletter_Summary_Graph', 'Email Campaign Summary Graph');
define('LNG_Summary_Graph_Opened', 'Opened (%s %%)');
define('LNG_Summary_Graph_Unopened', 'Unopened (%s %%)');

define('LNG_Autoresponder_Summary_Graph', 'Autoresponder Summary Graph');

define('LNG_Autoresponder_Summary_Graph_openchart', 'Autoresponder Opens');
define('LNG_Autoresponder_Summary_Graph_unsubscribechart', 'Autoresponder Unsubscribes');
define('LNG_Autoresponder_Summary_Graph_forwardschart', 'Autoresponder Forwards');
define('LNG_Autoresponder_Summary_Graph_linkschart', 'Autoresponder Links');

/**
* subscriber/mailing list stats.
*/
define('LNG_List_Summary_Graph_subscribersummary', 'Subscriber Summary');
define('LNG_ListStatistics_Snapshot', 'Subscribers Snapshot');
define('LNG_ListStatistics_Snapshot_PerDomain', 'Top 10 Domain Name Subscribers');
define('LNG_Subscribes', 'Subscribes');
define('LNG_Unsubscribes', 'Unsubscribes');
define('LNG_Forwards', 'Forwards');
define('LNG_DateTime', 'Date/Time');
define('LNG_New_Subscribes', 'New Subscribes');
define('LNG_DomainName', 'Domain Name');
define('LNG_SubscribeCount', 'Subscribes');
define('LNG_ForwardCount', 'Forwards');
define('LNG_Summary_Domain_Name', '\'%s\' (%s %%)');
define('LNG_Unknown_List', 'Unknown Mailing List');
define('LNG_ListStatsTotalSubscribers', 'Total Subscribers: ');
define('LNG_ListStatsTotalUnsubscribes', 'Total Unsubscribes: ');
define('LNG_ListStatsTotalForwards', 'Total Forwards: ');
define('LNG_ListStatsTotalForwardSignups', 'Total Signups: ');

define('LNG_List_Summary_Graph_openchart', 'Open Statistics');
define('LNG_ListStatistics_Snapshot_OpenStats', 'Open Stats');
define('LNG_ListStatistics_Snapshot_OpenHeading', 'View open rates and email addresses for email campaigns and autoresponders sent to list \'%s\'');
define('LNG_ListStatistics_Snapshot_OpenHeading_Unique', 'View <b>unique</b> open rates and email addresses for email campaigns and autoresponders sent to list \'%s\'');
define('LNG_ListOpenStatsHasNotBeenOpened', 'No email campaigns or autoresponders have been opened.');
define('LNG_ListOpenStatsHasNotBeenOpened_CalendarProblem', 'No email campaigns or autoresponders have been opened in the date range selected.');


define('LNG_List_Summary_Graph_linkschart', 'Links Chart');
define('LNG_ListStatistics_Snapshot_LinksStats', 'Link Stats');
define('LNG_ListStatistics_Snapshot_LinkHeading', 'Link statistics for email campaigns and autoresponders sent to mailing list \'%s\'');
define('LNG_ListLinkStatsHasNotBeenClicked', 'No links in email campaigns or autoresponders sent to this list have been clicked.');
define('LNG_ListLinkStatsHasNotBeenClicked_CalendarProblem', 'No links have been found in any email campaigns or autoresponders sent to this mailing list in the date range selected.');

define('LNG_ListLinkStatsHasNotBeenClicked_NoLinksFound', 'No links have been found in any email campaigns or autoresponders sent to this mailing list.');
define('LNG_ListLinkStatsHasNotBeenClicked_CalendarLinkProblem', 'The selected link has not been clicked in the date range selected.');
define('LNG_ListLinkStatsHasNotBeenClicked_LinkProblem', 'No clicks have been detected for the link chosen');

define('LNG_List_Summary_Graph_unsubscribechart', 'Unsubscribe Chart');
define('LNG_ListStatistics_Snapshot_UnsubscribeStats', 'Unsubscribe Stats');
define('LNG_ListStatistics_Snapshot_UnsubscribesHeading', 'Unsubscribe statistics for email campaigns and autoresponders sent to mailing list \'%s\'');
define('LNG_ListHasNoUnsubscribes', 'No subscribers have unsubscribed from this mailing list.');
define('LNG_ListHasNoUnsubscribes_CalendarProblem', 'No subscribers have unsubscribed from this mailing list during the date range selected.');

define('LNG_List_Summary_Graph_forwardschart', 'Forwarding Statistics');
define('LNG_ListStatistics_Snapshot_ForwardsStats', 'Forward Stats');
define('LNG_ListStatistics_Snapshot_ForwardsHeading', 'Forward statistics for email campaigns and autoresponders sent to mailing list \'%s\'');
define('LNG_ListHasNoForwards', 'No subscribers have forwarded email campaigns or autoresponders sent to this list.');
define('LNG_ListHasNoForwards_CalendarProblem', 'No subscribers have forwarded email campaigns or autoresponders sent to this list in the date range selected.');


/**
* Bounce messages
*/
define('LNG_NewsletterStatistics_Snapshot_BounceStats', 'Bounce Stats');
define('LNG_NewsletterStatistics_Bounced', 'Bounced');
define('LNG_NewsletterStatistics_Snapshot_BounceHeading', 'View bounce statistics for email campaigns \'%s\', sent %s');
define('LNG_NewsletterHasNotBeenBounced', 'No bounce reports have been received for this email campaign.');
define('LNG_NewsletterHasNotBeenBounced_BounceType', 'No %ss have been received for this email campaign.');
define('LNG_NewsletterHasNotBeenBounced_CalendarProblem', 'No bounce reports have been received for this email campaign in the selected date range.');
define('LNG_NewsletterHasNotBeenBounced_CalendarProblem_BounceType', 'No %ss have occurred in the selected date range.');
define('LNG_AutoresponderStatistics_Snapshot_BounceStats', 'Bounce Stats');
define('LNG_AutoresponderStatistics_Bounced', 'Bounced');
define('LNG_AutoresponderStatistics_Snapshot_BounceHeading', 'View bounce statistics for autoresponder \'%s\'');

define('LNG_AutoresponderHasNotBeenBounced', 'No bounce reports have been received for this autoresponder.');
define('LNG_AutoresponderHasNotBeenBounced_BounceType', 'No %ss have been received for this autoresponder.');
define('LNG_AutoresponderHasNotBeenBounced_CalendarProblem', 'No bounce reports have been received for this autoresponder in the selected date range.');
define('LNG_AutoresponderHasNotBeenBounced_CalendarProblem_BounceType', 'No %ss have occurred in the selected date range.');

define('LNG_Summary_Graph_Bounced', 'Bounced (%s %%)');
define('LNG_Bounces', 'Bounces');
define('LNG_ListStatistics_Snapshot_BounceStats', 'Bounce Stats');
define('LNG_ListStatistics_Snapshot_BounceHeading', 'Bounce statistics for email campaigns and autoresponders sent to mailing list \'%s\'');
define('LNG_ListStatsHasNotBeenBounced', 'No emails that have been sent to this list have bounced.');
define('LNG_ListStatsHasNotBeenBounced_BounceType', 'No %ss have been received for subscribers on this mailing list.');
define('LNG_ListStatsHasNotBeenBounced_CalendarProblem', 'No bounce reports have been received for subscribers on this mailing list in the selected date range.');
define('LNG_ListStatsHasNotBeenBounced_CalendarProblem_BounceType', 'No %ss have occurred in the selected date range.');

define('LNG_BounceCount', 'Bounces');
define('LNG_Stats_TotalBounces', 'Total Bounces: ');
define('LNG_Bounce_Summary', 'Bounce Summary');
define('LNG_Stats_TotalSoftBounces', 'Total Soft Bounces: ');
define('LNG_Stats_TotalHardBounces', 'Total Hard Bounces: ');
define('LNG_BounceChart', 'Bounce Chart');
define('LNG_Bounce_Type_hard', 'Hard Bounce');
define('LNG_Bounce_Type_soft', 'Soft Bounce');
define('LNG_Bounce_Type_any', 'Any Bounce Type');
define('LNG_BounceType', 'Bounce Type');
define('LNG_BounceRule', 'Bounce Rule');
define('LNG_BounceDate', 'Bounce Date');
define('LNG_Bounce_Type_hard_bounce', LNG_Bounce_Type_hard);
define('LNG_Bounce_Type_soft_bounce', LNG_Bounce_Type_soft);
define('LNG_Bounce_Type_unknown_bounce', 'Unknown Bounce');

define('LNG_Bounce_Rule_emaildoesntexist', 'Email Address doesn\'t exist');
define('LNG_Bounce_Rule_domaindoesntexist', 'Domain Name doesn\'t exist');
define('LNG_Bounce_Rule_invalidemail', 'Invalid Email Address');
define('LNG_Bounce_Rule_relayerror', 'Relay Error');

define('LNG_Bounce_Rule_overquota', 'Over Quota');
define('LNG_Bounce_Rule_inactive', 'Inactive Email Account');

define('LNG_Stats_TotalBouncedEmails', 'Total Bounced Emails');
define('LNG_HardBounces', 'Hard Bounces');
define('LNG_SoftBounces', 'Soft Bounces');
define('LNG_Stats_NoSubscribersOnList', 'There are no subscribers on this mailing list.');
define('LNG_Stats_NoSubscribersOnList_DateRange', 'There are no subscribers on this mailing list in the selected date range.');

/**
**************************
* Changed/added in NX1.0.5
**************************
*/
define('LNG_Bounce_Rule_blockedcontent', 'Blocked due to content');

/**
**************************
* Changed/added in NX1.0.7
**************************
*/
define('LNG_Bounce_Rule_remoteconfigerror', 'Problem with remote servers configuration');
define('LNG_Bounce_Rule_localconfigerror', 'Problem with local servers configuration');

/**
**************************
* Changed/added in NX1.1.2
**************************
*/
define('LNG_Daily_Time_Display', 'ga');
define('LNG_DOW_Word_Display', 'D');
define('LNG_DOW_Word_Full_Display', 'l');
define('LNG_DOM_Number_Display', 'd');
define('LNG_Date_Display', 'D, jS M');

/**
**************************
* Changed/added in NX1.1.3
**************************
*/
define('LNG_Date_Display_Display', 'D, d. M');


?>
