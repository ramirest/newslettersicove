<?php
/**
* The List API.
*
* @version     $Id: lists.php,v 1.56 2007/06/21 01:48:13 chris Exp $

*
* @package API
* @subpackage Lists_API
*/

/**
* Include the base API class if we haven't already.
*/
require_once(dirname(__FILE__) . '/api.php');

/**
* This will load a list, save a list, set details and get details.
* It will also check access areas.
*
* @package API
* @subpackage Lists_API
*/
class Lists_API extends API
{

	/**
	* The List that is loaded. By default is 0 (no list).
	*
	* @var Int
	*/
	var $listid = 0;

	/**
	* Name of the list that we've loaded.
	*
	* @var String
	*/
	var $name = '';

	/**
	* Email address of the list owner.
	*
	* @var String
	*/
	var $owneremail = '';

	/**
	* Name of the list owner.
	*
	* @var String
	*/
	var $ownername = '';

	/**
	* The lists default bounce email address.
	*
	* @var String
	*/
	var $bounceemail = '';

	/**
	* The lists default reply-to email address.
	*
	* @var String
	*/
	var $replytoemail = '';

	/**
	* The lists format
	*
	* @var String
	*/
	var $format = 'b';

	/**
	* This is the bounce email server (used for processing bounced emails).
	*
	* @var String
	*/
	var $bounceserver = '';

	/**
	* This is the bounce email username (used for processing bounced emails).
	*
	* @var String
	*/
	var $bounceusername = '';

	/**
	* This is the bounce email password (used for processing bounced emails).
	*
	* @var String
	*/
	var $bouncepassword = '';

	/**
	* Whether the list notifies the owner about subscribes/unsubscribes
	*
	* @var Boolean
	*/
	var $notifyowner = false;

	/**
	* Whether the bounce email account is an imap account or not. If this is false, it is a POP3 account.
	*
	* @var Boolean
	*/
	var $imapaccount = false;

	/**
	* Extra email account settings. For example '/notls'.
	*
	* @var String
	*/
	var $extramailsettings = '';

	/**
	* The userid of the owner of this list.
	*
	* @var Int
	*/
	var $ownerid = 0;

	/**
	* The timestamp of when the list was created (integer)
	*
	* @var Int
	*/
	var $createdate = 0;

	/**
	* Default Order to show templates in.
	* @see GetLists
	*
	* @var String
	*/
	var $DefaultOrder = 'name';

	/**
	* Default direction to show lists in.
	*
	* @see GetLists
	*
	* @var String
	*/
	var $DefaultDirection = 'up';

	/**
	* An array of valid sorts that we can use here. This makes sure someone doesn't change the query to try and create an sql error.
	*
	* @see GetLists
	*
	* @var Array
	*/
	var $ValidSorts = array('name' => 'Name', 'date' => 'CreateDate', 'subscribers' => 'subscribecount', 'unsubscribes' => 'unsubscribecount');

	/**
	* The active subscriber count for this particular mailing list. This is used to save doing joins when retrieveing a list of mailing lists and their subscriber counts.
	*
	* @var Int
	*/
	var $subscribecount = 0;

	/**
	* The unsubscribe count for this particular mailing list.
	*
	* @var Int
	*/
	var $unsubscribecount = 0;

	/**
	* Constructor
	* Sets up the database object, loads the list if the ID passed in is not 0.
	*
	* @param Int $listid The listid of the list to load. If it is 0 then you get a base class only. Passing in a listid > 0 will load that list.
	* @param Boolean $connect_to_db Whether to connect to the database or not. If this is set to false, you need to set the database up yourself.
	*
	* @see Load
	* @see GetDb
	*
	* @return Boolean If no listid is passed in, this will return true. If a listid is passed in, this will return the status from Load
	*/
	function Lists_API($listid=0, $connect_to_db=true)
	{
		if ($connect_to_db) {
			$this->GetDb();
		}
		if ($listid >= 0) {
			return $this->Load($listid);
		}
		return true;
	}

	/**
	* Load
	* Loads up the list and sets the appropriate class variables.
	*
	* @param Int $listid The listid to load up. If the listid is not present then it will not load up.
	*
	* @return Boolean Will return false if the listid is not present, or the list can't be found, otherwise it set the class vars and return true.
	*/
	function Load($listid=0)
	{
		$listid = (int)$listid;
		if ($listid <= 0) {
			return false;
		}

		$query = 'SELECT * FROM ' . SENDSTUDIO_TABLEPREFIX . 'lists WHERE listid=\'' . $listid . '\'';
		$result = $this->Db->Query($query);
		if (!$result) {
			return false;
		}

		$list = $this->Db->Fetch($result);
		if (empty($list)) {
			return false;
		}

		$this->listid = $list['listid'];
		$this->name = $list['name'];
		$this->ownername = $list['ownername'];
		$this->owneremail = $list['owneremail'];
		$this->bounceemail = $list['bounceemail'];
		$this->replytoemail = $list['replytoemail'];
		$this->notifyowner = ($list['notifyowner'] == 1) ? true : false;
		$this->imapaccount = ($list['imapaccount'] == 1) ? true : false;
		$this->createdate = $list['createdate'];
		$this->format = $list['format'];
		$this->bounceserver = $list['bounceserver'];
		$this->bounceusername = $list['bounceusername'];
		$this->bouncepassword = base64_decode($list['bouncepassword']);
		$this->ownerid = $list['ownerid'];
		$this->subscribecount = (int)$list['subscribecount'];
		$this->unsubscribecount = (int)$list['unsubscribecount'];
		$this->extramailsettings = $list['extramailsettings'];

		return true;
	}

	/**
	* Create
	* This function creates a list based on the current class vars.
	*
	* @return Boolean Returns true if it worked, false if it fails.
	*/
	function Create()
	{
		$query = "INSERT INTO " . SENDSTUDIO_TABLEPREFIX . "lists (name, owneremail, ownername, bounceemail, replytoemail, format, createdate, notifyowner, imapaccount, bounceserver, bounceusername, bouncepassword, extramailsettings, ownerid, subscribecount, unsubscribecount) VALUES ('" . $this->Db->Quote($this->name) . "', '" . $this->Db->Quote($this->owneremail) . "', '" . $this->Db->Quote($this->ownername) . "', '" . $this->Db->Quote($this->bounceemail) . "', '" . $this->Db->Quote($this->replytoemail) . "', '" . $this->Db->Quote($this->format) . "', '" . $this->Db->Quote($this->GetServerTime()) . "', '" . $this->Db->Quote($this->notifyowner) . "', '" . $this->Db->Quote($this->imapaccount) . "', '" . $this->Db->Quote($this->bounceserver) . "', '" . $this->Db->Quote($this->bounceusername) . "', '" . $this->Db->Quote(base64_encode($this->bouncepassword)) . "', '" . $this->Db->Quote($this->extramailsettings) . "', '" . $this->Db->Quote($this->ownerid) . "', 0, 0)";

		$result = $this->Db->Query($query);

		if ($result) {
			$listid = $this->Db->LastId(SENDSTUDIO_TABLEPREFIX . 'lists_sequence');
			$this->listid = $listid;
			return $listid;
		}
		return false;
	}

	/**
	* Find
	* This function finds a list based on the name passed in. If it's an integer, it will find the list based on that id. If it's a string, it will search for it by name. If it finds more than one, it will return -1.
	*
	* @param Mixed $name The list to find. This could be a string (list name) or an integer.
	*
	* @return Mixed Will return the listid if it's found, false if it can't be found (or it's an invalid type of name), or -1 if there are multiple results.
	*/
	function Find($name=false)
	{
		if (!$name) {
			return false;
		}

		if (is_numeric($name)) {
			$query = "SELECT listid FROM " . SENDSTUDIO_TABLEPREFIX . "lists WHERE listid='" . $this->Db->Quote($name) . "'";
		} else {
			$query = "SELECT listid FROM " . SENDSTUDIO_TABLEPREFIX . "lists WHERE name='" . $this->Db->Quote($name) . "'";
		}

		$result = $this->Db->Query($query);
		if (!$result) {
			list($error, $level) = $this->Db->GetError();
			trigger_error($error, $level);
			return false;
		}
		$num_results = $this->Db->CountResult($result);
		if ($num_results > 1) {
			return -1;
		}
		if ($num_results == 0) {
			return false;
		}
		$listid = $this->Db->Fetch($result);
		return $listid;
	}

	/**
	* Delete
	* Delete a list from the database. First we delete the list (and check the result for that), then we delete the subscribers for the list, the 'custom field data' for the list, the user permissions for the list, and finally reset all class vars.
	*
	* @param Int $listid Listid of the list to delete. If not passed in, it will delete 'this' list.
	* @param Int $userid The userid that is deleting the list. This is used so the stats api can "hide" stats.
	*
	* @see Stats_API::HideStats
	* @see DeleteAllSubscribers
	*
	* @return Boolean True if it deleted the list, false otherwise.
	*
	*/
	function Delete($listid=0, $userid=0)
	{
		$listid = (int)$listid;
		if ($listid == 0) {
			$listid = $this->listid;
		}

		$query = "DELETE FROM " . SENDSTUDIO_TABLEPREFIX . "lists WHERE listid='" . $listid . "'";
		$result = $this->Db->Query($query);
		if (!$result) {
			list($error, $level) = $this->Db->GetError();
			trigger_error($error, $level);
			return false;
		}

		$this->DeleteAllSubscribers($listid);

		$query = "DELETE FROM " . SENDSTUDIO_TABLEPREFIX . "customfield_lists WHERE listid='" . $listid . "'";
		$result = $this->Db->Query($query);

		$query = "DELETE FROM " . SENDSTUDIO_TABLEPREFIX . "form_lists WHERE listid='" . $listid . "'";
		$result = $this->Db->Query($query);

		$query = "DELETE FROM " . SENDSTUDIO_TABLEPREFIX . "user_access WHERE area='lists' AND id='" . $listid . "'";
		$result = $this->Db->Query($query);

		if (!class_exists('stats_api')) {
			require(dirname(__FILE__) . '/stats.php');
		}
		$stats_api = &new Stats_API();

		// clean up stats
		$stats = array('0');
		$query = "SELECT statid FROM " . SENDSTUDIO_TABLEPREFIX . "stats_newsletter_lists WHERE listid='" . $listid . "'";
		$result = $this->Db->Query($query);
		while ($row = $this->Db->Fetch($result)) {
			$stats[] = $row['statid'];
		}

		$stats_api->HideStats($stats, 'newsletter', $userid);

		$stats = array('0');

		$query = "SELECT statid FROM " . SENDSTUDIO_TABLEPREFIX . "stats_autoresponders sa, " . SENDSTUDIO_TABLEPREFIX . "autoresponders a WHERE a.autoresponderid=sa.autoresponderid AND a.listid='" . $listid . "'";
		$result = $this->Db->Query($query);
		while ($row = $this->Db->Fetch($result)) {
			$stats[] = $row['statid'];
		}

		$stats_api->HideStats($stats, 'autoresponder', $userid);

		// autoresponder queues are cleaned up in DeleteAllSubscribers.
		// we just need to clean up the autoresponders.
		$query = "DELETE FROM " . SENDSTUDIO_TABLEPREFIX . "autoresponders WHERE listid='" . $listid . "'";
		$result = $this->Db->Query($query);

		// clean up banned emails.
		$query = "DELETE FROM " . SENDSTUDIO_TABLEPREFIX . "banned_emails WHERE list='" . $listid . "'";
		$result = $this->Db->Query($query);

		if ($listid == $this->listid) {
			$this->listid = 0;
			$this->name = '';
			$this->ownername = '';
			$this->owneremail = '';
			$this->bounceemail = '';
			$this->replytoemail = '';
			$this->bounceserver = '';
			$this->bounceusername = '';
			$this->bouncepassword = '';
			$this->extramailsettings = '';
			$this->subscribecount = 0;
			$this->unsubscribecount = 0;
		}
		return true;
	}

	/**
	* DeleteAllSubscribers
	* Deletes all subscribers from the database.
	* We do not delete custom field info because custom fields can be associated with multiple lists.
	* If that needs doing, deleting the custom field itself will clean up that extra data.
	* This will also clean up any autoresponder queues that are present for this list so nobody can be emailed accidentally.
	*
	* @param Int $listid Listid of the list to delete. If not passed in, it will delete subscribers from 'this' list.
	*
	* @see ClearQueue
	*
	* @return True Always returns true.
	*/
	function DeleteAllSubscribers($listid=0)
	{
		$listid = (int)$listid;
		if ($listid <= 0) {
			$listid = $this->listid;
		}

		// fix stats.
		$query = "UPDATE " . SENDSTUDIO_TABLEPREFIX . "lists SET unsubscribecount=0, subscribecount=0 WHERE listid='" . $listid . "'";
		$result = $this->Db->Query($query);

		$query = "DELETE FROM " . SENDSTUDIO_TABLEPREFIX . "list_subscribers WHERE listid='" . $listid . "'";
		$result = $this->Db->Query($query);

		// delete bounce info.
		$query = "DELETE FROM " . SENDSTUDIO_TABLEPREFIX . "list_subscriber_bounces WHERE listid='" . $listid . "'";
		$result = $this->Db->Query($query);

		// delete unsubscribe info.
		$query = "DELETE FROM " . SENDSTUDIO_TABLEPREFIX . "list_subscribers_unsubscribe WHERE listid='" . $listid . "'";
		$result = $this->Db->Query($query);

		// clean up the autoresponder queues for this mailing list.
		$query = "SELECT queueid FROM " . SENDSTUDIO_TABLEPREFIX . "autoresponders WHERE listid='" . $listid . "'";
		$result = $this->Db->Query($query);
		while ($row = $this->Db->Fetch($result)) {
			$this->ClearQueue($row['queueid'], 'autoresponder');
		}

		return true;
	}

	/**
	* ChangeSubscriberFormat
	* Changes all subscribers for a list to a particular format.
	*
	* @param String $format Format to change subscribers to. This can be 'h', 'html', 't', 'text'.
	* @param Int $listid Listid of the list to change. If not passed in, it will change 'this' list.
	*
	* @return Array Returns an array consisting of the success/failure and a reason why. If it's an invalid format passed in it will return failure. If it's a valid format, it will return success.
	*/
	function ChangeSubscriberFormat($format='html', $listid=0)
	{
		$listid = (int)$listid;

		if ($listid <= 0) {
			$listid = $this->listid;
		}

		$format = strtolower($format);
		if ($format == 'html') {
			$format = 'h';
		}

		if ($format == 'text') {
			$format = 't';
		}

		if ($format != 'h' && $format != 't') {
			return array(false, 'Invalid Format supplied');
		}

		$query = "UPDATE " . SENDSTUDIO_TABLEPREFIX . "list_subscribers SET format='" . $format . "' WHERE listid='" . $listid . "'";
		$this->Db->Query($query);
		return array(true, false);
	}

	/**
	* ChangeSubscriberStatus
	* Changes all subscribers for a list to a particular status.
	*
	* @param String $status Status to change subscribers to. This can be 'a', 'active', 'i', 'inactive'.
	* @param Int $listid Listid of the list to change. If not passed in, it will change 'this' list.
	*
	* @return Array Returns an array consisting of the success/failure and a reason why. If it's an invalid status passed in it will return failure. If it's a valid status, it will return success.
	*/
	function ChangeSubscriberStatus($status='active', $listid=0)
	{
		$listid = (int)$listid;

		if ($listid <= 0) {
			$listid = $this->listid;
		}

		$status = strtolower($status);
		if ($status == 'active') {
			$status = 'a';
		}

		if ($status == 'inactive') {
			$status = 'i';
		}

		if ($status != 'a' && $status != 'i') {
			return array(false, 'Invalid Status supplied');
		}

		if ($status == 'a') {
			$query = "UPDATE " . SENDSTUDIO_TABLEPREFIX . "list_subscribers SET bounced=0, unsubscribed=0 WHERE listid='" . $listid . "'";
		}

		if ($status == 'b') {
			$query = "UPDATE " . SENDSTUDIO_TABLEPREFIX . "list_subscribers SET bounced=" . $this->GetServerTime() . ", unsubscribed=0 WHERE listid='" . $listid . "'";
		}

		if ($status == 'u') {
			$query = "UPDATE " . SENDSTUDIO_TABLEPREFIX . "list_subscribers SET bounced=0, unsubscribed=" . $this->GetServerTime() . " WHERE listid='" . $listid . "'";
		}
		$this->Db->Query($query);
		return array(true, false);
	}

	/**
	* ChangeSubscriberConfirm
	* Changes all subscribers for a list to a particular confirmation status.
	*
	* @param String $status Status to change subscribers to. This can be 'c', 'confirm', 'confirmed', 'u', 'unconfirm', 'unconfirmed'.
	* @param Int $listid Listid of the list to change. If not passed in, it will change 'this' list.
	*
	* @return Array Returns an array consisting of the success/failure and a reason why. If it's an invalid status passed in it will return failure. If it's a valid status, it will return success.
	*/
	function ChangeSubscriberConfirm($status='confirm', $listid=0)
	{
		$listid = (int)$listid;
		if ($listid <= 0) {
			$listid = $this->listid;
		}

		$status = strtolower($status);
		if ($status == 'confirm' || $status == 'confirmed') {
			$status = 'c';
		}

		if ($status == 'unconfirm' || $status == 'unconfirmed') {
			$status = 'u';
		}

		if ($status != 'c' && $status != 'u') {
			return array(false, 'Invalid Status supplied');
		}

		if ($status == 'c') {
			$query = "UPDATE " . SENDSTUDIO_TABLEPREFIX . "list_subscribers SET confirmed=1 WHERE listid='" . $listid . "'";
		}

		if ($status == 'u') {
			$query = "UPDATE " . SENDSTUDIO_TABLEPREFIX . "list_subscribers SET confirmed=0 WHERE listid='" . $listid . "'";
		}
		$this->Db->Query($query);
		return array(true, false);
	}

	/**
	* Copy
	* Copy list details only along with custom field associations.
	*
	* @param Int $int Listid to copy.
	*
	* @see Load
	* @see Create
	* @see Save
	* @see CopyCustomFields
	*
	* @return Array Returns an array of status (whether the copy worked or not) and a message to go with it. If the copy worked, then the message is 'false'.
	*/
	function Copy($oldid=0)
	{
		$oldid = (int)$oldid;
		if ($oldid <= 0) {
			return array(false, 'No ID');
		}

		if (!$this->Load($oldid)) {
			return array(false, 'Unable to load old list.');
		}

		$this->name = GetLang('CopyPrefix') . $this->name;

		$newid = $this->Create();
		if (!$newid) {
			return array(false, 'Unable to create new list');
		}

		$this->CopyCustomFields($oldid, $newid);
		return array(true, $newid);
	}

	/**
	* GetSubscriberCount
	* Gets a subscriber count for the list id passed in. This will check the type and return the number.
	*
	* @param Int $listid Listid to get count for. If not supplied, defaults to this list.
	* @param String $counttype The type of count to get. Defaults to active user count.
	*
	* @return Int The number of subscribers on the list.
	*/
	function GetSubscriberCount($listid=0, $counttype='')
	{
		$listid = (int)$listid;
		if ($listid <= 0) {
			$listid = $this->listid;
		}

		if ($listid <= 0) {
			return 0;
		}

		switch (strtolower($counttype)) {
			default:
				$query = "SELECT COUNT(subscriberid) AS count FROM " . SENDSTUDIO_TABLEPREFIX . "list_subscribers WHERE listid='" . $listid . "' AND bounced = 0 AND unsubscribed = 0";
			break;
		}
		$result = $this->Db->Query($query);
		$count = $this->Db->FetchOne($result, 'count');
		return $count;
	}

	/**
	* Save
	* This function saves the current class vars to the list.
	* If a list isn't loaded, it will return failure.
	*
	* @return Boolean Returns true if it worked, false if it fails.
	*/
	function Save()
	{
		if ($this->listid <= 0) {
			return false;
		}

		$query = "UPDATE " . SENDSTUDIO_TABLEPREFIX . "lists SET name='" . $this->Db->Quote($this->name) . "', ownername='" . $this->Db->Quote($this->ownername) . "', owneremail='" . $this->Db->Quote($this->owneremail) . "', bounceemail='" . $this->Db->Quote($this->bounceemail) . "', replytoemail='" . $this->Db->Quote($this->replytoemail) . "', notifyowner='" . $this->Db->Quote($this->notifyowner) . "', imapaccount='" . $this->Db->Quote($this->imapaccount) . "', format='" . $this->Db->Quote($this->format) . "', bounceserver='" . $this->Db->Quote($this->bounceserver) . "', bounceusername='" . $this->Db->Quote($this->bounceusername) . "', bouncepassword='" . $this->Db->Quote(base64_encode($this->bouncepassword)) . "', extramailsettings='" . $this->Db->Quote($this->extramailsettings) . "' WHERE listid='" . $this->Db->Quote($this->listid) . "'";
		$result = $this->Db->Query($query);
		if (!$result) {
			list($error, $level) = $this->Db->GetError();
			trigger_error($error, $level);
			return false;
		}
		return true;
	}

	/**
	* GetListFormat
	* Returns the list formats that subscribers can join as.
	*
	* @see format
	*
	* @return Char Which format the list accepts for subscribers.
	*/
	function GetListFormat()
	{
		return $this->format;
	}

	/**
	* GetCustomFields
	* Fetches custom fields for the list(s) specified. Returns an array with the fieldid, name, type, default, required and settings.
	*
	* @param Array $listids An array of listids to get custom fields for. If not passed in, it will use 'this' list. If it's not an array, it will be converted to one.
	*
	* @return Array Custom field information for the list provided.
	*/
	function GetCustomFields($listids=array())
	{
		if (!is_array($listids)) {
			$listid = (int)$listids;
			if ($listid <= 0) {
				$listid = $this->listid;
			}
			$listids = array($listid);
		} else {
			if (empty($listids)) {
				$listids = array($this->listid);
			}
		}

		$listids = $this->CheckIntVars($listids);
		if (empty($listids)) {
			$listids = array('0');
		}

		$qry = "SELECT f.fieldid, f.name, f.fieldtype, f.defaultvalue, f.required, f.fieldsettings FROM " . SENDSTUDIO_TABLEPREFIX . "customfields f, " . SENDSTUDIO_TABLEPREFIX . "customfield_lists l WHERE l.fieldid=f.fieldid AND l.listid IN (" . implode(',', $listids) . ")";

		// if a custom field is mapped to multiple lists, we only want the custom field to be returned once.
		if (sizeof($listids) > 1) {
			$qry .= " GROUP BY f.fieldid, f.name, f.fieldtype, f.defaultvalue, f.required, f.fieldsettings";
		}

		$qry .= " ORDER BY f.name ASC, f.fieldid";

		$fieldlist = array();

		$result = $this->Db->Query($qry);
		while ($row = $this->Db->Fetch($result)) {
			$fieldlist[] = $row;
		}
		return $fieldlist;
	}

	/**
	* CopyCustomFields
	* Copies custom fields from one list to the other. This is a 'shortcut' approach to getting each custom field, getting it's associations and updating them.
	*
	* @param Int $fromlistid Which list to copy the custom fields from.
	* @param Int $tolistid Which list to copy the custom fields to. Defaults to 'this' list.
	*
	* @return Boolean Whether the copy worked or not.
	*/
	function CopyCustomFields($fromlistid=0, $tolistid=0)
	{
		if ($fromlistid <= 0) {
			return false;
		}

		if ($tolistid <= 0) {
			$tolistid = $this->listid;
		}

		$fromlistid = (int)$fromlistid;
		$tolistid = (int)$tolistid;

		$qry = "INSERT INTO " . SENDSTUDIO_TABLEPREFIX . "customfield_lists (listid, fieldid) SELECT " . $tolistid . ", fieldid FROM " . SENDSTUDIO_TABLEPREFIX . "customfield_lists l WHERE l.listid='" . $fromlistid . "'";

		$result = $this->Db->Query($qry);
		return $result;
	}

	/**
	* GetLists
	* Get a list of lists based on the criteria passed in.
	*
	* @param Mixed $lists This is used to restrict which lists this will fetch information for. If this is not passed in (it's null), then all lists are checked. If this is not null, it will be an array of listid's to page through. This is so a user is restricted as to which lists they are shown.
	* @param Array $sortinfo An array of sorting information - what to sort by and what direction.
	* @param Boolean $countonly Whether to only get a count of lists, rather than the information.
	* @param Int $start Where to start in the list. This is used in conjunction with perpage for paging.
	* @param Int $perpage How many results to return (max).
	*
	* @see CheckIntVars
	* @see ValidSorts
	* @see DefaultOrder
	* @see DefaultDirection
	*
	* @return Mixed Returns false if it couldn't retrieve list information. Otherwise returns the count (if specified), or an array of lists.
	*/
	function GetLists($lists=null, $sortinfo=array(), $countonly=false, $start=0, $perpage=10)
	{
		$start = (int)$start;
		$perpage = (int)$perpage;

		if (is_array($lists)) {
			$lists = $this->CheckIntVars($lists);
			$lists[] = '0';
		}

		if ($countonly) {
			$query = "SELECT COUNT(listid) AS count FROM " . SENDSTUDIO_TABLEPREFIX . "lists";
			if (is_array($lists)) {
				$query .= " WHERE listid IN (" . implode(',', $lists) . ")";
			}
			$result = $this->Db->Query($query);
			return $this->Db->FetchOne($result, 'count');
		}

		$query = "SELECT listid, name, createdate, subscribecount, unsubscribecount FROM " . SENDSTUDIO_TABLEPREFIX . "lists";

		if (is_array($lists)) {
			$query .= " WHERE listid IN (" . implode(',', $lists) . ")";
		}

		$order = (isset($sortinfo['SortBy']) && !is_null($sortinfo['SortBy'])) ? strtolower($sortinfo['SortBy']) : $this->DefaultOrder;

		$order = (in_array($order, array_keys($this->ValidSorts))) ? $this->ValidSorts[$order] : $this->DefaultOrder;

		$direction = (isset($sortinfo['Direction']) && !is_null($sortinfo['Direction'])) ? $sortinfo['Direction'] : $this->DefaultDirection;

		$direction = (strtolower($direction) == 'up' || strtolower($direction) == 'asc') ? 'ASC' : 'DESC';

		if (strtolower($order) == 'name') {
			$order = 'LOWER(name)';
		}

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
		$return_lists = array();
		while ($row = $this->Db->Fetch($result)) {
			$return_lists[] = $row;
		}
		return $return_lists;
	}

	/**
	* MergeLists
	* Merges a bunch of mailing lists together into a new list. It will copy subscribers, custom field data etc into the new list and then find/remove any duplicate subscribers that have been imported.
	*
	* @param Array $lists_to_merge An array of list id's to merge together. Must be more than one id in the list otherwise we can't merge anything together.
	* @param Array $userinfo An array of user information to use for the new settings. This is done because the API is separate to the 'frontend', it contains the userid, fullname and email address. These are used for setting the "list owner", "list owner email" and so on.
	*
	* @see CheckIntVars
	* @see CopyCustomFields
	*
	* @return Array Returns an array of information. This contains how many lists were successfully merged, how many were not merged, how many duplicates were removed and how many duplicates were not removed.
	*/
	function MergeLists($lists_to_merge = array(), $userinfo=array())
	{
		$results = array('Success' => 0, 'Failure' => 0, 'DuplicatesSuccess' => 0, 'DuplicatesFailure' => 0);

		$lists_to_merge = $this->CheckIntVars($lists_to_merge);

		if (empty($lists_to_merge) || empty($userinfo)) {
			return array(false, 'Empty array of lists to merge', $results);
		}

		if (!isset($userinfo['userid'])) {
			return array(false, 'Empty user information', $results);
		}

		if (sizeof($lists_to_merge) == 1) {
			return array(false, 'Empty array of lists to merge', $results);
		}

		$format = 'b';
		$newname = GetLang('MergePrefix');
		foreach ($lists_to_merge as $p => $listid) {
			if (!$this->Load($listid)) {
				$results['Failure']++;
				continue;
			}

			$results['Success']++;

			$newname .= '\'' . $this->name . '\', ';
		}
		$newname = substr($newname, 0, -2);

		$this->name = $newname;
		$this->owneremail = $userinfo['emailaddress'];
		$this->ownername  = $userinfo['name'];
		$this->bounceemail = $userinfo['emailaddress'];
		$this->replytoemail = $userinfo['emailaddress'];
		$this->format = $format;
		$this->notifyowner = true;
		$this->imapaccount = false;
		$this->bounceserver = '';
		$this->bounceusername = '';
		$this->bouncepassword = '';
		$this->extramailsettings = '';
		$this->ownerid = $userinfo['userid'];

		$newid = $this->Create();
		$newid = (int)$newid;
		if (!$newid || $newid <= 0) {
			return array(false, true, $results);
		}

		foreach ($lists_to_merge as $p => $listid) {
			$customfield_status = $this->CopyCustomFields($listid, $newid);
		}

		// clean up any duplicate custom field associations.
		$query = "SELECT fieldid, COUNT(fieldid) AS foundcount FROM " . SENDSTUDIO_TABLEPREFIX . "customfield_lists WHERE listid=" . $newid . " GROUP BY fieldid HAVING COUNT(fieldid) > 1";
		$result = $this->Db->Query($query);

		while ($row = $this->Db->Fetch($result)) {
			// delete all but one instance of the field association (hence 'foundcount - 1').
			$query = "SELECT cflid FROM " . SENDSTUDIO_TABLEPREFIX . "customfield_lists WHERE listid=" . $newid . " AND fieldid='" . $row['fieldid'] . "' LIMIT " . ($row['foundcount'] - 1);
			$check_result = $this->Db->Query($query);
			while ($check_row = $this->Db->Fetch($check_result)) {
				$deletelist[] = $check_row['cflid'];
			}
		}

		if (!empty($deletelist)) {
			$deletequery = "DELETE FROM " . SENDSTUDIO_TABLEPREFIX . "customfield_lists WHERE cflid IN (" . implode(',', $deletelist) . ")";
			$this->Db->Query($deletequery);
		}

		$timenow = $this->GetServerTime();

		// now we have to copy subscribers and their data across.
		// first basic subscriber info.
		$query = "INSERT INTO " . SENDSTUDIO_TABLEPREFIX . "list_subscribers(listid, emailaddress, format, confirmed, confirmcode, subscribedate, bounced, unsubscribed) SELECT " . $newid . ", emailaddress, format, confirmed, confirmcode, " . $timenow . ", 0, 0 FROM " . SENDSTUDIO_TABLEPREFIX . "list_subscribers WHERE listid IN (" . implode(',', $lists_to_merge) . ")";

		$result = $this->Db->Query($query);

		// now we copy the custom field data.
		// since the subscribers_data table doesn't have a listid, we match the email addresses up from the old lists and new list(s).
		$customfield_copy_query = "INSERT INTO " . SENDSTUDIO_TABLEPREFIX . "subscribers_data(subscriberid, fieldid, data) select s2.subscriberid, sd.fieldid, sd.data FROM " . SENDSTUDIO_TABLEPREFIX . "list_subscribers s1, " . SENDSTUDIO_TABLEPREFIX . "subscribers_data sd, " . SENDSTUDIO_TABLEPREFIX . "list_subscribers s2 where s1.subscriberid=sd.subscriberid and s1.emailaddress=s2.emailaddress and s1.listid in (" . implode(',', $lists_to_merge) . ") and s2.listid=" . $newid;
		$customfield_copy_result = $this->Db->Query($customfield_copy_query);

		// now we check for duplicate subscribers.
		$duplicate_emails = array();

		$query = "SELECT emailaddress FROM " . SENDSTUDIO_TABLEPREFIX . "list_subscribers WHERE listid=" . $newid . " GROUP BY emailaddress HAVING COUNT(emailaddress) > 1";

		$result = $this->Db->Query($query);
		while ($row = $this->Db->Fetch($result)) {
			$duplicate_emails[] = $row['emailaddress'];
		}

		$duplicate_subscriber_count_before = sizeof($duplicate_emails);

		if (empty($duplicate_ids)) {
			$duplicate_emails[] = '0';
		}

		if ($duplicate_subscriber_count_before > 0) {
			// in case there are a ton of duplicate subscribers, only do a small number at a time
			// otherwise queries are too long and won't work.

			$remove_size = 50;
			$start_pos = 0;

			while ($start_pos < $duplicate_subscriber_count_before) {
				$remove_subscribers = array_slice($duplicate_emails, $start_pos, $remove_size);

				// remove duplicates.
				$query = "DELETE FROM " . SENDSTUDIO_TABLEPREFIX . "list_subscribers WHERE listid=" . $newid . " AND emailaddress IN ('" . implode('\',\'', $remove_subscribers) . "')";
				$result = $this->Db->Query($query);

				// re-add the duplicate subscribers.
				$query = "INSERT INTO " . SENDSTUDIO_TABLEPREFIX . "list_subscribers(listid, emailaddress, format, confirmed, confirmcode, subscribedate, bounced, unsubscribed) SELECT " . $newid . ", emailaddress, format, confirmed, confirmcode, " . $timenow . ", 0, 0 FROM " . SENDSTUDIO_TABLEPREFIX . "list_subscribers WHERE listid='" . $listid . "' AND emailaddress IN ('" . implode('\',\'', $remove_subscribers) . "')";
				$result = $this->Db->Query($query);

				$start_pos += $remove_size;
			}

			// now we double check to see whether any duplicates are left (for reporting).
			$query = "SELECT COUNT(emailaddress) AS count FROM " . SENDSTUDIO_TABLEPREFIX . "list_subscribers WHERE listid=" . $newid . " GROUP BY emailaddress HAVING COUNT(emailaddress) > 1";
			$result = $this->Db->Query($query);
			$duplicate_subscriber_count_after = (int)$this->Db->FetchOne($result, 'count');
		} else {
			$duplicate_subscriber_count_after = 0;
		}

		if ($duplicate_subscriber_count_before > 0 && $duplicate_subscriber_count_after == 0) {
			$results['DuplicatesSuccess'] = $duplicate_subscriber_count_before;
		}

		if ($duplicate_subscriber_count_before > 0 && $duplicate_subscriber_count_after > 0) {
			$results['DuplicatesSuccess'] = $duplicate_subscriber_count_before - $duplicate_subscriber_count_after;
			$results['DuplicatesFailure'] = $duplicate_subscriber_count_after;
		}

		// now we have to check for unsubscribes on any of the merged lists and unsubscribe them from the new list.
		$query = "SELECT emailaddress, MAX(unsubscribed) AS unsubscribe_date FROM " . SENDSTUDIO_TABLEPREFIX . "list_subscribers WHERE listid IN (" . implode(',', $lists_to_merge) . ") AND unsubscribed > 0 GROUP BY emailaddress";
		$result = $this->Db->Query($query);
		while ($row = $this->Db->Fetch($result)) {
			$unsubscribe_date = (int)$row['unsubscribe_date'];

			$new_subscriberid_query = "SELECT subscriberid FROM " . SENDSTUDIO_TABLEPREFIX . "list_subscribers WHERE emailaddress='" . $this->Db->Quote($row['emailaddress']) . "' AND listid=" . $newid;

			$new_subscriber_result = $this->Db->Query($new_subscriberid_query);
			$new_subscriber = (int)$this->Db->FetchOne($new_subscriber_result, 'subscriberid');

			if (!$new_subscriber || $new_subscriber <= 0) {
				continue;
			}

			$update_query = "UPDATE " . SENDSTUDIO_TABLEPREFIX . "list_subscribers SET unsubscribed=" . $unsubscribe_date . " WHERE subscriberid=" . $new_subscriber . " AND listid=" . $newid;
			$update_result = $this->Db->Query($update_query);

			$insert_query = "INSERT INTO " . SENDSTUDIO_TABLEPREFIX . "list_subscribers_unsubscribe
			(
				subscriberid,
				unsubscribetime,
				unsubscribeip,
				unsubscriberequesttime,
				unsubscriberequestip,
				listid,
				statid,
				unsubscribearea
			)
			SELECT
				" . $new_subscriber . ",
				" . $unsubscribe_date . ",
				unsubscribeip,
				0,
				null,
				" . $newid . ",
				0,
				null
				FROM " . SENDSTUDIO_TABLEPREFIX . "list_subscribers_unsubscribe u,
				" . SENDSTUDIO_TABLEPREFIX . "list_subscribers s
				WHERE s.subscriberid=u.subscriberid AND
				s.emailaddress='" . $this->Db->Quote($row['emailaddress']) . "' AND u.listid IN (" . implode(',', $lists_to_merge) . ") AND u.unsubscribetime='" . $unsubscribe_date . "'";

			$insert_result = $this->Db->Query($insert_query);
		}

		// fix up the counts of subscribe/unsubscribed people.
		$query = "SELECT COUNT(subscriberid) AS subscribecount FROM " . SENDSTUDIO_TABLEPREFIX . "list_subscribers WHERE listid='" . (int)$newid . "' AND unsubscribed=0";
		$sub_count = $this->Db->FetchOne($query, 'subscribecount');

		$query = "SELECT COUNT(subscriberid) AS unsubscribecount FROM " . SENDSTUDIO_TABLEPREFIX . "list_subscribers WHERE listid='" . (int)$newid . "' AND unsubscribed > 0";
		$unsub_count = $this->Db->FetchOne($query, 'unsubscribecount');

		$query = "SELECT COUNT(subscriberid) AS bouncecount FROM " . SENDSTUDIO_TABLEPREFIX . "list_subscribers WHERE listid='" . (int)$newid . "' AND bounced > 0";
		$bounce_count = $this->Db->FetchOne($query, 'bouncecount');

		$query = "UPDATE " . SENDSTUDIO_TABLEPREFIX . "lists SET subscribecount='" . (int)$sub_count . "', unsubscribecount='" . (int)$unsub_count . "', bouncecount='" . (int)$bounce_count . "' WHERE listid='" . (int)$newid . "'";
		$this->Db->Query($query);

		return array($newid, false, $results);
	}

	/**
	* GetArchives
	* Gets archives from the database for a particular list. This is used when generating the RSS feed so it will fetch the last $num_to_retrieve sends to that particular list.
	*
	* @param Int $listid List to get archives for
	* @param Int $num_to_retrieve Number of sends to retrieve from the database
	*
	* @return Array Returns an array of entries that were last sent to that particular list.
	*/
	function GetArchives($listid=0, $num_to_retrieve=0)
	{
		$listid = (int)$listid;
		$num_to_retrieve = (int)$num_to_retrieve;

		$query = "SELECT n.newsletterid, n.name, n.subject, MIN(sn.starttime) AS starttime, nl.listid, u.username, u.fullname, n.textbody, n.htmlbody FROM " . SENDSTUDIO_TABLEPREFIX . "newsletters n, " . SENDSTUDIO_TABLEPREFIX . "stats_newsletters sn, " . SENDSTUDIO_TABLEPREFIX . "stats_newsletter_lists nl, " . SENDSTUDIO_TABLEPREFIX . "users u WHERE u.userid=sn.sentby AND sn.statid=nl.statid AND sn.newsletterid=n.newsletterid";

		// we don't just check for 0/1 here because we store the userid who performed the action (eg made it active).
		$query .= " AND n.active > 0 AND n.archive > 0";

		if ($listid > 0) {
			$query .= " AND nl.listid='" . (int)$listid . "'";
		}

		// Group the entries so we can get the start time of the first entry
		// rather then getting an entry for each time the send was started
		$query .= " GROUP BY n.newsletterid, nl.listid, n.name, n.subject, u.username, u.fullname, n.textbody, n.htmlbody";

		// order by most recently sent first.
		$query .= " ORDER BY starttime DESC";

		if ($num_to_retrieve > 0) {
			$query .= " LIMIT " . $num_to_retrieve;
		}

		$result = $this->Db->Query($query);

		$archive_list = array();
		while ($row = $this->Db->Fetch($result)) {
			$archive_list[] = $row;
		}

		return $archive_list;
	}
}

?>
