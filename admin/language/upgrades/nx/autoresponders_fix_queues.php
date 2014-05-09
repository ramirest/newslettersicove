<?php
if (!class_exists('Upgrade_API')) {
	exit();
}

class autoresponders_fix_queues extends Upgrade_API
{
	function RunUpgrade()
	{
		// go through each autoresponder and set the queueid appropriately.
		$query = 'SELECT autoresponderid, ownerid, listid FROM ' . SENDSTUDIO_TABLEPREFIX . 'autoresponders';
		$result = $this->Db->Query($query);
		while ($row = $this->Db->Fetch($result)) {
			$queueid = $this->Db->NextId(SENDSTUDIO_TABLEPREFIX . 'queues_sequence');

			// set the queueid
			$query = "update " . SENDSTUDIO_TABLEPREFIX . "autoresponders SET queueid='" . $queueid . "' WHERE autoresponderid='" . $row['autoresponderid'] . "'";
			$update_result = $this->Db->Query($query);
		}

		// take (now() - subscribedate) > hoursaftersubscription (from autoresponder)
		// if that is true, add them to the queue.
		// otherwise ignore.

		// add all recipients back to the autoresponder queue that need to be added.
		$query = "insert into " . SENDSTUDIO_TABLEPREFIX . "queues(queueid, queuetype, ownerid, recipient, processed)";

		// here we kind of join the autoresponders table to itself
		// then get it to tell us all of the queues it needs
		// this is done with the 'case' at the end
		// we need the 'case' in case a subscriber has joined but not received anything (hence >=)
		//
		// we need the distinct because we are not fully joining the autoresponders 'a' table to
		// itself (autoresponders 'n'), so we were getting duplicates in there.
		// can't really use anything else to do a similar thing unfortunately.
		// and can't do a full join otherwise we'd only ever get one result ;)
		//
		// the order by's aren't needed hence commented out
		// but left in here in case we need to print out the query and check it ;)
		$select_query = "select
				distinct a.queueid, 'autoresponder', a.ownerid, l.subscriberid, 0
			from
				" . SENDSTUDIO_TABLEPREFIX . "list_subscribers l,
				" . SENDSTUDIO_TABLEPREFIX . "autoresponders a,
				" . SENDSTUDIO_TABLEPREFIX . "autoresponders n
			where
				l.listid=a.listid and l.listid=n.listid
				and
				case when l.lastresponderid=0 then
					a.hoursaftersubscription >= n.hoursaftersubscription
				else
					a.hoursaftersubscription > n.hoursaftersubscription
				end
		";
		#$select_query .= "order by a.queueid, l.subscriberid";

		$query .= $select_query;

		$result = $this->Db->Query($query);

		return $result;
	}
}
