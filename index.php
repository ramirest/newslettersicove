<?php
/**
* Simply redirect to the admin/ area.
*
* @version     $Id: index.php,v 1.2 2007/04/23 02:46:27 chris Exp $

*
* @package SendStudio
*/

$location = 'admin/index.php';

/**
* If sendstudio is set up and working,
* redirect to the full application url
* rather than just admin/index.php
*/
require(dirname(__FILE__) . '/admin/includes/config.php');
if (defined('SENDSTUDIO_IS_SETUP') && SENDSTUDIO_IS_SETUP == 1) {
	$location = SENDSTUDIO_APPLICATION_URL . '/admin/index.php';
}

header('Location: ' . $location);
exit();
?>
