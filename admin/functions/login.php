<?php
/**
* This file has the login functions in it. Shows the login page and then authenticates upon submission.
*
* @version     $Id: login.php,v 1.18 2007/05/15 07:03:55 rodney Exp $

*
* @package SendStudio
* @subpackage SendStudio_Functions
*/

/**
* Include the base sendstudio functions.
*/
require(dirname(__FILE__) . '/sendstudio_functions.php');

/**
* Class for the login page. Will show the login screen, authenticate and set the session details as it needs to.
*
* @package SendStudio
* @subpackage SendStudio_Functions
*/
class Login extends SendStudio_Functions
{

	/**
	* Constructor
	* Loads the language file.
	*
	* @see LoadLanguageFile
	*
	* @return Void Doesn't return anything.
	*/
	function Login()
	{
		$this->LoadLanguageFile();
	}

	/**
	* Process
	* All the action happens here.
	* If you are not logged in, it will print the login form.
	* Submitting that form will then try to authenticate you.
	* If you are successfully authenticated, you get redirected back to the main index page (quickstats etc).
	* Otherwise, will show an error message and the login form again.
	*
	* @see ShowLoginForm
	* @see GetAuthenticationSystem
	* @see AuthenticationSystem::Authenticate
	* @see GetUser
	* @see GetSession
	* @see Session::Set
	*
	* @return Void Doesn't return anything. Checks the action and passes it off to the appropriate area.
	*/
	function Process()
	{
		$action = (isset($_GET['Action'])) ? strtolower($_GET['Action']) : '';
		switch ($action) {
			case 'forgotpass':
				$this->ShowForgotForm();
			break;

			case 'changepassword':
				$session = &GetSession();
				if (!$session->Get('ForgotUser')) {
					$this->ShowForgotForm('errormsg', GetLang('BadLogin_Link'));
					break;
				}

				$userapi = &GetUser(-1);
				$loaded = $userapi->Load($session->Get('ForgotUser'), false);

				if (!$loaded) {
					$this->ShowForgotForm('errormsg', GetLang('BadLogin_Link'));
					break;
				}
				$password = false;
				$confirm = false;
				if (isset($_POST['ss_password'])) {
					$password = $_POST['ss_password'];
				}

				if (isset($_POST['ss_password_confirm'])) {
					$confirm  = $_POST['ss_password_confirm'];
				}

				if ($password == false || ($password != $confirm)) {
					$this->ShowForgotForm_Step2($userapi->Get('username'), 'errormsg', GetLang('PasswordsDontMatch'));
					break;
				}

				$userapi->password = $password;
				$userapi->Save();

				$code = md5(uniqid(rand(), true));

				$userapi->ResetForgotCode($code);

				$this->ShowLoginForm('successmsg', GetLang('PasswordUpdated'));
			break;

			case 'sendpass':
				$user = &GetUser(-1);
				$username = $_POST['ss_username'];

				$founduser = $user->Find($username);
				if (!$founduser) {
					$this->ShowForgotForm('errormsg', GetLang('BadLogin_Forgot'));
					break;
				}

				$user->Load($founduser['userid'], false);

				$code = md5(uniqid(rand(), true));

				$user->ResetForgotCode($code);

				$link = SENDSTUDIO_APPLICATION_URL . '/admin/index.php?Page=Login&Action=ConfirmCode&user=' . $founduser . '&code=' . $code;

				$headers = "From: " . SENDSTUDIO_EMAIL_ADDRESS . "\n";
				$headers = "Reply-To: " . SENDSTUDIO_EMAIL_ADDRESS . "\n";
				$headers .= "Content-Type: text/plain\n";

				$message = sprintf(GetLang('ChangePasswordEmail'), $link);
				if (SENDSTUDIO_SAFE_MODE) {
					mail($user->emailaddress, GetLang('ChangePasswordSubject'), $message, $headers);
				} else {
					mail($user->emailaddress, GetLang('ChangePasswordSubject'), $message, $headers, "-f" . SENDSTUDIO_EMAIL_ADDRESS);
				}
				$this->ShowLoginForm('successmsg', GetLang('ChangePassword_Emailed'));
			break;

			case 'confirmcode':
				$user = (isset($_GET['user'])) ? (int)$_GET['user'] : false;
				$code = (isset($_GET['code'])) ? $_GET['code'] : false;

				if (!$user || !$code) {
					$this->ShowForgotForm('errormsg', GetLang('BadLogin_Link'));
					break;
				}

				$userapi = &GetUser(-1);
				$loaded = $userapi->Load($user, false);

				if (!$loaded || $userapi->Get('forgotpasscode') != $code) {
					$this->ShowForgotForm('errormsg', GetLang('BadLogin_Link'));
					break;
				}

				$session = &GetSession();
				$session->Set('ForgotUser', $user);

				$this->ShowForgotForm_Step2($userapi->Get('username'));
			break;

			case 'login':
				$auth_system = &GetAuthenticationSystem();
				$username = isset($_POST['ss_username']) ? $_POST['ss_username'] : '';
				$password = isset($_POST['ss_password']) ? $_POST['ss_password'] : '';
				$result = $auth_system->Authenticate($username, $password);
				if (!$result) {
					$this->ShowLoginForm('errormsg', GetLang('BadLogin'));
					break;
				}

				$rememberdetails = (isset($_POST['rememberme'])) ? true : false;

				$user = &GetUser($result['userid']);

				$rand_check = uniqid(true);

				$user->settings['LoginCheck'] = $rand_check;
				$user->SaveSettings();

				$user->UpdateLoginTime();

				$session = &GetSession();
				$session->Set('UserDetails', $user);

				$oneyear = time() + (365 * 24 * 3600); // one year's time.

				if ($rememberdetails) {
					$usercookie_info = array('user' => $user->userid, 'time' => time(), 'rand' => $rand_check);
					setcookie('SendStudioLogin', base64_encode(serialize($usercookie_info)), $oneyear, '/');
				}

				header('Location: ' . SENDSTUDIO_APPLICATION_URL . '/admin/index.php');
			break;

			default:
				$msg = false; $template = false;
				if ($action == 'logout') {
					$this->LoadLanguageFile('Logout');
					$msg = GetLang('LogoutSuccessful');
					$template = 'successmsg';
				}
				$this->ShowLoginForm($template, $msg);
		}
	}

	/**
	* ShowLoginForm
	* This shows the login form.
	* If there is a template to use in the data/templates folder it will use that as the login form.
	* Otherwise it uses the default one below. If you pass in a message it will show that message above the login form.
	*
	* @param String $template Uses the template passed in for the message (eg success / error).
	* @param String $msg Prints the message passed in above the login form (eg unsuccessful attempt).
	*
	* @see FetchTemplate
	* @see GetSession
	* @see Session::LoggedIn
	* @see PrintHeader
	* @see PrintFooter
	*
	* @return Void Doesn't return anything, just prints the login form.
	*/
	function ShowLoginForm($template=false, $msg=false)
	{
		$session = &GetSession();
		if (!$session->LoggedIn()) {
			$this->GlobalAreas['InfoTips'] = '';
		}

		$this->PrintHeader();

		if ($template && $msg) {
			switch ($template) {
				case 'errormsg':
					$this->GlobalAreas['Error'] = $msg;
				break;
				case 'successmsg':
					$this->GlobalAreas['Success'] = $msg;
				break;
			}
			$this->GlobalAreas['Message'] = $this->ParseTemplate($template, true, false);
		}

		if (isset($_POST['ss_username'])) {
			$GLOBALS['ss_username'] = htmlspecialchars($_POST['ss_username'], ENT_QUOTES, SENDSTUDIO_CHARSET);
		}

		$this->GlobalAreas['SubmitAction'] = 'Login';

		$this->ParseTemplate('login');

		$this->PrintFooter();
	}

	/**
	* ShowForgotForm
	* This shows the forgot password form and handles the multiple stages of actions. If the template and message are passed in, there will be a success/error message shown. If one is not present, nothing is shown.
	*
	* @param String $template If there is a template (will either be success or error template) use that as a message.
	* @param String $msg This also tells us what's going on (password has been reset and so on).
	*
	* @see PrintHeader
	* @see ParseTemplate
	* @see PrintFooter
	*
	* @return Void Doesn't return anything, only prints out the form.
	*/
	function ShowForgotForm($template=false, $msg=false)
	{
		$this->PrintHeader();

		if ($template && $msg) {
			switch (strtolower($template)) {
				case 'errormsg':
					$this->GlobalAreas['Error'] = $msg;
				break;
				case 'successmsg':
					$this->GlobalAreas['Success'] = $msg;
				break;
			}
			$this->GlobalAreas['Message'] = $this->ParseTemplate($template, true, false);
		}

		$GLOBALS['SubmitAction'] = 'SendPass';

		$this->ParseTemplate('ForgotPassword');

		$this->PrintFooter();
	}

	/**
	* ShowForgotForm_Step2
	* This shows the form for changing the password. It will show the password/password confirm boxes for the user to fill in.
	*
	* @param String $username The username to show in the form. This is not editable, it is just shown for reference.
	* @param String $template If there is a template (will either be success or error template) use that as a message.
	* @param String $msg This also tells us what's going on (password has been reset and so on).
	*
	* @see PrintHeader
	* @see ParseTemplate
	* @see PrintFooter
	*
	* @return Void Doesn't return anything, only prints out the form.
	*/
	function ShowForgotForm_Step2($username='', $template=false, $msg=false)
	{
		$this->PrintHeader();

		$GLOBALS['UserName'] = htmlspecialchars($username, ENT_QUOTES, SENDSTUDIO_CHARSET);

		if ($template && $msg) {
			switch (strtolower($template)) {
				case 'errormsg':
					$this->GlobalAreas['Error'] = $msg;
				break;
				case 'successmsg':
					$this->GlobalAreas['Success'] = $msg;
				break;
			}
			$this->GlobalAreas['Message'] = $this->ParseTemplate($template, true, false);
		}

		$this->ParseTemplate('ForgotPassword_Step2');

		$this->PrintFooter();
	}

}

?>
