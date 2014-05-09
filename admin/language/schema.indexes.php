<?php
/**
* Indexes for both databases (mysql & postgresql).
*
* @version     $Id: schema.indexes.php,v 1.10 2007/02/26 05:15:06 chris Exp $

*
* @package SendStudio
* @subpackage Language
*/

/**
* Indexes for both databases (mysql & postgresql).
* DO NOT CHANGE BELOW THIS LINE.
*/

if (!isset($queries)) {
	exit();
}

$queries[] = "CREATE INDEX %%TABLEPREFIX%%subscribers_email_idx ON %%TABLEPREFIX%%list_subscribers(emailaddress)";

$queries[] = "CREATE INDEX %%TABLEPREFIX%%list_subscribers_sub_list_idx ON %%TABLEPREFIX%%list_subscribers(subscriberid, listid)";

$queries[] = "CREATE INDEX %%TABLEPREFIX%%list_unsubscribe_sub_list_idx ON %%TABLEPREFIX%%list_subscribers_unsubscribe (subscriberid, listid)";

$queries[] = "CREATE INDEX %%TABLEPREFIX%%subscribe_date_idx ON %%TABLEPREFIX%%list_subscribers(subscribedate)";

$queries[] = "CREATE INDEX %%TABLEPREFIX%%autoresponders_owner_idx on %%TABLEPREFIX%%autoresponders(ownerid)";
$queries[] = "CREATE INDEX %%TABLEPREFIX%%autoresponders_list_idx on %%TABLEPREFIX%%autoresponders(listid)";
$queries[] = "CREATE INDEX %%TABLEPREFIX%%autoresponders_queue_idx on %%TABLEPREFIX%%autoresponders(queueid)";

$queries[] = "CREATE INDEX %%TABLEPREFIX%%banned_emails_list_idx on %%TABLEPREFIX%%banned_emails(list)";

$queries[] = "CREATE INDEX %%TABLEPREFIX%%customfield_lists_field_list_idx on %%TABLEPREFIX%%customfield_lists(fieldid, listid)";

$queries[] = "CREATE INDEX %%TABLEPREFIX%%customfields_owner_idx on %%TABLEPREFIX%%customfields(ownerid)";

$queries[] = "CREATE INDEX %%TABLEPREFIX%%form_customfields_formid_idx on %%TABLEPREFIX%%form_customfields(formid)";

$queries[] = "CREATE INDEX %%TABLEPREFIX%%form_lists_formid_idx on %%TABLEPREFIX%%form_lists(formid)";

$queries[] = "CREATE INDEX %%TABLEPREFIX%%form_pages_formid_idx on %%TABLEPREFIX%%form_pages(formid)";

$queries[] = "CREATE INDEX %%TABLEPREFIX%%jobs_fkid_idx on %%TABLEPREFIX%%jobs(fkid)";
$queries[] = "CREATE INDEX %%TABLEPREFIX%%jobs_jobtime_idx on %%TABLEPREFIX%%jobs(jobtime)";
$queries[] = "CREATE INDEX %%TABLEPREFIX%%jobs_queue_idx on %%TABLEPREFIX%%jobs(queueid)";
$queries[] = "CREATE INDEX %%TABLEPREFIX%%jobs_owner_idx on %%TABLEPREFIX%%jobs(ownerid)";

$queries[] = "CREATE INDEX %%TABLEPREFIX%%jobs_lists_listid_idx on %%TABLEPREFIX%%jobs_lists(listid)";
$queries[] = "CREATE INDEX %%TABLEPREFIX%%jobs_lists_jobid_idx on %%TABLEPREFIX%%jobs_lists(jobid)";

$queries[] = "CREATE INDEX %%TABLEPREFIX%%list_subscribers_unsubscribe_statid_idx on %%TABLEPREFIX%%list_subscribers_unsubscribe(statid)";

$queries[] = "CREATE INDEX %%TABLEPREFIX%%lists_owner_idx on %%TABLEPREFIX%%lists(ownerid)";

$queries[] = "CREATE INDEX %%TABLEPREFIX%%newsletters_owner_idx on %%TABLEPREFIX%%newsletters(ownerid)";

$queries[] = "CREATE INDEX %%TABLEPREFIX%%queues_id_type_idx on %%TABLEPREFIX%%queues(queueid, queuetype)";

$queries[] = "CREATE INDEX %%TABLEPREFIX%%stats_autoresponders_statid_idx on %%TABLEPREFIX%%stats_autoresponders(statid)";
$queries[] = "CREATE INDEX %%TABLEPREFIX%%stats_autoresponders_autoresponderid_idx on %%TABLEPREFIX%%stats_autoresponders(autoresponderid)";

$queries[] = "CREATE INDEX %%TABLEPREFIX%%stats_emailforwards_subscriberid_idx on %%TABLEPREFIX%%stats_emailforwards(subscriberid)";
$queries[] = "CREATE INDEX %%TABLEPREFIX%%stats_emailforwards_statid_idx on %%TABLEPREFIX%%stats_emailforwards(statid)";
$queries[] = "CREATE INDEX %%TABLEPREFIX%%stats_emailforwards_listid_idx on %%TABLEPREFIX%%stats_emailforwards(listid)";

$queries[] = "CREATE INDEX %%TABLEPREFIX%%stats_emailopens_subscriberid_idx on %%TABLEPREFIX%%stats_emailopens(subscriberid)";
$queries[] = "CREATE INDEX %%TABLEPREFIX%%stats_emailopens_statid_idx on %%TABLEPREFIX%%stats_emailopens(statid)";

$queries[] = "CREATE INDEX %%TABLEPREFIX%%user_stats_emailsperhour_statid_idx on %%TABLEPREFIX%%user_stats_emailsperhour(statid)";
$queries[] = "CREATE INDEX %%TABLEPREFIX%%user_stats_emailsperhour_userid_idx on %%TABLEPREFIX%%user_stats_emailsperhour(userid)";

$queries[] = "CREATE INDEX %%TABLEPREFIX%%stats_linkclicks_subscriberid_idx on %%TABLEPREFIX%%stats_linkclicks(subscriberid)";
$queries[] = "CREATE INDEX %%TABLEPREFIX%%stats_linkclicks_statid_idx on %%TABLEPREFIX%%stats_linkclicks(statid)";
$queries[] = "CREATE INDEX %%TABLEPREFIX%%stats_linkclicks_linkid_idx on %%TABLEPREFIX%%stats_linkclicks(linkid)";

$queries[] = "CREATE INDEX %%TABLEPREFIX%%stats_links_statid_idx on %%TABLEPREFIX%%stats_links(statid)";

$queries[] = "CREATE INDEX %%TABLEPREFIX%%stats_newsletter_lists_list_stat_idx on %%TABLEPREFIX%%stats_newsletter_lists(listid, statid)";

$queries[] = "CREATE INDEX %%TABLEPREFIX%%stats_newsletters_queue_idx on %%TABLEPREFIX%%stats_newsletters(queueid)";
$queries[] = "CREATE INDEX %%TABLEPREFIX%%stats_newsletters_sentby_idx on %%TABLEPREFIX%%stats_newsletters(sentby)";

$queries[] = "CREATE INDEX %%TABLEPREFIX%%stats_users_all_idx on %%TABLEPREFIX%%stats_users(userid, queuetime, queuesize)";
$queries[] = "CREATE INDEX %%TABLEPREFIX%%stats_users_statid_idx on %%TABLEPREFIX%%stats_users(statid)";

$queries[] = "CREATE INDEX %%TABLEPREFIX%%list_subscriber_bounces_statid_idx on %%TABLEPREFIX%%list_subscriber_bounces(statid)";
$queries[] = "CREATE INDEX %%TABLEPREFIX%%list_subscriber_bounces_listid_idx on %%TABLEPREFIX%%list_subscriber_bounces(listid)";
$queries[] = "CREATE INDEX %%TABLEPREFIX%%list_subscriber_bounces_subscriberid_idx on %%TABLEPREFIX%%list_subscriber_bounces(subscriberid)";

$queries[] = "CREATE INDEX %%TABLEPREFIX%%subscribers_data_subscriber_field_idx on %%TABLEPREFIX%%subscribers_data(subscriberid, fieldid)";

$queries[] = "CREATE INDEX %%TABLEPREFIX%%templates_owner_idx on %%TABLEPREFIX%%templates(ownerid)";

$queries[] = "CREATE INDEX %%TABLEPREFIX%%user_acce%%TABLEPREFIX%%userid_idx on %%TABLEPREFIX%%user_access(userid)";

$queries[] = "CREATE INDEX %%TABLEPREFIX%%user_permissions_userid_idx on %%TABLEPREFIX%%user_permissions(userid)";

$queries[] = "CREATE INDEX %%TABLEPREFIX%%users_logincheck_idx on %%TABLEPREFIX%%users(username, password)";

$queries[] = "create index %%TABLEPREFIX%%queues_id_type_recip_idx on %%TABLEPREFIX%%queues(queueid,queuetype,recipient)";

$queries[] = "create index %%TABLEPREFIX%%queues_id_type_processed_idx on %%TABLEPREFIX%%queues(queueid,queuetype,processed);";

$queries[] = "create index %%TABLEPREFIX%%banned_emails_list_email_idx on %%TABLEPREFIX%%banned_emails(list, emailaddress)";

$queries[] = "create index %%TABLEPREFIX%%customfield_id_name on %%TABLEPREFIX%%customfields(fieldid, name)";
?>
