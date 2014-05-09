<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class stats_autoresponders_convert extends Upgrade_API
{
	function RunUpgrade()
	{
		$query = 'SELECT autoresponderid, listid FROM ' . SENDSTUDIO_TABLEPREFIX . 'autoresponders';
		$result = $this->Db->Query($query);
		while ($row = $this->Db->Fetch($result)) {
			$statid = $this->Db->NextId(SENDSTUDIO_TABLEPREFIX . 'stats_sequence');

			$query = "INSERT INTO " . SENDSTUDIO_TABLEPREFIX . "stats_linkclicks(clicktime, clickip, subscriberid, statid, linkid) SELECT lc.timestamp, lc.ipaddress, lc.memberid, " . $statid . ", ln.linkid FROM " . SENDSTUDIO_TABLEPREFIX . "link_clicks lc, " . SENDSTUDIO_TABLEPREFIX . "links l, " . SENDSTUDIO_TABLEPREFIX . "links_new ln WHERE lc.linkid=l.linkid AND l.url=ln.url AND lc.ComposedID=l.ComposedID AND UPPER(lc.LinkType)='AUTO' AND lc.ListID='" . $row['listid'] . "'";

			$this->Db->Query($query);

			$query = "INSERT INTO " . SENDSTUDIO_TABLEPREFIX . "stats_links SELECT " . $statid . ", linkid FROM " . SENDSTUDIO_TABLEPREFIX . "stats_linkclicks WHERE statid='" . $statid . "'";

			$this->Db->Query($query);



			$query = "INSERT INTO " . SENDSTUDIO_TABLEPREFIX . "stats_emailopens(subscriberid, statid, opentime, openip) SELECT MemberID, " . $statid . ", TimeStamp, NULL FROM " . SENDSTUDIO_TABLEPREFIX . "email_opens WHERE SendID='" . $row['autoresponderid'] . "' AND UPPER(EmailType)='AUTO'";

			$this->Db->Query($query);


			$link_clicks_query = "SELECT COUNT(linkid) AS linkcount FROM " . SENDSTUDIO_TABLEPREFIX . "stats_links WHERE statid='" . $statid . "'";
			$clicks_result = $this->Db->Query($link_clicks_query);
			$link_clicks = $this->Db->FetchOne($clicks_result, 'linkcount');

			$link_clicks_query = "SELECT COUNT(openid) AS opencount FROM " . SENDSTUDIO_TABLEPREFIX . "stats_emailopens WHERE statid='" . $statid . "'";
			$opens_result = $this->Db->Query($link_clicks_query);
			$email_opens = $this->Db->FetchOne($opens_result, 'opencount');


			$insert_query = "INSERT INTO " . SENDSTUDIO_TABLEPREFIX . "stats_autoresponders(statid, htmlrecipients, textrecipients, multipartrecipients, bouncecount_soft, bouncecount_hard, bouncecount_unknown, unsubscribecount, autoresponderid, linkclicks, emailopens, emailforwards, emailopens_unique, hiddenby) VALUES ('" . $statid . "', '0', '0', '0', 0, 0, 0, 0, '" . $row['autoresponderid'] . "', '" . $link_clicks . "', '" . $email_opens . "', 0, '" . $email_opens . "', 0)";

			$insert_result = $this->Db->Query($insert_query);
		}
		return true;
	}
}
