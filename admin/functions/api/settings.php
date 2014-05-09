<?php
/**
* The Settings API.
*
* @version     $Id: settings.php,v 1.25 2007/06/18 03:29:39 chris Exp $

*
* @package API
* @subpackage Settings_API
*/


/**
* Include the base API class if we haven't already.
*/
require_once(dirname(__FILE__) . '/api.php');

/**
* This will load settings, set them and save them all for you.
*
* @package API
* @subpackage Settings_API
*/
class Settings_API extends API
{

	/**
	* A list of the current settings. This is used by load and save to store things temporarily.
	*
	* @see Areas
	* @see Load
	* @see Save
	*
	* @var Array
	*/
	var $Settings = array();

	/**
	* Used to store the location of the settings file temporarily.
	*
	* @see Settings_API
	*
	* @var String
	*/
	var $ConfigFile = false;

	/**
	* A list of areas that we hold settings for. This is used by 'save' in conjunction with Settings to see what will get saved.
	*
	* @see Save
	*
	* @var Array
	*/
	var $Areas = array('DATABASE_TYPE', 'DATABASE_USER', 'DATABASE_PASS', 'DATABASE_HOST', 'DATABASE_NAME', 'TABLEPREFIX', 'LICENSEKEY', 'APPLICATION_URL', 'SMTP_SERVER', 'SMTP_USERNAME', 'SMTP_PASSWORD', 'SMTP_PORT', 'SERVERTIMEZONE', 'FORCE_UNSUBLINK', 'HTMLFOOTER', 'TEXTFOOTER', 'MAXHOURLYRATE', 'MAXOVERSIZE', 'CRON_ENABLED', 'DEFAULTCHARSET', 'EMAIL_ADDRESS', 'IPTRACKING', 'MAX_IMAGEWIDTH', 'MAX_IMAGEHEIGHT', 'BOUNCE_ADDRESS', 'BOUNCE_SERVER', 'BOUNCE_USERNAME', 'BOUNCE_PASSWORD', 'BOUNCE_IMAP', 'BOUNCE_EXTRASETTINGS');

	/**
	* If cron is enabled, this setting is checked to make sure it's working ok. This allows the settings page to show a warning about it being set up properly or not.
	*
	* @see Load
	*
	* @var Boolean
	*/
	var $cronok = false;

	/**
	* The first time cron runs it will store information in cronrun1.
	*
	* @see Load
	*
	* @var Boolean
	*/
	var $cronrun1 = 0;

	/**
	* The second time cron runs it will store information in cronrun2.
	*
	* @see Load
	*
	* @var Boolean
	*/
	var $cronrun2 = 0;

	/**
	* The database version number.
	*
	* @see Load
	*/
	var $database_version = -1;

	/**
	* Constructor
	*
	* Sets the path to the config file. Loads up the database so it can check whether cron is set up properly or not.
	*
	* @param Boolean $load_settings Whether to load up the settings or not. Defaults to loading the settings.
	*
	* @see GetDb
	*
	* @return Void Doesn't return anything, just sets up the variables.
	*/
	function Settings_API($load_settings=true)
	{
		$this->ConfigFile = SENDSTUDIO_INCLUDES_DIRECTORY . '/config.php';
		if ($load_settings) {
			$db = $this->GetDb();
		}
	}

	/**
	* Load
	* Loads up the settings from the config file.
	*
	* @see CheckCron
	* @see Areas
	*
	* @return Boolean Will return false if the config file isn't present, otherwise it set the class vars and return true.
	*/
	function Load()
	{
		if (!$fp = fopen($this->ConfigFile, 'r')) {
			return false;
		}
		$contents = fread($fp, filesize($this->ConfigFile));
		fclose($fp);
		$this->CheckCron();
		return true;
	}

	/**
	* CheckCron
	* Checks whether cron has run ok and updated the settings database.
	*
	* @see cronok
	*
	* @return Boolean Returns true if the database has been updated, otherwise false.
	*/
	function CheckCron()
	{
		$cronok = false;
		$query = "SELECT * FROM " . SENDSTUDIO_TABLEPREFIX . "settings";
		$result = $this->Db->Query($query);
		$row = $this->Db->Fetch($result);
		if ($row['cronok'] == 1) {
			$cronok = true;
		}

		$this->cronok = $cronok;
		$this->cronrun1 = (int)$row['cronrun1'];
		$this->cronrun2 = (int)$row['cronrun2'];

		if (isset($row['database_version'])) {
			$this->database_version = $row['database_version'];
		} else {
			$query = "ALTER TABLE " . SENDSTUDIO_TABLEPREFIX . "settings ADD COLUMN database_version INT";
			$result = $this->Db->Query($query);
			if ($result) {
				$query = "UPDATE " . SENDSTUDIO_TABLEPREFIX . "settings SET database_version='0'";
				$result = $this->Db->Query($query);
			}
		}
		return $cronok;
	}

	/**
	* CronEnabled
	* Checks whether cron has been enabled or not.
	*
	* @see cronok
	*
	* @return Boolean Returns true if cron is enabled, otherwise false.
	*/
	function CronEnabled()
	{
		if (SENDSTUDIO_CRON_ENABLED == 1) {
			return true;
		}
		return false;
	}

	/**
	* UpdateCron
	* Updates the check for cron. If you disable cron on the settings page, it makes it re-check it.
	*
	* @see cronok
	*
	* @return True Always returns true.
	*/
	function UpdateCron()
	{
		$this->cronok = false;
		$query = "UPDATE " . SENDSTUDIO_TABLEPREFIX . "settings SET cronok='0', cronrun1=0, cronrun2=0";
		$this->Db->Query($query);
		return true;
	}

	/**
	* Save
	* This function saves the current class vars to the settings file.
	* It checks to make sure the file is writable, then places the appropriate values in there and saves it. It uses a temporary name then copies that over the top of the old one, then removes the temporary file.
	*
	* @return Boolean Returns true if it worked, false if it fails.
	*/
	function Save()
	{
		if (!is_writable($this->ConfigFile)) {
			return false;
		}

		$tmpfname = tempnam(TEMP_DIRECTORY, 'SS_');
		if (!$handle = fopen($tmpfname, 'w')) {
			return false;
		}

		$copy = true;
		if (is_file(SENDSTUDIO_INCLUDES_DIRECTORY . '/config.prev.php')) {
			if (!@unlink(SENDSTUDIO_INCLUDES_DIRECTORY . '/config.prev.php')) {
				$copy = false;
			}
		}

		if ($copy) {
			@copy($this->ConfigFile, SENDSTUDIO_INCLUDES_DIRECTORY . '/config.prev.php');
		}

		$contents = '';
		$contents .= '<?' . 'php' . "\n\n";

		foreach ($this->Areas as $area) {
			$string = 'define(\'SENDSTUDIO_' . $area . '\', \'' . addslashes($this->Settings[$area]) . '\');' . "\n";
			$contents .= $string;
		}

		$contents .= 'define(\'SENDSTUDIO_IS_SETUP\', 1);' . "\n";

		$contents .= "\n" . '?>' . "\n";

		fputs($handle, $contents, strlen($contents));
		fclose($handle);
		chmod($tmpfname, 0644);

		if (!copy($tmpfname, $this->ConfigFile)) {
			return false;
		}
		unlink($tmpfname);

		$copy = true;
		if (is_file(SENDSTUDIO_INCLUDES_DIRECTORY . '/config.bkp.php')) {
			if (!@unlink(SENDSTUDIO_INCLUDES_DIRECTORY . '/config.bkp.php')) {
				$copy = false;
			}
		}

		if ($copy) {
			@copy($this->ConfigFile, SENDSTUDIO_INCLUDES_DIRECTORY . '/config.bkp.php');
		}

		return true;
	}

	function GetDatabaseVersion()
	{
		if ($this->database_version == -1) {
			$this->CheckCron();
		}
		return $this->database_version;
	}

	function NeedDatabaseUpgrade()
	{
		if ($this->database_version == -1) {
			$this->CheckCron();
		}
		if ($this->database_version < SENDSTUDIO_DATABASE_VERSION) {
			return true;
		}
		return false;
	}
}

?>
