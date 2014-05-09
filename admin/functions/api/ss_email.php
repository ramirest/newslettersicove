<?php
require(dirname(__FILE__) . '/email.php');

class SS_Email_API extends Email_API
{

	/**
	* An array of recipient custom fields.
	*
	* @see AddCustomFieldInfo
	*
	* @var Array
	*/
	var $_RecipientsCustomFields = array();

	/**
	* ForceChecks
	* Whether we check for unsubscribe and modify details links or not.
	*
	* @see ForceLinkChecks
	* @see ForgetEmail
	* @see CheckUnsubscribeLink
	*/
	var $forcechecks = false;

	/**
	* TrackLinks
	* Whether we track urls in the newsletter or not.
	*
	* @see TrackLinks
	* @see ForgetEmail
	*/
	var $tracklinks = false;

	/**
	* TrackOpens
	* Whether we track email opens in the newsletter or not.
	*
	* @see TrackOpen
	* @see ForgetEmail
	*/
	var $trackopens = false;

	/**
	* _FoundLinks
	* An array of links found in each part of the email contents. We can then check that against the database to see if it needs to be tracked.
	*
	* @see _GetLinks
	* @see TrackLinks
	* @see _ReplaceLinks
	*/
	var $_FoundLinks = array(
		'h' => array(),
		't' => array(),
		'm' => array()
	);

	/**
	* _convertedlinks
	* An array of links that have been converted. This is used when replacing found links with new (temporary) links. It saves hitting the database over and over again.
	*
	* @see _ReplaceLinks
	* @see ForgetEmail
	*
	* @var Array
	*/
	var $_convertedlinks = array();


	/**
	* disableunsubscribe
	* This is used by send-friend so unsubscribe, modify etc links don't work.
	*
	* @see DisableUnsubscribe
	* @see _ReplaceCustomFields
	*/
	var $disableunsubscribe = false;

	/**
	* Put who sent the email in the headers.
	*
	* @see _SetupHeaders
	*
	* @var String
	*/
	var $SentBy = false;

	/**
	* Put the listid in the headers.
	*
	* @see _SetupHeaders
	*
	* @var Array
	*/
	var $listids = Array();

	/**
	* Put the statid in the headers.
	*
	* @see _SetupHeaders
	*
	* @var Int
	*/
	var $statid = 0;

	/**
	* Database reference.
	*
	* @see GetDb
	* @see SaveLink
	*/
	var $Db = null;

	/**
	* Whether placeholders for an email have already been changed or not.
	*
	* @see _ChangePlaceholders
	*/
	var $placeholders_changed = false;

	/**
	* Whether listid's have already been checked or not.
	*
	* @see CheckIntVars
	*/
	var $ids_checked = false;

	/**
	* Constructor.
	*
	* Calls the parent constructor
	* Then sets up the class variable 'message_id_server' if sendstudio is set up.
	*
	* @see Email_API
	*/
	function SS_Email_API()
	{
		$this->Email_API();

		if (defined('SENDSTUDIO_APPLICATION_URL')) {
			$url_parts = parse_url(SENDSTUDIO_APPLICATION_URL);
			if (isset($url_parts['host'])) {
				$this->message_id_server = $url_parts['host'];
			}
		}
	}

	/**
	* Adds an address and name to the list of recipients to email.
	*
	* @param String $address Email Address to add.
	* @param String $name Their name (if applicable). This is checked before constructing the email to make sure it's available.
	* @param String $format Which format the recipient wants to receive. Either 'h' or 't'.
	* @param Int $subscriberid The subscriber id from the database. This is only used when tracking links and opens so can be left off normally.
	*
	* @see _Recipients
	*
	* @return Void Doesn't return anything - just adds the information to the _Recipients array.
	*/
	function AddRecipient($address, $name = '', $format='h', $subscriberid=0)
	{
		$curr = count($this->_Recipients);
		$this->_Recipients[$curr]['address'] = trim($address);
		$this->_Recipients[$curr]['name'] = $name;
		$this->_Recipients[$curr]['format'] = strtolower($format);
		$this->_Recipients[$curr]['subscriberid'] = (int)$subscriberid;
	}


	/**
	* AddCustomFieldInfo
	* Adds custom field information (loaded previously) for this recipient. This saves loading it again from the database. It will overwrite the recipients current settings (if applicable).
	*
	* @param String $subscriber_email The recipients email address (this allows us to remember it easily).
	* @param Array $customfields The custom field id's and settings for the recipient.
	*
	* @see _RecipientsCustomFields
	*
	* @return Void Doesn't return anything - just adds the information to the _RecipientsCustomFields array.
	*/
	function AddCustomFieldInfo($subscriber_email=false, $customfields=array())
	{
		if (!$subscriber_email) {
			return;
		}

		$this->_RecipientsCustomFields[$subscriber_email] = $customfields;
	}

	/**
	* _GetLinks
	* Gets the links from the appropriate body based on the body type passed in.
	* This allows us to do link tracking
	* A series of regular expressions are used to work out the links
	* The links are stored in _FoundLinks for easy reference when we need to convert them to tracked links.
	* Text emails only look for http:// or https:// links until we reach a space, a comma or a fullstop.
	* HTML emails look for <a href="..."> tags but also has to check whether there is a base href tag set in case the links are all relative instead of full.
	*
	* @param String $bodytype The type of body we're looking for links in. This should either be 't'ext or 'h'tml.
	*
	* @see tracklinks
	* @see _FoundLinks
	* @see body
	*
	* @return Void Doesn't return anything. The links found are stored in _FoundLinks for easy access later on.
	*/
	function _GetLinks()
	{
		if (!$this->tracklinks) {
			return;
		}

		if (!empty($this->_FoundLinks['t']) || !empty($this->_FoundLinks['h'])) {
			return;
		}

		$matches = array();
		$templinkid = 1;

		// this is used so we don't get the . in http://www.domain.com. or http://www.domain.com,
		$invalid_last_chars = array(',', '.', ')');

		while (preg_match('%(http[^"\' >\r\n\t]+["\']*)%i', $this->body['t'], $matches)) {
			$url = $matches[1];
			while (true) {
				$lastchar = substr($url, -1, 1);
				if (in_array($lastchar, $invalid_last_chars)) {
					$url = substr($url, 0, -1);
				} else {
					break;
				}
			}

			$templinkid++;
			$newlink = '%%LINK[' . $templinkid . ']%%';
			$replacement = str_replace($url, $newlink, $matches[0]);
			$this->body['t'] = substr_replace($this->body['t'], $replacement, strpos($this->body['t'], $matches[0]), strlen($matches[0]));

			$this->_FoundLinks['t'][$templinkid] = $url;
		}

		if (!empty($this->_FoundLinks['t'])) {
			$this->_FoundLinks['m'][] = true;
		}

		preg_match_all('%<a.+(href\s*=\s*(["\']?[^>"\']+?))\s*.+>%isU', $this->body['h'], $matches);
		$links_to_replace = $matches[2];
		$link_locations = $matches[1];

		arsort($link_locations);
		reset($links_to_replace);
		reset($link_locations);

		foreach ($link_locations as $tlinkid => $url) {
			// so we know whether we need to put quotes around the replaced url or not.
			$singles = false;
			$doubles = false;

			// make sure the quotes are matched up.
			// ie there is either 2 singles or 2 doubles.
			$quote_check = substr_count($url, "'");
			if (($quote_check % 2) != 0) {
				$url .= "'";
				$singles = true;
			}

			$quote_check = substr_count($url, '"');
			if (($quote_check % 2) != 0) {
				$url .= '"';
				$doubles = true;
			}

			if (preg_match('/%basic:(.*)?%/i', $url)) {
				continue;
			}

			if (preg_match('/%%(.*)?%%/i', $url)) {
				continue;
			}

			if (preg_match('%mailto%i', $url)) {
				continue;
			}

			// if there is a "#" as the first or second char, ignore it. Could be second if it is quoted: '#' or "#"
			$check = str_replace('href=', '', $url);
			if ($check{0} == '#' || $check{1} == '#') {
				continue;
			}

			$check = str_replace(array('"', "'"), '', $check);
			if ($check == '') {
				continue;
			}

			// the links_to_replace array gets rid of the quotes around the link because the replacement link will contain it.
			// otherwise you end up with two sets of quotes around things which breaks everything.
			$links_to_replace[$tlinkid] = str_replace(array('"', "'"), "", $links_to_replace[$tlinkid]);

			$replacement = 'href=';
			if ($singles) {
				$replacement .= "'";
			}

			if ($doubles) {
				$replacement .= '"';
			}

			$templinkid++;

			$replacement .= '%%LINK[' . $templinkid . ']%%';

			if ($singles) {
				$replacement .= "'";
			}

			if ($doubles) {
				$replacement .= '"';
			}

			$this->body['h'] = str_replace($url, $replacement, $this->body['h']);
			$this->_FoundLinks['h'][$templinkid] = $links_to_replace[$tlinkid];
		}

		$basehref = $this->_GetBaseHref();

		if ($basehref) {
			$matches = array();

			preg_match_all('%<a.+href\s*=\s*(["\']*[^ >]+?["\']*)\s*.+>%isU', $this->body['h'], $matches);

			$links_to_replace = array_unique($matches[1]);
			sort($links_to_replace);
			reset($links_to_replace);

			$templinkid++;

			foreach ($links_to_replace as $tlinkid => $url) {
				if (preg_match('/%basic:(.*)?%/i', $url)) {
					continue;
				}

				if (preg_match('/%%(.*)?%%/i', $url)) {
					continue;
				}

				if (preg_match('%mailto%i', $url)) {
					continue;
				}

				// if there is a "#" as the first or second char, ignore it. Could be second if it is quoted: '#' or "#"
				$check = str_replace('href=', '', $url);
				if ($check{0} == '#' || $check{1} == '#') {
					continue;
				}

				$check = str_replace(array('"', "'"), '', $check);
				if ($check == '') {
					continue;
				}

				$this->body['h'] = str_replace($url, '%%LINK[' . $templinkid . ']%%', $this->body['h']);
				$this->_FoundLinks['h'][$templinkid] = $basehref . $url;
				$templinkid++;
			}
		}

		if (!empty($this->_FoundLinks['h'])) {
			$this->_FoundLinks['m'][] = true;
		}
	}

	/**
	* _AddOpenTrack
	* Adds a placeholder for the open tracking image to html or multipart emails.
	*
	* @see GetBodyType
	* @see GetLang
	* @see AppendBody
	*
	* @return Void Doesn't return anything, it appends the open tracking image to the end of the message.
	*/
	function _AddOpenTrack()
	{
		if (!$this->trackopens) {
			return;
		}

		if (!$this->body['h']) {
			return;
		}

		if (strpos($this->body['h'], '%%openimage%%') !== false) {
			return;
		}

		$body = $this->body['h'];

		$imgsrc = '%%openimage%%';

		if (preg_match('%</body>%i', $body)) {
			$body = preg_replace('%</body>%i', $imgsrc . '</body>', $body);
		} else {
			if (!preg_match('%</html>%i', $body)) {
				$body .= "\n\n" . $imgsrc . '</body></html>';
			} else {
				$body = preg_replace('%</html>%i',
					$imgsrc . '</body></html>',
					$body
				);
			}
		}
		$this->body['h'] = $body;
	}

	/**
	* CheckUnsubscribeLink
	* Checks whether we have an unsubscribe link in the body. This only applies if we force an unsubscribe link.
	* Uses language variables (DefaultUnsubscribeFooter_html and DefaultUnsubscribeFooter_text) for the variables.
	*
	* @see forcechecks
	* @see GetBodyType
	* @see GetLang
	* @see AppendBody
	*
	* @return Void Doesn't return anything, it appends the unsubscribe link to the end of the message.
	*/
	function CheckUnsubscribeLink()
	{
		if (!$this->forcechecks) {
			return;
		}

		if (!SENDSTUDIO_FORCE_UNSUBLINK) {
			return;
		}

		if (
			!preg_match('/%%unsubscribelink%%/i', $this->body['t']) &&
			!preg_match('/%basic:unsublink%/i', $this->body['t']) &&
			!preg_match('/%%unsubscribe%%/i', $this->body['t'])
		) {
			$this->AppendBody('text', GetLang('DefaultUnsubscribeFooter_text'), false);
		}

		if (
			!preg_match('/%%unsubscribelink%%/i', $this->body['h']) &&
			!preg_match('/%basic:unsublink%/i', $this->body['h']) &&
			!preg_match('/%%unsubscribe%%/i', $this->body['h'])
		) {
			$this->AppendBody('html', GetLang('DefaultUnsubscribeFooter_html'), false);
		}
	}

	/**
	* _ReplaceLinks
	* Replaces links found in the body of the emails with sendstudio trackable links instead.
	* It goes through _FoundLinks and replaces the placeholder with a saved version of the link.
	*
	* @param String $body The body to replace links in. This is passed in by reference.
	* @param String $subscriberaddress The subscriber to replace the links for. This gives each subscriber a specific link to click.
	*
	* @see _RecipientsCustomFields
	* @see _FoundLinks
	* @see _convertedlinks
	* @see SaveLink
	*
	* @return Void Doesn't return anything because both parameters are passed in by reference.
	*/
	function _ReplaceLinks(&$body, &$subscriberaddress)
	{
		if (!isset($this->_RecipientsCustomFields[$subscriberaddress])) {
			return;
		}

		$info = &$this->_RecipientsCustomFields[$subscriberaddress];
		// if there's no subscriber id, we don't know who to track. Return the body as it was.
		if (!isset($info['subscriberid'])) {
			return;
		}

		$reallink = SENDSTUDIO_APPLICATION_URL . '/link.php?M=' . $info['subscriberid'];

		if (isset($info['newsletter'])) {
			$reallink .= '&N=' . $info['statid'];
		}

		if (isset($info['autoresponder'])) {
			$reallink .= '&A=' . $info['statid'];
		}

		// replace the html links first.
		foreach ($this->_FoundLinks['h'] as $p => $url) {

			// we need to track things in the admin/temp folder so we'll do that here first.

			// things like unsubscribe links, modify-details links should be left alone.
			// so if it's based on the BaseURL, just replace it back.
			if (strpos($url, SENDSTUDIO_APPLICATION_URL) !== false) {

				// however, if it's in the admin/temp url we do want to track it.
				// strpos will fail (be "false") if the SENDSTUDIO_TEMP_URL isn't in the $url.
				if (strpos($url, SENDSTUDIO_TEMP_URL) === false) {
					$body = str_replace('%%LINK[' . $p . ']%%', str_replace(array('"', "'"), "", $this->_FoundLinks['h'][$p]), $body);
					continue;
				}
			}

			if (!isset($this->_convertedlinks[$url])) {
				$linkid = $this->SaveLink($url, $info['statid']);
				$this->_convertedlinks[$url] = $linkid;
			} else {
				$linkid = $this->_convertedlinks[$url];
			}
			$replacelink = $reallink . '&L=' . $linkid;
			$body = str_replace('%%LINK[' . $p . ']%%', $replacelink, $body);
		}

		// then the text ones.
		foreach ($this->_FoundLinks['t'] as $p => $url) {
			// things like unsubscribe links, modify-details links should be left alone.
			// so if it's based on the BaseURL, just replace it back.
			if (preg_match('%' . SENDSTUDIO_APPLICATION_URL . '%', $url)) {
				$body = str_replace('%%LINK[' . $p . ']%%', $this->_FoundLinks['t'][$p], $body);
				continue;
			}

			if (!in_array($url, array_keys($this->_convertedlinks))) {
				$linkid = $this->SaveLink($url, $info['statid']);
				$this->_convertedlinks[$url] = $linkid;
			} else {
				$linkid = $this->_convertedlinks[$url];
			}
			$replacelink = $reallink . '&L=' . $linkid;
			$body = str_replace('%%LINK[' . $p . ']%%', $replacelink, $body);
		}
	}

	/**
	* GetCustomFields
	* Gets custom fields from both the html section of the email and the text section.
	* It looks for %field:*% and %%fieldname%% type syntaxes in case it's an old style newsletter or new style.
	*
	* @return Array Returns an array of custom field placeholders that were found.
	*/
	function GetCustomFields()
	{
		$fields = array();
		$html_body = $this->body['h'];
		if ($html_body) {
			preg_match_all('/%field:(.*?)%/is', $html_body, $matches);
			if (!empty($matches) && isset($matches[1])) {
				$fields += $matches[1];
			}
			preg_match_all('/%%(.*?)%%/is', $html_body, $matches);
			if (!empty($matches) && isset($matches[1])) {
				$fields += $matches[1];
			}
		}

		$text_body = $this->body['t'];
		if ($text_body) {
			preg_match_all('/%field:(.*?)%/is', $text_body, $matches);
			if (!empty($matches) && isset($matches[1])) {
				$fields += $matches[1];
			}
			preg_match_all('/%%(.*?)%%/is', $text_body, $matches);
			if (!empty($matches) && isset($matches[1])) {
				$fields += $matches[1];
			}
		}

		preg_match_all('/%field:(.*?)%/i', $this->Subject, $matches);

		if (!empty($matches) && isset($matches[1])) {
			foreach ($matches[1] as $p => $f_s) {
				$fields[] = $f_s;
			}
		}

		preg_match_all('/%%(.*?)%%/i', $this->Subject, $matches);

		if (!empty($matches) && isset($matches[1])) {
			foreach ($matches[1] as $p => $f_s) {
				$fields[] = $f_s;
			}
		}

		// in case the field went over multiple lines, strip them out.
		foreach ($fields as $p => $field) {
			$fields[$p] = str_replace(array("\r", "\n"), "", $field);
		}

		$fields = array_unique($fields);

		$built_in_fields = array('emailaddress', 'unsubscribelink', 'confirmlink', 'openimage', 'mailinglistarchive', 'webversion');
		foreach ($built_in_fields as $p => $built_in_field) {
			$key = array_search($built_in_field, $fields);
			if ($key !== false) {
				unset($fields[$key]);
			}
		}
		return $fields;
	}

	/**
	* _ReplaceCustomFields
	* Replaces custom fields in the text passed in (passed in by reference) based on the subscriber details (also passed in by reference).
	* Custom fields that are replaced include regular custom fields (eg %%name%% or %%address%%) and pre-defined ones (such as %%emailaddress%%).
	* If no text or address is passed in, this will return straight away.
	* If there are no custom fields to replace for this address, this will return straight away.
	* This also generates confirmation links, unsubscribe links, open tracking, modify details and send-to-friend links.
	* If disableunsubscribe is set to true, the links mentioned above will be replaced with a simple '#' so they don't work. This will happen if send-to-friend emails are sent.
	*
	* @param String $text Text to replace custom fields in
	* @param String $subscriberaddress The subscriber address to replace. This is looked up in _RecipientsCustomFields for other information required.
	*
	* @see _RecipientsCustomFields
	* @see disableunsubscribe
	*
	* @return Void Nothing is returned because the information is passed in by reference.
	*/
	function _ReplaceCustomFields(&$text, &$subscriberaddress)
	{
		if (!isset($this->_RecipientsCustomFields[$subscriberaddress])) {
			return;
		}

		$info = $this->_RecipientsCustomFields[$subscriberaddress];

		$text = str_replace(array('%LISTS%', '%lists%'), '%%listname%%', $text);

		$text = str_replace(array('%BASIC:EMAIL%', '%basic:email%', '%email%', '%EMAIL%'), '%%emailaddress%%', $text);

		$basefields = array('emailaddress', 'confirmed', 'format', 'subscribedate', 'listname');
		foreach ($basefields as $p => $field) {
			$field = strtolower($field);

			if (!isset($info[$field])) {
				continue;
			}
			$fielddata = $info[$field];
			if ($field == 'subscribedate') {
				$fielddata = date(LNG_DateFormat, $fielddata);
			}
			$fieldname = '%%' . $field . '%%';
			$text = str_replace($fieldname, $fielddata, $text);
			unset($fielddata);
		}

		$web_version_link = SENDSTUDIO_APPLICATION_URL . '/display.php?M=' . $info['subscriberid'];
		$web_version_link .= '&C=' . $info['confirmcode'];

		if (isset($info['listid'])) {
			$web_version_link .= '&L=' . $info['listid'];
		}

		if (isset($info['newsletter'])) {
			$web_version_link .= '&N=' . $info['newsletter'];
		}

		if (isset($info['autoresponder'])) {
			$web_version_link .= '&A=' . $info['autoresponder'];
		}

		$text = str_replace(array('%%webversion%%', '%%WEBVERSION%%'), $web_version_link, $text);

		$mailinglist_archives_link = SENDSTUDIO_APPLICATION_URL . '/rss.php?M=' . $info['subscriberid'];
		$mailinglist_archives_link .= '&C=' . $info['confirmcode'];

		if (isset($info['listid'])) {
			$mailinglist_archives_link .= '&L=' . $info['listid'];
		}

		$text = str_replace(array('%%mailinglistarchive%%', '%%MAILINGLISTARCHIVE%%'), $mailinglist_archives_link, $text);


		$confirmlink = SENDSTUDIO_APPLICATION_URL . '/confirm.php?E=' . urlencode($info['emailaddress']);
		if (isset($info['listid'])) {
			$confirmlink .= '&L=' . $info['listid'];
		}

		$confirmlink .= '&C=' . $info['confirmcode'];

		$text = str_replace(array('%%confirmlink%%', '%%CONFIRMLINK%%', '%CONFIRMLINK%', '%confirmlink%'), $confirmlink, $text);

		$text = str_replace(array('%basic:confirmlink%', '%BASIC:CONFIRMLINK%'), $confirmlink, $text);

		$unsubscribelink = SENDSTUDIO_APPLICATION_URL . '/unsubscribe.php?';

		$linkdata = 'M=' . $info['subscriberid'];

		// so we can track where someone unsubscribed from, we'll add that into the url.
		if (isset($info['newsletter'])) {
			$linkdata .= '&N=' . $info['statid'];
		}

		if (isset($info['autoresponder'])) {
			$linkdata .= '&A=' . $info['statid'];
		}

		if (isset($info['listid'])) {
			$linkdata .= '&L=' . $info['listid'];
		}

		$linkdata .= '&C=' . $info['confirmcode'];

		$unsubscribelink .= $linkdata;

		$text = str_replace(array('%basic:confirmunsublink%', '%BASIC:CONFIRMUNSUBLINK%'), $confirmlink, $text);

		$open_image = '<img src="' . SENDSTUDIO_APPLICATION_URL . '/open.php?' . str_replace('&C='.$info['confirmcode'], '', $linkdata) . '">';

		if (!$this->disableunsubscribe) {
			// preg_replace takes up too much memory so we'll do double replace.
			// we can't do it as an array because that takes up too much memory as well.
			$text = str_replace(array('%%UNSUBSCRIBELINK%%','%%unsubscribelink%%'), $unsubscribelink, $text);

			$text = str_replace(array('%basic:unsublink%', '%BASIC:UNSUBLINK%'), $unsubscribelink, $text);

			$text = str_replace(array('%%UNSUBSCRIBE%%','%%unsubscribe%%'), $unsubscribelink, $text);

			$text = str_replace('%%openimage%%', $open_image, $text);

			preg_match_all('/%%modifydetails_(.*?)%%/i', $text, $matches);
			if (isset($matches[1]) && !empty($matches[1])) {
				foreach ($matches[1] as $p => $mtch) {
					$replaceurl = SENDSTUDIO_APPLICATION_URL . '/modifydetails.php?' . $linkdata . '&F=' . $mtch;
					$text = str_replace($matches[0][$p], $replaceurl, $text);
				}
			}
			$matches = array();

			preg_match('/%%sendfriend_(.*?)%%/i', $text, $matches);
			if (isset($matches[1]) && !empty($matches[1])) {
				$extra = '';
				if (isset($info['newsletter'])) {
					$extra = '&i=' . $info['newsletter'];
				}

				if (isset($info['autoresponder'])) {
					$extra = '&i=' . $info['autoresponder'];
				}

				$replaceurl = SENDSTUDIO_APPLICATION_URL . '/sendfriend.php?' . $linkdata . '&F=' . $matches[1] . $extra;

				$text = str_replace($matches[0], $replaceurl, $text);
			}
			$matches = array();

		} else {
			$text = str_replace('%%openimage%%', '', $text);
			$text = preg_replace('/%%modifydetails_(.*)%%/i', '#', $text);
			$text = preg_replace('/%%sendfriend_(.*)%%/i', '#', $text);

			$text = str_replace(array('%%UNSUBSCRIBELINK%%','%%unsubscribelink%%'), '#', $text);
			$text = str_replace(array('%basic:unsublink%', '%BASIC:UNSUBLINK%'), '#', $text);
			$text = str_replace(array('%%UNSUBSCRIBE%%','%%unsubscribe%%'), '#', $text);
		}

		if (isset($info['CustomFields'])) {
			$customfields = $info['CustomFields'];

			foreach ($customfields as $p => $details) {
				$fieldname = '%%' . str_replace(' ', '\\s+', preg_quote(strtolower($details['fieldname']), '/')) . '%%';

				$replacetext = '';
				if (is_null($details['data']) || $details['data'] == '') {
					if (isset($details['defaultvalue'])) {
						$replacetext = $details['defaultvalue'];
					}
				} else {
					$replacetext = $details['data'];
				}
				$text = preg_replace('/'. $fieldname . '/is', $replacetext, $text);
				if (isset($details['fieldid'])) {
					$text = preg_replace('/%field\:' . $details['fieldid'] . '%/i', $replacetext, $text);
				}
			}
		}

		unset($info);
	}

	/**
	* ForceLinkChecks
	* Whether to force checking of links or not. This is off by default so you can send regular emails without trying to add unsubscribe links.
	*
	* @param Boolean $forcechecks Whether to check the links or not.
	*
	* @see forcechecks
	*
	* @return Void Sets the class var, doesn't return anything.
	*/
	function ForceLinkChecks($forcechecks=false)
	{
		$this->forcechecks = (bool)$forcechecks;
	}

	/**
	* TrackLinks
	* Whether to track links or not. This is off by default.
	*
	* @param Boolean $tracklinks Whether to track links or not.
	*
	* @see tracklinks
	*
	* @return Void Sets the class var, doesn't return anything.
	*/
	function TrackLinks($tracklinks=false)
	{
		$this->tracklinks = (bool)$tracklinks;
	}

	/**
	* TrackOpens
	* Whether to track email opens or not. This is off by default.
	*
	* @param Boolean $trackopens Whether to track opens or not.
	*
	* @see trackopens
	*
	* @return Void Sets the class var, doesn't return anything.
	*/
	function TrackOpens($trackopens=false)
	{
		$this->trackopens = (bool)$trackopens;
	}

	/**
	* DisableUnsubscribe
	* Whether to disable the unsubscribe link or not.
	* This is used when sending to a friend so the 'friend' can't unsubscribe you or modify your information.
	*
	* @param Boolean $disable Whether to disable the unsubscribe link or not.
	*
	* @return Void Doesn't return anything.
	*/
	function DisableUnsubscribe($disable=true)
	{
		$this->disableunsubscribe = (bool)$disable;
	}

	function _ChangePlaceholders() {
		if ($this->placeholders_changed) {
			return;
		}
		$this->body['h'] = str_replace(array('%BASIC:ARCHIVELINK%', '%basic:archivelink%'), '%%mailinglistarchive%%', $this->body['h']);

		if (!preg_match('/<a(.+)href=["\']*%%mailinglistarchive%%["\']*(.*)>/i', $this->body['h'])) {
			$this->body['h'] = str_replace('%%mailinglistarchive%%', '<a href="%%mailinglistarchive%%">%%mailinglistarchive%%</a>', $this->body['h']);
		}

		if (!preg_match('/<a(.+)href=["\']*%%webversion%%["\']*(.*)>/i', $this->body['h'])) {
			$this->body['h'] = str_replace('%%webversion%%', '<a href="%%webversion%%">%%webversion%%</a>', $this->body['h']);
		}

		$this->body['h'] = str_replace(array('%BASIC:UNSUBLINK%', '%basic:unsublink%'), '%%unsubscribelink%%', $this->body['h']);

		if (!preg_match('/<a(.+)href=["\']*%%unsubscribelink%%["\']*(.*)>/i', $this->body['h'])) {
			$this->body['h'] = str_replace(array('%%unsubscribelink%%', '%%UNSUBSCRIBELINK%%'), '<a href="%%unsubscribelink%%">%%unsubscribelink%%</a>', $this->body['h']);
		}

		if (!preg_match('/<a(.+)href=["\']*%%unsubscribe%%["\']*(.*)>/i', $this->body['h'])) {
			$this->body['h'] = str_replace(array('%%unsubscribe%%', '%%UNSUBSCRIBE%%'), '<a href="%%unsubscribe%%">%%unsubscribe%%</a>', $this->body['h']);
		}

		$this->body['h'] = str_replace(array('%BASIC:CONFIRMLINK%', '%basic:confirmlink%'), '%%confirmlink%%', $this->body['h']);

		if (!preg_match('/<a(.+)href=["\']*%%confirmlink%%["\']*(.*)>/i', $this->body['h'])) {
			$this->body['h'] = str_replace('%%confirmlink%%', '<a href="%%confirmlink%%">%%confirmlink%%</a>', $this->body['h']);
		}

		if (!preg_match('/<a(.+)href=["\']*%%modifydetails_(.*?)%%["\']*(.*)>/i', $this->body['h'])) {
			$this->body['h'] = preg_replace('/%%MODIFYDETAILS_(.*?)%%/i', '<a href="%%MODIFYDETAILS_\\1%%">%%MODIFYDETAILS_\\1%%</a>', $this->body['h']);
		}

		if (!preg_match('/<a(.+)href=["\']*%basic:modifydetails_(.*?)%["\']*(.*)>/i', $this->body['h'])) {
			$this->body['h'] = preg_replace('/%BASIC:MODIFYDETAILS_(.*?)%/i', '<a href="%BASIC:MODIFYDETAILS_\\1%">%BASIC:MODIFYDETAILS_\\1%</a>', $this->body['h']);
		}

		if (!preg_match('/<a(.+)href=["\']*%%sendfriend_(.*?)%%["\']*(.*)>/i', $this->body['h'])) {
			$this->body['h'] = preg_replace('/%%sendfriend_(.*?)%%/i', '<a href="%%sendfriend_\\1%%">%%sendfriend_\\1%%</a>', $this->body['h']);
		}
		$this->placeholders_changed = true;
	}

	function Send($replace=false)
	{

		if ($this->Debug) {
			error_log('Line ' . __LINE__ . '; sending' . "\n", 3, $this->LogFile);
			if ($this->memory_limit) {
				error_log("\n\n", 3, $this->MemoryLogFile);
				error_log(basename(__FILE__) . "\t" . __LINE__ . "\t" . __FUNCTION__ . "\t" . number_format((memory_get_usage()/1024), 5) . "\n", 3, $this->MemoryLogFile);
			}
		}

		$extra_headers = Array();

		$this->listids = $this->CheckIntVars($this->listids);

		if (sizeof($this->listids) > 0) {
			$extra_headers[] = 'X-Mailer-LID: ' . implode(',', $this->listids);
		}

		if ((int)$this->statid > 0) {
			$extra_headers[] = 'X-Mailer-SID: ' . (int)$this->statid;
		}

		if ($this->SentBy !== false) {
			$header = 'X-Mailer-Sent-By: ';
			if (!is_numeric($this->SentBy)) {
				$header .= '"' . $this->SentBy . '"';
			} else {
				$header .= $this->SentBy;
			}
			$extra_headers[] = $header;
		}
		$this->extra_headers = $extra_headers;

		$results = array('success' => 0, 'fail' => array());

		if ($this->Debug) {
			if ($this->memory_limit) {
				error_log(basename(__FILE__) . "\t" . __LINE__ . "\t" . __FUNCTION__ . "\t" . number_format((memory_get_usage()/1024), 5) . "\n", 3, $this->MemoryLogFile);
			}
		}

		$this->_ChangePlaceholders();

		if ($this->Debug) {
			if ($this->memory_limit) {
				error_log(basename(__FILE__) . "\t" . __LINE__ . "\t" . __FUNCTION__ . "\t" . number_format((memory_get_usage()/1024), 5) . "\n", 3, $this->MemoryLogFile);
			}
		}

		$this->_AddOpenTrack();

		if ($this->Debug) {
			if ($this->memory_limit) {
				error_log(basename(__FILE__) . "\t" . __LINE__ . "\t" . __FUNCTION__ . "\t" . number_format((memory_get_usage()/1024), 5) . "\n", 3, $this->MemoryLogFile);
			}
		}

		$this->_GetLinks();

		if ($this->Debug) {
			if ($this->memory_limit) {
				error_log(basename(__FILE__) . "\t" . __LINE__ . "\t" . __FUNCTION__ . "\t" . number_format((memory_get_usage()/1024), 5) . "\n", 3, $this->MemoryLogFile);
			}
		}

		$this->CheckUnsubscribeLink();

		if ($this->Debug) {
			if ($this->memory_limit) {
				error_log(basename(__FILE__) . "\t" . __LINE__ . "\t" . __FUNCTION__ . "\t" . number_format((memory_get_usage()/1024), 5) . "\n", 3, $this->MemoryLogFile);
			}
		}

		$headers = $this->_SetupHeaders();

		if ($this->Debug) {
			if ($this->memory_limit) {
				error_log(basename(__FILE__) . "\t" . __LINE__ . "\t" . __FUNCTION__ . "\t" . number_format((memory_get_usage()/1024), 5) . "\n", 3, $this->MemoryLogFile);
			}
		}

		$this->_SetupAttachments();

		if ($this->Debug) {
			if ($this->memory_limit) {
				error_log(basename(__FILE__) . "\t" . __LINE__ . "\t" . __FUNCTION__ . "\t" . number_format((memory_get_usage()/1024), 5) . "\n", 3, $this->MemoryLogFile);
			}
		}

		$this->_SetupImages();

		if ($this->Debug) {
			if ($this->memory_limit) {
				error_log(basename(__FILE__) . "\t" . __LINE__ . "\t" . __FUNCTION__ . "\t" . number_format((memory_get_usage()/1024), 5) . "\n", 3, $this->MemoryLogFile);
			}
		}

		$body = $this->_SetupBody();

		if ($this->Debug) {
			if ($this->memory_limit) {
				error_log(basename(__FILE__) . "\t" . __LINE__ . "\t" . __FUNCTION__ . "\t" . number_format((memory_get_usage()/1024), 5) . "\n", 3, $this->MemoryLogFile);
			}
		}

		foreach ($this->_Recipients as $p => $details) {

			if ($this->Debug) {
				if ($this->memory_limit) {
					error_log(basename(__FILE__) . "\t" . __LINE__ . "\t" . __FUNCTION__ . "\t" . number_format((memory_get_usage()/1024), 5) . "\n", 3, $this->MemoryLogFile);
				}
			}

			$to = '';
			if ($details['name']) {
				$to .= '"' . $details['name'] . '" <';
			}
			$to .= $details['address'];

			if ($details['name']) {
				$to .= '>';
			}

			$rcpt_to = $details['address'];

			$headers = $this->_GetHeaders($details['format']);
			$body = $this->_GetBody($details['format']);

			if (!$headers || !$body) {
				$results['fail'][] = array($rcpt_to, 'BlankEmail');
				continue;
			}

			if ($this->Debug) {
				if ($this->memory_limit) {
					error_log(basename(__FILE__) . "\t" . __LINE__ . "\t" . __FUNCTION__ . "\t" . number_format((memory_get_usage()/1024), 5) . "\n", 3, $this->MemoryLogFile);
				}
			}

			if ($replace) {
				$this->_ReplaceCustomFields($body, $rcpt_to);
			}

			if ($this->Debug) {
				if ($this->memory_limit) {
					error_log(basename(__FILE__) . "\t" . __LINE__ . "\t" . __FUNCTION__ . "\t" . number_format((memory_get_usage()/1024), 5) . "\n", 3, $this->MemoryLogFile);
				}
			}

			$subject = $this->Subject;

			if ($replace) {
				$this->_ReplaceCustomFields($subject, $rcpt_to);
			}

			if ($this->Debug) {
				if ($this->memory_limit) {
					error_log(basename(__FILE__) . "\t" . __LINE__ . "\t" . __FUNCTION__ . "\t" . number_format((memory_get_usage()/1024), 5) . "\n", 3, $this->MemoryLogFile);
				}
			}

			$this->_ReplaceLinks($body, $rcpt_to);

			if ($this->Debug) {
				if ($this->memory_limit) {
					error_log(basename(__FILE__) . "\t" . __LINE__ . "\t" . __FUNCTION__ . "\t" . number_format((memory_get_usage()/1024), 5) . "\n", 3, $this->MemoryLogFile);
				}
			}

			list($mail_result, $reason) = $this->_Send_Recipient($to, $rcpt_to, $subject, $details['format'], $headers, $body);

			if ($this->Debug) {
				if ($this->memory_limit) {
					error_log(basename(__FILE__) . "\t" . __LINE__ . "\t" . __FUNCTION__ . "\t" . number_format((memory_get_usage()/1024), 5) . "\n", 3, $this->MemoryLogFile);
				}
			}

			if ($mail_result) {
				$results['success']++;
			} else {
				$results['fail'][] = array($rcpt_to, $reason);
			}

			if (isset($this->_RecipientsCustomFields[$rcpt_to])) {
				unset($this->_RecipientsCustomFields[$rcpt_to]);
			}

			if ($this->Debug) {
				if ($this->memory_limit) {
					error_log(basename(__FILE__) . "\t" . __LINE__ . "\t" . __FUNCTION__ . "\t" . number_format((memory_get_usage()/1024), 5) . "\n", 3, $this->MemoryLogFile);
				}
			}

		}

		$this->_Close_Smtp_Connection();
		return $results;
	}

	/**
	* ForgetEmail
	* Forgets the email settings ready for another send.
	*
	* @return Void Doesn't return anything.
	*/
	function ForgetEmail()
	{
		$this->ids_checked = false;
		$this->placeholders_changed = false;

		$this->listids = Array();
		$this->statid = 0;

		$this->forcechecks = false;

		$this->tracklinks = false;

		$this->trackopens = false;

		$this->_FoundLinks = array(
			'h' => array(),
			't' => array(),
			'm' => array()
		);

		$this->_convertedlinks = array();

		$this->disableunsubscribe = false;

		$this->SentBy = false;

		parent::ForgetEmail();
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
		if ($this->ids_checked) {
			return $array_to_check;
		}

		if (!is_array($array_to_check)) {
			return array();
		}
		foreach ($array_to_check as $p => $var) {
			if (!is_numeric($var)) {
				unset($array_to_check[$p]);
			}
		}
		$this->ids_checked = true;
		return $array_to_check;
	}

	/**
	* SaveLink
	* Saves a url into the database for tracking purposes. This is then loaded later on to redirect to the right url.
	* This checks whether the url has already been saved. If it has, it will return that linkid. If it has not, it will save it and then return the new id.
	*
	* @param String $url The URL to save into the database.
	* @param Int $statid The statid to save the url for.
	*
	* @see GetDb
	*
	* @return Int Returns either the existing linkid from the database or the newly created one.
	*/
	function SaveLink($url='', $statid=0)
	{
		$this->GetDb();

		$query = "SELECT l.linkid AS linkid, statid FROM " . SENDSTUDIO_TABLEPREFIX . "links l LEFT OUTER JOIN " . SENDSTUDIO_TABLEPREFIX . "stats_links sl ON l.linkid=sl.linkid WHERE url='" . $this->Db->Quote($url) . "'";

		$result = $this->Db->Query($query);

		$linkid = false;
		while ($row = $this->Db->Fetch($result)) {
			// if the link is already stored for this particular stat, return the linkid straight away.
			if ($row['statid'] == $statid) {
				return $row['linkid'];
			}
			$linkid = $row['linkid'];
		}

		// if we get into the loop over $row, then we found the link.
		// which sets found_link to true.
		if (!$linkid) {
			$query = "INSERT INTO " . SENDSTUDIO_TABLEPREFIX . "links(url) VALUES ('" . $this->Db->Quote($url) . "')";
			$this->Db->Query($query);
			$linkid = $this->Db->LastId(SENDSTUDIO_TABLEPREFIX . 'links_sequence');
		}

		$query = "INSERT INTO " . SENDSTUDIO_TABLEPREFIX . "stats_links(linkid, statid) VALUES ('" . $this->Db->Quote($linkid) . "', '" . $this->Db->Quote($statid) . "')";
		$this->Db->Query($query);

		return $linkid;
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

}

?>
