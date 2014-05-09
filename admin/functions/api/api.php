<?php
/**
* This has the Base SendStudio API class in it.
* Includes the init file (to set up database and so on) if it needs to.
*
* @version     $Id: api.php,v 1.50 2007/05/08 02:36:02 chris Exp $

*
* @package API
*/

/**
* Check whether this is a valid request. It will include the function directory's init file.
*/
if (!defined('SENDSTUDIO_API_DIRECTORY')) {
	require(dirname(__FILE__) . '/../init.php');
}

/**
* This has the Base API class in it.
* Sets up the database object for use.
* Has base functions available to all subclasses, like Get, Set
* A lot of the queue functions are in here also.
*
* @package API
*/
class API
{

	/**
	* Database object is stored here. Is null by default, the constructor sets it up.
	*
	* @see API
	*
	* @var Object
	*/
	var $Db = null;

	/**
	* ConvertDate object is stored here. Is null by default, the constructor sets it up.
	*
	* @see API
	*
	* @var Object
	*/
	var $ConvertDate = null;

	/**
	* List of all formats.
	* The option is the language pack variable to display.
	*
	* @see GetAllFormats
	*
	* @var Array
	*/
	var $AllFormats = array('b' => 'TextAndHTML', 'h' => 'HTML', 't' => 'Text');

	/**
	* TextBody - this is used with templates, autoresponders, newsletters. Set it to nothing in the base class, the other API's set it.
	*
	* @see GetBody
	* @see SetBody
	*
	* @var String
	*/
	var $textbody = '';

	/**
	* HTMLBody - this is used with templates, autoresponders, newsletters. Set it to nothing in the base class, the other API's set it.
	*
	* @see GetBody
	* @see SetBody
	*
	* @var String
	*/
	var $htmlbody = '';

	/**
	* API
	* Sets up the database object for this and the child objects to use.
	*
	* @see GetDb
	*/
	function API()
	{
		$this->GetDb();
	}

	/**
	* GetDb
	* Sets up the database object for this and the child objects to use.
	* If the Db var is already set up and the connection is a valid resource, this will return true straight away.
	* If the Db var is null or the connection is not valid, it will fetch it and store it for easy reference.
	* If it's unable to setup the database (or it's null or false) it will trigger an error.
	*
	* @see Db
	* @see GetDatabase
	*
	* @return Boolean True if it works or false if it fails. Failing also triggers a fatal error.
	*/
	function GetDb()
	{
		if (is_object($this->Db) && is_resource($this->Db->connection)) {
			return true;
		}

		if (is_null($this->Db) || !$this->Db->connection) {
			$Db = &GetDatabase();
			$this->Db = &$Db;
		}

		if (!is_object($this->Db) || !is_resource($this->Db->connection)) {
			trigger_error('Unable to connect to database', SENDSTUDIO_ERROR_FATAL);
			return false;
		}
		return true;
	}

	/**
	* Set
	* This sets the class var to the value passed in.
	* If the variable doesn't exist in the object, this will return false.
	*
	* @param String $varname Name of the class var to set.
	* @param Mixed $value The value to set the class var (this can be an array, string, int, float, object).
	*
	* @return Boolean True if it works, false if the var isn't present.
	*/
	function Set($varname='', $value='')
	{
		if ($varname == '') {
			return false;
		}

		// make sure we're setting a valid variable.
		$my_vars = array_keys(get_object_vars($this));
		if (!in_array($varname, $my_vars)) {
			return false;
		}

		$this->$varname = $value;
		return true;
	}

	/**
	* Get
	* Returns the class variable based on the variable passed in.
	* If the object variable doesn't exist, this will return false.
	*
	* @param String $varname Name of the class variable to return.
	*
	* @return False|Mixed Returns false if the class variable doesn't exist, otherwise it will return the value in the variable.
	*/
	function Get($varname='')
	{
		if ($varname == '') {
			return false;
		}

		if (!isset($this->$varname)) {
			return false;
		}

		return $this->$varname;
	}

	/**
	* GetAllFormats
	* Returns a list of all formats for use with newsletters, templates, autoresponders and lists.
	*
	* @see AllFormats
	*
	* @return Array List of all formats that we can use.
	*/
	function GetAllFormats()
	{
		return $this->AllFormats;
	}

	/**
	* GetFormat
	* Returns a format name based on the format letter you pass in.
	*
	* <b>Example</b>
	* <code>
	* $format = 'h';
	* </code>
	* will return 'HTML'
	*
	* @param String $format Format to find and return the name of.
	*
	* @see AllFormats
	*
	* @return False|String False if the format doesn't exist, otherwise returns a string of the format name.
	*/
	function GetFormat($format='h')
	{
		$format = strtolower($format{0}); // only get the first character in case the whole name is passed in.
		if (!in_array($format, array_keys($this->AllFormats))) {
			return false;
		}

		return $this->AllFormats[$format];
	}


	/**
	* SetBody
	* SetBody sets class variables for easy access. Newsletters, templates and autoresponders all use this. If you pass in something other than text and html, this returns false.
	*
	* @param String $bodytype The type you're setting. This is either text or html.
	* @param String $content The content to set the bodytype to.
	*
	* @return Boolean Returns whether it worked or not. Passing an invalid bodytype will return false. Passing in a correct bodytype will return true.
	*/
	function SetBody($bodytype='text', $content='')
	{
		switch (strtolower($bodytype)) {
			case 'text':
				$this->textbody = $content;
				return true;
			break;
			case 'html':
				$this->htmlbody = $content;
				return true;
			break;
			default:
				return false;
		}
	}

	/**
	* GetBody
	* GetBody returns the class variable based on which bodytype you're after. If you pass in something other than text and html, this returns false.
	*
	* @param String $bodytype The type you're getting. This is either text or html.
	*
	* @return False|String If the right sort of bodytype is passed in, it will return the content. If an invalid type is passed in, this will return false.
	*/
	function GetBody($bodytype='text')
	{
		switch (strtolower($bodytype)) {
			case 'text':
				return $this->textbody;
			break;
			case 'html':
				return $this->htmlbody;
			break;
			default:
				return false;
		}
	}

	/**
	* CreateQueue
	* Creates a queue based on the queuetype and the recipients you pass in.
	* This is used by the send process, export process and autoresponder process to create a queue before anything else happens.
	*
	* @param String $queuetype The type of queue to create.
	* @param Array $recipients A list of recipients to put in the queue as an array. If it's not an array, it gets converted to one. If this is not empty, once the queue has been created all recipients in this array are added to the queue as 'unprocessed'.
	*
	* @see GetUser
	* @see User_API::UserID
	* @see Autoresponders_API::Create
	* @see Jobs_Send_API::ProcessJob
	* @see Subscribers_API::GetSubscribers
	* @see Send::Process
	* @see Subscribers_Export::ExportSubscribers_Step3
	*
	* @return False|Int Returns false if it can't create a queue, or if in the process of adding subscribers to the database something goes wrong. If everything succeeds, this returns the new queueid.
	*/
	function CreateQueue($queuetype='send', $recipients=array())
	{
		$queuetype = strtolower($queuetype);
		if (!is_array($recipients)) {
			$recipients = array($recipients);
		}

		$thisuser = &GetUser();
		$ownerid = $thisuser->userid;

		$queue_sequence_ok = $this->Db->CheckSequence(SENDSTUDIO_TABLEPREFIX . 'queues_sequence');
		if (!$queue_sequence_ok) {
			$qry = "SELECT id FROM " . SENDSTUDIO_TABLEPREFIX . "queues_sequence ORDER BY id DESC LIMIT 1";
			$id = $this->Db->FetchOne($qry, 'id');
			$new_id = $id + 1;
			$reset_ok = $this->Db->ResetSequence(SENDSTUDIO_TABLEPREFIX . 'queues_sequence', $new_id);
			if (!$reset_ok) {
				return false;
			}
		}

		$queueid = $this->Db->NextId(SENDSTUDIO_TABLEPREFIX . 'queues_sequence');
		if (!$queueid) {
			return false;
		}

		if (!empty($recipients)) {
			$this->Db->Query("BEGIN");
			foreach ($recipients as $pos => $subscriberid) {
				$query = "INSERT INTO " . SENDSTUDIO_TABLEPREFIX . "queues (queueid, queuetype, ownerid, recipient, processed) VALUES (" . $queueid . ", '" . $this->Db->Quote($queuetype) . "', " . (int)$ownerid . ", " . (int)$subscriberid['subscriberid'] . ", 0)";
				$result = $this->Db->Query($query);
				if (!$result) {
					return false;
				}
			}
			$this->Db->Query("COMMIT");
		}
		return $queueid;
	}

	/**
	* AddToQueue
	* Adds to an existing queue based on the recipient list you pass in.
	*
	* @param Int $queueid The queueid you're accessing.
	* @param String $queuetype The queuetype you're adding to.
	* @param Array $recipients A list of recipients to put in the queue as an array. If it's not an array, it gets converted to one. If it's an empty list, nothing happens.
	*
	* @see GetUser
	* @see User_API::UserID
	* @see RemoveDuplicatesInQueue
	*
	* @return Boolean Returns false if it can't add to the queue, otherwise true.
	*/
	function AddToQueue($queueid=0, $queuetype='send', $recipients=array())
	{
		$queuetype = strtolower($queuetype);
		if (!is_array($recipients)) {
			$recipients = array($recipients);
		}

		$thisuser = &GetUser();
		$ownerid = $thisuser->userid;

		if (!empty($recipients)) {
			$this->Db->Query("BEGIN");
			foreach ($recipients as $pos => $subscriberid) {
				$query = "INSERT INTO " . SENDSTUDIO_TABLEPREFIX . "queues (queueid, queuetype, ownerid, recipient, processed) VALUES (" . $queueid . ", '" . $this->Db->Quote($queuetype) . "', " . (int)$ownerid . ", " . (int)$subscriberid['subscriberid'] . ", 0)";
				$result = $this->Db->Query($query);
				if (!$result) {
					return false;
				}
			}
			$this->Db->Query("COMMIT");
		}
		return true;
	}

	/**
	* ImportToQueue
	* Import recipients to a queue based on the criteria passed in. This will create the queue if it doesn't already exist, and imports the subscriber id's straight through sql depending on the listid passed in. Once they have all been inserted, duplicates are checked and removed.
	*
	* @param Int $queueid The queue you're adding recipients to.
	* @param String $queuetype The queuetype you're adding them to.
	* @param Int $lists The list you're importing from.
	*
	* @see GetUser
	* @see User_API::UserID
	* @see IsQueue
	* @see RemoveDuplicatesInQueue
	*
	* @return False|RemoveDuplicatesInQueue Returns false if there's no list to import them from. Otherwise imports them, and returns the status from RemoveDuplicatesInQueue.
	*/
	function ImportToQueue($queueid=0, $queuetype='', $listid=0)
	{
		if ($listid <= 0) {
			return false;
		}

		$thisuser = &GetUser();
		$ownerid = $thisuser->userid;

		$query = "INSERT INTO " . SENDSTUDIO_TABLEPREFIX . "queues(queueid, queuetype, ownerid, recipient, processed) SELECT " . (int)$queueid . ", '" . $this->Db->Quote($queuetype) . "', '" . (int)$ownerid . "', l.subscriberid, 0 FROM " . SENDSTUDIO_TABLEPREFIX . "list_subscribers l WHERE l.listid='" . (int)$listid . "'";
		$this->Db->Query($query);

		$this->Db->OptimizeTable(SENDSTUDIO_TABLEPREFIX . "queues");

		return $this->RemoveDuplicatesInQueue($queueid, $queuetype);
	}


	/**
	* RemoveDuplicatesInQueue
	* Removes duplicate recipients from a queue based on the id and queuetype you pass in.
	*
	* If the database is a mysql database, it works out the duplicate email addresses, then using a "special" mysql syntax, we can work out a single subscriberid for duplicate email addresses.
	* It goes through an "endless loop" scenario to continually reduce the number of recipients per email address to a 1:1 ratio and finally return out of the function.
	*
	* If the database is a postgres database, we handle things a little differently.
	* Postgres doesn't allow the same syntax as mysql, so we have to do a subquery:
	* - Get singular subscriberid's based on distinct email addresses (using postgres specific SELECT DISTINCT ON syntax)
	* Then we pass all that to a DELETE command which takes the subquery and does a NOT IN delete
	* That is, instead of finding the duplicates and then having to delete just them, we do it the other way. We find uniques and delete any that aren't unique.
	* It ends up being the same thing but in a reverse logic order.
	* See inline comments for further info.
	*
	* @param Int $queueid The queueid you're removing recipients from.
	* @param String $queuetype The queuetype you're removing from.
	*
	* @see ImportToQueue
	* @see RemoveFromQueue
	* @see AddToQueue
	*
	* @return Boolean If there are no recipients, this will return true. Otherwise, it returns the status from AddToQueue
	*/
	function RemoveDuplicatesInQueue($queueid=0, $queuetype='send')
	{

		/**
		* Postgresql doesn't let us select two fields but group by one, but it does have a DISTINCT ON feature we can use to achieve the same effect - it is postgresql specific.
		* We can do the selections & deletions all in one go.
		*
		* It's not very pretty but this works by:
		* Get singular subscriberid's based on distinct email addresses ($select_distinct_query)
		* Deleting anything *not* in that list of unique subscriberids ($delete_query which includes $select_distinct_query)
		*
		* See http://www.postgresql.org/docs/current/static/queries-select-lists.html
		* See http://www.postgresql.org/docs/current/static/sql-select.html
		*/
		if (SENDSTUDIO_DATABASE_TYPE == 'pgsql') {
			$select_distinct_query = "SELECT DISTINCT ON (emailaddress) l.subscriberid AS subscriberid FROM " . SENDSTUDIO_TABLEPREFIX . "list_subscribers l, " . SENDSTUDIO_TABLEPREFIX . "queues q WHERE q.recipient=l.subscriberid AND queueid='" . $this->Db->Quote($queueid) . "' AND queuetype='" . strtolower($queuetype) . "'";

			$delete_query = "DELETE FROM " . SENDSTUDIO_TABLEPREFIX . "queues WHERE queueid='" . $this->Db->Quote($queueid) . "' AND queuetype='" . strtolower($queuetype) . "' AND recipient NOT IN (" . $select_distinct_query . ")";

			$result = $this->Db->Query($delete_query);
			return true;
		}

		/**
		* Since mysql 4.0 doesn't allow subselects, we have to approach this differently.
		* We create an endless loop that looks for subscriberid's based on email addresses that are duplicated.
		* We can't get a full list of all id's that are duplicated, so instead we narrow each email address down one by one.
		* For example:
		* email@domain.com is on list 1, 2 & 3.
		* email2@domain.com is on list 2 & 3.
		* email3@domain.com is only on list 1.
		* and we send to lists 1, 2, & 3.
		* The first part of the query will find email2@domain.com and email@domain.com (since they are both duplicated on the send).
		* Then it will call RemoveFromQueue to remove one instance of each subscriber.
		* The next loop will only find email@domain.com (since email2@domain.com only has one instance in the queue now)
		* Then it will call RemoveFromQueue to remove that second instance of the duplicate.
		* Finally, another loop will happen this time fetching nothing (1 email address per recipient) - and that will return out of the function.
		*/
		while (true) {
			$query = "SELECT l.subscriberid AS subscriberid FROM " . SENDSTUDIO_TABLEPREFIX . "list_subscribers l, " . SENDSTUDIO_TABLEPREFIX . "queues q WHERE q.recipient=l.subscriberid AND queueid='" . $this->Db->Quote($queueid) . "' AND queuetype='" . strtolower($queuetype) . "' GROUP BY emailaddress HAVING COUNT(emailaddress) > 1";

			$result = $this->Db->Query($query);

			$recipients_to_remove = array();
			while ($row = $this->Db->Fetch($result)) {
				$recipients_to_remove[] = $row['subscriberid'];
			}

			if (empty($recipients_to_remove)) {
				return true;
			}

			// remove traces of the duplicates.
			$this->RemoveFromQueue($queueid, $queuetype, $recipients_to_remove);
		}
	}

	/**
	* RemoveBannedEmails
	* Checks a queue for banned email addresses and domain names. It checks the lists you pass in (listids) and the global list. The database queries are slightly different depending on which database type you are using.
	*
	* @param Array $lists A list of listid's to check for banned subscribers. This doesn't include the global list.
	* @param Int $queueid The queueid we're working with.
	* @param String $queuetype The Queuetype we're working with. Most likely to be the 'send' queue.
	*
	* @see SENDSTUDIO_DATABASE_TYPE
	*
	* @return Boolean Returns true if it worked, returns false if there was a problem with the query.
	*/
	function RemoveBannedEmails($lists = array(), $queueid=0, $queuetype='send')
	{
		if (!is_array($lists)) {
			$lists = array($lists);
		}

		$lists = $this->CheckIntVars($lists);

		if (empty($lists)) {
			$lists = array('0');
		}

		// need quotes around this because it's a character.
		$lists[] = 'g';

		if (SENDSTUDIO_DATABASE_TYPE == 'mysql') {
			$query = "SELECT l.subscriberid AS subscriberid FROM " . SENDSTUDIO_TABLEPREFIX . "list_subscribers l, " . SENDSTUDIO_TABLEPREFIX . "banned_emails b WHERE b.list IN ('" . implode('\',\'', $lists) . "') AND l.emailaddress LIKE " . $this->Db->Concat("'%'", 'b.emailaddress', "'%'");

			$result = $this->Db->Query($query);
			$subscribers = array();

			while ($row = $this->Db->Fetch($result)) {
				$subscribers[] = $row['subscriberid'];
			}

			if (empty($subscribers)) {
				return true;
			}

			$query = "DELETE FROM " . SENDSTUDIO_TABLEPREFIX . "queues WHERE queueid='" . (int)$queueid . "' AND queuetype='" . $this->Db->Quote($queuetype) . "' AND recipient IN (" . implode(',', $subscribers) . ")";
		}

		if (SENDSTUDIO_DATABASE_TYPE == 'pgsql') {
			$query = "DELETE FROM " . SENDSTUDIO_TABLEPREFIX . "queues WHERE recipient IN (SELECT subscriberid FROM " . SENDSTUDIO_TABLEPREFIX . "list_subscribers l, " . SENDSTUDIO_TABLEPREFIX . "banned_emails b WHERE l.emailaddress like " . $this->Db->Concat("'%'", 'b.emailaddress', "'%'") . " AND b.list IN ('" . implode('\',\'', $lists) . "')) AND queueid='" . (int)$queueid . "' AND queuetype='" . $this->Db->Quote($queuetype) . "'";
		}
		$result = $this->Db->Query($query);
		if ($result) {
			return true;
		}

		return false;
	}

	/**
	* RemoveUnsubscribedEmails
	* Checks a queue for unsubscribed email addresses. It checks the lists you pass in (listids), the queue and the queue type. The database queries are slightly different depending on which database type you are using.
	*
	* @param Array $lists A list of listid's to check for unsubscribed subscribers.
	* @param Int $queueid The queueid to check.
	* @param String $queuetype The Queuetype to check.
	*
	* @see SENDSTUDIO_DATABASE_TYPE
	*
	* @return Boolean Returns true if it worked, returns false if there was a problem with the query.
	*/
	function RemoveUnsubscribedEmails($lists = array(), $queueid=0, $queuetype='send')
	{
		if (!is_array($lists)) {
			$lists = array($lists);
		}

		$lists = $this->CheckIntVars($lists);

		if (empty($lists)) {
			$lists = array('0');
		}

		if (SENDSTUDIO_DATABASE_TYPE == 'mysql') {

			$query = "SELECT l.subscriberid AS subscriberid FROM " . SENDSTUDIO_TABLEPREFIX . "list_subscribers l WHERE l.unsubscribed > 0 AND l.listid IN (" . implode(',', $lists) . ")";

			$result = $this->Db->Query($query);
			while ($row = $this->Db->Fetch($result)) {
				$subscribers[] = $row['subscriberid'];
			}

			if (empty($subscribers)) {
				return true;
			}

			$query = "DELETE FROM " . SENDSTUDIO_TABLEPREFIX . "queues WHERE queueid='" . (int)$queueid . "' AND queuetype='" . $this->Db->Quote($queuetype) . "' AND recipient IN (" . implode(',', $subscribers) . ")";

		}

		if (SENDSTUDIO_DATABASE_TYPE == 'pgsql') {
			$query = "DELETE FROM " . SENDSTUDIO_TABLEPREFIX . "queues WHERE recipient IN (SELECT l.subscriberid FROM " . SENDSTUDIO_TABLEPREFIX . "list_subscribers l, " . SENDSTUDIO_TABLEPREFIX . "queues q WHERE l.unsubscribed > 0 AND l.listid IN (" . implode(',', $lists) . ") AND q.recipient=l.subscriberid) AND queueid='" . (int)$queueid . "' AND queuetype='" . $this->Db->Quote($queuetype) . "'";
		}
		$result = $this->Db->Query($query);
		if ($result) {
			return true;
		}

		return false;
	}


	/**
	* RemoveFromQueue
	* Removes recipients from a queue based on the id, queuetype and recipients list you pass in.
	*
	* @param Int $queueid The queueid you're removing recipients from.
	* @param String $queuetype The queuetype you're deleting from.
	* @param Mixed $recipients A list of recipients to remove from the queue. This can be an array or a singular recipient id.
	*
	* @return Boolean Returns true if the query worked, returns false if there was a problem with the query.
	*/
	function RemoveFromQueue($queueid=0, $queuetype='export', $recipients=array())
	{
		if (!is_array($recipients)) {
			$recipients = array($recipients);
		}

		$recipients = $this->CheckIntVars($recipients);

		if (empty($recipients)) {
			return true;
		}

		$query = "DELETE FROM " . SENDSTUDIO_TABLEPREFIX . "queues WHERE queueid='" . $this->Db->Quote($queueid) . "' AND queuetype='" . strtolower($queuetype) . "' AND recipient IN (" . implode(',', $recipients) . ")";
		$result = $this->Db->Query($query);
		if ($result) {
			return true;
		}

		return false;
	}

	/**
	* MarkAsProcessed
	* Marks recipients as processed in the queue. An update is usually 'cheaper' in database terms to do than a delete so that's what this does.
	*
	* @param Int $queueid The queueid you're processing recipients for.
	* @param String $queuetype The queuetype you're processing.
	* @param Mixed $recipients A list of recipients to process in the queue. This can be an array or a singular recipient id.
	*
	* @return Boolean Returns true if the query worked, returns false if there was a problem with the query.
	*/
	function MarkAsProcessed($queueid=0, $queuetype='export', $recipients=array())
	{
		if (!is_array($recipients)) {
			$recipients = array($recipients);
		}

		$recipients = $this->CheckIntVars($recipients);
		if (empty($recipients)) {
			return true;
		}

		$query = "UPDATE " . SENDSTUDIO_TABLEPREFIX . "queues SET processed=1, processtime=NOW() WHERE queueid='" . $this->Db->Quote($queueid) . "' AND queuetype='" . strtolower($queuetype) . "' AND recipient IN (" . implode(',', $recipients) . ")";
		$result = $this->Db->Query($query);
		if ($result) {
			return true;
		}
		return false;
	}

	/**
	* FetchFromQueue
	* Fetches recipients from a queue based on the queueid and queuetype.
	* If we are fetching for the autoresponder queue, we make sure we add an extra check to make sure we haven't sent to the subscriber already. This is used as an 'update' type flag instead of deleting them straight away (database speed issue).
	*
	* @param Int $queueid The queueid you're fetching recipients from.
	* @param String $queuetype The queuetype you're fetching from.
	* @param Int $startpos Starting position of records you want to fetch.
	* @param Int $limit The number of records you want to fetch. Combined with startpos you can 'page' through records in a queue.
	*
	* @see Jobs_Autoresponders_API::ActionJob
	*
	* @return False|Array Returns false if the query fails. If the query works then it returns an array of recipients - whether that array is empty or not is left for the calling function to check.
	*/
	function FetchFromQueue($queueid=0, $queuetype='', $startpos=1, $limit=100)
	{
		$queueid = (int)$queueid;
		$queuetype = strtolower($queuetype);

		$query = "SELECT recipient FROM " . SENDSTUDIO_TABLEPREFIX . "queues WHERE queueid='" . $queueid . "' AND queuetype='" . $this->Db->Quote($queuetype) . "' AND processed=0";

		if (substr($queuetype, 0, 4) == 'auto') {
			$query .= " AND sent=0";
		}

		if ($startpos && $limit) {
			$query .= $this->Db->AddLimit((($startpos - 1) * $limit), $limit);
		}

		$result = $this->Db->Query($query);
		if (!$result) {
			return false;
		}

		$recipients = array();
		while ($row = $this->Db->Fetch($result)) {
			array_push($recipients, $row['recipient']);
		}
		return $recipients;
	}

	/**
	* IsQueue
	* Checks whether a queue has already been created for this id and queuetype. This is used with autoresponders and scheduled sending especially. Scheduled sending will create the queue when it is about to run, so it needs to check if it is there already first.
	*
	* @param Int $queueid The queueid you're checking for.
	* @param String $queuetype The queuetype you're checking for.
	*
	* @see Jobs_Send_API::ActionJob
	* @see Jobs_Autoresponders_API::ActionJob
	*
	* @return Boolean Returns false if it doesn't exist, returns true if it does.
	*/
	function IsQueue($queueid=0, $queuetype='')
	{
		$query = "SELECT queueid FROM " . SENDSTUDIO_TABLEPREFIX . "queues WHERE queueid='" . $this->Db->Quote($queueid) . "' AND queuetype='" . $this->Db->Quote($queuetype) . "' LIMIT 1";
		$result = $this->Db->Query($query);
		if (!$result) {
			return false;
		}

		$row = $this->Db->Fetch($result);
		if (empty($row)) {
			return false;
		}

		if ($row['queueid'] <= 0) {
			return false;
		}

		return true;
	}

	/**
	* ClearQueue
	* Clears a queue based on the queueid and queuetype.
	*
	* @param Int $queueid The queueid you're deleting.
	* @param String $queuetype The queuetype you're deleting.
	*
	* @return Boolean Returns true if the query worked, returns false if there was a problem with the query.
	*/
	function ClearQueue($queueid=0, $queuetype='')
	{
		$queuetype = strtolower($queuetype);
		$query = "DELETE FROM " . SENDSTUDIO_TABLEPREFIX . "queues WHERE queueid='" . $this->Db->Quote($queueid) . "' AND queuetype='" . $this->Db->Quote($queuetype) . "'";
		$result = $this->Db->Query($query);
		if ($result) {
			return true;
		}

		return false;
	}

	/**
	* QueueSize
	* Lets us know how many recipients are left in the queue.
	*
	* @param Int $queueid The queueid you're fetching recipients from.
	* @param String $queuetype The queuetype you're fetching from.
	*
	* @return False|Int Returns false if the query didn't work. Otherwise returns the number of recipients left in the queue.
	*/
	function QueueSize($queueid=0, $queuetype='')
	{
		$queueid = (int)$queueid;
		$queuetype = strtolower($queuetype);

		$query = "SELECT COUNT(recipient) AS count FROM " . SENDSTUDIO_TABLEPREFIX . "queues WHERE queueid='" . $queueid . "' AND queuetype='" . $this->Db->Quote($queuetype) . "' AND processed=0";
		$result = $this->Db->Query($query);
		if (!$result) {
			return false;
		}

		$row = $this->Db->Fetch($result);
		return $row['count'];
	}


	/**
	* GetCustomFieldData
	* Returns custom field information based on the data passed in. If it's a checkbox or dropdown custom field type, it will get the 'real' values from the custom field - rather than using the 'key' values.
	*
	* <b>Example</b>
	* <code>
	* $data = array('fieldtype' => 'text', 'data' => 'this is my text', 'fieldid' => 0);
	* </code>
	* <code>
	* $data = array('fieldtype' => 'dropdown', 'data' => array('m'), 'fieldid' => 0);
	* </code>
	*
	* @param Array $data An array which contains the custom field data you want to process. This includes the fieldtype, the data and the fieldid.
	*
	* @see Customfields_API::Load
	* @see Customfields_API::Settings
	*
	* @return Mixed Returns false if the data array is empty or it doesn't have a fieldtype. Returns an array of real values if it's a checkbox or dropdown. Otherwise returns the raw value (for textboxes for example) or the integer value if it's a number field.
	*/
	function GetCustomFieldData($data=array())
	{
		if (!is_array($data)) {
			return false;
		}

		if (!isset($data['fieldtype'])) {
			return false;
		}

		switch (strtolower($data['fieldtype'])) {
			case 'checkbox':
			case 'dropdown':
				if ($data['fieldtype'] == 'checkbox') {
					$returninfo = (!is_array($data['data'])) ? unserialize($data['data']) : $data['data'];
				} else {
					$returninfo = $data['data'];
				}

				require_once(dirname(__FILE__) . '/customfields_' . $data['fieldtype'] . '.php');
				$apiname = 'CustomFields_' . ucwords($data['fieldtype']) . '_API';
				$customfields_api = & new $apiname();
				$customfields_api->Load($data['fieldid']);

				$returndetails = array();
				$settings = $customfields_api->Settings;
				foreach ($settings['Key'] as $pos => $val) {
					if (is_array($returninfo)) {
						if (in_array($val, array_keys($returninfo))) {
							$returndetails[] = $settings['Value'][$pos];
						}
					} else {
						if ($val == $returninfo) {
							$returndetails[] = $settings['Value'][$pos];
							break;
						}
					}
				}
				return $returndetails;
			break;
			default:
				return $data['data'];
		}
		return false;
	}

	/**
	* GenerateRandomPassword
	* Generates a random password string for a subscriber.
	*
	* @return String Returns the new password.
	*/
	function GenerateRandomPassword()
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

		$rand = 'ss';
		$max = sizeof($chars) - 1;
		while (strlen($rand) < 10) {
			$randchar = rand(0, $max);
			$rand .= $chars[$randchar];
		}
		return $rand;
	}

	/**
	* GetRealIp
	*
	* Gets the IP from the users web browser. It checks if there is a proxy etc in front of the browser.
	*
	* @return String The IP address of the user.
	*/
	function GetRealIp()
	{
		if (!SENDSTUDIO_IPTRACKING) {
			return null;
		}

		$ip = false;

		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		}

		// proxy
		if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ips = explode(', ', $_SERVER['HTTP_X_FORWARDED_FOR']);
			if ($ip) {
				array_unshift($ips, $ip);
				$ip = false;
			}

			for ($i = 0; $i < count($ips); $i++) {
				$ip_check = ip2long($ips[$i]);
				/**
				* PHP4 returns false if it's not a valid ip, PHP5 returns -1
				*/
				if ($ip_check !== false && $ip_check != -1) {
					$ip = $ips[$i];
					break;
				}
			}
		}
		return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);
	}

	/**
	* DeleteUserStats
	* Deletes statistics from a user account based on the details passed in. This is used when a scheduled job has been started but then cancelled (ie deleted). This will update the user stats to reflect the right number of emails that were sent from the beginning until when it was deleted.
	*
	* @param Int $userid The userid to update statistics for
	* @param Int $jobid The job that was deleted
	* @param Int $remove_amount The amount to remove from the user statistics. If this is less than 0 for any reason, it is reset back to 0.
	* @param Boolean $delete_all Whether to delete the whole user statistics for this job or not. This is used if the stats are recorded (when the job is set up) but never run.
	*
	* @see Jobs_API::Delete
	*
	* @return True Always returns true
	*/
	function DeleteUserStats($userid=0, $jobid=0, $remove_amount=0, $delete_all=false)
	{
		$remove_amount = (int)$remove_amount;

		$userid = (int)$userid;
		$jobid = (int)$jobid;

		$query = "UPDATE " . SENDSTUDIO_TABLEPREFIX . "stats_users SET ";

		if ($remove_amount < 0) {
			$remove_amount = 0;
		}

		if (!$delete_all) {
			if ($remove_amount >= 0) {
				$query .= "queuesize=queuesize - " . $remove_amount;
			}
		}

		if ($delete_all) {
			$query = "DELETE FROM " . SENDSTUDIO_TABLEPREFIX . "stats_users";
		}

		$query .= " WHERE jobid='" . $jobid . "' AND userid='" . $userid . "'";

		$result = $this->Db->Query($query);

		return true;
	}

	/**
	* FetchLink
	* Fetchs a url from the links table based on the linkid. This is used after tracking has taken place so we can redirect to the right place.
	*
	* @param Int $linkid Link to load
	* @param Int $statid The statistics id to load up the link for. This is passed in by link tracking so we can make sure it's a valid link and statistical recording.
	*
	* @see GetDb
	* @see SaveLink
	*
	* @return String Returns the URL from the database based on the information passed in.
	*/
	function FetchLink($linkid=0, $statid=false)
	{
		$this->GetDb();

		if (!$statid) {
			$query = "SELECT url FROM " . SENDSTUDIO_TABLEPREFIX . "links WHERE linkid='" . $this->Db->Quote($linkid) . "'";
		} else {
			$query = "SELECT url FROM " . SENDSTUDIO_TABLEPREFIX . "links l, " . SENDSTUDIO_TABLEPREFIX . "stats_links sl WHERE l.linkid=sl.linkid AND l.linkid='" . $this->Db->Quote($linkid) . "' AND sl.statid='" . $this->Db->Quote($statid) . "'";
		}
		$result = $this->Db->Query($query);
		$url = $this->Db->FetchOne($result, 'url');
		$url = str_replace(array('"', "'"), '', $url);
		return $url;
	}

	/**
	* CheckIntVars
	* This goes through the array passed in and strips out any non-numeric characters. This can then be used safely for implodes for searching particular listid's or subscriberid's without worrying about sql injection.
	* create_function creates a dynamic function so we don't have another function to call inside this one.
	* Quoted numbers such as '2' or "11" will get returned without the quotes as per is_numeric functionality.
	*
	* <b>Example</b>
	* <code>
	* $vals = array(1,'12', 'f', "string");
	* $vals = CheckIntVars($vals);
	* </code>
	* This will become:
	* <code>
	* $vals = array(1, 12);
	* </code>
	*
	* @param Array $array_to_check Array of values to check and make sure they are integers.
	*
	* @see RemoveBannedEmails
	* @see RemoveUnsubscribedEmails
	* @see RemoveFromQueue
	* @see MarkAsProcessed
	*
	* @return Array Array of values which are numbers only. All non-numeric characters or strings are removed.
	*/
	function CheckIntVars($array_to_check=array())
	{
		if (!is_array($array_to_check)) {
			return array();
		}
		foreach ($array_to_check as $p => $var) {
			if (!is_numeric($var)) {
				unset($array_to_check[$p]);
			}
		}
		return $array_to_check;
	}

	/**
	* GetServerTime
	* Uses the ConvertDate object to turn "now" server time into gmt time.
	*
	* @see ConvertDate
	* @see ConvertDate::ConvertToGMTFromServer
	*
	* @return Int Returns the new 'timestamp' in gmt time.
	*/
	function GetServerTime()
	{
		if (is_null($this->ConvertDate)) {
			$this->ConvertDate = new ConvertDate(SENDSTUDIO_SERVERTIMEZONE);
		}

		$timenow = getdate();

		$hr = $timenow['hours'];
		$min = $timenow['minutes'];
		$sec = $timenow['seconds'];
		$mon = $timenow['mon'];
		$day = $timenow['mday'];
		$yr = $timenow['year'];

		return $this->ConvertDate->ConvertToGMTFromServer($hr, $min, $sec, $mon, $day, $yr);
	}

	/**
	* CleanVersion
	* Cleans up placeholders from the content passed in. If no subscriber information is passed in, it will replace placeholders with '#' so for example an unsubscribe link can't be clicked on from an rss feed if you are not a valid subscriber.
	*
	* @var String $content The content to "clean up".
	* @var Array $subscriberinfo The subscriber information to use to clean up the content. If this is not present, placeholders will be replaced with a '#'.
	*
	* @return String The new content either with proper placeholders or invalid placeholders.
	*/
	function CleanVersion($content='', $subscriberinfo=array())
	{
		if (empty($subscriberinfo)) {
			$content = preg_replace('/%%webversion%%/i', '#', $content);
			$content = preg_replace('/%basic:archivelink%/i', '#', $content);

			$content = preg_replace('/%%mailinglistarchive%%/i', '#', $content);

			$content = preg_replace('/%basic:unsublink%/i', '#', $content);
			$content = preg_replace('/%%unsubscribelink%%/i', '#', $content);

			$content = preg_replace('/%%confirmlink%%/i', '#', $content);
			$content = preg_replace('/%basic:confirmlink%/i', '#', $content);

			$content = preg_replace('/%%sendfriend_(.*)%%/i', '#', $content);

			$content = preg_replace('/%%modifydetails_(.*)%%/i', '#', $content);

			return $content;
		}

		$customfields = $subscriberinfo['CustomFields'];
		foreach ($customfields as $p => $details) {
			$fieldname = '%%' . str_replace(' ', '\\s+', preg_quote(strtolower($details['fieldname']), '/')) . '%%';

			if (!is_null($details['data'])) {
				$content = preg_replace('/'. $fieldname . '/i', $details['data'], $content);
			}
		}

		$content = str_replace(array('%BASIC:EMAIL%', '%basic:email%'), '%%emailaddress%%', $content);

		$basefields = array('emailaddress', 'confirmed', 'format', 'subscribedate', 'listname');
		foreach ($basefields as $p => $field) {
			$field = strtolower($field);

			if (!isset($subscriberinfo[$field])) {
				continue;
			}
			$fielddata = $subscriberinfo[$field];
			if ($field == 'subscribedate') {
				$fielddata = date(LNG_DateFormat, $fielddata);
			}
			$fieldname = '%%' . $field . '%%';
			$content = str_replace($fieldname, $fielddata, $content);
			unset($fielddata);
		}

		$web_version_link = SENDSTUDIO_APPLICATION_URL . '/display.php?M=' . $subscriberinfo['subscriberid'];
		$web_version_link .= '&C=' . $subscriberinfo['confirmcode'];

		if (isset($subscriberinfo['listid'])) {
			$web_version_link .= '&L=' . $subscriberinfo['listid'];
		}

		if (isset($subscriberinfo['newsletter'])) {
			$web_version_link .= '&N=' . $subscriberinfo['newsletter'];
		}

		if (isset($subscriberinfo['autoresponder'])) {
			$web_version_link .= '&A=' . $info['autoresponder'];
		}

		$content = str_replace(array('%%webversion%%', '%%WEBVERSION%%'), $web_version_link, $content);

		$mailinglist_archives_link = SENDSTUDIO_APPLICATION_URL . '/rss.php?M=' . $subscriberinfo['subscriberid'];
		$mailinglist_archives_link .= '&C=' . $subscriberinfo['confirmcode'];

		if (isset($subscriberinfo['listid'])) {
			$mailinglist_archives_link .= '&L=' . $subscriberinfo['listid'];
		}

		$content = str_replace(array('%%mailinglistarchive%%', '%%MAILINGLISTARCHIVE%%'), $mailinglist_archives_link, $content);


		$confirmlink = SENDSTUDIO_APPLICATION_URL . '/confirm.php?E=' . $subscriberinfo['emailaddress'];
		if (isset($subscriberinfo['listid'])) {
			$confirmlink .= '&L=' . $subscriberinfo['listid'];
		}

		$confirmlink .= '&C=' . $subscriberinfo['confirmcode'];

		$content = str_replace(array('%%confirmlink%%', '%%CONFIRMLINK%%'), $confirmlink, $content);

		$content = str_replace(array('%basic:confirmlink%', '%BASIC:CONFIRMLINK%'), $confirmlink, $content);

		$unsubscribelink = SENDSTUDIO_APPLICATION_URL . '/unsubscribe.php?';

		$linkdata = 'M=' . $subscriberinfo['subscriberid'];

		// so we can track where someone unsubscribed from, we'll add that into the url.
		if (isset($subscriberinfo['newsletter'])) {
			$linkdata .= '&N=' . $subscriberinfo['statid'];
		}

		if (isset($subscriberinfo['autoresponder'])) {
			$linkdata .= '&A=' . $subscriberinfo['statid'];
		}

		if (isset($subscriberinfo['listid'])) {
			$linkdata .= '&L=' . $subscriberinfo['listid'];
		}

		$linkdata .= '&C=' . $subscriberinfo['confirmcode'];

		$unsubscribelink .= $linkdata;

		// preg_replace takes up too much memory so we'll do double replace.
		// we can't do it as an array because that takes up too much memory as well.
		$content = str_replace(array('%%UNSUBSCRIBELINK%%','%%unsubscribelink%%'), $unsubscribelink, $content);

		$content = str_replace(array('%basic:unsublink%', '%BASIC:UNSUBLINK%'), $unsubscribelink, $content);

		preg_match('/%%modifydetails_(.*)%%/i', $content, $matches);
		if (isset($matches[1]) && !empty($matches[1])) {
			$replaceurl = SENDSTUDIO_APPLICATION_URL . '/modifydetails.php?' . $linkdata . '&F=' . $matches[1];
			$content = str_replace($matches[0], $replaceurl, $content);
		}

		preg_match('/%%sendfriend_(.*)%%/i', $content, $matches);

		if (isset($matches[1]) && !empty($matches[1])) {
			$extra = '';
			if (isset($info['newsletter'])) {
				$extra = '&i=' . $subscriberinfo['newsletter'];
			}

			if (isset($info['autoresponder'])) {
				$extra = '&i=' . $subscriberinfo['autoresponder'];
			}

			$replaceurl = SENDSTUDIO_APPLICATION_URL . '/sendfriend.php?' . $linkdata . '&F=' . $matches[1] . $extra;

			$content = str_replace($matches[0], $replaceurl, $content);
		}
		return $content;
	}

	/**
	* GetCustomFieldType
	* Gets the custom field type for the fieldid passed in. This allows us to quickly use different searching / filtering for different field types.
	*
	* @see Subscriber_API::FetchSubscribers
	* @see Subscriber_API::GetSubscribers
	*
	* @return Mixed Returns false if the fieldtype can't be fetched, otherwise returns the fieldtype from the database.
	*/
	function GetCustomFieldType($fieldid=0)
	{
		$query = "SELECT fieldtype FROM " . SENDSTUDIO_TABLEPREFIX . "customfields WHERE fieldid='" . (int)$fieldid . "'";
		$result = $this->Db->Query($query);
		if (!$result) {
			return false;
		}
		$fieldtype = $this->Db->FetchOne($result, 'fieldtype');
		return strtolower($fieldtype);
	}

}

?>
