<?php
/**
* This is the authentication system object.
*
* @version     $Id: authentication.php,v 1.8 2006/07/04 04:08:33 chris Exp $

*
* @package API
* @subpackage AuthenticationSystem
*/

/**
* Require the base API class.
*/
require_once(dirname(__FILE__) . '/api.php');

/**
* This class does authentication.
*
* @package API
* @subpackage AuthenticationSystem
*/
class AuthenticationSystem extends API
{

	/**
	* Constructor
	* Sets up the database object for easy use.
	*
	* @return Void Doesn't return anything.
	*/
	function AuthenticationSystem()
	{
		$this->GetDb();
	}

	/**
	* Authenticate
	* Authenticates the user. Will return false if the user doesn't exist or the passwords don't match.
	*
	* @param String $username Username to authenticate.
	* @param String $password Password to use to authenticate the user.
	*
	* @return Mixed Returns false if the user doesn't exist or can't authenticate, otherwise it will return the UserID of the user it found.
	*/
	function Authenticate($username='', $password='')
	{
		if (!$username || !$password) {
			return false;
		}

		$qry = "SELECT userid FROM " . SENDSTUDIO_TABLEPREFIX . "users WHERE username='" . $this->Db->Quote($username) . "' AND password='" . $this->Db->Quote(md5($password)) . "' AND status=1";

		$result = $this->Db->Query($qry);
		if (!$result) {
			return false;
		}
		$details = $this->Db->Fetch($result);
		return $details;
	}
}

?>
