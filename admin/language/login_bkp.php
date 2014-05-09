<?php
/**
* Language file variables for the login page.
*
* @see GetLang
*
* @version     $Id: login.php,v 1.8 2006/11/08 05:08:20 chris Exp $

*
* @package SendStudio
* @subpackage Language
*/

/**
* Here are all of the variables for the login area... Please backup before you start!
*/

define('LNG_LoginTitle', 'Control Panel Login');
define('LNG_Help_Login', 'Log in with your username and password below.');
define('LNG_LoginDetails', 'Login Details');
define('LNG_Login', 'Login');
define('LNG_UserName', 'Username');
define('LNG_NoUsername', 'Please enter your username.');
define('LNG_NoPassword', 'Please enter a password.');
define('LNG_BadLogin', 'Your username or password are incorrect. Please try again.');

define('LNG_RememberMe', 'Remember my login details');
define('LNG_ForgotPasswordReminder', 'If you have forgotten your password, <a href="index.php?Page=Login&Action=ForgotPass">click here</a>.');

/**
* Forgot password page.
*/
define('LNG_ForgotPasswordTitle', 'Forgot your password?');
define('LNG_ForgotPasswordDetails', 'Enter your details below.');
define('LNG_Help_ForgotPassword', 'Enter your details below to reset your password.');
define('LNG_NewPassword', 'New Password');
define('LNG_SendPassword', 'Send Password');
define('LNG_BadLogin_Forgot', 'That username doesn\'t exist. Please try again.');
define('LNG_ChangePasswordSubject', 'Please confirm your password change');
define('LNG_ChangePasswordEmail', 'You have recently chosen to change your control panel password. To confirm this, please click on the following link: %s');
define('LNG_ChangePassword_Emailed', 'A verification email has been sent to you. Please follow the instructions in that email to request a new password.');
define('LNG_PasswordUpdated', 'Your password has been updated successfully. Please login below.');
define('LNG_BadLogin_Link', 'The link you received in the email is invalid. Please try again.');
define('LNG_ChangePassword', 'Change Password');
?>
