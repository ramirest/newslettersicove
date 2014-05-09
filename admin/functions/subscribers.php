<?php
/**
* This file has the base subscriber functions in it. Each subprocess is handled separately.
*
* @version     $Id: subscribers.php,v 1.32 2007/05/15 07:03:55 rodney Exp $

*
* @package SendStudio
* @subpackage SendStudio_Functions
*/

/**
* Include the base sendstudio functions.
*/
require(dirname(__FILE__) . '/sendstudio_functions.php');

/**
* Base class for subscribers processing. This simply hands the processing to subareas (eg adding, banning, exporting and so on).
*
* @package SendStudio
* @subpackage SendStudio_Functions
*/
class Subscribers extends SendStudio_Functions
{

	/**
	* PopupWindows
	* A list of popup windows for subscribers. This is overridden from the main sendstudio_functions file.
	*
	* @var Array
	*/
	var $PopupWindows = array('import', 'export', 'view_report', 'viewtutorial');

	/**
	* Constructor
	* Loads the language file.
	*
	* @return Void Doesn't return anything
	*/
	function Subscribers()
	{
		$this->LoadLanguageFile('Subscribers');
	}

	/**
	* Process
	* This does base processing only. Prints the headers, handles paging, then passes off the functionality to the appropriate subarea.
	*
	* @see Subscribers_Add
	* @see Subscribers_Banned
	* @see Subscribers_Edit
	* @see Subscribers_Export
	* @see Subscribers_Import
	* @see Subscribers_Manage
	* @see Subscribers_Remove
	*/
	function Process()
	{
		$GLOBALS['Message'] = '';

		$action = (isset($_GET['Action'])) ? strtolower(urldecode($_GET['Action'])) : null;
		$user = &GetUser();

		$permission_action = $action;
		if ($action == 'remove') {
			$permission_action = 'delete';
		}

		$access = $user->HasAccess('Subscribers', $permission_action);

		$subaction = (isset($_GET['SubAction'])) ? strtolower($_GET['SubAction']) : null;

		$popup = (in_array($subaction, $this->PopupWindows)) ? true : false;
		$this->PrintHeader($popup);

		if (!$access) {
			$this->DenyAccess();
			return;
		}

		switch ($action) {
			case 'add':
				require(dirname(__FILE__) . '/subscribers_add.php');
				$AddSubscribers = &new Subscribers_Add();
				$AddSubscribers->Process($subaction);
			break;

			case 'remove':
				require(dirname(__FILE__) . '/subscribers_remove.php');
				$RemoveSubscribers = &new Subscribers_Remove();
				$RemoveSubscribers->Process($subaction);
			break;

			case 'delete':
			case 'manage':
				require(dirname(__FILE__) . '/subscribers_manage.php');
				$ManageSubscribers = &new Subscribers_Manage();
				$ManageSubscribers->Process($subaction);
			break;

			case 'edit':
				require(dirname(__FILE__) . '/subscribers_edit.php');
				$EditSubscriber = &new Subscribers_Edit();
				$EditSubscriber->Process($subaction);
			break;

			case 'import':
				require(dirname(__FILE__) . '/subscribers_import.php');
				$ImportSubscribers = &new Subscribers_Import();
				$ImportSubscribers->Process($subaction);
			break;

			case 'export':
				require(dirname(__FILE__) . '/subscribers_export.php');
				$ExportSubscribers = &new Subscribers_Export();
				$ExportSubscribers->Process($subaction);
			break;

			case 'banned':
				require(dirname(__FILE__) . '/subscribers_banned.php');
				$ExportSubscribers = &new Subscribers_Banned();
				$ExportSubscribers->Process($subaction);
			break;

			case 'search':
				require(dirname(__FILE__) . '/subscribers_search.php');
				$SearchSubscribers = &new Subscribers_Search();
				$SearchSubscribers->Process($subaction);
			break;

			default:
				$this->ChooseList();
			break;
		}
		$this->PrintFooter($popup);
	}

	/**
	* ChooseList
	* This prints out the select box which makes you choose a list (to start any subscriber process).
	* If there is only one list, it will automatically redirect you to that particular list (depending on which area you're looking for).
	* Otherwise, it prints out the appropriate template for the area you're working with.
	*
	* @param String $action The area you're working with. This can be manage, export, import, banned and so on.
	* @param String $subaction Which step you're up to in the process.
	*
	* @see GetSession
	* @see Session::Get
	* @see User_API::GetLists
	* @see User_API::CanCreateList
	*
	* @return Void Prints out the appropriate template, doesn't return anything.
	*/
	function ChooseList($action='Manage', $subaction=null)
	{
		$action = strtolower($action);
		$session = &GetSession();
		$user = &GetUser();
		$lists = $user->GetLists();

		$listids = array_keys($lists);

		if (sizeof($listids) < 1) {
			$GLOBALS['Intro'] = GetLang('Subscribers_' . ucwords($action));
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
			$location = 'index.php?Page=Subscribers&Action=' . $action . '&list=' . current($listids);
			if (!is_null($subaction)) {
				$location .= '&SubAction=' . $subaction;
			}

			if (
				$action != 'banned'
				||
				(
					$action == 'banned' &&
					!$session->Get('EmptyBannedSubscriberMessage') &&
					!$session->Get('DeleteBannedSubscriberMessage') &&
					!$user->HasAccess('Lists', 'Global')
				)
			) {
				?>
				<script language="javascript">
					window.location = '<?php echo $location; ?>';
				</script>
				<?php
				exit();
			}
		}

		if ($listname = $session->Get('AddSubscriberMessage')) {
			$GLOBALS['Message'] = $this->PrintSuccess('SubscriberAddSuccessfulList', htmlspecialchars($listname, ENT_QUOTES, SENDSTUDIO_CHARSET));
			$session->Remove('AddSubscriberMessage');
		}

		if ($emptybannedmsg = $session->Get('EmptyBannedSubscriberMessage')) {
			$GLOBALS['Message'] = $this->PrintSuccess('SubscriberBanListEmpty', htmlspecialchars($emptybannedmsg, ENT_QUOTES, SENDSTUDIO_CHARSET));
			$session->Remove('EmptyBannedSubscriberMessage');
		}

		if ($bannedmsg = $session->Get('DeleteBannedSubscriberMessage')) {
			$GLOBALS['Message'] = $bannedmsg;
			$session->Remove('DeleteBannedSubscriberMessage');
		}

		$sortedlist = array();

		if ($action == 'banned') {
			$banned_list = $user->GetBannedLists($listids);

			$banned_listids = array_keys($banned_list);

			if ($user->HasAccess('Lists', 'Global')) {
				$sortedlist['global'] = array('name' => GetLang('Subscribers_GlobalBan'));
			}

			$sortedlist += $lists;

			foreach ($sortedlist as $name => $details) {
				$check_name = $name;
				if ($name == 'global') {
					$check_name = 'g';
				}
				$sortedlist[$name]['bancount'] = 0;
				if (in_array($check_name, $banned_listids)) {
					$sortedlist[$name]['bancount'] = $banned_list[$check_name];
				}
			}
		}

		if ($action != 'banned') {
			if ($action == 'manage') {
				$sortedlist = array('any' => array('name' => GetLang('AnyList')));
			}
			$sortedlist += $lists;
		}

		if ($action == 'manage' || $action == 'export') {
			$show_filtering_options = $user->GetSettings('ShowFilteringOptions');
			if (!$show_filtering_options || $show_filtering_options == 1) {
				$GLOBALS['ShowFilteringOptions'] = ' CHECKED';
			}
		}

		$selectlist = '';
		foreach ($sortedlist as $listid => $listdetails) {
			$subscriber_count = '';
			if (isset($listdetails['bancount'])) {
				if ($listdetails['bancount'] == 1) {
					$subscriber_count = GetLang('Ban_Count_One');
				} else {
					$subscriber_count = sprintf(GetLang('Ban_Count_Many'), $this->FormatNumber($listdetails['bancount']));
				}
			} else {
				if (isset($listdetails['subscribecount'])) {
					if ($listdetails['subscribecount'] == 1) {
						$subscriber_count = GetLang('Subscriber_Count_One');
					} else {
						$subscriber_count = sprintf(GetLang('Subscriber_Count_Many'), $this->FormatNumber($listdetails['subscribecount']));
					}
				}
			}

			if ($listid == 'any') {
				$sel = 'selected';
			} else {
				$sel = '';
			}

			$selectlist .= '<option ' . $sel . ' value="' . $listid . '">' . htmlspecialchars($listdetails['name'], ENT_QUOTES, SENDSTUDIO_CHARSET) . $subscriber_count . '</option>';
		}
		$GLOBALS['SelectList'] = $selectlist;

		switch ($action) {
			case 'search':
				$this->ParseTemplate('Subscriber_Search_Step1');
			break;
			case 'manage':
				$this->ParseTemplate('Subscriber_Manage_Step1');
			break;
			case 'add':
				$this->ParseTemplate('Subscribers_Add_Step1');
			break;
			case 'remove':
				$this->ParseTemplate('Subscribers_Remove_Step1');
			break;
			case 'import':
				$this->ParseTemplate('Subscribers_Import_Step1');
			break;
			case 'export':
				$this->ParseTemplate('Subscribers_Export_Step1');
			break;
			case 'banned':
				$this->ParseTemplate('Subscribers_Banned_Step1');
			break;
		}
	}
}
?>
