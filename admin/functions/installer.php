<?php

/**
* Include the base sendstudio functions.
*/
require(dirname(__FILE__) . '/sendstudio_functions.php');

/**
* Class for the welcome page. Includes quickstats and so on.
*
* @package SendStudio
* @subpackage SendStudio_Functions
*/
class Installer extends SendStudio_Functions
{

	/**
	* Constructor
	* Doesn't do anything.
	*
	* @return Void Doesn't return anything.
	*/
	function Installer()
	{
	}

	/**
	* Process
	* Works out which step we are up to in the install process and passes it off for the other methods to handle.
	*
	* @return Void Works out which step you are up to and that's it.
	*/
	function Process()
	{
		$this->PrintHeader();

		$step = 0;
		if (isset($_GET['Step'])) {
			$step = (int)$_GET['Step'];
		}

		if ($step <= 0) {
			$step = 0;
		}

		$handle_step = 'ShowStep_' . $step;

		$this->$handle_step();

		$this->PrintFooter();
	}

	/**
	* ShowStep_0
	* This shows the first "thanks for purchasing" page.
	* Doesn't do anything else.
	*
	* @return Void Doesn't return anything.
	*/
	function ShowStep_0()
	{
		?>
		<form method="post" action="index.php?Step=1">
		<table cellSpacing="0" cellPadding="0" width="95%" align="center">
			<TR>
				<TD class="Heading1">Welcome to the Sendstudio Installation Wizard</TD>
			</TR>
			<TR>
				<TD class="Gap">&nbsp;</TD>
			</TR>
			<TR>
				<TD>
					<table class="Panel" id="Table14" width="100%">
						<TR>
							<TD class="Content" colSpan="2">
								<TABLE id="Table2" style="BORDER-RIGHT: #adaaad 1px solid; BORDER-TOP: #adaaad 1px solid; BORDER-LEFT: #adaaad 1px solid; BORDER-BOTTOM: #adaaad 1px solid; BACKGROUND-COLOR: #f7f7f7"
									cellSpacing="0" cellPadding="10" width="100%" border="0">
									<TR>
										<TD>
											<TABLE width="100%" class="Message" cellSpacing="0" cellPadding="0" border="0">
												<TR>
													<TD width="20"><IMG height="18" hspace="5" src="images/success.gif" width="18" align="middle" vspace="5"></TD>
													<TD class="text">Thank you for purchasing Sendstudio!<BR>
													</TD>
												</TR>
											</TABLE>
											<DIV class="text">
												<br/>
												Welcome to the Sendstudio installation wizard. Over the next 4 steps you will be asked for various details that will be used by Sendstudio to build your new email marketing system. If you are unsure about what any particular option means simply move your mouse over the help icon next to it to learn more about it. To get started click the "Proceed" button below.
											</DIV>
										</TD>
									</TR>
									<TR>

										<TD>
											<input type="submit" name="WelcomeProceedButton" value="Proceed" class="FormButton" />
										</TD>
									</TR>
								</TABLE>
							</TD>
						</TR>
					</TABLE>
				</TD>
			</TR>
		</TABLE>
		</form>
		<?php
	}

	/**
	* CheckPermissions
	* Checks permissions on the appropriate folders.
	* If permissions aren't correct, a message is displayed and you can't continue until they are fixed.
	* Also checks to make sure either 'mysql' or 'postgresql' functions are available
	* That is, some sort of database functionality is there.
	*
	* @return Void If everything is ok, this will return out of the function. If something is wrong, it prints an error message and the script dies.
	*/
	function CheckPermissions()
	{
		$isOK = true;
		$permError = '';
		$serverError = '';
		$permArray = array(
			'/includes/config.php',
			'/temp'
		);

		$linux_message = 'Please CHMOD it to 757.';
		$windows_message = 'Please set anonymous write permissions in IIS. If you don\'t have access to do this, you will need to contact your hosting provider.';

		$error_message = $linux_message;
		if (strtolower(substr(PHP_OS, 0, 3)) == 'win') {
			$error_message = $windows_message;
		}

		foreach ($permArray as $a) {
			if (!$this->CheckWritable(SENDSTUDIO_BASE_DIRECTORY . $a)) {
				$permError .= sprintf("<li>The file or folder <b>%s</b> isn't writable. " . $error_message . "</li>", SENDSTUDIO_BASE_DIRECTORY . str_replace('/', DIRECTORY_SEPARATOR, $a));
				$isOK = false;
			}
		}

		if (SENDSTUDIO_SAFE_MODE) {
			if (!$this->CheckWritable(TEMP_DIRECTORY . '/send')) {
				$permError .= sprintf("<li>The file or folder <b>%s</b> isn't writable. " . $error_message . "</li>", TEMP_DIRECTORY . DIRECTORY_SEPARATOR . 'send');
				$isOK = false;
			}
			if (!$this->CheckWritable(TEMP_DIRECTORY . '/autoresponder')) {
				$permError .= sprintf("<li>The file or folder <b>%s</b> isn't writable. " . $error_message . "</li>", TEMP_DIRECTORY . DIRECTORY_SEPARATOR . 'autoresponder');
				$isOK = false;
			}
		}

		if (!function_exists('mysql_connect') && !function_exists('pg_connect')) {
			$serverError .= '<li>Your server does not support mysql or postgresql databases. PHP on your web server needs to be compiled with MySQL or PostgreSQL support.<br><br>
			For more information:<br>
			<a href="http://www.php.net/mysql" target="_blank">http://www.php.net/mysql</a><br>
			<a href="http://www.php.net/pgsql" target="_blank">http://www.php.net/pgsql</a><br><br>
			Please contact your web hosting provider or administrator for more details.
			</li>';
			$isOK = false;
		}

		if ($isOK) {
			return;
		}

		?>
		<form method="post" action="index.php?Step=1">
		<TABLE cellSpacing="0" cellPadding="0" width="95%" align="center">
			<TR>
				<TD class="Heading1">Oops... Something Went Wrong</TD>
			</TR>
			<TR>
				<TD class="text"><br/></TD>
			</TR>
			<TR>
				<TD>
					<table border=0 cellspacing="0" cellpadding="0" width=100% class="text">
						<tr>
							<td colspan='2'>
								<table border='0' cellspacing='0' cellpadding='0'>
									<tr>
										<td class='Message' width='20' valign='top'>
											<img src='images/error.gif' width='18' height='18' hspace='10'>
										</td>
										<td class='Message' width='100%'>
											<?php
												if ($permError) {
													echo 'The following files or folders cannot be written to:<br/>';
													echo '<ul>';
													echo $permError;
													echo '</ul>';
												}
												if ($serverError) {
													echo '<br/>';
												}
											?>
											<?php
												if ($serverError) {
													echo 'The following problems have been found with your server:<br/>';
													echo '<ul>';
													echo $serverError;
													echo '</ul>';
												}
											?>
											<br/>
											<input type="submit" value="Try Again" class="formbutton">
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</TD>
			</TR>
		</TABLE>
		</form>
		<?php
		$this->PrintFooter();
		exit;
	}

	/**
	* ShowStep_1
	* Step 1 checks the license key is valid and permissions on the appropriate files/folders.
	*
	* @param String $license_error If there is a license key error it is passed in here so we can display an error message. If this is empty, we are at step 1 for the first time so an error message isn't shown.
	*
	* @see CheckPermissions
	* @see ShowStep_2
	*
	* @return Void Doesn't return anything. Checks permissions, shows the license key box and (if applicable) shows an error message if the license key is invalid.
	*/
	function ShowStep_1($license_error=false)
	{
		$permissions = $this->CheckPermissions();
		$licensekey = '';
		if (isset($_POST['license'])) {
			$licensekey = htmlspecialchars($_POST['license'], ENT_QUOTES, SENDSTUDIO_CHARSET);
		}

		?>
		<form method="post" action="index.php?Step=2" onsubmit="return CheckForm();">
		<table cellspacing="0" cellpadding="0" width="95%" align="center">
			<TR>
				<td class="heading1">Step 1 of 4: License</td>
			</TR>
			<TR>
				<TD class="text"><br/>Please copy and paste the license key you received when you purchased SendStudio.<br/><br/></TD>
			</TR>
			<?php
			if ($license_error) {
				?>
					<tr>
						<td class="text">
							<br/>
							<table border='0' cellspacing='0' cellpadding='0'>
								<tr>
									<td class='Message' width='20' valign='top'>
										<img src='images/error.gif' width='18' height='18' hspace='10' vspace='5'>
									</td>
									<td class='Message' width='100%'>
										The specified license key is invalid, please contact <a href="mailto:help" style='color: blue'>help</a> for a new key.
									</td>
								</tr>
							</table>
							<br/>
						</td>
					</tr>
				<?php
			}
			?>
			<TR>
				<TD>
					<table class="panel" border="0" cellpadding="2" cellspacing="0" width="100%">
						<tr class="Heading3">
							<td colspan="2">
								&nbsp;&nbsp;License Key
							</td>
						</tr>
						<tr>
							<td class="FieldLabel">
								License Key:
							</td>
							<td>Removed.
							</td>
						</tr>
						<tr>
							<td>
								&nbsp;
							</td>
							<td>
								<input type="submit" value="Next &raquo;" class="formbutton">
							</td>
						</tr>
					</table>
				</TD>
			</TR>
		</TABLE>
		</form>
		
		<?php
	}

	/**
	* ShowStep_2
	* Checks the license key from step 1. If it is invalid, it goes back to step 1 and that's it.
	* If the license key is valid, database information is displayed.
	* If there are database errors (step 3 checks this) then this will also display the database errors so they can be addressed.
	*
	* @param Boolean $dberror Whether there is a database error or not.
	* @param Array $query_errors The database errors step 3 encountered.
	*
	* @see ShowStep_1
	* @see ShowStep_3
	*/
	function ShowStep_2($dberror=false, $query_errors=array())
	{
		$session = &GetSession();

		$key = false;
		$session = &GetSession();
		$session->Set('LicenseKey', 'wst');

		$db_types = array();
		if (function_exists('mysql_connect')) {
			$db_types['mysql'] = 'MySQL';
		}

		if (function_exists('pg_connect')) {
			$db_types['pgsql'] = 'PostgreSQL';
		}

		$db_selected = 'mysql';

		if (isset($_POST['dbtype'])) {
			$db_selected = $_POST['dbtype'];
		}

		if (!in_array($db_selected, array_keys($db_types))) {
			$db_selected = '';
		}

		$dbhostname = 'localhost';
		if (isset($_POST['dbhostname'])) {
			$dbhostname = htmlspecialchars($_POST['dbhostname'], ENT_QUOTES, SENDSTUDIO_CHARSET);
		}

		$dbuser = $dbname = '';
		if (isset($_POST['dbu'])) {
			$dbuser = htmlspecialchars($_POST['dbu'], ENT_QUOTES, SENDSTUDIO_CHARSET);
		}

		if (isset($_POST['dbname'])) {
			$dbname = htmlspecialchars($_POST['dbname'], ENT_QUOTES, SENDSTUDIO_CHARSET);
		}

		$tableprefix = 'ss_';
		if (isset($_POST['tableprefix'])) {
			$tableprefix = htmlspecialchars($_POST['tableprefix'], ENT_QUOTES, SENDSTUDIO_CHARSET);
		}

		?>
		<form method="post" action="index.php?Step=3" onsubmit="return CheckForm();">
		<table cellspacing="0" cellpadding="0" width="95%" align="center">
			<tr>
				<td class="Heading1">Step 2 of 4: Database Details</TD>
			</tr>
			<tr>
				<td class="text">
					<br/>Please enter the details for your database in the form shown below.<br/>
					The database tables and fields will be created for you automatically.<br/><br/>
				</TD>
			</TR>

			<?php
			if ($dberror) {
				?>
					<tr>
						<td class="text">
							<br/>
							<table class="panel">
								<tr>
									<td class='Message' width='20' valign='top'>
										<img src='images/error.gif' width='18' height='18' hspace='10' vspace='5'>
									</td>
									<td class='Message' width='100%'>
										<?php
											if (is_array($query_errors)) {
												if (empty($query_errors)) {
													echo 'There was a problem connecting to your database. ' . $dberror;
												} else {
													echo 'There was a problem running the following queries against your database: ';
													echo implode('<br /><br />', $query_errors);
													echo '<br/>';
												}
											} else {
												echo $query_errors;
											}
										?>
									</td>
								</tr>
							</table>
							<br/>
						</td>
					</tr>
				<?php
			}
			?>

			<TR>
				<TD>
					<table class="panel" border="0" cellpadding="2" cellspacing="0" width="100%">
						<tr class="Heading3">
							<td colspan="2">
								&nbsp;&nbsp;Database Settings
							</td>
						</tr>
						<tr>
							<td class="FieldLabel">
								<span class="required">*</span> Database Type:
							</td>
							<td>
								<select name="dbtype" class="Field250">
									<?php
										foreach($db_types as $type => $desc) {
											$selected = '';
											if ($type == $db_selected) {
												$selected = ' SELECTED';
											}
											echo '<option value="' . $type . '"' . $selected . '>' . $desc . '</option>';
										}
									?>
								</select>&nbsp;<img onMouseOut="HideHelp('d1')"; onMouseOver="ShowHelp('d1', 'Database Type', 'Choose the type of database you wish to use')"; src="images/help.gif" width="24" height="16" border="0"><span style="display:inline" id="d1"></span>
							</td>
						</tr>
						<tr>
							<td class="FieldLabel">
								<span class="required">*</span> Database Server:
							</td>
							<td>
								<input type="text" name="dbhostname" id="dbhostname" value="<?php echo $dbhostname; ?>" class="Field250">&nbsp;<img onMouseOut="HideHelp('d6')"; onMouseOver="ShowHelp('d6', 'Database Server', 'The server where your database exists, such as \'localhost\'.')"; src="images/help.gif" width="24" height="16" border="0"><span style="display:inline" id="d6"></span>
							</td>
						</tr>
						<tr>
							<td class="FieldLabel">
								<span class="required">*</span> Database Username:
							</td>
							<td>
								<input type="text" name="dbu" id="dbu" value="<?php echo $dbuser; ?>" class="Field250">&nbsp;<img onMouseOut="HideHelp('d2')"; onMouseOver="ShowHelp('d2', 'Database Username', 'The username required to connect to the database, such as \'root\' or \'user\'.')"; src="images/help.gif" width="24" height="16" border="0"><span style="display:inline" id="d2"></span>
							</td>
						</tr>
						<tr>
							<td class="FieldLabel">
								&nbsp;&nbsp;&nbsp;Database Password:
							</td>
							<td>
								<input type="password" name="dbp" id="dbp" value="" class="Field250">&nbsp;<img onMouseOut="HideHelp('d3')"; onMouseOver="ShowHelp('d3', 'Database Password', 'The password required to connect to the database, such as \'pass\'.')"; src="images/help.gif" width="24" height="16" border="0"><span style="display:inline" id="d3"></span>
							</td>
						</tr>
						<tr>
							<td class="FieldLabel">
								<span class="required">*</span> Database Name:
							</td>
							<td>
								<input type="text" name="dbname" id="dbname" value="<?php echo $dbname; ?>" class="Field250">&nbsp;<img onMouseOut="HideHelp('d4')"; onMouseOver="ShowHelp('d4', 'Database Name', 'The name of your database, such as \'sendstudio\'.')"; src="images/help.gif" width="24" height="16" border="0"><span style="display:inline" id="d4"></span>
							</td>
						</tr>
						<tr>
							<td class="FieldLabel">
								&nbsp;&nbsp;&nbsp;Table Prefix:
							</td>
							<td>
								<input type="text" name="tableprefix" id="tableprefix" value="<?php echo $tableprefix; ?>" class="Field250">&nbsp;<img onMouseOut="HideHelp('d5')"; onMouseOver="ShowHelp('d5', 'Database Name', 'The prefix of all database tables, such as \'ss_\'')"; src="images/help.gif" width="24" height="16" border="0"><span style="display:inline" id="d5"></span>
							</td>
						</tr>
						<tr>
							<td>
								&nbsp;
							</td>
							<td>
								<input type="submit" value="Next &raquo;" class="formbutton">
							</td>
						</tr>
					</table>
				</TD>
			</TR>
		</TABLE>
		</form>
		<script>
			function CheckForm() {

				dbhostname = document.getElementById('dbhostname');
				if (dbhostname.value == '') {
					alert('Please enter your database server name');
					dbhostname.focus();
					return false;
				}

				dbuser = document.getElementById('dbu');
				if (dbuser.value == '') {
					alert('Please enter your database username');
					dbu.focus();
					return false;
				}

				dbname = document.getElementById('dbname');
				if (dbname.value == '') {
					alert('Please enter your database name');
					dbname.focus();
					return false;
				}
				return true;
			}
		</script>
		<?php
	}

	/**
	* ShowStep_3
	* Checks the database details work and runs the schema to set up the database tables.
	* If something doesn't work, it takes you back to step 2 and that displays the database errors.
	*
	* @see ShowStep_2
	* @see Db::Connect
	* @see Db::Query
	* @see Db::GetError
	*
	* @return Void Doesn't return anything.
	*/
	function ShowStep_3()
	{
		$dbtype = $_POST['dbtype'];
		$hostname = $_POST['dbhostname'];
		$username = $_POST['dbu'];
		$password = $_POST['dbp'];
		$dbname = $_POST['dbname'];

		$tableprefix = $_POST['tableprefix'];

		require(SENDSTUDIO_LIB_DIRECTORY . '/database/' . $dbtype . '.php');
		$db_type = $dbtype . 'Db';
		$db = &new $db_type();

		$connection = $db->Connect($hostname, $username, $password, $dbname);

		if (!$connection) {
			list($error, $level) = $db->GetError();
			$this->ShowStep_2($error);
			return;
		}

		if ($dbtype == 'mysql') {
			$query = "SELECT VERSION() AS version";
			$res = $db->Query($query);
			$version = $db->FetchOne($res, 'version');
			$compare = version_compare($version, '4.0');
			if ($compare < 0) {
				$this->ShowStep_2(true, 'SendStudio NX requires MySQL v4.0 or above to work properly. Your server is running ' . $version . '. To complete the installation, your host will need to upgrade MySQL. Please contact your host to arrange this.');
				return;
			}
		}

		$query = "SELECT COUNT(*) AS subcount FROM " . $tableprefix . "users";
		$result = $db->Query($query, true);
		// the table already exists?! That's bad.
		if ($result) {
			$count = $db->FetchOne($result, 'subcount');
			if ($count > 0) {
				$this->ShowStep_2(true, 'SendStudio NX seems to be already installed in this database. To continue with this installation, you will need to delete the data from this database or select a different database. You may need to contact your administrator or web hosting provider to do this.');
				return;
			}
		}

		$query = "SELECT COUNT(*) AS subcount FROM " . $tableprefix . "admins";
		$result = $db->Query($query, true);
		// the table already exists?! That's bad.
		if ($result) {
			$count = $db->FetchOne($result, 'subcount');
			if ($count > 0) {
				$this->ShowStep_2(true, 'An older version of SendStudio is already installed in this database. If you are attempting to upgrade your existing version of SendStudio, you will need to ensure that your existing includes/config.inc.php file exists on the server. SendStudio will detect if this config file exists and will automatically start up the upgrade wizard.<br><br>If you would like to install a fresh copy of SendStudio NX, then either delete the data from this database (contact your administrator or web host if you need help) or select a new database.');
				return;
			}
		}

		$db_errors = array();

		$schema_file = 'schema.' . $dbtype . '.php';
		require(SENDSTUDIO_LANGUAGE_DIRECTORY . '/' . $schema_file);
		foreach ($queries as $p => $query) {
			$query = str_replace('%%TABLEPREFIX%%', $tableprefix, $query);
			$result = $db->Query($query);
			if (!$result) {
				$db_errors[] = $query;
			}
		}

		if (!empty($db_errors)) {
			$this->ShowStep_2(true, $db_errors);
			return;
		}

		$db_details = array();
		$db_details['hostname'] = $hostname;
		$db_details['username'] = $username;
		$db_details['password'] = $password;
		$db_details['dbname'] = $dbname;
		$db_details['dbtype'] = $dbtype;
		$db_details['tableprefix'] = $tableprefix;

		$session = &GetSession();
		$session->Set('DatabaseDetails', $db_details);

		$base_url = preg_replace('%/admin/index.php%', '', $_SERVER['PHP_SELF']);

		$http = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 'https' : 'http';
		$applicationurl = $http . '://' . $_SERVER['HTTP_HOST'] . $base_url;

		$encoding = '';
		$charset = '';

		if (isset($_POST['encoding'])) {
			$encoding = $_POST['encoding'];
		}

		if (isset($_POST['charset'])) {
			$charset = $_POST['charset'];
		}

		if (isset($_POST['applicationurl'])) {
			$applicationurl = htmlspecialchars($_POST['applicationurl'], ENT_QUOTES, SENDSTUDIO_CHARSET);
		}

		$contactemail = '';
		if (isset($_POST['contactemail'])) {
			$contactemail = htmlspecialchars($_POST['contactemail'], ENT_QUOTES, SENDSTUDIO_CHARSET);
		}

		$this->LoadLanguageFile('Timezones');
		$this->LoadLanguageFile('CharacterSets');

		?>
		<form method="post" action="index.php?Step=4" onsubmit="return CheckForm();">
		<TABLE cellSpacing="0" cellPadding="0" width="95%" align="center">
			<TR>
				<TD class="Heading1">Step 3 of 4: SendStudio Settings</TD>
			</TR>
			<TR>
				<TD class="text">
					<br/>Please enter the details in the form shown below.<br/><br/>
				</TD>
			</TR>
			<TR>
				<TD>
					<table class="panel" border="0" cellpadding="2" cellspacing="0" width="100%">
						<tr class="Heading3">
							<td colspan="2">
								&nbsp;&nbsp;General Settings
							</td>
						</tr>
						<tr>
							<td class="FieldLabel">
								<span class="required">*</span> Application URL:
							</td>
							<td>
								<input type="text" name="applicationurl" id="applicationurl" value="<?php echo $applicationurl; ?>" class="Field250">&nbsp;<img onMouseOut="HideHelp('d0')"; onMouseOver="ShowHelp('d0', 'Application URL', 'The base application url. This does not include the \'admin\' folder.')"; src="images/help.gif" width="24" height="16" border="0"><span style="display:inline" id="d0"></span>
							</td>
						</tr>
						<tr>
							<td class="FieldLabel">
								<span class="required">*</span> Contact Email Address:
							</td>
							<td>
								<input type="text" name="contactemail" id="contactemail" value="<?php echo $contactemail; ?>" class="Field250">&nbsp;<img onMouseOut="HideHelp('d1')"; onMouseOver="ShowHelp('d1', 'Contact Email Address', 'The return email address for \'Forgot Password\' requests.')"; src="images/help.gif" width="24" height="16" border="0"><span style="display:inline" id="d1"></span>
							</td>
						</tr>
						<?php
							$timezone = date('O');
							$timez = 'GMT';
							foreach($GLOBALS['SendStudioTimeZones'] as $k => $tz) {
								// if we're using date('O') it doesn't include "GMT" or the ":"
								// see if we can match it up.
								$tz_trim = str_replace(array('GMT', ':'), '', $tz);
								if ($tz_trim == $timezone) {
									$timez = $tz;
									break;
								}
							}
							echo '<input type="hidden" name="timezone" value="' . $timez . '"/>';
						?>
						<tr>
							<td class="FieldLabel">
								<span class="required">*</span> Default Send Character Set:
							</td>
							<td>
								<select name="charset" class="Field250">
									<?php
										foreach ($GLOBALS['SendStudioCharacterSets'] as $pos => $type) {
											$selected = '';
											if ($type == $charset) {
												$selected = ' SELECTED';
											}
											echo '<option value="' . $type . '"' . $selected . '>' .GetLang($type) . '</option>';
										}
									?>
								</select>&nbsp;<img onMouseOut="HideHelp('d4')"; onMouseOver="ShowHelp('d4', 'Character Set', 'This is the character set used when sending out an email campaign or autoresponder.<br/><br/>If you are sending anything other than strictly English newsletters or autoresponders, choose UTF-8.<br/><br/>This cannot be changed once SendStudio has been installed because it causes issues with already encoded database information (for example accented characters).')"; src="images/help.gif" width="24" height="16" border="0"><span style="display:inline" id="d4"></span>
							</td>
						</tr>
						<tr>
							<td>
								&nbsp;
							</td>
							<td>
								<input type="submit" value="Next &raquo;" class="formbutton">
							</td>
						</tr>
					</table>
				</TD>
			</TR>
		</TABLE>
		</form>
		<script>
			function CheckForm() {

				appurl = document.getElementById('applicationurl');
				if (appurl.value == '') {
					alert('Please enter an application url.');
					appurl.focus();
					return false;
				}

				echeck = document.getElementById('contactemail');
				if (echeck.value == '') {
					alert('Please enter a contact email address.');
					echeck.focus();
					return false;
				}
				return true;
			}
		</script>
		<?php
	}

	/**
	* ShowStep_4
	* Saves the information from the previous steps into the sendstudio config file and prints out a final "thanks" message with the default login details.
	*
	* @see Settings_API
	* @see Settings_API::Save
	*
	* @return Void Doesn't return anything.
	*/
	function ShowStep_4()
	{
		$session = &GetSession();

		require(dirname(__FILE__) . '/api/settings.php');

		$settings_api = &new Settings_API(false);

		$db_details = $session->Get('DatabaseDetails');

		$settings_details = array();

		$settings_details['DATABASE_TYPE'] = $db_details['dbtype'];
		$settings_details['DATABASE_USER'] = $db_details['username'];
		$settings_details['DATABASE_PASS'] = $db_details['password'];
		$settings_details['DATABASE_HOST'] = $db_details['hostname'];
		$settings_details['DATABASE_NAME'] = $db_details['dbname'];
		$settings_details['TABLEPREFIX'] = $db_details['tableprefix'];
		$settings_details['LICENSEKEY'] = $session->Get('LicenseKey');
		$settings_details['APPLICATION_URL'] = $_POST['applicationurl'];
		$settings_details['SERVERTIMEZONE'] = $_POST['timezone'];
		$settings_details['DEFAULTCHARSET'] = $_POST['charset'];
		$settings_details['EMAIL_ADDRESS'] = $_POST['contactemail'];

		// now for the default settings.
		$settings_details['SMTP_PORT'] = '25';
		$settings_details['SMTP_SERVER'] = '';
		$settings_details['SMTP_USERNAME'] = '';
		$settings_details['SMTP_PASSWORD'] = '';
		$settings_details['FORCE_UNSUBLINK'] = '';
		$settings_details['HTMLFOOTER'] = '';
		$settings_details['TEXTFOOTER'] = '';
		$settings_details['MAXHOURLYRATE'] = '0';
		$settings_details['MAXOVERSIZE'] = '0';
		$settings_details['CRON_ENABLED'] = '0';
		$settings_details['IPTRACKING'] = '1';

		$settings_details['MAX_IMAGEWIDTH'] = 700;
		$settings_details['MAX_IMAGEHEIGHT'] = 400;

		$settings_details['BOUNCE_ADDRESS'] = '';
		$settings_details['BOUNCE_SERVER'] = '';
		$settings_details['BOUNCE_USERNAME'] = '';
		$settings_details['BOUNCE_PASSWORD'] = '';
		$settings_details['BOUNCE_IMAP'] = '0';
		$settings_details['BOUNCE_EXTRASETTINGS'] = '';

		$settings_api->Set('Settings', $settings_details);

		$settings_api->Save();

		require(SENDSTUDIO_LIB_DIRECTORY . '/database/' . $db_details['dbtype'] . '.php');
		$db_type = $db_details['dbtype'] . 'Db';
		$db = &new $db_type();

		$connection = $db->Connect($db_details['hostname'], $db_details['username'], $db_details['password'], $db_details['dbname']);

		$query = "UPDATE " . $db_details['tableprefix'] . "users SET usertimezone='" . $db->Quote($settings_details['SERVERTIMEZONE']) . "', emailaddress='" . $db->Quote($settings_details['EMAIL_ADDRESS']) . "' WHERE userid='1'";

		$db->Query($query);

		?>
		<table cellspacing="0" cellpadding="0" width="95%" align="center">
			<tr>
				<td class="Heading1">Step 4 of 4: SendStudio Installation Complete</td>
			</tr>
			<tr>
				<td class="gap">&nbsp;</td>
			</tr>
			<tr>
				<td>
					<table class="panel" border="0" cellpadding="2" cellspacing="0" width="100%">

						<TR>
							<TD class="Content" colSpan="2">
								<TABLE id="Table2" style="BORDER-RIGHT: #adaaad 1px solid; BORDER-TOP: #adaaad 1px solid; BORDER-LEFT: #adaaad 1px solid; BORDER-BOTTOM: #adaaad 1px solid; BACKGROUND-COLOR: #f7f7f7"
									cellSpacing="0" cellPadding="10" width="95%" border="0">
									<TR>
										<TD>
											<TABLE width="100%" class="Message" cellSpacing="0" cellPadding="0" border="0">
												<TR>
													<TD width="20"><IMG height="18" hspace="10" src="images/success.gif" width="18" align="middle" vspace="5"></TD>
													<TD class="text">Congratulations! SendStudio has been successfully installed on your web server.<BR>
													</TD>
												</TR>
											</TABLE>
											<DIV class="text">
												<br/>
												Remember, you can modify any of the settings for SendStudio by clicking on the "Settings" tab in the SendStudio control panel.
												<br><br>
												Please click on the button below to login to the SendStudio control panel, where you can start adding content, etc.
												<br>
												Your account username is <B><I>admin</I></B> and your account password is <B><I>password</I></B>.
												<br/><br/>
												For scheduled sending and autoresponders, you will need this path for the cron jobs:<br/>
												<b><?php echo SENDSTUDIO_BASE_DIRECTORY; ?>/cron/cron.php</b>
											</DIV>
										</TD>
									</TR>
									<TR>
										<td><input class="formbutton" onclick="document.location.href='index.php';" type="button" value="Login"></td>
									</TR>
								</TABLE>
							</TD>
						</TR>
					</TABLE>
				</TD>
			</TR>
		</TABLE>
		<?php
	}

	function CheckWritable($file='')
	{
		if (!$file) {
			return false;
		}

		$unlink = false;

		if (!is_file($file)) {
			$unlink = true;
			if (is_dir($file)) {
				$file = $file . '/' . date('U') . '.php';
			} else {
				return false;
			}
		}

		if (!$fp = @fopen($file, 'w+')) {
			return false;
		}

		$contents = '<?php' . "\n";

		if (!@fputs($fp, $contents, strlen($contents))) {
			return false;
		}

		if (!@fclose($fp)) {
			return false;
		}

		if ($unlink) {
			if (!@unlink($file)) {
				return false;
			}
		}
		return true;
	}

}
?>
