<?php
/**
* This file handles previewing of templates, newsletters, autoresponders and so on.
*
* @version     $Id: preview.php,v 1.5 2007/06/04 08:16:05 chris Exp $

*
* @package SendStudio
* @subpackage SendStudio_Functions
*/

/**
* Include the base sendstudio functions.
*/
require(dirname(__FILE__) . '/sendstudio_functions.php');

/**
* Class for handling previewing of items like templates, newsletters, autoresponders and forms.
*
* @package SendStudio
* @subpackage SendStudio_Functions
*/
class Preview extends SendStudio_Functions
{

	/**
	* Process
	* Prints out the preview frames.
	*
	* @return Void Prints out the frame previews, doesn't return anything.
	*/
	function Process()
	{
		$action = '';
		if (isset($_GET['Action'])) {
			$action = strtolower($_GET['Action']);
		}

		$session = &GetSession();

		$details = $session->Get('PreviewWindow');

		if (empty($details)) {
			return;
		}

		switch($action) {
			case 'top':
				$GLOBALS['SwitchOptions'] = '';

				if ($details['format'] == 't' || $details['format'] == 'b') {
					$GLOBALS['SwitchOptions'] .= '<option value="text">' . GetLang('TextPreview') . '</option>';
				}

				if ($details['format'] == 'h' || $details['format'] == 'b') {
					$GLOBALS['SwitchOptions'] .= '<option value="html" SELECTED>' . GetLang('HTMLPreview') . '</option>';
				}
				$this->ParseTemplate('Preview_Window_TopFrame', false, false);
			break;

			case 'display':
				$displaytype = 'html';

				if (isset($_GET['Type'])) {
					$displaytype = $_GET['Type'];
				}

				if ($displaytype != 'html' && $displaytype != 'text') {
					$displaytype = 'html';
				}

				if ($details['format'] == 't') {
					$displaytype = 'text';
				}

				if ($displaytype == 'html') {
					if (isset($details['htmlcontent']) && !empty($details['htmlcontent'])) {
						header('Content-type: text/html; charset=' . SENDSTUDIO_CHARSET);
						echo $details['htmlcontent'];
						return;
					}
				}
				header('Content-type: text/html; charset=' . SENDSTUDIO_CHARSET);
				echo nl2br($details['textcontent']);
			break;
		}
	}
}
?>
