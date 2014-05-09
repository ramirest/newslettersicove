<?php
/**
* The Subscribers API. It handles loading, whether a subscriber exists, is on a particular mailing list and so on.
*
* @version     $Id: subscribers.php,v 1.95 2007/06/21 05:22:27 chris Exp $

*
* @package API
* @subpackage Subscribers_API
*/

/**
* Load up the base API class if we need to.
*/
require_once(dirname(__FILE__) . '/api.php');

/**
* This will load a subscriber, save a subscriber, set details and get details.
*
* @package API
* @subpackage Subscribers_API
*/
class Subscribers_API extends API
{

	/**
	* The current subscriber id. By default it's '0'. Is set by Load
	*
	* @see Load
	*
	* @var Int
	*/
	var $subscriberid = 0;

	/**
	* The subscribers email address.
	*
	* @var String
	*/
	var $emailaddress = '';

	/**
	* The subscribers chosen format. This is either 't' (text) or 'h' (html). This depends on which list they are subscribed (or trying to subscribe) to.
	*
	* @var String
	*/
	var $format = 't';

	/**
	* Whether the subscriber has confirmed their email address or not. This depends on which list they are subscribed (or trying to subscribe) to.
	*
	* @var Int
	*/
	var $confirmed = 0;

	/**
	* The form the subscriber first joined from.
	*
	* @var Int
	*/
	var $formid = 0;

	/**
	* A random string used to authenticate a subscriber. It is randomly generated.
	*
	* @see GenerateConfirmCode
	*
	* @var String
	*/
	var $confirmcode = false;

	/**
	* The date the subscriber tried to join a list.
	*
	* @var Int
	*/
	var $requestdate = 0;

	/**
	* The ip from which the subscriber tried to join a list.
	*
	* @var String
	*/
	var $requestip = '';

	/**
	* Whether the subscriber has confirmed their unsubscribe request or not.
	*
	* @var Int
	*/
	var $unsubscribeconfirmed = 0;

	/**
	* The ip from which the subscriber tried to unsubscribe from a list.
	*
	* @var String
	*/
	var $unsubscriberequestip = '';

	/**
	* The date the subscriber tried to unsubscribe from a list.
	*
	* @var Int
	*/
	var $unsubscriberequesttime = 0;

	/**
	* The date the subscriber confirmed they wanted to unsubscribe.
	*
	* @var Int
	*/
	var $unsubscribetime = 0;

	/**
	* The ip from which the subscriber confirmed unsubscribing.
	*
	* @var String
	*/
	var $unsubscribeip = '';

	/**
	* The date the subscriber confirmed their subscription.
	*
	* @var Int
	*/
	var $confirmdate = 0;

	/**
	* The date the subscriber became active on the list.
	* This is kept separately and can be imported through 'Import Subscribers' so we can see the 3 stages:
	* - request
	* - confirm
	* - subscribe
	*
	* @var Int
	*/
	var $subscribedate = 0;

	/**
	* The ip from which the subscriber confirmed joining the list.
	*
	* @var String
	*/
	var $confirmip = '';

	/**
	* This temporarily stores the subscribers custom field data. This depends on which list they are subscribed (or trying to subscribe) to.
	*
	* @see GetCustomFieldSettings
	* @see LoadSubscriberList
	* @see LoadSubscriberListCustomFields
	* @see GetCustomFieldSettings
	*
	* @var Array
	*/
	var $customfields = array();

	/**
	* Required number of messages before we 'bounce' a subscriber from the list and make them inactive. This is the upper limit, so if they reach this amount they are bounced. That is, it's inclusive of the bounce that is being recorded.
	*
	* @var Int
	*/
	var $softbounce_count = 5;

	/**
	* Constructor
	* Sets up the database object only. You cannot pass in a subscriber id to load. Loading a subscriber's settings depends on what list they are subscribed to.
	*
	* @see LoadSubscriberList
	*
	* @return True Always returns true.
	*/
	function Subscribers_API()
	{
		$this->GetDb();
		return true;
	}

	/**
	* LoadSubscriberBasicInformation
	* Loads up basic subscriber information for a particular list, which includes the format they are subscribed as, the confirm code and so on. This is used by scheduled sending to check which list(s) a particular email address is on.
	* If they are not on a list or they are bounced / unsubscribed from a list, this returns an empty array.
	* If they are active on a list it returns an array of their information.
	*
	* @param Int $subscriberid The subscriber / recipient to check.
	* @param Array $listids The listids to check. This can be a single number (eg '1') or an array. If it's not an array, it will be converted into one.
	*
	* @return Array Returns either an empty array or full array.
	*/
	function LoadSubscriberBasicInformation($subscriberid=0, $listids=Array())
	{
		if (!is_array($listids)) {
			$listids = array((int)$listids);
		} else {
			$listids = $this->CheckIntVars($listids);
		}

		if (empty($listids)) {
			return array();
		}

		$query = "SELECT * FROM " . SENDSTUDIO_TABLEPREFIX . "list_subscribers WHERE listid IN (" . implode(',', $listids) . ") AND subscriberid='" . $this->Db->Quote($subscriberid) . "'";

		$result = $this->Db->Query($query);
		$subscriberinfo = $this->Db->Fetch($result);

		if (empty($subscriberinfo)) {
			return array();
		}

		if ($subscriberinfo['bounced'] > 0 || $subscriberinfo['unsubscribed'] > 0) {
			return array();
		}

		$subscriberinfo['subscriberid'] = $subscriberid;
		return $subscriberinfo;
	}

	/**
	* GetSubscriberIdsToConfirm
	* Gets a list of subscriberid's that need confirming based on the email address and confirm code passed in.
	* We need to do this because subscriberid's are unique per system, so if you sign up for multiple lists we need to get all subscriberid's to confirm.
	* The confirm code will be the same and of course so will the email address.
	* Returns an array of subscriberid's that need confirming.
	*
	* @param String $email Email address to check for
	* @param String $confirmcode The confirmation code to check for
	*
	* @see confirm.php
	*
	* @return Array Returns an array of subscriberid's that need confirming.
	*/
	function GetSubscriberIdsToConfirm($email='', $confirmcode='')
	{
		$query = "SELECT subscriberid FROM " . SENDSTUDIO_TABLEPREFIX . "list_subscribers WHERE emailaddress='" . $this->Db->Quote($email) . "' AND confirmcode='" . $this->Db->Quote($confirmcode) . "'";

		$result = $this->Db->Query($query);

		$ids = array();
		while ($row = $this->Db->Fetch($result)) {
			$ids[] = $row['subscriberid'];
		}
		return $ids;
	}

	/**
	* GetAllListsForEmailAddress
	* Gets all subscriberid's, listid's for a particular email address and returns an array of them.
	* This is used by the unsubscribe function to remove a subscriber from multiple lists at once.
	* This only gets called if you send to multiple lists.
	* This also only finds active subscribers. If you have unsubscribed from a list already it will not return your entry.
	*
	* @param String $email The email address to find on all of the lists.
	* @param Array $listids The lists to check for the address on. This will be the lists that the newsletter was sent to. By limiting the query here, saves some processing on the unsubscribe side of things.
	* @param Int $main_listid This is used for ordering the results of the query. When this is passed in, the main list should appear at the top. This makes it first in line for checking whether the subscriber is valid or not. We need to do this if you are subscribed to multiple lists, because confirmcodes will be different per list.
	*
	* @return Array Returns either an empty array (if no email address is passed in) or a multidimensional array containing both subscriberid and listid.
	*/
	function GetAllListsForEmailAddress($email='', $listids=array(), $main_listid=0)
	{
		$return = array();
		if (!$email) {
			return $return;
		}

		$listids = $this->CheckIntVars($listids);
		if (empty($listids)) {
			return $return;
		}

		$query = "SELECT subscriberid, listid";

		if ($main_listid) {
			$query .= ", CASE WHEN listid='" . (int)$main_listid . "' THEN 1 ELSE 0 END AS order_list";
		}

		$query .= " FROM " . SENDSTUDIO_TABLEPREFIX . "list_subscribers WHERE emailaddress='" . $this->Db->Quote($email) . "' AND (unsubscribed = 0 AND bounced = 0)";
		if (!empty($listids) && $listids[0] != '0') {
			$query .= " AND listid IN (" . implode(',', $listids) . ")";
		}

		if ($main_listid) {
			$query .= " ORDER BY order_list DESC";
		}

		$result = $this->Db->Query($query);
		while ($row = $this->Db->Fetch($result)) {
			$return[] = array('subscriberid' => $row['subscriberid'], 'listid' => $row['listid']);
		}
		return $return;
	}

	/**
	* GetEmailForSubscriber
	* Gets the email address for the subscriberid passed in. This is used by unsubscribe forms so we can then use GetAllListsForEmailAddress to get all lists the subscriber is on. We need to do this in case you send to multiple lists and someone clicks an unsubscribe link.
	*
	* @param Int $subscriberid The subscriberid to look up and fetch the email address for.
	*
	* @return Boolean|String Returns false if the email address can't be found. Otherwise returns the email address.
	*/
	function GetEmailForSubscriber($subscriberid=0)
	{
		$query = "SELECT emailaddress FROM " . SENDSTUDIO_TABLEPREFIX . "list_subscribers WHERE subscriberid='" . (int)$subscriberid ."'";
		$result = $this->Db->Query($query);
		$row = $this->Db->Fetch($result);
		if (empty($row) || !isset($row['emailaddress'])) {
			return false;
		}
		return $row['emailaddress'];
	}


	/**
	* LoadSubscriberForm
	* This is used by confirmation emails to get the order of the custom fields according to the order in the website form.
	*
	* It is much the same as LoadSubscriberList & LoadSubscriberCustomFields put together, but it joins the form custom fields table as well to get the order and give info in the specified order.
	*
	* @see LoadSubscriberList
	* @see LoadSubscriberCustomFields
	*
	* @return Array Returns an array containing the basic subscriber information and also the custom field information in the order according to the form.
	*/
	function LoadSubscriberForm($subscriberid=0, $listid=0)
	{
		$subscriberinfo = $this->LoadSubscriberList($subscriberid, $listid, true, false, false);

		if (empty($subscriberinfo)) {
			return array();
		}

		$allcustomfields = array();

		$query = "select sd.fieldid AS fieldid, sd.data AS data, c.fieldtype AS fieldtype, c.name AS fieldname FROM ";
		$query .= SENDSTUDIO_TABLEPREFIX . "subscribers_data sd,";
		$query .= SENDSTUDIO_TABLEPREFIX . "customfield_lists cl,";
		$query .= SENDSTUDIO_TABLEPREFIX . "form_customfields fc,";
		$query .= SENDSTUDIO_TABLEPREFIX . "customfields c";
		$query .= " WHERE ";
		$query .= "sd.fieldid=cl.fieldid AND ";
		$query .= "fc.fieldid=cl.fieldid AND ";
		$query .= "c.fieldid=sd.fieldid AND ";
		$query .= "sd.subscriberid='" . $this->Db->Quote($subscriberid) . "' AND ";
		$query .= "cl.listid='" . $this->Db->Quote($listid) . "'";
		$query .= " ORDER BY fc.fieldorder ASC";

		$result = $this->Db->Query($query);

		while ($row = $this->Db->Fetch($result)) {
			$allcustomfields[] = $row;
		}

		$subscriberinfo['CustomFields'] = $allcustomfields;

		return $subscriberinfo;
	}

	/**
	* LoadSubscriberList
	* Loads subscriber data based on the list specified. Also loads custom fields (if there are any).
	*
	* @param Int $subscriberid Subscriber to load up.
	* @param Int $listid The list the subscriber is on.
	* @param Boolean $returnonly Whether to only return the results or whether to set them in the class variables as well. If it's false (default), it sets the class variables. If it's true, then it only returns the values.
	* @param Boolean $activeonly Whether to search for active only subscribers.
	* @param Boolean $include_customfields Whether to load up custom fields at the same time as loading the subscriber or not. This is used by sending and autoresponders to possibly limit the number of queries that are run.
	*
	* @see LoadSubscriberListCustomFields
	*
	* @return Array Returns the subscribers information with custom fields etc.
	*/
	function LoadSubscriberList($subscriberid=0, $listid=0, $returnonly=false, $activeonly=false, $include_customfields=true)
	{
		$query = "SELECT * FROM " . SENDSTUDIO_TABLEPREFIX . "list_subscribers l LEFT OUTER JOIN " . SENDSTUDIO_TABLEPREFIX . "list_subscribers_unsubscribe lu ON l.subscriberid=lu.subscriberid WHERE l.listid='" . $this->Db->Quote($listid) . "' AND l.subscriberid='" . $this->Db->Quote($subscriberid) . "'";

		if ($activeonly) {
			$query .= " AND (unsubscribed = 0 AND bounced = 0)";
		}

		$result = $this->Db->Query($query);
		$subscriberinfo = $this->Db->Fetch($result);

		$customfields = array();

		if ($returnonly && empty($subscriberinfo)) {
			return array();
		}

		if ($include_customfields) {
			if (!empty($subscriberinfo)) {
				$customfields = $this->LoadSubscriberCustomFields($subscriberid, $listid);
			}
			$subscriberinfo['CustomFields'] = $customfields;
		} else {
			$subscriberinfo['CustomFields'] = array();
		}

		$subscriberinfo['subscriberid'] = $subscriberid;

		if (!isset($subscriberinfo['confirmcode'])) {
			return array();
		}

		if (!$returnonly) {
			$this->confirmcode = $subscriberinfo['confirmcode'];
			$this->confirmip = $subscriberinfo['confirmip'];
			$this->confirmdate = $subscriberinfo['confirmdate'];
			$this->requestip = $subscriberinfo['requestip'];
			$this->requestdate = $subscriberinfo['requestdate'];
			$this->customfields = $customfields;
			$this->subscriberid = $subscriberid;
			$this->format = $subscriberinfo['format'];
			$this->formid = $subscriberinfo['formid'];
		}
		return $subscriberinfo;
	}

	/**
	* AddToListAutoresponders
	* This will add a subscriber to all autoresponders for a particular list.
	* The autoresponder file handles removing duplicate subscribers so we don't have to worry about it here.
	*
	* @param Int $subscriberid Subscriber id to add to the autoresponder.
	* @param Int $listid List to add them to for autoresponders.
	*
	* @see Jobs_Autoresponders::ActionJob
	* @see RemoveDuplicatesInQueue
	*
	* @return Boolean Returns false if there is no subscriber or listid, otherwise adds them and returns true.
	*/
	function AddToListAutoresponders($subscriberid=0, $listid=0)
	{
		$subscriberid = (int)$subscriberid;
		$listid = (int)$listid;
		if ($subscriberid <= 0 || $listid <= 0) {
			return false;
		}

		// in case they have been added to the autoresponders already, we should remove them
		$query = "DELETE FROM " . SENDSTUDIO_TABLEPREFIX . "queues WHERE queuetype='autoresponder' AND recipient='" . $subscriberid . "'";
		$this->Db->Query($query);

		$query = "INSERT INTO " . SENDSTUDIO_TABLEPREFIX . "queues (queueid, queuetype, ownerid, recipient, processed) SELECT queueid, 'autoresponder', ownerid, '" . $subscriberid . "', 0 FROM " . SENDSTUDIO_TABLEPREFIX . "autoresponders WHERE listid='" . $listid . "'";
		$this->Db->Query($query);
	}

	/**
	* LoadSubscriberListCustomFields
	* Loads customfield data based on the list specified.
	* Checks whether the list actually exists and fetches custom fields from that list.
	*
	* @param Int $subscriberid Subscriber to load up.
	* @param Int $listid The list the subscriber is on.
	*
	* @see LoadSubscriberList
	* @see Lists_API
	* @see Lists_API::GetCustomFields
	*
	* @return Array Returns the subscribers custom field data for that particular list.
	*/
	function LoadSubscriberCustomFields($subscriberid=0, $listid=0)
	{
		$subscriberid = (int)$subscriberid;
		$listid = (int)$listid;

		require_once(dirname(__FILE__) . '/lists.php');
		$list = &new Lists_API();
		$customfields = $list->GetCustomFields($listid);

		$fields = array();
		$fieldtypes = array();
		foreach ($customfields as $pos => $details) {
			$fields[] = $details['fieldid'];
			$fieldtypes[$details['fieldid']] = array('fieldtype' => $details['fieldtype'], 'fieldname' => $details['name']);
		}

		$allcustomfields = array();
		if (empty($fields)) {
			return $allcustomfields;
		}

		$query = "SELECT fieldid, data FROM " . SENDSTUDIO_TABLEPREFIX . "subscribers_data WHERE subscriberid='" . $this->Db->Quote($subscriberid) . "' AND fieldid IN (" . implode(',', $fields) . ")";
		$result = $this->Db->Query($query);
		while ($row = $this->Db->Fetch($result)) {
			$row['fieldtype'] = $fieldtypes[$row['fieldid']]['fieldtype'];
			$cur = sizeof($allcustomfields);
			$allcustomfields[$cur] = $row;
			$allcustomfields[$cur]['fieldname'] = $fieldtypes[$row['fieldid']]['fieldname'];
		}
		unset($fields);
		unset($fieldtypes);
		return $allcustomfields;
	}

	/**
	* GetAllSubscriberCustomFields
	* Gets all subscriber custom fields for a particular list. This is used by autoresponders and sending so it only has to load all custom fields once per run.
	*
	* @param Array $listids An array of listid's that the custom fields are attached to. If this is not an array, it is turned into one for easy processing.
	* @param Array $limit_fields An array of field names to fetch custom field information for. These are strings (eg 'Name') and are the placeholders in the newsletter/autoresponder that are going to be replaced.
	* @param Array $subscriberids An array of subscriberids to fetch custom field information for.
	*
	* @return Array Returns the subscribers custom field data for the lists & fields passed in. It will also contain an 'unclaimed' entry which will have default values in it if applicable. This is so custom field replacement can use the default if the specific subscriber doesn't have data.
	*/
	function GetAllSubscriberCustomFields($listids=array(), $limit_fields=array(), $subscriberids=array(), $custom_fieldids=array())
	{
		if (!is_array($listids)) {
			$listids = array($listids);
		}
		$listids = $this->CheckIntVars($listids);

		$return_fields = array();

		if ((empty($limit_fields) && empty($custom_fieldids)) || empty($listids)) {
			return array();
		}

		$field_list = '';
		foreach ($limit_fields as $p => $fieldname) {
			if (is_numeric($fieldname)) {
				$custom_fieldids[] = $fieldname;
				continue;
			}
			$field_list .= "'" . $this->Db->Quote($fieldname) . "', ";
		}
		$field_list = substr($field_list, 0, -2);

		if (!empty($custom_fieldids)) {
			$custom_fieldids = $this->CheckIntVars($custom_fieldids);
			if (empty($custom_fieldids)) {
				$custom_fieldids = array('0');
			}
		}

		$subscriberids = $this->CheckIntVars($subscriberids);
		if (empty($subscriberids)) {
			$subscriberids = array('0');
		}

		$query = "SELECT c.fieldid, c.name AS fieldname, c.fieldtype, c.defaultvalue, c.fieldsettings, d.subscriberid, d.data FROM " . SENDSTUDIO_TABLEPREFIX . "customfields c, " . SENDSTUDIO_TABLEPREFIX . "customfield_lists cl, " . SENDSTUDIO_TABLEPREFIX . "list_subscribers ls LEFT OUTER JOIN " . SENDSTUDIO_TABLEPREFIX . "subscribers_data d ON ls.subscriberid=d.subscriberid WHERE cl.fieldid=c.fieldid AND cl.listid IN (" . implode(',', $listids) . ") AND cl.listid=ls.listid AND d.fieldid=c.fieldid";

		if ($field_list) {
			$query .= " AND ";
			if (!empty($custom_fieldids)) {
				$query .= "(";
			}
			$query .= " c.name IN (" . $field_list . ")";
		}

		if (!empty($custom_fieldids)) {
			if ($field_list) {
				$query .= " OR ";
			} else {
				$query .= " AND ";
			}
			$query .= " c.fieldid IN (" . implode(',', $custom_fieldids) . ")";

			if ($field_list) {
				$query .= " )";
			}
		}

		$query .= " AND (d.subscriberid IS NULL OR d.subscriberid IN(" . implode(',', $subscriberids) . "))";

		$result = $this->Db->Query($query);
		while ($row = $this->Db->Fetch($result)) {
			if ($row['fieldtype'] != 'text') {
				$row['defaultvalue'] = null;
			}
			if (!in_array($row['subscriberid'], array_keys($return_fields))) {
				$return_fields[$row['subscriberid']] = array();
			}

			$return_fields[$row['subscriberid']][] = $row;
		}

		return $return_fields;
	}

	/**
	* GetCustomFieldSettings
	* Goes through this subscribers custom fields and looks for specific field (based on the id).
	*
	* @param Int $fieldid Field to check for.
	* @param Boolean $allinfo Whether to return just the data or both the data, fieldtype and id. Being off (default) only returns the data.
	*
	* @see customfields
	*
	* @return Mixed Returns false if it can't find the field. Returns the data if that's all you want. Returns an array of the data, fieldtype and id if you specify allinfo.
	*/
	function GetCustomFieldSettings($fieldid=0, $allinfo=false)
	{
		if (!$fieldid) {
			return false;
		}

		foreach ($this->customfields as $pos => $details) {
			if ($fieldid != $details['fieldid']) {
				continue;
			}
			if (!$allinfo) {
				return $details['data'];
			}

			return $details;
		}
		return false;
	}

	/**
	* SaveSubscriberCustomField
	* Saves custom field information for a particular subscriber, particular list and particular field.
	* Deletes old data if it's present, then re-creates it again.
	*
	* @param Array $subscriberids An array of subscriberid's to update. We need to pass in an array for modify details forms - that is, so we can change it across multiple lists at once. If this is not already an array, it will be turned into one.
	* @param Int $fieldid The custom field you are saving for.
	* @param Mixed $data The actual custom field data. If this is an array, it will be serialized up before saving.
	*
	* @return Mixed Returns false if an invalid field, subscriber or list is returned. Or if it can't save the custom field data. Otherwise returns true.
	*/
	function SaveSubscriberCustomField($subscriberids=array(), $fieldid=0, $data='')
	{
		if (!is_array($subscriberids)) {
			$subscriberids = array($subscriberids);
		}

		$subscriberids = $this->CheckIntVars($subscriberids);

		if (empty($subscriberids) || $fieldid <= 0) {
			return false;
		}

		if (is_array($data)) {
			// if it's a data field, store it a little differently.
			// This makes searching a lot easier.
			if (isset($data['dd']) && isset($data['mm']) && isset($data['yy'])) {
				$data = $data['dd'] . '/' . $data['mm'] . '/' . $data['yy'];
			} else {
				$data = serialize($data);
			}
		}

		$query = "DELETE FROM " . SENDSTUDIO_TABLEPREFIX . "subscribers_data WHERE subscriberid IN (" . implode(',', $subscriberids) . ") AND fieldid='" . $this->Db->Quote($fieldid) . "'";
		$result = $this->Db->Query($query);

		foreach ($subscriberids as $p => $subscriberid) {
			$query = "INSERT INTO " . SENDSTUDIO_TABLEPREFIX . "subscribers_data (subscriberid, fieldid, data) VALUES ('" . $this->Db->Quote($subscriberid) . "', '" . $this->Db->Quote($fieldid) . "', '" . $this->Db->Quote($data) . "')";
			$result = $this->Db->Query($query);
		}
		return true;
	}

	/**
	* AddToList
	* Adds a subscriber to a list. Checks whether the list actually exists. If it doesn't, returns an error.
	*
	* @param String $emailaddress Subscriber address to add to the list.
	* @param Mixed $listid The list to add the subcriber to. This can be a list name or a list id.
	* @param Boolean $add_to_autoresponders Whether to add the subscriber to the lists' autoresponders or not.
	* @param Boolean $skip_listcheck Whether to skip checking the list or not. This is useful if you've already processed the lists to make sure they are ok.
	*
	* @see GenerateConfirmCode
	* @see Lists_API
	* @see Lists_API::Find
	*
	* @return Boolean Returns false if there is an invalid subscriber or list id, or if the list doesn't really exist. If it works, then it returns the new subscriber id from the database.
	*/
	function AddToList($emailaddress='', $listid=null, $add_to_autoresponders=true, $skip_listcheck=false)
	{
		if (is_null($listid)) {
			return false;
		}

		if ($skip_listcheck) {
			$real_listid['listid'] = (int)$listid;
			if ($real_listid['listid'] <= 0) {
				return false;
			}
		} else {
			require_once(dirname(__FILE__) . '/lists.php');
			$list = &new Lists_API();
			$real_listid = $list->Find($listid);
			$real_listid['listid'] = (int)$real_listid['listid'];
			if ($real_listid['listid'] <= 0) {
				return false;
			}
		}

		$confirmdate = $this->GetServerTime();
		if ($this->confirmdate > 0) {
			$confirmdate = $this->confirmdate;
		}

		$requestdate = $this->GetServerTime();
		if ($this->requestdate > 0) {
			$requestdate = $this->requestdate;
		}

		$this->requestdate = $requestdate;
		$this->confirmdate = $confirmdate;

		$query = "INSERT INTO " . SENDSTUDIO_TABLEPREFIX . "list_subscribers(listid, emailaddress, format, confirmed, confirmcode, subscribedate, bounced, unsubscribed, requestdate, requestip, confirmdate, confirmip, formid) VALUES ('" . $real_listid['listid'] . "', '" . $this->Db->Quote($emailaddress) . "', '" . $this->Db->Quote($this->format) . "', '" . $this->Db->Quote($this->confirmed) . "', '" . $this->Db->Quote($this->GenerateConfirmCode()) . "', " . $confirmdate . ", '0', '0', " . $requestdate . ", '" . $this->Db->Quote($this->requestip) . "', " . $confirmdate . ", '', '" . (int)$this->formid . "')";
		$result = $this->Db->Query($query);

		if (!$result) {
			return false;
		}

		$subscriberid = $this->Db->LastId(SENDSTUDIO_TABLEPREFIX . 'list_subscribers_sequence');

		$query = "UPDATE " . SENDSTUDIO_TABLEPREFIX . "lists SET subscribecount=subscribecount + 1 WHERE listid='" . $real_listid['listid'] . "'";
		$result = $this->Db->Query($query);

		if ($add_to_autoresponders) {
			$this->AddToListAutoresponders($subscriberid, $listid);
		}

		return $subscriberid;
	}

	/**
	* IsSubscriberOnList
	* Checks whether a subscriber is on a particular list based on their email address or subscriberid and whether you are checking only for active subscribers.
	*
	* @param String $emailaddress Email address to check for.
	* @param Array $listids Lists to check on. If this is not an array, it's turned in to one for easy checking.
	* @param Int $subscriberid Subscriber id. This can be used instead of the email address.
	* @param Boolean $activeonly Whether to only check for active subscribers or not.  By default this is false - so it will not restrict searching.
	* @param Boolean $not_bounced Whether to only check for non-bounced subscribers or not. By default this is false - so it will not restrict searching.
	* @param Boolean $return_listid Whether to return the listid as well as the subscriber id. By default this is false, so it will only return the subscriberid. The bounce processing functions changes this to true, so it returns the list and the subscriber id's.
	*
	* @return Int|False Returns false if there is no such subscriber. Otherwise returns the subscriber id.
	*/
	function IsSubscriberOnList($emailaddress='', $listids=array(), $subscriberid=0, $activeonly=false, $not_bounced=false, $return_listid=false)
	{
		if (!is_array($listids)) {
			$listids = array($listids);
		}

		$listids = $this->CheckIntVars($listids);
		if (sizeof($listids) == 0) {
			return false;
		}

		$query = "SELECT subscriberid";
		if ($return_listid) {
			$query .= ", listid";
		}

		$query .= " FROM " . SENDSTUDIO_TABLEPREFIX . "list_subscribers WHERE listid IN (" . implode(',', $listids) . ")";

		if ($emailaddress) {
			$query .= " AND emailaddress='" . $this->Db->Quote($emailaddress) . "'";
		}

		if ($subscriberid) {
			$query .= " AND subscriberid='" . (int)$subscriberid . "'";
		}

		if ($activeonly) {
			$query .= " AND confirmed=1 AND unsubscribed=0";
		}

		if ($not_bounced) {
			$query .= " AND unsubscribed=0 AND bounced=0";
		}

		$result = $this->Db->Query($query);
		$row = $this->Db->Fetch($result);
		if (empty($row)) {
			return false;
		}

		if ($return_listid) {
			return $row;
		} else {
			return (int)$row['subscriberid'];
		}
	}

	/**
	* UpdateEmailAddress
	* Updates an email address for a particular subscriber id. If the email address is the same as it was previously, this will return true straight away. Otherwise it will update the database and change the emailaddress class variable.
	*
	* @param Array $subscriberids An array of subscriberid's to update. We need to pass in an array for modify details forms - that is, so we can change it across multiple lists at once. If this is not already an array, it will be turned into one.
	* @param String $emailaddress Email address to update to. If this isn't specified, uses 'this' email address.
	*
	* @see emailaddress
	*
	* @return Boolean Returns true if it worked, false otherwise.
	*/
	function UpdateEmailAddress($subscriberids=array(), $emailaddress=0)
	{
		if (!is_array($subscriberids)) {
			$subscriberids = array($subscriberids);
		}

		$subscriberids = $this->CheckIntVars($subscriberids);

		if (empty($subscriberids)) {
			return false;
		}

		if (($emailaddress == $this->emailaddress) && $emailaddress != '') {
			return true;
		}

		if (!$emailaddress) {
			$emailaddress = $this->emailaddress;
		}

		foreach ($subscriberids as $p => $subscriberid) {
			$query = "UPDATE " . SENDSTUDIO_TABLEPREFIX . "list_subscribers SET emailaddress='" . $this->Db->Quote($emailaddress) . "' WHERE subscriberid='" . $this->Db->Quote($subscriberid) . "'";
			$result = $this->Db->Query($query);
		}
		return true;
	}

	/**
	* UpdateList
	* Updates list information for a particular subscriber. Checks whether a list exists or not first before updating information. It updates the format, confirm status, confirm code.
	*
	* @param Int $subscriberid Subscriber to update.
	* @param Int $listid List to update their information on.
	*
	* @see confirmed
	* @see format
	* @see GenerateConfirmCode
	* @see Lists_API
	* @see Lists_API::Find
	*
	* @return Boolean Returns true if it worked, false if the list doesn't exist or if it didn't work.
	*/
	function UpdateList($subscriberid=0, $listid=0)
	{
		$subscriberid = (int)$subscriberid;
		$listid = (int)$listid;

		if ($subscriberid <= 0 || !$listid) {
			return false;
		}

		require_once(dirname(__FILE__) . '/lists.php');
		$list = &new Lists_API();
		$real_listid = $list->Find($listid);
		if ($real_listid['listid'] <= 0) {
			return false;
		}

		$query = "UPDATE " . SENDSTUDIO_TABLEPREFIX . "list_subscribers SET format='" . $this->Db->Quote($this->format) . "', confirmed='" . $this->Db->Quote($this->confirmed) . "' WHERE listid='" . $this->Db->Quote($listid) . "' AND subscriberid='" . $this->Db->Quote($subscriberid) . "'";

		$result = $this->Db->Query($query);
		return $result;
	}

	/**
	* IsDuplicate
	* Checks whether an email address is already on a particular list. It can ignore a particular subscriber based on their id. This is handy if you want to change other details but not your email address, otherwise this would return true even though you're not changing the email. This also helps check if you are already subscribed using a different email address apart from the one being checked (eg family members signing up for the same newsletter).
	*
	* @param String $emailaddress Email Address to check.
	* @param Int $listid List to check for duplicates on.
	* @param Int $ignore_subscriberid This excludes the 'subscriberid' mentioned. This allows you to update an email address for a subscriber on a list and make sure it doesn't return the existing (current) subscriber.
	*
	* @return Mixed Returns the duplicate subscriberid if there is a duplicate. Returns false if there isn't one.
	*/
	function IsDuplicate($emailaddress='', $listid=0, $ignore_subscriberid=0)
	{
		if ($emailaddress == '' || $listid <= 0) {
			return true;
		}

		$query = "SELECT subscriberid FROM " . SENDSTUDIO_TABLEPREFIX . "list_subscribers WHERE emailaddress='" . $this->Db->Quote($emailaddress) . "' AND listid='" . $this->Db->Quote($listid) . "'";

		if ($ignore_subscriberid) {
			$query .= " AND subscriberid != '" . $this->Db->Quote($ignore_subscriberid) . "'";
		}

		$result = $this->Db->Query($query);
		if (!$result) {
			return true;
		}

		$subscriber = $this->Db->FetchOne($result, 'subscriberid');
		if ($subscriber > 0) {
			return $subscriber;
		}

		return false;
	}

	/**
	* IsUnSubscriber
	* Checks whether an email address is an 'unsubscriber' - they have unsubscribed from a list.
	*
	* @param String $emailaddress Email Address to check.
	* @param Int $listid List to check for.
	* @param Int $subscriberid Subscriber id to check.
	*
	* @return Int|False Returns the unsubscribed id if there is one. Returns false if there isn't one.
	*/
	function IsUnSubscriber($emailaddress='', $listid=0, $subscriberid=0)
	{
		if ((!$emailaddress && $subscriberid <= 0) || $listid <= 0) {
			return false;
		}

		$query = "SELECT subscriberid FROM " . SENDSTUDIO_TABLEPREFIX . "list_subscribers WHERE listid='" . $this->Db->Quote($listid) . "' AND unsubscribed > 0 AND";

		if ($emailaddress) {
			$query .= " emailaddress='" . $this->Db->Quote($emailaddress) . "'";
		}

		if ($subscriberid) {
			$query .= " subscriberid='" . (int)$subscriberid . "'";
		}

		$result = $this->Db->Query($query);
		if (!$result) {
			return false;
		}

		$subscriber = $this->Db->FetchOne($result, 'subscriberid');
		if ($subscriber > 0) {
			return $subscriber;
		}

		return false;
	}

	/**
	* IsBounceSubscriber
	* Checks whether an email address has 'bounced' on a list.
	*
	* @param String $emailaddress Email Address to check.
	* @param Int $listid List to check for.
	*
	* @return Int|False Returns the bounced id if there is one. Returns false if there isn't one.
	*/
	function IsBounceSubscriber($emailaddress='', $listid=0)
	{
		if ($emailaddress == '' || $listid <= 0) {
			return false;
		}

		$query = "SELECT subscriberid FROM " . SENDSTUDIO_TABLEPREFIX . "list_subscribers WHERE listid='" . $this->Db->Quote($listid) . "' AND emailaddress='" . $this->Db->Quote($emailaddress) . "' AND bounced > 0";
		$result = $this->Db->Query($query);
		if (!$result) {
			return false;
		}

		$subscriber = $this->Db->FetchOne($result, 'subscriberid');
		if ($subscriber > 0) {
			return $subscriber;
		}

		return false;
	}

	/**
	* IsBannedSubscriber
	* Checks whether an email address is banned or not. Checks for a specific list or 'g'lobally.
	*
	* @param String $emailaddress Email Address to check.
	* @param Array $listids An array of listids to check and make sure they are not banned from. This will be run through CheckIntVars to make sure they are all integers, then it will add the global ban list to the array of id's to check.
	* @param Boolean $return_ids Whether to return the listid's the person is banned from subscribing or not. If this is false, an array is returned with a status message (true/false) and a reason why they are banned. If this is true, an array will be returned with the listid's the person is banned from.
	*
	* @return Mixed If return_ids is false, this will return an array with a status and a message about why they are banned (false if they are not banned). If return_ids is true, this will return a multidimensional array with the 'list' (listid) and the listname they are banned from joining.
	*/
	function IsBannedSubscriber($emailaddress='', $listids=array(), $return_ids=false)
	{
		if ($emailaddress == '') {
			return array(true, 'No email address supplied');
		}

		if (!is_array($listids)) {
			$listids = array($listids);
		}

		$listids = $this->CheckIntVars($listids);

		$listids[] = 'g';

		$domain_parts = explode('@', $emailaddress);
		if (isset($domain_parts[1])) {
			$domain = $domain_parts[1];
		} else {
			$domain = $emailaddress;
		}

		$query = "SELECT banid, be.list AS list, l.name AS listname FROM " . SENDSTUDIO_TABLEPREFIX . "banned_emails be LEFT OUTER JOIN " . SENDSTUDIO_TABLEPREFIX . "lists l ON be.list=l.listid WHERE (emailaddress='" . $this->Db->Quote($emailaddress) . "' OR emailaddress='" . $this->Db->Quote($domain) . "') AND list IN ('" . implode('\',\'', $listids) . "')";

		if (!$return_ids) {
			$query .= " LIMIT 1";
		}

		$result = $this->Db->Query($query);
		if (!$result) {
			return array(true, 'Bad Query');
		}

		$banid = 0;

		$banned_lists = array();
		while ($row = $this->Db->Fetch($result)) {
			$banid = $row['banid'];
			$banned_lists[] = array('list' => $row['list'], 'listname' => $row['listname']);
		}

		if (!$return_ids) {
			if (!$banid) {
				return array(false, true);
			}

			return array(true, GetLang('Subscriber_AlreadyBanned'));
		}
		return $banned_lists;
	}

	/**
	* ValidEmail
	* This checks whether an email address is valid or not using a series of checks and general regular expressions.
	*
	* @param String $email Email address to check.
	*
	* @return Boolean Returns true if the email address has correct syntax, otherwise false.
	*/
	function ValidEmail($email=false)
	{
		// If the email is empty it can't be valid
		if (empty($email)) {
			return false;
		}

		// If the email doesnt have exactle 1 @ it isnt valid
		if (substr_count($email, '@') != 1) {
			return false;
		}

		$matches = array();
		$local_matches = array();
		preg_match(':^([^@]+)@([a-zA-Z0-9\-][a-zA-Z0-9\-\.]{0,254})$:', $email, $matches);

		if (count($matches) != 3) {
			return false;
		}

		$local = $matches[1];
		$domain = $matches[2];

		// If the local part has a space but isnt inside quotes its invalid
		if (strpos($local, ' ') && (substr($local, 0, 1) != '"' || substr($local, -1, 1) != '"')) {
			return false;
		}

		// If there are not exactly 0 and 2 quotes
		if (substr_count($local, '"') != 0 && substr_count($local, '"') != 2) {
			return false;
		}

		// if the local part starts with a dot (.)
		if (substr($local, 0, 1) == '.' || substr($local, -1, 1) == '.') {
			return false;
		}

		// If the local string doesnt start and end with quotes
		if ((strpos($local, '"') || strpos($local, ' ')) && (substr($local, 0, 1) != '"' || substr($local, -1, 1) != '"')) {
			return false;
		}

		preg_match(':^([\ \"\w\!\#\$\%\&\'\*\+\-\/\=\?\^\_\`\{\|\}\~\.]{1,64}$):', $local, $local_matches);

		if (empty($local_matches)) {
			return false;
		}

		// Check the domain has at least 1 dot in it
		if (strpos($domain, '.') !== false) {
			return true;
		}

		return false;

	}

	/**
	* AddBannedSubscriber
	* Adds a subscriber to the 'banned' list.
	*
	* @param String $emailaddress Email Address to add to the banned list. This can either be a specific email address or a domain name.
	* @param Mixed $listid List to ban them from. This can either be an integer for a specific list or 'g' for the global list.
	*
	* @see IsBannedSubscriber
	*
	* @return Array Returns a status(true/false) whether they were added to the banned list and why.
	*/
	function AddBannedSubscriber($emailaddress='', $listid=0)
	{
		if ($emailaddress == '') {
			return array(false, 'No email address supplied');
		}

		if (!is_numeric($listid)) {
			$listid = 'g';
		}

		list($isbanned, $msg) = $this->IsBannedSubscriber($emailaddress, $listid, false);
		if ($isbanned) {
			return array(false, $msg);
		}

		$query = "INSERT INTO " . SENDSTUDIO_TABLEPREFIX . "banned_emails (emailaddress, list, bandate) VALUES ('" . $this->Db->Quote($emailaddress) . "', '" . $this->Db->Quote($listid) . "',  " . $this->GetServerTime() . ")";
		$result = $this->Db->Query($query);
		if ($result) {
			return array(true, false);
		}

		return array(false, 'Bad Query');
	}

	/**
	* RemoveBannedSubscriber
	* Remove a subscriber from the 'banned' list.
	*
	* @param Int $banid Ban to remove from the list.
	* @param Mixed $listid List to ban them from. This can either be an integer (listid) or 'g' for the global list.
	*
	* @return Array Returns a status(true/false) whether they were removed from the banned list and why.
	*/
	function RemoveBannedSubscriber($banid=0, $listid=0)
	{
		$banid = (int)$banid;
		if ($banid <= 0) {
			return array(false, 'No ban id supplied');
		}

		if (!is_numeric($listid)) {
			$listid = 'g';
		}

		$query = "DELETE FROM " . SENDSTUDIO_TABLEPREFIX . "banned_emails WHERE banid='" . $this->Db->Quote($banid) . "' AND list='" . $this->Db->Quote($listid) . "'";
		$result = $this->Db->Query($query);
		if ($result) {
			return array(true, false);
		}

		return array(false, 'Bad Query');
	}

	/**
	* DeleteSubscriber
	* Deletes a subscriber and their information from a particular list. If you specify an email address, it will use that. If there is a subscriber id, it will use that instead. If you specify an email address, it will verify they are a subscriber before going any further. It will also fix up subscribe/unsubscribe counts if applicable.
	*
	* @param String $emailaddress Email Address to delete.
	* @param Int $listid List to delete them off.
	* @param Int $subscriberid Subscriberid to delete. This is used if the email address is empty.
	*
	* @see IsSubscriberOnList
	* @see Subscribers_Manage::DeleteSubscribers
	*
	* @return Array Returns a status (success,failure) and a reason why.
	*/
	function DeleteSubscriber($emailaddress='', $listid=0, $subscriberid=0)
	{
		if ($emailaddress == '' && $subscriberid <= 0) {
			return array(false, 'No email address or subscriber id');
		}

		if ($subscriberid == 0 && $emailaddress != '') {
			$subscriberid = $this->IsSubscriberOnList($emailaddress, $listid);
			if (!$subscriberid) {
				return array(false, sprintf(GetLang('Subscriber_NotSubscribed'), $emailaddress));
			}
		}

		$query = "SELECT unsubscribed, bounced, listid FROM " . SENDSTUDIO_TABLEPREFIX . "list_subscribers WHERE subscriberid='" . $this->Db->Quote($subscriberid) . "'";

		if ($listid) {
			$query .= " AND listid='" . $this->Db->Quote($listid) . "'";
		}

		$unsubscribe_result = $this->Db->Query($query);
		$unsubscribe_info = $this->Db->Fetch($unsubscribe_result);
		$unsub_date = $unsubscribe_info['unsubscribed'];
		$bounce_date = $unsubscribe_info['bounced'];
		$listid = $unsubscribe_info['listid'];

		if (!$listid) {
			return array(true, false);
		}

		// if they were previously unsubscribed, we need to fix up the list counts.
		if ($unsub_date > 0) {
			$query = "UPDATE " . SENDSTUDIO_TABLEPREFIX . "lists SET unsubscribecount = unsubscribecount - 1 WHERE listid='" . $this->Db->Quote($listid) . "'";
		} elseif ($bounce_date > 0) {
			$query = "UPDATE " . SENDSTUDIO_TABLEPREFIX . "lists SET bouncecount = bouncecount - 1 WHERE listid='" . $this->Db->Quote($listid) . "'";
		} else {
			$query = "UPDATE " . SENDSTUDIO_TABLEPREFIX . "lists SET subscribecount = subscribecount - 1 WHERE listid='" . $this->Db->Quote($listid) . "'";
		}
		$this->Db->Query($query);

		$query = "DELETE FROM " . SENDSTUDIO_TABLEPREFIX . "list_subscribers WHERE subscriberid='" . $this->Db->Quote($subscriberid) . "' AND listid='" . $this->Db->Quote($listid) . "'";

		$this->Db->Query($query);

		$query = "DELETE FROM " . SENDSTUDIO_TABLEPREFIX . "queues WHERE recipient='" . $subscriberid . "'";
		$this->Db->Query($query);

		return array(true, false);
	}

	/**
	* ChangeSubscriberFormat
	* Change a particular subscribers format for a list.
	*
	* @param String $format Format to change them to.
	* @param Int $listid List to change them for.
	* @param Int $subscriberid Subscriberid to change.
	*
	* @see Subscribers_Manage::ChangeFormat
	*
	* @return Array Returns a status (success,failure) and a reason why.
	*/
	function ChangeSubscriberFormat($format='html', $subscriberid=0)
	{
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

		$query = "UPDATE " . SENDSTUDIO_TABLEPREFIX . "list_subscribers SET format='" . $format . "' WHERE subscriberid='" . $this->Db->Quote($subscriberid) . "'";

		$this->Db->Query($query);
		return array(true, false);
	}

	/**
	* ChangeSubscriberConfirm
	* Change a particular subscribers confirmation status. You can do it for a subscriber on a list, you can do it for a subscriber in general (all lists - in case they signed up to multiple lists on a form).
	*
	* @param String $status Status to change them to.
	* @param Int $listid List to change them for.
	* @param Int $subscriberid Subscriberid to change.
	*
	* @see Subscribers_Manage::ChangeStatus
	*
	* @return Array Returns a status (success,failure) and a reason why.
	*/
	function ChangeSubscriberConfirm($status='confirm', $listid=0, $subscriberid=0)
	{
		$status = strtolower($status);
		if ($status == 'confirm') {
			$status = 'c';
		}

		if ($status == 'unconfirm') {
			$status = 'u';
		}

		if ($status != 'c' && $status != 'u') {
			return array(false, 'Invalid Status supplied');
		}

		if ($status == 'c') {
			$status = '1';
		}

		if ($status == 'u') {
			$status = '0';
		}

		if ($listid) {
			$query = "UPDATE " . SENDSTUDIO_TABLEPREFIX . "list_subscribers SET confirmed='" . $this->Db->Quote($status) . "' WHERE subscriberid='" . $this->Db->Quote($subscriberid) . "' AND listid='" . $this->Db->Quote($listid) . "'";
		} else {
			$query = "UPDATE " . SENDSTUDIO_TABLEPREFIX . "list_subscribers SET confirmed='" . $this->Db->Quote($status) . "' WHERE subscriberid='" . $this->Db->Quote($subscriberid) . "'";
		}

		$this->Db->Query($query);
		return array(true, false);
	}

	/**
	* ListConfirm
	* Updates a subscribers' status on a particular list to mark them as confirmed.
	* This is used as part of the form confirm process.
	* Checks whether the subscriber is on the list at all in the first place.
	* When you confirm your subscription it re-adds you to any autoresponders the list has set up.
	* This is done so if you are unconfirmed when the autoresponder cron job runs, you won't get the autoresponder because you (most likely) won't meet the autoresponder criteria.
	*
	* @param Int $listid List to confirm the subscriber on.
	* @param Int $subscriberid Subscriber id to confirm
	*
	* @see IsSubscriberOnList
	* @see AddToListAutoresponders
	*
	* @return Array Returns a status (success, failure) and a reason why.
	*/
	function ListConfirm($listid=0, $subscriberid=0)
	{
		if (!$this->IsSubscriberOnList(false, $listid, $subscriberid)) {
			return array(false, sprintf(GetLang('Subscriber_NotSubscribed'), $subscriberid));
		}

		$query = "UPDATE " . SENDSTUDIO_TABLEPREFIX. "list_subscribers SET confirmed=1, confirmip='" . $this->Db->Quote($this->confirmip) . "', confirmdate='" . (int)$this->confirmdate . "', subscribedate='" . (int)$this->confirmdate . "' WHERE listid='" . (int)$listid . "' AND subscriberid='" . (int)$subscriberid . "'";
		$result = $this->Db->Query($query);

		if (!$result) {
			return array(false, true);
		}

		$this->AddToListAutoresponders($subscriberid, $listid);

		return array(true, false);
	}

	/**
	* UnsubscribeSubscriber
	* Unsubscribes an email address from a particular list. Makes sure the email address is subscribed to a list before going any further. It updates the list statistics if the person has made a request previously and this is confirming that unsubscribe request.
	*
	* @param String $emailaddress Subscriber's email address to unsubscribe.
	* @param Int $listid List to remove them from.
	* @param Int $subscriberid Subscriberid to remove.
	* @param Boolean $skipcheck Whether to skip the check to make sure they are on the list.
	* @param String $statstype The type of statistic we're updating (send/autoresponder)
	* @param Int $statid The statistics id we're updating so we can see (through stats) the number of people who have unsubscribed directly from a send/autoresponder
	*
	* @see UnsubscribeRequest
	* @see IsSubscriberOnList
	*
	* @return Array Returns a status (success,failure) and a reason why.
	*/
	function UnsubscribeSubscriber($emailaddress='', $listid=0, $subscriberid=0, $skipcheck=false, $statstype=false, $statid=0)
	{
		if (($emailaddress == '' && $subscriberid <= 0) || $listid <= 0) {
			return array(false, 'No List or email address');
		}

		if (!$skipcheck) {
			$subscriberid = $this->IsSubscriberOnList($emailaddress, $listid);
			if (!$subscriberid) {
				return array(false, sprintf(GetLang('Subscriber_NotSubscribed'), $emailaddress));
			}
		}

		$unsubscribetime = $this->GetServerTime();

		$subscriberid = (int)$subscriberid;
		$listid = (int)$listid;

		// fix up the list totals.
		$query = "UPDATE " . SENDSTUDIO_TABLEPREFIX . "lists SET unsubscribecount = unsubscribecount + 1, subscribecount = subscribecount - 1 WHERE listid='" . $listid . "'";
		$this->Db->Query($query);

		$query = "UPDATE " . SENDSTUDIO_TABLEPREFIX . "list_subscribers SET unsubscribed='" . $this->Db->Quote($unsubscribetime) . "', unsubscribeconfirmed=1 WHERE listid='" . $listid . "' AND subscriberid='" . $subscriberid . "'";
		$this->Db->Query($query);

		$unsub_requestdate = 0;
		$unsub_requestip = '';
		// load up the request date/ip for the unsubscribe.
		$query = "SELECT unsubscriberequesttime, unsubscriberequestip FROM " . SENDSTUDIO_TABLEPREFIX . "list_subscribers_unsubscribe WHERE subscriberid='" . $subscriberid . "' AND listid='" . $listid . "'";
		$result = $this->Db->Query($query);
		$row = $this->Db->Fetch($result);
		if (!empty($row)) {
			$unsub_requestdate = (int)$row['unsubscriberequesttime'];
			$unsub_requestip = $row['unsubscriberequestip'];
		}

		// delete the old request (if applicable).
		$query = "DELETE FROM " . SENDSTUDIO_TABLEPREFIX . "list_subscribers_unsubscribe WHERE subscriberid='" . $subscriberid . "' AND listid='" . $listid . "'";
		$this->Db->Query($query);

		if (!$this->unsubscribeip) {
			$this->unsubscribeip = $this->GetRealIp();
		}

		if (!$statstype) {
			$statstype = 'f'; // f = form.
		}

		$query = "INSERT INTO " . SENDSTUDIO_TABLEPREFIX . "list_subscribers_unsubscribe (subscriberid, unsubscribetime, listid, unsubscribeip, unsubscriberequesttime, unsubscriberequestip, statid, unsubscribearea) VALUES ('" . $subscriberid . "', " . $this->Db->Quote($unsubscribetime) . ", '" . $listid . "', '" . $this->Db->Quote($this->unsubscribeip) . "', '" . $unsub_requestdate . "', '" . $this->Db->Quote($unsub_requestip) . "', '" . (int)$statid . "', '" . $this->Db->Quote(strtolower(substr($statstype, 0, 1))) . "')";
		$this->Db->Query($query);

		return array(true, false);
	}

	/**
	* BounceSubscriber
	* Bounces an email address from a particular list. Makes sure the email address is subscribed to a list before going any further.
	*
	* @param String $emailaddress Subscriber's email address to bounce.
	* @param Int $listid List to remove them from.
	* @param Int $subscriberid The subscriber's id from the database. If this is supplied, then it is used and the email address is not checked (ie it assumes you have already checked it's valid).
	* @param Boolean $already_bounced Whether the email address has already been marked as a bounce message. This is true if bounced by the 'RecordBounceInfo' function, but not if manually bounced from a mailing list.
	*
	* @see IsSubscriberOnList
	* @see RecordBounceInfo
	*
	* @return Array Returns a status (success,failure) and a reason why.
	*/
	function BounceSubscriber($emailaddress=false, $listid=0, $subscriberid=0, $bouncetime=0, $already_bounced=false)
	{
		$subscriberid = (int)$subscriberid;
		$bouncetime = (int)$bouncetime;
		if ($bouncetime <= 0) {
			$bouncetime = $this->GetServerTime();
		}

		if (!$emailaddress && $subscriberid <= 0) {
			return array(false, 'No email address supplied');
		}

		if ($listid <= 0) {
			return array(false, 'No List supplied');
		}

		// if we're passing in a subscriberid, don't do this check.
		if ($subscriberid <= 0) {
			$subscriberid = $this->IsSubscriberOnList($emailaddress, $listid);
			if (!$subscriberid) {
				return array(false, sprintf(GetLang('Subscriber_NotSubscribed'), $emailaddress));
			}
		}

		$query = "UPDATE " . SENDSTUDIO_TABLEPREFIX . "list_subscribers SET bounced='" . $this->Db->Quote($bouncetime) . "' WHERE listid='" . $this->Db->Quote($listid) . "' AND subscriberid='" . $this->Db->Quote($subscriberid) . "'";
		$this->Db->Query($query);

		if (!$already_bounced) {
			$query = "INSERT INTO " . SENDSTUDIO_TABLEPREFIX . "list_subscriber_bounces (subscriberid, bouncetime, listid, statid, bouncetype, bouncerule, bouncemessage) VALUES ('" . $this->Db->Quote($subscriberid) . "', " . $this->Db->Quote($bouncetime) . ", '" . $this->Db->Quote($listid) . "', 0, 'unknown', 'unknown', 'Manually Bounced')";
			$this->Db->Query($query);
		}

		$query = "UPDATE " . SENDSTUDIO_TABLEPREFIX . "lists SET bouncecount=bouncecount + 1, subscribecount=subscribecount - 1 WHERE listid='" . $this->Db->Quote($listid) . "'";
		$this->Db->Query($query);

		return array(true, false);
	}

	/**
	* AlreadyBounced
	* Check whether a bounce has already been recorded for the statid, listid and subscriberid passed in.
	* This is only used by bounce processing and should stop the same bounce information from being recorded if a bounce gets interrupted (otherwise it would be recorded again and again).
	* A bounce should only be recorded ONCE per statid & listid & subscriberid.
	* Autoresponders & newsletters share the same sequence so we don't need to check the type of bounce it was coming from.
	*
	* @param Int $subscriberid Subscriberid to check.
	* @param Int $statid Statid to check.
	* @param Int $listid Listid to check.
	*
	* @return Mixed Returns the bounceid if a bounce has already been recorded. Returns false if one has not been recorded.
	*/
	function AlreadyBounced($subscriberid=0, $statid=0, $listid=0)
	{
		$query = "SELECT bounceid FROM " . SENDSTUDIO_TABLEPREFIX . "list_subscriber_bounces WHERE subscriberid='" . (int)$subscriberid . "' AND statid='" . (int)$statid . "' AND listid='" . (int)$listid . "' LIMIT 1";
		$result = $this->Db->Query($query);
		$bid = $this->Db->FetchOne($result, 'bounceid');
		if ($bid > 0) {
			return $bid;
		}
		return false;
	}

	/**
	* RecordBounceInfo
	* This will save bounce information against the subscriber and also work out whether they have bounced too many times in the last period of time.
	* If they have bounced less than $this->softbounce_count, the bounce is only recorded.
	* If they have bounced more than or equal to $this->softbounce_count, then they are marked as bounced on the list and are made inactive.
	*
	* @param Int $subscriberid Subscriber id to bounce / check. This is not checked to make sure it is valid, it has been done already by the calling function.
	* @param Int $bounce_statid The bounce statistics id to record this particular bounce against.
	* @param Int $bounce_listid The list id the subscriber is bouncing from.
	* @param String $bounce_type The type of bounce this matched. This is stored in the database for 'historical' reasons.
	* @param String $bounce_rule The bounce rule this matched. This is stored in the database for 'historical' reasons.
	* @param String $bounce_message The entire bounce message. This is stored in the database for 'historical' reasons.
	* @param Int $bounce_time The time of the bounce. If this is not passed in, it is assumed to be 'now'.
	*
	* @see softbounce_count
	*
	* @return Boolean Returns true if the subscriber has been completely bounced from the mailing list and made inactive. Returns false if they have not been made inactive.
	*/
	function RecordBounceInfo($subscriberid=0, $bounce_statid=0, $bounce_listid=0, $bounce_type='', $bounce_rule='', $bounce_message='', $bounce_time=0)
	{
		$subscriberid = (int)$subscriberid;
		$bounce_statid = (int)$bounce_statid;
		$bounce_listid = (int)$bounce_listid;
		$bounce_time = (int)$bounce_time;

		if ($bounce_time <= 0) {
			$bounce_time = time();
		}

		$query = "INSERT INTO " . SENDSTUDIO_TABLEPREFIX . "list_subscriber_bounces(subscriberid, statid, listid, bouncetime, bouncetype, bouncerule, bouncemessage) VALUES (" . $subscriberid . ", " . $bounce_statid . ", " . $bounce_listid . ", " . $bounce_time . ", '" . $this->Db->Quote($bounce_type) . "', '" . $this->Db->Quote($bounce_rule) . "', '" . $this->Db->Quote($bounce_message) . "')";
		$result = $this->Db->Query($query);

		$query = "SELECT COUNT(*) AS count FROM " . SENDSTUDIO_TABLEPREFIX . "list_subscriber_bounces WHERE subscriberid='" . $subscriberid . "'";
		$result = $this->Db->Query($query);
		$bounce_count = $this->Db->FetchOne($result, 'count');

		if ($bounce_count >= $this->softbounce_count || $bounce_type == 'hard') {
			$this->BounceSubscriber(false, $bounce_listid, $subscriberid, $bounce_time, true);
			return true;
		}

		return false;
	}

	/**
	* ActivateSubscriber
	* Re-activates a subscriber and removes them from the 'bounce' and 'unsubscribe' lists. It will also update list subscribe/unsubscribe counts appropriately.
	*
	* @param String $emailaddress Subscriber's email address to re-activate.
	* @param Int $listid List to activate them on.
	*
	* @see IsSubscriberOnList
	*
	* @return Array Returns a status (success,failure) and a reason why.
	*/
	function ActivateSubscriber($emailaddress='', $listid=0)
	{
		if ($emailaddress == '' || $listid <= 0) {
			return array(false, 'No List or email address');
		}

		$subscriberid = $this->IsSubscriberOnList($emailaddress, $listid);
		if (!$subscriberid) {
			return array(false, sprintf(GetLang('Subscriber_NotSubscribed'), $emailaddress));
		}

		$query = "SELECT unsubscribed, bounced FROM " . SENDSTUDIO_TABLEPREFIX . "list_subscribers WHERE subscriberid='" . $this->Db->Quote($subscriberid) . "' AND listid='" . $this->Db->Quote($listid) . "'";
		$result = $this->Db->Query($query);
		$row = $this->Db->Fetch($result);

		// if they were previously unsubscribed, we need to fix up the list counts.
		if ($row['unsubscribed'] > 0) {
			$query = "UPDATE " . SENDSTUDIO_TABLEPREFIX . "lists SET unsubscribecount = unsubscribecount - 1, subscribecount = subscribecount + 1 WHERE listid='" . $this->Db->Quote($listid) . "'";
			$this->Db->Query($query);
		}

		// if they were previously bounced, we need to fix up the list counts.
		if ($row['bounced'] > 0) {
			$query = "UPDATE " . SENDSTUDIO_TABLEPREFIX . "lists SET bouncecount = bouncecount - 1, subscribecount = subscribecount + 1 WHERE listid='" . $this->Db->Quote($listid) . "'";
			$this->Db->Query($query);
		}

		$query = "DELETE FROM " . SENDSTUDIO_TABLEPREFIX . "list_subscribers_unsubscribe WHERE subscriberid='" . $this->Db->Quote($subscriberid) . "' AND listid='" . $this->Db->Quote($listid) . "'";
		$this->Db->Query($query);

		$query = "DELETE FROM " . SENDSTUDIO_TABLEPREFIX . "list_subscriber_bounces WHERE subscriberid='" . $this->Db->Quote($subscriberid) . "' AND listid='" . $this->Db->Quote($listid) . "'";
		$this->Db->Query($query);

		$query = "UPDATE " . SENDSTUDIO_TABLEPREFIX . "list_subscribers SET bounced=0, unsubscribed=0 WHERE listid='" . $this->Db->Quote($listid) . "' AND subscriberid='" . $this->Db->Quote($subscriberid) . "'";
		$this->Db->Query($query);

		return array(true, false);

	}


	/**
	* GenerateConfirmCode
	* Generates a random string to use as a confirmation code.
	*
	* @see confirmcode
	*
	* @return String Returns an md5 sum confirmation code.
	*/
	function GenerateConfirmCode()
	{
		if ($this->confirmcode) {
			return $this->confirmcode;
		}

		$code = md5(uniqid(rand(), true));
		$this->confirmcode = $code;
		return $code;
	}

	/**
	* GenerateSubscriberSubQuery
	* Generates the queries involved both for fetching & getting subscribers
	* It uses information in the $searchinfo array passed in to generate the query.
	* This info should include the list, link, newsletter, status etc you are searching for.
	*
	* @param Array $searchinfo The information to create the search query.
	* @param Array $sortdetails The sort information to add to the end of the query.
	* @param Boolean $queueonly Whether the calling function is queuing the results from this query or not. This will stop the first part of the query ("SELECT") from being returned.
	*
	* @see FetchSubscribers
	* @see GetSubscribers
	* @see _GenerateDateSubQuery
	* @see _GenerateSubscribeDateSubQuery
	* @see _CreateCustomFieldSubquery
	* @see _CreateNumberQuery
	*
	* @return Array Returns an array of 'search_query' and 'count_query'.
	*/
	function GenerateSubscriberSubQuery($searchinfo, $sortdetails, $queueonly=false)
	{
		// list is always used from the searchinfo array passed in because the calling function checks which lists a user has access to already.
		$list = $searchinfo['List'];

		$tables = array();

		$tables['l'] = 'list_subscribers';

		$tablejoins = array();
		if (!is_array($list) && $list == 'any') {
			$tables['ml'] = 'lists';
			$tablejoins[] = 'l.listid=ml.listid';
		}

		if (is_array($list)) {
			$list = $this->CheckIntVars($list);
		}

		$subqueries = array();

		$custom_fields_found = false;
		$need_distinct = false;

		$customfield_subqueries = $this->_CreateCustomFieldSubquery($searchinfo);

		if (!empty($customfield_subqueries)) {
			$custom_fields_found = true;
		}

		if (isset($searchinfo['Link'])) {
			$tables['lc'] = 'stats_linkclicks';
			$tablejoins[] = 'l.subscriberid=lc.subscriberid';
			if ($searchinfo['Link'] > -1) {
				$tablejoins[] = 'lc.linkid=' . (int)$searchinfo['Link'];
			}
			$need_distinct = true;
		}

		if (isset($searchinfo['Newsletter'])) {
			$tables['seo'] = 'stats_emailopens';
			$tables['sn'] = 'stats_newsletters';
			$tablejoins[] = 'l.subscriberid=seo.subscriberid';
			$tablejoins[] = 'sn.statid=seo.statid';
			if ($searchinfo['Newsletter'] > -1) {
				$tablejoins[] = 'sn.newsletterid=' . (int)$searchinfo['Newsletter'];
			}
			$need_distinct = true;
		}

		$count_query = "SELECT COUNT(";
		if ($need_distinct) {
			$count_query .= "DISTINCT l.subscriberid";
		} else {
			$count_query .= "*";
		}
		$count_query .= ") AS count FROM ";

		$search_query = "SELECT ";
		if ($need_distinct) {
			$search_query .= "DISTINCT ";
		}
		$search_query .= " l.subscriberid, l.emailaddress, l.format, l.subscribedate, l.confirmed, l.unsubscribed, l.bounced, l.listid";

		if (!is_array($list) && $list == 'any') {
			$search_query .= ", ml.name AS listname";
		}

		// if we are queueing only, we only need the "from" and the other details. The rest are worked out by the calling function.
		if ($queueonly) {
			$search_query = "";
		}

		$search_query .= " FROM ";

		$total_subquery = " ";

		foreach ($tables as $tableprefix => $tablename) {
			$total_subquery .= SENDSTUDIO_TABLEPREFIX . $tablename . " " . $tableprefix . ", ";
		}
		$total_subquery = substr($total_subquery, 0, -2);

		if (empty($tablejoins)) {
			$tablejoins[] = '1=1';
		}

		$total_subquery .= " WHERE " . implode(' AND ', $tablejoins);

		if (!empty($subqueries)) {
			$total_subquery .= " AND " . implode(' AND ', $subqueries);
		}

		if (isset($searchinfo['Format'])) {
			$total_subquery .= " AND l.format='" . $this->Db->Quote($searchinfo['Format']) . "'";
		}

		if (isset($searchinfo['Email'])) {
			$total_subquery .= " AND l.emailaddress LIKE '%" . $this->Db->Quote($searchinfo['Email']) . "%'";
		}

		if (isset($searchinfo['Status'])) {
			switch (strtolower($searchinfo['Status'])) {
				case 'b':
					$total_subquery .= " AND (l.bounced > 0)";
				break;

				case 'u':
					$total_subquery .= " AND (l.unsubscribed > 0)";
				break;

				case '-1':
					$total_subquery .= "";
				break;

				case 'a':
				default:
					$total_subquery .= " AND (l.unsubscribed=0 AND l.bounced=0)";
			}
		}

		if (isset($searchinfo['Confirmed'])) {
			switch (strtolower($searchinfo['Confirmed'])) {
				case '1':
					$total_subquery .= " AND confirmed=1";
				break;
				case '0':
					$total_subquery .= " AND confirmed=0";
				break;
			}
		}

		if (isset($searchinfo['Subscriber'])) {
			$total_subquery .= " AND l.subscriberid='" . (int)$searchinfo['Subscriber'] . "'";
		}

		$list = $searchinfo['List'];

		if (isset($searchinfo['AvailableLists'])) {
			$searchinfo['AvailableLists'] = $this->CheckIntVars($searchinfo['AvailableLists']);
			if (empty($searchinfo['AvailableLists'])) {
				$searchinfo['AvailableLists'] = array('0');
			}
			$list = $searchinfo['AvailableLists'];
		}

		if (is_array($list)) {
			$total_subquery .= " AND l.listid IN (" . implode(',', $list) . ")";
		} else {
			if ($list != 'any') {
				$total_subquery .= " AND l.listid='" . $this->Db->Quote($list) . "'";
			}
		}


		if (isset($searchinfo['DateSearch'])) {
			$total_subquery .= " AND " . $this->_GenerateSubscribeDateSubQuery($searchinfo['DateSearch']);
		}

		if ($custom_fields_found) {
			$customfield_check_query = "SELECT d.subscriberid FROM " . SENDSTUDIO_TABLEPREFIX . "subscribers_data d WHERE " . implode(' OR ', $customfield_subqueries) . " GROUP BY d.subscriberid HAVING COUNT(d.subscriberid)=" . sizeof($customfield_subqueries);

			if (SENDSTUDIO_DATABASE_TYPE == 'pgsql') {
				$total_subquery .= " AND l.subscriberid IN (" . $customfield_check_query . ")";
			} else {
				$customfield_subscribers = array('0');
				$customfield_check_result = $this->Db->Query($customfield_check_query);
				while ($customfield_check_row = $this->Db->Fetch($customfield_check_result)) {
					$customfield_subscribers[] = (int)$customfield_check_row['subscriberid'];
				}
				$total_subquery .= " AND l.subscriberid IN (" . implode(',', $customfield_subscribers) . ")";
			}
		}

		if (strtolower($sortdetails['SortBy']) == 'status') {
			$sortdetails['SortBy'] = 'CASE WHEN (bounced=0 AND unsubscribed=0) THEN 1 WHEN (unsubscribed > 0) THEN 2 WHEN (bounced > 0) THEN 3 END';
		}

		$count_query .= $total_subquery;

		$search_query .= $total_subquery;



		$search_query .= " ORDER BY " . $sortdetails['SortBy'] . " " . $sortdetails['Direction'];

		if (strtolower($sortdetails['SortBy']) != 'emailaddress') {
			$search_query .= ", emailaddress";
		}
		return array('count_query' => $count_query, 'search_query' => $search_query);
	}

	/**
	* _CreateCustomFieldSubquery
	* Creates custom field queries based on the field types etc that are present in the information passed in.
	*
	* @param Array $searchinfo The information to generate a custom field query from.
	*
	* @see _CreateNumberQuery
	*
	* @return Array Returns an array of subqueries to put together.
	*/
	function _CreateCustomFieldSubquery($searchinfo=array())
	{
		$customfield_subqueries = array();
		if (isset($searchinfo['CustomFields']) && !empty($searchinfo['CustomFields'])) {
			foreach ($searchinfo['CustomFields'] as $fieldid => $fielddata) {
				if ($fielddata != "") {

					$fieldtype = $this->GetCustomFieldType($fieldid);

					switch ($fieldtype) {
						case 'date':
							$subquery = $this->_GenerateDateSubQuery($fielddata, $fieldid);

							// if we don't have "filter" set, then we're not filtering by this field.
							if (!$subquery) {
								break;
							}

							$customfield_subqueries[] = $subquery;
						break;

						case 'number':
							$customfield_subqueries[] = $this->_CreateNumberQuery($fieldid, $fielddata);
						break;

						case 'checkbox':
							$fielddata_queries = array();
							if (is_array($fielddata)) {
								foreach ($fielddata as $k => $p) {
									// hand "serialize" the string so we can find it reasonably easily.
									$newfielddata = 's:' . strlen($k) . ':"' . $this->Db->Quote($k) . '";';
									$fielddata_queries[] = "(d.fieldid='" . $this->Db->Quote($fieldid) . "' AND d.data LIKE '%" . $newfielddata . "%')";
								}
							}
							if (!empty($fielddata_queries)) {
								$customfield_subqueries[] = '(' . implode(' AND ', $fielddata_queries) . ')';
							}
						break;

						case 'dropdown':
						case 'radiobutton':
							$customfield_subqueries[] = "(d.fieldid='" . $this->Db->Quote($fieldid) . "' AND d.data='" . $this->Db->Quote($fielddata) . "')";
						break;

						default:
							$customfield_subqueries[] = "(d.fieldid='" . $this->Db->Quote($fieldid) . "' AND d.data LIKE '%" . $this->Db->Quote($fielddata) . "%')";
					}
				}
			}
		}
		return $customfield_subqueries;
	}

	/**
	* Creates a subquery for a number custom field based on the information passed in. This allows number fields to use '>', '<' and '=' in their search criteria.
	*
	* @var Int $fieldid The field we are creating the search subquery for.
	* @var String $fielddata The data we want to search for.
	*
	* @see _CreateCustomFieldSubquery
	*
	* @return String Returns the subquery for number searching after it has been cleaned up. Anything not one of these characters is stripped out.
	*/
	function _CreateNumberQuery($fieldid=0, $fielddata='')
	{
		$fieldid = (int)$fieldid;
		if (!$fielddata || $fieldid <= 0) {
			return '';
		}

		// get rid of anything that isn't a number, isn't a space, isn't a
		// <, >, =, |, &, and, or.
		$fielddata = preg_replace('%[^\d\s|&><=(and|or)]+%', '', $fielddata);

		// get rid of multiple spaces between numbers.
		$fielddata = preg_replace('%([\d])\s+([\d])%', '\\1\\2', $fielddata);

		if (preg_match('%[><=]+%', $fielddata)) {
			$subq = "(d.fieldid=" . $fieldid . " AND d.data";

			if (preg_match('%[^0-9]+%', $fielddata)) {
				$fielddata = strtolower($fielddata);
				$fielddata = str_replace(' && ', ' && d.data ', $fielddata);
				$fielddata = str_replace(' || ', ' || d.data ', $fielddata);
				$fielddata = str_replace(' and ', ' and d.data ', $fielddata);
				$fielddata = str_replace(' or ', ' or d.data ', $fielddata);

				// get rid of anything not a number on the end of the query.
				$fielddata = preg_replace('/[^\d]+$/', '', trim($fielddata));

				$subq .= $fielddata;
			} else {
				$subq .= "='" . (int)$fielddata."'";
			}
			return $subq . ")";
		}
		return "(d.fieldid=" . $fieldid . " AND d.data=" . (int)$fielddata . ")";
	}

	/**
	* GetSubscribers
	* Returns a list of subscriber id's based on the information passed in.
	* This is used to create a queue for exporting or sending to particular subscribers.
	* If the queue details are passed in, then this will put the subscribers who match the criteria straight into the queue instead of returning them.
	*
	* @param Array $searchinfo An array of search information to restrict searching to. This is used to construct queries to cut down the subscribers found.
	* @param Array $sortdetails How to sort the resulting subscriber information.
	* @param Boolean $countonly Whether to only do a count or get the list of subscribers as well.
	* @param Array $queuedetails If this is not an empty array, the subscribers returned from the query are put directly into this queue (based on the array fields).
	*
	* @see CreateQueue
	* @see FetchSubscribers
	* @see _GenerateDateSubQuery
	* @see _GenerateSubscribeDateSubQuery
	* @see GenerateSubscriberSubQuery
	*
	* @return Mixed This will return the count only if that is set to true. Otherwise this will return an array of data including the count and the subscriber list.
	*/
	function GetSubscribers($searchinfo=array(), $sortdetails=array(), $countonly=false, $queuedetails=array())
	{
		if (empty($searchinfo)) {
			return array('count' => 0, 'subscribers' => array());
		}

		if (empty($sortdetails)) {
			$sortdetails = array('SortBy' => 'emailaddress', 'Direction' => 'asc');
		}

		$search_query = "";
		$queueonly = false;

		$analyze = false;

		if (!empty($queuedetails)) {
			$search_query = "INSERT INTO " . SENDSTUDIO_TABLEPREFIX . "queues (queueid, queuetype, ownerid, recipient, processed) SELECT " . $queuedetails['queueid'] . ", '" . $queuedetails['queuetype'] . "', '" . $queuedetails['ownerid'] . "', l.subscriberid, 0";
			$queueonly = true;
			$analyze = true;
		}

		$queries = $this->GenerateSubscriberSubQuery($searchinfo, $sortdetails, $queueonly);

		$count_query = $queries['count_query'];
		$search_query .= $queries['search_query'];

		$count_result = $this->Db->Query($count_query);
		$count = $this->Db->FetchOne($count_result, 'count');

		if ($countonly) {
			return $count;
		}

		$search_result = $this->Db->Query($search_query);

		if ($analyze) {
			$this->Db->OptimizeTable(SENDSTUDIO_TABLEPREFIX . "queues");
		}

		$subscriber_results = array();
		while ($row = $this->Db->Fetch($search_result)) {
			$subscriber_results[] = $row;
		}

		$return = array();
		$return['count'] = $count;
		$return['subscriberlist'] = $subscriber_results;
		return $return;
	}

	/**
	* FetchSubscribers
	* Returns all subscriber information based on the criteria passed in.
	* Customfield restrictions are also checked if they are present in the $searchnfo array.
	* The difference between this function and GetSubscribers is the return type, and GetSubscribers can also insert directly into a queue (for exporting or sending). This will only return an array of 'count' and the subscriber list (even if it's empty).
	*
	* <b>Example</b>
	* <code>
	* $return_array = array('count' => $count, 'subscribers' => $subscriber_list_array);
	* </code>
	*
	* @param Int $pageid Which 'page' of results to return. Used with perpage it handles paging of results.
	* @param Int $perpage How many results to return.
	* @param Array $searchinfo An array of search information to restrict searching to. This is used to construct queries to cut down the subscribers found.
	* @param Array $sortdetails How to sort the resulting subscriber information.
	*
	* @see GetSubscribers
	* @see GenerateSubscriberSubQuery
	*
	* @return Array Returns an empty array if there is no search info or no subscribers found. Otherwise returns subscriber info based on the criteria.
	*/
	function FetchSubscribers($pageid=1, $perpage=20, $searchinfo=array(), $sortdetails=array())
	{
		if ($pageid < 1) {
			$pageid = 1;
		}

		if ($perpage <= 0) {
			$perpage = 20;
		}

		if (empty($searchinfo)) {
			return array('count' => 0, 'subscribers' => array());
		}

		if (empty($sortdetails)) {
			$sortdetails = array('SortBy' => 'emailaddress', 'Direction' => 'asc');
		}

		$queries = $this->GenerateSubscriberSubQuery($searchinfo, $sortdetails);

		$search_query = $queries['search_query'] . $this->Db->AddLimit((($pageid - 1) * $perpage), $perpage);

		$count_query = $queries['count_query'];

		$count_result = $this->Db->Query($count_query);
		$count = $this->Db->FetchOne($count_result, 'count');

		if ($perpage > 0) {
			$search_result = $this->Db->Query($search_query);
			$subscriber_results = array();
			while ($row = $this->Db->Fetch($search_result)) {
				$subscriber_results[] = $row;
			}
		} else {
			$subscriber_results = array();
		}

		$return = array();
		$return['count'] = $count;
		$return['subscriberlist'] = $subscriber_results;
		return $return;
	}

	/**
	* FetchBannedSubscribers
	* Returns all subscriber information based on the criteria passed in.
	*
	* @param Int $pageid Which 'page' of results to return. Used with perpage it handles paging of results.
	* @param Int $perpage How many results to return.
	* @param Array $searchinfo An array of search information to restrict searching to. This is used to construct queries to cut down the banned subscribers found.
	* @param Array $sortdetails How to sort the resulting banned subscriber information.
	*
	* @return Array Returns an empty array if there is no search info or no subscribers found. Otherwise returns banned subscriber info based on the criteria. Always contains both a count and a list.
	* <b>Example</b>
	* <code>
	* $return_array = array('count' => $count, 'subscribers' => $subscriber_list_array());
	* </code>
	*/
	function FetchBannedSubscribers($pageid=1, $perpage=10, $searchinfo=array(), $sortdetails=array())
	{
		if ((int)$pageid < 1) {
			$pageid = 1;
		}

		if ((int)$perpage <= 0) {
			$perpage = 10;
		}

		if (empty($searchinfo)) {
			return array('count' => 0, 'subscribers' => array());
		}

		if (empty($sortdetails)) {
			$sortdetails = array('SortBy' => 'emailaddress', 'Direction' => 'asc');
		}

		$count_query = "SELECT COUNT(*) AS count FROM " . SENDSTUDIO_TABLEPREFIX . "banned_emails WHERE ";

		$search_query = "SELECT banid, emailaddress, list, bandate FROM " . SENDSTUDIO_TABLEPREFIX . "banned_emails WHERE ";

		$total_subquery = "";

		if (!is_numeric($searchinfo['List'])) {
			$total_subquery .= "list='g'";
		} else {
			$total_subquery .= "list='" . (int)$searchinfo['List'] . "'";
		}

		$count_query .= $total_subquery;

		$search_query .= $total_subquery;

		$search_query .= " ORDER BY " . $sortdetails['SortBy'] . " " . $sortdetails['Direction'];
		$search_query .= $this->Db->AddLimit((($pageid - 1) * $perpage), $perpage);

		$count_result = $this->Db->Query($count_query);
		$count = $this->Db->FetchOne($count_result, 'count');

		if ($perpage > 0) {
			$search_result = $this->Db->Query($search_query);
			$subscriber_results = array();
			while ($row = $this->Db->Fetch($search_result)) {
				$subscriber_results[] = $row;
			}
		} else {
			$subscriber_results = array();
		}

		$return = array();
		$return['count'] = $count;
		$return['subscriberlist'] = $subscriber_results;
		return $return;
	}

	/**
	* LoadBan
	* Loads a ban from the database based on the 'banid' and 'list' passed in. If the list passed in is numeric, that's ok - if it's not, then we assume you're checking the global banned list.
	*
	* @param Int $banid The ban id to load up (used for editing).
	* @param Mixed $list The list the ban is on. If this is not a number it will look at the 'global' ban list.
	*
	* @return False|Array Returns false if the ban doesn't exist or if the query can't be run. Otherwise returns the result (which is an array).
	*/
	function LoadBan($banid=0, $list=null)
	{
		$banid = (int)$banid;
		if ($banid <= 0) {
			return false;
		}

		if (is_numeric($list)) {
			$list = (int)$list;
		} elseif (!is_null($list)) {
			$list = 'g';
		} else {
			$list = false;
		}

		$query = "SELECT banid, emailaddress, list, bandate FROM " . SENDSTUDIO_TABLEPREFIX . "banned_emails WHERE banid='" . $banid . "'";
		if ($list) {
			$query .= " AND list='" . $this->Db->Quote($list) . "'";
		}
		$query .= " LIMIT 1";

		$result = $this->Db->Query($query);
		if (!$result) {
			return false;
		}

		$row = $this->Db->Fetch($result);
		return $row;
	}

	/**
	* UpdateBan
	* Updates a banned email address and list based on the information passed in. If it's the same as the current information, it just returns and nothing else happens. If it's different, it gets updated.
	*
	* @param Int $banid The ban id from the database to update. This is loaded to make sure it's valid and then gets updated.
	* @param Array $info The new ban information to update. This includes the email address and the list to update.
	*
	* @see LoadBan
	*
	* @return Array Returns a status (true/false) and a message about what happened.
	*/
	function UpdateBan($banid=0, $info=array())
	{
		$banid = (int)$banid;
		$current_ban = $this->LoadBan($banid);
		if (!$current_ban) {
			return array(false, 'Bad ban id');
		}

		if ($current_ban['emailaddress'] == $info['emailaddress'] && $current_ban['list'] ==  $info['list']) {
			return array(true, false);
		}

		$newlist = $info['list'];
		if (!is_numeric($newlist)) {
			$newlist = 'g';
		}

		$query = "UPDATE " . SENDSTUDIO_TABLEPREFIX . "banned_emails SET emailaddress='" . $this->Db->Quote($info['emailaddress']) . "', list='" . $this->Db->Quote($newlist) . "' WHERE banid='" . (int)$banid . "'";

		$result = $this->Db->Query($query);
		if (!$result) {
			return array(false, 'Bad Query');
		}
		return array(true, false);
	}

	/**
	* GetForm
	* Gets the last form the subscriber used for an action. This is used for unsubscribing and confirming of the unsubscribe request. If the formid is present, then this means the request came from a form and not from an unsubscribe link in an email.
	*
	* @param Int $subscriberid The subscriberid to fetch the formid for.
	*
	* @see SetForm
	* @see confirm.php
	* @see formid
	*
	* @return False|Int Returns false if there is no formid present in the requests. If there is one, it returns the formid last used.
	*/
	function GetForm($subscriberid=0)
	{
		$query = "SELECT formid FROM " . SENDSTUDIO_TABLEPREFIX . "list_subscribers WHERE subscriberid='" . (int)$subscriberid . "' ORDER BY requestdate DESC LIMIT 1";
		$result = $this->Db->Query($query);
		$row = $this->Db->Fetch($result);
		if (empty($row)) {
			return false;
		}

		return (int)$row['formid'];
	}

	/**
	* SetForm
	* Sets the last form the subscriber used for an action. This is used for unsubscribing and confirming of the unsubscribe request.
	*
	* @param Int $subscriberid The subscriberid to update to the new formid.
	*
	* @see GetForm
	* @see unsubform.php
	* @see formid
	*
	* @return Boolean Returns whether the update worked or not.
	*/
	function SetForm($subscriberid=0)
	{
		$query = "UPDATE " . SENDSTUDIO_TABLEPREFIX . "list_subscribers SET formid='" . (int)$this->formid . "' WHERE subscriberid='" . (int)$subscriberid . "'";
		$result = $this->Db->Query($query);
		return $result;
	}

	/**
	* GetListsByForm
	* Gets an array of lists the email address is on according to the formid.
	* This is used when confirming (especially unsubscribe confirmations) so you are only removed from the appropriate lists you want.
	* For example, if you are on multiple lists, and an unsubscribe form has multiple lists, we don't want to remove you from all mailing lists - only the ones you have chosen.
	*
	* @param String $emailaddress The emailaddress to fetch the info for.
	* @param Int $formid The form to check against.
	*
	* @see SetForm
	* @see confirm.php
	* @see formid
	*
	* @return Array Returns an array of list id's. If none match, an empty array is returned.
	*/
	function GetListsByForm($emailaddress='', $formid=0)
	{
		$lists = array();

		$formid = (int)$formid;
		if (!$emailaddress || $formid <= 0) {
			return $lists;
		}

		$query = "SELECT listid FROM " . SENDSTUDIO_TABLEPREFIX . "list_subscribers WHERE emailaddress='" . $this->Db->Quote($emailaddress) . "' AND formid=" . $formid;
		$result = $this->Db->Query($query);
		while ($row = $this->Db->Fetch($result)) {
			$lists[] = (int)$row['listid'];
		}
		return $lists;
	}

	/**
	* UnsubscribeRequest
	* This handles unsubscribe requests. If this is a first-time request, it logs it appropriately. If the first request wasn't acknowledged or process (ie you submit to an unsubscribe form again before clicking the 'unsubscribe' link), this will delete the old request and re-add it.
	* If the request is acknowledged, the subscriber will be unsubscribed from the list accordingly.
	*
	* @param Int $subscriberid The subscriber's id from the database
	* @param Int $listid The listid to unsubscribe them from
	*
	* @see IsSubscriberOnList
	* @see UnsubscribeSubscriber
	* @see unsubform.php
	*
	* @return Boolean Returns true if the unsubscribe worked, or if the request is acknowledged. Returns false if the subscriber is not on the mailing list in the first place or if the unsubscribe confirmation failed.
	*/
	function UnsubscribeRequest($subscriberid=0, $listid=0)
	{
		if (!$this->IsSubscriberOnList(false, $listid, $subscriberid, true)) {
			return false;
		}

		$subscriberid = (int)$subscriberid;
		$listid = (int)$listid;

		$query = "UPDATE " . SENDSTUDIO_TABLEPREFIX . "list_subscribers SET unsubscribeconfirmed='" . $this->unsubscribeconfirmed . "' WHERE subscriberid='" . $subscriberid . "' AND listid='" . $listid . "'";
		$this->Db->Query($query);

		if ($this->unsubscribeconfirmed) {
			$result = $this->UnsubscribeSubscriber(false, $listid, $subscriberid, true);
			if ($result[0] == true) {
				return true;
			}
			return false;
		} else {
			// delete the old request (if applicable).
			$query = "DELETE FROM " . SENDSTUDIO_TABLEPREFIX . "list_subscribers_unsubscribe WHERE subscriberid='" . $subscriberid . "' AND listid='" . $listid . "'";
			$this->Db->Query($query);

			if (!$this->unsubscriberequestip) {
				$this->unsubscriberequestip = $this->GetRealIp();
			}

			// re-add it.
			$query = "INSERT INTO " . SENDSTUDIO_TABLEPREFIX . "list_subscribers_unsubscribe (subscriberid, unsubscribetime, listid, unsubscribeip, unsubscriberequesttime, unsubscriberequestip) VALUES ('" . $subscriberid . "', 0, '" . $listid . "', '', '" . $this->GetServerTime() . "', '" . $this->unsubscriberequestip . "')";
			$this->Db->Query($query);
		}
		return true;
	}

	/**
	* _GenerateDateSubQuery
	* Generates the date query that gets appended for queries that include any sort of date custom field. It has to break the date stored in the database up into chunks and check each part separately to make sure they match. This is done so the date is stored in a consistent manner and searching will work in any scenario.
	* If we are doing this for a postgresql database, everything is passed off to _GenerateDateSubQuery_PG
	*
	* It takes in an array of data:
	* <b>Example</b>
	* <code>
	* $fielddata = array('filter' => true, 'type' => 'after', 'mm_start' => 01, 'dd_start' => 01, 'yy_start' => 01);
	* </code>
	* will return the sql query to search for the 'date' after 01/01/(20)01
	* If filter is not in the array, this will return false (ie we are not using this as a filter).
	*
	* @param Array $fielddata The array of data to use for constructing the sql query for proper searching
	* @param Int $fieldid The field we are searching for.
	*
	* @see GetSubscribers
	* @see FetchSubscribers
	* @see _GenerateDateSubQuery_PG
	*
	* @return False|String This returns false if the 'filter' option isn't set in the array. Otherwise it will construct the date 'subquery' and return it.
	*/
	function _GenerateDateSubQuery($fielddata=array(), $fieldid=0)
	{
		if (!isset($fielddata['filter'])) {
			return false;
		}

		if (SENDSTUDIO_DATABASE_TYPE == 'pgsql') {
			return $this->_GenerateDateSubQuery_PG($fielddata, $fieldid);
		}

		switch ($fielddata['type']) {
			case 'after':
				$subquery = "(d.fieldid='" . $this->Db->Quote($fieldid) . "' AND (((RIGHT(d.data, 4) = '" . $this->Db->Quote($fielddata['yy_start']) . "') AND (LEFT(d.data,2) >= '" . $this->Db->Quote($fielddata['dd_start']) . "' AND MID(d.data, 4, 2) >= '" . $this->Db->Quote($fielddata['mm_start']) . "')) OR (RIGHT(d.data,4) > '" . $this->Db->Quote($fielddata['yy_start']) . "')))";
			break;

			case 'between':
				$subquery = "(d.fieldid='" . $this->Db->Quote($fieldid) . "' AND (((";
					if ($fielddata['yy_start'] != '*') {
						$subquery .= "(RIGHT(d.data, 4) = '" . $this->Db->Quote($fielddata['yy_start']) . "') AND ";
					}
					$subquery .=" (LEFT(d.data,2) >= '" . $this->Db->Quote($fielddata['dd_start']) . "' AND MID(d.data, 4, 2) >= '" . $this->Db->Quote($fielddata['mm_start']) . "')";
					if ($fielddata['yy_start'] != '*') {
						$subquery .= "OR (RIGHT(d.data,4) > '" . $this->Db->Quote($fielddata['yy_start']) . "')";
					}

				$subquery .= ") AND (";
					if ($fielddata['yy_end'] != '*') {
						$subquery .= "(RIGHT(d.data, 4) = '" . $this->Db->Quote($fielddata['yy_end']) . "') AND ";
					}
					$subquery .= "(LEFT(d.data,2) <= '" . $this->Db->Quote($fielddata['dd_end']) . "' AND MID(d.data, 4, 2) <= '" . $this->Db->Quote($fielddata['mm_end']) . "')";
					if ($fielddata['yy_end'] != '*') {
						$subquery .= "OR (RIGHT(d.data,4) < '" . $this->Db->Quote($fielddata['yy_end']) . "')";
					}
				$subquery .= "))))";
			break;

			case 'before':
					$subquery = "(d.fieldid='" . $this->Db->Quote($fieldid) . "' AND (";
					// check if the year is before yy_start
					$subquery .= "RIGHT(d.data, 4) < '" . $this->Db->Quote($fielddata['yy_start']) . "'";

					// check if the year is the same or month of before mm_start
					$subquery .= " OR (";
					$subquery .= "RIGHT(d.data, 4) = '" . $this->Db->Quote($fielddata['yy_start']) . "'";
					$subquery .= " AND MID(d.data, 4, 2) < '" . $this->Db->Quote($fielddata['mm_start']) . "'";
					$subquery .= ")"; // end or

					// check if the year and month are the same but day is before dd_start
					$subquery .= " OR (";
					$subquery .= "RIGHT(d.data, 4) = '" . $this->Db->Quote($fielddata['yy_start']) . "'";
					$subquery .= " AND MID(d.data, 4, 2) = '" . $this->Db->Quote($fielddata['mm_start']) . "'";
					$subquery .= " AND LEFT(d.data,2) < '" . $this->Db->Quote($fielddata['dd_start']) . "'";
					$subquery .= ")"; // end or

					$subquery .= ")";

					$subquery .= ")"; // end d.fieldid=
			break;

			case 'exactly':
				$subquery = "(d.fieldid='" . $this->Db->Quote($fieldid) . "' AND d.data='" . $fielddata['dd_start'] . '/' . $fielddata['mm_start'] . '/' . $fielddata['yy_start'] . "')";
			break;
		}
		return $subquery;
	}

	/**
	* _GenerateDateSubQuery_PG
	* Generates the date query for postgresql databases.
	*
	* @param Array $fielddata The array of data to use for constructing the sql query for proper searching
	* @param Int $fieldid The field we are searching for.
	*
	* @see _GenerateDateSubQuery
	*
	* @return False|String This returns false if the 'filter' option isn't set in the array. Otherwise it will construct the date 'subquery' and return it.
	*/
	function _GenerateDateSubQuery_PG($fielddata=array(), $fieldid=0)
	{
		if (!isset($fielddata['filter'])) {
			return false;
		}

		switch ($fielddata['type']) {
			case 'after':
				$subquery = "(d.fieldid='" . $this->Db->Quote($fieldid) . "' AND (((SUBSTRING(d.data FROM 7 FOR 4) = '" . $this->Db->Quote($fielddata['yy_start']) . "') AND (SUBSTRING(d.data FROM 1 FOR 2) >= '" . $this->Db->Quote($fielddata['dd_start']) . "' AND SUBSTRING(d.data FROM 4 FOR 2) >= '" . $this->Db->Quote($fielddata['mm_start']) . "')) OR (SUBSTRING(d.data FROM 7 FOR 4) > '" . $this->Db->Quote($fielddata['yy_start']) . "')))";
			break;

			case 'between':
				$subquery = "(d.fieldid='" . $this->Db->Quote($fieldid) . "' AND (";
					if ($fielddata['yy_start'] != '*') {
						$subquery .= "(SUBSTRING(d.data FROM 7 FOR 4) = '" . $this->Db->Quote($fielddata['yy_start']) . "') AND ";
					}

					$subquery .= "(SUBSTRING(d.data FROM 1 FOR 2) >= '" . $this->Db->Quote($fielddata['dd_start']) . "' AND SUBSTRING(d.data FROM 4 FOR 2) >= '" . $this->Db->Quote($fielddata['mm_start']) . "')";
					if ($fielddata['yy_start'] != '*') {
						$subquery .= "OR (SUBSTRING(d.data FROM 7 FOR 4) > '" . $this->Db->Quote($fielddata['yy_start']) . "')";
					}

				$subquery .= ") AND ";
					if ($fielddata['yy_end'] != '*') {
						$subquery .= "((SUBSTRING(d.data FROM 7 FOR 4) = '" . $this->Db->Quote($fielddata['yy_end']) . "') AND ";
					}
					$subquery .= "(SUBSTRING(d.data FROM 1 FOR 2) <= '" . $this->Db->Quote($fielddata['dd_end']) . "' AND SUBSTRING(d.data FROM 4 FOR 2) <= '" . $this->Db->Quote($fielddata['mm_end']) . "') ";
					if ($fielddata['yy_end'] != '*') {
						$subquery .= "OR (SUBSTRING(d.data FROM 7 FOR 4) < '" . $this->Db->Quote($fielddata['yy_end']) . "'))";
					}
				$subquery .= ")";
			break;

			case 'before':
					$subquery = "(d.fieldid='" . $this->Db->Quote($fieldid) . "' AND (";
					// check if the year is before yy_start
					$subquery .= "SUBSTRING(d.data FROM 7 FOR 4) < '" . $this->Db->Quote($fielddata['yy_start']) . "'";

					// check if the year is the same or month of before mm_start
					$subquery .= " OR (";
					$subquery .= "SUBSTRING(d.data FROM 7 FOR 4) = '" . $this->Db->Quote($fielddata['yy_start']) . "'";
					$subquery .= " AND SUBSTRING(d.data FROM 4 FOR 2) < '" . $this->Db->Quote($fielddata['mm_start']) . "'";
					$subquery .= ")"; // end or

					// check if the year and month are the same but day is before dd_start
					$subquery .= " OR (";
					$subquery .= "SUBSTRING(d.data FROM 7 FOR 4) = '" . $this->Db->Quote($fielddata['yy_start']) . "'";
					$subquery .= " AND SUBSTRING(d.data FROM 4 FOR 2) = '" . $this->Db->Quote($fielddata['mm_start']) . "'";
					$subquery .= " AND SUBSTRING(d.data FROM 1 FOR 2) < '" . $this->Db->Quote($fielddata['dd_start']) . "'";
					$subquery .= ")"; // end or

					$subquery .= ")";

					$subquery .= ")";
			break;

			case 'exactly':
				$subquery = "(d.fieldid='" . $this->Db->Quote($fieldid) . "' AND d.data='" . $fielddata['dd_start'] . '/' . $fielddata['mm_start'] . '/' . $fielddata['yy_start'] . "')";
			break;
		}
		return $subquery;
	}

	/**
	* _GenerateSubscribeDateSubQuery
	* Generates the subscribe date query that gets appended for queries that include the subscribe date.
	* It takes in an array of data:
	* <b>Example</b>
	* <code>
	* $fielddata = array('filter' => true, 'type' => 'after', 'mm_start' => 01, 'dd_start' => 01, 'yy_start' => 01);
	* </code>
	* will return the sql query to search for subscribers after 01/01/(20)01
	* If filter is not in the array, this will return false (ie we are not using this as a filter).
	*
	* @param Array $fielddata The array of data to use for constructing the sql query for proper searching
	*
	* @see GetSubscribers
	* @see FetchSubscribers
	*
	* @return False|String This returns false if the 'filter' option isn't set in the array. Otherwise it will construct the subscribe date 'subquery' and return it.
	*/
	function _GenerateSubscribeDateSubQuery($fielddata=array())
	{
		if (!isset($fielddata['filter'])) {
			return false;
		}

		$type = strtolower($fielddata['type']);

		switch ($type) {
			case 'after':
				$query_clause = " subscribedate >= " . $fielddata['StartDate'];
			break;

			case 'before':
				$query_clause = " subscribedate <= " . $fielddata['StartDate'];
			break;

			case 'exact':
			case 'exactly':
				$query_clause = " (subscribedate >= " . $fielddata['StartDate'] . " AND subscribedate < " . ($fielddata['StartDate'] + 86400) . ")";
			break;

			case 'between':
				$query_clause = " (subscribedate >= " . $fielddata['StartDate'] . " AND subscribedate <= " . $fielddata['EndDate'] . ")";
			break;
		}
		return $query_clause;
	}
}

?>
