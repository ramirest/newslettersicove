<?php
/**
* The Upgrade API.
*
* @version     $Id: upgrade.php,v 1.7 2007/06/18 03:29:39 chris Exp $

*
* @package API
* @subpackage User_API
*/

/**
* Include the base api class if we need to.
*/
require_once(dirname(__FILE__) . '/api.php');

/**
* This will load a user, save a user, set details and get details.
* It will also check access areas.
*
* @package API
* @subpackage User_API
*/
class Upgrade_API extends API
{

	var $FriendlyDescription = false;

	var $error = null;

	var $offset_query = '';

	var $upgrades_to_run = array(
		'nx' => array(
		#	'banned_emails_drop_status',
			'banned_emails_add_banid',
			'banned_emails_change_email',
			'banned_emails_change_dateadded',
			'banned_emails_change_listid',

			'customfield_lists_create',
			'customfield_lists_insert',

			'customfields_rename',
			'customfields_change_fieldid',
			'customfields_change_fieldname',
			'customfields_change_fieldtype',
			'customfields_change_defaultvalue',
			'customfields_change_required',
			'customfields_change_fieldsettings',
			'customfields_change_owner',
			'customfields_add_createdate',
			'customfields_fix_fieldsettings',

			'jobs_create',

			'jobs_list_create',

			'queues_create',
			'queues_processed_index',
			'queues_recipient_index',
			'queues_sequence_create',
			'queues_sequence_insert',

			'lists_change_listid',
			'lists_change_createdate',
			'lists_change_format',
			'lists_change_ownername',
			'lists_change_owneremail',
			'lists_change_notifyowner',
			'lists_change_name',
			'lists_drop_status',
			'lists_drop_cansubscribe',
			'lists_drop_canunsubscribe',
			'lists_add_bounceemail',
			'lists_add_replytoemail',
			'lists_add_bounceserver',
			'lists_add_bounceusername',
			'lists_add_bouncepassword',
			'lists_add_extramailsettings',
			'lists_add_imapaccount',
			'lists_add_subscribecount',
			'lists_add_unsubscribecount',
			'lists_add_bouncecount',
			'lists_add_ownerid',
			'lists_set_new_defaults',
			'lists_set_format_multipart',
			'lists_set_format_html',
			'lists_set_format_text',

			'users_rename',
			'users_change_userid',
			'users_change_name',
			'users_change_username',
			'users_change_password',
			'users_change_emailaddress',
			'users_change_status',
			'users_change_perhour',
			'users_change_permonth',
			'users_change_maxlists',
			'users_change_lastloggedin',
			'users_drop_loginstring',
			'users_drop_quickstart',
			'users_drop_summaries',
			'users_drop_attachments',
			'users_add_settings',
			'users_add_editownsettings',
			'users_add_unlimitedmaxemails',
			'users_add_textfooter',
			'users_add_htmlfooter',
			'users_change_smtpport',
			'users_change_smtpserver',
			'users_add_smtpusername',
			'users_add_smtppassword',
			'users_add_admintype',
			'users_add_listadmintype',
			'users_add_templateadmintype',
			'users_add_usertimezone',
			'users_add_infotips',
			'users_add_createdate',
			'users_add_maxemails',
			'users_add_forgotpasscode',
			'users_update_defaults',
			'users_update_smtpdetails',
			'users_update_adminpermissions',
			'users_drop_manager',
			'users_drop_root',

			'user_access_create',
			'user_access_convert',
			'user_access_convertowner',

			'user_permissions_create',
			'user_permissions_change_subscriber',
			'user_permissions_change_list',
			'user_permissions_change_forms',
			'user_permissions_change_customfields',
			'user_permissions_change_templates',
			'user_permissions_change_newsletters',
			'user_permissions_change_send',
			'user_permissions_change_autoresponders',
			'user_permissions_change_statistics',

			'templates_change_templateid',
			'templates_change_name',
			'templates_change_format',
			'templates_change_textbody',
			'templates_change_htmlbody',
			'templates_change_createdate',
			'templates_change_ownerid',
			'templates_add_active',
			'templates_add_isglobal',
			'templates_set_new_defaults',
			'templates_set_format_multipart',
			'templates_set_format_html',
			'templates_set_format_text',

			'settings_create',
			'settings_set_new_defaults',

			'form_lists_change_formid',
			'form_lists_change_listid',

			'form_customfields_rename',
			'form_customfields_change_formid',
			'form_customfields_change_fieldid',
			'form_customfields_change_fieldorder',
			'form_customfields_drop_adminid',
			'form_customfields_fix_fieldorder',

			'form_pages_create',
			'form_pages_create_responses',

			'forms_change_formid',
			'forms_change_name',
			'forms_change_requireconfirm',
			'forms_change_sendthanks',
			'forms_change_ownerid',
			'forms_change_createdate',
			'forms_add_contactform',
			'forms_add_usecaptcha',
			'forms_add_changeformat',
			'forms_add_chooseformat',
			'forms_add_design',
			'forms_add_formhtml',
			'forms_set_new_defaults',
			'forms_change_formtype',
			'forms_drop_status',
			'forms_drop_formcode',
			'forms_drop_selectlists',
			'forms_drop_contenttypeid',
			'forms_drop_templateid',
			'forms_drop_sendemail',
			'forms_drop_sendname',

			'list_subscriber_bounces_create',

			'list_subscribers_unsubscribe_create',

			'list_subscribers_rename',
			'list_subscribers_change_memberid',
			'list_subscribers_change_listid',
			'list_subscribers_change_emailaddress',
			'list_subscribers_change_format',
			'list_subscribers_update_format_html',
			'list_subscribers_update_format_text',
			'list_subscribers_change_confirmed',
			'list_subscribers_change_confirmcode',
			'list_subscribers_change_subscribedate',
			'list_subscribers_change_formid',
			'list_subscribers_drop_importid',
			'list_subscribers_add_confirmip',
			'list_subscribers_add_requestip',
			'list_subscribers_add_requestdate',
			'list_subscribers_add_confirmdate',
			'list_subscribers_add_bounced',
			'list_subscribers_add_unsubscribed',
			'list_subscribers_add_unsubscribeconfirmed',
			'list_subscribers_set_unsubscribed',
			'list_subscribers_set_unsubscribeconfirmed',
			'list_subscribers_add_to_unsubscribe',
			'list_subscribers_drop_status',
			'list_subscribers_update_subscribecount',
			'list_subscribers_update_unsubscribecount',

			'autoresponders_change_autoresponderid',
			'autoresponders_change_listid',
			'autoresponders_change_hoursaftersubscription',
			'autoresponders_change_format',
			'autoresponders_change_subject',
			'autoresponders_change_sendfromemail',
			'autoresponders_change_sendfromname',
			'autoresponders_change_bounceemail',
			'autoresponders_change_replytoemail',
			'autoresponders_change_htmlbody',
			'autoresponders_change_textbody',
			'autoresponders_change_createdate',
			'autoresponders_change_searchcriteria',
			'autoresponders_change_ownerid',
			'autoresponders_change_trackopens',
			'autoresponders_change_tracklinks',
			'autoresponders_change_multipart',
			'autoresponders_change_name',
			'autoresponders_drop_attachmentids',
			'autoresponders_add_queueid',
			'autoresponders_add_active',
			'autoresponders_add_charset',
			'autoresponders_add_to_firstname',
			'autoresponders_add_to_lastname',
			'autoresponders_add_embedimages',
			'autoresponders_set_new_defaults',
			'autoresponders_set_createdate',
			'autoresponders_fix_queues',
			'list_subscribers_drop_lastresponderid',
			'autoresponders_set_format_multipart',
			'autoresponders_set_format_html',
			'autoresponders_set_format_text',

			'newsletters_rename',
			'newsletters_change_newsletterid',
			'newsletters_change_name',
			'newsletters_change_createdate',
			'newsletters_change_subject',
			'newsletters_change_textbody',
			'newsletters_change_htmlbody',
			'newsletters_change_ownerid',
			'newsletters_change_format',
			'newsletters_drop_attachmentids',
			'newsletters_add_active',
			'newsletters_add_archive',
			'newsletters_set_format_multipart',
			'newsletters_set_format_html',
			'newsletters_set_format_text',
			'newsletters_set_new_defaults',

			'subscriber_data_rename',
			'subscriber_data_drop_listid',
			'subscriber_data_change_subscriberid',
			'subscriber_data_change_fieldid',
			'subscriber_data_change_data',
			'subscriber_data_fix_data',

			'stats_sequence_create',
			'stats_sequence_insert',
			'stats_newsletter_lists_create',
			'stats_newsletters_create',
			'stats_users_create',
			'stats_emailopens_create',
			'user_stats_emailsperhour_create',
			'stats_linkclicks_create',
			'stats_links_create',
			'stats_emailforwards_create',
			'stats_autoresponders_create',

			'links_new_create',
			'links_new_populate',

			'stats_newsletters_convert',
			'stats_autoresponders_convert',

			'links_old_rename',
			'links_new_rename',

			'drop_unused_table_sends',
			'drop_unused_table_sends_perhour',
			'drop_unused_table_sends_permonth',
			'drop_unused_table_server_sends',
			'drop_unused_table_allow_functions',
			'drop_unused_table_email_opens',
			'drop_unused_table_form_responses',
			'drop_unused_table_link_clicks',
			'drop_unused_table_send_recipients',
			'drop_unused_table_autoresponder_recipients',
			'drop_unused_table_imports',
			'drop_unused_table_import_mappings',
			'drop_unused_table_export_users',
			'drop_unused_table_exports',
			'drop_unused_table_attachments',
			'drop_unused_table_allow_lists',

			'newsletters_fix_content',
			'autoresponders_fix_content',
			'templates_fix_content',
			'form_generate_modifydetails',

			'timezone_fix_banned_emails',
			'timezone_fix_customfields',
			'timezone_fix_lists',
			'timezone_fix_users',
			'timezone_fix_templates',
			'timezone_fix_forms',
			'timezone_fix_autoresponders',
			'timezone_fix_newsletters',
			'timezone_fix_newsletter_stats',
			'timezone_fix_user_stats',
			'timezone_fix_open_stats',
			'timezone_fix_user_stats_perhour',
			'timezone_fix_link_stats',

			'optimize_table_banned_emails',
			'optimize_table_customfield_lists',
			'optimize_table_customfields',
			'optimize_table_jobs',
			'optimize_table_jobs_lists',
			'optimize_table_queues',
			'optimize_table_queues_sequence',
			'optimize_table_lists',
			'optimize_table_users',
			'optimize_table_user_access',
			'optimize_table_user_permissions',
			'optimize_table_templates',
			'optimize_table_settings',
			'optimize_table_form_lists',
			'optimize_table_form_customfields',
			'optimize_table_form_pages',
			'optimize_table_forms',
			'optimize_table_list_subscriber_bounces',
			'optimize_table_list_subscribers_unsubscribe',
			'optimize_table_list_subscribers',
			'optimize_table_autoresponders',
			'optimize_table_newsletters',
			'optimize_table_subscribers_data',
			'optimize_table_stats_sequence',
			'optimize_table_stats_newsletter_lists',
			'optimize_table_stats_newsletters',
			'optimize_table_stats_users',
			'optimize_table_stats_emailopens',
			'optimize_table_user_stats_emailsperhour',
			'optimize_table_stats_linkclicks',
			'optimize_table_stats_links',
			'optimize_table_stats_emailforwards',
			'optimize_table_stats_autoresponders',
			'optimize_table_links',
		)
	);

	/**
	* Constructor
	* Sets up the database object, loads the user if the ID passed in is not 0.
	*
	* @param Int $userid The userid of the user to load. If it is 0 then you get a base class only. Passing in a userid > 0 will load that user.
	*
	* @see GetDb
	* @see Load
	*
	* @return True|Load If no userid is present, this always returns true. Otherwise it returns the status from Load
	*/
	function Upgrade_API()
	{
		if (is_object($this->Db) && is_resource($this->Db->connection)) {
			return true;
		}

		$db_type = SENDSTUDIO_DATABASE_TYPE . 'Db';

		if (!class_exists($db_type)) {
			require(SENDSTUDIO_LIB_DIRECTORY . '/database/' . SENDSTUDIO_DATABASE_TYPE . '.php');
		}

		$db = &new $db_type();

		$connection = $db->Connect(SENDSTUDIO_DATABASE_HOST, SENDSTUDIO_DATABASE_USER, SENDSTUDIO_DATABASE_PASS, SENDSTUDIO_DATABASE_NAME);

		$this->Db = &$db;
	}

	function RunUpgrade($version=false, $upgrade=false)
	{
		$class = &new $upgrade;

		$upgrade_result = $class->RunUpgrade();

		if ($upgrade_result) {
			return true;
		}

		if (!isset($class->errormessage)) {
			$class_err = $class->Db->GetError();

			$this->error = 'Upgrade for \'' . $upgrade . '\' failed. Reason: \'' . $class_err[0] . '\'';
		} else {
			$this->error = $class->errormessage;
		}
		return false;
	}

	function GetNextUpgrade($version=false)
	{
		if (!$version) {
			$this->error = 'Invalid Version';
			return false;
		}

		if (!in_array($version, array_keys($this->upgrades_to_run))) {
			$this->error = 'Invalid Version';
			return false;
		}

		if (!is_dir(SENDSTUDIO_LANGUAGE_DIRECTORY . '/upgrades/' . $version)) {
			$this->error = 'Invalid Version - Directory Doesn\'t Exist';
			return false;
		}

		$session = &GetSession();
		$upgrades_done = $session->Get('DatabaseUpgradesCompleted');

		foreach ($this->upgrades_to_run[$version] as $p => $upgrade) {
			if (in_array($upgrade, $upgrades_done)) {
				continue;
			}

			$file = SENDSTUDIO_LANGUAGE_DIRECTORY . '/upgrades/' . $version . '/' . $upgrade . '.php';

			if (!is_file($file)) {
				$this->error = 'Upgrade file for \'' . $upgrade . '\' doesn\'t exist';
				return false;
			}

			require($file);

			if (isset($upgrade_description)) {
				$this->FriendlyDescription = $upgrade_description;
			}

			return $upgrade;

		}
		return null;
	}

	function GetNextVersion($current_version=0)
	{
		$dirs = list_directories(SENDSTUDIO_LANGUAGE_DIRECTORY . '/upgrades');

		$between_versions = array();

		foreach ($dirs as $p => $dir) {
			$dirname = str_replace(SENDSTUDIO_LANGUAGE_DIRECTORY . '/upgrades/', '', $dir);

			// between versions are 'numeric' (backwards date format)
			// so ignore any that aren't in that format.
			if (!is_numeric($dirname)) {
				continue;
			}

			/**
			* If we are mid-way through database changes, ie:
			* - our version is '2'
			* and there are upgrades for 1,2,3
			* we want to skip any that are before our current version.
			*/
			if ($dirname <= $current_version) {
				continue;
			}
			$between_versions[] = $dirname;
		}

		/**
		* Since we don't add previous versions to the array, we get the minimum of the versions left.
		*/
		return min($between_versions);
	}

}

?>
