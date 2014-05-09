<?php

/**
* These are the bounce rules we maintain for processing bounces.
*
* ORDER OF THESE RULES DOES MATTER.
*
* They will be processed in the order they appear in this file.
*
* Do NOT change the rule type.
* Those types are used for statistics so you can see what type of bounces you are getting.
*
* The valid rule types are:
* - emaildoesntexist (eg "user doesn't exist" or "account doesn't exist" or "invalid user")
* - domaindoesntexist (eg "unknown domain name")
* - invalidemail
* - overquota
* - relayerror
* - inactive
*
*
*******************************************************************
*         HOW TO ADD A NEW RULE TO YOUR BOUNCE PROCESSING         *
*******************************************************************
*
* To add a new rule, find a consistent message in the bounced email - for example:
* "User Does Not Exist"
* do NOT include ip addresses or server names in the rule because if you get a similar bounce from another server, it will not pick up properly.
*
* Then find the section you want to add it to, for example:
* $GLOBALS['BOUNCE_RULES']['hard']['emaildoesntexist']
* Copy a line like the one you want to modify, and change the rule:
*
* $GLOBALS['BOUNCE_RULES']['hard']['emaildoesntexist'][] = 'your new rule goes here';
*
* Please add new rules to the user_bounce_rules.php file between the applicable lines
* so your new rules don't get overwritten with the next sendstudio update.
*/

/*
* Set up the arrays. Don't need to do anything else here.
*/
$GLOBALS['BOUNCE_RULES'] = array(
	'soft' => array(
		'inactive' => array(),
		'overquota'  => array(),
		'blockedcontent' => array()
	),
	'hard' => array(
		// we distinguish between the types so we can see the differences in the statistics area.
		'emaildoesntexist' => array(),
		'domaindoesntexist' => array(),
		'relayerror' => array(),
		'invalidemail' => array()
	)
);

$GLOBALS['BOUNCE_RULES']['soft']['inactive'][] = 'user account disabled';
$GLOBALS['BOUNCE_RULES']['soft']['inactive'][] = 'this account has been disabled or discontinued';
$GLOBALS['BOUNCE_RULES']['soft']['inactive'][] = 'user account is expired';
$GLOBALS['BOUNCE_RULES']['soft']['inactive'][] = 'User is inactive';
$GLOBALS['BOUNCE_RULES']['soft']['inactive'][] = 'inactive user';
$GLOBALS['BOUNCE_RULES']['soft']['inactive'][] = 'extended inactivity new mail is not currently being accepted';
$GLOBALS['BOUNCE_RULES']['soft']['inactive'][] = 'Sorry, I wasn\'t able to establish an SMTP connection';
$GLOBALS['BOUNCE_RULES']['soft']['inactive'][] = 'message refused';
$GLOBALS['BOUNCE_RULES']['soft']['inactive'][] = 'permission denied';
$GLOBALS['BOUNCE_RULES']['soft']['inactive'][] = 'user mailbox is inactive';
$GLOBALS['BOUNCE_RULES']['soft']['inactive'][] = 'mailbox temporarily disabled';
$GLOBALS['BOUNCE_RULES']['soft']['inactive'][] = 'Blocked address';
$GLOBALS['BOUNCE_RULES']['soft']['inactive'][] = 'Account inactive as unread';
$GLOBALS['BOUNCE_RULES']['soft']['inactive'][] = 'Account inactive';
$GLOBALS['BOUNCE_RULES']['soft']['inactive'][] = 'account expired';
$GLOBALS['BOUNCE_RULES']['soft']['inactive'][] = 'User hasn\'t entered during last ';
$GLOBALS['BOUNCE_RULES']['soft']['inactive'][] = 'Account closed due to inactivity';
$GLOBALS['BOUNCE_RULES']['soft']['inactive'][] = 'This account is not allowed';
$GLOBALS['BOUNCE_RULES']['soft']['inactive'][] = 'Mailbox_currently_suspended';
$GLOBALS['BOUNCE_RULES']['soft']['inactive'][] = 'Mailbox disabled';
$GLOBALS['BOUNCE_RULES']['soft']['inactive'][] = 'Mailaddress is administratively disabled';
$GLOBALS['BOUNCE_RULES']['soft']['inactive'][] = 'Mailbox currently suspended';
$GLOBALS['BOUNCE_RULES']['soft']['inactive'][] = 'Account has been suspended';
$GLOBALS['BOUNCE_RULES']['hard']['inactive'][] = 'account is not active';

$GLOBALS['BOUNCE_RULES']['soft']['overquota'][] = 'Quota exceeded';
$GLOBALS['BOUNCE_RULES']['soft']['overquota'][] = 'user is over quota';
$GLOBALS['BOUNCE_RULES']['soft']['overquota'][] = 'exceeds size limit';
$GLOBALS['BOUNCE_RULES']['soft']['overquota'][] = 'user has full mailbox';
$GLOBALS['BOUNCE_RULES']['soft']['overquota'][] = 'Mailbox disk quota exceeded';
$GLOBALS['BOUNCE_RULES']['soft']['overquota'][] = 'over the allowed quota';
$GLOBALS['BOUNCE_RULES']['soft']['overquota'][] = 'User mailbox exceeds allowed size';
$GLOBALS['BOUNCE_RULES']['soft']['overquota'][] = 'does not have enough space';
$GLOBALS['BOUNCE_RULES']['soft']['overquota'][] = 'mailbox is full';
$GLOBALS['BOUNCE_RULES']['soft']['overquota'][] = 'Can\'t create output';
$GLOBALS['BOUNCE_RULES']['soft']['overquota'][] = 'mailbox full';
$GLOBALS['BOUNCE_RULES']['soft']['overquota'][] = 'File too large';
$GLOBALS['BOUNCE_RULES']['soft']['overquota'][] = 'too many messages on this mailbox';
$GLOBALS['BOUNCE_RULES']['soft']['overquota'][] = 'too many messages in this mailbox';
$GLOBALS['BOUNCE_RULES']['soft']['overquota'][] = 'Not enough storage space';
$GLOBALS['BOUNCE_RULES']['soft']['overquota'][] = 'Over quota';
$GLOBALS['BOUNCE_RULES']['soft']['overquota'][] = 'over the maximum allowed number of messages';
$GLOBALS['BOUNCE_RULES']['soft']['overquota'][] = 'Recipient exceeded email quota';
$GLOBALS['BOUNCE_RULES']['soft']['overquota'][] = 'The user has not enough diskspace available';
$GLOBALS['BOUNCE_RULES']['soft']['overquota'][] = 'Mailbox has exceeded the limit';
$GLOBALS['BOUNCE_RULES']['soft']['overquota'][] = 'exceeded storage allocation';
$GLOBALS['BOUNCE_RULES']['soft']['overquota'][] = 'Quota violation';
$GLOBALS['BOUNCE_RULES']['soft']['overquota'][] = '522_mailbox_full';
$GLOBALS['BOUNCE_RULES']['soft']['overquota'][] = 'account is full';
$GLOBALS['BOUNCE_RULES']['soft']['overquota'][] = 'incoming mailbox for user (.*) is full';
$GLOBALS['BOUNCE_RULES']['soft']['overquota'][] = 'message would exceed quota';
$GLOBALS['BOUNCE_RULES']['soft']['overquota'][] = 'recipient exceeded dropfile size quota';
$GLOBALS['BOUNCE_RULES']['soft']['overquota'][] = 'not able to receive any more mail';
$GLOBALS['BOUNCE_RULES']['soft']['overquota'][] = 'user is invited to retry';
$GLOBALS['BOUNCE_RULES']['soft']['overquota'][] = 'User account is overquota';
$GLOBALS['BOUNCE_RULES']['soft']['overquota'][] = 'mailfolder is full';
$GLOBALS['BOUNCE_RULES']['soft']['overquota'][] = 'exceeds allowed message count';
$GLOBALS['BOUNCE_RULES']['soft']['overquota'][] = 'message is larger than the space available';
$GLOBALS['BOUNCE_RULES']['soft']['overquota'][] = 'recipient storage full';

$GLOBALS['BOUNCE_RULES']['soft']['blockedcontent'][] = 'Your e-mail was rejected for policy reasons on this gateway';
$GLOBALS['BOUNCE_RULES']['soft']['blockedcontent'][] = '550 Protocol violation';
$GLOBALS['BOUNCE_RULES']['soft']['blockedcontent'][] = 'Blacklisted';
$GLOBALS['BOUNCE_RULES']['soft']['blockedcontent'][] = 'is refused. See http://spamblock.outblaze.com';
$GLOBALS['BOUNCE_RULES']['soft']['blockedcontent'][] = '550 Rule imposed mailbox access for';
$GLOBALS['BOUNCE_RULES']['soft']['blockedcontent'][] = 'Message cannot be accepted, content filter rejection';
$GLOBALS['BOUNCE_RULES']['soft']['blockedcontent'][] = 'Mail appears to be unsolicited';
$GLOBALS['BOUNCE_RULES']['soft']['blockedcontent'][] = 'rejected for policy reasons';
$GLOBALS['BOUNCE_RULES']['soft']['blockedcontent'][] = 'Spam rejected';
$GLOBALS['BOUNCE_RULES']['soft']['blockedcontent'][] = 'Error: content rejected';
$GLOBALS['BOUNCE_RULES']['soft']['blockedcontent'][] = 'Message Denied: Restricted attachment';
$GLOBALS['BOUNCE_RULES']['soft']['blockedcontent'][] = 'Denied by policy';
$GLOBALS['BOUNCE_RULES']['soft']['blockedcontent'][] = 'has exceeded maximum attachment count limit';
$GLOBALS['BOUNCE_RULES']['soft']['blockedcontent'][] = 'Blocked for spam';
$GLOBALS['BOUNCE_RULES']['soft']['blockedcontent'][] = 'Message held for human verification';
$GLOBALS['BOUNCE_RULES']['soft']['blockedcontent'][] = 'considered unsolicited bulk e-mail';
$GLOBALS['BOUNCE_RULES']['soft']['blockedcontent'][] = 'message held before permitting delivery';
$GLOBALS['BOUNCE_RULES']['soft']['blockedcontent'][] = 'envelope sender is in my badmailfrom';
$GLOBALS['BOUNCE_RULES']['soft']['blockedcontent'][] = 'listed in multi.surbl.org';
$GLOBALS['BOUNCE_RULES']['soft']['blockedcontent'][] = 'black listed url host';
$GLOBALS['BOUNCE_RULES']['soft']['blockedcontent'][] = 'this message scored (.*) spam points';
$GLOBALS['BOUNCE_RULES']['soft']['blockedcontent'][] = 'message scored (.*) on spam scale';
$GLOBALS['BOUNCE_RULES']['soft']['blockedcontent'][] = 'sender address (.*) blocked using';
$GLOBALS['BOUNCE_RULES']['soft']['blockedcontent'][] = 'message filtered';
$GLOBALS['BOUNCE_RULES']['soft']['blockedcontent'][] = 'rejected as bulk';
$GLOBALS['BOUNCE_RULES']['soft']['blockedcontent'][] = 'message content rejected';
$GLOBALS['BOUNCE_RULES']['soft']['blockedcontent'][] = 'Mail From IP Banned';
$GLOBALS['BOUNCE_RULES']['soft']['blockedcontent'][] = 'Connection refused due to abuse';
$GLOBALS['BOUNCE_RULES']['soft']['blockedcontent'][] = 'mail server is currently blocked';
$GLOBALS['BOUNCE_RULES']['soft']['blockedcontent'][] = 'Spam origin';
$GLOBALS['BOUNCE_RULES']['soft']['blockedcontent'][] = 'extremely high on spam scale';
$GLOBALS['BOUNCE_RULES']['soft']['blockedcontent'][] = 'is not accepting mail from this sender';
$GLOBALS['BOUNCE_RULES']['soft']['blockedcontent'][] = 'spamblock';
$GLOBALS['BOUNCE_RULES']['soft']['blockedcontent'][] = 'blocked using ';
$GLOBALS['BOUNCE_RULES']['soft']['blockedcontent'][] = 'HTML tag unacceptable';
$GLOBALS['BOUNCE_RULES']['soft']['blockedcontent'][] = 'appears to be spam';
$GLOBALS['BOUNCE_RULES']['soft']['blockedcontent'][] = 'not accepting mail with attachments or embedded images';
$GLOBALS['BOUNCE_RULES']['soft']['blockedcontent'][] = 'message contains potential spam';
$GLOBALS['BOUNCE_RULES']['soft']['blockedcontent'][] = 'You have been blocked by the recipient';
$GLOBALS['BOUNCE_RULES']['soft']['blockedcontent'][] = 'message looks like spam';
$GLOBALS['BOUNCE_RULES']['soft']['blockedcontent'][] = 'Message contains unacceptable attachment';
$GLOBALS['BOUNCE_RULES']['soft']['blockedcontent'][] = 'high spam probability';
$GLOBALS['BOUNCE_RULES']['soft']['blockedcontent'][] = 'email is considered spam';

$GLOBALS['BOUNCE_RULES']['soft']['remoteconfigerror'][] = 'Remote sending only allowed with authentication';
$GLOBALS['BOUNCE_RULES']['soft']['remoteconfigerror'][] = '550 authentication required';
$GLOBALS['BOUNCE_RULES']['soft']['remoteconfigerror'][] = 'sorry, that domain isn\'t in my list of allowed rcpthosts';
$GLOBALS['BOUNCE_RULES']['soft']['remoteconfigerror'][] = 'sorry, that domain isn\'t in my list of allowed rcpthosts';
$GLOBALS['BOUNCE_RULES']['soft']['remoteconfigerror'][] = 'has installed an invalid MX record with an IP address instead of a domain name on the right hand side.';
$GLOBALS['BOUNCE_RULES']['soft']['remoteconfigerror'][] = 'all relevant MX records point to non-existent hosts';
$GLOBALS['BOUNCE_RULES']['soft']['remoteconfigerror'][] = 'not capable to receive mail';
$GLOBALS['BOUNCE_RULES']['soft']['remoteconfigerror'][] = 'CNAME lookup failed temporarily';
$GLOBALS['BOUNCE_RULES']['soft']['remoteconfigerror'][] = 'TLS connect failed: timed out';
$GLOBALS['BOUNCE_RULES']['soft']['remoteconfigerror'][] = 'timed out while receiving the initial server greeting';
$GLOBALS['BOUNCE_RULES']['soft']['remoteconfigerror'][] = 'malformed or unexpected name server reply';
$GLOBALS['BOUNCE_RULES']['soft']['remoteconfigerror'][] = 'Message temporarily deferred';
$GLOBALS['BOUNCE_RULES']['soft']['remoteconfigerror'][] = 'unreachable for too long';

$GLOBALS['BOUNCE_RULES']['soft']['localconfigerror'][] = 'Could not complete sender verify callout';
$GLOBALS['BOUNCE_RULES']['soft']['localconfigerror'][] = 'Sender verification error';
$GLOBALS['BOUNCE_RULES']['soft']['localconfigerror'][] = 'Mail only accepted from IPs with valid reverse lookups';
$GLOBALS['BOUNCE_RULES']['soft']['localconfigerror'][] = 'lost connection with';

$GLOBALS['BOUNCE_RULES']['hard']['emaildoesntexist'][] = 'invalid mailbox';
$GLOBALS['BOUNCE_RULES']['hard']['emaildoesntexist'][] = 'mailbox unavailable';
$GLOBALS['BOUNCE_RULES']['hard']['emaildoesntexist'][] = 'invalid address';
$GLOBALS['BOUNCE_RULES']['hard']['emaildoesntexist'][] = 'this user doesn\'t have a yahoo.com account';
$GLOBALS['BOUNCE_RULES']['hard']['emaildoesntexist'][] = 'permanent fatal errors';
$GLOBALS['BOUNCE_RULES']['hard']['emaildoesntexist'][] = 'No mailbox here by that name';
$GLOBALS['BOUNCE_RULES']['hard']['emaildoesntexist'][] = 'User not known';
$GLOBALS['BOUNCE_RULES']['hard']['emaildoesntexist'][] = 'Remote host said: 553';
$GLOBALS['BOUNCE_RULES']['hard']['emaildoesntexist'][] = 'No such user';
$GLOBALS['BOUNCE_RULES']['hard']['emaildoesntexist'][] = 'No such recipient';
$GLOBALS['BOUNCE_RULES']['hard']['emaildoesntexist'][] = 'unknown user';
$GLOBALS['BOUNCE_RULES']['hard']['emaildoesntexist'][] = 'mailbox not found';
$GLOBALS['BOUNCE_RULES']['hard']['emaildoesntexist'][] = 'No such user here';
$GLOBALS['BOUNCE_RULES']['hard']['emaildoesntexist'][] = 'Delivery to the following recipients failed';
$GLOBALS['BOUNCE_RULES']['hard']['emaildoesntexist'][] = 'unknown or illegal alias';
$GLOBALS['BOUNCE_RULES']['hard']['emaildoesntexist'][] = 'not listed in domino directory';
$GLOBALS['BOUNCE_RULES']['hard']['emaildoesntexist'][] = 'unrouteable address';
$GLOBALS['BOUNCE_RULES']['hard']['emaildoesntexist'][] = 'Destination server rejected recipients';
$GLOBALS['BOUNCE_RULES']['hard']['emaildoesntexist'][] = 'unable to validate recipient';
$GLOBALS['BOUNCE_RULES']['hard']['emaildoesntexist'][] = 'No such virtual user here';
$GLOBALS['BOUNCE_RULES']['hard']['emaildoesntexist'][] = 'The recipient cannot be verified';
$GLOBALS['BOUNCE_RULES']['hard']['emaildoesntexist'][] = 'bad address ';
$GLOBALS['BOUNCE_RULES']['hard']['emaildoesntexist'][] = 'Recipient unknown';
$GLOBALS['BOUNCE_RULES']['hard']['emaildoesntexist'][] = 'mailbox is currently unavailable';
$GLOBALS['BOUNCE_RULES']['hard']['emaildoesntexist'][] = 'user mailbox is inactive';
$GLOBALS['BOUNCE_RULES']['hard']['emaildoesntexist'][] = 'Invalid User';
$GLOBALS['BOUNCE_RULES']['hard']['emaildoesntexist'][] = 'recipient rejected';
$GLOBALS['BOUNCE_RULES']['hard']['emaildoesntexist'][] = 'invalid recipient';
$GLOBALS['BOUNCE_RULES']['hard']['emaildoesntexist'][] = 'not our customer';
$GLOBALS['BOUNCE_RULES']['hard']['emaildoesntexist'][] = 'Unknown account ';
$GLOBALS['BOUNCE_RULES']['hard']['emaildoesntexist'][] = 'This user doesn\'t have a ';
$GLOBALS['BOUNCE_RULES']['hard']['emaildoesntexist'][] = 'no users here by that name';
$GLOBALS['BOUNCE_RULES']['hard']['emaildoesntexist'][] = 'account closed';
$GLOBALS['BOUNCE_RULES']['hard']['emaildoesntexist'][] = 'user not found';
$GLOBALS['BOUNCE_RULES']['hard']['emaildoesntexist'][] = 'This address no longer accepts mail';
$GLOBALS['BOUNCE_RULES']['hard']['emaildoesntexist'][] = 'does not like recipient';
$GLOBALS['BOUNCE_RULES']['hard']['emaildoesntexist'][] = 'Delivery to the following recipient failed permanently';
$GLOBALS['BOUNCE_RULES']['hard']['emaildoesntexist'][] = 'User Does Not Exist';
$GLOBALS['BOUNCE_RULES']['hard']['emaildoesntexist'][] = 'The mailbox is not available on this system';
$GLOBALS['BOUNCE_RULES']['hard']['emaildoesntexist'][] = 'mailbox (.*) does not exist';
$GLOBALS['BOUNCE_RULES']['hard']['emaildoesntexist'][] = 'not a valid mailbox';
$GLOBALS['BOUNCE_RULES']['hard']['emaildoesntexist'][] = 'server doesn\'t handle mail for that user';
$GLOBALS['BOUNCE_RULES']['hard']['emaildoesntexist'][] = 'No such account ';
$GLOBALS['BOUNCE_RULES']['hard']['emaildoesntexist'][] = 'unknown recipient';
$GLOBALS['BOUNCE_RULES']['hard']['emaildoesntexist'][] = 'user invalid';
$GLOBALS['BOUNCE_RULES']['hard']['emaildoesntexist'][] = 'User reject the mail';
$GLOBALS['BOUNCE_RULES']['hard']['emaildoesntexist'][] = 'The following recipients are unknown';
$GLOBALS['BOUNCE_RULES']['hard']['emaildoesntexist'][] = 'User unknown in virtual mailbox';
$GLOBALS['BOUNCE_RULES']['hard']['emaildoesntexist'][] = 'User unknown in virtual alias table';
$GLOBALS['BOUNCE_RULES']['hard']['emaildoesntexist'][] = 'User is unknown';
$GLOBALS['BOUNCE_RULES']['hard']['emaildoesntexist'][] = 'user unknown';
$GLOBALS['BOUNCE_RULES']['hard']['emaildoesntexist'][] = 'Unrouteable address';
$GLOBALS['BOUNCE_RULES']['hard']['emaildoesntexist'][] = 'mailbox unavailable';
$GLOBALS['BOUNCE_RULES']['hard']['emaildoesntexist'][] = 'This address does not receive mail';
$GLOBALS['BOUNCE_RULES']['hard']['emaildoesntexist'][] = 'This address is no longer in use';
$GLOBALS['BOUNCE_RULES']['hard']['emaildoesntexist'][] = 'Recipient no longer on server';
$GLOBALS['BOUNCE_RULES']['hard']['emaildoesntexist'][] = 'retry timeout exceeded';
$GLOBALS['BOUNCE_RULES']['hard']['emaildoesntexist'][] = 'retry time not reached for any host after a long failure period';
$GLOBALS['BOUNCE_RULES']['hard']['emaildoesntexist'][] = 'unknown address or alias';
$GLOBALS['BOUNCE_RULES']['hard']['emaildoesntexist'][] = '> does not exist';
$GLOBALS['BOUNCE_RULES']['hard']['emaildoesntexist'][] = 'Recipient address rejected';
$GLOBALS['BOUNCE_RULES']['hard']['emaildoesntexist'][] = 'Recipient not allowed';
$GLOBALS['BOUNCE_RULES']['hard']['emaildoesntexist'][] = 'Address rejected';
$GLOBALS['BOUNCE_RULES']['hard']['emaildoesntexist'][] = 'Address invalid';
$GLOBALS['BOUNCE_RULES']['hard']['emaildoesntexist'][] = 'Unknown local part';
$GLOBALS['BOUNCE_RULES']['hard']['emaildoesntexist'][] = 'mail receiving disabled';
$GLOBALS['BOUNCE_RULES']['hard']['emaildoesntexist'][] = 'bad destination email address';
$GLOBALS['BOUNCE_RULES']['hard']['emaildoesntexist'][] = 'deactivated due to abuse';
$GLOBALS['BOUNCE_RULES']['hard']['emaildoesntexist'][] = 'no such address';
$GLOBALS['BOUNCE_RULES']['hard']['emaildoesntexist'][] = 'user_unknown';
$GLOBALS['BOUNCE_RULES']['hard']['emaildoesntexist'][] = 'recipient not found';
$GLOBALS['BOUNCE_RULES']['hard']['emaildoesntexist'][] = 'User unknown in local recipient table';
$GLOBALS['BOUNCE_RULES']['hard']['emaildoesntexist'][] = 'This recipient e-mail address was not found';
$GLOBALS['BOUNCE_RULES']['hard']['emaildoesntexist'][] = 'no valid recipients';

$GLOBALS['BOUNCE_RULES']['hard']['domaindoesntexist'][] = 'name or service not known';
$GLOBALS['BOUNCE_RULES']['hard']['domaindoesntexist'][] = 'I couldn\'t find any host named';
$GLOBALS['BOUNCE_RULES']['hard']['domaindoesntexist'][] = 'message could not be delivered for \d+ days';
$GLOBALS['BOUNCE_RULES']['hard']['domaindoesntexist'][] = 'I couldn\'t find a mail exchanger or IP address';
$GLOBALS['BOUNCE_RULES']['hard']['domaindoesntexist'][] = 'address does not exist';
$GLOBALS['BOUNCE_RULES']['hard']['domaindoesntexist'][] = 'No such domain at this location';
$GLOBALS['BOUNCE_RULES']['hard']['domaindoesntexist'][] = 'an MX or SRV record indicated no SMTP service';
$GLOBALS['BOUNCE_RULES']['hard']['domaindoesntexist'][] = 'I couldn\'t find any host by that name';
$GLOBALS['BOUNCE_RULES']['hard']['domaindoesntexist'][] = 'Domain does not exist; please check your spelling';
$GLOBALS['BOUNCE_RULES']['hard']['domaindoesntexist'][] = 'Domain not used for mail';
$GLOBALS['BOUNCE_RULES']['hard']['domaindoesntexist'][] = 'Domain must resolve';
$GLOBALS['BOUNCE_RULES']['hard']['domaindoesntexist'][] = 'unrouteable mail domain';
$GLOBALS['BOUNCE_RULES']['hard']['domaindoesntexist'][] = 'no route to host';

$GLOBALS['BOUNCE_RULES']['hard']['relayerror'][] = 'relaying denied';
$GLOBALS['BOUNCE_RULES']['hard']['relayerror'][] = 'access denied';
$GLOBALS['BOUNCE_RULES']['hard']['relayerror'][] = '554 denied';
$GLOBALS['BOUNCE_RULES']['hard']['relayerror'][] = 'they are not accepting mail from';
$GLOBALS['BOUNCE_RULES']['hard']['relayerror'][] = 'Relaying not allowed';
$GLOBALS['BOUNCE_RULES']['hard']['relayerror'][] = 'not permitted to relay through this server';
$GLOBALS['BOUNCE_RULES']['hard']['relayerror'][] = 'Sender verify failed';
$GLOBALS['BOUNCE_RULES']['hard']['relayerror'][] = 'Although I\'m listed as a best-preference MX or A for that host';
$GLOBALS['BOUNCE_RULES']['hard']['relayerror'][] = 'mail server permanently rejected message';
$GLOBALS['BOUNCE_RULES']['hard']['relayerror'][] = 'too many hops, this message is looping';
$GLOBALS['BOUNCE_RULES']['hard']['relayerror'][] = 'relay not permitted';
$GLOBALS['BOUNCE_RULES']['hard']['relayerror'][] = 'This mail server requires authentication when attempting to send to a non-local e-mail address.';
$GLOBALS['BOUNCE_RULES']['hard']['relayerror'][] = 'is currently not permitted to relay';
$GLOBALS['BOUNCE_RULES']['hard']['relayerror'][] = 'Unable to relay for';
$GLOBALS['BOUNCE_RULES']['hard']['relayerror'][] = 'not a gateway';
$GLOBALS['BOUNCE_RULES']['hard']['relayerror'][] = 'This system is not configured to relay mail';
$GLOBALS['BOUNCE_RULES']['hard']['relayerror'][] = 'we do not relay';
$GLOBALS['BOUNCE_RULES']['hard']['relayerror'][] = 'relaying mail to';
$GLOBALS['BOUNCE_RULES']['hard']['relayerror'][] = 'Relaying is prohibited';
$GLOBALS['BOUNCE_RULES']['hard']['relayerror'][] = 'Cannot relay';
$GLOBALS['BOUNCE_RULES']['hard']['relayerror'][] = 'relaying disallowed';
$GLOBALS['BOUNCE_RULES']['hard']['relayerror'][] = 'Authentication required for relay';

$GLOBALS['BOUNCE_RULES']['hard']['invalidemail'][] = 'bad address syntax';
$GLOBALS['BOUNCE_RULES']['hard']['invalidemail'][] = 'domain missing or malformed';
$GLOBALS['BOUNCE_RULES']['hard']['invalidemail'][] = '550_Invalid_recipient';
$GLOBALS['BOUNCE_RULES']['hard']['invalidemail'][] = 'Invalid Address';
$GLOBALS['BOUNCE_RULES']['hard']['invalidemail'][] = 'not our customer';

$user_rules = dirname(__FILE__) . '/user_bounce_rules.php';
if (is_file($user_rules)) {
	require($user_rules);
}

?>
