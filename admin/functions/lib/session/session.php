<?php
/**
* This file only has the Session class in it.
* The session class handles reading, writing, garbage collection, getting, setting of session data.
* All session related work goes on in the database to stop the temp directory from filling up.
* It checks whether the product is set up before using the database.
*
* @version     $Id: session.php,v 1.13 2007/05/16 06:23:41 chris Exp $

*
* @package Library
* @subpackage Session
*/

/**
* This is the class for the session system.
*
* @package Library
* @subpackage Session
*/
class Session
{

	/**
	* SessionName
	* Name of the session.
	*
	* @see Session
	*
	* @var String
	*/
	var $SessionName = 'SendStudioSession';


	/**
	* _ExternalVars
	* Where we get/set 'external' variables. They are stored here for easy access with Set/Get functions.
	*
	* @see Set
	* @see Get
	*
	* @var Array
	*/
	var $_ExternalVars = array();


	/**
	* Session
	* Sets the session name.
	*
	* @param String $SessionName Session name can override the class SessionName variable.
	*
	* @see SessionName
	*
	* @return True This always returns true
	*/
	function Session($SessionName='')
	{
		if ($SessionName) {
			$this->SessionName = $SessionName;
		}
		return true;
	}

	/**
	* Set
	* Sets the variable to the value passed in.
	*
	* @param Mixed $var Variable name to remember
	* @param Mixed $val Value to set the variable to.
	*
	* @see _ExternalVars
	*
	* @return Boolean Returns false if there is no variable name, otherwise sets the variable and returns true.
	*/
	function Set($var='', $val='')
	{
		if ($var == '') {
			return false;
		}
		$this->_ExternalVars[$var] = $val;
		return true;
	}

	/**
	* Get
	* Gets the value stored based on the variable name.
	*
	* @param Mixed $var Variable name to fetch.
	*
	* @see _ExternalVars
	*
	* @return Mixed Returns false if there is no variable or if it doesn't exist. Otherwise returns the value.
	*/
	function Get($var='')
	{
		if ($var == '') {
			return false;
		}

		if (!isset($this->_ExternalVars[$var])) {
			return false;
		}

		return $this->_ExternalVars[$var];
	}

	/**
	* Remove
	* Removes session information totally. Set can change something to be blank but it doesn't remove it.
	*
	* @param Mixed $var Variable name to fetch.
	*
	* @see Set
	* @see _ExternalVars
	*
	* @return Boolean Returns false if the variable doesn't exist, otherwise unsets the data and returns true.
	*/
	function Remove($var='')
	{
		if ($var == '') {
			return false;
		}

		unset($this->_ExternalVars[$var]);
		return true;
	}

	/**
	* LoggedIn
	* Easy check whether a user is logged in or not.
	*
	* @see Get
	*
	* @return Mixed Returns UserDetails if the user is logged in, otherwise returns false.
	*/
	function LoggedIn()
	{
		return $this->Get('UserDetails');
	}
}

/**
* Only set the session path if sendstudio is set up properly.
* Part of that process includes setting right permissions on the TEMP_DIRECTORY folder.
*/

if (!defined('SENDSTUDIO_CRON_JOB') && !defined('SENDSTUDIO_NO_SESSION')) {
	if (is_writable(TEMP_DIRECTORY)) {
		ini_set('session.save_handler', 'files');
		if (defined('TEMP_DIRECTORY')) {
			ini_set('session.save_path', TEMP_DIRECTORY);
		}
	}

	/**
	* The captcha-pages need to explicitly pass in the session id so it's the same for all images.
	* Otherwise image 1 would set a different session_id to image 2 and so on.
	* That's the only reason we need this here.
	*/
	if (isset($_GET['ss'])) {
		// make sure that the session passed in to the captcha page ONLY contains A-Z characters.
		// so we don't have to worry about non-alpha-numeric characters.
		if (preg_match('/[^A-Z]/', $_GET['ss'])) {
			die();
		}
		session_id($_GET['ss']);
	}
	session_start();
}
?>
