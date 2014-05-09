<?php
/**
* This file has the custom fields display in it - this is the popup window where you choose which custom field to put in your newsletter/template/autoresponder.
*
* @version     $Id: showcustomfields.php,v 1.12 2007/05/02 15:05:09 chris Exp $

*
* @package SendStudio
* @subpackage SendStudio_Functions
*/

/**
* Include the base sendstudio functions.
*/
require(dirname(__FILE__) . '/sendstudio_functions.php');

/**
* Class for handling the custom field descriptions.
*
* @package SendStudio
* @subpackage SendStudio_Functions
*/
class ShowCustomFields extends SendStudio_Functions
{

	/**
	* Constructor
	* Loads the language file.
	*
	* @see LoadLanguageFile
	*
	* @return Void Doesn't return anything.
	*/
	function ShowCustomFields()
	{
		$this->LoadLanguageFile();
	}

	/**
	* Process
	* Prints out the custom fields list.
	*
	* @see PrintHeader
	* @see GetApi
	* @see GetUser
	* @see User_API::GetLists
	* @see Lists_API::GetCustomFields
	* @see Forms_API::GetUserForms
	*
	* @return Void Prints out the list, doesn't return anything.
	*/
	function Process()
	{
		$this->PrintHeader(true);

		$listapi = $this->GetApi('Lists');

		$formapi = $this->GetApi('Forms');

		$GLOBALS['ContentArea'] = $_GET['ContentArea'];

		$GLOBALS['EditorName'] = 'myDeveditControl';
		if (isset($_GET['EditorName'])) {
			$GLOBALS['EditorName'] = $_GET['EditorName'];
		}

		$template = $this->ParseTemplate('ShowCustomFields_List_Start', true, false);

		$user = &GetUser();

		$lists = $user->GetLists();

		$customfieldlist = '';

		$GLOBALS['AreaName'] = GetLang('ShowCustomFields_BasicAreas');
		$customfieldlist .= $this->ParseTemplate('ShowCustomFields_List_List', true, false);

		$GLOBALS['CustomFieldRequired'] = '';

		foreach (array('ListName', 'UnsubscribeLink', 'EmailAddress', 'WebVersion', 'MailingListArchive', 'ConfirmLink', 'SubscribeDate') as $p => $area) {
			$GLOBALS['CustomFieldName'] = GetLang('CustomFields_' . $area);
			$GLOBALS['LinkName'] = strtolower($area);
			$customfieldlist .= $this->ParseTemplate('ShowCustomFields_List_CustomFields', true, false);
		}

		$session = &GetSession();
		$session_list = false;
		$session_autoresponder = $session->Get('Autoresponders');

		$pagename = (isset($_GET['PageName'])) ? strtolower($_GET['PageName']) : false;
		if ($session_autoresponder && $pagename == 'autoresponders') {
			$session_list = $session_autoresponder['list'];
		}

		foreach ($lists as $listid => $listdetails) {
			if ($session_list && $session_list != $listid) {
				continue;
			}

			$customfields = $listapi->GetCustomFields($listid);
			if (empty($customfields)) {
				continue; // if there are no custom fields for this list, try the next one.
			}

			$GLOBALS['AreaName'] = sprintf(GetLang('ShowCustomFields_AreaName'), $listdetails['name']);
			$customfieldlist .= $this->ParseTemplate('ShowCustomFields_List_List', true, false);
			foreach ($customfields as $pos => $details) {
				$GLOBALS['CustomFieldName'] = $details['name'];
				$GLOBALS['LinkName'] = $details['name'];
				$GLOBALS['CustomFieldRequired'] = '';
				if ($details['required']) {
					$GLOBALS['CustomFieldRequired'] = GetLang('CustomFieldRequired_Popup');
				}

				$customfieldlist .= $this->ParseTemplate('ShowCustomFields_List_CustomFields', true, false);
			}
		}

		$modify_forms = $formapi->GetUserForms($user->userid, 'modify');
		if (!empty($modify_forms)) {
			$GLOBALS['AreaName'] = GetLang('ShowCustomFields_ModifyForms');
			$customfieldlist .= $this->ParseTemplate('ShowCustomFields_List_List', true, false);
			foreach ($modify_forms as $p => $formdetails) {
				$GLOBALS['CustomFieldName'] = htmlspecialchars($formdetails['name']);
				$GLOBALS['LinkName'] = 'modifydetails_' . $formdetails['formid'];
				$customfieldlist .= $this->ParseTemplate('ShowCustomFields_List_CustomFields', true, false);
			}
		}

		$sendfriend_forms = $formapi->GetUserForms($user->userid, 'friend');
		if (!empty($sendfriend_forms)) {
			$GLOBALS['AreaName'] = GetLang('ShowCustomFields_SendToFriendForms');
			$customfieldlist .= $this->ParseTemplate('ShowCustomFields_List_List', true, false);
			foreach ($sendfriend_forms as $p => $formdetails) {
				$GLOBALS['CustomFieldName'] = htmlspecialchars($formdetails['name']);
				$GLOBALS['LinkName'] = 'sendfriend_' . $formdetails['formid'];
				$customfieldlist .= $this->ParseTemplate('ShowCustomFields_List_CustomFields', true, false);
			}
		}

		$template = str_replace('%%TPL_ShowCustomFields_List_Details%%', $customfieldlist, $template);
		echo $template;
		$this->PrintFooter(true);
	}
}
?>
