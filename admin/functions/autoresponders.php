<?php
/**
* This file has the autoresponders page in it. This allows you to manage, create and edit your autoresponders.
*
* @version     $Id: autoresponders.php,v 1.52 2007/05/28 07:13:22 scott Exp $

*
* @package SendStudio
* @subpackage SendStudio_Functions
*/

/**
* Include the base sendstudio functions.
*/
require(dirname(__FILE__) . '/sendstudio_functions.php');

/**
* Class for management of autoresponders.
*
* @package SendStudio
* @subpackage SendStudio_Functions
*/
class Autoresponders extends SendStudio_Functions
{

	/**
	* ValidSorts
	* An array of sorts you can use with autoresponder management.
	*
	* @var Array
	*/
	var $ValidSorts = array('name', 'createdate', 'hoursaftersubscription');

	/**
	* _DefaultSort
	* Default sort for autoresponders is hours after subscription
	*
	* @see GetSortDetails
	*
	* @var String
	*/
	var $_DefaultSort = 'hoursaftersubscription';

	/**
	* _DefaultDirection
	* Default sort direction for autoresponders is ascending
	*
	* @see GetSortDetails
	*
	* @var String
	*/
	var $_DefaultDirection = 'Up';

	/**
	* Constructor
	* Loads the language file.
	* Adds 'sendpreview' to the list of valid popup windows.
	*
	* @return Void Doesn't return anything.
	*/
	function Autoresponders()
	{
		$this->PopupWindows[] = 'sendpreview';
		$this->LoadLanguageFile();
	}

	/**
	* Process
	* Takes the appropriate action based on the action and user permissions
	*
	* @see GetUser
	* @see User_API::HasAccess
	* @see PrintHeader
	* @see PrintFooter
	*
	* @return Void Doesn't return anything. Takes the appropriate action.
	*/
	function Process()
	{
		$GLOBALS['Message'] = '';

		$action = (isset($_GET['Action'])) ? strtolower(urldecode($_GET['Action'])) : null;
		$user = &GetUser();

		$secondary_actions = array('step2', 'sendpreview', 'view', 'processpaging', 'activate', 'deactivate', 'change');
		if (in_array($action, $secondary_actions)) {
			$access = $user->HasAccess('Autoresponders');
		} else {
			$access = $user->HasAccess('Autoresponders', $action);
		}

		$popup = (in_array($action, $this->PopupWindows)) ? true : false;
		$this->PrintHeader($popup);

		if (!$access) {
			if (!$popup) {
				$this->DenyAccess();
				return;
			}
		}

		if ($action == 'processpaging') {
			$perpage = (int)$_GET['PerPageDisplay'];
			$display_settings = array('NumberToShow' => $perpage);
			$user->SetSettings('DisplaySettings', $display_settings);
			$action = 'step2';
		}

		switch ($action) {
			case 'activate':
			case 'deactivate':
				$access = $user->HasAccess('Autoresponders', 'Approve');
				if (!$access) {
					$this->DenyAccess();
					break;
				}

				$id = (int)$_GET['id'];
				$autoapi = $this->GetApi();
				$autoapi->Load($id);
				if ($action == 'activate') {
					$autoapi->Set('active', $user->Get('userid'));
					$GLOBALS['Message'] = $this->PrintSuccess('AutoresponderActivatedSuccessfully');
				} else {
					$autoapi->Set('active', 0);
					$GLOBALS['Message'] = $this->PrintSuccess('AutoresponderDeactivatedSuccessfully');
				}
				$autoapi->Save();

				if (isset($_GET['list'])) {
					$listid = (int)$_GET['list'];
				}

				$this->ManageAutoresponders($listid);
			break;

			case 'copy':
				$id = (isset($_GET['id'])) ? (int)$_GET['id'] : 0;
				$api = $this->GetApi();
				list($result, $files_copied) = $api->Copy($id);
				if (!$result) {
					$GLOBALS['Error'] = GetLang('AutoresponderCopyFail');
					$GLOBALS['Message'] = $this->ParseTemplate('ErrorMsg', true, false);
				} else {
					$api->Set('active', 0);
					$api->Save();
					$GLOBALS['Message'] = $this->PrintSuccess('AutoresponderCopySuccess');
					$GLOBALS['Message'] .= $this->PrintWarning('AutoresponderHasBeenDisabled');
					if (!$files_copied) {
						$GLOBALS['Error'] = GetLang('AutoresponderFilesCopyFail');
						$GLOBALS['Message'] .= $this->ParseTemplate('ErrorMsg', true, false);
					}
				}
				if (isset($_GET['list'])) {
					$listid = (int)$_GET['list'];
				}

				$this->ManageAutoresponders($listid);
			break;

			case 'change':
				$subaction = strtolower($_POST['ChangeType']);
				$autolist = $_POST['autoresponders'];

				switch ($subaction) {
					case 'delete':
						$access = $user->HasAccess('Autoresponders', 'Delete');
						if ($access) {
							$this->DeleteAutoresponders($autolist);
						} else {
							$this->DenyAccess();
						}
					break;

					case 'approve':
					case 'disapprove':
						$access = $user->HasAccess('Autoresponders', 'Approve');
						if ($access) {
							$this->ActionAutoresponders($autolist, $subaction);
						} else {
							$this->DenyAccess();
						}
					break;
				}
			break;

			case 'delete':
				$id = (int)$_GET['id'];
				$autolist = array($id);
				$access = $user->HasAccess('Autoresponders', 'Delete');
				if ($access) {
					$this->DeleteAutoresponders($autolist);
				} else {
					$this->DenyAccess();
				}
			break;

			case 'step2':
				$listid = (isset($_POST['list'])) ? $_POST['list'] : 0;
				if (isset($_GET['list'])) {
					$listid = (int)$_GET['list'];
				}

				$this->ManageAutoresponders($listid);
			break;

			case 'sendpreview':
				$this->SendPreview();
			break;

			case 'view':
				$id = (isset($_GET['id'])) ? (int)$_GET['id'] : 0;
				$type = strtolower(get_class($this));
				$autoresponderapi = $this->GetApi();
				if (!$autoresponderapi->Load($id)) {
					break;
				}

				$details = array();
				$details['htmlcontent'] = $autoresponderapi->GetBody('HTML');
				$details['textcontent'] = $autoresponderapi->GetBody('Text');
				$details['format'] = $autoresponderapi->format;
				$this->PreviewWindow($details);
			break;

			case 'edit':
				$session = &GetSession();

				$subaction = (isset($_GET['SubAction'])) ? strtolower(urldecode($_GET['SubAction'])) : false;

				switch ($subaction) {
					case 'save':
					case 'complete':
						$user = $session->Get('UserDetails');
						$session_autoresponder = $session->Get('Autoresponders');

						$listid = $session_autoresponder['list'];

						if (!$session_autoresponder || !isset($session_autoresponder['autoresponderid'])) {
							$this->ManageAutoresponders($listid);
							break;
						}

						$text_unsubscribelink_found = true;
						$html_unsubscribelink_found = true;

						$id = $session_autoresponder['autoresponderid'];

						$autoapi = $this->GetApi();
						$autoapi->Load($id);

						$autoapi->Set('listid', $listid);

						if (isset($_POST['TextContent'])) {
							$textcontent = $_POST['TextContent'];
							$autoapi->SetBody('Text', $textcontent);
							$text_unsubscribelink_found = $this->CheckForUnsubscribeLink($textcontent);
						}

						if (isset($_POST['myDevEditControl_html'])) {
							$htmlcontent = $_POST['myDevEditControl_html'];
							$autoapi->SetBody('HTML', $htmlcontent);
							$html_unsubscribelink_found = $this->CheckForUnsubscribeLink($htmlcontent);
						}

						if (isset($_POST['subject'])) {
							$autoapi->Set('subject', $_POST['subject']);
						}

						foreach (array('name', 'format', 'searchcriteria', 'sendfromname', 'sendfromemail', 'replytoemail', 'bounceemail', 'tracklinks', 'trackopens', 'multipart', 'embedimages', 'hoursaftersubscription', 'charset', 'includeexisting', 'to_firstname', 'to_lastname') as $p => $area) {
							$autoapi->Set($area, $session_autoresponder[$area]);
						}

						$autoapi->Set('active', 0);

						$dest = strtolower(get_class($this));

						$movefiles_result = $this->MoveFiles($dest, $id);

						if ($movefiles_result) {
							if (isset($textcontent)) {
								$textcontent = $this->ConvertContent($textcontent, $dest, $id);
								$autoapi->SetBody('Text', $textcontent);
							}
							if (isset($htmlcontent)) {
								$htmlcontent = $this->ConvertContent($htmlcontent, $dest, $id);
								$autoapi->SetBody('HTML', $htmlcontent);
							}
						}

						$result = $autoapi->Save();

						if (!$result) {
							$GLOBALS['Error'] = GetLang('UnableToUpdateAutoresponder');
							$GLOBALS['Message'] = $this->ParseTemplate('ErrorMsg', true, false);
							$this->ManageAutoresponders($listid);
							break;
						} else {
							$GLOBALS['Message'] = $this->PrintSuccess('AutoresponderUpdated');
						}

						list($attachments_status, $attachments_status_msg) = $this->SaveAttachments($dest, $id);

						if ($attachments_status) {
							if ($attachments_status_msg != '') {
								$GLOBALS['Success'] = $attachments_status_msg;
								$GLOBALS['Message'] .= $this->ParseTemplate('SuccessMsg', true, false);
							}
						} else {
							$GLOBALS['Error'] = $attachments_status_msg;
							$GLOBALS['Message'] .= $this->ParseTemplate('ErrorMsg', true, false);
						}

						list($del_attachments_status, $del_attachments_status_msg) = $this->CleanupAttachments($dest, $id);

						if ($del_attachments_status) {
							if ($del_attachments_status_msg) {
								$GLOBALS['Success'] = $del_attachments_status_msg;
								$GLOBALS['Message'] .= $this->ParseTemplate('SuccessMsg', true, false);
							}
						} else {
							$GLOBALS['Error'] = $del_attachments_status_msg;
							$GLOBALS['Message'] .= $this->ParseTemplate('ErrorMsg', true, false);
						}

						if (!$html_unsubscribelink_found) {
							$GLOBALS['Message'] .= $this->PrintWarning('NoUnsubscribeLinkInHTMLContent');
						}

						if (!$text_unsubscribelink_found) {
							$GLOBALS['Message'] .= $this->PrintWarning('NoUnsubscribeLinkInTextContent');
						}

						if ($subaction == 'save') {
							$GLOBALS['Message'] .= $this->PrintWarning('AutoresponderHasBeenDisabled_Save');
							$this->EditAutoresponderStep4($id);
							break;
						}

						$GLOBALS['Message'] .= $this->PrintWarning('AutoresponderHasBeenDisabled');
						$this->ManageAutoresponders($listid);

					break;

					case 'step4':
						$sessionauto = $session->Get('Autoresponders');

						$sessionauto['sendfromname'] = $_POST['sendfromname'];
						$sessionauto['sendfromemail'] = $_POST['sendfromemail'];
						$sessionauto['replytoemail'] = $_POST['replytoemail'];
						$sessionauto['bounceemail'] = $_POST['bounceemail'];

						$sessionauto['charset'] = $_POST['charset'];

						$sessionauto['format'] = $_POST['format'];
						$sessionauto['hoursaftersubscription'] = (int)$_POST['hoursaftersubscription'];
						$sessionauto['trackopens'] = (isset($_POST['trackopens'])) ? true : false;
						$sessionauto['tracklinks'] = (isset($_POST['tracklinks'])) ? true : false;
						$sessionauto['multipart'] = (isset($_POST['multipart'])) ? true : false;
						$sessionauto['embedimages'] = (isset($_POST['embedimages'])) ? true : false;
						$sessionauto['includeexisting'] = (isset($_POST['includeexisting'])) ? true : false;

						$sessionauto['to_lastname'] = 0;
						if (isset($_POST['to_lastname'])) {
							$sessionauto['to_lastname'] = (int)$_POST['to_lastname'];
						}
						$sessionauto['to_firstname'] = 0;
						if (isset($_POST['to_firstname'])) {
							$sessionauto['to_firstname'] = (int)$_POST['to_firstname'];
						}

						$session->Set('Autoresponders', $sessionauto);

						$this->EditAutoresponderStep4($sessionauto['autoresponderid']);
					break;

					case 'step3':
						$sessionauto = $session->Get('Autoresponders');
						$sessionauto['name'] = $_POST['name'];
						$sessionauto['searchcriteria'] = array();
						$sessionauto['searchcriteria']['emailaddress'] = $_POST['emailaddress'];
						$sessionauto['searchcriteria']['format'] = $_POST['format'];
						$sessionauto['searchcriteria']['confirmed'] = $_POST['confirmed'];

						$customfields = (isset($_POST['CustomFields'])) ? $_POST['CustomFields'] : array();
						$sessionauto['searchcriteria']['customfields'] = $customfields;

						foreach ($sessionauto['searchcriteria']['customfields'] as $fieldid => $fieldvalue) {
							if (!$fieldvalue) {
								unset($sessionauto['searchcriteria']['customfields'][$fieldid]);
								continue;
							}
						}

						if (isset($_POST['clickedlink']) && isset($_POST['linkid'])) {
							$sessionauto['searchcriteria']['link'] = $_POST['linkid'];
						}

						if (isset($_POST['openednewsletter']) && isset($_POST['newsletterid'])) {
							$sessionauto['searchcriteria']['newsletter'] = $_POST['newsletterid'];
						}

						$session->Set('Autoresponders', $sessionauto);

						$this->EditAutoresponderStep3($sessionauto['autoresponderid']);
					break;

					default:
						$id = (int)$_GET['id'];

						$session->Remove('Autoresponders');
						$autosession = array('list' => (int)$_GET['list'], 'autoresponderid' => $id);
						$session->Set('Autoresponders', $autosession);

						$this->EditAutoresponderStep1($id);
				}
			break;

			case 'create':
				$session = &GetSession();

				$subaction = (isset($_GET['SubAction'])) ? strtolower(urldecode($_GET['SubAction'])) : false;

				switch ($subaction) {

					case 'save':
					case 'complete':
						$autoresponder = $this->GetApi();

						$user = $session->Get('UserDetails');
						$session_autoresponder = $session->Get('Autoresponders');

						if (!$session_autoresponder || !isset($session_autoresponder['name'])) {
							$this->ManageAutoresponders($listid);
							break;
						}

						$text_unsubscribelink_found = true;
						$html_unsubscribelink_found = true;

						$listid = $session_autoresponder['list'];

						$autoresponder->Set('listid', $listid);

						if (isset($_POST['TextContent'])) {
							$textcontent = $_POST['TextContent'];
							$autoresponder->SetBody('Text', $textcontent);
							$text_unsubscribelink_found = $this->CheckForUnsubscribeLink($textcontent);
						}

						if (isset($_POST['myDevEditControl_html'])) {
							$htmlcontent = $_POST['myDevEditControl_html'];
							$autoresponder->SetBody('HTML', $htmlcontent);
							$html_unsubscribelink_found = $this->CheckForUnsubscribeLink($htmlcontent);
						}

						if (isset($_POST['subject'])) {
							$autoresponder->Set('subject', $_POST['subject']);
						}

						foreach (array('name', 'format', 'searchcriteria', 'sendfromname', 'sendfromemail', 'replytoemail', 'bounceemail', 'tracklinks', 'trackopens', 'multipart', 'embedimages', 'hoursaftersubscription', 'charset', 'includeexisting', 'to_firstname', 'to_lastname') as $p => $area) {
							$autoresponder->Set($area, $session_autoresponder[$area]);
						}

						$autoresponder->Set('active', 0);

						$autoresponder->ownerid = $user->userid;

						$result = $autoresponder->Create();

						if (!$result) {
							$GLOBALS['Error'] = GetLang('UnableToCreateAutoresponder');
							$GLOBALS['Message'] = $this->ParseTemplate('ErrorMsg', true, false);
							$this->ManageAutoresponders($listid);
							break;
						}

						$session_autoresponder['autoresponderid'] = $result;
						$session->Set('Autoresponders', $session_autoresponder);

						$GLOBALS['Message'] = $this->PrintSuccess('AutoresponderCreated');

						$dest = strtolower(get_class($this));
						$movefiles_result = $this->MoveFiles($dest, $result);
						if ($movefiles_result) {
							if (isset($textcontent)) {
								$textcontent = $this->ConvertContent($textcontent, $dest, $result);
								$autoresponder->SetBody('Text', $textcontent);
							}
							if (isset($htmlcontent)) {
								$htmlcontent = $this->ConvertContent($htmlcontent, $dest, $result);
								$autoresponder->SetBody('HTML', $htmlcontent);
							}
						}

						$autoresponder->Save();

						list($attachments_status, $attachments_status_msg) = $this->SaveAttachments($dest, $result);

						if ($attachments_status) {
							if ($attachments_status_msg != '') {
								$GLOBALS['Success'] = $attachments_status_msg;
								$GLOBALS['Message'] .= $this->ParseTemplate('SuccessMsg', true, false);
							}
						} else {
							$GLOBALS['Error'] = $attachments_status_msg;
							$GLOBALS['Message'] .= $this->ParseTemplate('ErrorMsg', true, false);
						}

						if (!$html_unsubscribelink_found) {
							$GLOBALS['Message'] .= $this->PrintWarning('NoUnsubscribeLinkInHTMLContent');
						}

						if (!$text_unsubscribelink_found) {
							$GLOBALS['Message'] .= $this->PrintWarning('NoUnsubscribeLinkInTextContent');
						}

						if ($subaction == 'save') {
							$GLOBALS['Message'] = $this->PrintWarning('AutoresponderHasBeenDisabled_Save');
							$this->EditAutoresponderStep4($result);
							break;
						}
						$GLOBALS['Message'] = $this->PrintWarning('AutoresponderHasBeenDisabled');

						$this->ManageAutoresponders($listid);
					break;

					case 'step4':
						$sessionauto = $session->Get('Autoresponders');

						$sessionauto['sendfromname'] = $_POST['sendfromname'];
						$sessionauto['sendfromemail'] = $_POST['sendfromemail'];
						$sessionauto['replytoemail'] = $_POST['replytoemail'];
						$sessionauto['bounceemail'] = $_POST['bounceemail'];

						$sessionauto['charset'] = $_POST['charset'];

						$sessionauto['format'] = $_POST['format'];
						$sessionauto['hoursaftersubscription'] = (int)$_POST['hoursaftersubscription'];
						$sessionauto['trackopens'] = (isset($_POST['trackopens'])) ? true : false;
						$sessionauto['tracklinks'] = (isset($_POST['tracklinks'])) ? true : false;
						$sessionauto['multipart'] = (isset($_POST['multipart'])) ? true : false;
						$sessionauto['embedimages'] = (isset($_POST['embedimages'])) ? true : false;

						$sessionauto['includeexisting'] = (isset($_POST['includeexisting'])) ? true : false;

						$sessionauto['to_lastname'] = 0;
						if (isset($_POST['to_lastname'])) {
							$sessionauto['to_lastname'] = (int)$_POST['to_lastname'];
						}

						$sessionauto['to_firstname'] = 0;
						if (isset($_POST['to_firstname'])) {
							$sessionauto['to_firstname'] = (int)$_POST['to_firstname'];
						}

						if (isset($_POST['TemplateID'])) {
							$sessionauto['TemplateID'] = $_POST['TemplateID'];
						}

						$session->Set('Autoresponders', $sessionauto);

						$this->EditAutoresponderStep4();

					break;

					case 'step3':
						$sessionauto = $session->Get('Autoresponders');
						$sessionauto['name'] = $_POST['name'];
						$sessionauto['searchcriteria'] = array();
						$sessionauto['searchcriteria']['emailaddress'] = $_POST['emailaddress'];
						$sessionauto['searchcriteria']['format'] = $_POST['format'];
						$sessionauto['searchcriteria']['confirmed'] = $_POST['confirmed'];

						$customfields = (isset($_POST['CustomFields'])) ? $_POST['CustomFields'] : array();
						$sessionauto['searchcriteria']['customfields'] = $customfields;

						foreach ($sessionauto['searchcriteria']['customfields'] as $fieldid => $fieldvalue) {
							if (!$fieldvalue) {
								unset($sessionauto['searchcriteria']['customfields'][$fieldid]);
								continue;
							}
						}

						if (isset($_POST['clickedlink']) && isset($_POST['linkid'])) {
							$sessionauto['searchcriteria']['link'] = $_POST['linkid'];
						}

						if (isset($_POST['openednewsletter']) && isset($_POST['newsletterid'])) {
							$sessionauto['searchcriteria']['newsletter'] = $_POST['newsletterid'];
						}

						$session->Set('Autoresponders', $sessionauto);

						$this->EditAutoresponderStep3();
					break;

					case 'step2':
						$listid = 0;
						if (isset($_POST['list'])) {
							$listid = (int)$_POST['list'];
						}

						if (isset($_GET['list'])) {
							$listid = (int)$_GET['list'];
						}

						$auto = array('list' => $listid);

						$session->Set('Autoresponders', $auto);

						$this->EditAutoresponderStep1();
					break;

					default:
						$session->Remove('Autoresponders');
						$this->ChooseCreateList();
				}
			break;

			default:
				$this->ChooseList('Autoresponders', 'step2');
			break;
		}
		$this->PrintFooter($popup);
	}

	/**
	* ManageAutoresponders
	* Prints a list of autoresponders for the list we're passing in. Sets up the action dropdown list so we can bulk change or bulk delete autoresponders. Checks permissions to see what the user can do.
	*
	* @param Int $listid ListID to get autoresponders for
	*
	* @see ChooseList
	* @see GetSession
	* @see Session::Remove
	* @see GetPerPage
	* @see GetAPI
	* @see Autoresponder_API::GetAutoresponders
	* @see User_API::HasAccess
	* @see SetupPaging
	*
	* @return Void Doesn't return anything. Prints out the list of autoresponders.
	*/
	function ManageAutoresponders($listid=0)
	{
		$listid = (int)$listid;

		if (!isset($GLOBALS['Message'])) {
			$GLOBALS['Message'] = '';
		}

		if ($listid <= 0) {
			$this->ChooseList('Autoresponders', 'step2');
			return;
		}

		$session = &GetSession();

		$session->Remove('Autoresponders');

		$autodetails = array('list' => $listid);
		$session->Set('Autoresponders', $autodetails);

		$user = $session->Get('UserDetails');
		$perpage = $this->GetPerPage();

		$DisplayPage = (isset($_GET['DisplayPage'])) ? (int)$_GET['DisplayPage'] : 1;
		$start = ($DisplayPage - 1) * $perpage;

		$sortinfo = $this->GetSortDetails();

		$autoresponderapi = $this->GetApi();

		$NumberOfAutoresponders = $autoresponderapi->GetAutoresponders($listid, $sortinfo, true);
		$myautoresponders = $autoresponderapi->GetAutoresponders($listid, $sortinfo, false, $start, $perpage);

		$GLOBALS['SubAction'] = 'SubAction=Step2&list=' . $listid;
		$GLOBALS['Autoresponders_AddButton'] = $this->ParseTemplate('Autoresponder_Create_Button', true, false);

		$GLOBALS['List'] = $listid;

		$this->DisplayCronWarning();

		if ($NumberOfAutoresponders == 0) {
			$GLOBALS['Intro'] = GetLang('AutorespondersManage');
			if ($user->HasAccess('Autoresponders', 'Create')) {
				$GLOBALS['Message'] .= $this->PrintSuccess('NoAutoresponders', GetLang('AutoresponderCreate'));
			} else {
				$GLOBALS['Message'] .= $this->PrintSuccess('NoAutoresponders', GetLang('AutoresponderAssign'));
			}

			$this->ParseTemplate('Autoresponders_Manage_Empty');

			return;
		}

		if ($user->HasAccess('Autoresponders', 'Delete')) {
			$GLOBALS['Option_DeleteAutoresponder'] = '<option value="Delete">' . GetLang('Delete') . '</option>';
		}

		if ($user->HasAccess('Autoresponders', 'Approve')) {
			$GLOBALS['Option_ActivateAutoresponder'] = '<option value="Approve">' . GetLang('ActivateAutoresponders') . '</option>';
			$GLOBALS['Option_ActivateAutoresponder'] .= '<option value="Disapprove">' . GetLang('DeactivateAutoresponders') . '</option>';
		}

		$GLOBALS['PAGE'] = 'Autoresponders&Action=Step2&list=' . $listid;

		$this->SetupPaging($NumberOfAutoresponders, $DisplayPage, $perpage);
		$GLOBALS['FormAction'] = 'Action=ProcessPaging&SubAction=Step2&list=' . $listid;
		$paging = $this->ParseTemplate('Paging', true, false);

		// reset the page for correct links for ordering.
		$GLOBALS['PAGE'] = 'Autoresponders&Action=Step2&list=' . $listid;

		$GLOBALS['list'] = $listid;

		$autoresponder_manage = $this->ParseTemplate('Autoresponders_Manage', true, false);

		$autoresponderdisplay = '';

		foreach ($myautoresponders as $pos => $autoresponderdetails) {
			$autoresponderid = $autoresponderdetails['autoresponderid'];
			$GLOBALS['Name'] = htmlspecialchars($autoresponderdetails['name'], ENT_QUOTES, SENDSTUDIO_CHARSET);
			$GLOBALS['Created'] = $this->PrintDate($autoresponderdetails['createdate']);
			$GLOBALS['Format'] = GetLang('Format_' . $autoresponderapi->GetFormat($autoresponderdetails['format']));
			if ($autoresponderdetails['hoursaftersubscription'] < 1) {
				$GLOBALS['SentWhen'] = GetLang('Immediately');
			} else {
				if ($autoresponderdetails['hoursaftersubscription'] == 1) {
					$GLOBALS['SentWhen'] = GetLang('HoursAfter_One');
				} else {
					$GLOBALS['SentWhen'] = sprintf(GetLang('HoursAfter_Many'), $autoresponderdetails['hoursaftersubscription']);
				}
			}

			$GLOBALS['AutoresponderAction']  = '<a href="index.php?Page=Autoresponders&Action=View&id=' . $autoresponderid . '" target="_blank">' . GetLang('View') . '</a>';

			if ($user->HasAccess('Autoresponders', 'Edit')) {
				$GLOBALS['AutoresponderAction'] .= '&nbsp;&nbsp;<a href="index.php?Page=Autoresponders&Action=Edit&id=' . $autoresponderid . '&list=' . $listid . '">' . GetLang('Edit') . '</a>';
			} else {
				$GLOBALS['AutoresponderAction'] .= $this->DisabledItem('Edit');
			}

			if ($user->HasAccess('Autoresponders', 'Create')) {
				$GLOBALS['AutoresponderAction'] .= '&nbsp;&nbsp;<a href="index.php?Page=Autoresponders&Action=Copy&id=' . $autoresponderid . '&list=' . $listid . '">' . GetLang('Copy') . '</a>';
			} else {
				$GLOBALS['AutoresponderAction'] .= $this->DisabledItem('Copy');
			}

			if ($user->HasAccess('Autoresponders', 'Delete')) {
				$GLOBALS['AutoresponderAction'] .= '&nbsp;&nbsp;<a href="javascript: ConfirmDelete(' . $autoresponderid . ');">' . GetLang('Delete') . '</a>';
			} else {
				$GLOBALS['AutoresponderAction'] .= $this->DisabledItem('Delete');
			}

			if ($autoresponderdetails['active'] > 0) {
				$statusaction = 'deactivate';
				$activeicon = 'tick';
				$activetitle = GetLang('Autoresponder_Title_Disable');
			} else {
				$statusaction = 'activate';
				$activeicon = 'cross';
				$activetitle = GetLang('Autoresponder_Title_Enable');
			}
			$GLOBALS['id'] = $autoresponderid;

			if ($user->HasAccess('Autoresponders', 'Approve')) {
				$GLOBALS['ActiveAction'] = '<a href="index.php?Page=Autoresponders&Action=' . $statusaction . '&id=' . $autoresponderid . '&list=' . $listid . '" title="' . $activetitle . '"><img src="images/' . $activeicon . '.gif" border="0"></a>';
			} else {
				$GLOBALS['ActiveAction'] = '<span title="' . $activetitle . '"><img src="images/' . $activeicon . '.gif" border="0"></span>';
			}
			$autoresponderdisplay .= $this->ParseTemplate('Autoresponders_Manage_Row', true, false);
		}
		$autoresponder_manage = str_replace('%%TPL_Autoresponders_Manage_Row%%', $autoresponderdisplay, $autoresponder_manage);
		$autoresponder_manage = str_replace('%%TPL_Paging%%', $paging, $autoresponder_manage);
		$autoresponder_manage = str_replace('%%TPL_Paging_Bottom%%', $GLOBALS['PagingBottom'], $autoresponder_manage);

		echo $autoresponder_manage;
	}

	/**
	* EditAutoresponderStep1
	* The first step in creating/editing an autoresponder is the name and any custom field type filters that should be set up based on which list the autoresponder is for.
	*
	* @param Int $autoresponderid Autoresponder to load up. If there is one, it will pre-load that content.
	*
	* @see GetAPI
	* @see GetSession
	* @see DisplayCronWarning
	* @see Autoresponder_API::Load
	* @see List_API::Load
	* @see List_API::GetListFormat
	* @see List_API::GetCustomFields
	* @see Search_Display_CustomField
	*
	* @return Void Prints out the form, doesn't return anything.
	*/
	function EditAutoresponderStep1($autoresponderid=0)
	{
		$autoapi = $this->GetApi();
		$session = &GetSession();

		$this->DisplayCronWarning();

		$link_chosen = $newsletter_chosen = false;

		if ($autoresponderid > 0) {
			$autoapi->Load($autoresponderid);
			$GLOBALS['Action'] = 'Edit&SubAction=Step3';
			$GLOBALS['CancelButton'] = GetLang('EditAutoresponderCancelButton');
			$GLOBALS['Heading'] = GetLang('EditAutoresponder');
			$GLOBALS['Intro'] = GetLang('EditAutoresponderIntro');

			$GLOBALS['Name'] = htmlspecialchars($autoapi->Get('name'), ENT_QUOTES, SENDSTUDIO_CHARSET);
			$criteria = $autoapi->Get('searchcriteria');
			$GLOBALS['emailaddress'] = $criteria['emailaddress'];
			$confirmed = $criteria['confirmed'];

			if (isset($criteria['link'])) {
				$link_chosen = $criteria['link'];
			}

			if (isset($criteria['newsletter'])) {
				$newsletter_chosen = $criteria['newsletter'];
			}

			$formatchosen = $criteria['format'];

		} else {
			$GLOBALS['Action'] = 'Create&SubAction=Step3';
			$GLOBALS['CancelButton'] = GetLang('CreateAutoresponderCancelButton');
			$GLOBALS['Heading'] = GetLang('CreateAutoresponder');
			$GLOBALS['Intro'] = GetLang('CreateAutoresponderIntro');

			$formatchosen = 'b';
			$confirmed = '1';
		}

		$sessionauto = $session->Get('Autoresponders');
		$listid = $sessionauto['list'];

		$GLOBALS['List'] = $listid;

		$session->Set('LinksForList', $listid);

		$GLOBALS['clickedlinkdisplay'] = 'none';

		if ($link_chosen !== false) {
			$GLOBALS['clickedlink'] = ' CHECKED';
			$GLOBALS['clickedlinkdisplay'] = "'';";
			$GLOBALS['LinkChange'] = 'onClick="enable_ClickedLink(clickedlink, \'clicklink\', \'linkid\', \'' . GetLang('LoadingMessage') . '\', \'' . $link_chosen . '\')"';
			if ($link_chosen == '-1') {
				$GLOBALS['ClickedLinkOptions'] = '<option value="-1" SELECTED>' . GetLang('FilterAnyLink') . '</option>';
			} else {
				$link_url = $autoapi->FetchLink($link_chosen);
				$GLOBALS['ClickedLinkOptions'] = '<option value="' . $link_chosen . '" SELECTED>' . $link_url . '</option>';
			}
		}

		$session->Set('NewsForList', $listid);

		$GLOBALS['openednewsletterdisplay'] = 'none';

		if ($newsletter_chosen !== false) {
			$GLOBALS['openednewsletter'] = ' CHECKED';
			$GLOBALS['openednewsletterdisplay'] = "'';";
			$GLOBALS['NewsletterChange'] = 'onClick="enable_OpenedNewsletter(openednewsletter, \'openednewsletter\', \'newsletterid\', \'' . GetLang('LoadingMessage') . '\', \'' . $newsletter_chosen . '\')"';
			if ($newsletter_chosen == '-1') {
				$GLOBALS['OpenedNewsletterOptions'] = '<option value="-1" SELECTED>' . GetLang('FilterAnyNewsletter') . '</option>';
			} else {
				$newsletter_api = $this->GetApi('Newsletters');
				$newsletter_api->Load($newsletter_chosen);
				$GLOBALS['OpenedNewsletterOptions'] = '<option value="' . $newsletter_chosen . '" SELECTED>' . $newsletter_api->Get('name') . '</option>';
			}
		}

		$listApi = $this->GetApi('Lists');
		$listApi->Load($listid);

		$format_either  = '<option value="-1">' . GetLang('Either_Format') . '</option>';
		$format_html    = '<option value="h">' . GetLang('Format_HTML') . '</option>';
		$format_text    = '<option value="t">' . GetLang('Format_Text') . '</option>';

		$listformat = $listApi->GetListFormat();

		switch ($listformat) {
			case 'h':
				$format = $format_html;
			break;
			case 't':
				$format = $format_text;
			break;
			default:
				switch ($formatchosen) {
					case 't':
						$format_text = str_replace('"t">', '"t" SELECTED>', $format_text);
					break;
					case 'h':
						$format_html = str_replace('"h">', '"h" SELECTED>', $format_html);
					break;
					case '-1':
						$format_either = str_replace('"-1">', '"-1" SELECTED>', $format_either);
					break;
				}
				$format = $format_either . $format_html . $format_text;
		}
		$GLOBALS['FormatList'] = $format;

		$confirmlist = '';
		$selected = '';
		if ($confirmed == '-1') {
			$selected = ' SELECTED';
		}

		$confirmlist .= '<option value="-1"' . $selected . '>' . GetLang('Either_Confirmed') . '</option>';

		$selected = '';
		if ($confirmed == '1') {
			$selected = ' SELECTED';
		}

		$confirmlist .= '<option value="1"' . $selected . '>' . GetLang('Confirmed') . '</option>';

		$selected = '';
		if ($confirmed == '0') {
			$selected = ' SELECTED';
		}

		$confirmlist .= '<option value="0"' . $selected . '>' . GetLang('Unconfirmed') . '</option>';

		$GLOBALS['ConfirmList'] = $confirmlist;

		$customfields = $listApi->GetCustomFields($listid);

		if (!empty($customfields)) {
			$customfield_display = $this->ParseTemplate('Subscriber_Search_Step2_CustomFields', true, false);
			foreach ($customfields as $pos => $customfield_info) {
				$fieldvalue = '';
				if (isset($criteria['customfields'][$customfield_info['fieldid']])) {
					$fieldvalue = $criteria['customfields'][$customfield_info['fieldid']];
				}
				$customfield_info['FieldValue'] = $fieldvalue;
				$manage_display = $this->Search_Display_CustomField($customfield_info);
				$customfield_display .= $manage_display;
			}
			$GLOBALS['CustomFieldInfo'] = $customfield_display;
		}
		$this->ParseTemplate('Autoresponder_Form_Step2');
	}

	/**
	* EditAutoresponderStep3
	* This step sets whether the autoresponder is multipart, whether to embed images, which character set to use and so on.
	*
	* @param Int $autoresponderid Autoresponder to load up. If there is one, it will pre-load that content.
	*
	* @return Void Prints out the form, doesn't return anything.
	*/
	function EditAutoresponderStep3($autoresponderid=0)
	{
		$autoresponderapi = $this->GetApi();
		$session = &GetSession();

		$user = &GetUser();

		$sessionauto = $session->Get('Autoresponders');
		$listid = $sessionauto['list'];

		$GLOBALS['List'] = $listid;

		$this->DisplayCronWarning();

		if ($autoresponderid > 0) {
			$GLOBALS['Heading'] = GetLang('EditAutoresponder');
			$GLOBALS['Intro'] = GetLang('EditAutoresponderIntro_Step3');
			$GLOBALS['Action'] = 'Edit&SubAction=Step4&id=' . $autoresponderid;
			$GLOBALS['CancelButton'] = GetLang('EditAutoresponderCancelButton');

			$autoresponderapi->Load($autoresponderid);

			$GLOBALS['SendFromName'] = htmlspecialchars($autoresponderapi->Get('sendfromname'), ENT_QUOTES, SENDSTUDIO_CHARSET);
			$GLOBALS['SendFromEmail'] = $autoresponderapi->Get('sendfromemail');
			$GLOBALS['BounceEmail'] = $autoresponderapi->Get('bounceemail');
			$GLOBALS['ReplyToEmail'] = $autoresponderapi->Get('replytoemail');

			$charset = $autoresponderapi->Get('charset');

			$formatoption_chosen = $autoresponderapi->Get('format');
			$GLOBALS['HoursAfterSubscription'] = $autoresponderapi->Get('hoursaftersubscription');

			if ($autoresponderapi->Get('multipart')) {
				$GLOBALS['multipart'] = 'CHECKED';
			}

			if ($autoresponderapi->Get('embedimages')) {
				$GLOBALS['embedimages'] = 'CHECKED';
			}

			if ($autoresponderapi->Get('tracklinks')) {
				$GLOBALS['tracklinks'] = 'CHECKED';
			}

			if ($autoresponderapi->Get('trackopens')) {
				$GLOBALS['trackopens'] = 'CHECKED';
			}

			//$GLOBALS['DisplayTemplateList'] = 'none';
			$templateselects = $this->GetTemplateList();
			$GLOBALS['TemplateList'] = $templateselects;

			$customfield_to_firstname = $autoresponderapi->Get('to_firstname');
			$customfield_to_lastname = $autoresponderapi->Get('to_lastname');

		} else {
			$GLOBALS['Heading'] = GetLang('CreateAutoresponder');
			$GLOBALS['Intro'] = GetLang('CreateAutoresponderIntro_Step3');
			$GLOBALS['Action'] = 'Create&SubAction=Step4';
			$GLOBALS['CancelButton'] = GetLang('CreateAutoresponderCancelButton');

			$GLOBALS['HoursAfterSubscription'] = 0;

			$charset = false;

			$formatoption_chosen = 'b';

			$GLOBALS['multipart'] = 'CHECKED';
			$GLOBALS['embedimages'] = 'CHECKED';
			$GLOBALS['tracklinks'] = 'CHECKED';
			$GLOBALS['trackopens'] = 'CHECKED';

			$templateselects = $this->GetTemplateList();
			$GLOBALS['TemplateList'] = $templateselects;

			$customfield_to_firstname = $customfield_to_lastname = 0;

		}

		$GLOBALS['Charset'] = SENDSTUDIO_CHARSET;

		$list_api = $this->GetApi('Lists');
		$list_api->Load($listid);

		$customfields = $list_api->GetCustomFields($listid);
		if (empty($customfields)) {
			$GLOBALS['DisplayNameOptions'] = 'none';
		} else {
			$GLOBALS['FirstNameOptions'] = '<option value="0">'.GetLang('SelectNameOption').'</option>';
			$GLOBALS['LastNameOptions'] = '<option value="0">'.GetLang('SelectNameOption').'</option>';
			foreach ($customfields as $p => $details) {
				$selected = '';
				if ($details['fieldid'] == $customfield_to_firstname) {
					$selected = " SELECTED";
				}
				$GLOBALS['FirstNameOptions'] .= "<option value='" . $details['fieldid'] . "'" . $selected . ">" . htmlspecialchars($details['name'], ENT_QUOTES, SENDSTUDIO_CHARSET) . "</option>";

				$selected = '';
				if ($details['fieldid'] == $customfield_to_lastname) {
					$selected = " SELECTED";
				}
				$GLOBALS['LastNameOptions'] .= "<option value='" . $details['fieldid'] . "'" . $selected . ">" . htmlspecialchars($details['name'], ENT_QUOTES, SENDSTUDIO_CHARSET) . "</option>";

			}
		}

		if ($autoresponderid <= 0) {
			$GLOBALS['SendFromName'] = $list_api->ownername;
			$GLOBALS['SendFromEmail'] = $list_api->owneremail;
			$GLOBALS['BounceEmail'] = $list_api->bounceemail;
			$GLOBALS['ReplyToEmail'] = $list_api->replytoemail;
		}

		$GLOBALS['ShowBounceInfo'] = 'none';

		if ($user->HasAccess('Lists', 'BounceSettings')) {
			$GLOBALS['ShowBounceInfo'] = '';
		}

		$listformat = $list_api->GetListFormat();

		switch ($listformat) {
			case 't':
				$format  = '<option value="t" SELECTED>' . GetLang('Format_Text') . '</option>';
			break;
			case 'h':
				$format  = '<option value="h" SELECTED>' . GetLang('Format_HTML') . '</option>';
			break;
			case 'b':
				$format  = '<option value="b"' . (($formatoption_chosen == 'b') ? ' SELECTED' : '' ) . '>' . GetLang('Format_TextAndHTML') . '</option>';
				$format .= '<option value="h"' . (($formatoption_chosen == 'h') ? ' SELECTED' : '' ) . '>' . GetLang('Format_HTML') . '</option>';
				$format .= '<option value="t"' . (($formatoption_chosen == 't') ? ' SELECTED' : '' ) . '>' . GetLang('Format_Text') . '</option>';
			break;
		}

		$GLOBALS['FormatList'] = $format;

		$this->ParseTemplate('Autoresponder_Form_Step3');
	}

	/**
	* EditAutoresponderStep4
	* Loads up step 4 of editing an autoresponder which is editing the actual content.
	* If you pass in an autoresponderid, it will load it up and set the appropriate language variables.
	*
	* @param Int $autoresponderid AutoresponderID to edit.
	*
	* @return Void Prints out step 4, doesn't return anything.
	*/
	function EditAutoresponderStep4($autoresponderid=0)
	{
		$autoapi = $this->GetApi();
		$autorespondercontents = array('text' => '', 'html' => '');

		$this->DisplayCronWarning();

		$session = &GetSession();

		$user = &GetUser();
		$GLOBALS['FromPreviewEmail'] = $user->Get('emailaddress');

		if ($autoresponderid > 0) {
			$GLOBALS['SaveAction'] = 'Edit&SubAction=Save&id=' . $autoresponderid;
			$GLOBALS['Heading'] = GetLang('EditAutoresponder');
			$GLOBALS['Intro'] = GetLang('EditAutoresponderIntro_Step4');
			$GLOBALS['Action'] = 'Edit&SubAction=Complete&id=' . $autoresponderid;
			$GLOBALS['CancelButton'] = GetLang('EditAutoresponderCancelButton');

			$autoapi->Load($autoresponderid);
			$autorespondercontents['text'] = $autoapi->GetBody('text');
			$autorespondercontents['html'] = $autoapi->GetBody('html');

			$GLOBALS['Subject'] = htmlspecialchars($autoapi->subject, ENT_QUOTES, SENDSTUDIO_CHARSET);

			$attachmentsarea = strtolower(get_class($this));
			$GLOBALS['AttachmentsList'] = $this->GetAttachments($attachmentsarea, $autoresponderid);

		} else {
			$GLOBALS['SaveAction'] = 'Create&SubAction=Save&id=' . $autoresponderid;
			$GLOBALS['Heading'] = GetLang('CreateAutoresponder');
			$GLOBALS['Intro'] = GetLang('CreateAutoresponderIntro_Step4');
			$GLOBALS['Action'] = 'Create&SubAction=Complete';
			$GLOBALS['CancelButton'] = GetLang('CreateAutoresponderCancelButton');
		}
		$GLOBALS['PreviewID'] = $autoresponderid;

		// we don't really need to get/set the stuff here.. we could use references.
		// if we do though, it segfaults! so we get and then set the contents.
		$session_autoresponder = $session->Get('Autoresponders');

		$GLOBALS['List'] = $session_autoresponder['list'];

		if (isset($session_autoresponder['TemplateID'])) {
			$templateApi = $this->GetApi('Templates');
			if (is_numeric($session_autoresponder['TemplateID'])) {
				$templateApi->Load($session_autoresponder['TemplateID']);
				$autorespondercontents['text'] = $templateApi->textbody;
				$autorespondercontents['html'] = $templateApi->htmlbody;
			} else {
				$autorespondercontents['html'] = $templateApi->ReadServerTemplate($session_autoresponder['TemplateID']);
			}
			unset($session_autoresponder['TemplateID']);
		}

		$session_autoresponder['id'] = (int)$autoresponderid;

		$session_autoresponder['contents'] = $autorespondercontents;

		// we use the lowercase variable when we save, but the editor expects the uppercased version.
		$session_autoresponder['Format'] = $session_autoresponder['format'];

		$session->Set('Autoresponders', $session_autoresponder);
		$editor = $this->FetchEditor();
		$GLOBALS['Editor'] = $editor;

		unset($session_autoresponder['Format']);

		$this->ParseTemplate('Autoresponder_Form_Step4');
	}

	/**
	* ChooseCreateList
	* Prints a list of options to choose from to create an autoresponder.
	* If you only have one list or only have access to one list, you are taken directly to it.
	*
	* @see GetSession
	* @see GetUser
	* @see User_API::GetLists
	* @see GetAPI
	*
	* @return Void Returns nothing, either prints the list or takes you to your only list.
	*/
	function ChooseCreateList()
	{
		$session = &GetSession();
		$user = &GetUser();
		$lists = $user->GetLists();

		$listids = array_keys($lists);

		if (sizeof($listids) < 1) {
			$GLOBALS['Intro'] = GetLang('CreateAutoresponder');
			$GLOBALS['Lists_AddButton'] = '';

			if ($user->CanCreateList() === true) {
				$GLOBALS['Message'] = $this->PrintSuccess('NoLists', GetLang('ListCreate'));
				$GLOBALS['Lists_AddButton'] = $this->ParseTemplate('List_Create_Button', true, false);
			} else {
				$GLOBALS['Message'] = $this->PrintSuccess('NoLists', GetLang('ListAssign'));
			}
			$this->ParseTemplate('Lists_Manage_Empty');
			return;
		}

		if (sizeof($listids) == 1) {
			$listid = current($listids);
			$location = 'index.php?Page=Autoresponders&Action=Create&SubAction=Step2&list=' . $listid;
			?>
			<script language="javascript">
				window.location = '<?php echo $location; ?>';
			</script>
			<?php
			exit();
		}

		$this->DisplayCronWarning();

		$selectlist = '';
		foreach ($lists as $listid => $listdetails) {
			if ($listdetails['subscribecount'] == 1) {
				$subscriber_count = GetLang('Subscriber_Count_One');
			} else {
				$subscriber_count = sprintf(GetLang('Subscriber_Count_Many'), $this->FormatNumber($listdetails['subscribecount']));
			}
			$selectlist .= '<option value="' . $listid . '">' . $listdetails['name'] . $subscriber_count . '</option>';
		}
		$GLOBALS['SelectList'] = $selectlist;

		$this->ParseTemplate('Autoresponders_Create_Step1');
	}

	/**
	* ActionAutoresponders
	* This actions the autoresponders based on the id's passed in and the action you are passing in.
	*
	* @param Array $autoresponderids An array of autoresponderid's to "action".
	* @param String $action The action to perform. This function only accepts approve/disapprove as appropriate actions. Any other type of action will throw an error message.
	*
	* @see GetAPI
	* @see Autoresponders_API::Set
	* @see Autoresponders_API::Load
	* @see Autoresponders_API::Save
	*
	* @return Void Doesn't return anything. Prints out a message about what happened and displays the list of autoresponders again.
	*/
	function ActionAutoresponders($autoresponderids=array(), $action='')
	{
		$listid = (isset($_GET['list'])) ? (int)$_GET['list'] : 0;

		$autoresponderapi = $this->GetApi();

		$autoresponderids = $autoresponderapi->CheckIntVars($autoresponderids);

		if (!is_array($autoresponderids)) {
			$autoresponderids = array($autoresponderids);
		}

		if (empty($autoresponderids)) {
			$GLOBALS['Error'] = GetLang('NoAutorespondersToAction');
			$GLOBALS['Message'] = $this->ParseTemplate('ErrorMsg', true, false);

			$this->ManageAutoresponders($listid);
			return;
		}

		$action = strtolower($action);

		if (!in_array($action, array('approve', 'disapprove'))) {
			$GLOBALS['Error'] = GetLang('InvalidAutoresponderAction');
			$GLOBALS['Message'] = $this->ParseTemplate('ErrorMsg', true, false);
			$this->ManageAutoresponders($listid);
			return;
		}

		$user = &GetUser();

		$update_ok = $update_fail = 0;
		foreach ($autoresponderids as $p => $autoid) {
			$autoresponderapi->Load($autoid);

			switch ($action) {
				case 'approve':
					$langvar = 'Approved';
					$autoresponderapi->Set('active', $user->Get('userid'));
				break;
				case 'disapprove':
					$langvar = 'Disapproved';
					$autoresponderapi->Set('active', 0);
				break;
			}
			$status = $autoresponderapi->Save();
			if ($status) {
				$update_ok++;
			} else {
				$update_fail++;
			}
		}

		$msg = '';

		if ($update_fail > 0) {
			if ($update_fail == 1) {
				$GLOBALS['Error'] = GetLang('Autoresponder_Not' . $langvar);
			} else {
				$GLOBALS['Error'] = sprintf(GetLang('Autoresponders_Not' . $langvar), $this->FormatNumber($update_fail));
			}
			$msg .= $this->ParseTemplate('ErrorMsg', true, false);
		}

		if ($update_ok > 0) {
			if ($update_ok == 1) {
				$msg .= $this->PrintSuccess('Autoresponder_' . $langvar);
			} else {
				$msg .= $this->PrintSuccess('Autoresponders_' . $langvar, $this->FormatNumber($update_ok));
			}
		}
		$GLOBALS['Message'] = $msg;

		$this->ManageAutoresponders($listid);
	}

	/**
	* DeleteAutoresponders
	* This goes through the autoresponders and deletes the ones that have been passed in.
	* The API is used to delete and clean up the autoresponders as they need to so we don't need to worry about it.
	*
	* @param Array $autoresponderids An array of autoresponder id's to delete.
	*
	* @see GetAPI
	* @see Autoresponders_API::Delete
	*
	* @return Void Doesn't return anything, prints out the appropriate messages.
	*/
	function DeleteAutoresponders($autoresponderids=array())
	{
		$listid = (isset($_GET['list'])) ? (int)$_GET['list'] : 0;

		$api = $this->GetApi();
		$user = &GetUser();

		if (!is_array($autoresponderids)) {
			$autoresponderids = array($autoresponderids);
		}

		$autoresponderids = $api->CheckIntVars($autoresponderids);

		if (empty($autoresponderids)) {
			$GLOBALS['Error'] = GetLang('NoAutorespondersToDelete');
			$GLOBALS['Message'] = $this->ParseTemplate('ErrorMsg', true, false);
			$this->ManageAutoresponders($listid);
			return;
		}

		$delete_ok = $delete_fail = 0;
		foreach ($autoresponderids as $p => $autoid) {
			$status = $api->Delete($autoid, $user->Get('userid'));
			if ($status) {
				$delete_ok++;
			} else {
				$delete_fail++;
			}
		}

		$msg = '';

		if ($delete_fail > 0) {
			if ($delete_fail == 1) {
				$GLOBALS['Error'] = GetLang('Autoresponder_NotDeleted');
			} else {
				$GLOBALS['Error'] = sprintf(GetLang('Autoresponders_NotDeleted'), $this->FormatNumber($delete_fail));
			}
			$msg .= $this->ParseTemplate('ErrorMsg', true, false);
		}

		if ($delete_ok > 0) {
			if ($delete_ok == 1) {
				$msg .= $this->PrintSuccess('Autoresponder_Deleted');
			} else {
				$msg .= $this->PrintSuccess('Autoresponders_Deleted', $this->FormatNumber($delete_ok));
			}
		}
		$GLOBALS['Message'] = $msg;

		$this->ManageAutoresponders($listid);
	}
}
?>
