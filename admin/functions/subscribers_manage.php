<?php
/**
* This file manages subscribers. It prints out the forms, lets you perform mass-actions and lets you delete subscribers. It also handles paging and so on.
*
* @version     $Id: subscribers_manage.php,v 1.40 2007/05/15 07:03:55 rodney Exp $

*
* @package SendStudio
* @subpackage SendStudio_Functions
*/

/**
* Include the base sendstudio functions.
*/
if (!defined('SENDSTUDIO_BASE_DIRECTORY')) {
	require(dirname(__FILE__) . '/sendstudio_functions.php');
}

/**
* Class for managing subscribers. This only handles subscriber management and mass-actions (eg changing formats, bulk deletion etc). It handles paging, processing, sorting and that's it.
*
* @package SendStudio
* @subpackage SendStudio_Functions
*/
class Subscribers_Manage extends Subscribers
{

	/**
	* ValidSorts
	* An array of valid sort criteria.
	*
	* @var array
	*/
	var $ValidSorts = array('emailaddress', 'format', 'subscribedate', 'confirmed', 'listname');

	/**
	* _DefaultDirection
	* Set the default sort direction to ascending.
	*
	* @var String
	*/
	var $_DefaultDirection = 'asc';

	/**
	* Process
	* Works out what you're trying to do and takes appropriate action.
	* Checks to make sure you have access to manage subscribers before anything else.
	*
	* @param String $action Action to perform. This is usually 'step1', 'step2', 'step3' etc. This gets passed in by the Subscribers::Process function.
	*
	* @see Subscribers::Process
	* @see GetUser
	* @see User_API::HasAccess
	* @see ChooseList
	* @see DeleteSubscribers
	* @see ChangeFormat
	* @see ManageSubscribers_Step2
	* @see ManageSubscribers_Step3
	*
	* @return Void Prints out the step, doesn't return anything.
	*/
	function Process($action=null)
	{
		$user = &GetUser();

		$this->PrintHeader(false, false, false);

		if (!is_null($action)) {
			$action = strtolower($action);
		}

		if ($action == 'processpaging') {
			$perpage = (int)$_GET['PerPageDisplay'];
			$display_settings = array('NumberToShow' => $perpage);
			$user->SetSettings('DisplaySettings', $display_settings);
			$action = 'step3';
		}

		$session = &GetSession();

		switch ($action) {
			case 'change':
				$subaction = strtolower($_POST['ChangeType']);
				$subscriberlist = $_POST['subscribers'];

				switch ($subaction) {
					case 'delete':
						$access = $user->HasAccess('Subscribers', 'Delete');
						if ($access) {
							$this->DeleteSubscribers($subscriberlist);
						} else {
							$GLOBALS['ErrorMessage'] = GetLang('NoAccess');
							$this->ParseTemplate('AccessDenied');
						}
					break;

					case 'changeformat_text':
						$this->ChangeFormat('Text', $subscriberlist);
					break;
					case 'changeformat_html':
						$this->ChangeFormat('HTML', $subscriberlist);
					break;
					case 'changestatus_confirm':
						$this->ChangeConfirm('Confirm', $subscriberlist);
					break;
					case 'changestatus_unconfirm':
						$this->ChangeConfirm('Unconfirm', $subscriberlist);
					break;
				}
				$this->ManageSubscribers_Step3(true);

			break;

			case 'delete':
				$access = $user->HasAccess('Subscribers', 'Delete');
				if ($access) {
					$subscriberids = array();
					if (isset($_GET['id'])) {
						$subscriberids[] = $_GET['id'];
					}
					$this->DeleteSubscribers($subscriberids);
					$this->ManageSubscribers_Step3(true);
				} else {
					$GLOBALS['ErrorMessage'] = GetLang('NoAccess');
					$this->ParseTemplate('AccessDenied');
				}
			break;

			case 'step3':
				$this->ManageSubscribers_Step3();
			break;

			case 'step2':
				$show_filtering_options = (isset($_POST['ShowFilteringOptions'])) ? 1 : 2;
				$user->SetSettings('ShowFilteringOptions', $show_filtering_options);

				$listid = (isset($_POST['list'])) ? $_POST['list'] : $_GET['list'];
				$this->ManageSubscribers_Step2($listid);
			break;

			default:
				$this->ChooseList('Manage', 'Step2');
		}
	}

	/**
	* DeleteSubscribers
	* Deletes subscribers from the list. Goes through the subscribers array (passed in) and deletes them from the list as appropriate.
	*
	* @param Array $subscribers A list of subscriber id's to remove from the list.
	*
	* @see GetApi
	* @see Subscribers_API::DeleteSubscriber
	*
	* @return Void Doesn't return anything. Creates a report and prints that out.
	*/
	function DeleteSubscribers($subscribers=array())
	{
		if (!is_array($subscribers)) {
			$subscribers = array($subscribers);
		}

		if (empty($subscribers)) {
			return array(false, GetLang('NoSubscribersToDelete'));
		}

		if (!isset($GLOBALS['Message'])) {
			$GLOBALS['Message'] = '';
		}

		$session = &GetSession();
		$subscriber_search = $session->Get('Search_Subscribers');
		$list = $subscriber_search['List'];

		$subscribersdeleted = 0;
		$subscribersnotdeleted = 0;
		$SubscriberApi = $this->GetApi('Subscribers');
		foreach ($subscribers as $p => $subscriberid) {
			list($status, $msg) = $SubscriberApi->DeleteSubscriber(false, 0, $subscriberid);
			if ($status) {
				$subscribersdeleted++;
				continue;
			}
			$subscribersnotdeleted++;
		}

		$msg = '';

		if ($subscribersnotdeleted > 0) {
			if ($subscribersnotdeleted == 1) {
				$GLOBALS['Error'] = GetLang('Subscriber_NotDeleted');
			} else {
				$GLOBALS['Error'] = sprintf(GetLang('Subscribers_NotDeleted'), $this->FormatNumber($subscribersnotdeleted));
			}
			$msg .= $this->ParseTemplate('ErrorMsg', true, false);
		}

		if ($subscribersdeleted > 0) {
			if ($subscribersdeleted == 1) {
				$msg .= $this->PrintSuccess('Subscriber_Deleted');
			} else {
				$msg .= $this->PrintSuccess('Subscribers_Deleted', $this->FormatNumber($subscribersdeleted));
			}
		}
		$GLOBALS['Message'] .= $msg;
	}

	/**
	* ChangeFormat
	* Changes subscriber formats to the one chosen for the particular list.
	*
	* @param String $format The format to change the subscribers to.
	* @param Array $subscribers A list of subscriber id's to change for the list.
	* @param Int $listid Listid to change subscribers for.
	*
	* @see GetApi
	* @see Subscribers_API::ChangeSubscriberFormat
	*
	* @return Void Doesn't return anything. Creates a report and prints that out.
	*/
	function ChangeFormat($format='html', $subscribers=array(), $listid=0)
	{
		$format = strtolower($format);
		if (!is_array($subscribers)) {
			$subscribers = array($subscribers);
		}

		if (empty($subscribers)) {
			return array(false, GetLang('NoSubscribersToChangeFormat'));
		}

		$subscriberschanged = 0;
		$subscribersnotchanged = 0;
		$SubscriberApi = $this->GetApi('Subscribers');
		foreach ($subscribers as $p => $subscriberid) {
			list($status, $msg) = $SubscriberApi->ChangeSubscriberFormat($format, $subscriberid);
			if ($status) {
				$subscriberschanged++;
				continue;
			}
			$subscribersnotchanged++;
		}

		$msg = '';

		$format_lang = ($format == 'text') ? 'Format_Text' : 'Format_HTML';

		if ($subscribersnotchanged > 0) {
			if ($subscribersnotchanged == 1) {
				$GLOBALS['Error'] = sprintf(GetLang('Subscriber_NotChangedFormat'), strtolower(GetLang($format_lang)));
			} else {
				$GLOBALS['Error'] = sprintf(GetLang('Subscribers_NotChangedFormat'), $this->FormatNumber($subscribersnotchanged), strtolower(GetLang($format_lang)));
			}
			$msg .= $this->ParseTemplate('ErrorMsg', true, false);
		}

		if ($subscriberschanged > 0) {
			if ($subscriberschanged == 1) {
				$msg .= $this->PrintSuccess('Subscriber_ChangedFormat', strtolower(GetLang($format_lang)));
			} else {
				$msg .= $this->PrintSuccess('Subscribers_ChangedFormat', $this->FormatNumber($subscriberschanged), strtolower(GetLang($format_lang)));
			}
		}
		$GLOBALS['Message'] = $msg;
	}

	/**
	* ChangeStatus
	* Changes subscriber status to the one chosen for the particular list.
	*
	* @param String $newstatus The status to change the subscribers to.
	* @param Array $subscribers A list of subscriber id's to change for the list.
	*
	* @see GetApi
	* @see Subscribers_API::ChangeSubscriberStatus
	*
	* @return Void Doesn't return anything. Creates a report and prints that out.
	*/
	function ChangeStatus($newstatus='active', $subscribers=array())
	{
		$newstatus = strtolower($newstatus);
		if (!is_array($subscribers)) {
			$subscribers = array($subscribers);
		}

		if (empty($subscribers)) {
			return array(false, GetLang('NoSubscribersToChangeStatus'));
		}

		$subscriberschanged = 0;
		$subscribersnotchanged = 0;
		$SubscriberApi = $this->GetApi('Subscribers');
		foreach ($subscribers as $p => $subscriberid) {
			list($status, $msg) = $SubscriberApi->ChangeSubscriberStatus($newstatus, 0, $subscriberid);
			if ($status) {
				$subscriberschanged++;
				continue;
			}
			$subscribersnotchanged++;
		}

		$msg = '';

		$status_lang = ($newstatus == 'active') ? 'Status_Active' : 'Status_Inactive';

		if ($subscribersnotchanged > 0) {
			if ($subscribersnotchanged == 1) {
				$GLOBALS['Error'] = sprintf(GetLang('Subscriber_NotChangedStatus'), strtolower(GetLang($status_lang)));
			} else {
				$GLOBALS['Error'] = sprintf(GetLang('Subscribers_NotChangedStatus'), $this->FormatNumber($subscribersnotchanged), strtolower(GetLang($status_lang)));
			}
			$msg .= $this->ParseTemplate('ErrorMsg', true, false);
		}

		if ($subscriberschanged > 0) {
			if ($subscriberschanged == 1) {
				$msg .= $this->PrintSuccess('Subscriber_ChangedStatus', strtolower(GetLang($status_lang)));
			} else {
				$msg .= $this->PrintSuccess('Subscribers_ChangedStatus', $this->FormatNumber($subscriberschanged), strtolower(GetLang($status_lang)));
			}
		}
		$GLOBALS['Message'] = $msg;
	}

	/**
	* ChangeConfirm
	* Changes subscriber confirmation status to the one chosen for the particular list.
	*
	* @param String $confirmstatus The status to change the subscribers to.
	* @param Array $subscribers A list of subscriber id's to change for the list.
	*
	* @see GetApi
	* @see Subscribers_API::ChangeSubscriberConfirm
	*
	* @return Void Doesn't return anything. Creates a report and prints that out.
	*/
	function ChangeConfirm($confirmstatus='confirm', $subscribers=array())
	{
		$confirmstatus = strtolower($confirmstatus);
		if (!is_array($subscribers)) {
			$subscribers = array($subscribers);
		}

		if (empty($subscribers)) {
			return array(false, GetLang('NoSubscribersToChangeConfirm'));
		}

		$subscriberschanged = 0;
		$subscribersnotchanged = 0;
		$SubscriberApi = $this->GetApi('Subscribers');
		foreach ($subscribers as $p => $subscriberid) {
			list($status, $msg) = $SubscriberApi->ChangeSubscriberConfirm($confirmstatus, 0, $subscriberid);
			if ($status) {
				$subscriberschanged++;
				continue;
			}
			$subscribersnotchanged++;
		}

		$msg = '';

		$status_lang = ($confirmstatus == 'confirm') ? 'Status_Confirmed' : 'Status_Unconfirmed';

		if ($subscribersnotchanged > 0) {
			if ($subscribersnotchanged == 1) {
				$GLOBALS['Error'] = sprintf(GetLang('Subscriber_NotChangedConfirm'), strtolower(GetLang($status_lang)));
			} else {
				$GLOBALS['Error'] = sprintf(GetLang('Subscribers_NotChangedConfirm'), $this->FormatNumber($subscribersnotchanged), strtolower(GetLang($status_lang)));
			}
			$msg .= $this->ParseTemplate('ErrorMsg', true, false);
		}

		if ($subscriberschanged > 0) {
			if ($subscriberschanged == 1) {
				$msg .= $this->PrintSuccess('Subscriber_ChangedConfirm', strtolower(GetLang($status_lang)));
			} else {
				$msg .= $this->PrintSuccess('Subscribers_ChangedConfirm', $this->FormatNumber($subscriberschanged), strtolower(GetLang($status_lang)));
			}
		}
		$GLOBALS['Message'] = $msg;
	}

	/**
	* ManageSubscribers_Step3
	* Prints out the subscribers for the list chosen and criteria selected in steps 1 & 2. This handles sorting, paging and searching. If you are coming in for the first time, it remembers your search criteria in the session. If you change number per page, sorting criteria, it fetches the search criteria from the session again before continuing.
	*
	* @see ManageSubscribers_Step2
	* @see GetApi
	* @see GetSession
	* @see Session::Get
	* @see GetPerPage
	* @see GetCurrentPage
	* @see GetSortDetails
	* @see Subscribers_API::FetchSubscribers
	* @see SetupPaging
	* @see Lists_API::Load
	*
	* @return Void Doesn't return anything. Prints out the results and that's it.
	*/
	function ManageSubscribers_Step3($change=false)
	{
		$subscriber_api = $this->GetApi('Subscribers');
		$session = &GetSession();
		$user = $session->Get('UserDetails');
		$search_info = $session->Get('Search_Subscribers');

		if (!isset($GLOBALS['Message'])) {
			$GLOBALS['Message'] = '';
		}

		// if we are posting a form, we are most likely resetting the search criteria.
		$resetsearch = (isset($_POST) && !empty($_POST)) ? true : false;

		// except if we're changing paging!
		if (isset($_GET['SubAction'])) {
			$subaction =  strtolower($_GET['SubAction']);
			if ($subaction == 'processpaging' || $subaction == 'change') {
				$resetsearch = false;
			}
		}

		if (!$search_info || $resetsearch) {
			$search_details = array();
			if (isset($_POST['emailaddress']) && $_POST['emailaddress'] != '') {
				$search_details['Email'] = $_POST['emailaddress'];
			}

			if (isset($_POST['format']) && $_POST['format'] != '-1') {
				$search_details['Format'] = $_POST['format'];
			}

			if (isset($_POST['confirmed']) && $_POST['confirmed'] != '-1') {
				$search_details['Confirmed'] = $_POST['confirmed'];
			}

			if (isset($_POST['status']) && $_POST['status'] != '-1') {
				$search_details['Status'] = $_POST['status'];
			}

			if (isset($_POST['datesearch']) && isset($_POST['datesearch']['filter'])) {
				$search_details['DateSearch'] = $_POST['datesearch'];

				$search_details['DateSearch']['StartDate'] = AdjustTime(array(0, 0, 1, $_POST['datesearch']['mm_start'], $_POST['datesearch']['dd_start'], $_POST['datesearch']['yy_start']));

				$search_details['DateSearch']['EndDate'] = AdjustTime(array(0, 0, 1, $_POST['datesearch']['mm_end'], $_POST['datesearch']['dd_end'], $_POST['datesearch']['yy_end']));
			}

			$customfields = array();
			if (isset($_POST['CustomFields']) && !empty($_POST['CustomFields'])) {
				$customfields = $_POST['CustomFields'];
			}

			$search_details['CustomFields'] = $customfields;

			$search_details['List'] = $_GET['List'];

			if ($search_details['List'] == 'any') {
				if (!$user->ListAdmin()) {
					$lists = $user->GetLists();
					$search_details['AvailableLists'] = array_keys($lists);
				}
			} else {
				$search_details['List'] = (int)$search_details['List'];
			}

			if (isset($_POST['clickedlink']) && isset($_POST['linkid'])) {
				$search_details['Link'] = $_POST['linkid'];
			}

			if (isset($_POST['openednewsletter']) && isset($_POST['newsletterid'])) {
				$search_details['Newsletter'] = $_POST['newsletterid'];
			}

			$session->Set('Search_Subscribers', $search_details);
		}

		$search_info = $session->Get('Search_Subscribers');

		$GLOBALS['List'] = $search_info['List'];

		$perpage = $this->GetPerPage();
		$pageid = $this->GetCurrentPage();

		$sortinfo = $this->GetSortDetails();

		$subscriber_list = $subscriber_api->FetchSubscribers($pageid, $perpage, $search_info, $sortinfo);

		$subscriber_edited = (isset($_GET['Edit'])) ? true : false;

		$totalsubscribers = $subscriber_list['count'];
		unset($subscriber_list['count']);
		if ($totalsubscribers < 1) {
			if (!$change && !$subscriber_edited) {
				$this->ManageSubscribers_Step2($search_info['List'], GetLang('NoSubscribersMatch'));
			} else {
				if ($subscriber_edited) {
					$GLOBALS['Message'] .= $this->PrintSuccess('SubscriberEditSuccess');
				}
				$this->ParseTemplate('Subscribers_Manage_Empty');
			}
			return;
		}

		if ($subscriber_edited) {
			$GLOBALS['Message'] .= $this->PrintSuccess('SubscriberEditSuccess');
		}

		$GLOBALS['TotalSubscriberCount'] = $this->FormatNumber($totalsubscribers);
		if ($totalsubscribers == 1) {
			$GLOBALS['Message'] .= $this->PrintSuccess('Subscribers_FoundOne');
		} else {
			$GLOBALS['Message'] .= $this->PrintSuccess('Subscribers_FoundMany', $GLOBALS['TotalSubscriberCount']);
		}

		$DisplayPage = $pageid;
		$start = ($DisplayPage - 1) * $perpage;

		$GLOBALS['PAGE'] = 'Subscribers&Action=Manage&SubAction=Step3&List=' . $search_info['List'];
		$this->SetupPaging($totalsubscribers, $DisplayPage, $perpage);
		$GLOBALS['FormAction'] = 'SubAction=ProcessPaging';
		$paging = $this->ParseTemplate('Paging', true, false);

		$subscriberdetails = '';

		if (is_numeric($search_info['List'])) {
			$ListApi = $this->GetApi('Lists');
			$ListApi->Load($search_info['List']);
			$listname = $ListApi->name;
			$GLOBALS['SubscribersManage'] = sprintf(GetLang('SubscribersManage'), htmlspecialchars($listname, ENT_QUOTES, SENDSTUDIO_CHARSET));

			$template = $this->ParseTemplate('Subscribers_Manage', true, false);
			$subscriber_row_template = 'Subscribers_Manage_Row';
		} else {
			$GLOBALS['SubscribersManage'] = GetLang('SubscribersManageAnyList');
			$template = $this->ParseTemplate('Subscribers_Manage_AnyList', true, false);
			$subscriber_row_template = 'Subscribers_Manage_AnyList_Row';
		}
		$GLOBALS['List'] = $search_info['List'];

		foreach ($subscriber_list['subscriberlist'] as $pos => $subscriberinfo) {
			$GLOBALS['Email'] = $subscriberinfo['emailaddress'];
			$GLOBALS['SubscribeDate'] = $this->PrintDate($subscriberinfo['subscribedate']);
			$GLOBALS['SubscriberFormat'] = ($subscriberinfo['format'] == 't') ? GetLang('Format_Text') : GetLang('Format_HTML');
			$GLOBALS['EditSubscriberID'] = $subscriberinfo['subscriberid'];
			$GLOBALS['SubscriberConfirmed'] = ($subscriberinfo['confirmed'] == '1') ? GetLang('Confirmed') : GetLang('Unconfirmed');

			// if we are searching "any" list then we need to adjust the link.
			if (isset($subscriberinfo['listid'])) {
				$GLOBALS['List'] = $subscriberinfo['listid'];
			}

			$GLOBALS['subscriberid'] = $subscriberinfo['subscriberid'];

			if (isset($subscriberinfo['listname'])) {
				$GLOBALS['MailingListName'] = htmlspecialchars($subscriberinfo['listname'], ENT_QUOTES, SENDSTUDIO_CHARSET);
			}

			$status = GetLang('Active');

			if ($subscriberinfo['unsubscribed'] > 0) {
				$status = GetLang('Unsubscribed');
			}

			if ($subscriberinfo['bounced'] > 0) {
				$status = GetLang('Bounced');
			}

			$GLOBALS['SubscriberStatus'] = $status;

			$GLOBALS['SubscriberAction'] = $this->ParseTemplate('Subscribers_Manage_EditLink', true, false);
			if ($user->HasAccess('Subscribers', 'Delete')) {
				$GLOBALS['DeleteSubscriberID'] = $subscriberinfo['subscriberid'];
				$GLOBALS['SubscriberAction'] .= $this->ParseTemplate('Subscribers_Manage_DeleteLink', true, false);
			}
			$GLOBALS['SubscriberID'] = $subscriberinfo['subscriberid'];
			$subscriberdetails .= $this->ParseTemplate($subscriber_row_template, true, false);
		}

		$template = str_replace('%%TPL_' . $subscriber_row_template . '%%', $subscriberdetails, $template);
		$template = str_replace('%%TPL_Paging%%', $paging, $template);
		$template = str_replace('%%TPL_Paging_Bottom%%', $GLOBALS['PagingBottom'], $template);

		echo $template;
	}

	/**
	* ManageSubscribers_Step2
	* Prints out the search forms to restrict the subscribers you want to see. This includes custom fields, format and so on.
	*
	* @param Int $listid Which list we are managing subscribers for.
	* @param Mixed $msg If there is a message (eg "no subscribers found"), it is passed in for display.
	*
	* @see GetApi
	* @see Lists_API::Load
	* @see Lists_API::GetListFormat
	* @see Lists_API::GetCustomFields
	* @see Search_Display_CustomField
	*
	* @return Void Doesn't return anything. Prints the search form and that's it.
	*/
	function ManageSubscribers_Step2($listid=0, $msg=false)
	{
		$user = &GetUser();

		$user_lists = $user->GetLists();

		$access = $user->HasAccess('Subscribers', 'Manage');

		if (!$access) {
			$this->DenyAccess();
			return;
		}

		if ($msg) {
			$GLOBALS['Error'] = $msg;
			$GLOBALS['Message'] = $this->ParseTemplate('ErrorMsg', true, false);
		}

		$GLOBALS['List'] = $listid;
		$GLOBALS['Heading'] = GetLang('Subscribers_Manage');
		$GLOBALS['FormAction'] = 'Manage';

		$listApi = $this->GetApi('Lists');
		$listApi->Load($listid);

		$format_either  = '<option value="-1">' . GetLang('Either_Format') . '</option>';
		$format_html    = '<option value="h">' . GetLang('Format_HTML') . '</option>';
		$format_text    = '<option value="t">' . GetLang('Format_Text') . '</option>';

		if (is_numeric($listid)) {
			$listformat = $listApi->GetListFormat();
			switch ($listformat) {
				case 'h':
					$format = $format_html;
				break;
				case 't':
					$format = $format_text;
				break;
				default:
					$format = $format_either . $format_html . $format_text;
			}
		} else {
			$format = $format_either . $format_html . $format_text;
		}

		$session = &GetSession();
		$session->Remove('LinksForList');
		if (is_numeric($listid)) {
			$session->Set('LinksForList', $listid);
		}

		$GLOBALS['FormatList'] = $format;

		$customfields = $listApi->GetCustomFields($listid);

		$this->PrintSubscribeDate();

		if (!empty($customfields)) {
			$customfield_display = $this->ParseTemplate('Subscriber_Manage_Step2_CustomFields', true, false);
			foreach ($customfields as $pos => $customfield_info) {
				$manage_display = $this->Search_Display_CustomField($customfield_info);
				$customfield_display .= $manage_display;
			}
			$GLOBALS['CustomFieldInfo'] = $customfield_display;
		}
		$this->ParseTemplate('Subscriber_Manage_Step2');

		if (sizeof(array_keys($user_lists)) == 1) {
			return;
		}

		if (!$msg && $user->GetSettings('ShowFilteringOptions') == 2) {
			?>
			<script>
				document.forms[0].submit();
			</script>
			<?php
			exit();
		}
	}
}
?>
