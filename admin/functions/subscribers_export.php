<?php
/**
* This file handles exporting of subscribers only.
*
* @version     $Id: subscribers_export.php,v 1.36 2007/05/15 07:03:55 rodney Exp $

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
* Class for exporting subscribers.
*
* @package SendStudio
* @subpackage SendStudio_Functions
*/
class Subscribers_Export extends Subscribers
{

	/**
	* PerRefresh
	* Number of subscribers to export per refresh.
	*
	* @var Int
	*/
	var $PerRefresh = 100;

	/**
	* _customfields_loaded
	* An array of custom field objects that have been loaded. This is filled up on the fly once per refresh - each custom field is added to the array once.
	*
	* @see ExportSubscriber
	*/
	var $_customfields_loaded = array();

	/**
	* Process
	* Works out what you're trying to do and takes appropriate action.
	* Uses a 'queue' system to export subscribers, and then removes them as it exports the subscriber.
	*
	* @param String $action Action to perform. This is usually 'step1', 'step2', 'step3' etc. This gets passed in by the Subscribers::Process function.
	*
	* @see Subscribers::Process
	* @see GetUser
	* @see ChooseList
	* @see ExportSubscribers_Step2
	* @see ExportSubscribers_Step3
	* @see ExportSubscribers_Step4
	* @see GetApi
	* @see API::ClearQueue
	* @see API::RemoveFromQueue
	* @see API::FetchFromQueue
	* @see PrintStatusReport
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

		$session = &GetSession();

		switch ($action) {
			case 'step2':
				$show_filtering_options = (isset($_POST['ShowFilteringOptions'])) ? 1 : 2;
				$user->SetSettings('ShowFilteringOptions', $show_filtering_options);

				$listid = (isset($_POST['list'])) ? (int)$_POST['list'] : $_GET['list'];
				$exportinfo = array();
				$exportinfo['List'] = $listid;
				$session->Set('ExportInfo', $exportinfo);
				$this->ExportSubscribers_Step2($listid);
			break;

			case 'step3':
				$this->ExportSubscribers_Step3();
			break;

			case 'step4':
				$this->ExportSubscribers_Step4();
			break;

			case 'export':
				$exportinfo = $session->Get('ExportInfo');
				$queueid = $exportinfo['ExportQueue'];
				$subscriber_api = $this->GetApi('Subscribers');

				$pageid = $this->GetCurrentPage();

				$subscriber_Api = $this->GetApi('Subscribers');
				$subscriber_list = $subscriber_Api->FetchFromQueue($queueid, 'export', $pageid, $this->PerRefresh);

				$ExportsCompleted = $exportinfo['ExportsCompleted'];

				if (!empty($subscriber_list)) {
					$this->PrintStatusReport();

					foreach ($subscriber_list as $pos => $subscriberid) {
						$this->ExportSubscriber($subscriberid);
						$ExportsCompleted++;
					}

					$subscriber_Api->MarkAsProcessed($queueid, 'export', $subscriber_list);

					$exportinfo['ExportsCompleted'] = $ExportsCompleted;

					$session->Set('ExportInfo', $exportinfo);

					$this->PrintFooter(true);
					?>
						<script language="javascript">
							setTimeout('window.location="index.php?Page=Subscribers&Action=Export&SubAction=Export"', 2);
						</script>
					<?php
					exit();
				}

				$this->PrintFooter(true);

				?>
				<script language="javascript">
					window.opener.document.location = 'index.php?Page=Subscribers&Action=Export&SubAction=PrintReport';
					window.opener.focus();
					window.close();
				</script>
				<?php
				exit();
			break;

			case 'printreport':
				$exportinfo = $session->Get('ExportInfo');
				$queueid = $exportinfo['ExportQueue'];

				$api = $this->GetApi('Subscribers');
				if ($queueid) {
					$api->ClearQueue($queueid, 'export');
				}

				$exportlink = SENDSTUDIO_TEMP_URL . '/' . $exportinfo['ExportFile'];
				$GLOBALS['Message'] = $this->PrintSuccess('ExportResults_Intro', $exportlink);

				$this->ParseTemplate('Subscribers_Export_Results_Report');
			break;

			default:
				$api = $this->GetApi('Subscribers');
				$exportinfo = $session->Get('ExportInfo');

				// clean up the old queue and export file if it didn't complete properly before.
				if ($exportinfo && is_array($exportinfo)) {
					if (isset($exportinfo['ExportQueue'])) {
						$queueid = $exportinfo['ExportQueue'];
						if ($queueid) {
							$api->ClearQueue($queueid, 'export');
						}
					}
					if (isset($exportinfo['ExportFile'])) {
						$exportfile = $exportinfo['ExportFile'];
						if (is_file($exportfile)) {
							unlink(TEMP_DIRECTORY . '/' . $exportfile);
						}
					}
				}

				$session->Remove('ExportInfo');
				$this->ChooseList('Export', 'Step2');
		}
	}

	/**
	* ExportSubscribers_Step2
	* Prints out the 'search' form to restrict which subscribers you are going to export.
	*
	* @param Int $listid Which list you are going to export subscribers from.
	* @param String $msg If there is a message (eg no subscribers for your search criteria), then it is passed in so it can be displayed above the search form.
	*
	* @see GetApi
	* @see Lists_API::Load
	* @see Lists_API::GetListFormat
	* @see Lists_API::GetCustomFields
	* @see Search_Display_CustomField
	*
	* @return Void Prints out the form, doesn't return anything.
	*/
	function ExportSubscribers_Step2($listid=0, $msg=false)
	{
		$user = &GetUser();
		$access = $user->HasAccess('Subscribers', 'Export');
		if (!$access) {
			$this->DenyAccess();
			return;
		}

		$user_lists = $user->GetLists();

		if ($msg) {
			$GLOBALS['Error'] = $msg;
			$GLOBALS['Message'] = $this->ParseTemplate('ErrorMsg', true, false);
		}

		$GLOBALS['List'] = $listid;

		$GLOBALS['Heading'] = GetLang('Subscribers_Export');
		$GLOBALS['FormAction'] = 'Export';

		$listApi = $this->GetApi('Lists');
		$listApi->Load($listid);

		$confirmed  = '<option value="-1">' . GetLang('Either_Confirmed') . '</option>';
		$confirmed .= '<option value="1">'  . GetLang('Confirmed') . '</option>';
		$confirmed .= '<option value="0">'  . GetLang('Unconfirmed') . '</option>';

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
				$format = $format_either . $format_html . $format_text;
		}

		$GLOBALS['ConfirmedList'] = $confirmed;
		$GLOBALS['FormatList'] = $format;

		$this->PrintSubscribeDate();

		$list_api = $this->GetApi('Lists');
		$customfields = $list_api->GetCustomFields($listid);

		if (!empty($customfields)) {
			$customfield_display = $this->ParseTemplate('Subscriber_Search_Step2_CustomFields', true, false);
			foreach ($customfields as $pos => $customfield_info) {
				$manage_display = $this->Search_Display_CustomField($customfield_info);
				$customfield_display .= $manage_display;
			}
			$GLOBALS['CustomFieldInfo'] = $customfield_display;
		}
		$this->ParseTemplate('Subscriber_Search_Step2');

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

	/**
	* ExportSubscribers_Step3
	* Checks that there are subscribers to export. Creates a 'queue' of subscribers to export and lets you choose which fields you want to export.
	*
	* @see GetApi
	* @see GetSession
	* @see Session::Get
	* @see Lists_API::Load
	* @see Lists_API::GetCustomFields
	* @see GetSortDetails
	* @see Subscribers_API::GetSubscribers
	* @see ExportSubscribers_Step2
	* @see API::CreateQueue
	*
	* @return Void Prints out the form, doesn't return anything.
	*/
	function ExportSubscribers_Step3()
	{
		$subscriber_api = $this->GetApi('Subscribers');
		$session = &GetSession();
		$user = $session->Get('UserDetails');
		$exportinfo = $session->Get('ExportInfo');

		$listApi = $this->GetApi('Lists');
		$listid = $exportinfo['List'];
		$CustomFieldsList = $listApi->GetCustomFields($listid);

		if (!$exportinfo || !empty($_POST)) {
			$export_details = array();
			if (isset($_POST['emailaddress']) && $_POST['emailaddress'] != '') {
				$export_details['Email'] = $_POST['emailaddress'];
			}

			if (isset($_POST['format']) && $_POST['format'] != '-1') {
				$export_details['Format'] = $_POST['format'];
			}

			if (isset($_POST['confirmed']) && $_POST['confirmed'] != '-1') {
				$export_details['Confirmed'] = $_POST['confirmed'];
			}

			if (isset($_POST['status']) && $_POST['status'] != '-1') {
				$export_details['Status'] = $_POST['status'];
			}

			if (isset($_POST['datesearch']) && isset($_POST['datesearch']['filter'])) {
				$export_details['DateSearch'] = $_POST['datesearch'];

				$export_details['DateSearch']['StartDate'] = AdjustTime(array(0, 0, 1, $_POST['datesearch']['mm_start'], $_POST['datesearch']['dd_start'], $_POST['datesearch']['yy_start']));

				$export_details['DateSearch']['EndDate'] = AdjustTime(array(0, 0, 1, $_POST['datesearch']['mm_end'], $_POST['datesearch']['dd_end'], $_POST['datesearch']['yy_end']));
			}

			$customfields = array();
			if (isset($_POST['CustomFields']) && !empty($_POST['CustomFields'])) {
				$customfields = $_POST['CustomFields'];
			}

			if (isset($_POST['clickedlink']) && isset($_POST['linkid'])) {
				$export_details['Link'] = $_POST['linkid'];
			}

			if (isset($_POST['openednewsletter']) && isset($_POST['newsletterid'])) {
				$export_details['Newsletter'] = $_POST['newsletterid'];
			}

			$export_details['CustomFields'] = $customfields;

			$export_details['List'] = $_GET['List'];

			$exportinfo['ExportDetails'] = $export_details;

			$exportinfo['ExportsCompleted'] = 0;

			$exportinfo['ExportFile'] = 'export-'. md5(uniqid(rand(), true) . SENDSTUDIO_LICENSEKEY) . '.csv';

			touch(TEMP_DIRECTORY . '/' . $exportinfo['ExportFile']);
			chmod(TEMP_DIRECTORY . '/' . $exportinfo['ExportFile'], 0644);

			$session->Set('ExportInfo', $exportinfo);
		}

		$exportinfo = $session->Get('ExportInfo');
		$export_details = $exportinfo['ExportDetails'];

		if (isset($exportinfo['ExportQueue'])) {
			$queueid = $exportinfo['ExportQueue'];
			if ($queueid) {
				$subscriber_api->ClearQueue($queueid, 'export');
			}
		}
		$exportqueue = $subscriber_api->CreateQueue('Export');

		$queueinfo = array('queueid' => $exportqueue, 'queuetype' => 'export', 'ownerid' => $user->userid);

		$subscriber_list = $subscriber_api->GetSubscribers($export_details, array(), false, $queueinfo);

		$totalsubscribers = $subscriber_list['count'];
		unset($subscriber_list['count']);
		if ($totalsubscribers < 1) {
			$this->ExportSubscribers_Step2($_GET['List'], GetLang('NoSubscribersMatch'));
			return;
		}

		$exportinfo['ExportQueue'] = $exportqueue;
		$exportinfo['QueueSize'] = $totalsubscribers;
		$session->Set('ExportInfo', $exportinfo);

		$GLOBALS['TotalSubscriberCount'] = $this->FormatNumber($totalsubscribers);
		if ($totalsubscribers == 1) {
			$GLOBALS['Message'] = $this->PrintSuccess('Subscribers_Export_FoundOne');
		} else {
			$GLOBALS['Message'] = $this->PrintSuccess('Subscribers_Export_FoundMany', $GLOBALS['TotalSubscriberCount']);
		}

		$all_options = array('e' => GetLang('EmailAddress'), 'f' => GetLang('Format'), 'c' => GetLang('Confirmed'), 'mdy' => GetLang('SubscribeDate_MDY'));

		foreach ($CustomFieldsList as $pos => $details) {
			$all_options[$details['fieldid']] = $details['name'];
		}

		$all_options['n'] = GetLang('None');

		$fieldoptions = '';

		$fieldcount = sizeof($all_options) - 1;
		for ($i = 1; $i <= $fieldcount; $i++) {
			$GLOBALS['FieldName'] = sprintf(GetLang('ExportField'), $i);
			$GLOBALS['OptionName'] = 'fieldoption[' . $i . ']';
			$optionlist = '';
			$fcount = 1;
			foreach ($all_options as $id => $name) {
				$optionlist .= '<option value="' . $id . '"';
				if ($fcount == $i) {
					$optionlist .= ' SELECTED';
				}

				$optionlist .= '>' . htmlspecialchars($name, ENT_QUOTES, SENDSTUDIO_CHARSET) . '</option>';
				if ($id == 'mdy') {
					$optionlist .= '<option value="dmy">' . GetLang('SubscribeDate_DMY') . '</option>';
					$optionlist .= '<option value="ymd">' . GetLang('SubscribeDate_YMD') . '</option>';
				}
				$fcount++;
			}
			$GLOBALS['OptionList'] = $optionlist;
			$fieldoptions .= $this->ParseTemplate('Subscribers_Export_Step3_Options', true, false);
		}
		$GLOBALS['FieldOptions'] = $fieldoptions;

		$this->ParseTemplate('Subscribers_Export_Step3');
	}

	/**
	* ExportSubscribers_Step4
	* Prints out the export header (if required) and creates the export file. This is the last step before exports happen.
	*
	* @see GetApi
	* @see GetSession
	* @see Session::Get
	* @see CustomFields_API::Load
	* @see CustomFields_API::GetFieldName
	*
	* @return Void Prints out the form, doesn't return anything.
	*/
	function ExportSubscribers_Step4()
	{
		$session = &GetSession();
		$exportinfo = $session->Get('ExportInfo');

		$exportsettings = array();
		$exportsettings['Headers'] = $_POST['includeheader'];
		$exportsettings['FieldSeparator'] = $_POST['fieldseparator'];
		$exportsettings['FieldEnclosedBy'] = $_POST['fieldenclosedby'];
		$exportsettings['FieldOptions'] = $_POST['fieldoption'];

		$exportinfo['Settings'] = $exportsettings;
		$session->Set('ExportInfo', $exportinfo);

		$queuesize = $exportinfo['QueueSize'];

		if ($queuesize == 1) {
			$GLOBALS['SubscribersReport'] = GetLang('ExportSummary_FoundOne');
		} else {
			$GLOBALS['SubscribersReport'] = sprintf(GetLang('ExportSummary_FoundMany'), $this->FormatNumber($queuesize));
		}

		$exportfile = $exportinfo['ExportFile'];

		if (is_file(TEMP_DIRECTORY . '/'. $exportinfo['ExportFile'])) {
			unlink(TEMP_DIRECTORY . '/'. $exportinfo['ExportFile']);
		}

		$customfields_Api = $this->GetApi('CustomFields');

		if ($exportsettings['Headers']) {
			$parts = array();
			foreach ($exportsettings['FieldOptions'] as $pos => $type) {
				switch (strtolower($type)) {
					case 'n':
						continue;
					break;
					case 'e':
						$parts[] = GetLang('EmailAddress');
					break;
					case 'f':
						$parts[] = GetLang('Format');
					break;
					case 'c':
						$parts[] = GetLang('Confirmed');
					break;
					case 'dmy':
						$parts[] = GetLang('SubscribeDate_DMY');
					break;
					case 'mdy':
						$parts[] = GetLang('SubscribeDate_MDY');
					break;
					case 'ymd':
						$parts[] = GetLang('SubscribeDate_YMD');
					break;
					default:
						if (is_numeric($type)) {
							$customfields_Api->Load($type);
							$parts[] = $customfields_Api->GetFieldName();
						}
				}
			}

			if ($exportsettings['FieldEnclosedBy'] != '') {
				$line = '';
				foreach ($parts as $p => $part) {
					// To escape a field enclosure inside a field we double it up
					$part = str_replace($exportsettings['FieldEnclosedBy'], $exportsettings['FieldEnclosedBy'].$exportsettings['FieldEnclosedBy'], $part);
					$line .= $exportsettings['FieldEnclosedBy'] . $part . $exportsettings['FieldEnclosedBy'] . $exportsettings['FieldSeparator'];
				}
				$line = substr($line, 0, -1);
			} else {
				$line = implode($exportsettings['FieldSeparator'], $parts);
			}

			$line .= "\n";

			$fp = fopen(TEMP_DIRECTORY . '/' . $exportinfo['ExportFile'], 'a');
			fputs($fp, $line, strlen($line));
			fclose($fp);
		}

		$this->ParseTemplate('Subscribers_Export_Step4');
	}

	/**
	* PrintStatusReport
	* Prints out the status report of how many subscribers have been exported, how many to go.
	*
	* @return Void Prints out the report, doesn't return anything.
	*/
	function PrintStatusReport()
	{
		$session = &GetSession();
		$exportinfo = $session->Get('ExportInfo');

		$GLOBALS['ExportResults_Message'] = sprintf(GetLang('ExportResults_InProgress_Message'), $this->FormatNumber($exportinfo['QueueSize']));

		$GLOBALS['Report'] = sprintf(GetLang('ExportResults_InProgress_Status'), $this->FormatNumber($exportinfo['ExportsCompleted']), $this->FormatNumber($exportinfo['QueueSize']));

		$this->ParseTemplate('Subscribers_Export_ReportProgress');
	}

	/**
	* ExportSubscriber
	* Actually does the exporting of the subscriber. Gets what it needs to export from the session, prints out the subscriber info to the export file.
	*
	* @param Int $subscriberid The subscriber to export.
	*
	* @see GetSession
	* @see Session::Get
	* @see GetApi
	* @see Subscribers_API::LoadSubscriberList
	* @see Subscribers_API::GetCustomFieldSettings
	* @see Subscribers_API::GetCustomFieldData
	*
	* @return Void Exports the subscriber information to the export file.
	*/
	function ExportSubscriber($subscriberid=0)
	{
		$session = &GetSession();
		$exportinfo = $session->Get('ExportInfo');

		$list = $exportinfo['List'];
		$exportfile = $exportinfo['ExportFile'];
		$exportsettings = $exportinfo['Settings'];

		$subscriberApi = $this->GetApi('Subscribers');
		$subscriberinfo = $subscriberApi->LoadSubscriberList($subscriberid, $list);

		$CustomFieldApi = $this->GetApi('CustomFields');

		$parts = array();
		foreach ($exportsettings['FieldOptions'] as $pos => $type) {
			switch (strtolower($type)) {
				case 'n':
					continue;
				break;

				case 'e':
					$parts[] = $subscriberinfo['emailaddress'];
				break;

				case 'f':
					$parts[] = ($subscriberinfo['format'] == 'h') ? GetLang('Format_HTML') : GetLang('Format_Text');
				break;

				case 'c':
					$parts[] = ($subscriberinfo['confirmed']) ? GetLang('Confirmed') : GetLang('Unconfirmed');
				break;

				case 'dmy':
					$parts[] = AdjustTime($subscriberinfo['subscribedate'], false, 'd/m/Y');
				break;

				case 'mdy':
					$parts[] = AdjustTime($subscriberinfo['subscribedate'], false, 'm/d/Y');
				break;

				case 'ymd':
					$parts[] = AdjustTime($subscriberinfo['subscribedate'], false, 'Y/m/d');
				break;

				default:
					if (is_numeric($type)) {
						$customfield_data = $subscriberApi->GetCustomFieldSettings($type, true);

						if ($customfield_data['fieldtype'] == 'checkbox') {
							/**
							* See if we have loaded this custom field yet or not.
							* If we haven't then load it up.
							*
							* If we have, then use that instead for the checkdata calls.
							*
							* Doing it this way saves a lot of db queries/overhead especially with lots of custom fields.
							*/
							$fieldid = $customfield_data['fieldid'];

							if (!in_array($fieldid, array_keys($this->_customfields_loaded))) {
								$field_options = $CustomFieldApi->Load($fieldid, false, true);
								$subfield = $CustomFieldApi->LoadSubField($field_options);
								$this->_customfields_loaded[$fieldid] = $subfield;
							}

							$subf = $this->_customfields_loaded[$fieldid];
							$customfield_data['data'] = $subf->GetRealValue($customfield_data['data']);
						}

						if (!isset($customfield_data['data'])) {
							$parts[] = '';
							continue;
						}

						$customfield_data = $customfield_data['data'];

						if (!is_array($customfield_data)) {
							if (substr_count($customfield_data, $exportsettings['FieldSeparator']) > 0) {
								if ($exportsettings['FieldEnclosedBy'] == '') {
									$customfield_data = '"' . $customfield_data . '"';
								}
							}

							$parts[] = $customfield_data;
							continue;
						}

						if ($exportsettings['FieldEnclosedBy'] != '') {
							$customfield_sanitized = implode(',', $customfield_data);
						} else {
							if (sizeof($customfield_data) > 1) {
								$customfield_sanitized = '"' . implode(',', $customfield_data) . '"';
							} else {
								$customfield_sanitized = implode(',', $customfield_data);

								if (substr_count($customfield_sanitized, $exportsettings['FieldSeparator']) > 0) {
									$customfield_sanitized = '"' . $customfield_sanitized . '"';
								}
							}
						}
						$parts[] = $customfield_sanitized;
					}
				break;
			}
		}

		if ($exportsettings['FieldEnclosedBy'] != '') {
			$line = '';
			foreach ($parts as $p => $part) {
				// To escape a field enclosure inside a field we double it up
				$part = str_replace($exportsettings['FieldEnclosedBy'], $exportsettings['FieldEnclosedBy'].$exportsettings['FieldEnclosedBy'], $part);
				$line .= $exportsettings['FieldEnclosedBy'] . $part . $exportsettings['FieldEnclosedBy'] . $exportsettings['FieldSeparator'];
			}
			$line = substr($line, 0, -1);
		} else {
			$line = implode($exportsettings['FieldSeparator'], $parts);
		}

		$line .= "\n";

		$fp = fopen(TEMP_DIRECTORY . '/' . $exportinfo['ExportFile'], 'a');
		fputs($fp, $line, strlen($line));
		fclose($fp);
	}

}
?>
