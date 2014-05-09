<?php
/**
* This file handles editing of subscribers only. This lets you edit and save only. Management is handled elsewhere.
*
* @version     $Id: subscribers_edit.php,v 1.33 2007/05/15 07:03:55 rodney Exp $

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
* Class for editing a subscriber.
*
* @package SendStudio
* @subpackage SendStudio_Functions
*/
class Subscribers_Edit extends Subscribers
{

	/**
	* Process
	* Works out what you're trying to do and takes the appropriate action. Passes off processing to other functions.
	*
	* @param String $action The subaction or area you're working in.
	*
	* @see GetApi
	* @see Subscribers_API::IsDuplicate
	* @see Subscribers_API::UpdateEmailAddress
	* @see Subscribers_API::SaveSubscriberCustomField
	* @see Lists_API::GetCustomFields
	* @see CustomFields_API::Load
	* @see CustomFields_API::ValidData
	*
	* @return Void Prints out the step, doesn't return anything.
	*/
	function Process($action=null)
	{
		switch ($action) {
			case 'save':
				$listid = (isset($_GET['List'])) ? (int)$_GET['List'] : 0;
				$subscriberid = (int)$_POST['subscriberid'];

				$subscriber = $this->GetApi('Subscribers');
				$duplicate = $subscriber->IsDuplicate($_POST['emailaddress'], $listid, $subscriberid);

				if ($duplicate) {
					$msg = sprintf(GetLang('SubscriberEditFail_Duplicate'), $_POST['emailaddress']);
					$this->EditSubscriber($listid, $subscriberid, 'Error', $msg);
					break;
				}

				foreach (array('emailaddress', 'format', 'confirmed') as $p => $area) {
					$subscriber->Set($area, $_POST[$area]);
				}

				$status = $_POST['status'];
				if ($status == 'u') {
					if (!$subscriber->IsUnSubscriber($_POST['emailaddress'], $listid)) {
						$subscriber->UnsubscribeSubscriber($_POST['emailaddress'], $listid);
					}
				}
				if ($status == 'b') {
					if (!$subscriber->IsBounceSubscriber($_POST['emailaddress'], $listid)) {
						$subscriber->BounceSubscriber($_POST['emailaddress'], $listid);
					}
				}

				if ($status == 'a') {
					$subscriber->ActivateSubscriber($_POST['emailaddress'], $listid);
				}

				$subscriber->UpdateEmailAddress($subscriberid);

				$updateresult = $subscriber->UpdateList($subscriberid, $listid);

				if (!$updateresult) {
					$msg = GetLang('SubscriberEditFail');
					$this->EditSubscriber($listid, $subscriberid, 'Error', $msg);
					break;
				}

				$ListApi = $this->GetApi('Lists');
				$ListCustomFields = $ListApi->GetCustomFields($listid);

				$CustomFieldApi = $this->GetApi('CustomFields');

				$customfield_errors = array();

				foreach ($ListCustomFields as $pos => $data) {
					$fieldid = $data['fieldid'];
					$CustomFieldApi->Load($fieldid);
					$postdata = (isset($_POST['CustomFields'][$fieldid])) ? $_POST['CustomFields'][$fieldid] : '';

					if (!isset($_POST['CustomFields'][$fieldid]) && !$CustomFieldApi->IsRequired()) {
						continue;
					}

					if ($CustomFieldApi->IsRequired()) {
						if (!$postdata) {
							$ftype = $CustomFieldApi->Get('fieldtype');
							switch($ftype) {
								case 'text':
								case 'number':
									$errormsg = 'SubscriberAddFail_EmptyData_EnterData';
								break;

								case 'dropdown':
								case 'radiobutton':
								case 'checkbox':
								case 'date':
									$errormsg = 'SubscriberAddFail_EmptyData_ChooseOption';
								break;
							}

							$customfield_errors[] = sprintf(GetLang($errormsg), $CustomFieldApi->GetFieldName());
							continue;
						}
					}

					if (!$CustomFieldApi->ValidData($postdata)) {
						$customfield_errors[] = sprintf(GetLang('SubscriberEditFail_InvalidData'), $CustomFieldApi->GetFieldName());
						continue;
					}
				}
				unset($CustomFieldApi);

				if (!empty($customfield_errors)) {
					$msg = implode('<br/>', $customfield_errors);
					$this->EditSubscriber($listid, $subscriberid, 'Error', $msg);
					break;
				}

				// go through each custom field and save the info.
				foreach ($ListCustomFields as $pos => $data) {
					$fieldid = $data['fieldid'];
					$postdata = (isset($_POST['CustomFields'][$fieldid])) ? $_POST['CustomFields'][$fieldid] : '';
					$subscriber->SaveSubscriberCustomField($subscriberid, $fieldid, $postdata);
				}

				?>
				<script>
					document.location = 'index.php?Page=Subscribers&Action=Manage&SubAction=Step3&Edit';
				</script>
				<?php
				exit();
			break;
			default:
				$list = (int)$_GET['List'];
				$subscriberid = (int)$_GET['id'];
				$this->EditSubscriber($list, $subscriberid);
		}
	}

	/**
	* EditSubscriber
	* Prints the 'edit subscriber' form and all appropriate options including custom fields.
	*
	* @param Int $listid The list the subscriber is on. This is checked to make sure the user has 'edit' access to the list before anything else.
	* @param Int $subscriberid The subscriberid to edit.
	* @param String $msgtype The heading to show when editing a subscriber. This can be either error or success. Used with $msg to display something.
	* @param String $msg The message to display in the heading. If this is not present, no message is displayed.
	*
	* @see GetApi
	* @see Subscribers_API::GetCustomFieldSettings
	* @see Lists_API::GetCustomFields
	* @see Lists_API::Load
	* @see Lists_API::GetListFormat
	*
	* @return Void Doesn't return anything. Prints out the edit form and that's it.
	*/
	function EditSubscriber($listid=0, $subscriberid=0, $msgtype='Error', $msg=false)
	{
		$user = &GetUser();
		$access = $user->HasAccess('Subscribers', 'Edit');
		if (!$access) {
			$this->DenyAccess();
			return;
		}

		$session = &GetSession();
		$search_info = $session->Get('Search_Subscribers');

		$GLOBALS['list'] = $listid;

		if ($msg && $msgtype) {
			switch (strtolower($msgtype)) {
				case 'success':
					$GLOBALS['Success'] = $msg;
					$GLOBALS['Message'] = $this->ParseTemplate('SuccessMsg', true, false);
				break;
				default:
					$GLOBALS['Error'] = $msg;
					$GLOBALS['Message'] = $this->ParseTemplate('ErrorMsg', true, false);
			}
		}

		$SubscriberApi = $this->GetApi('Subscribers');

		$list_api = $this->GetApi('Lists');
		$list_api->Load($listid);

		$subscriberinfo = $SubscriberApi->LoadSubscriberList($subscriberid, $listid);

		$GLOBALS['emailaddress'] = $subscriberinfo['emailaddress'];
		$GLOBALS['subscriberid'] = $subscriberid;

		if ($subscriberinfo['requestdate'] == 0) {
			$GLOBALS['requestdate'] = GetLang('Unknown');
		} else {
			$GLOBALS['requestdate'] = $this->PrintTime($subscriberinfo['requestdate']);
		}

		$GLOBALS['requestip'] = ($subscriberinfo['requestip'] == '') ? GetLang('Unknown') : $subscriberinfo['requestip'];

		if ($subscriberinfo['confirmdate'] == 0) {
			$GLOBALS['confirmdate'] = GetLang('Unknown');
		} else {
			$GLOBALS['confirmdate'] = $this->PrintTime($subscriberinfo['confirmdate']);
		}

		$GLOBALS['confirmip'] = ($subscriberinfo['confirmip'] == '') ? GetLang('Unknown') : $subscriberinfo['confirmip'];

		$confirmed = '';
		foreach (array('1' => 'Confirmed', '0' => 'Unconfirmed') as $confirmoption => $option) {
			$selected = ($confirmoption == $subscriberinfo['confirmed']) ? ' SELECTED' : '';
			$confirmed .= '<option value="' . $confirmoption . '"' . $selected . '>' . GetLang($option) . '</option>';
		}

		$GLOBALS['ConfirmedList'] = $confirmed;

		$GLOBALS['ShowUnsubscribeInfo'] = 'none';

		$subscriber_status = 'a';
		if ($subscriberinfo['unsubscribed'] > 0) {
			$subscriber_status = 'u';
			$GLOBALS['unsubscribetime'] = $this->PrintTime($subscriberinfo['unsubscribed']);
			$GLOBALS['unsubscribeip'] = ($subscriberinfo['unsubscribeip'] == '') ? GetLang('Unknown') : $subscriberinfo['unsubscribeip'];
			$GLOBALS['ShowUnsubscribeInfo'] = '';
		}

		if ($subscriberinfo['bounced'] > 0) {
			$subscriber_status = 'b';
		}

		$status = '';
		foreach (array('a' => 'Active', 'u' => 'Unsubscribed', 'b' => 'Bounced') as $statusoption => $option) {
			$selected = ($statusoption == $subscriber_status) ? ' SELECTED' : '';
			$status .= '<option value="' . $statusoption . '"' . $selected . '>' . GetLang($option) . '</option>';
		}
		$GLOBALS['StatusList'] = $status;

		$listformat = $list_api->GetListFormat();

		switch ($listformat) {
			case 't':
				$format  = '<option value="t" SELECTED>' . GetLang('Format_Text') . '</option>';
			break;
			case 'h':
				$format  = '<option value="h" SELECTED>' . GetLang('Format_HTML') . '</option>';
			break;
			case 'b':
				$selected = '';

				if ($subscriberinfo['format'] == 'h') {
					$selected = ' SELECTED';
				}
				$format  = '<option value="h"' . $selected . '>' . GetLang('Format_HTML') . '</option>';

				$selected = '';

				if ($subscriberinfo['format'] == 't') {
					$selected = ' SELECTED';
				}
				$format .= '<option value="t"' . $selected . '>' . GetLang('Format_Text') . '</option>';
			break;
		}

		$GLOBALS['FormatList'] = $format;

		$customfields = $list_api->GetCustomFields($listid);

		$extra_javascript = '';

		if (!empty($customfields)) {
			$customfield_display = $this->ParseTemplate('Subscribers_Edit_Step2_CustomFields', true, false);
			foreach ($customfields as $pos => $customfield_info) {
				$GLOBALS['FieldID'] = $customfield_info['fieldid'];
				if ($customfield_info['required']) {
					$GLOBALS['Required'] = $this->ParseTemplate('Required', true, false);
				} else {
					$GLOBALS['Required'] = $this->ParseTemplate('Not_Required', true, false);
				}

				$subscriber_settings = $SubscriberApi->GetCustomFieldSettings($customfield_info['fieldid']);

				switch ($customfield_info['fieldtype']) {
					case 'date':
						$optionlist = '';
						if ($subscriber_settings) {
							$date_parts = explode('/', $subscriber_settings);
							$date_info = array('dd' => $date_parts[0], 'mm' => $date_parts[1], 'yy' => $date_parts[2]);
						} else {
							$date_info = array();
						}
						$this->Display_CustomField($customfield_info, $date_info);
					break;

					case 'radiobutton':
						$fieldsettings = (is_array($customfield_info['fieldsettings'])) ? $customfield_info['fieldsettings'] : unserialize($customfield_info['fieldsettings']);

						$default_value = ($subscriber_settings) ? $subscriber_settings : '';

						$optionlist = '';

						$c = 1;
						foreach ($fieldsettings['Key'] as $pos => $key) {
							$selected = '';
							if ($key == $default_value) {
								$selected = ' CHECKED';
							}

							$label_id = htmlspecialchars('CustomFields_' . $customfield_info['fieldid'] . '_'.$key, ENT_QUOTES, SENDSTUDIO_CHARSET);

							$optionlist .= '<label for="'.$label_id.'"><input type="radio" id="'.$label_id.'" name="CustomFields[' . $customfield_info['fieldid'] . ']" value="' . htmlspecialchars($key, ENT_QUOTES, SENDSTUDIO_CHARSET) . '"' . $selected . '>' . htmlspecialchars($fieldsettings['Value'][$pos], ENT_QUOTES, SENDSTUDIO_CHARSET) . '</label>';
							if ($c % 4 == 0) {
								$optionlist .= '<br/>';
							}
							$c++;
						}

						if ($customfield_info['required']) {
							$extra_javascript .= '
								field_'.$customfield_info['fieldid'].'_check = CheckRadio("CustomFields_'.$customfield_info['fieldid'].'");

								if (!field_'.$customfield_info['fieldid'].'_check) {
									alert("' . sprintf(GetLang('ChooseValueForCustomField'), htmlspecialchars($customfield_info['name'], ENT_QUOTES, SENDSTUDIO_CHARSET)) . '");
									return false;
								}
							';
						}

					break;

					case 'dropdown':
						$fieldsettings = (is_array($customfield_info['fieldsettings'])) ? $customfield_info['fieldsettings'] : unserialize($customfield_info['fieldsettings']);
						$optionlist = '';

						$default_value = ($subscriber_settings) ? $subscriber_settings : array();

						$optionlist .= '<option value="">' . $customfield_info['defaultvalue'] . '</option>';

						foreach ($fieldsettings['Key'] as $pos => $key) {
							$selected = '';
							if ($key == $default_value) {
								$selected = ' SELECTED';
							}

							$optionlist .= '<option value="' . htmlspecialchars($key, ENT_QUOTES, SENDSTUDIO_CHARSET) . '"' . $selected . '>' . htmlspecialchars($fieldsettings['Value'][$pos], ENT_QUOTES, SENDSTUDIO_CHARSET) . '</option>';
						}

						if ($customfield_info['required']) {
							$extra_javascript .= '
								fld = document.getElementById("CustomFields['.$customfield_info['fieldid'].']");
								selIndex = fld.selectedIndex;
								if (selIndex < 1) {
									alert("'.sprintf(GetLang('ChooseOptionForCustomField'), htmlspecialchars($customfield_info['name'], ENT_QUOTES, SENDSTUDIO_CHARSET)) . '");
									fld.focus();
									return false;
								}
							';
						}

					break;

					case 'checkbox':
						$fieldsettings = (is_array($customfield_info['fieldsettings'])) ? $customfield_info['fieldsettings'] : unserialize($customfield_info['fieldsettings']);

						$default_values = ($subscriber_settings) ? unserialize($subscriber_settings) : array();

						$optionlist = '';
						$c = 1;

						foreach ($fieldsettings['Key'] as $pos => $key) {
							$selected = '';
							if (in_array($key, $default_values)) {
								$selected = ' CHECKED';
							}

							$label = htmlspecialchars('CustomFields[' . $customfield_info['fieldid'] . '][' . $key . ']', ENT_QUOTES, SENDSTUDIO_CHARSET);

							$optionlist .= '<label for="' . $label . '"><input type="checkbox" name="CustomFields[' . $customfield_info['fieldid'] . '][' . $pos . ']" id="' . $label . '" value="' . htmlspecialchars($key, ENT_QUOTES, SENDSTUDIO_CHARSET) . '"' . $selected . '>' . htmlspecialchars($fieldsettings['Value'][$pos], ENT_QUOTES, SENDSTUDIO_CHARSET) . '</label>';
							if ($c % 4 == 0) {
								$optionlist .= '<br/>';
							}

							$c++;
						}

						if ($customfield_info['required']) {
							$extra_javascript .= '
								CheckboxCheck = CheckMultiple("CustomFields[' . $customfield_info['fieldid'] . ']");
								if (!CheckboxCheck) {
									alert("' . sprintf(GetLang('ChooseValueForCustomField'), htmlspecialchars($customfield_info['name'], ENT_QUOTES, SENDSTUDIO_CHARSET)) . '");
									return false;
								}
							';
						}

					break;

					default:
						if ($customfield_info['required']) {
							$extra_javascript .= '
								if (document.getElementById("CustomFields['.$customfield_info['fieldid'].']").value == "") {
									alert("' . sprintf(GetLang('EnterValueForCustomField'), htmlspecialchars($customfield_info['name'], ENT_QUOTES, SENDSTUDIO_CHARSET)) . '");
									document.getElementById("CustomFields['.$customfield_info['fieldid'].']").focus();
									return false;
								}
							';
						}
						$optionlist = '';
						$subscriber_settings = $subscriber_settings;
				}

				$GLOBALS['OptionList'] = $optionlist;
				$GLOBALS['DefaultValue'] = htmlspecialchars($subscriber_settings, ENT_QUOTES, SENDSTUDIO_CHARSET);
				$GLOBALS['FieldName'] = htmlspecialchars($customfield_info['name'], ENT_QUOTES, SENDSTUDIO_CHARSET);
				$customfield_display .= $this->ParseTemplate('CustomField_Edit_' . $customfield_info['fieldtype'], true, false);
			}
			$GLOBALS['CustomFieldInfo'] = $customfield_display;
			$GLOBALS['ExtraJavascript'] = $extra_javascript;
		}

		$this->ParseTemplate('Subscribers_Edit_Step2');
	}
}
?>
