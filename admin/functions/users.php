<?php
/**
* This file has the user editing forms in it.
*
* @version     $Id: users.php,v 1.38 2007/05/15 07:03:55 rodney Exp $

*
* @package SendStudio
* @subpackage SendStudio_Functions
*/

/**
* Include the base sendstudio functions.
*/
require(dirname(__FILE__) . '/sendstudio_functions.php');

/**
* Class for the users management page.
* This checks whether you are allowed to manage users or whether you are only allowed to manage your own account. This also handles editing, deleting, checks to make sure you're not removing the 'last' user and so on.
*
* @package SendStudio
* @subpackage SendStudio_Functions
*/
class Users extends SendStudio_Functions
{

	/**
	* _DefaultDirection
	* Override the default sort direction for users.
	*
	* @see _DefaultSort
	* @see GetSortDetails
	*
	* @var String
	*/
	var $_DefaultDirection = 'asc';

	/**
	* Constructor
	* Loads the 'users' and 'timezones' language files
	*
	* @return Void Doesn't return anything.
	*/
	function Users()
	{
		$this->LoadLanguageFile('Users');
		$this->LoadLanguageFile('Timezones');
	}

	/**
	* Process
	* Works out what's going on.
	* The API does the loading, saving, updating - this page just displays the right form(s), checks password validation and so on.
	* After that, it'll print a success/failure message depending on what happened.
	* It also checks to make sure that you're an admin before letting you add or delete.
	* It also checks you're not going to delete your own account.
	* If you're not an admin user, it won't let you edit anyone elses account and it won't let you delete your own account either.
	*
	* @see PrintHeader
	* @see ParseTemplate
	* @see GetSession
	* @see Session::Get
	* @see GetDatabase
	* @see GetUser
	* @see GetLang
	* @see User_API::Set
	* @see PrintEditForm
	* @see CheckUserSystem
	* @see PrintManageUsers
	* @see User_API::Find
	* @see User_API::Admin
	* @see PrintFooter
	*
	* @return Void Doesn't return anything, passes control over to the relevant function and prints that functions return message.
	*/
	function Process()
	{
		$this->PrintHeader();

		$session = &GetSession();
		$thisuser = $session->Get('UserDetails');

		$action = (isset($_GET['Action'])) ? strtolower($_GET['Action']) : '';

		$access = $thisuser->HasAccess('users', $action);

		if (!$access) {
			$this->DenyAccess();
		}

		if ($action == 'processpaging') {
			$perpage = (int)$_GET['PerPageDisplay'];
			$display_settings = array('NumberToShow' => $perpage);
			$thisuser->SetSettings('DisplaySettings', $display_settings);
			$action = '';
		}

		switch ($action) {
			case 'save':
				$userid = (isset($_GET['UserID'])) ? $_GET['UserID'] : 0;

				$user = &GetUser($userid);
				$username = false;
				if (isset($_POST['username'])) {
					$username = $_POST['username'];
				}
				$userfound = $user->Find($username);

				$error = false;
				$template = false;

				$duplicate_username = false;
				if ($userfound && $userfound != $userid) {
					$duplicate_username = true;
					$error = GetLang('UserAlreadyExists');
				}

				if (!$duplicate_username) {

					$to_check = array();
					foreach (array('status' => 'LastActiveUser', 'admintype' => 'LastAdminUser') as $area => $desc) {
						if (!isset($_POST[$area])) {
							$to_check[] = $desc;
						}
						if (isset($_POST[$area]) && $_POST[$area] == '0') {
							$to_check[] = $desc;
						}
					}

					if ($_POST['admintype'] != 'a') {
						$to_check[] = 'LastAdminUser';
					}

					$error = $this->CheckUserSystem($userid, $to_check);

					if (!$error) {

						$smtptype = (isset($_POST['smtptype'])) ? $_POST['smtptype'] : 0;
						foreach (array('username', 'fullname', 'emailaddress', 'status', 'admintype', 'listadmintype', 'templateadmintype', 'editownsettings', 'usertimezone', 'textfooter', 'htmlfooter', 'unlimitedmaxemails', 'infotips', 'smtpserver', 'smtpusername', 'smtpport') as $p => $area) {
							$val = (isset($_POST[$area])) ? $_POST[$area] : '';
							if (in_array($area, array('status', 'editownsettings'))) {
								if ($userid == $thisuser->userid) {
									$val = $thisuser->$area;
								}
							}
							if ($area == 'unlimitedmaxemails') {
								$maxemails = 0;
								if ($val == 0 && isset($_POST['maxemails'])) {
									$maxemails = (int)$_POST['maxemails'];
								}
								$user->Set('maxemails', $maxemails);
							}
							$user->Set($area, $val);
						}

						// the 'limit' things being on actually means unlimited. so check if the value is NOT set.
						foreach (array('permonth', 'perhour', 'maxlists') as $p => $area) {
							$limit_check = 'limit' . $area;
							$val = 0;
							if (!isset($_POST[$limit_check])) {
								$val = (isset($_POST[$area])) ? $_POST[$area] : 0;
							}
							$user->Set($area, $val);
						}

						// this has a different post value otherwise firefox tries to pre-fill it.
						$smtp_pass = '';
						if (isset($_POST['smtp_p'])) {
							$smtp_pass = $_POST['smtp_p'];
						}
						$user->Set('smtppassword', $smtp_pass);

						if ($smtptype == 0) {
							$user->Set('smtpserver', false);
							$user->Set('smtpusername', false);
							$user->Set('smtppassword', false);
							$user->Set('smtpport', false);
						}

						if ($_POST['ss_p'] != '') {
							if ($_POST['ss_p_confirm'] != '' && $_POST['ss_p_confirm'] == $_POST['ss_p']) {
								$user->Set('password', $_POST['ss_p']);
							} else {
								$error = GetLang('PasswordsDontMatch');
							}
						}
					}

					$user->RevokeAccess();

					if (!empty($_POST['permissions'])) {
						foreach ($_POST['permissions'] as $area => $p) {
							foreach ($p as $subarea => $k) {
								$user->GrantAccess($area, $subarea);
							}
						}
					}

					if ($_POST['listadmintype'] == 'c') {
						$user->RevokeListAccess();
						if (!empty($_POST['lists'])) {
							$user->GrantListAccess($_POST['lists']);
						}
					}

					if ($_POST['templateadmintype'] == 'c') {
						$user->RevokeTemplateAccess();
						if (!empty($_POST['templates'])) {
							$user->GrantTemplateAccess($_POST['templates']);
						}
					}
				}

				if (!$error) {
					$result = $user->Save();
					if ($result) {
						$GLOBALS['Message'] = $this->PrintSuccess('UserUpdated') . '<br/>';
					} else {
						$GLOBALS['Error'] = GetLang('UserNotUpdated');
						$GLOBALS['Message'] = $this->ParseTemplate('ErrorMsg', true, false);
					}
				} else {
					$GLOBALS['Error'] = $error;
					$GLOBALS['Message'] = $this->ParseTemplate('ErrorMsg', true, false);
				}

				// if we're editing our own user, reload our settings.
				if ($userid == $thisuser->userid) {
					$new_user = &GetUser($userid);
					$session->Set('UserDetails', $new_user);
				}
				$this->PrintEditForm($userid);
			break;

			case 'add':
				$this->PrintEditForm(0);
			break;

			case 'delete':
				$users = (isset($_POST['users'])) ? $_POST['users'] : array();
				if (isset($_GET['id'])) {
					$users = array((int)$_GET['id']);
				}

				$this->DeleteUsers($users);
			break;

			case 'create':
				$user = &New User_API();
				if (!$user->Find($_POST['username'])) {
					foreach (array('username', 'fullname', 'emailaddress', 'status', 'admintype', 'editownsettings', 'listadmintype', 'usertimezone', 'textfooter', 'htmlfooter', 'templateadmintype', 'unlimitedmaxemails', 'infotips', 'smtpserver', 'smtpusername', 'smtpport') as $p => $area) {
						$val = (isset($_POST[$area])) ? $_POST[$area] : '';

						if ($area == 'unlimitedmaxemails') {
							$maxemails = 0;
							if ($val == 0 && isset($_POST['maxemails'])) {
								$maxemails = (int)$_POST['maxemails'];
							}
							$user->Set('maxemails', $maxemails);
						}

						$user->Set($area, $val);
					}

					// the 'limit' things being on actually means unlimited. so check if the value is NOT set.
					foreach (array('permonth', 'perhour', 'maxlists') as $p => $area) {
						$limit_check = 'limit' . $area;
						$val = 0;
						if (!isset($_POST[$limit_check])) {
							$val = (isset($_POST[$area])) ? $_POST[$area] : 0;
						}
						$user->Set($area, $val);
					}

					// this has a different post value otherwise firefox tries to pre-fill it.
					$smtp_password = '';
					if (isset($_POST['smtp_p'])) {
						$smtp_password = $_POST['smtp_p'];
					}
					$user->Set('smtppassword', $smtp_password);

					$error = false;

					if ($_POST['ss_p'] != '') {
						if ($_POST['ss_p_confirm'] != '' && $_POST['ss_p_confirm'] == $_POST['ss_p']) {
							$user->Set('password', $_POST['ss_p']);
						} else {
							$error = GetLang('PasswordsDontMatch');
						}
					}

					if (!$error) {
						if (!empty($_POST['permissions'])) {
							foreach ($_POST['permissions'] as $area => $p) {
								foreach ($p as $subarea => $k) {
									$user->GrantAccess($area, $subarea);
								}
							}
						}

						if (!empty($_POST['lists'])) {
							$user->GrantListAccess($_POST['lists']);
						}

						if (!empty($_POST['templates'])) {
							$user->GrantTemplateAccess($_POST['templates']);
						}

						$result = $user->Create();
						if ($result) {
							$GLOBALS['Message'] = $this->PrintSuccess('UserCreated') . '<br/>';
							$this->PrintManageUsers();
							break;
						}
						$GLOBALS['Error'] = GetLang('UserNotCreated');
					} else {
						$GLOBALS['Error'] = $error;
					}
				} else {
					$GLOBALS['Error'] = GetLang('UserAlreadyExists');
				}
				$GLOBALS['Message'] = $this->ParseTemplate('ErrorMsg', true, false);

				$details = array();
				foreach (array('FullName', 'EmailAddress', 'Status', 'AdminType', 'ListAdminType', 'TemplateAdminType', 'InfoTips', 'smtpserver', 'smtpusername', 'smtpport') as $p => $area) {
					$lower = strtolower($area);
					$val = (isset($_POST[$lower])) ? $_POST[$lower] : '';
					$details[$area] = $val;
				}

				$details['smtppassword'] = $smtp_password;

				$this->PrintEditForm(0, $details);
			break;

			case 'edit':
				$userid = (isset($_GET['UserID'])) ? $_GET['UserID'] : 0;
				$this->PrintEditForm($userid);
			break;

			default:
				$this->PrintManageUsers();
			break;
		}
		$this->PrintFooter();
	}

	/**
	* PrintManageUsers
	* Prints a list of users to manage. If you are only allowed to manage your own account, only shows your account in the list. This allows you to edit, delete and so on.
	*
	* @see GetSession
	* @see Session::Get
	* @see GetApi
	* @see GetPerPage
	* @see GetSortDetails
	* @see User_API::Admin
	* @see GetUsers
	* @see SetupPaging
	*
	* @return Void Prints out the list, doesn't return anything.
	*/
	function PrintManageUsers()
	{
		$session = &GetSession();
		$thisuser = $session->Get('UserDetails');

		$userapi = $this->GetApi('User');

		$perpage = $this->GetPerPage();

		$DisplayPage = $this->GetCurrentPage();
		$start = ($DisplayPage - 1) * $perpage;

		$sortinfo = $this->GetSortDetails();

		$userowner = ($thisuser->Admin()) ? 0 : $thisuser->userid;

		$usercount = $userapi->GetUsers($userowner, $sortinfo, true);
		$myusers = $userapi->GetUsers($userowner, $sortinfo, false, $start, $perpage);

		if ($thisuser->Admin()) {
			$licensecheck = ssk23twgezm2();
			$GLOBALS['UserReport'] = $licensecheck;
		}

		$this->SetupPaging($usercount, $DisplayPage, $perpage);
		$GLOBALS['FormAction'] = 'Action=ProcessPaging';
		$paging = $this->ParseTemplate('Paging', true, false);

		$display = '';

		foreach ($myusers as $pos => $userdetails) {
			$GLOBALS['UserID'] = $userdetails['userid'];
			$GLOBALS['UserName'] = htmlspecialchars($userdetails['username'], ENT_QUOTES, SENDSTUDIO_CHARSET);
			$GLOBALS['FullName'] = htmlspecialchars($userdetails['fullname'], ENT_QUOTES, SENDSTUDIO_CHARSET);
			if (!$userdetails['fullname']) {
				$GLOBALS['FullName'] = GetLang('N/A');
			}
			$GLOBALS['Status'] = ($userdetails['status'] == 1) ? GetLang('Active') : GetLang('Inactive');

			$usertype = $thisuser->GetAdminType($userdetails['admintype']);
			if ($usertype == 'c') {
				$usertype = 'RegularUser';
			}

			$GLOBALS['UserType'] = GetLang('AdministratorType_' . $usertype);

			$action = '<a href="index.php?Page=Users&Action=Edit&UserID=' . $userdetails['userid'] . '">' . GetLang('Edit') . '</a>';

			if ($thisuser->UserAdmin()) {
				if ($userdetails['userid'] == $thisuser->Get('userid')) {
					$action .= $this->DisabledItem('Delete', 'User_Delete_Own_Disabled');
				} else {
					$action .= '&nbsp;&nbsp;<a href="javascript: ConfirmDelete(' . $userdetails['userid'] . ');">' . GetLang('Delete') . '</a>';
				}
			} else {
				$action .= $this->DisabledItem('Delete', 'User_Delete_Own_Disabled');
			}

			$GLOBALS['UserAction'] = $action;

			$template = $this->ParseTemplate('Users_List_Row', true, false);
			$display .= $template;
		}

		if ($thisuser->UserAdmin()) {
			$GLOBALS['Users_AddButton'] = $this->ParseTemplate('User_Add_Button', true, false);
			$GLOBALS['Users_DeleteButton'] = $this->ParseTemplate('User_Delete_Button', true, false);
		}

		$user_list = $this->ParseTemplate('Users', true, false);

		$user_list = str_replace('%%TPL_Paging%%', $paging, $user_list);
		$user_list = str_replace('%%TPL_Paging_Bottom%%', $GLOBALS['PagingBottom'], $user_list);
		$user_list = str_replace('%%TPL_Users_List_Row%%', $display, $user_list);

		echo $user_list;
	}

	/**
	* PrintEditForm
	* Prints a form to edit a user. If you pass in a userid, it will load up that user and print their information. If you pass in the details array, it will prefill the form with that information (eg if you tried to create a user with a duplicate username). Also checks whether you are allowed to edit this user. If you are not an admin, you are only allowed to edit your own account.
	*
	* @param Int $userid Userid to load up.
	* @param Array $details Details to prefill the form with (in case there was a problem creating the user).
	*
	* @see GetSession
	* @see Session::Get
	* @see User_API::Admin
	* @see User_API::Status
	* @see User_API::ListAdmin
	* @see User_API::EditOwnSettings
	* @see GetUser
	*
	* @return Void Returns nothing. If you don't have access to edit a particular user, it prints an error message and exits. Otherwise it prints the correct form (either edit-own or edit) and then exits.
	*/
	function PrintEditForm($userid=0, $details = array())
	{
		$session = &GetSession();
		$thisuser = $session->Get('UserDetails');
		if (!$thisuser->UserAdmin()) {
			if ($userid != $thisuser->userid) {
				$GLOBALS['ErrorMessage'] = GetLang('NoAccess');
				$this->ParseTemplate('AccessDenied');
				return;
			}

			if (!$thisuser->EditOwnSettings()) {
				$GLOBALS['ErrorMessage'] = GetLang('NoAccess');
				$this->ParseTemplate('AccessDenied');
				return;
			}
		}

		$listapi = $this->GetApi('Lists');
		$all_lists = $listapi->GetLists(0, array('name', 'desc'), false, 0, 0);
		$templateapi = $this->GetApi('Templates');
		$all_templates = $templateapi->GetTemplates(0, array('name', 'desc'), false, 0, 0);

		$GLOBALS['CustomSmtpServer_Display'] = '0';

		if ($userid > 0) {
			$user = &GetUser($userid);
			$GLOBALS['UserID'] = $user->Get('userid');
			$GLOBALS['UserName'] = htmlspecialchars($user->Get('username'), ENT_QUOTES, SENDSTUDIO_CHARSET);
			$GLOBALS['FullName'] = htmlspecialchars($user->Get('fullname'), ENT_QUOTES, SENDSTUDIO_CHARSET);
			$GLOBALS['EmailAddress'] = htmlspecialchars($user->Get('emailaddress'), ENT_QUOTES, SENDSTUDIO_CHARSET);

			$GLOBALS['MaxLists'] = $user->Get('maxlists');
			$GLOBALS['MaxEmails'] = $user->Get('maxemails');
			$GLOBALS['PerMonth'] = $user->Get('permonth');
			$GLOBALS['PerHour'] = $user->Get('perhour');


			$GLOBALS['DisplayMaxLists'] = '';
			if ($user->Get('maxlists') == 0) {
				$GLOBALS['LimitListsChecked'] = ' CHECKED';
				$GLOBALS['DisplayMaxLists'] = 'none';
			}

			$GLOBALS['DisplayEmailsPerHour'] = '';
			if ($user->Get('perhour') == 0) {
				$GLOBALS['LimitPerHourChecked'] = ' CHECKED';
				$GLOBALS['DisplayEmailsPerHour'] = 'none';
			}

			$GLOBALS['DisplayEmailsPerMonth'] = '';
			if ($user->Get('permonth') == 0) {
				$GLOBALS['LimitPerMonthChecked'] = ' CHECKED';
				$GLOBALS['DisplayEmailsPerMonth'] = 'none';
			}

			$GLOBALS['LimitMaximumEmailsChecked'] = ' CHECKED';
			$GLOBALS['DisplayEmailsMaxEmails'] = 'none';

			if (!$user->Get('unlimitedmaxemails')) {
				$GLOBALS['LimitMaximumEmailsChecked'] = '';
				$GLOBALS['DisplayEmailsMaxEmails'] = '';
			}

			$GLOBALS['TextFooter'] = $user->Get('textfooter');
			$GLOBALS['HTMLFooter'] = $user->Get('htmlfooter');

			$GLOBALS['SmtpServer'] = $user->Get('smtpserver');
			$GLOBALS['SmtpUsername'] = $user->Get('smtpusername');
			$GLOBALS['SmtpPassword'] = $user->Get('smtppassword');
			$GLOBALS['SmtpPort'] = $user->Get('smtpport');

			if ($GLOBALS['SmtpServer']) {
				$GLOBALS['CustomSmtpServer_Display'] = '1';
			}

			$GLOBALS['FormAction'] = 'Action=Save&UserID=' . $user->userid;

			if (!$thisuser->Admin()) {

				$smtp_access = $thisuser->HasAccess('User', 'SMTP');

				$GLOBALS['ShowSMTPInfo'] = 'none';
				$GLOBALS['DisplaySMTP'] = '0';

				if ($smtp_access) {
					$GLOBALS['ShowSMTPInfo'] = '';
				}

				if ($GLOBALS['SmtpServer']) {
					$GLOBALS['CustomSmtpServer_Display'] = '1';
					if ($smtp_access) {
						$GLOBALS['DisplaySMTP'] = '1';
					}
				}

				$this->ParseTemplate('User_Edit_Own');
				return;
			}

			$GLOBALS['StatusChecked'] = ($user->Status()) ? ' CHECKED' : '';

			$GLOBALS['InfoTipsChecked'] = ($user->InfoTips()) ? ' CHECKED' : '';

			$editown = '';
			if ($user->Admin()) {
				$editown = ' CHECKED';
			} else {
				if ($user->EditOwnSettings()) {
					$editown = ' CHECKED';
				}
			}
			$GLOBALS['EditOwnSettingsChecked'] = $editown;

			$timezone = $user->usertimezone;

			$GLOBALS['TimeZoneList'] = $this->TimeZoneList($timezone);

			$admintype = $user->AdminType();
			$listadmintype = $user->ListAdminType();
			$templateadmintype = $user->TemplateAdminType();

			$admin = $user->Admin();
			$listadmin = $user->ListAdmin();
			$templateadmin = $user->TemplateAdmin();

			$permissions = $user->Get('permissions');
			$area_access = $user->Get('access');

			$GLOBALS['Heading'] = GetLang('EditUser');
			$GLOBALS['Help_Heading'] = GetLang('Help_EditUser');

		} else {

			$timezone = (isset($details['timezone'])) ? $details['timezone'] : SENDSTUDIO_SERVERTIMEZONE;
			$GLOBALS['TimeZoneList'] = $this->TimeZoneList($timezone);

			$GLOBALS['FormAction'] = 'Action=Create';

			if (!empty($details)) {
				foreach ($details as $area => $val) {
					$GLOBALS[$area] = $val;
				}
			}
			$GLOBALS['Heading'] = GetLang('CreateUser');
			$GLOBALS['Help_Heading'] = GetLang('Help_CreateUser');

			$listadmintype = 'a';
			$admintype = 'c';
			$templateadmintype = 'a';

			$GLOBALS['DisplayMaxLists'] = 'none';
			$GLOBALS['DisplayEmailsPerHour'] = 'none';
			$GLOBALS['DisplayEmailsPerMonth'] = 'none';
			$GLOBALS['DisplayEmailsMaxEmails'] = 'none';

			$GLOBALS['MaxLists'] = '0';
			$GLOBALS['PerHour'] = '0';
			$GLOBALS['PerMonth'] = '0';
			$GLOBALS['MaxEmails'] = '0';

			$GLOBALS['StatusChecked'] = ' CHECKED';
			$GLOBALS['InfoTipsChecked'] = ' CHECKED';
			$GLOBALS['EditOwnSettingsChecked'] = ' CHECKED';

			$GLOBALS['LimitListsChecked'] = ' CHECKED';
			$GLOBALS['LimitPerHourChecked'] = ' CHECKED';
			$GLOBALS['LimitPerMonthChecked'] = ' CHECKED';
			$GLOBALS['LimitMaximumEmailsChecked'] = ' CHECKED';

			$admin = $listadmin = $templateadmin = false;
			$permissions = array();
			$area_access = array('lists' => array(), 'templates' => array());
		}

		$permission_types = $thisuser->Get('PermissionTypes');
		foreach ($permission_types as $area => $sub) {
			foreach ($sub as $p => $subarea) {
				if (in_array($area, array_keys($permissions))) {
					if (in_array($subarea, $permissions[$area])) {
						$GLOBALS['Permissions_' . ucwords($area) . '_' . ucwords($subarea)] = ' CHECKED';
					}
				}
			}
		}

		$GLOBALS['PrintAdminTypes'] = '';
		foreach ($thisuser->GetAdminTypes() as $option => $desc) {
			$selected = '';
			if ($option == $admintype) {
				$selected = ' SELECTED';
			}

			$GLOBALS['PrintAdminTypes'] .= '<option value="' . $option . '"' . $selected . '>' . GetLang('AdministratorType_' . $desc) . '</option>';
		}


		$GLOBALS['PrintListAdminList'] = '';
		foreach ($thisuser->GetListAdminTypes() as $option => $desc) {
			$selected = '';
			if ($listadmin) {
				if ($option == 'a') {
					$selected = ' SELECTED';
				}
			} else {
				if ($option == $listadmintype) {
					$selected = ' SELECTED';
				}
			}
			$GLOBALS['PrintListAdminList'] .= '<option value="' . $option . '"' . $selected . '>' . GetLang('ListAdministratorType_' . $desc) . '</option>';
		}

		$GLOBALS['DisplayPermissions'] = '';
		if ($admintype != 'c') {
			$GLOBALS['DisplayPermissions'] = 'none';
		}

		if ($listadmin) {
			$GLOBALS['ListDisplay'] = 'none';
		} else {
			$GLOBALS['ListDisplay'] = '';
		}

		$list_rows = '';
		if (empty($all_lists)) {
			$GLOBALS['NoOptionAvailable'] = GetLang('NoListsAvailable');
			$list_rows .= $this->ParseTemplate('User_No_Option_Available', true, false);
		} else {
			foreach ($all_lists as $p => $listdetails) {
				$GLOBALS['CheckBoxOption'] = htmlspecialchars($listdetails['name'], ENT_QUOTES, SENDSTUDIO_CHARSET);
				$GLOBALS['CheckBoxName'] = 'lists[' . $listdetails['listid'] . ']';
				$GLOBALS['CheckBoxChecked'] = '';
				if (in_array($listdetails['listid'], $area_access['lists'])) {
					$GLOBALS['CheckBoxChecked'] = ' CHECKED';
				}
				$list_rows .= $this->ParseTemplate('User_Permission_Option_Option', true, false);
			}
		}
		$GLOBALS['IndividualOption'] = $list_rows;
		$GLOBALS['PrintMailingLists'] = $this->ParseTemplate('User_Permission_Option', true, false);

		$GLOBALS['PrintTemplateAdminList'] = '';
		foreach ($thisuser->GetTemplateAdminTypes() as $option => $desc) {
			$selected = '';
			if ($templateadmin) {
				if ($option == 'a') {
					$selected = ' SELECTED';
				}
			} else {
				if ($option == $templateadmintype) {
					$selected = ' SELECTED';
				}
			}
			$GLOBALS['PrintTemplateAdminList'] .= '<option value="' . $option . '"' . $selected . '>' . GetLang('TemplateAdministratorType_' . $desc) . '</option>';
		}

		if ($templateadmin) {
			$GLOBALS['TemplateDisplay'] = 'none';
		} else {
			$GLOBALS['TemplateDisplay'] = '';
		}

		$template_rows = '';
		if (empty($all_templates)) {
			$GLOBALS['NoOptionAvailable'] = GetLang('NoTemplatesAvailable');
			$template_rows .= $this->ParseTemplate('User_No_Option_Available', true, false);
		} else {
			foreach ($all_templates as $p => $templatedetails) {
				$GLOBALS['CheckBoxOption'] = htmlspecialchars($templatedetails['name'], ENT_QUOTES, SENDSTUDIO_CHARSET);
				$GLOBALS['CheckBoxName'] = 'templates[' . $templatedetails['templateid'] . ']';
				$GLOBALS['CheckBoxChecked'] = '';
				if (in_array($templatedetails['templateid'], $area_access['templates'])) {
					$GLOBALS['CheckBoxChecked'] = ' CHECKED';
				}
				$template_rows .= $this->ParseTemplate('User_Permission_Option_Option', true, false);
			}
		}
		$GLOBALS['IndividualOption'] = $template_rows;
		$GLOBALS['PrintTemplateLists'] = $this->ParseTemplate('User_Permission_Option', true, false);

		$this->ParseTemplate('User_Form');
	}

	/**
	* CheckUserSystem
	* Checks the user system to make sure that this particular user isn't the last user, last active user or last admin user. This just ensures a bit of system integrity.
	*
	* @param Int $userid Userid to check.
	* @param Array $to_check Which areas you want to check. This can be LastActiveUser, LastUser and/or LastAdminUser.
	*
	* @see GetUser
	* @see User_API::LastActiveUser
	* @see User_API::LastUser
	* @see User_API::LastAdminUser
	*
	* @return False|String Returns false if you are not the last 'X', else it returns an error message about why the user can't be edited/deleted.
	*/
	function CheckUserSystem($userid=0, $to_check = array('LastActiveUser', 'LastUser', 'LastAdminUser'))
	{
		$return_error = false;

		$user_system = &GetUser($userid);

		if (in_array('LastActiveUser', $to_check)) {
			if ($user_system->LastActiveUser()) {
				$return_error = GetLang('LastActiveUser');
			}
		}

		if (in_array('LastUser', $to_check)) {
			if (!$return_error && $user_system->LastUser()) {
				$return_error = GetLang('LastUser');
			}
		}

		if (in_array('LastAdminUser', $to_check)) {
			if (!$return_error && $user_system->LastAdminUser()) {
				$return_error = GetLang('LastAdminUser');
			}
		}
		return $return_error;
	}

	/**
	* DeleteUsers
	* Deletes a list of users from the database via the api. Each user is checked to make sure you're not going to accidentally delete your own account and that you're not going to delete the 'last' something (whether it's the last active user, admin user or other).
	* If you aren't an admin user, you can't do anything at all.
	*
	* @param Array $users An array of userid's to delete
	*
	* @see GetUser
	* @see User_API::UserAdmin
	* @see DenyAccess
	* @see CheckUserSystem
	* @see PrintManageUsers
	*
	* @return Void Doesn't return anything. Works out the relevant message about who was/wasn't deleted and prints that out. Returns control to PrintManageUsers.
	*/
	function DeleteUsers($users=array())
	{
		$thisuser = &GetUser();
		if (!$thisuser->UserAdmin()) {
			$this->DenyAccess();
			return;
		}

		if (!is_array($users)) {
			$users = array($users);
		}

		$not_deleted_list = array();
		$not_deleted = $deleted = 0;
		foreach ($users as $p => $userid) {
			if ($userid == $thisuser->Get('userid')) {
				$not_deleted++;
				$not_deleted_list[$userid] = array('username' => $thisuser->Get('username'), 'reason' => GetLang('User_CantDeleteOwn'));
				continue;
			}
			$user = &GetUser($userid);

			$error = $this->CheckUserSystem($userid);
			if (!$error) {
				$result = $user->Delete();
				if ($result) {
					$deleted++;
				} else {
					$not_deleted++;
				}
			} else {
				$not_deleted++;
				$not_deleted_list[$userid] = array('username' => $user->Get('username'), 'reason' => $error);
			}
		}

		$msg = '';

		if ($not_deleted > 0) {
			foreach ($not_deleted_list as $uid => $details) {
				$GLOBALS['Error'] = sprintf(GetLang('UserDeleteFail'), htmlspecialchars($details['username'], ENT_QUOTES, SENDSTUDIO_CHARSET), htmlspecialchars($details['reason'], ENT_QUOTES, SENDSTUDIO_CHARSET));
				$msg .= $this->ParseTemplate('ErrorMsg', true, false);
			}
		}

		if ($deleted > 0) {
			if ($deleted == 1) {
				$msg .= $this->PrintSuccess('UserDeleteSuccess_One') . '<br/>';
			} else {
				$msg .= $this->PrintSuccess('UserDeleteSuccess_Many', $this->FormatNumber($deleted)) . '<br/>';
			}
		}
		$GLOBALS['Message'] = $msg;
		$this->PrintManageUsers();
	}
}

?>
