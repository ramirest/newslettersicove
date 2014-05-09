<?php

/**
* Include the init file so it sets everything up.
*/
require('functions/init.php');

/**
* See whether this is set up or not. If it's not set up, start the installer.
*/
if (!SENDSTUDIO_IS_SETUP) {
	$page = 'installer';

	$old_config_file = SENDSTUDIO_BASE_DIRECTORY.'/../includes/config.inc.php';
	if (is_file($old_config_file)) {
		require($old_config_file);
		if (isset($IsSetup) && $IsSetup == 1) {
			$page = 'upgrade';
		}
	}

	require(SENDSTUDIO_FUNCTION_DIRECTORY . '/' . $page . '.php');
	$system = &new $page();
	$system->Process();
	exit;
}

/**
* If sendstudio is setup, make sure we redirect to the sendstudio application url.
* That should stop problems if we go to the page with/without the www.
*/
if (SENDSTUDIO_IS_SETUP) {
	$url_parts = parse_url(SENDSTUDIO_APPLICATION_URL);
	if ($url_parts['host'] != $_SERVER['HTTP_HOST']) {
		header('Location: ' . SENDSTUDIO_APPLICATION_URL . '/admin/index.php');
		exit;
	}
}


/**
* Work out what page we're going to be working with.
*/
$page = (isset($_GET['Page'])) ? strtolower($_GET['Page']) : 'index';

/**
* If someone tries to be tricky, redirect them back to the main page.
*/
if (!is_file(SENDSTUDIO_FUNCTION_DIRECTORY . '/' . $page . '.php')) {
	$page = 'index';
}

/**
* Set up the session and make sure we're logged in.
* If we're not logged in, see if there is a cookie called 'SendStudioLogin'.
* This is used to "remember me" so you don't have to type in a username/password every time
* It Checks the user is valid and also checks to make sure the random LoginCheck string matches the one in the cookie.
* If they don't match or the user isn't valid, or the user is inactive, the user is taken back to the login page.
* If they do match and the user is valid, then you are logged in.
*/
$session = &GetSession();
if (!$session->LoggedIn()) {
	if (isset($_COOKIE['SendStudioLogin'])) {
		$valid = false;
		// check it's a valid user first.
		$cookie_info = @unserialize(base64_decode($_COOKIE['SendStudioLogin']));
		if (isset($cookie_info['user'])) {
			$userid = $cookie_info['user'];
			$user = &GetUser($userid);
			if (!isset($user->settings['LoginCheck'])) {
				$valid = false;
			} else {
				if ($user->userid && $user->settings['LoginCheck'] == $cookie_info['rand'] && $user->Status() == true) {
					$valid = true;
				}
			}
		}
		if ($valid) {
			$session->Set('UserDetails', $user);
			$user->UpdateLoginTime();
		} else {
			$page = 'login';
		}
	} else {
		$page = 'login';
	}
}

/**
* Include the 'page' we're working with and process it.
*/
require(SENDSTUDIO_FUNCTION_DIRECTORY . '/' . $page . '.php');
$system = &new $page();
$system->Process();

?>
