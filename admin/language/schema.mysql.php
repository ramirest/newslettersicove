<?php
/**
* Schema for mysql databases.
*
* @version     $Id: schema.mysql.php,v 1.35 2007/06/18 03:25:44 chris Exp $

*
* @package SendStudio
* @subpackage Language
*/

/**
* Schema for mysql databases.
* DO NOT CHANGE BELOW THIS LINE.
*/

$queries = array();

# $queries[] = "DROP TABLE IF EXISTS %%TABLEPREFIX%%autoresponders";
$queries[] = "CREATE TABLE %%TABLEPREFIX%%autoresponders (
  autoresponderid int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name varchar(255) default NULL,
  subject varchar(255) default NULL,
  format char(1) default NULL,
  textbody text,
  htmlbody text,
  createdate int(11) default NULL,
  active int default NULL,
  hoursaftersubscription int(11) default NULL,
  ownerid int(11) NOT NULL default '0',
  searchcriteria text,
  listid int(11) default NULL,
  tracklinks char(1) default NULL,
  trackopens char(1) default NULL,
  multipart char(1) default NULL,
  queueid int(11) default NULL,
  sendfromname varchar(255) default NULL,
  sendfromemail varchar(255) default NULL,
  replytoemail varchar(255) default NULL,
  bounceemail varchar(255) default NULL,
  charset varchar(255) default NULL,
  embedimages char(1) default '0',
  to_firstname int default 0,
  to_lastname int default 0
) character set utf8";

# $queries[] = "DROP TABLE IF EXISTS %%TABLEPREFIX%%banned_emails";
$queries[] = "CREATE TABLE %%TABLEPREFIX%%banned_emails (
  banid int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  emailaddress varchar(255) default NULL,
  list varchar(10) default NULL,
  bandate int(11) default NULL
) character set utf8";

# $queries[] = "DROP TABLE IF EXISTS %%TABLEPREFIX%%customfield_lists";
$queries[] = "CREATE TABLE %%TABLEPREFIX%%customfield_lists (
  cflid int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  fieldid int(11) NOT NULL default '0',
  listid int(11) NOT NULL default '0'
) character set utf8";


# $queries[] = "DROP TABLE IF EXISTS %%TABLEPREFIX%%customfields";
$queries[] = "CREATE TABLE %%TABLEPREFIX%%customfields (
  fieldid int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name varchar(255) default NULL,
  fieldtype varchar(100) default NULL,
  defaultvalue varchar(255) default NULL,
  required char(1) default NULL,
  fieldsettings text,
  createdate int(11) default NULL,
  ownerid int(11) default NULL
) character set utf8";


# $queries[] = "DROP TABLE IF EXISTS %%TABLEPREFIX%%form_customfields";
$queries[] = "CREATE TABLE %%TABLEPREFIX%%form_customfields (
  formid int(11) default NULL,
  fieldid varchar(10) default NULL,
  fieldorder int default 0
) character set utf8";

# $queries[] = "DROP TABLE IF EXISTS %%TABLEPREFIX%%form_lists";
$queries[] = "CREATE TABLE %%TABLEPREFIX%%form_lists (
  formid int(11) default NULL,
  listid int(11) default NULL
) character set utf8";

# $queries[] = "DROP TABLE IF EXISTS %%TABLEPREFIX%%form_pages";
$queries[] = "CREATE TABLE %%TABLEPREFIX%%form_pages (
  pageid int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  formid int(11) default NULL,
  pagetype varchar(100) default NULL,
  html text,
  url varchar(255) default NULL,
  sendfromname varchar(255) default NULL,
  sendfromemail varchar(255) default NULL,
  replytoemail varchar(255) default NULL,
  bounceemail varchar(255) default NULL,
  emailsubject varchar(255) default NULL,
  emailhtml text,
  emailtext text
) character set utf8";

# $queries[] = "DROP TABLE IF EXISTS %%TABLEPREFIX%%forms";
$queries[] = "CREATE TABLE %%TABLEPREFIX%%forms (
  formid int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name varchar(255) default NULL,
  design varchar(255) default NULL,
  formhtml text,
  chooseformat varchar(2) default NULL,
  changeformat varchar(1) default 0,
  sendthanks varchar(1) default 0,
  requireconfirm varchar(1) default 0,
  ownerid int(11) default NULL,
  formtype char(1) default NULL,
  createdate int(11) default NULL,
  contactform varchar(1) default 0,
  usecaptcha varchar(1) default 0
) character set utf8";

# $queries[] = "DROP TABLE IF EXISTS %%TABLEPREFIX%%jobs";
$queries[] = "CREATE TABLE %%TABLEPREFIX%%jobs (
  jobid int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  jobtype varchar(255) default NULL,
  jobstatus char(1) default NULL,
  jobtime int(11) default NULL,
  jobdetails text,
  fkid int(11) default '0',
  lastupdatetime int(11) default '0',
  fktype varchar(255) default NULL,
  queueid int(11) default '0',
  ownerid int(11) default NULL,
  approved int default 0,
  authorisedtosend int default 0
) character set utf8";

# $queries[] = "DROP TABLE IF EXISTS %%TABLEPREFIX%%jobs_lists";
$queries[] = "CREATE TABLE %%TABLEPREFIX%%jobs_lists (
  jobid int(11) default NULL,
  listid int(11) default NULL
) character set utf8";

# $queries[] = "DROP TABLE IF EXISTS %%TABLEPREFIX%%list_subscribers";
$queries[] = "CREATE TABLE %%TABLEPREFIX%%list_subscribers (
  subscriberid int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  listid int(11) NOT NULL default '0',
  emailaddress varchar(200),
  format char(1) default NULL,
  confirmed char(1) default NULL,
  confirmcode varchar(32) default NULL,
  requestdate int(11) default '0',
  requestip varchar(20) default NULL,
  confirmdate int(11) default '0',
  confirmip varchar(20) default NULL,
  subscribedate int(11) default '0',
  bounced int(11) default '0',
  unsubscribed int(11) default '0',
  unsubscribeconfirmed char(1) default NULL,
  formid int(11) default '0'
) character set utf8";

# $queries[] = "DROP TABLE IF EXISTS %%TABLEPREFIX%%list_subscribers_unsubscribe";
$queries[] = "CREATE TABLE %%TABLEPREFIX%%list_subscribers_unsubscribe (
  subscriberid int(11) NOT NULL default '0',
  unsubscribetime int(11) NOT NULL default '0',
  unsubscribeip varchar(20) default NULL,
  unsubscriberequesttime int(11) default '0',
  unsubscriberequestip varchar(20) default NULL,
  listid int(11) NOT NULL default '0',
  statid int(11) default '0',
  unsubscribearea varchar(20) default NULL
) character set utf8";

# $queries[] = "DROP TABLE IF EXISTS %%TABLEPREFIX%%lists";
$queries[] = "CREATE TABLE %%TABLEPREFIX%%lists (
  listid int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name varchar(255) default NULL,
  ownername varchar(255) default NULL,
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
  createdate int(11) default NULL,
  subscribecount int(11) default '0',
  unsubscribecount int(11) default '0',
  bouncecount int(11) default '0',
  ownerid int(11) default 0
) character set utf8";

# $queries[] = "DROP TABLE IF EXISTS %%TABLEPREFIX%%newsletters";
$queries[] = "CREATE TABLE %%TABLEPREFIX%%newsletters (
  newsletterid int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name varchar(255) default NULL,
  format char(1) default NULL,
  subject varchar(255) default NULL,
  textbody text,
  htmlbody text,
  createdate int(11) default NULL,
  active int default NULL,
  archive int default NULL,
  ownerid int(11) NOT NULL default '0'
) character set utf8";

# $queries[] = "DROP TABLE IF EXISTS %%TABLEPREFIX%%queues";
$queries[] = "CREATE TABLE %%TABLEPREFIX%%queues (
  queueid int(11) default '0',
  queuetype varchar(255) default NULL,
  ownerid int(11) default NULL,
  recipient int(11) default NULL,
  processed char(1) default '0',
  sent char(1) default '0',
  processtime datetime
) character set utf8";

# $queries[] = "DROP TABLE IF EXISTS %%TABLEPREFIX%%queues_sequence";
$queries[] = "CREATE TABLE %%TABLEPREFIX%%queues_sequence (
  id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY
) character set utf8";
$queries[] = "INSERT INTO %%TABLEPREFIX%%queues_sequence(id) VALUES (0)";

# $queries[] = "DROP TABLE IF EXISTS %%TABLEPREFIX%%settings";
$queries[] = "CREATE TABLE %%TABLEPREFIX%%settings (
  cronok char(1) default '0',
  cronrun1 int(11) default '0',
  cronrun2 int(11) default '0',
  database_version int default '0'
) character set utf8";
$queries[] = "INSERT INTO %%TABLEPREFIX%%settings(cronok, cronrun1, cronrun2, database_version) VALUES (0, 0, 0, " . SENDSTUDIO_DATABASE_VERSION . ")";

# $queries[] = "DROP TABLE IF EXISTS %%TABLEPREFIX%%list_subscriber_bounces";
$queries[] = "CREATE TABLE %%TABLEPREFIX%%list_subscriber_bounces (
  bounceid int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  subscriberid int(11) default NULL,
  statid int(11) default NULL,
  listid int(11) default NULL,
  bouncetime int(11) default NULL,
  bouncetype varchar(255) default NULL,
  bouncerule varchar(255) default NULL,
  bouncemessage text
) character set utf8";

# $queries[] = "DROP TABLE IF EXISTS %%TABLEPREFIX%%stats_autoresponders";
$queries[] = "CREATE TABLE %%TABLEPREFIX%%stats_autoresponders (
  statid int(11) NOT NULL default '0',
  htmlrecipients int(11) default '0',
  textrecipients int(11) default '0',
  multipartrecipients int(11) default '0',
  bouncecount_soft int(11) default '0',
  bouncecount_hard int(11) default '0',
  bouncecount_unknown int(11) default '0',
  unsubscribecount int(11) default '0',
  autoresponderid int(11) default '0',
  linkclicks int(11) default '0',
  emailopens int(11) default '0',
  emailforwards int(11) default '0',
  emailopens_unique int(11) default '0',
  hiddenby int default 0
) character set utf8";

# $queries[] = "DROP TABLE IF EXISTS %%TABLEPREFIX%%stats_emailopens";
$queries[] = "CREATE TABLE %%TABLEPREFIX%%stats_emailopens (
  openid int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  subscriberid int(11) default NULL,
  statid int(11) default NULL,
  opentime int(11) default NULL,
  openip varchar(20) default NULL
) character set utf8";

# $queries[] = "DROP TABLE IF EXISTS %%TABLEPREFIX%%stats_linkclicks";
$queries[] = "CREATE TABLE %%TABLEPREFIX%%stats_linkclicks (
  clickid int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  clicktime int(11) default NULL,
  clickip varchar(20) default NULL,
  subscriberid int(11) default NULL,
  statid int(11) default NULL,
  linkid int(11) default NULL
) character set utf8";

# $queries[] = "DROP TABLE IF EXISTS %%TABLEPREFIX%%stats_links";
$queries[] = "CREATE TABLE %%TABLEPREFIX%%stats_links (
  statid int(11) default 0,
  linkid int(11) default 0
) character set utf8";

# $queries[] = "DROP TABLE IF EXISTS %%TABLEPREFIX%%links";
$queries[] = "CREATE TABLE %%TABLEPREFIX%%links (
  linkid int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  url varchar(255) default NULL
) character set utf8";

# $queries[] = "DROP TABLE IF EXISTS %%TABLEPREFIX%%stats_newsletter_lists";
$queries[] = "CREATE TABLE %%TABLEPREFIX%%stats_newsletter_lists (
  statid int(11) default NULL,
  listid int(11) default NULL
) character set utf8";

# $queries[] = "DROP TABLE IF EXISTS %%TABLEPREFIX%%stats_newsletters";
$queries[] = "CREATE TABLE %%TABLEPREFIX%%stats_newsletters (
  statid int(11) NOT NULL default '0',
  queueid int(11) default NULL,
  starttime int(11) default NULL,
  finishtime int(11) default NULL,
  htmlrecipients int(11) default '0',
  textrecipients int(11) default '0',
  multipartrecipients int(11) default '0',
  trackopens char(1) default '0',
  tracklinks char(1) default '0',
  bouncecount_soft int(11) default '0',
  bouncecount_hard int(11) default '0',
  bouncecount_unknown int(11) default '0',
  unsubscribecount int(11) default '0',
  newsletterid int(11) default NULL,
  sendfromname varchar(200) default NULL,
  sendfromemail varchar(200) default NULL,
  bounceemail varchar(200) default NULL,
  replytoemail varchar(200) default NULL,
  charset varchar(200) default NULL,
  sendinformation text,
  sendsize int(11) default NULL,
  sentby int(11) default NULL,
  notifyowner char(1) default NULL,
  linkclicks int(11) default '0',
  emailopens int(11) default '0',
  emailforwards int(11) default '0',
  emailopens_unique int(11) default '0',
  hiddenby int default 0,
  PRIMARY KEY (statid)
) character set utf8";

# $queries[] = "DROP TABLE IF EXISTS %%TABLEPREFIX%%stats_sequence";
$queries[] = "CREATE TABLE %%TABLEPREFIX%%stats_sequence (
  id int(11) NOT NULL auto_increment,
  PRIMARY KEY (id)
) character set utf8";
$queries[] = "INSERT INTO %%TABLEPREFIX%%stats_sequence(id) VALUES (0)";

# $queries[] = "DROP TABLE IF EXISTS %%TABLEPREFIX%%stats_users";
$queries[] = "CREATE TABLE %%TABLEPREFIX%%stats_users (
  userid int(11) default NULL,
  statid int(11) default NULL,
  jobid int(11) default NULL,
  queuesize int(11) default NULL,
  queuetime int(11) default NULL
) character set utf8";

# $queries[] = "DROP TABLE IF EXISTS %%TABLEPREFIX%%subscribers_data";
$queries[] = "CREATE TABLE %%TABLEPREFIX%%subscribers_data (
  subscriberid int(11) NOT NULL default '0',
  fieldid int(11) NOT NULL default '0',
  data text
) character set utf8";

# $queries[] = "DROP TABLE IF EXISTS %%TABLEPREFIX%%templates";
$queries[] = "CREATE TABLE %%TABLEPREFIX%%templates (
  templateid int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name varchar(255) default NULL,
  format char(1) default NULL,
  textbody text,
  htmlbody text,
  createdate int(11) default NULL,
  active int default NULL,
  isglobal int default NULL,
  ownerid int(11) NOT NULL default '0'
) character set utf8";

# $queries[] = "DROP TABLE IF EXISTS %%TABLEPREFIX%%user_access";
$queries[] = "CREATE TABLE %%TABLEPREFIX%%user_access (
  userid int(11) default '0',
  area varchar(255) default NULL,
  id int(11) default '0'
) character set utf8";

# $queries[] = "DROP TABLE IF EXISTS %%TABLEPREFIX%%user_permissions";
$queries[] = "CREATE TABLE %%TABLEPREFIX%%user_permissions (
  userid int(11) default '0',
  area varchar(255) default NULL,
  subarea varchar(255) default NULL
) character set utf8";

# $queries[] = "DROP TABLE IF EXISTS %%TABLEPREFIX%%users";
$queries[] = "CREATE TABLE %%TABLEPREFIX%%users (
  userid int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  username varchar(255) default NULL,
  password varchar(32) default NULL,
  admintype char(1) default NULL,
  listadmintype char(1) default NULL,
  templateadmintype char(1) default NULL,
  status char(1) default NULL,
  fullname varchar(255) default NULL,
  emailaddress varchar(100) default NULL,
  settings text,
  editownsettings char(1) default NULL,
  usertimezone varchar(10) default NULL,
  textfooter varchar(255) default NULL,
  htmlfooter varchar(255) default NULL,
  maxlists int(11) default '0',
  perhour int(11) default '0',
  permonth int(11) default '0',
  unlimitedmaxemails char(1) default '0',
  maxemails int(11) default '0',
  infotips char(1) default NULL,
  smtpserver varchar(255) default NULL,
  smtpusername varchar(255) default NULL,
  smtppassword varchar(255) default NULL,
  smtpport int(11) default '0',
  createdate int(11) default '0',
  lastloggedin int(11) default '0',
  forgotpasscode char(32) DEFAULT ''
) character set utf8";

$queries[] = "INSERT INTO %%TABLEPREFIX%%users(username, password, status, admintype, listadmintype, templateadmintype, createdate, lastloggedin, unlimitedmaxemails, infotips) values('admin', md5('password'), '1', 'a', 'a', 'a', UNIX_TIMESTAMP(NOW()), '0', '1', '1')";

# $queries[] = "DROP TABLE IF EXISTS %%TABLEPREFIX%%stats_emailforwards";
$queries[] = "CREATE TABLE %%TABLEPREFIX%%stats_emailforwards (
  forwardid INT AUTO_INCREMENT PRIMARY KEY,
  forwardtime INT,
  forwardip VARCHAR(20),
  subscriberid INT,
  statid INT,
  subscribed INT,
  listid INT,
  emailaddress VARCHAR(255)
) character set utf8";

# $queries[] = "DROP TABLE IF EXISTS %%TABLEPREFIX%%user_stats_emailsperhour";
$queries[] = "CREATE TABLE %%TABLEPREFIX%%user_stats_emailsperhour (
	summaryid INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	statid INT DEFAULT '0',
	sendtime INT DEFAULT '0',
	emailssent INT DEFAULT '0',
	userid INT DEFAULT '0'
) character set utf8";

require(dirname(__FILE__) . '/schema.indexes.php');

?>
