<?php
/**
* This file is here for b/c reasons. It will only display the blank image, it will not record statistics.
*
* @version     $Id: sendopen.php,v 1.2 2007/03/26 08:08:29 chris Exp $

*
* @package SendStudio
*/

/**
* Require base sendstudio functionality. This connects to the database, sets up our base paths and so on.
*/
require(dirname(__FILE__) . '/../admin/functions/init.php');

DisplayImage();

/**
* DisplayImage
* Loads up the 'openimage' and displays it. It will exit after displaying the image.
*
* @return Void Doesn't return anything.
*/
function DisplayImage()
{
	// open the file in a binary mode
	$name = SENDSTUDIO_IMAGE_DIRECTORY . '/open.gif';
	$fp = fopen($name, 'rb');

	// send the right headers
	header("Content-Type: image/gif");
	header("Content-Length: " . filesize($name));

	// dump the picture and stop the script
	fpassthru($fp);
	exit(0);
}

?>
