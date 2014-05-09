<?php
/**
* This file has the bounce management pages in it.
*
* @version     $Id: bounce.php,v 1.5 2007/05/04 04:28:13 rodney Exp $

*
* @package SendStudio
* @subpackage SendStudio_Functions
*/

/**
* Include the base sendstudio functions.
*/
require(dirname(__FILE__) . '/sendstudio_functions.php');

/**
* Class for management of bouncing email addresses.
*
* @package SendStudio
* @subpackage SendStudio_Functions
*/
class Bounce extends SendStudio_Functions
{

	/**
	* EmailsPerRefresh
	* Number of emails to process per refresh.
	*
	* @var Int
	*/
	var $EmailsPerRefresh = 20;

	/**
	* Constructor
	* Loads the language file.
	*
	* @see LoadLanguageFile
	* @see PrintHeader
	* @see PrintFooter
	*
	* @return Void Loads up the language file and adds 'send' as a valid popup window type.
	*/
	function Bounce()
	{
		$this->PopupWindows[] = 'bounce';
		$this->LoadLanguageFile();
	}

	/**
	* Process
	* This works out where you are up to in the bounce process and takes the appropriate action. Most is passed off to other methods in this class for processing and displaying the right forms.
	*
	* @return Void Doesn't return anything.
	*/
	function Process()
	{
		$action = (isset($_GET['Action'])) ? strtolower(urldecode($_GET['Action'])) : null;
		$user = &GetUser();
		$access = $user->HasAccess('Lists', 'Bounce');

		$popup = (in_array($action, $this->PopupWindows)) ? true : false;
		$this->PrintHeader($popup);

		if (!$access) {
			$this->DenyAccess();
			return;
		}

		$session = &GetSession();

		switch ($action) {
			case 'bouncefinished':
				$this->PrintFinalReport();
			break;

			case 'bounce':
				$session = &GetSession();
				$bounce_info = $session->Get('BounceInfo');

				$bounce_api = $this->GetApi('Bounce');

				$bounce_api->Set('bounceserver', $bounce_info['BounceServer']);
				$bounce_api->Set('bounceuser', $bounce_info['BounceUser']);
				$bounce_api->Set('bouncepassword', $bounce_info['BouncePassword']);
				$bounce_api->Set('imapaccount', $bounce_info['IMAPAccount']);
				$bounce_api->Set('extramailsettings', $bounce_info['ExtraMailSettings']);

				$start_position = 1;
				if (isset($_GET['Email'])) {
					$start_position = (int)$_GET['Email'];
				}

				if ($start_position > $bounce_info['EmailCount']) {
					?>
						<script language="javascript">
							window.opener.focus();
							window.opener.document.location = 'index.php?Page=Bounce&Action=BounceFinished';
							window.close();
						</script>
					<?php
					break;
				}

				$this->PrintStatusReport($start_position, $bounce_info['EmailCount']);
				$this->PrintFooter(true);

				$inbox = $bounce_api->Login();

				if (!$inbox) {
					$GLOBALS['Error'] = sprintf(GetLang('BadLogin_Details'), $bounce_api->Get('ErrorMessage'));
					$GLOBALS['Message'] = $this->ParseTemplate('ErrorMsg', true, false);
					break;
				}

				for ($bouncecount = 0; $bouncecount < $this->EmailsPerRefresh; $bouncecount++) {
					$emailid = $start_position + $bouncecount;

					if ($emailid > $bounce_info['EmailCount']) {
						$session->Set('BounceInfo', $bounce_info);
						?>
							<script language="javascript">
								window.opener.focus();
								window.opener.document.location = 'index.php?Page=Bounce&Action=BounceFinished';
								window.close();
							</script>
						<?php
						$bounce_api->Logout();
						exit();
					}

					$processed = $bounce_api->ProcessEmail($emailid, $bounce_info['List']);

					if ($processed == 'ignore') {
						$bounce_info['Report']['EmailsIgnored']++;
					}

					if ($processed == 'hard') {
						$bounce_info['EmailsToDelete'][] = $emailid;
						$bounce_info['Report']['HardBounces']++;
					}

					if ($processed == 'soft') {
						$bounce_info['EmailsToDelete'][] = $emailid;
						$bounce_info['Report']['SoftBounces']++;
					}

					// see api/bounce.php for what 'delete' means.
					if ($processed == 'delete') {
						$bounce_info['EmailsToDelete'][] = $emailid;
					}
				}
				$bounce_api->Logout();
				$session->Set('BounceInfo', $bounce_info);

				// need to increment emailid because emails actually start counting at 1 not 0.
				// otherwise every $this->perrefresh emails would be processed twice.
				// eg email '20' would be processed twice - which stuffs up reporting....
				?>
					<script language="javascript" defer>
						setTimeout('window.location="index.php?Page=Bounce&Action=Bounce&Email=<?php echo $emailid+1; ?>&bx=<?php echo rand(1,50); ?>;"', 2);
					</script>
				<?php
				exit();
			break;

			case 'step3':
				$bounce_info = $session->Get('BounceInfo');
				$list = $bounce_info['List'];

				$errors = array();
				$bounce_server = false;
				if (!isset($_POST['BounceServer']) || $_POST['BounceServer'] == '') {
					$errors[] = GetLang('EnterBounceServer');
				} else {
					$bounce_server = $_POST['BounceServer'];
				}

				$bounce_username = false;
				if (!isset($_POST['BounceU']) || $_POST['BounceU'] == '') {
					$errors[] = GetLang('EnterBounceUsername');
				} else {
					$bounce_username = $_POST['BounceU'];
				}

				$bounce_password = false;
				if (!isset($_POST['BounceP']) || $_POST['BounceP'] == '') {
					$errors[] = GetLang('EnterBouncePassword');
				} else {
					$bounce_password = $_POST['BounceP'];
				}

				$imap = false;
				if (isset($_POST['IMAPAccount']) && $_POST['IMAPAccount'] == 1) {
					$imap = true;
				}

				$extramailsettings = false;
				if (isset($_POST['extramail']) && $_POST['extramail'] == 1) {
					$extramailsettings = $_POST['ExtraMailSettings'];
				}

				if (!empty($errors)) {
					$errormsg = implode('<br/>', $errors);
					$this->GetBounceInformation($list, $errormsg);
					break;
				}

				$bounce_api = $this->GetApi('Bounce');
				$bounce_api->Set('bounceserver', $bounce_server);
				$bounce_api->Set('bounceuser', $bounce_username);
				$bounce_api->Set('bouncepassword', @base64_encode($bounce_password));
				$bounce_api->Set('imapaccount', $imap);
				$bounce_api->Set('extramailsettings', $extramailsettings);

				$inbox = $bounce_api->Login();

				if (!$inbox) {
					$this->GetBounceInformation($list, sprintf(GetLang('BadLogin_Details'), $bounce_api->Get('ErrorMessage')));
					break;
				}

				if (isset($_POST['savebounceserverdetails']) && $_POST['savebounceserverdetails']) {
					$list_api = $this->GetApi('Lists');
					$list_api->Load($list);
					$list_api->Set('bounceserver', $bounce_server);
					$list_api->Set('bounceusername', $bounce_username);
					$list_api->Set('bouncepassword', $bounce_password);
					$list_api->Set('imapaccount', $imap);
					$list_api->Set('extramailsettings', $extramailsettings);
					$list_api->Save();
					$GLOBALS['Message'] = $this->PrintSuccess('BounceDetailsSaved');
				}

				$emailcount = $bounce_api->GetEmailCount();

				$bounce_api->Logout();

				if ($emailcount <= 0) {
					$this->GetBounceInformation($list, false, 'BounceAccountEmpty');
					break;
				}

				$session = &GetSession();
				$bounce_info = $session->Get('BounceInfo');

				$bounce_info['BounceServer'] = $bounce_server;
				$bounce_info['BounceUser'] = $bounce_username;
				$bounce_info['BouncePassword'] = base64_encode($bounce_password);
				$bounce_info['IMAPAccount'] = $imap;
				$bounce_info['ExtraMailSettings'] = $extramailsettings;

				$bounce_info['EmailCount'] = $emailcount;

				$bounce_info['EmailsToDelete'] = array();

				$bounce_info['Report']['HardBounces'] = 0;
				$bounce_info['Report']['SoftBounces'] = 0;
				$bounce_info['Report']['EmailsIgnored'] = 0;

				$session->Set('BounceInfo', $bounce_info);

				$this->ParseTemplate('Bounce_Step3');
			break;

			case 'step2':
				$listid = (isset($_POST['list'])) ? (int)$_POST['list'] : (int)$_GET['list'];
				$bounce_info = array('List' => $listid);
				$session->Set('BounceInfo', $bounce_info);
				$this->GetBounceInformation($listid);
			break;

			default:
				if (!function_exists('imap_open')) {
					$this->ParseTemplate('Bounce_NoImapSupport');
					break;
				}
				$session->Remove('BounceInfo');
				$this->ChooseList('Bounce', 'Step2');
			break;
		}
		$this->PrintFooter($popup);
	}

	function GetBounceInformation($listid, $errormsg=false, $warningmsg=false)
	{
		$listid = (int)$listid;
		if ($listid <= 0) {
			return $this->ChooseList('Bounce', 'Step2');
		}

		$list = $this->GetApi('Lists');
		$loaded = $list->Load($listid);
		if (!$loaded) {
			return $this->ChooseList('Bounce', 'Step2');
		}

		if ($errormsg) {
			$GLOBALS['Error'] = $errormsg;
			$GLOBALS['Message'] = $this->ParseTemplate('ErrorMsg', true, false);
		}

		if ($warningmsg) {
			$GLOBALS['Message'] = $this->PrintWarning($warningmsg);
		}

		$GLOBALS['BounceServer'] = $list->bounceserver;
		$GLOBALS['BounceUsername'] = $list->bounceusername;
		$GLOBALS['BouncePassword'] = $list->bouncepassword;
		$GLOBALS['IMAPAccount'] = ($list->imapaccount) ? ' CHECKED' : '';

		$GLOBALS['DisplayExtraMailSettings'] = 'none';
		if ($list->extramailsettings) {
			$GLOBALS['DisplayExtraMailSettings'] = '';
			$GLOBALS['UseExtraMailSettings'] = ' CHECKED';
			$GLOBALS['ExtraMailSettings'] = $list->extramailsettings;
		}

		if (isset($_POST['BounceServer'])) {
			$GLOBALS['BounceServer'] = $_POST['BounceServer'];

			$GLOBALS['IMAPAccount'] = '';
			if (isset($_POST['IMAPAccount'])) {
				$GLOBALS['IMAPAccount'] = ' CHECKED';
			}

			$GLOBALS['DisplayExtraMailSettings'] = 'none';
			if (isset($_POST['extramail'])) {
				$GLOBALS['DisplayExtraMailSettings'] = '';
				$GLOBALS['UseExtraMailSettings'] = ' CHECKED';
				$GLOBALS['ExtraMailSettings'] = $_POST['ExtraMailSettings'];
			}
		}

		if (isset($_POST['BounceU'])) {
			$GLOBALS['BounceU'] = $_POST['BounceU'];
		}

		if (isset($_POST['BounceP'])) {
			$GLOBALS['BounceP'] = $_POST['BounceP'];
		}

		$this->ParseTemplate('Bounce_Step2');
	}

	function PrintStatusReport($start, $total)
	{
		$session = &GetSession();
		$bounceinfo = $session->Get('BounceInfo');

		$GLOBALS['BounceResults_Message'] = sprintf(GetLang('BounceResults_InProgress_Message'), $this->FormatNumber($bounceinfo['EmailCount']));

		$report = '';
		foreach (array('HardBounces', 'SoftBounces', 'EmailsIgnored') as $pos => $key) {
			$amount = $bounceinfo['Report'][$key];
			if ($amount == 1) {
				$report .= GetLang('BounceResults_InProgress_' . $key . '_One');
			} else {
				$report .= sprintf(GetLang('BounceResults_InProgress_' . $key . '_Many'), $this->FormatNumber($amount));
			}
			$report .= '<br/>';
		}
		$report .= '<br/>';
		$report .= sprintf(GetLang('BounceResults_InProgress_Progress'), $this->FormatNumber($start-1), $this->FormatNumber($total));
		$GLOBALS['Report'] = $report;
		$this->ParseTemplate('Bounce_ReportProgress');
	}

	/**
	* PrintFinalReport
	* Prints out the report of what's happened.
	* If there are any problems, the item becomes a link the user can click to get more information about what broke and why.
	*
	* @return Void Doesn't return anything, prints out the report only.
	*/
	function PrintFinalReport()
	{
		$session = &GetSession();
		$bounceinfo = $session->Get('BounceInfo');

		$report = '<ul>';

		if ($bounceinfo['EmailCount'] == 1) {
			$report .= '<li>' . GetLang('BounceResults_Message_One') . '</li>';
		} else {
			$report .= '<li>' . sprintf(GetLang('BounceResults_Message_Multiple'), $this->FormatNumber($bounceinfo['EmailCount'])) . '</li>';
		}

		foreach (array('HardBounces', 'SoftBounces', 'EmailsIgnored') as $pos => $key) {
			$amount = $bounceinfo['Report'][$key];
			$report .= "<li>";
			if ($amount == 1) {
				$report .= GetLang('BounceResults_' . $key . '_One');
			} else {
				$report .= sprintf(GetLang('BounceResults_' . $key . '_Many'), $this->FormatNumber($amount));
			}
			$report .= '</li>';
		}

		$report .= '</ul>';

		$GLOBALS['Message'] = $this->PrintSuccess('BounceResults_Intro');
		$GLOBALS['Report'] = $report;

		$user = &GetUser();
		if ($user->HasAccess('Statistics', 'List')) {
			$GLOBALS['ListID'] = $bounceinfo['List'];
			$GLOBALS['Statistics_Button'] = $this->ParseTemplate('Bounce_Statistics_Button', true);
		}

		$this->ParseTemplate('Bounce_Results_Report');

		$bounce_api = $this->GetApi('Bounce');

		$bounce_api->Set('bounceserver', $bounceinfo['BounceServer']);
		$bounce_api->Set('bounceuser', $bounceinfo['BounceUser']);
		$bounce_api->Set('bouncepassword', $bounceinfo['BouncePassword']);
		$bounce_api->Set('imapaccount', $bounceinfo['IMAPAccount']);
		$bounce_api->Set('extramailsettings', $bounceinfo['ExtraMailSettings']);
		$inbox = $bounce_api->Login();

		foreach ($bounceinfo['EmailsToDelete'] as $p => $emailid) {
			$bounce_api->DeleteEmail($emailid);
		}
		$bounce_api->Logout(true);
	}

}
?>
