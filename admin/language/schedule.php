<?php
/**
* Language file variables for the schedule management area.
*
* @see GetLang
*
* @version     $Id: schedule.php,v 1.16 2006/12/08 03:09:02 chris Exp $

*
* @package SendStudio
* @subpackage Language
*/

/**
* Here are all of the variables for the schedule area... Please backup before you start!
*/

define('LNG_ScheduleManage', 'Manage Scheduling');
define('LNG_Help_ScheduleManage', ' Use the form below to manage your scheduled email campaigns. <br>To schedule an email campaign, click the \'Send Email Campaign\' button and modify the \'Scheduled Sending Options\'');
define('LNG_DateScheduled', 'Date Scheduled');
define('LNG_Schedule_Empty', 'Nothing is scheduled to be sent.%s');
define('LNG_NoSchedules_HasAccess', ' Click the "Send Button" below to send an email campaign.');

define('LNG_JobPausedSuccess', 'Job paused successfully.');
define('LNG_JobPausedFail', 'Job not paused successfully. You cannot pause jobs that have not been started.');

define('LNG_JobResumedSuccess', 'Email campaign sending has been resumed successfully.');
define('LNG_JobResumedFail', 'Email campaign sending has not resumed successfully.');

define('LNG_UnableToLoadJob', 'Unable to load job. Please try again.');
define('LNG_UnableToEditJob_InProgress', 'Unable to edit this job. It is currently in progress. Pause the job before trying again.');

define('LNG_Schedule_Edit', 'Edit Send Schedule');
define('LNG_Help_Schedule_Edit', 'Use the form below to update your scheduled email campaign details.');
define('LNG_ScheduleEditCancel_Prompt', 'Are you sure you want to cancel editing your scheduled email campaign?');

define('LNG_JobDeleteSuccess', 'The scheduled item has been deleted successfully');
define('LNG_JobsDeleteSuccess', '%s scheduled items have been deleted successfully');
define('LNG_JobDeleteFail', 'The scheduled item has not been deleted. You cannot delete a scheduled item while it is in progress.');
define('LNG_JobsDeleteFail', '%s scheduled items have not been deleted. You cannot delete a scheduled item while it is in progress.');

define('LNG_SendThisNewsletterButton', 'Send this email campaign');

define('LNG_CronWillRunInApproximately', 'Cron (the scheduled sending system) will run in: %s');

define('LNG_ChooseSchedulesToDelete', 'Please choose one or more schedules to delete first.');
define('LNG_ConfirmRemoveSchedules', 'Are you sure you want to remove these schedules?');
define('LNG_DeleteSchedulePrompt', 'Are you sure you want to delete this schedule?');

define('LNG_Refresh', 'Refresh');
define('LNG_JobScheduledOK', 'Your job has been scheduled.');

define('LNG_WaitingForApproval', 'Waiting for Approval');
define('LNG_WaitingForApproval_Description', 'The user who sent this email was trying to send too many emails. Click approve to let this email go through, or delete the scheduled event.');
define('LNG_JobApprovedFail_NotAdmin', 'You are not an administrator so you cannot approve this job.');
define('LNG_JobApprovedSuccess', 'Job approved successfully. It will be sent out according to the schedule.');
define('LNG_JobApprovedFail', 'Job not approved successfully. Please try again.');

define('LNG_CannotChangeAScheduleOnceItHasStarted', 'You cannot alter a scheduled event once it has started. To schedule this email campaign again, please start a new schedule.');
?>
