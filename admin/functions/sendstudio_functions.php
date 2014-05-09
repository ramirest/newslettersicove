<?php

/**
* Make sure nobody is doing a sneaky and trying to go to the page directly.
*
* @package SendStudio
* @subpackage SendStudio_Functions
*/
if (!defined('SENDSTUDIO_BASE_DIRECTORY')) {
	header('Location: ../index.php');
	exit();
}

/**
* Base class for SendStudio Functions.
*
* @package SendStudio
* @subpackage SendStudio_Functions
*/
class SendStudio_Functions
{

	/**
	* GlobalAreas
	* You can set global areas by putting them in this array. If they are in here, they will be used by ParseTemplate
	*
	* @see ParseTemplate
	*
	* @var Array
	*/
	var $GlobalAreas = array();


	/**
	* _RandomStrings
	* An array of helptip id's that have been generated. By remembering them here, we can ensure that they are unique.
	*
	* @see _GenerateHelpTip
	*
	* @var Array
	*/
	var $_RandomStrings = array();


	/**
	* _PagesToShow
	* This controls how many pages we show when we are creating the paging. This includes the current page. For example, if we are on page 7 of 20, we will see pages 5,6,7,8,9.
	* It should be an odd number so we get an even amount either side of the current page.
	*
	* @see SetupPaging
	*
	* @var Int
	*/
	var $_PagesToShow = 5;


	/**
	* _PagingMinimum Number of records to show before we start showing the paging at the bottom of the screen.
	*
	* @var Int
	*/
	var $_PagingMinimum = 5;


	/**
	* _PerPageDefault Default number to show per page. This is used if the user hasn't set anything before (in session).
	*
	* @see GetPerPage
	*
	* @var Int
	*/
	var $_PerPageDefault = 10;


	/**
	* _DefaultSort
	* Default sort order.
	*
	* @see _DefaultDirection
	* @see GetSortDetails
	*
	* @var String
	*/
	var $_DefaultSort = 'emailaddress';


	/**
	* _DefaultDirection
	* Default sort direction.
	*
	* @see _DefaultSort
	* @see GetSortDetails
	*
	* @var String
	*/
	var $_DefaultDirection = 'down';

	/**
	* _SecondarySorts
	* Secondary sort for fields. This is used if the primary sort is not being done.
	*
	* @see GetSortDetails
	*
	* @var Array
	*/
	var $_SecondarySorts = array();


	/**
	* PopupWindows
	* A list of actions that are popup windows.
	*
	* @see Process
	*
	* @var Array
	*/
	var $PopupWindows = array('view');


	/**
	* ValidFileExtensions
	* An array of valid file extensions that you can attach to a newsletter.
	*
	* @see SaveAttachments
	*
	* @var Array
	*/
	var $ValidFileExtensions = array('pdf', 'doc', 'xls', 'zip', 'jpg', 'png', 'gif', 'jpeg', 'txt', 'htm', 'html', 'csv', 'rtf', 'rar', 'ppt', 'pps', 'avi', 'mp3', 'mpg', 'mpeg');

	/**
	* MaxFileSize
	* The maximum filesize you can upload and attach to a newsletter. This is per attachment.
	* Default is 2 Meg (1024*1024*2).
	*
	* @see SaveAttachments
	*
	* @var Int
	*/
	var $MaxFileSize = 2097152;

	/**
	* Months
	* An array of months. This lets us quickly grab the right language pack variable.
	*
	* @see CreateDateTimeBox
	* @see GetLang
	*
	* @var Array
	*/
	var $Months = array(
		'1' => 'Jan',
		'2' => 'Feb',
		'3' => 'Mar',
		'4' => 'Apr',
		'5' => 'May',
		'6' => 'Jun',
		'7' => 'Jul',
		'8' => 'Aug',
		'9' => 'Sep',
		'10' => 'Oct',
		'11' => 'Nov',
		'12' => 'Dec'
	);

	/**
	* days_of_week
	* An array of days of the week. This is mainly used by stats so "last 7 days" graphs get created properly.
	*
	* @see Stats_Chart::SetupChart_SubscriberSummary
	* @see Stats_Chart::SetupChart
	*
	* @var Array
	*/
	var $days_of_week = array(
		'0' => 'Sun',
		'1' => 'Mon',
		'2' => 'Tue',
		'3' => 'Wed',
		'4' => 'Thu',
		'5' => 'Fri',
		'6' => 'Sat',
		'7' => 'Sun'
	);

	/**
	* @var _MaxNameLength The maximum length of a name (eg keywords, url, etc). If a name is longer than this length, it is chopped off (4 chars early) and has ' ...' appended to it.
	*
	* @see TruncateName
	*/
	var $_MaxNameLength = 45;

	/**
	* Constructor
	* Does nothing. Shouldn't ever be called itself anyway.
	*
	* @return Void Base class does nothing.
	*/
	function SendStudio_Functions()
	{
	}


	/**
	* Process
	* Base process function prints the header, prints the page and the footer.
	* If there is any functionality to provide, it must be overridden by the children objects.
	*
	* @see PrintHeader
	* @see PrintFooter
	*
	* @return Void Doesn't return anything. The base class prints out the header menu, prints out 'this' template and the footer. This should be overridden by the children objects.
	*/
	function Process()
	{
		$this->PrintHeader();
		$template = strtolower(get_class($this));
		$this->ParseTemplate($template);
		$this->PrintFooter();
	}


	/**
	* GetApi
	* Gets the API we pass in. If we don't pass in an API to fetch, it will fetch the API based on the class.
	*
	* @param String $api The name of the API to fetch. If there is nothing passed in, it will fetch the API based on this class.
	*
	* @return False|Object Returns an object if it can find the API, otherwise returns false.
	*/
	function GetApi($api=false)
	{
		if (!$api) {
			$api = get_class($this);
		}

		$api = strtolower($api);
		if ($api == 'email') {
			$api = 'ss_email';
		}

		$api_file = SENDSTUDIO_API_DIRECTORY.'/' . $api . '.php';
		if (!is_file($api_file)) {
			return false;
		}

		$api .= '_API';

		if (!class_exists($api)) {
			require_once($api_file);
		}

		$myapi = &New $api();
		return $myapi;
	}

	/**
	* ReplaceLanguageVariables
	* Replaces language variables that are found in the content passed in, then returns the content.
	*
	* @param String $template The template content to replace language variables in.
	*
	* @see ParseTemplate
	*
	* @return String Returns the content with the language variables replaced.
	*/
	function ReplaceLanguageVariables($template='')
	{
		// Parse out the language pack variables in the template file
		preg_match_all("/(?siU)(%%LNG_[a-zA-Z0-9_]{1,}%%)/", $template, $matches);

		foreach ($matches[0] as $p => $match) {
			$langvar = str_replace(array('%', 'LNG_'), '', $match);
			$template = str_replace($match, GetLang($langvar), $template);
		}
		unset($matches);
		return $template;
	}

	/**
	* ParseTemplate
	* Loads the template that you pass in. Replaces any placeholders that you set in GlobalAreas and then goes through, looks for language placeholders, request vars, global vars and replaces them all.
	*
	* @param String $templatename The name of the template to load and then display.
	* @param Boolean $return Whether to return the template or just display it. Default is to display it.
	* @param Boolean $recurse Whether to recurse into other templates that are included or not.
	* @param String $fullpath The full path to the template. This is used by the forms to pass in the path to the design.
	*
	* @see GetLang
	* @see GlobalAreas
	* @see _GenerateHelpTip
	* @see GetSession
	* @see Session::LoggedIn
	* @see Session::Get
	* @see User_API::Admin
	* @see ReplaceLanguageVariables
	*
	* @return Mixed Returns the template if specified otherwise it returns nothing.
	*/
	function ParseTemplate($templatename=false, $return=false, $recurse=true, $fullpath=null)
	{
		if (!$templatename) {
			return false;
		}

		$this->GlobalAreas['APPLICATION_URL'] = SENDSTUDIO_APPLICATION_URL;
		$this->GlobalAreas['CHARSET'] = SENDSTUDIO_CHARSET;

		$templatename = strtolower($templatename);
		if (is_null($fullpath)) {
			$template_file = SENDSTUDIO_TEMPLATE_DIRECTORY . '/' . $templatename . '.tpl';
		} else {
			$template_file = $fullpath;
		}
		if (!is_file($template_file)) {
			trigger_error(sprintf(GetLang('ErrCouldntLoadTemplate'), ucwords($templatename)), E_USER_ERROR);
		}
		$template = file_get_contents($template_file);

		$session = &GetSession();
		if (!$session->LoggedIn()) {
			$template = str_replace('%%GLOBAL_MenuTable%%', '', $template);
			$template = str_replace('%%GLOBAL_TextLinks%%', '', $template);
		}

		
		if ($session->LoggedIn()) {
			$user = $session->Get('UserDetails');

			if ($templatename == 'header') {
				$menulink = $this->GenerateMenuLinks();
				$textlink = '';
				
				$adjustedtime = AdjustTime();

				$GLOBALS['SystemDateTime'] = sprintf(GetLang('UserDateHeader'), AdjustTime($adjustedtime, false, GetLang('UserDateFormat')), $user->Get('usertimezone'));

				$textlink .= '<a href="index.php">' . GetLang('Home') . '</a>&nbsp;&nbsp;|';

				if ($user->HasAccess('Forms')) {
					$textlink .= '&nbsp;&nbsp;<a href="index.php?Page=Forms">' . GetLang('Forms') . '</a>&nbsp;&nbsp;|';
				}

				if (!$user->Admin() && $user->EditOwnSettings()) {
					$textlink .= '&nbsp;&nbsp;<a href="index.php?Page=ManageAccount">' . GetLang('MyAccount') . '</a>&nbsp;&nbsp;|';
				}

				foreach (array('Users', 'Settings') as $p => $area) {
					if ($user->HasAccess($area)) {
						$textlink .= '&nbsp;&nbsp;<a href="index.php?Page=' . $area . '">' . GetLang($area) . '</a>&nbsp;&nbsp;|';
					}
				}

				$textlink .= '&nbsp;&nbsp;<a href="resources/tutorials/index.html" target="_new">' . GetLang('ShowHelp') . '</a>&nbsp;&nbsp;|';

				$textlink .= '&nbsp;&nbsp;<a href="index.php?Page=Logout">' . GetLang('Logout') . '</a>';

				$unlimited_emails = $user->Get('unlimitedmaxemails');
				if (!$unlimited_emails) {
					$GLOBALS['TotalEmailCredits'] = sprintf(GetLang('User_Total_CreditsLeft'), $this->FormatNumber($user->Get('maxemails')));
				}

				$monthly_credits = $user->Get('permonth');
				if ($monthly_credits > 0) {
					$this_month = date('n');
					$GLOBALS['MonthlyEmailCredits'] = sprintf(GetLang('User_Monthly_CreditsLeft'), $this->FormatNumber($user->GetAvailableEmailsThisMonth()), $this->Months[$this_month]);
				}

				$template = str_replace('%%GLOBAL_MenuLinks%%', $menulink, $template);
				$template = str_replace('%%GLOBAL_TextLinks%%', $textlink, $template);
			}
		}

		
		foreach ($this->GlobalAreas as $area => $val) {
			$template = str_replace('%%GLOBAL_' . $area . '%%', $val, $template);
		}

		// Parse out the language pack help variables in the template file
		preg_match_all("/(?siU)(%%LNG_HLP_[a-zA-Z0-9_]{1,}%%)/", $template, $matches);

		foreach ($matches[0] as $p => $match) {
			$HelpTip = $this->_GenerateHelpTip($match);
			$template = str_replace($match, $HelpTip, $template);
		}

		$template = $this->ReplaceLanguageVariables($template);

		// Parse out the request variables in the template file
		preg_match_all("/(?siU)(%%REQUEST_[a-zA-Z0-9_]{1,}%%)/", $template, $matches);

		foreach ($matches[0] as $p => $match) {
			$request_var = str_replace(array('%', 'REQUEST_'), '', $match);
			$request_value = (isset($_REQUEST[$request_var])) ? $_REQUEST[$request_var] : '';
			$template = str_replace($match, $request_value, $template);
		}

		// Parse out the global variables in the template file
		preg_match_all("/(?siU)(%%GLOBAL_[a-zA-Z0-9_]{1,}%%)/", $template, $matches);

		foreach ($matches[0] as $p => $match) {
			$global_var = str_replace(array('%', 'GLOBAL_'), array('', ''), $match);
			$global_value = (isset($GLOBALS[$global_var])) ? $GLOBALS[$global_var] : '';
			$template = str_replace($match, $global_value, $template);
		}

		if ($recurse) {
			// Parse out the global variables in the template file
			preg_match_all("/(?siU)(%%TPL_[a-zA-Z0-9_]{1,}%%)/", $template, $matches);

			foreach ($matches[0] as $p => $match) {
				$template_var = str_replace(array('%', 'TPL_'), '', $match);
				$subtemplate = $this->ParseTemplate($template_var, true, $recurse);
				$template = str_replace($match, $subtemplate, $template);
			}
		}

		// Parse out the static variables in the template file
		$template = str_replace('%%PAGE_TITLE%%', GetLang('PageTitle'), $template);

		// this is for the 'Page' part for links. Eg -
		// index.php?Page=Stats
		if (!isset($GLOBALS['PAGE'])) {
			$thispage = get_class($this);
		} else {
			$thispage = $GLOBALS['PAGE'];
		}
		$template = str_replace('%%PAGE%%', $thispage, $template);

		if ($return) {
			return $template;
		}

		echo $template;
	}

	/**
	* PrintHeader
	* Prints out the header info. You can also set menuareas up with the MenuAreas array.
	*
	* @param Boolean $PopupWindow Pass in whether this is a popup window or not. This can be used to work out whether to display the menu at the top of the page.
	* @param Boolean $LoadLanguageFile Whether to automatically load the language file or not.
	* @param Boolean $PrintHeader Should we print the header file? This is separate to the popup window option because subclasses (eg Subscribers_Remove) have their parent print work this out.
	*
	* @see Generate
	* @see MenuAreas
	* @see Subscribers_Remove::Process
	*
	* @return Void Doesn't return anything. Loads up the appropriate header based on the details passed in and that's it.
	*/
	function PrintHeader($PopupWindow=false, $LoadLanguageFile=true, $PrintHeader=true)
	{
		$session = &GetSession();
		if ($session->LoggedIn()) {
			if (isset($_GET['Action']) && strtolower($_GET['Action']) == 'view') {
				return;
			}
		}

		if ($PrintHeader) {
			header('Content-type: text/html; charset="' . SENDSTUDIO_CHARSET . '"');
		}

		if ($LoadLanguageFile) {
			$this->LoadLanguageFile();
		}

		if ($PopupWindow && $PrintHeader) {
			$this->ParseTemplate('header_popup');
			return;
		}

		if ($PrintHeader) {
			$this->ParseTemplate('header');
			$this->ShowInfoTip();
		}
	}

	/**
	* PrintFooter
	* Prints out the footer info.
	*
	* There are three slightly different footers.
	*
	* The Footer_Popup file doesn't have the copyright line at the bottom of the page.
	* The main footer (Footer) has the help column on the right & copyright line at the bottom.
	*
	* @param Boolean $PopupWindow Pass in whether this is a popup window or not. This can be used to work out whether to display the copyright info at the bottom of the page.
	* @param Boolean $return Whether to return the template or just print it. By default it is just printed.
	*
	* @return Mixed If return is set to true, this will return the footer. If it's not, this will print out the appropriate footer and that's it.
	*/
	function PrintFooter($PopupWindow=false, $return=false)
	{
		$session = &GetSession();
		if (!$session->LoggedIn()) {
			return $this->ParseTemplate('Footer');
		}

		if ($PopupWindow) {
			if ($return) {
				return $this->ParseTemplate('Footer_Popup', true);
			}
			$this->ParseTemplate('Footer_Popup');
		} else {
			if ($return) {
				return $this->ParseTemplate('Footer', true);
			}
			$this->ParseTemplate('Footer');
		}
	}

	/**
	* GenerateMenuLinks
	* Prints out the menu based on which options a user has access to.
	*
	* @see GetSession
	* @see Session::Get
	* @see User::HasAccess
	*
	* @return String Returns the generated menu with the necessary options.
	*/
	function GenerateMenuLinks()
	{
		$session = &GetSession();
		$user = $session->Get('UserDetails');

		$menuItems = array (
			'list_button' => array (
				array (
					'text' => GetLang('Menu_MailingLists_Manage'),
					'link' => 'index.php?Page=Lists',
					'show' => $user->HasAccess('Lists'),
				),
				array (
					'text' => GetLang('Menu_MailingLists_Create'),
					'link' => 'index.php?Page=Lists&amp;Action=create',
					'show' => $user->HasAccess('Lists', 'Create'),
				),
				array (
					'text' => GetLang('Menu_MailingLists_CustomFields'),
					'link' => 'index.php?Page=CustomFields',
					'show' => $user->HasAccess('CustomFields'),
				),
				array (
					'text' => GetLang('Menu_MailingLists_Bounce'),
					'link' => 'index.php?Page=Bounce',
					'show' => $user->HasAccess('Lists', 'Bounce'),
				),
			),
			'subscriber_button' => array (
				array (
					'text' => GetLang('Menu_Members_Manage'),
					'link' => 'index.php?Page=Subscribers&amp;Action=Manage',
					'show' => $user->HasAccess('Subscribers', 'Manage'),
				),
				array (
					'text' => GetLang('Menu_Members_Import'),
					'link' => 'index.php?Page=Subscribers&amp;Action=Import',
					'show' => $user->HasAccess('Subscribers', 'Import'),
				),
				array (
					'text' => GetLang('Menu_Members_Add'),
					'link' => 'index.php?Page=Subscribers&amp;Action=Add',
					'show' => $user->HasAccess('Subscribers', 'Add'),
				),
				array (
					'text' => GetLang('Menu_Members_Export'),
					'link' => 'index.php?Page=Subscribers&amp;Action=Export',
					'show' => $user->HasAccess('Subscribers', 'Export'),
				),
				array (
					'text' => GetLang('Menu_Members_Remove'),
					'link' => 'index.php?Page=Subscribers&amp;Action=Remove',
					'show' => $user->HasAccess('Subscribers', 'Delete'),
				),
				array (
					'text' => GetLang('Menu_Members_Banned_Manage'),
					'link' => 'index.php?Page=Subscribers&amp;Action=Banned',
					'show' => $user->HasAccess('Subscribers', 'Banned'),
				),
				array (
					'text' => GetLang('Menu_Members_Banned_Add'),
					'link' => 'index.php?Page=Subscribers&amp;Action=Banned&amp;SubAction=Add',
					'show' => $user->HasAccess('Subscribers', 'Banned'),
				),
			),
			'newsletter_button' => array (
				array (
					'text' => GetLang('Menu_Newsletters_Manage'),
					'link' => 'index.php?Page=Newsletters&amp;Action=Manage',
					'show' => $user->HasAccess('Newsletters', 'Manage'),
				),
				array (
					'text' => GetLang('Menu_Newsletters_Create'),
					'link' => 'index.php?Page=Newsletters&amp;Action=Create',
					'show' => $user->HasAccess('Newsletters', 'Create'),
				),
				array (
					'text' => GetLang('Menu_Newsletters_Send'),
					'link' => 'index.php?Page=Send',
					'show' => $user->HasAccess('Newsletters', 'Send'),
				),
				array (
					'text' => GetLang('Menu_Newsletters_ManageSchedule'),
					'link' => 'index.php?Page=Schedule',
					'show' =>(SENDSTUDIO_CRON_ENABLED && $user->HasAccess('Newsletters', 'Send')),
				),
			),
			'autoresponder_button' => array (
				array (
					'text' => GetLang('Menu_Autoresponders_Manage'),
					'link' => 'index.php?Page=Autoresponders&amp;Action=Manage',
					'show' => $user->HasAccess('Autoresponders', 'Manage'),
				),
				array (
					'text' => GetLang('Menu_Autoresponders_Create'),
					'link' => 'index.php?Page=Autoresponders&amp;Action=Create',
					'show' => $user->HasAccess('Autoresponders', 'Create'),
				),
			),
			'template_button' => array (
				array (
					'text' => GetLang('Menu_Templates_Manage'),
					'link' => 'index.php?Page=Templates&amp;Action=Manage',
					'show' => $user->HasAccess('Templates', 'Manage'),
				),
				array (
					'text' => GetLang('Menu_Templates_Create'),
					'link' => 'index.php?Page=Templates&amp;Action=Create',
					'show' => $user->HasAccess('Templates', 'Create'),
				),
				array (
					'text' => GetLang('Menu_Templates_Manage_BuiltIn'),
					'link' => 'index.php?Page=Templates&amp;Action=BuiltIn',
					'show' => $user->HasAccess('Templates', 'BuiltIn'),
				),
			),
			'statistics_button' => array (
				array (
					'text' => GetLang('Menu_Statistics_Newsletters'),
					'link' => 'index.php?Page=Stats',
					'show' => $user->HasAccess('Statistics', 'Newsletter'),
				),
				array (
					'text' => GetLang('Menu_Statistics_Autoresponders'),
					'link' => 'index.php?Page=Stats&amp;Action=Autoresponders',
					'show' => $user->HasAccess('Statistics', 'Autoresponder'),
				),
				array (
					'text' => GetLang('Menu_Statistics_Lists'),
					'link' => 'index.php?Page=Stats&amp;Action=List',
					'show' => $user->HasAccess('Statistics', 'List'),
				),
				array (
					'text' => GetLang('Menu_Statistics_Users'),
					'link' => 'index.php?Page=Stats&amp;Action=User',
					'show' => $user->HasAccess('Statistics', 'User'),
				),
			),
		);

		// Generate the tabs
		$menu = '';

		// {{{ New menu
		$menu .= "\n".'<div id="headerMenu">'."\n".'<ul>'."\n";

		$imagesDir = dirname(__FILE__).'/../images';
		foreach ($menuItems as $image => $link) {
			// If the menu has sub menus, display them
			if (is_array($link)) {
				$first = true;
				$shown = false;
				foreach ($link as $id => $sub) {
					// If the child is forbidden by law, hide it
					if (!$sub['show']) {
						continue;
					} else {
						$shown = true;
					}
					// If its the first born, give it an image
					if ($first) {
						$menu .= '<li class="dropdown"><a  href="'.$sub['link'].'">';
						$filename = $imagesDir. DIRECTORY_SEPARATOR . $image . '.gif';
						if (file_exists($filename)) {
							list($width, $height, $type, $attr) = getimagesize($filename);
							$menu .= '<img '.$attr.' src="images/'.$image.'.gif" border="0" hspace="2" alt="">';
						} else {
							$menu .= $image;
						}
						$menu .= '</a>'."\n";
						if (count($link) > 1) {
							$menu .= '<ul>'."\n";
						}
						$first = false;
					}
					// If it's not an only child, don't show the first item as a child
					if (count($link) > 1) {
						$menu .= '<li><a class="menu_'.$image.'" href="'.$sub['link'].'">'.$sub['text'].'</a></li>'."\n";
					}
				}
				if ($shown) {
					if (count($link) > 1) {
						$menu .= '</ul>'."\n";
					}
					$menu .= '</li>'."\n";
				}
			}
		}
		$menu .= '</ul></div>'."\n";

		return $menu;
	}

	/**
	* ShowInfoTip
	* Shows info tips based on whether the user wants to see them or not (and if they are logged in).
	* If we are on the send page and cron jobs are not enabled, we always show the cron tip.
	* If we are not on the send page, or cron jobs are enabled, we show a random tip.
	*
	* @see GetUser
	* @see User_API::InfoTips
	*
	* @return Void Prints out the tip, doesn't return anything.
	*/
	function ShowInfoTip()
	{
		$user = &GetUser();
		if (!$user) {
			return; // if we're not logged in we can't show anything.
		}

		$session = &GetSession();
		if ($session->Get('LicenseError')) {
			return;
		}

		if (!$user->InfoTips()) {
			return;
		}

		$this->LoadLanguageFile('InfoTips');

		$page = 'index';
		if (isset($_GET['Page'])) {
			$page = strtolower($_GET['Page']);
		}

		$action = (isset($_GET['Action'])) ? (strtolower($_GET['Action'])) : 'manage';

		$tipnumber = $tip = false;

		if ($page == 'send' && !SENDSTUDIO_CRON_ENABLED) {
			$GLOBALS['TipIntro'] = GetLang('Infotip_Cron_Intro');
			$GLOBALS['Tip'] = GetLang('Infotip_Cron_Details');
			$GLOBALS['ReadMore'] = '';
			$tipnumber = true;
		}

		$context_helptips = array_keys($GLOBALS['ContextSensitiveTips']);

		if (in_array($page, $context_helptips)) {
			$page_keys = array_keys($GLOBALS['ContextSensitiveTips'][$page]);
			if (in_array($action, $page_keys)) {
				if (isset($GLOBALS['ContextSensitiveTips'][$page][$action])) {
					$tipsize = sizeof($GLOBALS['ContextSensitiveTips'][$page][$action]);
					if ($tipsize > 1) {
						$tipnumber = mt_rand(1, $tipsize);
					} else {
						$tipnumber = 1;
					}

					$tip = $GLOBALS['ContextSensitiveTips'][$page][$action][$tipnumber-1];
				} else {
					if (sizeof($page_keys) == 1) {
						$tipsize = sizeof($GLOBALS['ContextSensitiveTips'][$page]);
						if ($tipsize > 1) {
							$tipnumber = mt_rand(1, $tipsize);
						} else {
							$tipnumber = 1;
						}

						$tip = $GLOBALS['ContextSensitiveTips'][$page][$tipnumber-1];
					}
				}
			}

			if ($tip) {
				$GLOBALS['TipIntro'] = GetLang('Infotip_' . $tip . '_Intro');
				$GLOBALS['Tip'] = GetLang('Infotip_' . $tip . '_Details');
				if (defined('LNG_Infotip_' . $tip . '_ReadMoreLink')) {
					$GLOBALS['ReadMoreLink'] = SENDSTUDIO_RESOURCES_URL . '/tutorials/' . GetLang('Infotip_' . $tip . '_ReadMoreLink');
					$GLOBALS['ReadMoreInfo'] = GetLang('Infotip_' . $tip . '_ReadMore');

					$GLOBALS['InfoTip_ReadMore'] = $this->ParseTemplate('InfoTips_ReadMore', true, false);
				}
			}
		}

		$GLOBALS['ReadMoreLink'] = 'index.php?Page=Infotips#tip';

		if (!$tipnumber) {
			$GLOBALS['TipIntro'] = GetLang('Infotip_Intro');
			$tipnumber = mt_rand(1, Infotip_Size);

			$GLOBALS['ReadMoreInfo'] = GetLang('Infotip_ReadMore');

			$GLOBALS['Extra'] = ':';
			$GLOBALS['Tip'] = GetLang('Infotip_' . $tipnumber . '_Intro');
			$GLOBALS['ReadMoreLink'] .= $tipnumber;
			$GLOBALS['TipNumber'] = $tipnumber;
			$GLOBALS['InfoTip_ReadMore'] = $this->ParseTemplate('InfoTips_ReadMore', true, false);
		}

		$this->ParseTemplate('InfoTips');
	}

	/**
	* _GenerateHelpTip
	* Generates a help tip dynamically.
	* <b>Example</b>
	* If you pass in 'LNG_HLP_Status' - the tiptitle is 'LNG_Status', the description is 'LNG_HLP_Status'.
	*
	* @param String $tipname The name of the tip to create. This will get the variable from the language file and replace it and the title as necessary. The helptip title is the tipname.
	*
	* @see GetRandomId
	* @see ParseTemplate
	*
	* @return String The help tip that is generated.
	*/
	function _GenerateHelpTip($tipname=false)
	{
		if (!$tipname) {
			return false;
		}

		$tipname = str_replace(array('%%', 'LNG_'), '', $tipname);

		$rand = $this->GetRandomId();

		$tiptitle = str_replace('HLP_', '', $tipname);

		$helptip = '<img onMouseOut="HideHelp(\'' . $rand . '\');" onMouseOver="ShowHelp(\'' . $rand . '\', \'' . GetLang($tiptitle) . '\', \'' . GetLang($tipname) . '\');" src="images/help.gif" width="24" height="16" border="0"><div style="display:none" id="' . $rand . '"></div>';
		return $helptip;
	}

	/**
	* GetRandomId
	* Generates a random id for tooltips to use.
	* Stores any random helptip id's in this->_RandomStrings so we can make sure there are no duplicates.
	*
	* @see _GenerateHelpTip
	* @see _RandomStrings
	*
	* @return String Returns a string to use as the random tooltip name.
	*/
	function GetRandomId()
	{
		$chars = array();
		foreach (range('a', 'z') as $p => $char) {
			$chars[] = $char;
		}
		foreach (range('A', 'Z') as $p => $char) {
			$chars[] = $char;
		}
		foreach (range('0', '9') as $p => $char) {
			$chars[] = $char;
		}

		while (true) {
			$rand = 'ss';
			$max = sizeof($chars) - 1;
			while (strlen($rand) < 10) {
				$randchar = rand(0, $max);
				$rand .= $chars[$randchar];
			}

			if (!in_array($rand, $this->_RandomStrings)) {
				$this->_RandomStrings[] = $rand;
				break;
			}
		}
		return $rand;
	}

	/**
	* SetupPaging
	* Sets up the paging header with page numbers (using $this->_PagesToShow), sets up the 'Next/Back' links, 'First Page/Last Page' links and so on - based on how many records there are, which page you are on currently and the number of records to display per page.
	* Gets settings from the session if it can (based on what you've done previously).
	* Sets the $GLOBALS['DisplayPage'] and $GLOBALS['PerPageDisplayOptions'] so the template can be parsed properly.
	*
	* @param Int $numrecords The number of records to calculate pages for
	* @param Int $currentpage The current page that we're on (so we can highlight the right one)
	* @param Int $perpage Number of records per page we're going to show so we can calculate the right page
	*
	* @see _PagesToShow
	* @see GetSession
	* @see Session::Get
	* @see User_API::GetSettings
	* @see ParseTemplate
	*
	* @return Void Doesn't return anything. Places the paging in global variables GLOBALS['Paging'] and GLOBALS['PagingBottom']
	*/
	function SetupPaging($numrecords=0, $currentpage=1, $perpage=20)
	{
		$session = &GetSession();
		$thisuser = $session->Get('UserDetails');
		$display_settings = $thisuser->GetSettings('DisplaySettings');
		if (empty($display_settings)) {
			$display_settings = array('NumberToShow' => $this->_PerPageDefault);
		}

		$PerPageDisplayOptions = '';
		foreach (array('5', '10', '20', '30', '50', '100', '200') as $p => $numtoshow) {
			$PerPageDisplayOptions .= '<option value="' . $numtoshow . '"';
			if ($numtoshow == $display_settings['NumberToShow']) {
				$PerPageDisplayOptions .= ' SELECTED';
			}
			$PerPageDisplayOptions .= '>' . $this->FormatNumber($numtoshow) . '</option>';
		}
		$GLOBALS['PerPageDisplayOptions'] = $PerPageDisplayOptions;

		if (!$numrecords || $numrecords < 0) {
			$GLOBALS['PagingBottom'] = '<br />';
			$GLOBALS['Paging'] = '<br />';
			return false;
		}

		if ($currentpage < 1) {
			$currentpage = 1;
		}

		if ($perpage < 1) {
			$perpage = 10;
		}

		$num_pages = ceil($numrecords / $perpage);
		if ($currentpage > $num_pages) {
			$currentpage = $num_pages;
		}

		$prevpage = ($currentpage > 1) ? ($currentpage - 1) : 1;
		$nextpage = (($currentpage+1) > $num_pages) ? $num_pages : ($currentpage+1);

		$sortinfo = $this->GetSortDetails();

		$direction = $sortinfo['Direction'];
		$sort = $sortinfo['SortBy'];
		$sortdetails = '&SortBy=' . $sort . '&Direction=' . $direction;

		$string = '(' . GetLang('Page') . ' ' . $this->FormatNumber($currentpage) . ' ' . GetLang('Of') . ' ' . $this->FormatNumber($num_pages) . ')&nbsp;&nbsp;&nbsp;&nbsp;';

		$display_page_name = 'DisplayPage';
		if (isset($GLOBALS['PPDisplayName'])) {
			$display_page_name .= $GLOBALS['PPDisplayName'];
		}

		if ($currentpage > 1) {
			$string .= '<a href="index.php?Page=%%PAGE%%' . $sortdetails . '&'.$display_page_name.'=1" title="' . GetLang('GoToFirst') . '">&laquo;</a>&nbsp;|&nbsp;';
			$string .= '<a href="index.php?Page=%%PAGE%%' . $sortdetails . '&'.$display_page_name.'=' . $prevpage . '">' . GetLang('PagingBack') . '</a>&nbsp;|';
		} else {
			$string .= '&laquo;&nbsp;|&nbsp;';
			$string .= GetLang('PagingBack') . '&nbsp;|';
		}

		if ($num_pages > $this->_PagesToShow) {
			$start_page = $currentpage - (floor($this->_PagesToShow/2));
			if ($start_page < 1) {
				$start_page = 1;
			}

			$end_page = $currentpage + (floor($this->_PagesToShow/2));
			if ($end_page > $num_pages) {
				$end_page = $num_pages;
			}

			if ($end_page < $this->_PagesToShow) {
				$end_page = $this->_PagesToShow;
			}

			$pagestoshow = ($end_page - $start_page);
			if (($pagestoshow < $this->_PagesToShow) && ($num_pages > $this->_PagesToShow)) {
				$start_page = ($end_page - $this->_PagesToShow+1);
			}

		} else {
			$start_page = 1;
			$end_page = $num_pages;
		}

		for ($pageid = $start_page; $pageid <= $end_page; $pageid++) {
			if ($pageid > $num_pages) {
				break;
			}

			$string .= '&nbsp;';
			if ($pageid == $currentpage) {
				$string .= '<b>' . $pageid . '</b>';
			} else {
				$string .= '<a href="index.php?Page=%%PAGE%%' . $sortdetails . '&'.$display_page_name.'=' . $pageid . '">' . $pageid . '</a>';
			}
			$string .= '&nbsp;|';
		}

		if ($currentpage == $num_pages) {
			$string .= '&nbsp;' . GetLang('PagingNext') . '&nbsp;|';
			$string .= '&nbsp;&raquo;';
		} else {
			$string .= '&nbsp;<a href="index.php?Page=%%PAGE%%' . $sortdetails . '&'.$display_page_name.'=' . $nextpage . '">' . GetLang('PagingNext') . '</a>&nbsp;|';
			$string .= '&nbsp;<a href="index.php?Page=%%PAGE%%' . $sortdetails . '&'.$display_page_name.'=' . $num_pages . '" title="' . GetLang('GoToLast') . '">&raquo;</a>';
		}

		$GLOBALS['DisplayPage'] = $string;

		if ($perpage > $this->_PagingMinimum && $numrecords > $perpage) {
			$paging_bottom = $this->ParseTemplate('Paging_Bottom', true, false);
		} else {
			$paging_bottom = '<br />';
		}

		$GLOBALS['PagingBottom'] = $paging_bottom;
	}

	/**
	* FormatNumber
	* Formats the number passed in according to language variables and returns the value.
	*
	* @param Int $number Number to format
	* @param Int $decimalplaces Number of decimal places to format to
	*
	* @see GetLang
	*
	* @return String The number formatted
	*/
	function FormatNumber($number=0, $decimalplaces=0)
	{
		return number_format((float)$number, $decimalplaces, GetLang('NumberFormat_Dec'), GetLang('NumberFormat_Thousands'));
	}

	/**
	* PrintDate
	* Prints the date according to the language variables and returns the string value.
	* Uses AdjustTime to convert from server time to local user time before displaying.
	*
	* @param Int $timestamp Timestamp to print.
	* @param String $dateformat The date format you want to print rather than the language variable DateFormat
	*
	* @see LNG_DateFormat
	* @see GetLang
	* @see AdjustTime
	*
	* @return String This will return the date formatted, adjusted for the users timezone.
	*/
	function PrintDate($timestamp=0, $dateformat=false)
	{
		if ($dateformat) {
			return AdjustTime($timestamp, false, $dateformat, true);
		}

		$now = AdjustTime();
		$seconds = $now % 86400; // find number of seconds that today has had so far, so we can remove it.
		$today = $now - $seconds;

		$yesterday = $today - 86400;

		$tomorrow = $today + 86400;

		$two_days = $tomorrow + 86400;

		if ($timestamp < $today && $timestamp >= $yesterday) {
			return GetLang('Yesterday_Date');
		}

		if ($timestamp >= $today && $timestamp < $tomorrow) {
			return GetLang('Today_Date');
		}

		if ($timestamp >= $tomorrow && $timestamp < $two_days) {
			return GetLang('Tomorrow_Date');
		}
		return AdjustTime($timestamp, false, GetLang('DateFormat'), true);
	}

	/**
	* PrintTime
	* Prints the time according to the language variables and returns the string value.
	* Uses AdjustTime to convert from server time to local user time before displaying.
	*
	* @param Int $timestamp Timestamp to print.
	* @param Boolean $stats_format If this is a stats time, we use a different format (without the day, month or year). By default we use the TimeFormat language variable. If this is set to true, we use the Stats_TimeFormat variable.
	*
	* @see GetLang
	* @see AdjustTime
	*
	* @return String This will return the time formatted, adjusted for the users timezone.
	*/
	function PrintTime($timestamp=0, $stats_format=false)
	{
		if ($timestamp == 0) {
			$timestamp = AdjustTime(0, true, null, true);
		}

		$now = AdjustTime();
		$seconds = $now % 86400; // find number of seconds that today has had so far, so we can remove it.
		$today = $now - $seconds;

		$yesterday = $today - 86400;

		$tomorrow = $today + 86400;

		$two_days = $tomorrow + 86400;

		if ($timestamp >= $today && $timestamp < $tomorrow) {
			if ($stats_format) {
				return sprintf(GetLang('Today_Time'), AdjustTime($timestamp, false, GetLang('Stats_TimeFormat'), true));
			}
			return sprintf(GetLang('Today_Time'), AdjustTime($timestamp, false, GetLang('TimeFormat'), true));
		}

		if ($timestamp < $today && $timestamp >= $yesterday) {
			if ($stats_format) {
				return sprintf(GetLang('Yesterday_Time'), AdjustTime($timestamp, false, GetLang('Stats_TimeFormat'), true));
			}
			return sprintf(GetLang('Yesterday_Time'), AdjustTime($timestamp, false, GetLang('TimeFormat'), true));
		}

		if ($timestamp >= $tomorrow && $timestamp < $two_days) {
			if ($stats_format) {
				return sprintf(GetLang('Tomorrow_Time'), AdjustTime($timestamp, false, GetLang('Stats_TimeFormat'), true));
			}
			return sprintf(GetLang('Tomorrow_Time'), AdjustTime($timestamp, false, GetLang('TimeFormat'), true));
		}

		return AdjustTime($timestamp, false, GetLang('TimeFormat'), true);
	}

	/**
	* LoadLanguageFile
	* Loads a language file for this class unless you pass in a language file to load.
	*
	* @param String $languagefile Languagefile to load. This is useful when you are loading a different language file other than this class. Eg. The logout languagefile on the login page.
	*
	* @see GetLang
	*
	* @return Boolean Whether loading the language file worked or not.
	*/
	function LoadLanguageFile($languagefile=null)
	{
		if (is_null($languagefile)) {
			$languagefile = get_class($this);
		}

		$languagefile = strtolower($languagefile);
		$langfile = SENDSTUDIO_LANGUAGE_DIRECTORY . '/' . $languagefile . '.php';

		if (isset($GLOBALS['LoadedLanguages']) && is_array($GLOBALS['LoadedLanguages'])) {
			$loaded_languages = $GLOBALS['LoadedLanguages'];
		} else {
			$loaded_languages = array();
		}

		if (in_array($langfile, $loaded_languages)) {
			return true;
		}

		if (!is_file($langfile)) {
			trigger_error('No Language file for ' . $languagefile . ' area', SENDSTUDIO_ERROR_FATAL);
		}
		require($langfile);

		$GLOBALS['LoadedLanguages'][] = $langfile;
		return true;
	}

	/**
	* GetSortDetails
	* Returns an array of sort information.
	*
	* @see _DefaultSort
	* @see _DefaultDirection
	*
	* @return Array Array of sort information including sort direction and what field sort by.
	*/
	function GetSortDetails()
	{
		$sort = array();

		$sortby = (isset($_GET['SortBy'])) ? strtolower($_GET['SortBy']) : $this->_DefaultSort;
		$direction = (isset($_GET['Direction'])) ? $_GET['Direction'] : $this->_DefaultDirection;

		if (strtolower($direction) == 'up' || strtolower($direction) == 'asc') {
			$direction = 'asc';
		} else {
			$direction = 'desc';
		}

		$sort['SortBy'] = $sortby;
		$sort['Direction'] = $direction;

		$sort['Secondary'] = false;
		$sort['SecondaryDirection'] = false;

		if (in_array($sortby, array_keys($this->_SecondarySorts))) {
			$sort['Secondary'] = $this->_SecondarySorts[$sortby]['field'];
			$sort['SecondaryDirection'] = $this->_SecondarySorts[$sortby]['order'];
		}

		return $sort;
	}

	/**
	* GetCurrentPage
	* Returns the current pageid. This is used for paging.
	*
	* @return Int Current page id.
	*/
	function GetCurrentPage()
	{
		$pageid = (isset($_GET['DisplayPage'])) ? (int)$_GET['DisplayPage'] : 1;
		if ($pageid <= 0) {
			$pageid = 1;
		}

		return $pageid;
	}

	/**
	* GetPerPage
	* Gets the number to show based on your session. If you don't have a session, it sets a default of this->_PerPageDefault
	*
	* @see _PerPageDefault
	*
	* @return Int Number to show per page
	*/
	function GetPerPage()
	{
		$session = &GetSession();
		$thisuser = $session->Get('UserDetails');
		$display_settings = $thisuser->GetSettings('DisplaySettings');
		if (empty($display_settings)) {
			$perpage = $this->_PerPageDefault;
		} else {
			$perpage = $display_settings['NumberToShow'];
		}
		return $perpage;
	}

	/**
	* FetchEditor
	* Fetches the editor for you to display. It loads the data from the session and gets the html or text editor as it needs to.
	*
	* @see GetHTMLEditor
	* @see GetSession
	* @see Session::Get
	*
	* @return Mixed Returns false if the data isn't in the session. Otherwise returns the editor and it's contents.
	*/
	function FetchEditor()
	{
		$type = ucwords(get_class($this));
		$session = &GetSession();
		$details = $session->Get($type);
		if (empty($details)) {
			return false;
		}

		$format = $details['Format'];
		$id = (isset($details['id'])) ? $details['id'] : 0;
		switch ($format) {
			case 'b':
				$GLOBALS['TextContent'] = $details['contents']['text'];
				$GLOBALS['HTMLContent'] = $this->GetHTMLEditor($details['contents']['html'], $id);
				$editor = $this->ParseTemplate('Editor_Multipart', true, false);
			break;

			case 'h':
				$GLOBALS['HTMLContent'] = $this->GetHTMLEditor($details['contents']['html'], $id);
				$editor = $this->ParseTemplate('Editor_HTML', true, false);
			break;

			default:
			case 't':
				$GLOBALS['TextContent'] = $details['contents']['text'];
				$editor = $this->ParseTemplate('Editor_Text', true, false);
			break;
		}
		return $editor;
	}

	/**
	* GetHTMLEditor
	* Gets the editor - whether it's devedit or the regular editor, inserts the content and returns it.
	*
	* @param String $value The content to put in the editor.
	* @param Int $id The ID of what you're loading. This allows us to use the right location for storing images etc.
	* @param Int $height The height of the editor.
	* @param String $name Name of the editor. This allows us to place more than one devedit on the same page.
	*
	* @see FetchEditor
	*
	* @return String Returns the editor and it's content.
	*/
	function GetHTMLEditor($value='', $id=false, $height=500, $name='myDevEditControl')
	{
		$type = ucwords(get_class($this));

		$deveditpath = str_replace('index.php', 'de', $_SERVER['PHP_SELF']);
		$ROOTDIR1 = $deveditpath;

		$session = &GetSession();
		$user = $session->Get('UserDetails');

		if (!$id) {
			$ROOTDIR2 = '/temp/user/' . $user->userid;
			CreateDirectory(TEMP_DIRECTORY . '/user/' . $user->userid);
		} else {
			$ROOTDIR2 = '/temp/' . strtolower($type) . '/' . $id;
			CreateDirectory(TEMP_DIRECTORY . '/' . strtolower($type) . '/' . $id);
		}

		// Setup the REQUEST_URI on IIS servers using ISAPI_Rewrite
		if (isset($_SERVER['HTTP_X_REWRITE_URL'])) {
			$_SERVER['REQUEST_URI'] = $_SERVER['HTTP_X_REWRITE_URL'];
		}

		if (!isset($_SERVER['REQUEST_URI']) || $_SERVER['REQUEST_URI'] == '') {
			$_SERVER['REQUEST_URI'] = $_SERVER['SCRIPT_NAME'].'?'.(isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '');
		}

		$parts = explode('/', $_SERVER['REQUEST_URI']);

		$dir = array_slice(explode(DIRECTORY_SEPARATOR, __FILE__), 0, -sizeof($parts));

		/**
		* Now that we have trimmed off the right number of fields from the directory, we implode it back again using the directory separator.
		*/
		$docroot = implode(DIRECTORY_SEPARATOR, $dir);
		$docroot = str_replace('\\', '/', $docroot);

		$ROOTDIR2 = dirname($ROOTDIR1) . $ROOTDIR2;

		ob_start();

		if (!class_exists('DevEdit')) {
			include(dirname(__FILE__) . '/../de/class.devedit.php');
		}

		SetDocumentRoot($docroot);

		$myDE = new devedit;
		$myDE->SetName($name);
		SetDevEditPath($ROOTDIR1);
		$myDE->SetLanguage(DE_AMERICAN);
		$myDE->SetPathType(DE_PATH_TYPE_FULL);
		$myDE->SetDocumentType(DE_DOC_TYPE_HTML_PAGE);
		$myDE->HideHelpButton();
		$myDE->HideSaveButton();
		$myDE->SetImageDisplayType(DE_IMAGE_TYPE_THUMBNAIL);
		$myDE->SetFlashDisplayType(DE_FLASH_TYPE_THUMBNAIL);
		$myDE->SetImageUploadSize(SENDSTUDIO_MAX_IMAGEWIDTH,SENDSTUDIO_MAX_IMAGEHEIGHT);
		if (SENDSTUDIO_CHARSET == 'UTF-8') {
			$myDE->DisableHTMLEntities();
		}

		// Set the path to the folder that contains the flash files for the flash manager
		$myDE->SetFlashPath($ROOTDIR2);
		$myDE->SetMediaPath($ROOTDIR2);
		$myDE->SetLinkPath($ROOTDIR2);

		$myDE->SetValue($value);

		// Set the default wysiwyg mode - after the intial load the mode
		// is determined from a cookie
		$myDE->SetDevEditMode("Simple");

		// Disable the preview tab
		$myDE->DisablePreviewMode();

		$myDE->AddCustomLink(GetLang('Link_MailingListArchives'), "%%mailinglistarchives%%");
		$myDE->AddCustomLink(GetLang('Link_WebVersion'), "%%webversion%%");
		$myDE->AddCustomLink(GetLang('Link_Unsubscribe'), "%%unsubscribelink%%");

		$myDE->HideFlashButton();
		$myDE->HideMediaButton();

		$myDE->HideFlashTab();
		$myDE->HideMediaTab();

		$user = &GetUser();

		$formapi = $this->GetApi('Forms');

		$modify_forms = $formapi->GetUserForms($user->userid, 'modify');
		if (!empty($modify_forms)) {
			foreach ($modify_forms as $p => $formdetails) {
				$myDE->AddCustomLink(htmlspecialchars($formdetails['name']), "%%modifydetails_" . $formdetails['formid'] . "%%");
			}
		}

		$sendfriend_forms = $formapi->GetUserForms($user->userid, 'friend');
		if (!empty($sendfriend_forms)) {
			foreach ($sendfriend_forms as $p => $formdetails) {
				$myDE->AddCustomLink(htmlspecialchars($formdetails['name']), "%%sendfriend_" . $formdetails['formid'] . "%%");
			}
		}

		$myDE->AddToolbarButton("CustomFields", 97, 20, SENDSTUDIO_IMAGE_URL . "/customfields_toolbaricon.gif", "ShowCustomFields_toolbar('html', '" . $name . "')");

		// Setup the toolbars
		$rows = array (
			"Fullscreen,Undo,Redo,Paste,Findreplace,Spellcheck,-,Bold,Underline,Italic,-,OrderedList,UnorderedList,-,JustifyLeft,JustifyCenter,JustifyRight,JustifyFull,-,CreateLink,Table",
			"Fontname,Fontsize,-,Fontcolor,Highlight,-,Image,-,CustomFields,Help",
		);
		$myDE->AddToolbar("Simple", implode(",|,", $rows));

		$rows = array (
			"Fullscreen,Undo,Redo,Paste,Findreplace,Spellcheck,Bold,Underline,Italic,Strikthrough,-,OrderedList,UnorderedList,-,JustifyLeft,JustifyCenter,JustifyRight,JustifyFull,-,CustomFields,Help",
			"Spellcheck,-,RemoveFormat,-,Indent,Outdent,-,SubScript,SuperScript,CreateLink,CreateEmailLink,Anchor,-,Image,-,Table,Form",
			"Fontname,Fontsize,Formatblock,Fontcolor,Highlight,HR,Insertchars,Showborders",
		);
		$myDE->AddToolbar("Complete", implode(",|,", $rows));

		//Display the DevEdit control. This *MUST* be called between <form> and </form> tags
		$myDE->ShowControl("100%",$height, $ROOTDIR2);

		$editor = ob_get_contents();
		ob_end_clean();

		// Change the width of the editor
		$GLOBALS["EditorWidth"] = "100%";

		if (isset($_COOKIE["screenWidth"])) {
			$width = (int)$_COOKIE["screenWidth"];

			if ($width < 1024) {
				$GLOBALS["EditorWidth"] = 400;
			}
		}

		return $editor;
	}

	/**
	* ConvertContent
	* Changes content references from temporary storage under a user's id - to it's final location - under the type and it's id. Eg newsletter/1.
	*
	* @param String $content Content to change paths for.
	* @param String $dest The destination (eg newsletter or template).
	* @param Int $id The destinations id.
	*
	* @see GetSession
	* @see Session::Get
	*
	* @return String Returns the converted content.
	*/
	function ConvertContent($content='', $dest=false, $id=0)
	{
		if (!$dest || !$id) {
			return $content;
		}

		$session = &GetSession();
		$user = $session->Get('UserDetails');
		$sourceurl = SENDSTUDIO_APPLICATION_URL . '/admin/temp/user/' . $user->userid;
		$destinationurl = SENDSTUDIO_APPLICATION_URL . '/admin/temp/' . $dest . '/' . $id;
		$content = str_replace($sourceurl, $destinationurl, $content);
		return $content;
	}

	/**
	* MoveFiles
	* Moves uploaded images from temporary storage under a user's id - to it's final location - under the type and it's id. Eg newsletter/1.
	*
	* @param String $destination The destination (eg newsletter or template).
	* @param Int $id The destinations id.
	*
	* @see GetSession
	* @see Session::Get
	* @see CreateDirectory
	* @see list_files
	*
	* @return Boolean Returns false if it can't create the paths or it can't copy the necessary files. Returns true if everything worked ok.
	*/
	function MoveFiles($destination=false, $id=0)
	{
		if (!$destination || !$id) {
			return false;
		}

		$destinationdir = TEMP_DIRECTORY . '/' . $destination . '/' . $id;
		$createdir = CreateDirectory($destinationdir);
		if (!$createdir) {
			return false;
		}

		$session = &GetSession();
		$user = $session->Get('UserDetails');
		$sourcedir = TEMP_DIRECTORY . '/user/' . $user->userid;
		$file_list = list_files($sourcedir);
		if (empty($file_list)) {
			return true;
		}

		$result = true;

		foreach ($file_list as $p => $filename) {
			if (!copy($sourcedir . '/' . $filename, $destinationdir . '/' . $filename)) {
				$result = false;
			}
		}
		return $result;
	}

	/**
	* SaveAttachments
	* Saves uploaded attachments in the appropriate place. Returns a report on what happened and why some attachments might not have uploaded. Checks whether the file extension is valid, permissions and so on.
	*
	* @param String $destination Where to save the files. Eg templates, newsletters, autoresponders.
	* @param Int $id The id of the destination.
	*
	* @see CreateDirectory
	* @see ValidFileExtensions
	*
	* @return Array Returns a status and a report. If all uploaded ok, it returns true and how many uploaded. If any can't be uploaded it returns false and a list of reasons why a file couldn't be uploaded.
	*/
	function SaveAttachments($destination=false, $id=0)
	{
		if (empty($_FILES)) {
			return array(true, '');
		}

		if (!$destination || !$id) {
			return array(false, 'Invalid Data');
		}

		$id = (int)$id;
		$destinationdir = TEMP_DIRECTORY . '/' . strtolower($destination) . '/' . $id . '/attachments';
		$createdir = CreateDirectory($destinationdir);
		if (!$createdir) {
			return array(false, GetLang('UnableToCreateDirectory'));
		}

		$result = true;
		$success = 0;
		$errors = array();

		if (!is_writable($destinationdir)) {
			$errors[] = sprintf(GetLang('DirectoryNotWritable'), $destinationdir);
			$result = false;
		}

		if ($result) {
			foreach ($_FILES['attachments']['name'] as $pos => $name) {
				if ($name == '') {
					continue;
				}

				if ($_FILES['attachments']['tmp_name'][$pos] == '' || $_FILES['attachments']['tmp_name'][$pos] == 'none') {
					continue;
				}

				$fileparts = pathinfo($name);
				$extension = false;
				if (isset($fileparts['extension'])) {
					$extension = strtolower($fileparts['extension']);
				}

				if (!in_array($extension, $this->ValidFileExtensions)) {
					$errors[] = $name . ' (' . sprintf(GetLang('FileExtensionNotValid'), $extension) . ')';
					$result = false;
					continue;
				}

				$size = $_FILES['attachments']['size'][$pos];
				if ($size > $this->MaxFileSize) {
					$errors[] = $name . ' (' . sprintf(GetLang('FileTooBig'), EasySize($size), EasySize($this->MaxFileSize)) . ')';
					$result = false;
					continue;
				}

				$destination = $destinationdir . '/' . $name;

				if (!move_uploaded_file($_FILES['attachments']['tmp_name'][$pos], $destination)) {
					if (!is_uploaded_file($_FILES['attachments']['tmp_name'][$pos])) {
						$errors[] = $name . ' (' . GetLang('NotUploadedFile') . ')';
					} else {
						$errors[] = $name . ' (' . GetLang('UnableToUploadFile') . ')';
					}
					$result = false;
					continue;
				}
				chmod($destination, 0644);
				$success++;
			}
		}

		$report = '';
		if ($success > 0) {
			if ($success == 1) {
				$report .= GetLang('FileUploadSuccessful_One') . '<br/>';
			} else {
				$report .= sprintf(GetLang('FileUploadSuccessful_Many'), $this->FormatNumber($success)) . '<br/>';
			}
		}

		if (!empty($errors)) {
			$report .= GetLang('FileUploadFailure') . '<br/>- ';
			$report .= implode('<br/>- ', $errors);
		}

		return array($result, $report);
	}

	/**
	* CleanupAttachments
	* Removes attachments from a particular area (template, newsletter etc) based on the DeleteAttachments post variable. If the list is empty, it returns true for the status and false for the report so you can quickly see whether to display a message or not.
	*
	* @param String $area Where to remove the files from. Eg templates, newsletters, autoresponders.
	* @param Int $id The id of the destination.
	*
	* @see list_files
	*
	* @return Array Returns a status and a report. If all were deleted ok, it returns true and how many deleted. If any can't be deleted it returns false and a list of reasons why a file couldn't be deleted.
	*/
	function CleanupAttachments($area=false, $id=0)
	{
		$deleteattachments_list = (isset($_POST['DeleteAttachments'])) ? $_POST['DeleteAttachments'] : array();

		if (empty($deleteattachments_list)) {
			return array(true, false);
		}

		if (!$area || !$id) {
			return array(false, 'Invalid Data');
		}

		$id = (int)$id;

		$realdir = TEMP_DIRECTORY . '/' . strtolower($area) . '/' . $id . '/attachments';
		if (!is_dir($realdir)) {
			return array(false, 'Directory Not Found');
		}

		$result = true;
		$success = 0;
		$errors = array();

		$filelist = list_files($realdir);

		foreach ($deleteattachments_list as $pos => $filetodelete) {
			$filetodelete = urldecode($filetodelete);
			if (!in_array($filetodelete, $filelist)) {
				$result = false;
				$errors[] = $filetodelete . ' (' . GetLang('FileNotFound') . ')';
				continue;
			}
			if (!unlink($realdir . '/' . $filetodelete)) {
				$result = false;
				$errors[] = $filetodelete . ' (' . GetLang('UnableToDelete') . ')';
				continue;
			}
			$success++;
		}

		$report = '';
		if ($success > 0) {
			if ($success == 1) {
				$report .= GetLang('FileDeleteSuccessful_One') . '<br/>';
			} else {
				$report .= sprintf(GetLang('FileDeleteSuccessful_Many'), $this->FormatNumber($success)) . '<br/>';
			}
		}

		if (!empty($errors)) {
			$report .= GetLang('FileDeleteFailure') . '<br/>- ';
			$report .= implode('<br/>- ', $errors);
		}

		return array($result, $report);
	}

	/**
	* GetAttachments
	* GetAttachments prints a small table with file attachments based on the area and id passed in. It also turns the filename into a link (opens in a new window by default) so you can see what you've uploaded and preview the attachment.
	*
	* @param String $area Where to get the files from. Eg templates, newsletters, autoresponders.
	* @param Int $id The id of the destination.
	* @param Boolean $listonly Whether to just retrieve a list of files or not. If this is false (default), it will print a report (table) - with options to delete files etc.
	*
	* @see list_files
	*
	* @return Mixed Returns false if there are no files in the directory or the directory doesn't exist. If you set listonly to true it will only return an array with the real path and the list of files. Otherwise returns a string ready for printing with the filename and a checkbox next to it for easy deletion.
	*/
	function GetAttachments($area=false, $id=0, $listonly=false)
	{
		if (!$area || !$id) {
			return false;
		}

		$id = (int)$id;
		$area = strtolower($area);
		$realdir = TEMP_DIRECTORY . '/' . $area . '/' . $id . '/attachments';

		if (!is_dir($realdir)) {
			return false;
		}

		$filelist = list_files($realdir);
		if (empty($filelist)) {
			return false;
		}

		if ($listonly) {
			return array('path' => $realdir, 'filelist' => $filelist);
		}

		$report = '<table border="0" cellspacing="0" cellpadding="0">';

		if (!empty($filelist)) {
			$report .= '<tr><td class="FieldLabel">' . $this->ParseTemplate('Not_Required', true, false) . GetLang('ExistingAttachments') . '</td>';
		}

		$fpos = 0;
		foreach ($filelist as $pos => $filename) {
			if ($fpos > 0) {
				$report .= '<tr><td>&nbsp;</td>';
			}

			$report .= '<td>';

			$attach_name = 'DeleteAttachments_' . urlencode($filename);

			$report .= '<input type="checkbox" name="DeleteAttachments[]" id="' . $attach_name . '" value="' . urlencode($filename) . '">&nbsp;';

			$report .= '<label for="' . $attach_name . '">' . GetLang('DeleteAttachment') . '</label>&nbsp;';

			$report .= '<a href="' . SENDSTUDIO_TEMP_URL . '/' . $area . '/' . $id . '/attachments/' . htmlspecialchars($filename, ENT_QUOTES, SENDSTUDIO_CHARSET) . '" target="_blank">' . htmlspecialchars($filename, ENT_QUOTES, SENDSTUDIO_CHARSET) . '</a>&nbsp;&nbsp;';

			$report .= $this->_GenerateHelpTip('LNG_HLP_DeleteAttachment');

			$report .= '</td>';
			$report .= '</tr>';
			$fpos++;
		}
		$report .= '</table>';
		return $report;
	}

	/**
	* PreviewWindow
	* Creates a preview window based on the details passed in.
	*
	* @param Array $details The details to print out. This is an array containing format and also the content.
	*
	* @see Preview::Process
	*
	* @return Void Prints out the main preview frame only. The actual content is displayed by the "Preview" file.
	*/
	function PreviewWindow($details=array())
	{
		if (empty($details)) {
			return false;
		}

		$session = &GetSession();
		$session->Set('PreviewWindow', $details);

		$this->ParseTemplate('Preview_Window_Frameset');
		return;
	}

	/**
	* Display_CustomField
	* Displays a date custom field search box used for searching.
	*
	* @param Array $customfield_info The custom field information to use. This includes the order of the fields (dd/mm/yy).
	* @param Array $defaults The default settings to use for the custom field search. This will preselect the specified dates, if not passed in, it will default to 'today'.
	*
	* @see Subscribers_Manage::Process
	*
	* @return Void Doesn't return anything. Puts information in the GLOBALS['Display_date_Field_X'] placeholders.
	*/
	function Display_CustomField($customfield_info=array(), $defaults=array())
	{
		switch ($customfield_info['fieldtype']) {
			case 'date':
				$fieldsettings = (is_array($customfield_info['fieldsettings'])) ? $customfield_info['fieldsettings'] : unserialize($customfield_info['fieldsettings']);

				if (empty($defaults)) {
					$dd = date('d');
					$mm = date('m');
					$yy = date('Y');
				} else {
					$dd = $defaults['dd'];
					$mm = $defaults['mm'];
					$yy = $defaults['yy'];
				}

				$field_order = array_slice($fieldsettings['Key'], 0, 3);

				$year_start = $fieldsettings['Key'][3];
				$year_end = $fieldsettings['Key'][4];
				if ($year_end == 0) {
					$year_end = date('Y');
				}

				$daylist = '<select name="CustomFields['.$GLOBALS['FieldID'].'][dd]" class="datefield">';
				for ($i=1; $i<=31; $i++) {
					$dom = $i;
					$i = sprintf("%02d", $i);
					$sel = '';
					if ($i==$dd) {
						$sel='SELECTED';
					}

					$daylist.='<option '.$sel.' value="'.sprintf("%02d",$i).'">'. $dom . '</option>';
				}
				$daylist.='</select>';

				$monthlist = '<select name="CustomFields['.$GLOBALS['FieldID'].'][mm]" class="datefield">';
				for ($i=1; $i<=12; $i++) {
					$mth = $i;
					$sel = '';
					$i = sprintf("%02d",$i);

					if ($i==$mm) {
						$sel='SELECTED';
					}

					$monthlist.='<option '.$sel.' value="'.sprintf("%02d",$i).'">'.GetLang($this->Months[$mth]) . '</option>';
				}
				$monthlist.='</select>';

				$yearlist = '<select name="CustomFields['.$GLOBALS['FieldID'].'][yy]" class="datefield">';
				for ($i=$year_start; $i <= $year_end; $i++) {
					$sel = '';
					$i = sprintf("%04d",$i);
					if ($i==$yy) {
						$sel='SELECTED';
					}

					$yearlist.='<option '.$sel.' value="'.sprintf("%02d",$i).'">' . $i . '</option>';
				}
				$yearlist.='</select>';

				foreach ($field_order as $p => $order) {
					switch ($order) {
						case 'day':
							$GLOBALS['Display_date_Field'.($p+1)] = $daylist;
						break;
						case 'month':
							$GLOBALS['Display_date_Field'.($p+1)] = $monthlist;
						break;
						case 'year':
							$GLOBALS['Display_date_Field'.($p+1)] = $yearlist;
						break;
					}
				}
			break;
		}
	}

	/**
	* Search_Display_CustomField
	* Prints out the 'search' version of a custom field. eg, it will show an empty text box for a textbox custom field, show a bunch of tickboxes and so on.
	*
	* @param Array $customfield_info Custom field data to create a search box for. This contains options, settings (eg tick box "X"), name and so on.
	*
	* @return String Returns ths generated search box option.
	*/
	function Search_Display_CustomField($customfield_info=array())
	{
		if (!is_array($customfield_info) || empty($customfield_info)) {
			$GLOBALS['OptionList'] = '';
			$GLOBALS['DefaultValue'] = '';
			$GLOBALS['FieldName'] = '';
			$GLOBALS['FieldID'] = 0;
			$GLOBALS['FieldValue'] = '';
			$customfield_info['FieldValue'] = '';
		}

		if (!isset($customfield_info['FieldValue'])) {
			$customfield_info['FieldValue'] = '';
		}

		$GLOBALS['FieldID'] = $customfield_info['fieldid'];

		switch (strtolower($customfield_info['fieldtype'])) {
			case 'date':
				$GLOBALS['Style_FieldDisplayOne'] = $GLOBALS['Style_FieldDisplayTwo'] = 'none';

				$GLOBALS['FilterDescription'] = sprintf(GetLang('YesFilterByCustomDate'), $customfield_info['name']);

				$field_value = $customfield_info['FieldValue'];

				$options = array('after', 'before', 'exactly', 'between');
				$filterdateoptions = '';
				foreach ($options as $optionp => $option) {
					$selected = '';

					if (is_array($field_value) && isset($field_value['type'])) {
						if ($option == $field_value['type']) {
							$selected = ' SELECTED';
						}
					}
					$filterdateoptions .= '<option value="' . $option . '" ' . $selected . '>' . GetLang(ucwords($option)) . '</option>';
				}
				$GLOBALS['FilterDateOptions'] = $filterdateoptions;

				if (is_array($field_value) && isset($field_value['filter'])) {
					if ($field_value['filter'] == 1) {
						$GLOBALS['FilterChecked'] = ' CHECKED';
						$GLOBALS['Style_FieldDisplayOne'] = '';
					}
					if ($field_value['type'] == 'between') {
						$GLOBALS['Style_FieldDisplayTwo'] = '';
					}
				}

				$optionlist = '';
				$fieldsettings = (is_array($customfield_info['fieldsettings'])) ? $customfield_info['fieldsettings'] : unserialize($customfield_info['fieldsettings']);

				$dd_start = $dd_end = date('d');
				$mm_start = $mm_end = date('m');
				$yy_start = $yy_end = date('Y');

				if (is_array($field_value) && isset($field_value['dd_start'])) {
					$dd_start = $field_value['dd_start'];
					$mm_start = $field_value['mm_start'];
					$yy_start = $field_value['yy_start'];

					$dd_end = $field_value['dd_end'];
					$mm_end = $field_value['mm_end'];
					$yy_end = $field_value['yy_end'];
				}

				$field_order = array_slice($fieldsettings['Key'], 0, 3);

				$daylist_start = $daylist_end = '<select name="CustomFields['.$GLOBALS['FieldID'].'][dd_whichone]" class="datefield">';
				for ($i=1; $i<=31; $i++) {
					$dom = $i;
					$i = sprintf("%02d", $i);
					$sel = '';
					if ($i==$dd_start) {
						$sel='SELECTED';
					}

					$daylist_start.='<option '.$sel.' value="'.sprintf("%02d",$i).'">'.$dom . '</option>';

					$sel = '';
					if ($i==$dd_end) {
						$sel='SELECTED';
					}

					$daylist_end.='<option '.$sel.' value="'.sprintf("%02d",$i).'">'.$dom . '</option>';

				}
				$daylist_start.='</select>';
				$daylist_end.='</select>';

				$monthlist_start = $monthlist_end ='<select name="CustomFields['.$GLOBALS['FieldID'].'][mm_whichone]" class="datefield">';
				for ($i=1; $i<=12; $i++) {
					$mth = $i;
					$sel = '';
					$i = sprintf("%02d",$i);

					if ($i==$mm_start) {
						$sel='SELECTED';
					}

					$monthlist_start.='<option '.$sel.' value="'.sprintf("%02d",$i).'">'.GetLang($this->Months[$mth]) . '</option>';

					if ($i==$mm_end) {
						$sel='SELECTED';
					}

					$monthlist_end .='<option '.$sel.' value="'.sprintf("%02d",$i).'">'.GetLang($this->Months[$mth]) . '</option>';

				}
				$monthlist_start.='</select>';
				$monthlist_end.='</select>';

				$yearlist_start ='<input type="text" maxlength="4" size="4" value="'.$yy_start.'" name="CustomFields['.$GLOBALS['FieldID'].'][yy_whichone]" class="datefield">';
				$yearlist_end ='<input type="text" maxlength="4" size="4" value="'.$yy_end.'" name="CustomFields['.$GLOBALS['FieldID'].'][yy_whichone]" class="datefield">';

				foreach ($field_order as $p => $order) {
					switch ($order) {
						case 'day':
							$GLOBALS['Display_date1_Field'.($p+1)] = str_replace('_whichone', '_start', $daylist_start);
						break;
						case 'month':
							$GLOBALS['Display_date1_Field'.($p+1)] = str_replace('_whichone', '_start', $monthlist_start);
						break;
						case 'year':
							$GLOBALS['Display_date1_Field'.($p+1)] = str_replace('_whichone', '_start', $yearlist_start);
						break;
					}
				}

				foreach ($field_order as $p => $order) {
					switch ($order) {
						case 'day':
							$GLOBALS['Display_date2_Field'.($p+1)] = str_replace('_whichone', '_end', $daylist_end);
						break;
						case 'month':
							$GLOBALS['Display_date2_Field'.($p+1)] = str_replace('_whichone', '_end', $monthlist_end);
						break;
						case 'year':
							$GLOBALS['Display_date2_Field'.($p+1)] = str_replace('_whichone', '_end', $yearlist_end);
						break;
					}
				}
			break;

			case 'dropdown':
			case 'radiobutton':
				if (!is_array($customfield_info['FieldValue'])) {
					$customfield_info['FieldValue'] = array($customfield_info['FieldValue']);
				}

				$fieldsettings = (is_array($customfield_info['fieldsettings'])) ? $customfield_info['fieldsettings'] : unserialize($customfield_info['fieldsettings']);

				$optionlist = '';
				$optionlist .= '<option value="" selected>' . GetLang('None') . '</option>';
				foreach ($fieldsettings['Key'] as $pos => $key) {
					$selected = '';
					if (in_array($key, $customfield_info['FieldValue'])) {
						$selected = ' SELECTED';
					}

					$optionlist .= '<option value="' . htmlspecialchars($key, ENT_QUOTES, SENDSTUDIO_CHARSET) . '"' . $selected . '>' . htmlspecialchars($fieldsettings['Value'][$pos], ENT_QUOTES, SENDSTUDIO_CHARSET) . '</option>';
				}
			break;

			case 'checkbox':
				$fieldsettings = (is_array($customfield_info['fieldsettings'])) ? $customfield_info['fieldsettings'] : unserialize($customfield_info['fieldsettings']);

				if (!is_array($customfield_info['FieldValue'])) {
					$customfield_info['FieldValue'] = array($customfield_info['FieldValue']);
				}

				$optionlist = '';
				$c = 1;
				foreach ($fieldsettings['Key'] as $pos => $key) {
					$checked = '';
					if (in_array($key, $customfield_info['FieldValue'])) {
						$checked = ' CHECKED';
					}

					$label_id = 'CustomFields[' . $customfield_info['fieldid'] . '][' . $key . ']';

					$optionlist .= '<label for="'.$label_id.'"><input type="checkbox" name="'.$label_id.'" id="'.$label_id.'" value="1"' . $checked . '>' . htmlspecialchars($fieldsettings['Value'][$pos], ENT_QUOTES, SENDSTUDIO_CHARSET) . '</label>';

					if ($c % 4 == 0) {
						$optionlist .= '<br/>';
					}
					$c++;
				}
			break;

			default:
				$optionlist = '';
				$GLOBALS['FieldValue'] = htmlspecialchars($customfield_info['FieldValue'], ENT_QUOTES, SENDSTUDIO_CHARSET);
		}
		$GLOBALS['OptionList'] = $optionlist;
		$GLOBALS['FieldName'] = htmlspecialchars($customfield_info['name'], ENT_QUOTES, SENDSTUDIO_CHARSET);
		$display = $this->ParseTemplate('CustomField_Search_' . $customfield_info['fieldtype'], true, false);
		return $display;
	}

	/**
	* GetTemplateList
	* Returns a select box list of templates. This is used for email campaigns and autoresponders to get a list of 'live' templates the user can use.
	*
	* @param Boolean $built_in_only Whether to only show a list of built in templates or not. By default this will be false which means it will include both built in and user templates.
	*
	* @see GetUser
	* @see GetApi
	* @see User_API::Admin
	* @see Templates::GetLiveTemplates
	*
	* @return String The select box options for templates.
	*/
	function GetTemplateList($built_in_only=false)
	{
		$user = &GetUser();

		$templatelist = array();

		if (!$built_in_only) {
			$TemplatesApi = $this->GetApi('Templates');

			if ($user->Admin()) {
				$templatelist = $TemplatesApi->GetLiveTemplates();
			} else {
				$templatelist = $TemplatesApi->GetLiveTemplates($user->userid);
			}
		}

		$template_names = array();

		$template_packs = array();

		if ($user->HasAccess('Templates', 'BuiltIn')) {
			$server_template_list = list_files(SENDSTUDIO_NEWSLETTER_TEMPLATES_DIRECTORY . '/', null, true, true);

			// we only support two folders depth currently so we'll hardcode the look.
			if ($server_template_list) {
				foreach ($server_template_list as $template_name => $sub_templates) {
					if ($template_name == 'CVS') {
						continue;
					}
					$sub_folders = array_keys($sub_templates);
					if (in_array('CVS', $sub_folders)) {
						$pos = array_search('CVS', $sub_folders);
						if ($pos !== false) {
							unset($sub_folders[$pos]);
						}
					}
					if (!empty($sub_folders)) {
						$template_packs[] = array('Name' => $template_name, 'Designs' => $sub_folders);
						continue;
					}
					$template_names[] = $template_name;
				}
			}

			sort($template_names);
			sort($template_packs);
		}

		$templateselect = $this->ParseTemplate('Template_Select_Start', true, false);

		$GLOBALS['TemplateID'] = 0;
		$GLOBALS['TemplateName'] = GetLang('NoTemplate');
		$templateselect .= $this->ParseTemplate('Template_Select_Option', true, false);

		if (!empty($templatelist)) {
			$templateselect .= '<optgroup class="templategroup" label="' . GetLang('Templates_User') . '">';

			foreach ($templatelist as $pos => $templateinfo) {
				$GLOBALS['TemplateID'] = $templateinfo['templateid'];
				$GLOBALS['TemplateName'] = htmlspecialchars($templateinfo['name'], ENT_QUOTES, SENDSTUDIO_CHARSET);
				$templateselect .= $this->ParseTemplate('Template_Select_Option', true, false);
			}

			$templateselect .= '</optgroup>';
		}

		if (!empty($template_names) || !empty($template_packs)) {
			$templateselect .= '<optgroup class="templategroup" label="' . GetLang('Templates_BuiltIn') . '">';

			foreach ($template_names as $p => $templatename) {
				$GLOBALS['TemplateID'] = $templatename;
				$GLOBALS['TemplateName'] = htmlspecialchars($templatename, ENT_QUOTES, SENDSTUDIO_CHARSET);
				$templateselect .= $this->ParseTemplate('Template_Select_Option', true, false);
			}
			$templateselect .= '</optgroup>';
		}

		if (!empty($template_packs)) {
			foreach ($template_packs as $p => $details) {
				sort($details['Designs']);
				$templateselect .= '<optgroup class="templategroup" label="&nbsp;&nbsp;' . $details['Name'] . '">';
				foreach ($details['Designs'] as $d => $name) {
					$GLOBALS['TemplateID'] = $details['Name'].'/'.$name;
					$GLOBALS['TemplateName'] = $name;
					$templateselect .= $this->ParseTemplate('Template_Select_Option', true, false);
				}
				$templateselect .= '</optgroup>';
			}
		}

		$templateselect .= $this->ParseTemplate('Template_Select_End', true, false);
		return $templateselect;
	}

	/**
	* TimeZoneList
	* Creates a dropdown list of timezones.
	* These are loaded from the language file (TimeZones) and it creates the list from the options provided.
	* If we are viewing the settings page, you cannot change the timezone - you will only get one item in the dropdown list.
	* This should hopefully stop confusion about the server timezone compared to the user timezone.
	*
	* @param String $selected_timezone The currently selected timezone (so it can be pre-selected in the list). This corresponds to the GMT offset (eg +10:00).
	*
	* @see LoadLanguageFile
	* @see GetLang
	*
	* @return String Returns an option list of timezones with the timezone pre-selected if possible.
	*/
	function TimeZoneList($selected_timezone='')
	{
		$settings_page = false;
		if (isset($_GET['Page']) && strtolower($_GET['Page']) == 'settings') {
			$settings_page = true;
		}

		$selected_timezone = trim($selected_timezone);
		$this->LoadLanguageFile('TimeZones');
		$list = '';
		foreach ($GLOBALS['SendStudioTimeZones'] as $pos => $offset) {
			$selected = '';
			if ($offset == $selected_timezone) {
				$selected = ' SELECTED';
			}
			$entry = '<option value="' . $offset . '"' . $selected . '>' . GetLang($offset) . '</option>';

			if (!$settings_page) {
				$list .= $entry;
			}

			if ($settings_page && $selected) {
				$list .= $entry;
			}
		}
		return $list;
	}

	/**
	* ChooseList
	* This prints out the select box which makes you choose a list (to start most processes).
	* If there is only one list, it will automatically redirect you to that particular list (depending on which area you're looking for).
	* Otherwise, it prints out the appropriate template for the area you're working with.
	*
	* @param String $page The page you're working with. This can be send, schedule, autoresponders etc.
	* @param String $action Which step you're up to in the process.
	* @param Boolean $autoredirect This is used to stop autoredirection if you only have access to one list. This may be used for example, if you try to send to a mailing list that has no subscribers. If you do try that, without setting that flag you would get an endless loop.
	*
	* @see GetSession
	* @see Session::Get
	* @see User_API::GetLists
	* @see User_API::CanCreateList
	*
	* @return Void Prints out the appropriate template, doesn't return anything.
	*/
	function ChooseList($page='Send', $action='step2', $autoredirect=true)
	{
		$page = strtolower($page);
		$action = strtolower($action);
		$user = &GetUser();
		$lists = $user->GetLists();

		$listids = array_keys($lists);

		if (sizeof($listids) < 1 || $page == '' || $action == '') {
			$GLOBALS['Intro'] = GetLang(ucwords($page) . '_' . ucwords($action));
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
			if ($autoredirect) {
				$location = 'index.php?Page=' . $page . '&Action=' . $action . '&list=' . current($listids);
				?>
				<script language="javascript">
					window.location = '<?php echo $location; ?>';
				</script>
				<?php
				exit();
			}
		}

		if (strtolower($page) == 'autoresponders') {
			$this->DisplayCronWarning();
		}

		$selectlist = '';
		foreach ($lists as $listid => $listdetails) {
			if ($listdetails['subscribecount'] == 1) {
				$subscriber_count = GetLang('Subscriber_Count_One');
			} else {
				$subscriber_count = sprintf(GetLang('Subscriber_Count_Many'), $this->FormatNumber($listdetails['subscribecount']));
			}
			$selectlist .= '<option value="' . $listid . '">' . htmlspecialchars($listdetails['name'], ENT_QUOTES, SENDSTUDIO_CHARSET) . $subscriber_count . '</option>';
		}
		$GLOBALS['SelectList'] = $selectlist;

		$show_filtering_options = $user->GetSettings('ShowFilteringOptions');
		if (!$show_filtering_options || $show_filtering_options == 1) {
			$GLOBALS['ShowFilteringOptions'] = ' CHECKED';
		}

		$this->ParseTemplate($page . '_Step1');
	}

	/**
	* SendPreview
	* Sends a preview email from the posted form to the email address supplied.
	* Uses the Email_API to put everything together and possibly add attachments.
	* Displays whether the email was sent ok or not.
	*
	* @see GetUser
	* @see User_API::Get
	* @see GetAttachments
	* @see GetApi
	* @see Email_API::Set
	* @see Email_API::AddBody
	* @see Email_API::AppendBody
	* @see Email_API::AddAttachment
	* @see Email_API::AddRecipient
	* @see Email_API::Send
	*
	* @return Void Doesn't return anything. Processes the form and displays a success/error message.
	*/
	function SendPreview()
	{

		$user = &GetUser();
		$preview_email = (isset($_POST['PreviewEmail'])) ? $_POST['PreviewEmail'] : false;
		$subject = (isset($_POST['subject'])) ? $_POST['subject'] : '';
		$html = (isset($_POST['myDevEditControl_html'])) ? $_POST['myDevEditControl_html'] : false;
		$text = (isset($_POST['TextContent'])) ? $_POST['TextContent'] : false;
		$id = (isset($_GET['id'])) ? (int)$_GET['id'] : 0;

		$from = (isset($_POST['FromPreviewEmail'])) ? $_POST['FromPreviewEmail'] : $preview_email;

		if (!$preview_email) {
			$GLOBALS['Error'] = GetLang('NoEmailAddressSupplied');
			$GLOBALS['Message'] = $this->ParseTemplate('ErrorMsg', true, false);
			$this->ParseTemplate('Preview_EmailWindow');
			return;
		}

		if (!$text && !$html) {
			$GLOBALS['Error'] = GetLang('NoContentToEmail');
			$GLOBALS['Message'] = $this->ParseTemplate('ErrorMsg', true, false);
			$this->ParseTemplate('Preview_EmailWindow');
			return;
		}

		if ($id > 0) {
			$attachmentsarea = strtolower(get_class($this));
			$attachments_list = $this->GetAttachments($attachmentsarea, $id, true);
		} else {
			$attachments_list = false;
		}

		$email_api = $this->GetApi('Email');
		$email_api->Set('CharSet', SENDSTUDIO_CHARSET);
		$email_api->Set('Subject', $subject);
		$email_api->Set('FromAddress', $from);
		$email_api->Set('ReplyTo', $from);
		$email_api->Set('BounceAddress', $from);

		if ($text) {
			$email_api->AddBody('text', $text);
			$email_api->AppendBody('text', $user->Get('textfooter'));
			$email_api->AppendBody('text', stripslashes(SENDSTUDIO_TEXTFOOTER));
		}
		if ($html) {
			$email_api->AddBody('html', $html);
			$email_api->AppendBody('html', $user->Get('htmlfooter'));
			$email_api->AppendBody('html', stripslashes(SENDSTUDIO_HTMLFOOTER));
		}

		if ($attachments_list) {
			$path = $attachments_list['path'];
			$files = $attachments_list['filelist'];
			foreach ($files as $p => $file) {
				$email_api->AddAttachment($path . '/' . $file);
			}
		}

		$email_api->SetSmtp(SENDSTUDIO_SMTP_SERVER, SENDSTUDIO_SMTP_USERNAME, @base64_decode(SENDSTUDIO_SMTP_PASSWORD), SENDSTUDIO_SMTP_PORT);

		$user_smtpserver = $user->Get('smtpserver');
		if ($user_smtpserver) {
			$email_api->SetSmtp($user_smtpserver, $user->Get('smtpusername'), $user->Get('smtppassword'), $user->Get('smtpport'));
		}

		$format = 'h';

		if ($text && $html) {
			$email_api->Set('Multipart', true);
		} else {
			if ($text) {
				$format = 't';
			}
			if ($html) {
				$format = 'h';
			}
		}

		$email_api->AddRecipient($preview_email, '', $format);
		$send_result = $email_api->Send();

		if (isset($send_result['success']) && $send_result['success'] > 0) {
			$GLOBALS['Message'] = $this->PrintSuccess('PreviewEmailSent', $preview_email);
		} else {
			$failure = array_shift($send_result['fail']);
			$GLOBALS['Error'] = sprintf(GetLang('PreviewEmailNotSent'), $preview_email, htmlspecialchars($failure[1], ENT_QUOTES, SENDSTUDIO_CHARSET));
			$GLOBALS['Message'] = $this->ParseTemplate('ErrorMsg', true, false);
		}

		$this->ParseTemplate('Preview_EmailWindow');
	}

	/**
	* CreateDateTimeBox
	* Used to print out a date and time box based on the time passed in. This will pre-select options, check which order to display the date in and create a string of select boxes you can use to print out the date/time.
	*
	* @param Int $time Time to create the box for. If not specified it will create it for "now".
	* @param String $name Name of the datetime box. Defaults to 'datetime'.
	* @param Boolean $hide_datebox Whether to show or hide the datebox option. This defaults to false (that is - show the datebox).
	*
	* @see AdjustTime
	*
	* @return String String of select boxes ready for printing.
	*/
	function CreateDateTimeBox($time=0, $name='datetime', $hide_datebox=false)
	{
		if ($time == 0) {
			$time = AdjustTime(0, true, null, true);
		}

		$time = AdjustTime($time, false, 'j:n:Y:A:h:i');
		list($day_chosen, $mth_chosen, $yr_chosen, $meridiem_chosen, $hr_chosen, $min_chosen) = explode(':', $time);
/*
		$day_chosen = date('j', $time);
		$mth_chosen = date('n', $time);
		$yr_chosen = date('Y', $time);

		$meridiem_chosen = date('A', $time);
		$hr_chosen = date('h', $time);
		$min_chosen = date('i', $time);
*/
		$currentTime = $hr_chosen . ":" . $min_chosen . $meridiem_chosen;

		$output = '<timepicker id="sendtime" displaySeconds="false" displayAMPM="true" leadZero="true" value="' . $currentTime . '" enforceDefaultValue="false" class="timePicker">
		</timepicker>';

		$required = $this->ParseTemplate('Required', true, false);

		$style = '';
		if ($hide_datebox) {
			$style = 'display:none';
		}
		$day_list = '</tr><tr id="show_senddate" style="' . $style . '"><td width="200" class="FieldLabel">' . $required . GetLang('SendDate') . ':</td><td><select style="margin: 0px" name="' . $name . '[day]" class="DateTimeBox">';

		for ($i = 1; $i <= 31; $i++) {
			$day_list .= '<option value="' . $i . '"';
			if ($i == $day_chosen) {
				$day_list .= ' SELECTED';
			}
			$day_list .= '>' . sprintf('%02d', $i) . '</option>';
		}
		$day_list .= '</select>';

		$mth_list = '/ <select style="margin: 0px" name="' . $name . '[month]" class="DateTimeBox">';
		for ($i = 1; $i <= 12; $i++) {
			$mth_list .= '<option value="' . $i . '"';
			if ($i == $mth_chosen) {
				$mth_list .= ' SELECTED';
			}
			$mth_list .= '>' . GetLang($this->Months[$i]) . '</option>';
		}
		$mth_list .= '</select>';

		$yr_list = '/ <select style="margin: 0px" name="' . $name . '[year]" class="DateTimeBox">';
		for ($i = ($yr_chosen - 2); $i <= ($yr_chosen + 5); $i++) {
			$yr_list .= '<option value="' . $i . '"';
			if ($i == $yr_chosen) {
				$yr_list .= ' SELECTED';
			}
			$yr_list .= '>' . sprintf('%02d', $i) . '</option>';
		}
		$yr_list .= '</select>';

		$date_order = explode(' ', GetLang('DateTimeBoxFormat'));
		foreach ($date_order as $p => $order) {
			switch (strtolower($order)) {
				case 'd':
					$output .= '&nbsp;' . $day_list . '&nbsp;';
				break;
				case 'm':
					$output .= '&nbsp;' . $mth_list . '&nbsp;';
				break;
				case 'y':
					$output .= '&nbsp;' . $yr_list . '&nbsp;';
				break;
			}
		}
		$output = substr($output, 0, -6);

		return $output;


	}

	/**
	* TimeDifference
	* Returns the time difference in an easy format / unit system (eg how many seconds, minutes, hours etc).
	*
	* @param Int $timedifference Time difference as an integer to transform.
	*
	* @return String Time difference plus units.
	*/
	function TimeDifference($timedifference)
	{
		if ($timedifference < 60) {
			if ($timedifference == 1) {
				$timechange = GetLang('TimeTaken_Seconds_One');
			} else {
				$timechange = sprintf(GetLang('TimeTaken_Seconds_Many'), $this->FormatNumber($timedifference, 0));
			}
		}

		if ($timedifference >= 60 && $timedifference < 3600) {
			$num_mins = floor($timedifference / 60);

			$secs = floor($timedifference % 60);

			if ($num_mins == 1) {
				$timechange = GetLang('TimeTaken_Minutes_One');
			} else {
				$timechange = sprintf(GetLang('TimeTaken_Minutes_Many'), $this->FormatNumber($num_mins, 0));
			}

			if ($secs > 0) {
				$timechange .= ', ' . sprintf(GetLang('TimeTaken_Seconds_Many'), $this->FormatNumber($secs, 0));
			}
		}

		if ($timedifference >= 3600) {
			$hours = floor($timedifference/3600);
			$mins = floor($timedifference % 3600) / 60;

			if ($hours == 1) {
				if ($mins == 0) {
					$timechange = GetLang('TimeTaken_Hours_One');
				} else {
					$timechange = sprintf(GetLang('TimeTaken_Hours_One_Minutes'), $this->FormatNumber($mins, 0));
				}
			}

			if ($hours > 1) {
				if ($mins == 0) {
					$timechange = GetLang('TimeTaken_Hours_Many');
				} else {
					$timechange = sprintf(GetLang('TimeTaken_Hours_Many_Minutes'), $this->FormatNumber($hours, 0), $this->FormatNumber($mins, 0));
				}
			}
		}

		// can expand this futher to years/months etc - the schedule_manage file has it all done in javascript.

		return $timechange;
	}

	/**
	* GetPageContents
	* Returns the url's contents.
	*
	* @param String $url The url to import from.
	*
	* @return False|String Returns false if there is no url or it can't be opened (invalid url). Otherwise returns the content from the url. Tries to use curl functions if they are available, otherwise it uses 'fopen' if it's available (ie safe-mode is off and it's not disabled).
	*/
	function GetPageContents($url='')
	{
		if (!$url) {
			return array(false, 'No URL');
		}

		if (SENDSTUDIO_CURL) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_FAILONERROR, true);
			if (!SENDSTUDIO_SAFE_MODE && ini_get('open_basedir') == '') {
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			}
			curl_setopt($ch, CURLOPT_TIMEOUT, 10);

			$pageData = curl_exec($ch);

			if (!$pageData) {
				$error = curl_error($ch);
			}
			curl_close($ch);

			if (!$pageData) {
				return array(false, $error);
			}
			return array($pageData, true);
		}

		if (!SENDSTUDIO_FOPEN) {
			return array(false, GetLang('NoCurlOrFopen'));
		}

		if (!@$fp = fopen($url, "rb")) {
			return array(false, GetLang('URLCantBeRead'));
		}

		// Grab the files content
		$pageData = "";

		while (!feof($fp)) {
			$pageData .= fgets($fp, 4096);
		}

		fclose($fp);

		return array($pageData, true);
	}

	/**
	* DenyAccess
	* Prints out an access denied message, the footer, and quits.
	*
	* @return Void Doesn't return anything.
	*/
	function DenyAccess()
	{
		if (!isset($GLOBALS['ErrorMessage'])) {
			$GLOBALS['ErrorMessage'] = GetLang('NoAccess');
		}

		$this->ParseTemplate('AccessDenied');
		$this->PrintFooter();
		exit();
	}

	/**
	* PrintSubscribeDate
	* Prints out subscribe date searching so you can easily display the day/month/year dropdown boxes used in searching.
	*
	* @see Subscribers_Manage::Process
	*
	* @return Void Doesn't return anything, puts information into the GLOBALS['Display_subdate_date1_Field_X'] placeholders.
	*/
	function PrintSubscribeDate()
	{

		$time = AdjustTime(0, true, null, true);

		$dd_start = $dd_end = AdjustTime($time, false, 'd');
		$mm_start = $mm_end = AdjustTime($time, false, 'm');
		$yy_start = $yy_end = AdjustTime($time, false, 'Y');

		$field_order = array('day', 'month', 'year');

		$daylist_start = $daylist_end = '<select name="datesearch[dd_whichone]" class="datefield">';
		for ($i=1; $i<=31; $i++) {
			$dom = $i;
			$i = sprintf("%02d", $i);
			$sel = '';

			if ($i==$dd_start) {
				$sel='SELECTED';
			}

			$daylist_start.='<option '.$sel.' value="'.sprintf("%02d",$i).'">'.$dom . '</option>';

			$sel = '';
			if ($i==$dd_end) {
				$sel='SELECTED';
			}

			$daylist_end.='<option '.$sel.' value="'.sprintf("%02d",$i).'">'.$dom . '</option>';

		}
		$daylist_start.='</select>';
		$daylist_end.='</select>';

		$monthlist_start = $monthlist_end ='<select name="datesearch[mm_whichone]" class="datefield">';
		for ($i=1;$i<=12;$i++) {
			$mth = $i;
			$sel = '';
			$i = sprintf("%02d",$i);

			if ($i==$mm_start) {
				$sel='SELECTED';
			}

			$monthlist_start.='<option '.$sel.' value="'.sprintf("%02d",$i).'">'.GetLang($this->Months[$mth]) . '</option>';

			if ($i==$mm_end) {
				$sel='SELECTED';
			}

			$monthlist_end .='<option '.$sel.' value="'.sprintf("%02d",$i).'">'.GetLang($this->Months[$mth]) . '</option>';

		}
		$monthlist_start.='</select>';
		$monthlist_end.='</select>';

		$yearlist_start ='<input type="text" maxlength="4" size="4" value="'.$yy_start.'" name="datesearch[yy_whichone]" class="datefield">';
		$yearlist_end ='<input type="text" maxlength="4" size="4" value="'.$yy_end.'" name="datesearch[yy_whichone]" class="datefield">';

		foreach ($field_order as $p => $order) {
			switch ($order) {
				case 'day':
					$GLOBALS['Display_subdate_date1_Field'.($p+1)] = str_replace('_whichone', '_start', $daylist_start);
				break;
				case 'month':
					$GLOBALS['Display_subdate_date1_Field'.($p+1)] = str_replace('_whichone', '_start', $monthlist_start);
				break;
				case 'year':
					$GLOBALS['Display_subdate_date1_Field'.($p+1)] = str_replace('_whichone', '_start', $yearlist_start);
				break;
			}
		}

		foreach ($field_order as $p => $order) {
			switch ($order) {
				case 'day':
					$GLOBALS['Display_subdate_date2_Field'.($p+1)] = str_replace('_whichone', '_end', $daylist_end);
				break;
				case 'month':
					$GLOBALS['Display_subdate_date2_Field'.($p+1)] = str_replace('_whichone', '_end', $monthlist_end);
				break;
				case 'year':
					$GLOBALS['Display_subdate_date2_Field'.($p+1)] = str_replace('_whichone', '_end', $yearlist_end);
				break;
			}
		}
	}

	/**
	* PrintWarning
	* Returns the parsed template 'WarningMsg' with the appropriate variable placed in the warning.
	*
	* @param String $langvar Language variable to use for the warning message.
	*
	* @see ParseTemplate
	* @see DisplayCronWarning
	*
	* @return String Returns the parsed warningmsg template.
	*/
	function PrintWarning($langvar='')
	{
		$GLOBALS['Warning'] = GetLang($langvar);
		return $this->ParseTemplate('WarningMsg', true, false);
	}

	/**
	* PrintSuccess
	* Returns the parsed template 'SuccessMsg' with the appropriate variables placed in the message.
	* You can pass in any number of arguments. The first one is always the language variable to use.
	* The others are placeholders for within that language variable.
	* <b>Example</b>
	* <code>
	* PrintSuccess('MyMessage', $number);
	* </code>
	* will return as if you put
	* <code>
	* sprintf(GetLang('MyMessage'), $number);
	* </code>
	*
	* If you need to pass in another language variable, it must be changed before passing into the function.
	* <b>Example</b>
	* <code>
	* PrintSuccess('MyMessage', GetLang('SecondMessage'));
	* </code>
	*
	* @see ParseTemplate
	*
	* @return String Returns the parsed successmsg template.
	*/
	function PrintSuccess()
	{
		$arg_list = func_get_args();
		$langvar = array_shift($arg_list);
		$GLOBALS['Success'] = vsprintf(GetLang($langvar), $arg_list);
		return $this->ParseTemplate('SuccessMsg', true, false);
	}

	/**
	* DisplayCronWarning
	* This may show a warning message if cron is not enabled
	* It may also show a warning message if cron is enabled but has not run successfully yet.
	* This is shown on the autoresponder pages to let people know what's going on
	*
	* @param Boolean $autoresponderpage Whether you are on the autoresponder page or not. If you are on the autoresponder page, the warning message is a little different to non-autoresponder pages.
	* @see GetApi
	* @see Settings_API::CronEnabled
	* @see Settings_API::CheckCron
	*
	* @return Void Doesn't return anything. If there is a message to show, it calls PrintWarning. Otherwise nothing happens.
	*/
	function DisplayCronWarning($autoresponderpage=true)
	{
		$settings_api = $this->GetApi('Settings');
		$cron_enabled = $settings_api->CronEnabled();
		if (!$cron_enabled && $autoresponderpage) {
			$GLOBALS['CronWarning'] = $this->PrintWarning('AutoresponderCronNotEnabled');
		} else {
			$cron_check = $settings_api->CheckCron();
			if (!$cron_check) {
				$GLOBALS['CronWarning'] = $this->PrintWarning('CronNotSetup');
			}
		}
	}

	/**
	* TruncateName
	* Truncates a name to the _MaxNameLength name (minus 4) - so we can append '...' to the end.
	*
	* @param String $string String to truncate to the max length.
	* @param Int $length Max length to show. Defaults to _MaxNameLength.
	*
	* @see _MaxNameLength
	*
	* @return string The truncated string or the original string if it's less than MaxNameLength chars long
	*/
	function TruncateName($string='', $length=0) {
		if ($length == 0) $length = $this->_MaxNameLength;

		if (SENDSTUDIO_CHARSET == 'UTF-8') {
			if (utf8_strlen($string) > $length) {
				return utf8_substr($string, 0, ($length - 4)) . ' ...';
			}
		} else {
			if (strlen($string) > $length) {
				return substr($string, 0, ($length - 4)) . ' ...';
			}
		}
		return $string;
	}

	/**
	* CheckForUnsubscribeLink
	* Checks the content passed in to see if there is an unsubscribe link or not. This allows us to display a warning message about the problem if it's not present.
	* If FORCE_UNSUBLINK is switched on, this isn't needed.
	*
	* @param String $content Content to search for the unsubscribe link in.
	*
	* @return Boolean Returns true if FORCE_UNSUBLINK is on, or if there is an unsubscribe link present. Otherwise returns false.
	*/
	function CheckForUnsubscribeLink($content='') {
		if (!$content) {
			return true;
		}

		if (SENDSTUDIO_FORCE_UNSUBLINK) {
			return true;
		}

		if (preg_match('/%basic:unsublink%/i', $content)) {
			return true;
		}

		if (preg_match('/%%unsubscribelink%%/i', $content)) {
			return true;
		}

		return false;
	}

	/**
	* DisabledItem
	* This returns the "disableditem" template with the placeholders replaced. Use a simple function so it's easier to use everywhere.
	*
	* @param String $itemname The name of the item that is being disabled. Eg, edit, copy, delete.
	* @param String $itemtitle The title to put on the item when you hover over it. By default it's "NoAccess" but if you pass in a language variable name it will use that instead.
	*
	* @return String Returns the template parsed (language variables replaced).
	*/
	function DisabledItem($itemname='', $itemtitle='NoAccess')
	{
		$GLOBALS['ItemTitle'] = GetLang($itemtitle);
		$GLOBALS['ItemName'] = GetLang($itemname);
		return '&nbsp;&nbsp;' . $this->ParseTemplate('DisabledItem', true, false);
	}
}

?>
