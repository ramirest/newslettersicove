<?php
/**
* This file has the user editing forms in it if you can only manage your own account.
*
* @version     $Id: manageaccount.php,v 1.21 2007/05/15 04:25:34 chris Exp $

*
* @package SendStudio
* @subpackage SendStudio_Functions
*/

/**
* Include the base sendstudio functions.
*/
require(dirname(__FILE__) . '/sendstudio_functions.php');

/**
* Class for the manage-own-account page.
* Handles permission checks, making sure you only update certain aspects of your account (email, password, name)
*
* @package SendStudio
* @subpackage SendStudio_Functions
*/
class ManageAccount extends SendStudio_Functions
{

	/**
	* Constructor
	* Loads up the "Users" and "Timezones" language file.
	*
	* @see LoadLanguageFile
	*
	* @return Void Doesn't return anything.
	*/
	function ManageAccount()
	{
		$this->LoadLanguageFile('Users');
		$this->LoadLanguageFile('Timezones');
	}


	/**
	* Process
	* Lets a user manage their own account - to a certain extent.
	* The API itself manages saving and updating, this just works out displaying of forms etc.
	*
	* @see PrintHeader
	* @see ParseTemplate
	* @see GetSession
	* @see Session::Get
	* @see GetDatabase
	* @see GetUser
	* @see User_API::Set
	* @see GetLang
	* @see PrintEditForm
	* @see PrintFooter
	*
	* @return Void Doesn't return anything, hands the processing off to the appropriate subarea and lets it do the work.
	*/
	function Process()
	{
		$this->PrintHeader();

		$session = &GetSession();
		$thisuser = $session->Get('UserDetails');
		$db = &GetDatabase();

		$action = (isset($_GET['Action'])) ? strtolower($_GET['Action']) : '';
		switch ($action) {
			case 'save':
				$userid = (isset($_GET['UserID'])) ? $_GET['UserID'] : 0;

				$user = &GetUser($userid);

				$smtptype = (isset($_POST['smtptype'])) ? $_POST['smtptype'] : 0;

				foreach (array('fullname', 'emailaddress', 'status', 'admin', 'usertimezone', 'textfooter', 'htmlfooter', 'infotips', 'smtpserver', 'smtpusername', 'smtpport') as $p => $area) {
					$val = (isset($_POST[$area])) ? $_POST[$area] : '';
					if (in_array($area, array('status', 'admin'))) {
						if ($userid == $thisuser->userid) {
							$val = $thisuser->$area;
						}
					}
					$user->Set($area, $val);
				}

				// this has a different post value otherwise firefox tries to pre-fill it.
				$smtp_pass = '';
				if (isset($_POST['smtp_p'])) {
					$smtp_pass = $_POST['smtp_p'];
				}
				$user->Set('smtppassword', $smtp_pass);

				if ($smtptype == 0) {
					$user->Set('smtpserver', false);
					$user->Set('smtpusername', false);
					$user->Set('smtppassword', false);
					$user->Set('smtpport', false);
				}

				$error = false;
				$template = false;

				if (!$error) {
					if ($_POST['ss_p'] != '') {
						if ($_POST['ss_p_confirm'] != '' && $_POST['ss_p_confirm'] == $_POST['ss_p']) {
							$user->Set('password', $_POST['ss_p']);
						} else {
							$error = GetLang('PasswordsDontMatch');
						}
					}
				}

				if (!$error) {
					$result = $user->Save();
					if ($result) {
						$GLOBALS['Message'] = $this->PrintSuccess('UserUpdated') . '<br/>';
					} else {
						$GLOBALS['Error'] = GetLang('UserNotUpdated');
						$GLOBALS['Message'] = $this->ParseTemplate('ErrorMsg', true, false);
					}
				} else {
					$GLOBALS['Error'] = $error;
					$GLOBALS['Message'] = $this->ParseTemplate('ErrorMsg', true, false);
				}

				$new_user = &GetUser($userid);
				$session->Set('UserDetails', $new_user);

				$this->PrintEditForm($userid);
			break;

			default:
				$userid = $thisuser->userid;
				$this->PrintEditForm($userid);
			break;
		}
		$this->PrintFooter();
	}


	/**
	* PrintEditForm
	* Prints the editing form for the userid passed in.
	* If the user doesn't have access to edit their details, it will only display them.
	* Also makes sure that the user doesn't try to edit another users' details.
	*
	* @param Int $userid UserID to show the form for. This will load up the user and use their details as the defaults.
	*
	* @see GetSession
	* @see Session::Get
	* @see User_API::Admin
	* @see GetLang
	* @see GetUser
	*
	* @return Void Doesn't return anything, prints out the appropriate form and that's it.
	*/
	function PrintEditForm($userid=0)
	{
		$session = &GetSession();
		$thisuser = $session->Get('UserDetails');
		if (!$thisuser->Admin()) {
			if ($userid != $thisuser->userid) {
				$GLOBALS['ErrorMessage'] = GetLang('NoAccess');
				$this->ParseTemplate('AccessDenied');
				return false;
			}
		}

		$user = &GetUser($userid);
		$GLOBALS['UserID'] = $user->userid;
		$GLOBALS['UserName'] = $user->username;
		$GLOBALS['FullName'] = $user->fullname;
		$GLOBALS['EmailAddress'] = $user->emailaddress;

		$GLOBALS['TextFooter'] = $user->textfooter;
		$GLOBALS['HTMLFooter'] = $user->htmlfooter;

		$GLOBALS['CustomSmtpServer_Display'] = '0';

		$GLOBALS['SmtpServer'] = $user->Get('smtpserver');
		$GLOBALS['SmtpUsername'] = $user->Get('smtpusername');
		$GLOBALS['SmtpPassword'] = $user->Get('smtppassword');
		$GLOBALS['SmtpPort'] = $user->Get('smtpport');

		$smtp_access = $user->HasAccess('User', 'SMTP');

		$GLOBALS['ShowSMTPInfo'] = 'none';
		$GLOBALS['DisplaySMTP'] = '0';

		if ($smtp_access) {
			$GLOBALS['ShowSMTPInfo'] = '';
		}

		if ($GLOBALS['SmtpServer']) {
			$GLOBALS['CustomSmtpServer_Display'] = '1';
			if ($smtp_access) {
				$GLOBALS['DisplaySMTP'] = '1';
			}
		}

		$GLOBALS['FormAction'] = 'Action=Save&UserID=' . $user->userid;

		$timezone = $user->usertimezone;
		$GLOBALS['TimeZoneList'] = $this->TimeZoneList($timezone);

		$GLOBALS['InfoTipsChecked'] = ($user->InfoTips()) ? ' CHECKED' : '';

		if ($thisuser->EditOwnSettings()) {
			$this->ParseTemplate('User_Edit_Own');
		} else {
			$this->ParseTemplate('User_Display_Own');
		}
	}
}

?>
