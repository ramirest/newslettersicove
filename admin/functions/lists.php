<?php
/**
* This file handles list processing. This only covers maintaining (creating, editing, deleting etc).
*
* @version     $Id: lists.php,v 1.49 2007/05/30 02:19:22 chris Exp $

*
* @package SendStudio
* @subpackage SendStudio_Functions
*/

/**
* Include the base sendstudio functions.
*/
require(dirname(__FILE__) . '/sendstudio_functions.php');

/**
* This class handles list processing. This only covers maintaining (creating, editing, deleting etc). The main work is done by the API.
*
* @package SendStudio
* @subpackage SendStudio_Functions
*/
class Lists extends SendStudio_Functions
{

	/**
	* Set the default direction to be ascending (alphabetical order) rather than descending which is normally the default.
	*
	* @see GetSortDetails
	* @var String
	*/
	var $_DefaultDirection = 'up';

	/**
	* Constructor
	* Loads the language file.
	*
	* @see LoadLanguageFile
	*
	* @return Void Doesn't return anything, just loads up the language file.
	*/
	function Lists()
	{
		$this->LoadLanguageFile();
	}

	/**
	* Process
	* Does all of the work.
	* This handles processing of the functions. This includes adding, deleting, editing etc.
	*
	* @see GetUser
	* @see User_API::HasAccess
	* @see GetApi
	* @see List_API::DeleteAllSubscribers
	* @see List_API::ChangeSubscriberFormat
	* @see ManageLists
	* @see CreateList
	* @see EditList
	*
	* @return Void Handles processing, prints out what it needs to. Doesn't return anything.
	*/
	function Process()
	{
		$GLOBALS['Message'] = '';

		$this->PrintHeader();
		$session = &GetSession();
		$user = &GetUser();
		$action = (isset($_GET['Action'])) ? strtolower(urldecode($_GET['Action'])) : null;
		$secondary_actions = array('update', 'addlist', 'change', 'processpaging');
		if (in_array($action, $secondary_actions)) {
			$access = $user->HasAccess('lists');
		} else {
			$access = $user->HasAccess('lists', $action);
		}

		if (!$access) {
			$this->DenyAccess();
		}

		if ($action == 'processpaging') {
			$perpage = (int)$_GET['PerPageDisplay'];
			$display_settings = array('NumberToShow' => $perpage);
			$user->SetSettings('DisplaySettings', $display_settings);
			$action = '';
		}

		switch ($action) {
			case 'copy':
				if ($user->CanCreateList() !== true) {
					$GLOBALS['Error'] = GetLang('TooManyLists');
					$GLOBALS['Message'] = $this->ParseTemplate('ErrorMsg', true, false);
					$this->ManageLists();
					break;
				}

				$id = (isset($_GET['id'])) ? (int)$_GET['id'] : 0;
				$api = $this->GetApi();
				list($result, $newid) = $api->Copy($id);

				if (!$result) {
					$GLOBALS['Error'] = GetLang('ListCopyFail');
					$msg = $this->ParseTemplate('ErrorMsg', true, false);
				} else {
					$msg = $this->PrintSuccess('ListCopySuccess');
					$user->GrantListAccess($newid);
					$user->SavePermissions();

					$session->Set('UserDetails', $user);

					$session->Remove('UserLists');
				}

				$GLOBALS['Message'] = $msg;
				$this->ManageLists();
			break;

			case 'edit':
				$listid = (isset($_GET['id'])) ? (int)$_GET['id'] : 0;
				$this->EditList($listid);
			break;

			case 'update':
				$list = $this->GetApi();

				$listid = (isset($_GET['id'])) ? (int)$_GET['id'] : 0;
				$list->Load($listid);

				$checkfields = array('Name', 'OwnerName', 'OwnerEmail', 'BounceEmail', 'ReplyToEmail');
				$valid = true; $errors = array();
				foreach ($checkfields as $p => $field) {
					if ($_POST[$field] == '') {
						$valid = false;
						$errors[] = GetLang('List'.$field.'IsNotValid');
						break;
					} else {
						$value = $_POST[$field];
						$list->Set(strtolower($field), $value);
					}
				}
				if (!$valid) {
					$GLOBALS['Error'] = GetLang('UnableToUpdateList') . '<br/>- ' . implode('<br/>- ',$errors);
					$GLOBALS['Message'] = $this->ParseTemplate('ErrorMsg', true, false);
					$this->EditList($listid);
					break;
				}

				$list->notifyowner = (isset($_POST['NotifyOwner'])) ? 1 : 0;
				$list->imapaccount = (isset($_POST['IMAPAccount'])) ? 1 : 0;
				$list->bounceserver = $_POST['BounceServer'];
				$list->bounceusername = $_POST['BounceU'];
				$list->bouncepassword = $_POST['BounceP'];

				$list->extramailsettings = '';
				if (isset($_POST['extramail'])) {
					$list->extramailsettings = $_POST['ExtraMailSettings'];
				}

				$saveresult = $list->Save();
				if (!$saveresult) {
					$GLOBALS['Error'] = GetLang('UnableToUpdateList');
					$msg = $this->ParseTemplate('ErrorMsg', true, false);
				} else {
					$msg = $this->PrintSuccess('ListUpdated');
				}
				$GLOBALS['Message'] = $msg;
				$this->ManageLists();
			break;

			case 'create':
				$this->CreateList();
			break;

			case 'addlist':
				$list = $this->GetApi();

				if ($user->CanCreateList() !== true) {
					$GLOBALS['Error'] = GetLang('TooManyLists');
					$GLOBALS['Message'] = $this->ParseTemplate('ErrorMsg', true, false);
					$this->ManageLists();
					break;
				}

				$checkfields = array('Name', 'OwnerName', 'OwnerEmail', 'BounceEmail', 'ReplyToEmail');
				$valid = true; $errors = array();
				foreach ($checkfields as $p => $field) {
					if ($_POST[$field] == '') {
						$valid = false;
						$errors[] = GetLang('List'.$field.'IsNotValid');
						break;
					} else {
						$value = $_POST[$field];
						$list->Set(strtolower($field), $value);
					}
				}
				if (!$valid) {
					$GLOBALS['Error'] = GetLang('UnableToCreateList') . '<br/>- ' . implode('<br/>- ',$errors);
					$GLOBALS['Message'] = $this->ParseTemplate('ErrorMsg', true, false);
					$this->CreateList();
					break;
				}

				$list->notifyowner = (isset($_POST['NotifyOwner'])) ? 1 : 0;
				$list->imapaccount = (isset($_POST['IMAPAccount'])) ? 1 : 0;
				$list->bounceserver = $_POST['BounceServer'];
				$list->bounceusername = $_POST['BounceU'];
				$list->bouncepassword = $_POST['BounceP'];

				$list->extramailsettings = '';
				if (isset($_POST['extramail'])) {
					$list->extramailsettings = $_POST['ExtraMailSettings'];
				}

				$list->ownerid = $user->userid;

				$create = $list->Create();

				$user->GrantListAccess($create);
				$user->SavePermissions();

				$session->Remove('UserLists');

				$session->Set('UserDetails', $user);

				if (!$create) {
					$GLOBALS['Error'] = GetLang('UnableToCreateList');
					$GLOBALS['Message'] = $this->ParseTemplate('ErrorMsg', true, false);
					$this->CreateList();
				} else {
					$GLOBALS['Message'] = $this->PrintSuccess('ListCreated');
					$this->ManageLists();
				}
			break;

			case 'change':
				$subaction = strtolower($_POST['ChangeType']);
				$listApi  = $this->GetApi();

				$success_format = 0; $failure_format = 0;
				$success_status = 0; $failure_status = 0;
				$success_confirmed = 0; $failure_confirmed = 0;

				if ($subaction == 'mergelists') {
					if ($user->CanCreateList() !== true) {
						$GLOBALS['Error'] = GetLang('TooManyLists');
						$GLOBALS['Message'] = $this->ParseTemplate('ErrorMsg', true, false);
						$this->ManageLists();
						break;
					}

					if (sizeof($_POST['Lists']) < 2) {
						$GLOBALS['Error'] = GetLang('UnableToMergeLists');
						$GLOBALS['Message'] = $this->ParseTemplate('ErrorMsg', true, false);
						$this->ManageLists();
						break;
					}

					$message = '';

					$userdetails = array();
					$userdetails['userid'] = $user->userid;
					$userdetails['name'] = $user->fullname;
					$userdetails['emailaddress'] = $user->emailaddress;

					list($status, $msg, $results) = $listApi->MergeLists($_POST['Lists'], $userdetails);

					$success_merged = $results['Success'];
					$failure_merged = $results['Failure'];
					$duplicates_success_removed = $results['DuplicatesSuccess'];
					$duplicates_failure_removed = $results['DuplicatesFailure'];

					if ($success_merged > 0) {
						$message .= $this->PrintSuccess('MergeSuccessful', $this->FormatNumber($success_merged));
					}

					if ($failure_merged > 0) {
						$GLOBALS['Error'] = sprintf(GetLang('MergeUnsuccessful'), $this->FormatNumber($success_merged));
						$message .= $this->ParseTemplate('ErrorMsg', true, false);
					}

					if ($duplicates_success_removed > 0) {
						$message .= $this->PrintSuccess('MergeDuplicatesRemoved_Success', $this->FormatNumber($duplicates_success_removed));
					}

					if ($duplicates_failure_removed > 0) {
						$GLOBALS['Error'] = sprintf(GetLang('MergeDuplicatesRemoved_Fail'), $this->FormatNumber($duplicates_failure_removed));
						$message .= $this->ParseTemplate('ErrorMsg', true, false);
					}

					$user->GrantListAccess($status);
					$user->SavePermissions();

					$session->Set('UserDetails', $user);

					$GLOBALS['Message'] = $message;
					if (!$status) {
						$this->ManageLists();
					}
					if ($status) {
						$this->EditList($status);
					}
					break;
				}

				$lists_deleted_success = $lists_deleted_failure = 0;
				$subscribers_deleted_success = $subscribers_deleted_failure = 0;

				foreach ($_POST['Lists'] as $pos => $list) {
					$listApi->Load($list);
					switch ($subaction) {

						case 'delete':
							$status = $listApi->Delete($list, $user->Get('userid'));
							if ($status) {
								$lists_deleted_success++;
							} else {
								$lists_deleted_failure++;
							}
							$user->RevokeListAccess($list);
							$user->SavePermissions();
						break;

						case 'deleteallsubscribers':
							$status = $listApi->DeleteAllSubscribers($list);
							if ($status) {
								$subscribers_deleted_success++;
							} else {
								$subscribers_deleted_failure++;
							}
						break;

						case 'changeformat_text':
							$newformat = 'Text';
							list($status, $msg) = $listApi->ChangeSubscriberFormat($newformat, $list);
							if ($status) {
								$success_format++;
							} else {
								$failure_format++;
							}
						break;
						case 'changeformat_html':
							$newformat = 'HTML';
							list($status, $msg) = $listApi->ChangeSubscriberFormat($newformat, $list);
							if ($status) {
								$success_format++;
							} else {
								$failure_format++;
							}
						break;

						case 'changestatus_confirm':
							$newstatus = 'Confirmed';
							list($status, $msg) = $listApi->ChangeSubscriberConfirm('confirm', $list);
							if ($status) {
								$success_confirmed++;
							} else {
								$failure_confirmed++;
							}
						break;
						case 'changestatus_unconfirm':
							$newstatus = 'Unconfirmed';
							list($status, $msg) = $listApi->ChangeSubscriberConfirm('unconfirm', $list);
							if ($status) {
								$success_confirmed++;
							} else {
								$failure_confirmed++;
							}
						break;
					}
				}

				// need to do this for list permissions.
				$session->Set('UserDetails', $user);

				$message = '';

				if ($lists_deleted_success > 0) {
					if ($lists_deleted_success == 1) {
						$message .= $this->PrintSuccess('ListDeleteSuccess');
					} else {
						$message .= $this->PrintSuccess('ListsDeleteSuccess', $this->FormatNumber($lists_deleted_success));
					}

					if ($lists_deleted_failure > 0) {
						$message .= '<br/>';
					}
				}

				if ($lists_deleted_failure > 0) {
					if ($lists_deleted_failure == 1) {
						$GLOBALS['Error'] = GetLang('ListDeleteFail');
					} else {
						$GLOBALS['Error'] = GetLang('ListsDeleteFail');
					}
					$message .= $this->ParseTemplate('ErrorMsg', true, false);
				}

				if ($subscribers_deleted_success > 0) {
					if ($subscribers_deleted_success == 1) {
						$message .= $this->PrintSuccess('ListDeleteAllSubscribersSuccess');
					} else {
						$message .= $this->PrintSuccess('ListsDeleteAllSubscribersSuccess', $this->FormatNumber($subscribers_deleted_success));
					}

					if ($subscribers_deleted_failure > 0) {
						$message .= '<br/>';
					}
				}

				if ($subscribers_deleted_failure > 0) {
					if ($subscribers_deleted_failure == 1) {
						$GLOBALS['Error'] = GetLang('ListDeleteAllSubscribersFailure');
					} else {
						$GLOBALS['Error'] = GetLang('ListsDeleteAllSubscribersFailure');
					}
					$message .= $this->ParseTemplate('ErrorMsg', true, false);
				}

				if ($success_format > 0) {
					$message .= $this->PrintSuccess('AllListSubscribersChangedFormat', GetLang('Format_' . $newformat));

					if ($failure_format > 0) {
						$message .= '<br/>';
					}
				}
				if ($failure_format > 0) {
					$GLOBALS['Error'] = sprintf(GetLang('AllListSubscribersNotChangedFormat'), GetLang('Format_' . $newformat));
					$message .= $this->ParseTemplate('ErrorMsg', true, false);
				}

				if ($success_status > 0) {
					$message .= $this->PrintSuccess('AllListSubscribersChangedStatus', GetLang('Status_' . $newstatus));

					if ($failure_status > 0) {
						$message .= '<br/>';
					}
				}
				if ($failure_status > 0) {
					$GLOBALS['Error'] = sprintf(GetLang('AllListSubscribersNotChangedStatus'), GetLang('Status_' . $newstatus));
					$message .= $this->ParseTemplate('ErrorMsg', true, false);
				}

				if ($success_confirmed > 0) {
					$message .= $this->PrintSuccess('AllListSubscribersChangedConfirm', GetLang('Status_' . $newstatus));

					if ($failure_confirmed > 0) {
						$message .= '<br/>';
					}
				}
				if ($failure_confirmed > 0) {
					$GLOBALS['Error'] = sprintf(GetLang('AllListSubscribersNotChangedConfirm'), GetLang('Status_' . $newstatus));
					$message .= $this->ParseTemplate('ErrorMsg', true, false);
				}
				$GLOBALS['Message'] = $message;
				$this->ManageLists();
			break;

			case 'delete':
				$listApi = $this->GetApi('Lists');
				$list = (int)$_GET['id'];
				$lists_deleted_success = $lists_deleted_failure = 0;
				$status = $listApi->Delete($list, $user->Get('userid'));
				if ($status) {
					$lists_deleted_success++;
				} else {
					$lists_deleted_failure++;
				}
				$user->RevokeListAccess($list);
				$user->SavePermissions();
				$session->Set('UserDetails', $user);

				$message = '';
				if ($lists_deleted_success > 0) {
					if ($lists_deleted_success == 1) {
						$message .= $this->PrintSuccess('ListDeleteSuccess');
					} else {
						$message .= $this->PrintSuccess('ListsDeleteSuccess');
					}

					if ($lists_deleted_failure > 0) {
						$message .= '<br/>';
					}
				}

				if ($lists_deleted_failure > 0) {
					if ($lists_deleted_failure == 1) {
						$GLOBALS['Error'] = GetLang('ListDeleteFail');
					} else {
						$GLOBALS['Error'] = GetLang('ListsDeleteFail');
					}
					$message .= $this->ParseTemplate('ErrorMsg', true, false);
				}
				$GLOBALS['Message'] = $message;
				$this->ManageLists();
			break;

			default:
				$this->ManageLists();
			break;
		}
		$this->PrintFooter();
	}

	/**
	* ManageLists
	* Prints out the lists for management. This includes deleting subscribers, changing subscriber formats etc.
	*
	* @see GetSession
	* @see Session::Get
	* @see GetPerPage
	* @see GetCurrentPage
	* @see GetSortDetails
	* @see GetApi
	* @see User_API::ListAdmin
	* @see List_API::GetLists
	* @see User_API::CanCreateList
	* @see SetupPaging
	* @see PrintDate
	*
	* @return Void Doesn't return anything, prints out the list(s) so they can be managed.
	*/
	function ManageLists()
	{
		$session = &GetSession();
		$user = $session->Get('UserDetails');
		$perpage = $this->GetPerPage();

		$DisplayPage = $this->GetCurrentPage();
		$start = ($DisplayPage - 1) * $perpage;

		$sortinfo = $this->GetSortDetails();

		$all_lists = $user->GetLists();
		$check_lists = array_keys($all_lists);

		$listapi = $this->GetApi('Lists');

		$NumberOfLists = count($check_lists);

		// if we're a list admin, no point checking the lists - we have access to everything.
		if ($user->ListAdmin()) {
			$check_lists = null;
		}

		$mylists = $listapi->GetLists($check_lists, $sortinfo, false, $start, $perpage);

		$GLOBALS['Lists_AddButton'] = '';

		if ($user->CanCreateList() === true) {
			$GLOBALS['Lists_AddButton'] = $this->ParseTemplate('List_Create_Button', true, false);
			$GLOBALS['Lists_Heading'] = GetLang('Help_ListsManage_HasAccess');
		}

		if (!isset($GLOBALS['Message'])) {
			$GLOBALS['Message'] = '';
		}

		if ($NumberOfLists == 0) {
			$GLOBALS['Intro'] = GetLang('ListsManage');
			if ($user->CanCreateList() === true) {
				$GLOBALS['Message'] .= $this->PrintSuccess('NoLists', GetLang('ListCreate'));
			} else {
				$GLOBALS['Message'] .= $this->PrintSuccess('NoLists', GetLang('ListAssign'));
			}
			$this->ParseTemplate('Lists_Manage_Empty');
			return;
		}

		$this->SetupPaging($NumberOfLists, $DisplayPage, $perpage);
		$GLOBALS['FormAction'] = 'Action=ProcessPaging';
		$paging = $this->ParseTemplate('Paging', true, false);

		if ($user->HasAccess('Lists', 'Delete')) {
			$GLOBALS['Option_DeleteList'] = '<option value="Delete">' . GetLang('Delete') . '</option>';
		}

		if ($user->HasAccess('Subscribers', 'Delete')) {
			$GLOBALS['Option_DeleteSubscribers'] = '<option value="DeleteAllSubscribers">' . GetLang('DeleteAllSubscribers') . '</option>';
		}

		$template = $this->ParseTemplate('Lists_Manage', true, false);

		$listdetails = '';

		foreach ($mylists as $pos => $listinfo) {
			$GLOBALS['Name'] = htmlspecialchars($listinfo['name'], ENT_QUOTES, SENDSTUDIO_CHARSET);
			$GLOBALS['Created'] = $this->PrintDate($listinfo['createdate']);

			$GLOBALS['SubscriberCount'] = $this->FormatNumber($listinfo['subscribecount']);

			$GLOBALS['ListAction'] = '';

			if ($user->HasAccess('Lists', 'Edit', $listinfo['listid'])) {
				$GLOBALS['EditListID'] = $listinfo['listid'];
				$GLOBALS['ListAction'] .= $this->ParseTemplate('Lists_Manage_EditLink', true, false);
			} else {
				$GLOBALS['ListAction'] .= $this->DisabledItem('Edit');
			}

			# this checks whether the user is an admin or list admin, so we don't need to.
			$create_list = $user->CanCreateList();
			if ($create_list === true) {
				$GLOBALS['CopyListID'] = $listinfo['listid'];
				$GLOBALS['ListAction'] .= $this->ParseTemplate('Lists_Manage_Copy', true, false);
			} else {
				if ($create_list === false) {
					$itemtitle = 'ListCopyDisabled';
				} else {
					$itemtitle = 'ListCopyDisabled_TooMany';
				}
				$GLOBALS['ListAction'] .= $this->DisabledItem('Copy', $itemtitle);
			}

			if ($user->HasAccess('Lists', 'Delete', $listinfo['listid'])) {
				$GLOBALS['DeleteListID'] = $listinfo['listid'];
				$GLOBALS['ListAction'] .= $this->ParseTemplate('Lists_Manage_DeleteLink', true, false);
			} else {
				$GLOBALS['ListAction'] .= $this->DisabledItem('Delete');
			}

			$GLOBALS['List'] = $listinfo['listid'];

			$listdetails .= $this->ParseTemplate('Lists_Manage_Row', true, false);
		}
		$template = str_replace('%%TPL_Lists_Manage_Row%%', $listdetails, $template);
		$template = str_replace('%%TPL_Paging%%', $paging, $template);
		$template = str_replace('%%TPL_Paging_Bottom%%', $GLOBALS['PagingBottom'], $template);
		echo $template;
	}

	/**
	* EditList
	* Loads the list and displays it for editing.
	*
	* @see GetApi
	* @see List_API::Load
	* @see List_API::GetAllFormats
	*
	* @return Void Doesn't return anything, prints out the form for the list to be edited.
	*/
	function EditList($listid=0)
	{
		if ($listid <= 0) {
			$this->DenyAccess();
			return;
		}

		$list = $this->GetApi();
		if (!$list->Load($listid)) {
			$this->DenyAccess();
			return;
		}

		$user = &GetUser();
		if (!$user->HasAccess('Lists', 'Edit')) {
			$this->DenyAccess();
			return;
		}

		$GLOBALS['Action'] = 'Update&id=' . $listid;
		$GLOBALS['CancelButton'] = GetLang('EditListCancelButton');
		$GLOBALS['Heading'] = GetLang('EditMailingList');
		$GLOBALS['Intro'] = GetLang('EditMailingListIntro');
		$GLOBALS['ListDetails'] = GetLang('EditMailingListHeading');

		$GLOBALS['Name'] = htmlspecialchars($list->name, ENT_QUOTES, SENDSTUDIO_CHARSET);
		$GLOBALS['OwnerName'] = htmlspecialchars($list->ownername, ENT_QUOTES, SENDSTUDIO_CHARSET);
		$GLOBALS['OwnerEmail'] = $list->owneremail;
		$GLOBALS['BounceEmail'] = $list->bounceemail;
		$GLOBALS['ReplyToEmail'] = $list->replytoemail;

		$GLOBALS['BounceServer'] = $list->bounceserver;
		$GLOBALS['BounceUsername'] = $list->bounceusername;
		$GLOBALS['BouncePassword'] = $list->bouncepassword;

		$GLOBALS['DisplayExtraMailSettings'] = 'none';
		if ($list->extramailsettings) {
			if ($user->HasAccess('Lists', 'BounceSettings')) {
				$GLOBALS['DisplayExtraMailSettings'] = '';
			}
			$GLOBALS['UseExtraMailSettings'] = ' CHECKED';
			$GLOBALS['ExtraMailSettings'] = $list->extramailsettings;
		}

		$GLOBALS['NotifyOwner'] = ($list->notifyowner) ? ' CHECKED' : '';
		$GLOBALS['IMAPAccount'] = ($list->imapaccount) ? ' CHECKED' : '';

		$GLOBALS['ShowBounceInfo'] = 'none';

		if ($user->HasAccess('Lists', 'BounceSettings')) {
			$GLOBALS['ShowBounceInfo'] = '';
		}

		$this->ParseTemplate('Lists_Form');
	}

	/**
	* CreateList
	* Displays the 'create list' form.
	*
	* @see GetUser
	* @see User_API::CanCreateList
	* @see GetApi
	* @see List_API::Load
	* @see List_API::GetAllFormats
	*
	* @return Void Doesn't return anything, prints out the form for the list to be copied.
	*/
	function CreateList()
	{
		$user = &GetUser();
		$db = &GetDatabase();

		if ($user->CanCreateList() !== true) {
			$GLOBALS['Error'] = GetLang('TooManyLists');
			$this->ParseTemplate('ErrorMsg');
			return false;
		}

		$GLOBALS['OwnerName'] = $user->fullname;
		$GLOBALS['OwnerEmail'] = $user->emailaddress;
		$GLOBALS['BounceEmail'] = $user->emailaddress;

		$GLOBALS['ReplyToEmail'] = $user->emailaddress;

		if (SENDSTUDIO_BOUNCE_ADDRESS) {
			$GLOBALS['BounceEmail'] = SENDSTUDIO_BOUNCE_ADDRESS;
		}

		$GLOBALS['BounceServer'] = SENDSTUDIO_BOUNCE_SERVER;
		$GLOBALS['BounceUsername'] = SENDSTUDIO_BOUNCE_USERNAME;
		$GLOBALS['BouncePassword'] = @base64_decode(SENDSTUDIO_BOUNCE_PASSWORD);

		if (SENDSTUDIO_BOUNCE_IMAP == 1) {
			$GLOBALS['IMAPAccount'] = ' CHECKED';
		}

		$GLOBALS['DisplayExtraMailSettings'] = 'none';

		if (SENDSTUDIO_BOUNCE_EXTRASETTINGS) {
			$GLOBALS['UseExtraMailSettings'] = ' CHECKED';

			if ($user->HasAccess('Lists', 'BounceSettings')) {
				$GLOBALS['DisplayExtraMailSettings'] = '';
			}
			$GLOBALS['ExtraMailSettings'] = SENDSTUDIO_BOUNCE_EXTRASETTINGS;
		}

		// if the form has been filled in but we're displaying an error, try to prefill the form.
		if (!empty($_POST)) {
			foreach ($_POST as $key => $val) {
				$GLOBALS[$key] = htmlspecialchars($val, ENT_QUOTES, SENDSTUDIO_CHARSET);
			}
		}

		$GLOBALS['Action'] = 'AddList';
		$GLOBALS['CancelButton'] = GetLang('CreateListCancelButton');
		$GLOBALS['Heading'] = GetLang('CreateMailingList');
		$GLOBALS['Intro'] = GetLang('CreateMailingListIntro');
		$GLOBALS['ListDetails'] = GetLang('CreateMailingListHeading');

		$listapi = $this->GetApi();

		$GLOBALS['NotifyOwner'] = 'CHECKED';

		// if these variables aren't in the post array, then they have been unticked. Try to remember the options.
		if (!empty($_POST)) {
			if (!isset($_POST['NotifyOwner'])) {
				$GLOBALS['NotifyOwner'] = '';
			}
			if (!isset($_POST['IMAPAccount'])) {
				$GLOBALS['IMAPAccount'] = '';
			}
		}

		$GLOBALS['ShowBounceInfo'] = 'none';

		if ($user->HasAccess('Lists', 'BounceSettings')) {
			$GLOBALS['ShowBounceInfo'] = '';
		}

		$this->ParseTemplate('Lists_Form');
	}
}
?>
