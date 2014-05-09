<?php


/**
* Set up our error reporting - show everything.
*/
error_reporting(E_ALL);

ini_set('short_tags', false);
ini_set('memory_limit', '16M');
ini_set('track_errors', true);
ini_set('display_errors', true);

set_magic_quotes_runtime(false);

$_POST		= stripslashes_deep($_POST);
$_GET		= stripslashes_deep($_GET);
$_COOKIE	= stripslashes_deep($_COOKIE);
$_REQUEST	= stripslashes_deep($_REQUEST);

$admindir = dirname(dirname(__FILE__));

/**
* Set up our base variables. These are used all over the place.
*
* @see GetLang
* @see SendStudio_Functions::GetApi
* @see SendStudio_Functions::GetAttachments
* @see SendStudio_Functions::ParseTemplate
*/

define('LNG_SENDSTUDIO_VERSION', ' NX1.1.4');

define('SENDSTUDIO_BASE_DIRECTORY', $admindir);
define('SENDSTUDIO_INCLUDES_DIRECTORY', $admindir . '/includes');
define('SENDSTUDIO_LANGUAGE_DIRECTORY', $admindir . '/language');
define('SENDSTUDIO_TEMPLATE_DIRECTORY', SENDSTUDIO_INCLUDES_DIRECTORY . '/templates');
define('SENDSTUDIO_FUNCTION_DIRECTORY', $admindir . '/functions');
define('SENDSTUDIO_LIB_DIRECTORY', SENDSTUDIO_FUNCTION_DIRECTORY . '/lib');
define('SENDSTUDIO_API_DIRECTORY', SENDSTUDIO_FUNCTION_DIRECTORY . '/api');

define('TEMP_DIRECTORY', $admindir .'/temp');

/**
* Check for the config file.
* If it's not around, set default variables for the install process.
*/
$config_file = SENDSTUDIO_INCLUDES_DIRECTORY . '/config.php';
if (is_file($config_file)) {
	require($config_file);
}

/**
* If sendstudio isn't set up, then check to see if we have a backup available.
* If we do, load that up.
* If it's a proper config (SENDSTUDIO_IS_SETUP etc)
* then check to see if we can copy it to it's proper location.
* Even if we can't copy it, we'll load it up and be able to get in to sendstudio anyway.
*/
if (!defined('SENDSTUDIO_IS_SETUP')) {
	$bkp_file = SENDSTUDIO_INCLUDES_DIRECTORY . '/config.bkp.php';
	if (is_file($bkp_file)) {
		require($bkp_file);
	}
	if (defined('SENDSTUDIO_IS_SETUP') && SENDSTUDIO_IS_SETUP == 1) {
		$copy = false;
		if (is_file($config_file)) {
			if (is_writable($config_file)) {
				$copy = true;
			}
		} else {
			if (is_writable(SENDSTUDIO_INCLUDES_DIRECTORY)) {
				$copy = true;
			}
		}
		if ($copy) {
			@copy($bkp_file, $config_file);
		}
	}
}

/**
* If sendstudio still isn't set up (ie the config file is missing &/or the backup file is missing or incomplete)
* Then set the default values so we can handle the fresh install or upgrade from v2004.
*/
if (!defined('SENDSTUDIO_IS_SETUP')) {
	define('SENDSTUDIO_IS_SETUP', false);
	define('SENDSTUDIO_APPLICATION_URL', false);
	define('SENDSTUDIO_LICENSEKEY', false);
	define('SENDSTUDIO_DEFAULTCHARSET', 'ISO-8859-1');
	define('SENDSTUDIO_SERVERTIMEZONE', 'GMT');
}

/**
* These two options got added in NX1.0.5 - so we need to define them in case they are not in the config file.
*/
if (!defined('SENDSTUDIO_MAX_IMAGEWIDTH')) {
	define('SENDSTUDIO_MAX_IMAGEWIDTH', 700);
}
if (!defined('SENDSTUDIO_MAX_IMAGEHEIGHT')) {
	define('SENDSTUDIO_MAX_IMAGEHEIGHT', 400);
}

/**
* These options got added in NX1.1.0 - so we need to define them in case they are not in the config file.
*/
if (!defined('SENDSTUDIO_BOUNCE_ADDRESS')) {
	define('SENDSTUDIO_BOUNCE_ADDRESS', '');
	define('SENDSTUDIO_BOUNCE_SERVER', '');
	define('SENDSTUDIO_BOUNCE_USERNAME', '');
	define('SENDSTUDIO_BOUNCE_PASSWORD', '');
	define('SENDSTUDIO_BOUNCE_IMAP', '');
	define('SENDSTUDIO_BOUNCE_EXTRASETTINGS', '');
}

/**
* This tells sendstudio whether a database update is required or not.
*
* DO NOT CHANGE THIS VALUE
* UNLESS YOU KNOW WHAT YOU ARE DOING.
*/
define('SENDSTUDIO_DATABASE_VERSION', '0');

require(SENDSTUDIO_LIB_DIRECTORY . '/general/utf8.php');

require(SENDSTUDIO_LANGUAGE_DIRECTORY . '/language.php');

require(SENDSTUDIO_FUNCTION_DIRECTORY . '/process.php');

$safe_mode = (bool)ini_get('safe_mode');
define('SENDSTUDIO_SAFE_MODE', (int)$safe_mode);
define('SENDSTUDIO_FOPEN', ini_get('allow_url_fopen'));
define('SENDSTUDIO_CURL', function_exists('curl_init'));

define('SENDSTUDIO_DISABLED_FUNCTIONS', str_replace(' ', '', ini_get('disable_functions')));

define('SENDSTUDIO_COOKIE_PREFIX', 'ss_');

define('SENDSTUDIO_RESOURCES_DIRECTORY', $admindir . '/resources');
define('SENDSTUDIO_NEWSLETTER_TEMPLATES_DIRECTORY', SENDSTUDIO_RESOURCES_DIRECTORY . '/email_templates');

define('SENDSTUDIO_FORM_DESIGNS_DIRECTORY', SENDSTUDIO_RESOURCES_DIRECTORY . '/form_designs');

define('SENDSTUDIO_RESOURCES_URL', SENDSTUDIO_APPLICATION_URL . '/admin/resources');

define('SENDSTUDIO_IMAGE_DIRECTORY', $admindir . '/images');
define('SENDSTUDIO_IMAGE_URL', SENDSTUDIO_APPLICATION_URL . '/admin/images');
define('SENDSTUDIO_STYLE_URL', SENDSTUDIO_APPLICATION_URL . '/admin/styles');

define('SENDSTUDIO_TEMP_URL', SENDSTUDIO_APPLICATION_URL . '/admin/temp');

$GLOBALS['SendStudioURL'] = SENDSTUDIO_APPLICATION_URL;
$GLOBALS['SendStudioImageURL'] = SENDSTUDIO_IMAGE_URL;
$GLOBALS['SendStudioStyleURL'] = SENDSTUDIO_STYLE_URL;

define('SENSTUDIO_BASE_APPLICATION_URL', dirname(SENDSTUDIO_APPLICATION_URL));

define('SENDSTUDIO_ERROR_FATAL', E_USER_ERROR);
define('SENDSTUDIO_ERROR_ERROR', E_USER_WARNING);
define('SENDSTUDIO_ERROR_WARNING', E_USER_NOTICE);

/**
* Set the HTML_CHARSET
*
* The php function htmlspecialchars only allows certain character sets.
* If the default charset from the config file is in the allowed list, then use that.
* If it's not, set it to utf-8.
*/
$allowed_html_charsets = array(
	'ISO-8859-1',
	'UTF-8',
);

if (in_array(SENDSTUDIO_DEFAULTCHARSET, $allowed_html_charsets)) {
	define('SENDSTUDIO_CHARSET', SENDSTUDIO_DEFAULTCHARSET);
} else {
	define('SENDSTUDIO_CHARSET', 'UTF-8');
}


/**
* Include the database files and set that up.
* Then set up the db connection
*
* This is done before including the user object so the database object is 'complete' and not invalid.
*
*/
if (SENDSTUDIO_IS_SETUP) {
	require(SENDSTUDIO_LIB_DIRECTORY . '/database/' . SENDSTUDIO_DATABASE_TYPE . '.php');
	$db_type = SENDSTUDIO_DATABASE_TYPE . 'Db';
	$db = &new $db_type();

	$connection = $db->Connect(SENDSTUDIO_DATABASE_HOST, SENDSTUDIO_DATABASE_USER, SENDSTUDIO_DATABASE_PASS, SENDSTUDIO_DATABASE_NAME);

	if (!$connection) {
		list($error, $level) = $db->GetError();
		trigger_error($error, $level);
	}

	$GLOBALS['SendStudio']['Database'] = &$db;
}

/**
* We're always going to be using the user file to check permissions. let's load 'er up.
*/
require(SENDSTUDIO_API_DIRECTORY . '/user.php');

/**
* Finally, include our general file
*/
require(SENDSTUDIO_LIB_DIRECTORY . '/general/general.php');

/**
* Include our timezone converter file.
*/
require(SENDSTUDIO_LIB_DIRECTORY . '/general/convertdate.php');

/**
* And include the session file.
*/
if (session_id()) {
	if (ini_get('session.auto_start')) {
		$turn_off_message = "Session auto_start is enabled. Please turn it off for sendstudio to run.\n";
		if (isset($_SERVER['SERVER_SOFTWARE']) && is_numeric(strpos('apache'), strtolower($_SERVER['SERVER_SOFTWARE']))) {
			$turn_off_message .= "You can turn this off by creating a .htaccess file in the " . SENDSTUDIO_BASE_DIRECTORY . " folder with:\n
			php_flag session.auto_start 0\n\n
			in it.\nor\n";
		}
		$turn_off_message .= "You can turn this off in your php.ini file\n";
		die(nl2br($turn_off_message));
	}
} else {
	require(SENDSTUDIO_LIB_DIRECTORY . '/session/session.php');
}

/**
* GetDatabase
* Checks whether the global database is present.
*
* @return Mixed Will return false if there is no global database system present, otherwise returns a reference to it.
*/
function &GetDatabase()
{
	if (!isset($GLOBALS['SendStudio']['Database'])) {
		return false;
	}

	return $GLOBALS['SendStudio']['Database'];
}

/**
* GetLang
* Returns the defined language variable based on the name passed in.
*
* @param String $langvar Name of the language variable to retrieve.
*
* @return String Returns the defined string, if it doesn't exist issues a warning and returns the original language variable untouched.
*/
function GetLang($langvar=false)
{
	if (!$langvar) {
		return '';
	}

	if (!defined('LNG_' . $langvar)) {
		$message = '';
		if (function_exists('debug_backtrace')) {
			$btrace = debug_backtrace();
			$called_from = $btrace[0];
			$message = ' (Called from ' . basename($called_from['file']) . ', line ' . $called_from['line'] . ')';
		}
		die('Langvar \'' . $langvar . '\' doesn\'t exist' . $message);
	}
	$var = 'LNG_' . $langvar;
	return constant($var);
}

/**
* AdjustTime
* Adjusts the time based on the users timezone and the server timezone.
*
* @see GetUser
* @see User_API::UserTimeZone
* @see SENDSTUDIO_SERVERTIMEZONE
* @see ConvertDate
*
* @return Int The adjusted timestamp.
*/
function AdjustTime($time=0, $convert_to_gmt=true, $date_format='', $from_servertime=false)
{
	$user = &GetUser();

	if (!is_object($user)) {
		return false;
	}

	if (!isset($GLOBALS['DateConverter'])) {
		$GLOBALS['DateConverter'] = new ConvertDate(SENDSTUDIO_SERVERTIMEZONE, $user->Get('usertimezone'));
	}

	if ($convert_to_gmt) {
		if ((int)$time < 0) {
			$time = 0;
		}

		if ($time == 0) {
			$timenow = getdate();
			$hr = $timenow['hours'];
			$min = $timenow['minutes'];
			$sec = $timenow['seconds'];
			$mon = $timenow['mon'];
			$day = $timenow['mday'];
			$yr = $timenow['year'];
			return $GLOBALS['DateConverter']->ConvertToGMTFromServer($hr, $min, $sec, $mon, $day, $yr);
		}
		$hr = $time[0]; $min = $time[1]; $sec = $time[2]; $mon = $time[3]; $day = $time[4]; $yr = $time[5];
		if ($from_servertime) {
			return $GLOBALS['DateConverter']->ConvertToGMTFromServer($hr, $min, $sec, $mon, $day, $yr);
		}
		return $GLOBALS['DateConverter']->ConvertToGMT($hr, $min, $sec, $mon, $day, $yr);
	}

	return $GLOBALS['DateConverter']->ConvertFromGMT($time, $date_format);
}

/**
* GetUser
* If a userid is passed in, it will create a new user object and return the reference to it.
* If no userid is passed in, it will get the current user from the session.
*
* @param Int $userid If a userid is passed in, it will create a new user object and return it. If there is no userid it will get the current user from the session.
*
* @see GetSession
* @see Session::Get
* @see User
*
* @return Object The user object.
*/
function &GetUser($userid=0)
{
	if ($userid == 0) {
		$session = &GetSession();
		$UserDetails = $session->Get('UserDetails');
		return $UserDetails;
	}

	if ($userid == -1) {
		$user = &new User_API();
	} else {
		$user = &new User_API($userid);
	}
	return $user;
}

/**
* GetSession
* Checks whether the session is setup. Will start if it needs to.
*
* @return Object Returns the SendStudio session object.
*/
function &GetSession()
{
	if (!isset($_SESSION['SendStudioSession'])) {
		$_SESSION['SendStudioSession'] = new Session();
	}
	return $_SESSION['SendStudioSession'];
}

/**
* GetAuthenticationSystem
* Checks whether the global Authentication System is present.
* If it's present, will return it.
* If it's not present, it will set it up and then return it.
*
* @return Object Returns the authentication system object.
*/
function &GetAuthenticationSystem()
{
	if (isset($GLOBALS['SendStudio']['Authentication'])) {
		return $GLOBALS['SendStudio']['Authentication'];
	}
	require(SENDSTUDIO_API_DIRECTORY . '/authentication.php');
	$AuthSystem = &new AuthenticationSystem();
	$GLOBALS['SendStudio']['Authentication'] = $AuthSystem;
	return $AuthSystem;
}

/**
* Recursively use stripslashes on an array or a value
* If magic_quotes_gpc is on, strip all the slashes it added.
* By doing this we can be sure that all the gpc vars never have slashes and so
* we will always need to treat them the same way
*
* @param Mixed $value The array or value to perform stripslashes on
*
* @return Mixed The array or value which was stripslashed
*/
function stripslashes_deep($value='')
{
	if (get_magic_quotes_gpc()) {
		$value = is_array($value) ? array_map('stripslashes_deep', $value) : stripslashes($value);
	}
	return $value;
}

if (!function_exists( 'html_entity_decode')) {
	/**
	* html_entity_decode
	* Convert all HTML entities to their applicable characters
	* This function is created if it's not available, it became available in 4.3.0 or above.
	*
	* @param String $given_html The string to convert
	*
	* @return String The converted string
	*/
	function html_entity_decode($given_html='', $quote_style = ENT_QUOTES)
	{
		$trans_tbl = get_html_translation_table(HTML_ENTITIES);
		$trans_tbl = array_flip($trans_tbl);
		return strtr($given_html, $trans_tbl);
	}
}

?>
