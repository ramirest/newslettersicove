<?php
/**
* Simply include the functions/remote.php file. That file handles including the database, setting itself up and finally processing the request.
* This is done so we don't have to worry about full urls etc to the functions/remote.php file.
* Seems a little redundant but saves a lot of headaches.
*
* @version     $Id: remote.php,v 1.3 2006/08/22 07:23:15 chris Exp $

*
* @package SendStudio
*/

/**
* The other remote file handles everything. We keep this outside of the functions/ folder to make it easier to reference in a url.
*/
require('functions/remote.php');

?>
