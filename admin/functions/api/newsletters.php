<?php
/**
* The Newsletter API.
*
* @version     $Id: newsletters.php,v 1.35 2007/05/28 06:56:20 scott Exp $

*
* @package API
* @subpackage Newsletters_API
*/

/**
* Include the base API class if we haven't already.
*/
require_once(dirname(__FILE__) . '/api.php');

/**
* This will load a newsletter, save a newsletter, set details and get details.
* It will also check access areas.
*
* @package API
* @subpackage Newsletters_API
*/
class Newsletters_API extends API
{

	/**
	* The newsletter that is loaded. By default is 0 (no newsletter).
	*
	* @var Int
	*/
	var $newsletterid = 0;

	/**
	* Name of the newsletter that we've loaded.
	*
	* @var String
	*/
	var $name = '';

	/**
	* Subject of the newsletter that we've loaded.
	*
	* @var String
	*/
	var $subject = '';

	/**
	* The text version of the newsletter
	*
	* @var String
	*/
	var $textbody = '';

	/**
	* The html version of the newsletter
	*
	* @var String
	*/
	var $htmlbody = '';

	/**
	* The newsletters' format
	*
	* @var String
	*/
	var $format = 'h';

	/**
	* Whether the newsletter is active or not.
	*
	* @see Active
	*
	* @var Int
	*/
	var $active = 0;

	/**
	* Whether to show this newsletter in the archive or not.
	*
	* @see Archive
	*
	* @var Int
	*/
	var $archive = 0;

	/**
	* The userid of the owner of this newsletter.
	*
	* @var Int
	*/
	var $ownerid = 0;

	/**
	* The timestamp of when the newsletter was created (integer)
	*
	* @var Int
	*/
	var $createdate = 0;

	/**
	* Default Order to show newsletters in.
	*
	* @see GetNewsletters
	*
	* @var String
	*/
	var $DefaultOrder = 'createdate';

	/**
	* Default direction to show newsletters in.
	*
	* @see GetNewsletters
	*
	* @var String
	*/
	var $DefaultDirection = 'down';

	/**
	* An array of valid sorts that we can use here. This makes sure someone doesn't change the query to try and create an sql error.
	*
	* @see GetNewsletters
	*
	* @var Array
	*/
	var $ValidSorts = array('name' => 'Name', 'date' => 'CreateDate', 'subject' => 'Subject');

	/**
	* Constructor
	* Sets up the database object, loads the newsletter if the ID passed in is not 0.
	*
	* @param Int $newsletterid The newsletterid of the newsletter to load. If it is 0 then you get a base class only. Passing in a newsletterid > 0 will load that newsletter.
	*
	* @see GetDb
	* @see Load
	*
	* @return Boolean If no newsletterid is passed in, this will return true. Otherwise, it will call Load and return that status.
	*/
	function Newsletters_API($newsletterid=0)
	{
		$this->GetDb();
		if ($newsletterid > 0) {
			return $this->Load($newsletterid);
		}
		return true;
	}

	/**
	* Load
	* Loads up the newsletter and sets the appropriate class variables.
	*
	* @param Int $newsletterid The newsletterid to load up. If the newsletterid is not present then it will not load up.
	*
	* @return Boolean Will return false if the newsletterid is not present, or the newsletter can't be found, otherwise it set the class vars and return true.
	*/
	function Load($newsletterid=0)
	{
		$newsletterid = (int)$newsletterid;
		if ($newsletterid <= 0) {
			return false;
		}

		$query = "SELECT * FROM " . SENDSTUDIO_TABLEPREFIX . "newsletters WHERE newsletterid='" . $newsletterid . "'";
		$result = $this->Db->Query($query);
		if (!$result) {
			return false;
		}

		$newsletter = $this->Db->Fetch($result);
		if (empty($newsletter)) {
			return false;
		}

		$this->newsletterid = $newsletter['newsletterid'];
		$this->name = $newsletter['name'];
		$this->createdate = $newsletter['createdate'];
		$this->format = $newsletter['format'];
		$this->textbody = $newsletter['textbody'];
		$this->htmlbody = $newsletter['htmlbody'];
		$this->active = $newsletter['active'];
		$this->archive = $newsletter['archive'];
		$this->subject = $newsletter['subject'];
		$this->ownerid = $newsletter['ownerid'];
		return true;
	}

	/**
	* Create
	* This function creates a newsletter based on the current class vars.
	*
	* @return Boolean Returns true if it worked, false if it fails.
	*/
	function Create()
	{
		$query = "INSERT INTO " . SENDSTUDIO_TABLEPREFIX . "newsletters(name, format, active, archive, subject, textbody, htmlbody, createdate, ownerid) VALUES('" . $this->Db->Quote($this->name) . "', '" . $this->Db->Quote($this->format) . "', '" . (int)$this->active . "', '" . (int)$this->archive . "', '" . $this->Db->Quote($this->subject) . "', '" . $this->Db->Quote($this->textbody) . "', '" . $this->Db->Quote($this->htmlbody) . "', '" . $this->GetServerTime() . "', '" . $this->Db->Quote($this->ownerid) . "')";

		$result = $this->Db->Query($query);
		if ($result) {
			$newsletterid = $this->Db->LastId(SENDSTUDIO_TABLEPREFIX . 'newsletters_sequence');
			$this->newsletterid = $newsletterid;
			return $newsletterid;
		}
		return false;
	}

	/**
	* Delete
	* Delete a newsletter from the database
	*
	* @param Int $newsletterid Newsletterid of the newsletter to delete. If not passed in, it will delete 'this' newsletter. We delete the newsletter, then reset all class vars.
	* @param Int $userid The user doing the deleting of the newsletter. This is passed through to the stats api to "hide" statistics rather than deleting them.
	*
	* @see Stats_API::HideStats
	*
	* @return Boolean True if it deleted the newsletter, false otherwise.
	*
	*/
	function Delete($newsletterid=0, $userid=0)
	{
		if ($newsletterid == 0) {
			$newsletterid = $this->newsletterid;
		}

		$query = "DELETE FROM " . SENDSTUDIO_TABLEPREFIX . "newsletters WHERE newsletterid='" . $newsletterid. "'";
		$result = $this->Db->Query($query);
		if (!$result) {
			list($error, $level) = $this->Db->GetError();
			trigger_error($error, $level);
			return false;
		}

		$newsletter_dir = TEMP_DIRECTORY . '/newsletters/' . $newsletterid;
		remove_directory($newsletter_dir);

		$this->newsletterid = 0;
		$this->name = '';
		$this->format = 'h';
		$this->active = 0;
		$this->archive = 0;
		$this->SetBody('text', '');
		$this->SetBody('html', '');
		$this->ownerid = 0;

		$stats = array();
		$query = "SELECT statid FROM " . SENDSTUDIO_TABLEPREFIX . "stats_newsletters WHERE newsletterid='" . $newsletterid . "'";
		$result = $this->Db->Query($query);
		while ($row = $this->Db->Fetch($result)) {
			$stats[] = $row['statid'];
		}

		// clean up stats
		if (!class_exists('stats_api')) {
			require(dirname(__FILE__) . '/stats.php');
		}

		$stats_api = &new Stats_API();

		$stats_api->HideStats($stats, 'newsletter', $userid);

		return true;
	}

	/**
	* Copy
	* Copy a newsletter along with attachments, images etc.
	*
	* @param Int $oldid Newsletterid of the newsletter to copy.
	*
	* @see Load
	* @see Create
	* @see CopyDirectory
	* @see Save
	*
	* @return Array Returns an array of statuses. The first one is whether the newsletter could be found/loaded/copied, the second is whether the images/attachments could be copied. Both are true for success, false for failure.
	*/
	function Copy($oldid=0)
	{
		if ($oldid <= 0) {
			return array(false, false);
		}

		if (!$this->Load($oldid)) {
			return array(false, false);
		}

		$this->name = GetLang('CopyPrefix') . $this->name;

		$newid = $this->Create();
		if (!$newid) {
			return array(false, false);
		}

		$this->Load($newid);

		$this->createdate = $this->GetServerTime();

		$olddir = TEMP_DIRECTORY . '/newsletters/' . $oldid;
		$newdir = TEMP_DIRECTORY . '/newsletters/' . $newid;

		$status = CopyDirectory($olddir, $newdir);

		$this->textbody = str_replace('newsletters/' . $oldid, 'newsletters/' . $newid, $this->textbody);
		$this->htmlbody = str_replace('newsletters/' . $oldid, 'newsletters/' . $newid, $this->htmlbody);

		$this->Save();

		return array(true, $status);
	}

	/**
	* Active
	* Returns whether the newsletter is active or not. We remember who made it active (their userid) so we can't just check on/off status. 0 means it's inactive, anything else means user 'x' made it active.
	*
	* @return Boolean Returns true if the newsletter is active, otherwise returns false.
	*/
	function Active()
	{
		if ($this->active < 1) {
			return false;
		}

		return true;
	}

	/**
	* Archive
	* Returns whether the newsletter is archiveable or not. An inactive newsletter cannot be archived.
	*
	* @return Boolean Returns true if the newsletter is ok to archive, otherwise returns false.
	*/
	function Archive()
	{
		return $this->archive;
	}

	/**
	* Save
	* This function saves the current class vars to the newsletter. If there is no newsletter currently loaded, this will return false.
	*
	* @return Boolean Returns true if it worked, false if it fails.
	*/
	function Save()
	{
		if ($this->newsletterid <= 0) {
			return false;
		}

		$query = "UPDATE " . SENDSTUDIO_TABLEPREFIX . "newsletters SET name='" . $this->Db->Quote($this->name) . "', subject='" . $this->Db->Quote($this->subject) . "', textbody='" . $this->Db->Quote($this->textbody) . "', htmlbody='" . $this->Db->Quote($this->htmlbody) . "', format='" . $this->Db->Quote($this->format) . "', active='" . (int)$this->active . "', archive='" . (int)$this->archive . "' WHERE newsletterid='" . $this->Db->Quote($this->newsletterid) . "'";

		$result = $this->Db->Query($query);
		if (!$result) {
			list($error, $level) = $this->Db->GetError();
			trigger_error($error, $level);
			return false;
		}
		return true;
	}

	/**
	* GetLiveNewsletters
	* This function only retrieves live newsletters from the database. It will find active newsletters. If you pass in an owner, it will also find their newsletters that are active.
	*
	* @param Int $ownerid The ownerid to fetch newsletters for.
	*
	* @see active
	*
	* @return Array Returns an array of newsletters that are live.
	*/
	function GetLiveNewsletters($ownerid=0)
	{
		$query = "SELECT newsletterid, name, subject FROM " . SENDSTUDIO_TABLEPREFIX . "newsletters WHERE active > 0";
		if ($ownerid) {
			$query .= " AND ownerid='" . $this->Db->Quote($ownerid) . "'";
		}

		$query .= " ORDER BY name ASC";
		$result = $this->Db->Query($query);
		if (!$result) {
			list($error, $level) = $this->Db->GetError();
			trigger_error($error, $level);
			return false;
		}
		$newsletters = array();
		while ($row = $this->Db->Fetch($result)) {
			$row['name'] = $row['name'];
			$row['subject'] = $row['subject'];
			$newsletters[] = $row;
		}
		return $newsletters;
	}

	/**
	* GetLastSent
	* Get the date, number of recipients it has already been sent to and the number of recipients it will go to of when the last newsletter was sent.
	*
	* @param Int $newsletterid The newsletterid we are getting the last sent data for.
	*
	* @return Array Return the starttime, total recipients (already sent to) and sendsize (total to send to) and return it as an array. If it hasn't been sent before, this will be an empty array.
	*/
	function GetLastSent($newsletterid=0)
	{
		$return = array('starttime' => 0, 'total_recipients' => 0, 'sendsize' => 0);

		$newsletterid = (int)$newsletterid;
		$query = "SELECT starttime, finishtime, htmlrecipients + textrecipients + multipartrecipients AS total_recipients, sendsize FROM " . SENDSTUDIO_TABLEPREFIX . "stats_newsletters WHERE newsletterid='" . $this->Db->Quote($newsletterid) . "' ORDER BY starttime DESC LIMIT 1";
		$result = $this->Db->Query($query);
		$row = $this->Db->Fetch($result);
		if (empty($row)) {
			return $return;
		}
		$return['starttime'] = $row['starttime'];
		$return['finishtime'] = $row['finishtime'];
		$return['total_recipients'] = $row['total_recipients'];
		$return['sendsize'] = $row['sendsize'];
		return $return;
	}

	/**
	* GetNewsletters
	* Get a list of newsletters based on the criteria passed in.
	*
	* @param Int $ownerid Ownerid of the newsletters to check for.
	* @param Array $sortinfo An array of sorting information - what to sort by and what direction.
	* @param Boolean $countonly Whether to only get a count of lists, rather than the information.
	* @param Int $start Where to start in the list. This is used in conjunction with perpage for paging.
	* @param Int $perpage How many results to return (max).
	*
	* @see ValidSorts
	* @see DefaultOrder
	* @see DefaultDirection
	*
	* @return Mixed Returns false if it couldn't retrieve newsletter information. Otherwise returns the count (if specified), or an array of newsletters.
	*/
	function GetNewsletters($ownerid=0, $sortinfo=array(), $countonly=false, $start=0, $perpage=10)
	{
		$ownerid = (int)$ownerid;
		$start = (int)$start;
		$perpage = (int)$perpage;

		if ($countonly) {
			$query = "SELECT COUNT(newsletterid) AS count FROM " . SENDSTUDIO_TABLEPREFIX . "newsletters";
			if ($ownerid) {
				$query .= " WHERE ownerid='" . $ownerid . "'";
			}

			$result = $this->Db->Query($query);
			return $this->Db->FetchOne($result, 'count');
		}

		$query = "SELECT newsletterid, name, subject, format, createdate, archive, active FROM " . SENDSTUDIO_TABLEPREFIX . "newsletters";
		if ($ownerid) {
			$query .= " WHERE ownerid='" . $ownerid . "'";
		}

		$order = (isset($sortinfo['SortBy']) && !is_null($sortinfo['SortBy'])) ? strtolower($sortinfo['SortBy']) : $this->DefaultOrder;

		$order = (in_array($order, array_keys($this->ValidSorts))) ? $this->ValidSorts[$order] : $this->DefaultOrder;

		$direction = (isset($sortinfo['Direction']) && !is_null($sortinfo['Direction'])) ? $sortinfo['Direction'] : $this->DefaultDirection;

		$direction = (strtolower($direction) == 'up' || strtolower($direction) == 'asc') ? 'ASC' : 'DESC';
		$query .= " ORDER BY " . $order . " " . $direction;

		if ($start || $perpage) {
			$query .= $this->Db->AddLimit($start, $perpage);
		}

		$result = $this->Db->Query($query);
		if (!$result) {
			list($error, $level) = $this->Db->GetError();
			trigger_error($error, $level);
			return false;
		}
		$newsletters = array();
		while ($row = $this->Db->Fetch($result)) {
			$row['name'] = $row['name'];
			$row['subject'] = $row['subject'];
			$newsletters[] = $row;
		}
		return $newsletters;
	}
}

?>
