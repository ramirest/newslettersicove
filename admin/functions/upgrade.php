<?php

/**
* Include the base sendstudio functions.
*/
require(dirname(__FILE__) . '/sendstudio_functions.php');

/**
* Class for the upgrade process. This will run through all the queries needed to upgrade sendstudio 2004 to sendstudio nx, and change the config file.
*
* @package SendStudio
* @subpackage SendStudio_Functions
*/
class Upgrade extends SendStudio_Functions
{

	var $default_charset = 'ISO-8859-1';

	/**
	* Process
	* Works out which step we are up to in the install process and passes it off for the other methods to handle.
	*
	* @return Void Works out which step you are up to and that's it.
	*/
	function Process()
	{
		if (isset($_GET['Action'])) {
			$action = strtolower($_GET['Action']);
			switch ($action) {
				case 'createbackup':
					$this->CreateBackup();
					return;
				break;

				case 'upgradedatabase':
					$this->UpgradeDatabase();
					return;
				break;

				case 'copyfiles':
					$this->CopyFiles();
					return;
				break;
			}
		}

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
		<form method="post" action="index.php?Page=Upgrade&Step=1">
		<table cellSpacing="0" cellPadding="0" width="95%" align="center">
			<TR>
				<TD class="Heading1">Welcome to the Sendstudio Upgrade Wizard</TD>
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
													<TD class="text">Thank you for upgrading Sendstudio!<BR>
													</TD>
												</TR>
											</TABLE>
											<DIV class="text">
												Welcome to the Sendstudio upgrade wizard. Over the next 4 steps your current copy of SendStudio (including your database) will be upgraded.<br>Click the "Proceed" button below to get started and create a backup of your database.
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

		$session = &GetSession();
		$vars = array(
			'DatabaseTables_BackupErrors',
			'BackupFile',
			'DatabaseTables_Todo',
			'DatabaseTables_Done',

			'DatabaseUpgradesCompleted',
			'DatabaseUpgradesFailed',

			'DirectoriesToCopy',
			'DirectoriesCopied',
			'DirectoriesNotCopied'
		);
		foreach ($vars as $k => $var) {
			$session->Remove($var);
		}
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

		// since the upgrade from v2004 to nx can only be mysql, we don't need to check the database type.
		$query = "SELECT VERSION() AS version";
		$result = mysql_query($query);
		$row = mysql_fetch_assoc($result);
		$version = $row['version'];
		$compare = version_compare($version, '4.0');
		if ($compare < 0) {
			$serverError .= '<li>SendStudio NX requires MySQL v4.0 or above to work properly. Your server is running ' . $version . '. To complete the upgrade, your host will need to upgrade MySQL. Please contact your host to arrange this.</li>';
			$isOK = false;
		}

		if ($isOK) {
			return;
		}

		?>
		<form method="post" action="index.php?Page=Upgrade&Step=1">
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
													if ($serverError) {
														echo '<br/>';
													}
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
	* CheckFiles
	* Checks the number of files in the admin/language/upgrades/{upgrade_version}/ folder.
	*
	* @return Void If everything is ok, this will return out of the function. If something is wrong, it prints an error message and the script dies.
	*/
	function CheckFiles()
	{

		$upgrade_api = &new Upgrade_API();

		$upgrades_to_run = $upgrade_api->Get('upgrades_to_run');

		$nx_upgrades = $upgrades_to_run['nx'];

		$nx_upgrades_found = sizeof($nx_upgrades);

		$upgrades_found = list_files(SENDSTUDIO_LANGUAGE_DIRECTORY . '/upgrades/nx');

		if (sizeof($upgrades_found) == $nx_upgrades_found) {
			return;
		}

		$upgrades_found = str_replace('.php', '', $upgrades_found);

		$missing_files = array_diff($nx_upgrades, $upgrades_found);

		?>
		<form method="post" action="index.php?Page=Upgrade&Step=1">
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
											The following file(s) are missing from the admin/language/upgrades/nx/ folder:<br/>
											<ul>
												<?php
													foreach ($missing_files as $p => $filename) {
														echo '<li>' . $filename . '.php</li>';
													}
												?>
											</ul>
											Once you have uploaded the file(s) mentioned, try again.
											<br/>
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
	function ShowStep_1()
	{
		$already_upgraded = $this->AlreadyUpgraded();

		if ($already_upgraded) {
			return;
		}

		$permissions = $this->CheckPermissions();

		$check_files = $this->CheckFiles();

		?>
		<form>
		<table cellspacing="0" cellpadding="0" width="95%" align="center">
			<TR>
				<td class="heading1">Step 1: Creating a database backup</td>
			</TR>
			<TR>
				<TD class="text"><br/><br/></TD>
			</TR>
			<TR>
				<TD>
					<table class="panel" border="0" cellpadding="2" cellspacing="0" width="100%">
						<tr class="Heading3">
							<td colspan="2">
								&nbsp;&nbsp;Database Backup
							</td>
						</tr>
						<tr>
							<td>
								&nbsp;
							</td>
							<td style="padding:10px">
								Before upgrading, your existing SendStudio database will be backed up. If at any time during the upgrade something goes wrong,<br>we can use this backup to restore your database. Click the "Create Backup" button below to continue.
								<br><br>
								<input type="button" value="Create Backup &raquo;" class="field150" onclick="ShowBackup();">
							</td>
						</tr>
					</table>
				</TD>
			</TR>
		</TABLE>
		</form>
		<script>
			function ShowBackup()
			{
				var top = screen.height / 2 - (170);
				var left = screen.width / 2 - (140);

				window.open("index.php?Page=Upgrade&Action=CreateBackup","backupWin","left=" + left + ",top="+top+",toolbar=false,status=no,directories=false,menubar=false,scrollbars=false,resizable=false,copyhistory=false,width=360,height=200");
			}
		</script>
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
	function ShowStep_2()
	{
		$session = &GetSession();
		global $ROOTURL;

		$backup_errors = $session->Get('DatabaseTables_BackupErrors');

		if (!empty($backup_errors)) {
			?>
				<table cellspacing="0" cellpadding="0" width="95%" align="center">
					<tr>
						<td class="Heading1">Step 1: Backup Errors</TD>
					</tr>
					<tr>
						<td class="text">
							<br/>There were problems creating a backup of your database. Please contact <a href="mailto:help">help</a> before proceeding.<br/><br/>
						</TD>
					</TR>
				</TABLE>
			<?php
			return;
		}

		$backup_file = $session->Get('BackupFile');
		$backup_url = str_replace(TEMP_DIRECTORY, substr($ROOTURL, 0, -1) . SENDSTUDIO_TEMP_URL, $backup_file);

		?>
		<form>
		<table cellspacing="0" cellpadding="0" width="95%" align="center">
			<tr>
				<td class="Heading1">Step 2: Copying Images and Attachments</TD>
			</tr>
			<TR>
				<TD class="text"><br/><br/></TD>
			</TR>
			<TR>
				<TD>
					<table class="panel" border="0" cellpadding="2" cellspacing="0" width="100%">
						<tr class="Heading3">
							<td colspan="2">
								&nbsp;&nbsp;Copying Images and Attachments
							</td>
						</tr>
						<tr>
							<td>
								&nbsp;
							</td>
							<td style="padding:10px">
								You database has been backed up. Right click <a href="<?php echo $backup_url; ?>" target="_blank">this link</a> and choose "Save As" to save the backup to your hard drive.<br>The upgrade wizard will now copy your images and attachments to their new locations. Click "Copy Files" to continue.
								<br><br>
								<input type="button" value="Copy Files &raquo;" class="field150" onclick="ShowCopy();">
							</td>
						</tr>
					</table>
				</TD>
			</TR>
		</TABLE>
		</form>
		<script>
			function ShowCopy() {
				var top = screen.height / 2 - (170);
				var left = screen.width / 2 - (140);

				window.open("index.php?Page=Upgrade&Action=CopyFiles","copyWin","left=" + left + ",top="+top+",toolbar=false,status=no,directories=false,menubar=false,scrollbars=false,resizable=false,copyhistory=false,width=360,height=200");
			}
		</script>
		<?php
	}

	function ShowStep_3()
	{
		$session = &GetSession();

		$copy_errors = $session->Get('DirectoriesNotCopied');

		if (!empty($copy_errors)) {
			?>
				<table cellspacing="0" cellpadding="0" width="95%" align="center">
					<tr>
						<td class="Heading1">Step 2: Copy Errors</TD>
					</tr>
					<tr>
						<td class="text">
							<br/>There were problems copying images and attachments to their new locations. Please contact <a href="mailto:help">help</a> before proceeding.<br/><br/>
						</TD>
					</TR>
				</TABLE>
			<?php
			return;
		}
		?>
		<form>
		<table cellspacing="0" cellpadding="0" width="95%" align="center">
			<tr>
				<td class="Heading1">Step 3: Database Upgrade</TD>
			</tr>
			<TR>
				<TD class="text"><br/><br/></TD>
			</TR>
			<TR>
				<TD>
					<table class="panel" border="0" cellpadding="2" cellspacing="0" width="100%">
						<tr class="Heading3">
							<td colspan="2">
								&nbsp;&nbsp;Upgrade database
							</td>
						</tr>
						<tr>
							<td>
								&nbsp;
							</td>
							<td style="padding:10px">
								Your database has been backed up successfully.
								<?php
									if (SENDSTUDIO_SAFE_MODE) {
										echo '<br/><br/><b>There were problems copying images and attachments to their new locations. This is because safe-mode is enabled on your server. This step has been bypassed, please contact <a href="mailto:help">help</a> once your upgrade has finished.</b><br/>';
									} else {
										echo 'Your images and attachments have also been copied over successfully.';
									}
								?><br>Click the "Upgrade Database" button below to upgrade your SendStudio database.
								<br><br>
								<input type="button" value="Upgrade Database &raquo;" class="field150" onclick="ShowUpgrade();">
							</td>
						</tr>
					</table>
				</TD>
			</TR>
		</TABLE>
		</form>
		<script>
			function ShowUpgrade()
			{
				var top = screen.height / 2 - (170);
				var left = screen.width / 2 - (140);

				window.open("index.php?Page=Upgrade&Action=UpgradeDatabase","upgradeWin","left=" + left + ",top="+top+",toolbar=false,status=no,directories=false,menubar=false,scrollbars=false,resizable=false,copyhistory=false,width=360,height=200");
			}
		</script>
		<?php
	}

	function ShowStep_4()
	{
		$session = &GetSession();

		$upgrade_errors = $session->Get('DatabaseUpgradesFailed');

		if (!empty($upgrade_errors)) {
			?>
				<table cellspacing="0" cellpadding="0" width="95%" align="center">
					<tr>
						<td class="Heading1">Step 3: Upgrade Errors</TD>
					</tr>
					<tr>
						<td class="text">
							<br/>There were problems upgrading your database. Please contact <a href="mailto:help">help</a> and tell them the following errors occurred:<br/><br/>
							<textarea cols="100" rows="5" onfocus="this.select();"><?php
								foreach ($upgrade_errors as $p => $upgrade_problem) {
									echo $upgrade_problem . "\n";
								}
							?></textarea>
							<br/>
						</TD>
					</TR>
				</TABLE>
			<?php
			return;
		}

		$backup_file = $session->Get('BackupFile');
		if ($backup_file) {
			$backup_files = list_files(TEMP_DIRECTORY);
			foreach ($backup_files as $p => $backupfile) {
				if (strpos($backupfile, 'system_backup.'.date('m-d-Y').'.txt') !== false) {
					unlink(TEMP_DIRECTORY . '/' . $backupfile);
				}
			}
		}

		global $DBHOST, $DBUSER, $DBPASS, $DBNAME, $TABLEPREFIX;
		global $LicenseKey;
		global $ROOTURL;
		global $ServerSending;

		require(SENDSTUDIO_API_DIRECTORY . '/settings.php');

		$settings_api = &new Settings_API(false);

		$settings = array();
		// hardcode this in, for this upgrade it's always going to be mysql.
		$settings['DATABASE_TYPE'] = 'mysql';
		$settings['DATABASE_USER'] = $DBUSER;
		$settings['DATABASE_PASS'] = $DBPASS;
		$settings['DATABASE_HOST'] = $DBHOST;
		$settings['DATABASE_NAME'] = $DBNAME;
		$settings['TABLEPREFIX'] = $TABLEPREFIX;

		$settings['LICENSEKEY'] = $LicenseKey;

		$settings['APPLICATION_URL'] = substr($ROOTURL, 0, -1);

		$settings['CRON_ENABLED'] = $ServerSending;

		$timezone = date('O');
		$minutes = substr($timezone, -2);
		$timezone = 'GMT' . substr_replace($timezone, ':' . $minutes, -2);

		$settings['SERVERTIMEZONE'] = str_replace(array('GMT-0', 'GMT+0'), array('GMT-', 'GMT+'), $timezone);

		$settings['DEFAULTCHARSET'] = $this->default_charset;

		$empty_settings = array('SMTP_SERVER', 'SMTP_USERNAME', 'SMTP_PASSWORD', 'HTMLFOOTER', 'TEXTFOOTER', 'EMAIL_ADDRESS', 'BOUNCE_ADDRESS', 'BOUNCE_SERVER', 'BOUNCE_USERNAME', 'BOUNCE_PASSWORD', 'BOUNCE_EXTRASETTINGS');
		foreach ($empty_settings as $k => $set) {
			$settings[$set] = '';
		}

		$zero_settings = array('SMTP_PORT', 'FORCE_UNSUBLINK', 'MAXHOURLYRATE', 'MAXOVERSIZE', 'IPTRACKING', 'BOUNCE_IMAP');
		foreach ($zero_settings as $k => $set) {
			$settings[$set] = '0';
		}

		$settings['MAX_IMAGEWIDTH'] = 700;
		$settings['MAX_IMAGEHEIGHT'] = 400;

		$settings_api->Set('Settings', $settings);

		$settings_api->Save();

		?>
			<table cellspacing="0" cellpadding="0" width="95%" align="center">
				<tr>
					<td class="Heading1">Step 4: Upgrade Complete</TD>
				</tr>
				<TR>
					<TD class="text"><br/><br/></TD>
				</TR>
				<TR>
					<TD>
						<table class="panel" border="0" cellpadding="2" cellspacing="0" width="100%">
							<tr class="Heading3">
								<td colspan="2">
									&nbsp;&nbsp;Important Notes. Please Read.
								</td>
							</tr>
							<tr>
								<td>
									&nbsp;
								</td>
								<td style="padding:10px">
									<br/>The upgrade wizard has been completed successfully. You can log in <a href="<?php echo $_SERVER['PHP_SELF']; ?>">here</a> - your login details have not changed.<br>It's very important that you read the notes below, so please do that now:<br/><br/>
									<ul>
										<li>The default character set is set to 'ISO-8859-1'. If you need to change this, you will need to edit your admin/includes/config.php file to change it to 'UTF-8'.</li>
										<li>Sendstudio now supports timezones. Please check the settings page and confirm the server timezone. Please also check the timezone for each user and adjust it accordingly, they have all been set to GMT.</li>
										<li>Information (such as the date a person unsubscribed) was not stored, so the upgrade had to "guess" when this happened and set all of that information to today's date.</li>
										<li>Existing autoresponder statistics are not accurate. Information about who was sent which type of autoresponder was previously not recorded. That is, whether a subscriber was sent the html version or the text version.</li>
										<li>Users & settings have a lot of new options.</li>
										<li>Custom fields have been associated with all of a users mailing list. Please check these associations.</li>
										<li>All forms have been set to 'Classic White (Default)', please adjust as necessary.</li>
										<li>You may need to clear your browsers cache to see the new images and buttons.</li>
									</ul>
								</td>
							</tr>
						</table>
					</TD>
				</TR>
			</TABLE>
		<?php
	}

	function CopyFiles()
	{
		if (SENDSTUDIO_SAFE_MODE) {
			?>
				<script>
					window.opener.document.location = 'index.php?Page=Upgrade&Step=3';
					window.close();
				</script>
			<?php
			return;
		}

		// from the old config
		global $ROOTDIR, $TABLEPREFIX;

		$session = &GetSession();
		$dirs_to_copy = $session->Get('DirectoriesToCopy');
		if (!$dirs_to_copy) {
			$dirs_to_copy = list_directories($ROOTDIR . 'temp/images', null, true);

			$session->Set('DirectoriesToCopy', $dirs_to_copy);

			$dirs_copied = array();
			$session->Set('DirectoriesCopied', $dirs_copied);

			$dirs_not_copied = array();
			$session->Set('DirectoriesNotCopied', $dirs_not_copied);
		}

		$dirs_to_copy = $session->Get('DirectoriesToCopy');
		$dirs_copied = $session->Get('DirectoriesCopied');

		if ($dirs_to_copy == $dirs_copied) {

			// copy attachments last. there won't be too many of these so we'll do it all in one step.
			$all_attachments = array();
			$query = "SELECT AttachmentID, AttachmentFilename, AttachmentName FROM " . $TABLEPREFIX . "attachments";
			$result = mysql_query($query);
			while ($row = mysql_fetch_assoc($result)) {
				$all_attachments[$row['AttachmentID']] = array('filename' => $row['AttachmentFilename'], 'realname' => $row['AttachmentName']);
			}

			if (!empty($all_attachments)) {
				$query = "select ComposedID, AttachmentIDs from " . $TABLEPREFIX . "composed_emails where attachmentids != ''";
				$result = mysql_query($query);
				while ($row = mysql_fetch_assoc($result)) {
					$new_folder = TEMP_DIRECTORY . '/newsletters/' . $row['ComposedID'];
					CreateDirectory($new_folder);
					$attachments = explode(':', stripslashes($row['AttachmentIDs']));
					foreach ($attachments as $k => $attachid) {
						$fname = basename($all_attachments[$attachid]['filename']);
						$file = $ROOTDIR . 'temp/attachments/' . $fname;

						$realname = $all_attachments[$attachid]['realname'];
						copy($file, $new_folder . '/' . $realname);

						if (!SENDSTUDIO_SAFE_MODE) {
							@chmod($new_folder . '/' . $realname, 0644);
						}
					}
				}

				$query = "select AutoresponderID, AttachmentIDs from " . $TABLEPREFIX . "autoresponders where attachmentids != ''";
				$result = mysql_query($query);
				while ($row = mysql_fetch_assoc($result)) {
					$new_folder = TEMP_DIRECTORY . '/autoresponders/' . $row['ComposedID'];
					CreateDirectory($new_folder);
					$attachments = explode(':', stripslashes($row['AttachmentIDs']));
					foreach ($attachments as $k => $attachid) {
						$fname = basename($all_attachments[$attachid]['filename']);
						$file = $ROOTDIR . 'temp/attachments/' . $fname;

						$realname = $all_attachments[$attachid]['realname'];
						copy($file, $new_folder . '/' . $realname);

						if (!SENDSTUDIO_SAFE_MODE) {
							@chmod($new_folder . '/' . $realname, 0644);
						}
					}
				}
			}
			?>
				<script>
					window.opener.document.location = 'index.php?Page=Upgrade&Step=3';
					window.close();
				</script>
			<?php
			return;
		}

		$this->PrintHeader(true);

		foreach ($dirs_to_copy as $p => $dir) {
			if (in_array($dir, $dirs_copied)) {
				continue;
			}

			echo 'Copying directory ' . str_replace($ROOTDIR, '', $dir) . ' to new location...<br/>';
			$this->PrintFooter(true);


			$new_dir = str_replace($ROOTDIR . 'temp/images', TEMP_DIRECTORY . '/user', $dir);
			$copied = CopyDirectory($dir, $new_dir);
			if (!$copied) {
				$dirs_not_copied[] = $dir;
				$session->Set('DirectoriesNotCopied', $dirs_not_copied);
			}
			$dirs_copied[] = $dir;
			$session->Set('DirectoriesCopied', $dirs_copied);
		}
		?>
			<script language="javascript">
				setTimeout('window.location="index.php?Page=Upgrade&Action=CopyFiles"', 1);
			</script>
		<?php

	}

	function UpgradeDatabase()
	{
		$session = &GetSession();

		$disabled_functions = explode(',', str_replace(' ', '', SENDSTUDIO_DISABLED_FUNCTIONS));

		if (!SENDSTUDIO_SAFE_MODE && !in_array('set_time_limit', $disabled_functions)) {
			set_time_limit(0);
		}

		global $DBHOST, $DBUSER, $DBPASS, $DBNAME, $TABLEPREFIX;

		define('SENDSTUDIO_DATABASE_TYPE', 'mysql');
		define('SENDSTUDIO_DATABASE_HOST', $DBHOST);
		define('SENDSTUDIO_DATABASE_USER', $DBUSER);
		define('SENDSTUDIO_DATABASE_PASS', $DBPASS);
		define('SENDSTUDIO_DATABASE_NAME', $DBNAME);
		define('SENDSTUDIO_TABLEPREFIX', $TABLEPREFIX);

		if (!defined('SENDSTUDIO_DEFAULTCHARSET')) {
			define('SENDSTUDIO_DEFAULTCHARSET', $this->default_charset);
		}

		$upgrades_done = $session->Get('DatabaseUpgradesCompleted');
		if (!$upgrades_done) {
			$upgrades_done = array();
			$session->Set('DatabaseUpgradesCompleted', $upgrades_done);
			$upgrades_failed = array();
			$session->Set('DatabaseUpgradesFailed', $upgrades_failed);
		}

		$upgrades_failed = $session->Get('DatabaseUpgradesFailed');

		require(SENDSTUDIO_API_DIRECTORY . '/upgrade.php');

		$upgrade_api = &new Upgrade_API();

		$server_timeoffset = date('O');

		$offset_direction = $server_timeoffset{0};

		$offset_hours = $server_timeoffset{1} . $server_timeoffset{2};

		$offset_minutes = $server_timeoffset{3} . $server_timeoffset{4};

		$offset_query = "-(" . $offset_direction . (60 * 60) * (($offset_hours . $offset_minutes) / 100) . ")";

		$upgrade_api->Set('offset_query', $offset_query);

		$running_upgrade = $upgrade_api->GetNextUpgrade('nx');
		if (!is_null($running_upgrade)) {
			$this->PrintHeader(true);
			$desc = 'Running queries for \'' . $running_upgrade . '\'';
			$friendly_desc = $upgrade_api->Get('FriendlyDescription');
			if ($friendly_desc) {
				$desc = $friendly_desc;
			}
			echo $desc . '<br/>';
			$this->PrintFooter(true);

			$upgrade_result = $upgrade_api->RunUpgrade('nx', $running_upgrade);

			$upgrades_done[] = $running_upgrade;
			$session->Set('DatabaseUpgradesCompleted', $upgrades_done);

			if (!$upgrade_result) {
				$upgrades_failed[] = $upgrade_api->Get('error');
				$session->Set('DatabaseUpgradesFailed', $upgrades_failed);
			}

			?>
				<script language="javascript">
					setTimeout('window.location="index.php?Page=Upgrade&Action=UpgradeDatabase"', 1);
				</script>
			<?php
			return;
		}
		?>
			<script>
				window.opener.document.location = 'index.php?Page=Upgrade&Step=4';
				window.close();
			</script>
		<?php
		return;
	}

	function CreateBackup()
	{
		$session = &GetSession();

		$disabled_functions = explode(',', str_replace(' ', '', SENDSTUDIO_DISABLED_FUNCTIONS));

		if (!SENDSTUDIO_SAFE_MODE && !in_array('set_time_limit', $disabled_functions)) {
			set_time_limit(0);
		}

		$backupfile = $session->Get('BackupFile');
		if (!$backupfile) {
			$orig_backupfile = TEMP_DIRECTORY . '/system_backup.' . date('m-d-Y').'.txt';
			$backupfile = $orig_backupfile;
			$c = 1;
			while (true) {
				if (!is_file($backupfile)) {
					break;
				}
				$backupfile = $orig_backupfile . '.' . $c;
				$c++;
			}

			$session->Set('BackupFile', $backupfile);

			$tables_todo = $this->FetchTables();
			$session->Set('DatabaseTables_Todo', $tables_todo);

			$tables_done = array();
			$session->Set('DatabaseTables_Done', $tables_done);

			$backup_errors = array();
			$session->Set('DatabaseTables_BackupErrors', $backup_errors);
		}

		$tables_todo = $session->Get('DatabaseTables_Todo');

		$tables_done = $session->Get('DatabaseTables_Done');

		$backup_errors = $session->Get('DatabaseTables_BackupErrors');

		if ($tables_done == $tables_todo) {
			?>
				<script>
					window.opener.document.location = 'index.php?Page=Upgrade&Step=2';
					window.close();
				</script>
			<?php
			return;
		}

		$this->PrintHeader(true);

		foreach ($tables_todo as $p => $table) {
			if (in_array($table, $tables_done)) {
				continue;
			}

			echo "Backing up table '" . $table . "'..<br/>\n";

			$this->PrintFooter(true);

			$result = $this->BackupTable($table, $backupfile);
			if (!$result) {
				$backup_errors[] = $table;
			}
			$tables_done[] = $table;
			break;
		}

		$session->Set('DatabaseTables_Done', $tables_done);
		$session->Set('DatabaseTables_BackupErrors', $backup_errors);
		?>
			<script language="javascript">
				setTimeout('window.location="index.php?Page=Upgrade&Action=CreateBackup"', 1);
			</script>
		<?php
	}

	/**
	* BackupTable
	* Since 2004 -> NX can only be a mysql upgrade, we'll just use native functions.
	*/
	function BackupTable($tablename='', $filename='')
	{
		if ($tablename == '' || $filename == '') return false;
		if (!$fp = fopen($filename, 'a+')) return false;

		$drop_table = "DROP TABLE IF EXISTS " . $tablename . ";\n";
		fputs($fp, $drop_table);

		$qry = "SHOW CREATE TABLE " . $tablename;
		$result = mysql_query($qry);
		$create_table = mysql_result($result, 0, 1) . ";\n";

		fputs($fp, $create_table);

		$qry = "SELECT * FROM " . $tablename;
		$result = mysql_query($qry);
		while($row = mysql_fetch_assoc($result)) {
			$insert_query_fields = $insert_query_values = array();
			foreach($row as $name => $val) {
				$insert_query_fields[] = $name;
				$insert_query_values[] = str_replace("'", "\'", stripslashes($val));
			}
			$insert_query = "INSERT INTO " . $tablename . "(" . implode(',', $insert_query_fields) . ") VALUES ('" . implode("','", $insert_query_values) . "');\n";
			fputs($fp, $insert_query);
		}

		$empty_lines = "\n";
		fputs($fp, $empty_lines);

		fclose($fp);
		return true;
	}

	/**
	* FetchTables
	* Since 2004 -> NX can only be a mysql upgrade, we'll just use native functions.
	*/
	function FetchTables()
	{
		global $TABLEPREFIX, $DBNAME;
		$qry = "SHOW TABLES LIKE '" . addslashes($TABLEPREFIX) . "%'";
		$result = mysql_query($qry);
		$return = array();
		while($row = mysql_fetch_assoc($result)) {
			$return[] = array_pop($row);
		}
		return $return;
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

	function AlreadyUpgraded()
	{
		global $DBHOST, $DBUSER, $DBPASS, $DBNAME, $TABLEPREFIX;

		define('SENDSTUDIO_DATABASE_TYPE', 'mysql');
		define('SENDSTUDIO_DATABASE_HOST', $DBHOST);
		define('SENDSTUDIO_DATABASE_USER', $DBUSER);
		define('SENDSTUDIO_DATABASE_PASS', $DBPASS);
		define('SENDSTUDIO_DATABASE_NAME', $DBNAME);
		define('SENDSTUDIO_TABLEPREFIX', $TABLEPREFIX);

		require(SENDSTUDIO_API_DIRECTORY . '/upgrade.php');

		$upgrade_api = &new Upgrade_API();

		$query = "SELECT COUNT(*) AS subcount FROM " . $TABLEPREFIX . "users";
		$result = $upgrade_api->Db->Query($query, true);
		// the table already exists?! That's bad.
		if ($result) {
			$count = $upgrade_api->Db->FetchOne($result, 'subcount');
			if ($count > 0) {
				?>
					<table cellspacing="0" cellpadding="0" width="95%" align="center">
						<tr>
							<td class="Heading1">Step 1: Problem Upgrading</TD>
						</tr>
						<tr>
							<td class="text">
								<br/>
								<table class="panel">
									<tr>
										<td class='Message' width='20' valign='top'>
											<img src='images/error.gif' width='18' height='18' hspace='10' vspace='5'>
										</td>
										<td class='Message' width='100%'>
											SendStudio NX seems to be installed in this database already. If you are upgrading from a previous version of SendStudio NX, please restore the admin/includes/config.php file from your backup.
										</td>
									</tr>
								</table>
								<br/>
							</td>
						</tr>
					</table>
				<?php
				return true;
			}
		}
		return false;
	}
}
?>
