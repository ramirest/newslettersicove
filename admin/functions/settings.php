<?php
/**
* This file has the settings page in it.
*
* @version     $Id: settings.php,v 1.46 2007/06/22 02:39:16 chris Exp $

*
* @package SendStudio
* @subpackage SendStudio_Functions
*/

/**
* Include the base sendstudio functions.
*/
require(dirname(__FILE__) . '/sendstudio_functions.php');

/**
* Class for the settings page. This simply prints out and handles processing. The API does all of the work.
*
* @package SendStudio
* @subpackage SendStudio_Functions
*/
class Settings extends SendStudio_Functions
{

	/**
	* PopupWindows
	* An array of popup windows used in this class. Used to work out what sort of header and footer to print.
	*
	* @see Process
	*
	* @var Array
	*/
	var $PopupWindows = array('sendpreview', 'showinfo', 'upgradedb');

	/**
	* Constructor
	* Loads the language file.
	*
	* @see LoadLanguageFile
	*
	* @return Void Doesn't return anything.
	*/
	function Settings()
	{
		$this->LoadLanguageFile();
		$this->LoadLanguageFile('CharacterSets');
		$this->LoadLanguageFile('TimeZones');
	}


	/**
	* Process
	* Does all the work.
	* Saves settings, Checks details, calls the API to save the actual settings and checks whether it worked or not.
	*
	* @see GetApi
	* @see API::Set
	* @see API::Save
	* @see GetLang
	* @see ParseTemplate
	* @see SendStudio_Functions::Process
	* @see SendPreview
	* @see Settings_API::CheckCron
	* @see Settings_API::UpdateCron
	*
	* @return Void Does all of the processing, doesn't return anything.
	*/
	function Process()
	{
		$action = (isset($_GET['Action'])) ? strtolower(urldecode($_GET['Action'])) : null;

		$user = &GetUser();
		$access = $user->HasAccess('Settings');

		$popup = (in_array($action, $this->PopupWindows)) ? true : false;

		if (!$access) {
			$this->DenyAccess();
			return;
		}

		switch ($action) {
			case 'showinfo':
				$this->PrintHeader(true);
				phpinfo();
				$this->PrintFooter(true);
			break;

			case 'upgradedb':
				$this->UpgradeDb();
			break;

			case 'sendpreview':
				$this->PrintHeader($popup);
				$this->SendPreview();
				$this->PrintFooter($popup);
			break;

			case 'save':
				$api = $this->GetApi();
				$result = false;
				if ($api) {
					$settings = array();

					foreach ($api->Areas as $p => $area) {
						if (isset($_POST[strtolower($area)])) {
							$val = $_POST[strtolower($area)];
						} else {
							$val = false;
						}

						if ($area == 'APPLICATION_URL') {
							if (substr($val, -1) == '/') {
								$val = substr($val, 0, -1);
							}
						}

						if ($area == 'TEXTFOOTER') {
							$val = strip_tags($val);
						}

						if ($area == 'SMTP_USERNAME') {
							if (isset($_POST['smtp_u'])) {
								$val = $_POST['smtp_u'];
							}
						}

						if ($area == 'SMTP_PASSWORD') {
							if (isset($_POST['smtp_p'])) {
								$val = $_POST['smtp_p'];
							}
							$val = base64_encode($val);
						}

						if ($area == 'BOUNCE_USERNAME') {
							if (isset($_POST['bounce_u'])) {
								$val = $_POST['bounce_u'];
							}
						}

						if ($area == 'BOUNCE_PASSWORD') {
							if (isset($_POST['bounce_p'])) {
								$val = $_POST['bounce_p'];
							}
							$val = base64_encode($val);
						}

						if ($area == 'DATABASE_USER') {
							if (isset($_POST['database_u'])) {
								$val = $_POST['database_u'];
							}
						}

						if ($area == 'DATABASE_PASS') {
							if (isset($_POST['database_p'])) {
								$val = $_POST['database_p'];
							}
						}

						$settings[$area] = $val;
						$var = 'SENDSTUDIO_' . $area;
						$$var = $val;
					}
					$api->Set('Settings', $settings);
					$result = $api->Save();
				}

				if ($result) {
					$GLOBALS['Message'] = $this->PrintSuccess('SettingsSaved');
				} else {
					$GLOBALS['Error'] = GetLang('SettingsNotSaved');
					$GLOBALS['Message'] = $this->ParseTemplate('ErrorMsg', true, false);
				}

				

			default:
				

				$SENDSTUDIO_DATABASE_TYPE = (isset($SENDSTUDIO_DATABASE_TYPE)) ? $SENDSTUDIO_DATABASE_TYPE : SENDSTUDIO_DATABASE_TYPE;
				$SENDSTUDIO_DATABASE_USER = (isset($SENDSTUDIO_DATABASE_USER)) ? $SENDSTUDIO_DATABASE_USER : SENDSTUDIO_DATABASE_USER;
				$SENDSTUDIO_DATABASE_HOST = (isset($SENDSTUDIO_DATABASE_HOST)) ? $SENDSTUDIO_DATABASE_HOST : SENDSTUDIO_DATABASE_HOST;
				$SENDSTUDIO_DATABASE_PASS = (isset($SENDSTUDIO_DATABASE_PASS)) ? $SENDSTUDIO_DATABASE_PASS : SENDSTUDIO_DATABASE_PASS;
				$SENDSTUDIO_DATABASE_NAME = (isset($SENDSTUDIO_DATABASE_NAME)) ? $SENDSTUDIO_DATABASE_NAME : SENDSTUDIO_DATABASE_NAME;
				$SENDSTUDIO_TABLEPREFIX = (isset($SENDSTUDIO_TABLEPREFIX)) ? $SENDSTUDIO_TABLEPREFIX : SENDSTUDIO_TABLEPREFIX;
				$SENDSTUDIO_APPLICATION_URL = (isset($SENDSTUDIO_APPLICATION_URL)) ? $SENDSTUDIO_APPLICATION_URL : SENDSTUDIO_APPLICATION_URL;

				$SENDSTUDIO_LICENSEKEY = (isset($SENDSTUDIO_LICENSEKEY)) ? $SENDSTUDIO_LICENSEKEY : SENDSTUDIO_LICENSEKEY;

				$SENDSTUDIO_SMTP_SERVER = (isset($SENDSTUDIO_SMTP_SERVER)) ? $SENDSTUDIO_SMTP_SERVER : SENDSTUDIO_SMTP_SERVER;
				$SENDSTUDIO_SMTP_USERNAME = (isset($SENDSTUDIO_SMTP_USERNAME)) ? $SENDSTUDIO_SMTP_USERNAME : SENDSTUDIO_SMTP_USERNAME;
				$SENDSTUDIO_SMTP_PASSWORD = (isset($SENDSTUDIO_SMTP_PASSWORD)) ? $SENDSTUDIO_SMTP_PASSWORD : SENDSTUDIO_SMTP_PASSWORD;

				$SENDSTUDIO_SMTP_PORT = (isset($SENDSTUDIO_SMTP_PORT)) ? $SENDSTUDIO_SMTP_PORT : SENDSTUDIO_SMTP_PORT;

				$SENDSTUDIO_HTMLFOOTER = (isset($SENDSTUDIO_HTMLFOOTER)) ? $SENDSTUDIO_HTMLFOOTER : SENDSTUDIO_HTMLFOOTER;
				$SENDSTUDIO_TEXTFOOTER = (isset($SENDSTUDIO_TEXTFOOTER)) ? $SENDSTUDIO_TEXTFOOTER : SENDSTUDIO_TEXTFOOTER;

				$SENDSTUDIO_MAXHOURLYRATE = (isset($SENDSTUDIO_MAXHOURLYRATE)) ? $SENDSTUDIO_MAXHOURLYRATE : SENDSTUDIO_MAXHOURLYRATE;
				$SENDSTUDIO_MAXHOURLYRATE = (int)$SENDSTUDIO_MAXHOURLYRATE;

				$SENDSTUDIO_MAXOVERSIZE = (isset($SENDSTUDIO_MAXOVERSIZE)) ? $SENDSTUDIO_MAXOVERSIZE : SENDSTUDIO_MAXOVERSIZE;
				$SENDSTUDIO_MAXOVERSIZE = (int)$SENDSTUDIO_MAXOVERSIZE;

				$SENDSTUDIO_EMAIL_ADDRESS = (isset($SENDSTUDIO_EMAIL_ADDRESS)) ? $SENDSTUDIO_EMAIL_ADDRESS : SENDSTUDIO_EMAIL_ADDRESS;

				if (isset($SENDSTUDIO_FORCE_UNSUBLINK) && $SENDSTUDIO_FORCE_UNSUBLINK == 1) {
					$SENDSTUDIO_FORCE_UNSUBLINK = ' CHECKED';
				}
				if (!isset($SENDSTUDIO_FORCE_UNSUBLINK) && SENDSTUDIO_FORCE_UNSUBLINK == 1) {
					$SENDSTUDIO_FORCE_UNSUBLINK = ' CHECKED';
				}
				if (!isset($SENDSTUDIO_FORCE_UNSUBLINK)) {
					$SENDSTUDIO_FORCE_UNSUBLINK = '';
				}

				$cron_checked = false;
				if (isset($SENDSTUDIO_CRON_ENABLED) && $SENDSTUDIO_CRON_ENABLED == 1) {
					$SENDSTUDIO_CRON_ENABLED = ' CHECKED';
					$cron_checked = true;
				}

				if (!isset($SENDSTUDIO_CRON_ENABLED) && SENDSTUDIO_CRON_ENABLED == 1) {
					$SENDSTUDIO_CRON_ENABLED = ' CHECKED';
					$cron_checked = true;
				}

				if (!isset($SENDSTUDIO_CRON_ENABLED)) {
					$SENDSTUDIO_CRON_ENABLED = '';
				}

				$ip_tracking = false;
				if (isset($SENDSTUDIO_IPTRACKING) && $SENDSTUDIO_IPTRACKING == 1) {
					$SENDSTUDIO_IPTRACKING = ' CHECKED';
					$ip_tracking = true;
				}

				if (!isset($SENDSTUDIO_IPTRACKING) && SENDSTUDIO_IPTRACKING == 1) {
					$SENDSTUDIO_IPTRACKING = ' CHECKED';
					$ip_tracking = true;
				}

				if (!isset($SENDSTUDIO_IPTRACKING)) {
					$SENDSTUDIO_IPTRACKING = '';
				}

				if ($SENDSTUDIO_SMTP_SERVER) {
					$GLOBALS['UseSMTP'] = ' CHECKED';
					$GLOBALS['DisplaySMTP'] = "'';";
				} else {
					$GLOBALS['DisplaySMTP'] = 'none';
				}

				$GLOBALS['ServerTimeZone'] = SENDSTUDIO_SERVERTIMEZONE;

				$GLOBALS['ServerTimeZoneDescription'] = GetLang(SENDSTUDIO_SERVERTIMEZONE);

				$GLOBALS['ServerTime'] = date('r');

				$SENDSTUDIO_MAX_IMAGEWIDTH = (isset($SENDSTUDIO_MAX_IMAGEWIDTH)) ? $SENDSTUDIO_MAX_IMAGEWIDTH : SENDSTUDIO_MAX_IMAGEWIDTH;

				$SENDSTUDIO_MAX_IMAGEHEIGHT = (isset($SENDSTUDIO_MAX_IMAGEHEIGHT)) ? $SENDSTUDIO_MAX_IMAGEHEIGHT : SENDSTUDIO_MAX_IMAGEHEIGHT;


				if (!isset($SENDSTUDIO_BOUNCE_IMAP) && SENDSTUDIO_BOUNCE_IMAP == 1) {
					$SENDSTUDIO_BOUNCE_IMAP = ' CHECKED';
				}

				if (isset($SENDSTUDIO_BOUNCE_IMAP) && $SENDSTUDIO_BOUNCE_IMAP == 1) {
					$SENDSTUDIO_BOUNCE_IMAP = ' CHECKED';
				}

				$SENDSTUDIO_BOUNCE_ADDRESS = (isset($SENDSTUDIO_BOUNCE_ADDRESS)) ? $SENDSTUDIO_BOUNCE_ADDRESS : SENDSTUDIO_BOUNCE_ADDRESS;
				$SENDSTUDIO_BOUNCE_SERVER = (isset($SENDSTUDIO_BOUNCE_SERVER)) ? $SENDSTUDIO_BOUNCE_SERVER : SENDSTUDIO_BOUNCE_SERVER;
				$SENDSTUDIO_BOUNCE_USERNAME = (isset($SENDSTUDIO_BOUNCE_USERNAME)) ? $SENDSTUDIO_BOUNCE_USERNAME : SENDSTUDIO_BOUNCE_USERNAME;
				$SENDSTUDIO_BOUNCE_PASSWORD = (isset($SENDSTUDIO_BOUNCE_PASSWORD)) ? $SENDSTUDIO_BOUNCE_PASSWORD : SENDSTUDIO_BOUNCE_PASSWORD;
				$SENDSTUDIO_BOUNCE_IMAP = (isset($SENDSTUDIO_BOUNCE_IMAP)) ? $SENDSTUDIO_BOUNCE_IMAP : SENDSTUDIO_BOUNCE_IMAP;
				$SENDSTUDIO_BOUNCE_EXTRASETTINGS = (isset($SENDSTUDIO_BOUNCE_EXTRASETTINGS)) ? $SENDSTUDIO_BOUNCE_EXTRASETTINGS : SENDSTUDIO_BOUNCE_EXTRASETTINGS;

				if ($SENDSTUDIO_BOUNCE_ADDRESS) {
					$GLOBALS['SetDefaultBounceAccountDetails'] = ' CHECKED';
					$GLOBALS['DisplayDefaultBounceAccountDetails'] = "'';";
				} else {
					$GLOBALS['DisplayDefaultBounceAccountDetails'] = 'none';
					$GLOBALS['DisplayDefaultBounceAccountExtraSettings'] = 'none';
				}

				if ($SENDSTUDIO_BOUNCE_EXTRASETTINGS) {
					$GLOBALS['Bounce_ExtraSettings'] = ' CHECKED';
					$GLOBALS['DisplayDefaultBounceAccountDetails'] = "'';";
				} else {
					$GLOBALS['DisplayDefaultBounceAccountExtraSettings'] = 'none';
				}

				$disabled_functions = explode(',', SENDSTUDIO_DISABLED_FUNCTIONS);
				$php_path = '';

				if (!SENDSTUDIO_SAFE_MODE && !in_array('exec', $disabled_functions)) {
					if (substr(strtolower(PHP_OS), 0, 3) != 'win') {
						$php_path = exec('which php');
					}
				}

				if ($php_path != '') {
					$php_path .= ' -f ';
				}

				$GLOBALS['CronPath'] = $php_path . SENDSTUDIO_BASE_DIRECTORY . DIRECTORY_SEPARATOR . 'cron' . DIRECTORY_SEPARATOR . 'cron.php';

				$GLOBALS['DatabaseType'] = $SENDSTUDIO_DATABASE_TYPE;
				$GLOBALS['DatabaseUser'] = $SENDSTUDIO_DATABASE_USER;
				$GLOBALS['DatabaseHost'] = $SENDSTUDIO_DATABASE_HOST;
				$GLOBALS['DatabasePass'] = $SENDSTUDIO_DATABASE_PASS;
				$GLOBALS['DatabaseName'] = $SENDSTUDIO_DATABASE_NAME;
				$GLOBALS['DatabaseTablePrefix'] = $SENDSTUDIO_TABLEPREFIX;
				$GLOBALS['ApplicationURL'] = $SENDSTUDIO_APPLICATION_URL;
				$GLOBALS['LicenseKey'] = $SENDSTUDIO_LICENSEKEY;

				$GLOBALS['TextFooter'] = stripslashes(strip_tags($SENDSTUDIO_TEXTFOOTER));
				$GLOBALS['HTMLFooter'] = stripslashes($SENDSTUDIO_HTMLFOOTER);

				$GLOBALS['ForceUnsubLink'] = $SENDSTUDIO_FORCE_UNSUBLINK;

				$GLOBALS['CronEnabled'] = $SENDSTUDIO_CRON_ENABLED;

				$GLOBALS['IpTracking'] = $SENDSTUDIO_IPTRACKING;

				$GLOBALS['MaxHourlyRate'] = $SENDSTUDIO_MAXHOURLYRATE;

				$GLOBALS['MaxOverSize'] = $SENDSTUDIO_MAXOVERSIZE;

				$GLOBALS['EmailAddress'] = htmlspecialchars($SENDSTUDIO_EMAIL_ADDRESS, ENT_QUOTES, SENDSTUDIO_CHARSET);

				$GLOBALS['MaxImageWidth'] = $SENDSTUDIO_MAX_IMAGEWIDTH;
				$GLOBALS['MaxImageHeight'] = $SENDSTUDIO_MAX_IMAGEHEIGHT;

				$GLOBALS['Smtp_Server'] = htmlspecialchars($SENDSTUDIO_SMTP_SERVER, ENT_QUOTES, SENDSTUDIO_CHARSET);
				$GLOBALS['Smtp_Username'] = htmlspecialchars($SENDSTUDIO_SMTP_USERNAME, ENT_QUOTES, SENDSTUDIO_CHARSET);
				$GLOBALS['Smtp_Password'] = base64_decode($SENDSTUDIO_SMTP_PASSWORD);
				$GLOBALS['Smtp_Port'] = $SENDSTUDIO_SMTP_PORT;

				$GLOBALS['Bounce_Address'] = htmlspecialchars($SENDSTUDIO_BOUNCE_ADDRESS, ENT_QUOTES, SENDSTUDIO_CHARSET);
				$GLOBALS['Bounce_Server'] = htmlspecialchars($SENDSTUDIO_BOUNCE_SERVER, ENT_QUOTES, SENDSTUDIO_CHARSET);
				$GLOBALS['Bounce_Username'] = htmlspecialchars($SENDSTUDIO_BOUNCE_USERNAME, ENT_QUOTES, SENDSTUDIO_CHARSET);
				$GLOBALS['Bounce_Password'] = base64_decode($SENDSTUDIO_BOUNCE_PASSWORD);
				$GLOBALS['Bounce_Imap'] = $SENDSTUDIO_BOUNCE_IMAP;
				$GLOBALS['Bounce_Extrasettings'] = htmlspecialchars($SENDSTUDIO_BOUNCE_EXTRASETTINGS, ENT_QUOTES, SENDSTUDIO_CHARSET);

				$charset = (isset($SENDSTUDIO_DEFAULTCHARSET)) ? $SENDSTUDIO_DEFAULTCHARSET : SENDSTUDIO_CHARSET;
				$GLOBALS['DefaultCharset'] = $charset;

				$GLOBALS['CharsetDescription'] = GetLang($charset);

				$GLOBALS['FormAction'] = 'Action=Save';

				$api = $this->GetApi();

				if (!$cron_checked) {
					$api->UpdateCron();
				}

				if ($cron_checked) {
					$this->DisplayCronWarning(false);
				}

				$GLOBALS['DisplayDbUpgrade'] = "none;";
				$GLOBALS['DbUpgradeMessage'] = '';

				if ($action == 'upgradedb_finished') {
					$session = &GetSession();

					$new_version = $api->GetDatabaseVersion();

					$upgrades_failed = $session->Get('DatabaseUpgradesFailed');

					$vars = array(
						'DatabaseTables_BackupErrors',
						'DatabaseTables_Todo',
						'DatabaseTables_Done',

						'DatabaseUpgradesCompleted',
						'DatabaseUpgradesFailed'
					);
					foreach ($vars as $k => $var) {
						$session->Remove($var);
					}
					$GLOBALS['DisplayDbUpgrade'] = "'';";

					$message = '';
					if (empty($upgrades_failed)) {
						$message = $this->PrintSuccess('DatabaseUpgraded', $new_version);
					} else {
						$GLOBALS['Error'] = GetLang('DatabaseUpgradesFailed');
						$message .= $this->ParseTemplate('ErrorMsg', true, false);
						$message .= '<ul>';
						foreach ($upgrades_failed as $p => $upgrade_problem) {
							$message .= '<li>' . $upgrade_problem . '</li>';
						}
						$message .= '</ul>';
					}
					$GLOBALS['DbUpgradeMessage'] .= $message;
				}

				if ($api->NeedDatabaseUpgrade()) {
					$current_db_version = $api->GetDatabaseVersion();

					$GLOBALS['DisplayDbUpgrade'] = "'';";
					$GLOBALS['Error'] = sprintf(GetLang('DatabaseUpgradeIntro'), $current_db_version, SENDSTUDIO_DATABASE_VERSION);
					$GLOBALS['DbUpgradeMessage'] .= $this->ParseTemplate('ErrorMsg', true, false);

					$session = &GetSession();

					require(SENDSTUDIO_API_DIRECTORY . '/upgrade.php');

					$upgrade_api = &new Upgrade_API();

					$next_version = $upgrade_api->GetNextVersion($current_db_version);

					$session->Set('NextVersion', $next_version);
				}

				$GLOBALS['ExtraScript'] = $extra;

				Sendstudio_Functions::Process();
			break;
		}
	}

	/**
	* SendPreview
	* This sends a 'preview email' (test email in this case) to the email address supplied.
	*
	* @see GetUser
	* @see GetApi
	* @see User_API::Get
	* @see Email_API::Set
	* @see Email_API::AddBody
	* @see Email_API::AddRecipient
	* @see Email_API::Send
	*
	* @return Void Prints out whether the email was sent successfully or not. Doesn't return anything.
	*/
	function SendPreview()
	{
		$user = &GetUser();
		$preview_email = (isset($_POST['PreviewEmail'])) ? $_POST['PreviewEmail'] : false;
		$subject = GetLang('TestSendingSubject');
		$text = GetLang('TestSendingEmail');

		if (!$preview_email) {
			$GLOBALS['Error'] = GetLang('NoEmailAddressSupplied');
			$GLOBALS['Message'] = $this->ParseTemplate('ErrorMsg', true, false);
			$this->ParseTemplate('Preview_EmailWindow');
			return;
		}

		$email_api = $this->GetApi('Email');

		if (!empty($_POST['smtp_server'])) {
			$email_api->Set('SMTPServer', $_POST['smtp_server']);
		}
		if (!empty($_POST['smtp_u'])) {
			$email_api->Set('SMTPUsername', $_POST['smtp_u']);
		}
		if (!empty($_POST['smtp_p'])) {
			$email_api->Set('SMTPPassword', $_POST['smtp_p']);
		}

		if (!empty($_POST['smtp_port'])) {
			$email_api->Set('SMTPPort', $_POST['smtp_port']);
		}

		$email_api->Set('Subject', $subject);
		$email_api->Set('CharSet', SENDSTUDIO_CHARSET);
		$email_api->Set('FromAddress', $preview_email);
		$email_api->Set('ReplyTo', $preview_email);
		$email_api->Set('BounceAddress', $preview_email);

		$user_email = $user->Get('emailaddress');
		if ($user_email) {
			$email_api->Set('FromAddress', $user_email);
			$user_name = $user->Get('fullname');
			if ($user_name) {
				$email_api->Set('FromName', $user_name);
			}
		}

		$email_api->AddBody('text', $text);

		$email_api->AddRecipient($preview_email, '', 't');
		$send_result = $email_api->Send();

		if (isset($send_result['success']) && $send_result['success'] > 0) {
			$GLOBALS['Message'] = $this->PrintSuccess('TestEmailSent', $preview_email);
		} else {
			$failure = array_shift($send_result['fail']);
			$GLOBALS['Error'] = sprintf(GetLang('TestEmailNotSent'), $preview_email, htmlspecialchars($failure[1], ENT_QUOTES, SENDSTUDIO_CHARSET));
			$GLOBALS['Message'] = $this->ParseTemplate('ErrorMsg', true, false);
		}

		$this->ParseTemplate('Preview_EmailWindow_Settings');
	}

	function UpgradeDb()
	{
		$session = &GetSession();

		$disabled_functions = explode(',', str_replace(' ', '', SENDSTUDIO_DISABLED_FUNCTIONS));

		if (!SENDSTUDIO_SAFE_MODE && !in_array('set_time_limit', $disabled_functions)) {
			set_time_limit(0);
		}

		$upgrades_done = $session->Get('DatabaseUpgradesCompleted');
		if (!$upgrades_done) {
			$upgrades_done = array();
			$session->Set('DatabaseUpgradesCompleted', $upgrades_done);
			$upgrades_failed = array();
			$session->Set('DatabaseUpgradesFailed', $upgrades_failed);
		}

		$upgrades_failed = $session->Get('DatabaseUpgradesFailed');

		require(SENDSTUDIO_API_DIRECTORY . '/upgrade.php');

		$upgrade_api = &new Upgrade_API();

		$next_version = $session->Get('NextVersion');

		$running_upgrade = $upgrade_api->GetNextUpgrade($next_version);

		if (!is_null($running_upgrade)) {
			$this->PrintHeader(true);
			$desc = 'Running queries for \'' . $running_upgrade . '\'';
			$friendly_desc = $upgrade_api->Get('FriendlyDescription');
			if ($friendly_desc) {
				$desc = $friendly_desc;
			}
			echo $desc . '<br/>';
			$this->PrintFooter(true);

			$upgrade_result = $upgrade_api->RunUpgrade(SENDSTUDIO_DATABASE_VERSION, $running_upgrade);

			$upgrades_done[] = $running_upgrade;
			$session->Set('DatabaseUpgradesCompleted', $upgrades_done);

			if (!$upgrade_result) {
				$upgrades_failed[] = $upgrade_api->Get('error');
				$session->Set('DatabaseUpgradesFailed', $upgrades_failed);
			}
			?>
				<script language="javascript">
					setTimeout('window.location="index.php?Page=Settings&Action=UpgradeDb"', 1);
				</script>
			<?php
			return;
		}

		?>
			<script>
				window.opener.document.location = 'index.php?Page=Settings&Action=upgradedb_finished';
				window.close();
			</script>
		<?php
		return;

	}

}

?>
