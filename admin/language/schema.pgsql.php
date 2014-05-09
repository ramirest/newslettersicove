<?php
/**
* Schema for postgresql databases.
*
* @version     $Id: schema.pgsql.php,v 1.29 2007/06/18 03:25:44 chris Exp $

*
* @package SendStudio
* @subpackage Language
*/

/**
* Schema for postgresql databases.
* DO NOT CHANGE BELOW THIS LINE.
*/

$queries = array();

$queries[] = "CREATE SEQUENCE %%TABLEPREFIX%%autoresponders_sequence";
$queries[] = "CREATE TABLE %%TABLEPREFIX%%autoresponders (
  autoresponderid int DEFAULT nextval('%%TABLEPREFIX%%autoresponders_sequence') NOT NULL PRIMARY KEY,
  name varchar default NULL,
  subject varchar default NULL,
  format char(1) default NULL,
  textbody text,
  htmlbody text,
  createdate int default NULL,
  active int default NULL,
  hoursaftersubscription int default NULL,
  ownerid int NOT NULL default '0',
  searchcriteria text,
  listid int default NULL,
  tracklinks char(1) default NULL,
  trackopens char(1) default NULL,
  multipart char(1) default NULL,
  queueid int default NULL,
  sendfromname varchar default NULL,
  sendfromemail varchar default NULL,
  replytoemail varchar default NULL,
  bounceemail varchar default NULL,
  charset varchar default NULL,
  embedimages char(1) default '0',
  to_firstname int default 0,
  to_lastname int default 0
)";

$queries[] = "CREATE SEQUENCE %%TABLEPREFIX%%banned_emails_sequence";
$queries[] = "CREATE TABLE %%TABLEPREFIX%%banned_emails (
  banid int DEFAULT nextval('%%TABLEPREFIX%%banned_emails_sequence') NOT NULL PRIMARY KEY,
  emailaddress varchar default NULL,
  list varchar(10) default NULL,
  bandate int default NULL
)";

$queries[] = "CREATE SEQUENCE %%TABLEPREFIX%%customfield_lists_sequence";
$queries[] = "CREATE TABLE %%TABLEPREFIX%%customfield_lists (
  cflid int DEFAULT nextval('%%TABLEPREFIX%%customfield_lists_sequence') NOT NULL PRIMARY KEY,
  fieldid int NOT NULL default '0',
  listid int NOT NULL default '0'
)";


$queries[] = "CREATE SEQUENCE %%TABLEPREFIX%%customfields_sequence";
$queries[] = "CREATE TABLE %%TABLEPREFIX%%customfields (
  fieldid int DEFAULT nextval('%%TABLEPREFIX%%customfields_sequence') NOT NULL PRIMARY KEY,
  name varchar default NULL,
  fieldtype varchar(100) default NULL,
  defaultvalue varchar default NULL,
  required char(1) default NULL,
  fieldsettings text,
  createdate int default NULL,
  ownerid int default NULL
)";


$queries[] = "CREATE TABLE %%TABLEPREFIX%%form_customfields (
  formid int default NULL,
  fieldid varchar(10) default NULL,
  fieldorder int default 0
)";

$queries[] = "CREATE TABLE %%TABLEPREFIX%%form_lists (
  formid int default NULL,
  listid int default NULL
)";

$queries[] = "CREATE SEQUENCE %%TABLEPREFIX%%form_pages_sequence";
$queries[] = "CREATE TABLE %%TABLEPREFIX%%form_pages (
  pageid int DEFAULT nextval('%%TABLEPREFIX%%form_pages_sequence') NOT NULL PRIMARY KEY,
  formid int default NULL,
  pagetype varchar(100) default NULL,
  html text,
  url varchar default NULL,
  sendfromname varchar default NULL,
  sendfromemail varchar default NULL,
  replytoemail varchar default NULL,
  bounceemail varchar default NULL,
  emailsubject varchar default NULL,
  emailhtml text,
  emailtext text
)";

$queries[] = "CREATE SEQUENCE %%TABLEPREFIX%%forms_sequence";
$queries[] = "CREATE TABLE %%TABLEPREFIX%%forms (
  formid int DEFAULT nextval('%%TABLEPREFIX%%forms_sequence') NOT NULL PRIMARY KEY,
  name varchar default NULL,
  design varchar default NULL,
  formhtml text,
  chooseformat varchar(2) default NULL,
  changeformat varchar(1) default 0,
  sendthanks varchar(1) default 0,
  requireconfirm char(1) default 0,
  ownerid int default 0,
  formtype char(1) default NULL,
  createdate int default NULL,
  contactform varchar(1) default 0,
  usecaptcha varchar(1) default 0
)";

$queries[] = "CREATE SEQUENCE %%TABLEPREFIX%%jobs_sequence";
$queries[] = "CREATE TABLE %%TABLEPREFIX%%jobs (
  jobid int DEFAULT nextval('%%TABLEPREFIX%%jobs_sequence') NOT NULL PRIMARY KEY,
  jobtype varchar default NULL,
  jobstatus char(1) default NULL,
  jobtime int default NULL,
  jobdetails text,
  fkid int default '0',
  lastupdatetime int default '0',
  fktype varchar default NULL,
  queueid int default '0',
  ownerid int default NULL,
  approved int default 0,
  authorisedtosend int default 0
)";

$queries[] = "CREATE TABLE %%TABLEPREFIX%%jobs_lists (
  jobid int default NULL,
  listid int default NULL
)";

$queries[] = "CREATE SEQUENCE %%TABLEPREFIX%%list_subscribers_sequence";
$queries[] = "CREATE TABLE %%TABLEPREFIX%%list_subscribers (
  subscriberid int DEFAULT nextval('%%TABLEPREFIX%%list_subscribers_sequence') NOT NULL PRIMARY KEY,
  listid int NOT NULL default '0',
  emailaddress varchar(200),
  format char(1) default NULL,
  confirmed char(1) default NULL,
  confirmcode varchar(32) default NULL,
  requestdate int default '0',
  requestip varchar(20) default NULL,
  confirmdate int default '0',
  confirmip varchar(20) default NULL,
  subscribedate int default '0',
  bounced int default '0',
  unsubscribed int default '0',
  unsubscribeconfirmed char(1) default NULL,
  formid int default '0'
)";

$queries[] = "CREATE TABLE %%TABLEPREFIX%%list_subscribers_unsubscribe (
  subscriberid int NOT NULL default '0',
  unsubscribetime int NOT NULL default '0',
  unsubscribeip varchar(20) default NULL,
  unsubscriberequesttime int default '0',
  unsubscriberequestip varchar(20) default NULL,
  listid int NOT NULL default '0',
  statid int default '0',
  unsubscribearea varchar(20) default NULL
)";

$queries[] = "CREATE SEQUENCE %%TABLEPREFIX%%lists_sequence";
$queries[] = "CREATE TABLE %%TABLEPREFIX%%lists (
  listid int DEFAULT nextval('%%TABLEPREFIX%%lists_sequence') NOT NULL PRIMARY KEY,
  name varchar default NULL,
  ownername varchar default NULL,
  owneremail varchar(100) default NULL,
  bounceemail varchar(100) default NULL,
  replytoemail varchar(100) default NULL,
  bounceserver varchar(100) default NULL,
  bounceusername varchar(100) default NULL,
  bouncepassword varchar(100) default NULL,
  extramailsettings varchar(100) default NULL,
  format char(1) default NULL,
  notifyowner char(1) default NULL,
  imapaccount char(1) default 0,
  createdate int default NULL,
  subscribecount int default '0',
  unsubscribecount int default '0',
  bouncecount int default '0',
  ownerid int default 0
)";

$queries[] = "CREATE SEQUENCE %%TABLEPREFIX%%newsletters_sequence";
$queries[] = "CREATE TABLE %%TABLEPREFIX%%newsletters (
  newsletterid int DEFAULT nextval('%%TABLEPREFIX%%newsletters_sequence') NOT NULL PRIMARY KEY,
  name varchar default NULL,
  format char(1) default NULL,
  subject varchar default NULL,
  textbody text,
  htmlbody text,
  createdate int default NULL,
  active int default NULL,
  archive int default NULL,
  ownerid int NOT NULL default '0'
)";

$queries[] = "CREATE TABLE %%TABLEPREFIX%%queues (
  queueid int default '0',
  queuetype varchar default NULL,
  ownerid int default NULL,
  recipient int default NULL,
  processed char(1) default '0',
  sent char(1) default '0',
  processtime timestamp
)";

$queries[] = "CREATE SEQUENCE %%TABLEPREFIX%%queues_sequence";

$queries[] = "CREATE TABLE %%TABLEPREFIX%%settings (
  cronok char(1) default '0',
  cronrun1 int default '0',
  cronrun2 int default '0',
  database_version int default '0'
)";
$queries[] = "INSERT INTO %%TABLEPREFIX%%settings(cronok, cronrun1, cronrun2, database_version) VALUES (0, 0, 0, " . SENDSTUDIO_DATABASE_VERSION . ")";

$queries[] = "CREATE SEQUENCE %%TABLEPREFIX%%list_subscriber_bounces_sequence";
$queries[] = "CREATE TABLE %%TABLEPREFIX%%list_subscriber_bounces (
  bounceid int DEFAULT nextval('%%TABLEPREFIX%%list_subscriber_bounces_sequence') NOT NULL PRIMARY KEY,
  subscriberid int default NULL,
  statid int default NULL,
  listid int default NULL,
  bouncetime int default NULL,
  bouncetype varchar default NULL,
  bouncerule varchar default NULL,
  bouncemessage text
)";

$queries[] = "CREATE TABLE %%TABLEPREFIX%%stats_autoresponders (
  statid int NOT NULL default '0',
  htmlrecipients int default '0',
  textrecipients int default '0',
  multipartrecipients int default '0',
  bouncecount_soft int default '0',
  bouncecount_hard int default '0',
  bouncecount_unknown int default '0',
  unsubscribecount int default '0',
  autoresponderid int default '0',
  linkclicks int default '0',
  emailopens int default '0',
  emailforwards int default '0',
  emailopens_unique int default '0',
  hiddenby int default 0
)";

$queries[] = "CREATE SEQUENCE %%TABLEPREFIX%%stats_emailopens_sequence";
$queries[] = "CREATE TABLE %%TABLEPREFIX%%stats_emailopens (
  openid int DEFAULT nextval('%%TABLEPREFIX%%stats_emailopens_sequence') NOT NULL PRIMARY KEY,
  subscriberid int default NULL,
  statid int default NULL,
  opentime int default NULL,
  openip varchar(20) default NULL
)";

$queries[] = "CREATE SEQUENCE %%TABLEPREFIX%%stats_linkclicks_sequence";
$queries[] = "CREATE TABLE %%TABLEPREFIX%%stats_linkclicks (
  clickid int DEFAULT nextval('%%TABLEPREFIX%%stats_linkclicks_sequence') NOT NULL PRIMARY KEY,
  clicktime int default NULL,
  clickip varchar(20) default NULL,
  subscriberid int default NULL,
  statid int default NULL,
  linkid int default NULL
)";

$queries[] = "CREATE SEQUENCE %%TABLEPREFIX%%links_sequence";
$queries[] = "CREATE TABLE %%TABLEPREFIX%%links (
  linkid int DEFAULT nextval('%%TABLEPREFIX%%links_sequence') NOT NULL PRIMARY KEY,
  url varchar default NULL
)";

$queries[] = "CREATE TABLE %%TABLEPREFIX%%stats_links (
  statid int default NULL,
  linkid int default NULL
)";

$queries[] = "CREATE TABLE %%TABLEPREFIX%%stats_newsletter_lists (
  statid int default NULL,
  listid int default NULL
)";

$queries[] = "CREATE TABLE %%TABLEPREFIX%%stats_newsletters (
  statid int NOT NULL default '0' PRIMARY KEY,
  queueid int default NULL,
  starttime int default NULL,
  finishtime int default NULL,
  htmlrecipients int default '0',
  textrecipients int default '0',
  multipartrecipients int default '0',
  trackopens char(1) default '0',
  tracklinks char(1) default '0',
  bouncecount_soft int default '0',
  bouncecount_hard int default '0',
  bouncecount_unknown int default '0',
  unsubscribecount int default '0',
  newsletterid int default NULL,
  sendfromname varchar(200) default NULL,
  sendfromemail varchar(200) default NULL,
  bounceemail varchar(200) default NULL,
  replytoemail varchar(200) default NULL,
  charset varchar(200) default NULL,
  sendinformation text,
  sendsize int default NULL,
  sentby int default NULL,
  notifyowner char(1) default NULL,
  linkclicks int default '0',
  emailopens int default '0',
  emailforwards int default '0',
  emailopens_unique int default '0',
  hiddenby int default 0
)";

$queries[] = "CREATE SEQUENCE %%TABLEPREFIX%%stats_sequence";

$queries[] = "CREATE TABLE %%TABLEPREFIX%%stats_users (
  userid int default NULL,
  statid int default NULL,
  jobid int default NULL,
  queuesize int default NULL,
  queuetime int default NULL
)";

$queries[] = "CREATE TABLE %%TABLEPREFIX%%subscribers_data (
  subscriberid int NOT NULL default '0',
  fieldid int NOT NULL default '0',
  data text
)";

$queries[] = "CREATE SEQUENCE %%TABLEPREFIX%%templates_sequence";
$queries[] = "CREATE TABLE %%TABLEPREFIX%%templates (
  templateid int DEFAULT nextval('%%TABLEPREFIX%%templates_sequence') NOT NULL PRIMARY KEY,
  name varchar default NULL,
  format char(1) default NULL,
  textbody text,
  htmlbody text,
  createdate int default NULL,
  active int default NULL,
  isglobal int default NULL,
  ownerid int NOT NULL default '0'
)";

$queries[] = "CREATE TABLE %%TABLEPREFIX%%user_access (
  userid int default '0',
  area varchar default NULL,
  id int default '0'
)";

$queries[] = "CREATE TABLE %%TABLEPREFIX%%user_permissions (
  userid int default '0',
  area varchar default NULL,
  subarea varchar default NULL
)";

$queries[] = "CREATE SEQUENCE %%TABLEPREFIX%%users_sequence";
$queries[] = "CREATE TABLE %%TABLEPREFIX%%users (
  userid int DEFAULT nextval('%%TABLEPREFIX%%users_sequence') NOT NULL PRIMARY KEY,
  username varchar default NULL,
  password varchar(32) default NULL,
  admintype char(1) default NULL,
  listadmintype char(1) default NULL,
  templateadmintype char(1) default NULL,
  status char(1) default NULL,
  fullname varchar default NULL,
  emailaddress varchar(100) default NULL,
  settings text,
  editownsettings char(1) default NULL,
  usertimezone varchar(10) default NULL,
  textfooter varchar default NULL,
  htmlfooter varchar default NULL,
  maxlists int default '0',
  perhour int default '0',
  permonth int default '0',
  unlimitedmaxemails char(1) default '0',
  maxemails int default '0',
  infotips char(1) default NULL,
  smtpserver varchar default NULL,
  smtpusername varchar default NULL,
  smtppassword varchar default NULL,
  smtpport int default '0',
  createdate int default '0',
  lastloggedin int default '0',
  forgotpasscode char(32) DEFAULT ''
)";

$time = time();

$queries[] = "INSERT INTO %%TABLEPREFIX%%users(username, password, status, admintype, listadmintype, templateadmintype, createdate, lastloggedin, unlimitedmaxemails, infotips) values('admin', md5('password'), '1', 'a', 'a', 'a', " . $time . ", '0', '1', '1')";

$queries[] = "CREATE SEQUENCE %%TABLEPREFIX%%stats_emailforwards_sequence";
$queries[] = "CREATE TABLE %%TABLEPREFIX%%stats_emailforwards (
  forwardid int DEFAULT nextval('%%TABLEPREFIX%%stats_emailforwards_sequence') NOT NULL PRIMARY KEY,
  forwardtime INT,
  forwardip VARCHAR(20),
  subscriberid INT,
  statid INT,
  subscribed INT,
  listid INT,
  emailaddress varchar
)";

$queries[] = "CREATE SEQUENCE %%TABLEPREFIX%%user_stats_emailsperhour_sequence";
$queries[] = "CREATE TABLE %%TABLEPREFIX%%user_stats_emailsperhour (
  summaryid int DEFAULT nextval('%%TABLEPREFIX%%user_stats_emailsperhour_sequence') NOT NULL PRIMARY KEY,
  statid INT DEFAULT '0',
  sendtime INT DEFAULT '0',
  emailssent INT DEFAULT '0',
  userid INT DEFAULT '0'
)";

require(dirname(__FILE__) . '/schema.indexes.php');

?>
