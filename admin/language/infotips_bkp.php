<?php
/**
* Language file variables for the info tips.
*
* @see GetLang
*
* @version     $Id: infotips.php,v 1.11 2006/12/08 06:21:32 chris Exp $

*
* @package SendStudio
* @subpackage Language
*/

/**
* Here are all of the variables for the info tips... Please backup before you start!
*
* In each case, the 'Intro' is the tip that shows up in sendstudio.
* The 'Description' gets shown when a tip is clicked.
*/

/**
* Number of generic info tips we have to choose from.
*/
define('Infotip_Size', 15);

define('LNG_Infotip_Form_Intro', 'Use double-optin to reduce spam reports.');
define('LNG_Infotip_Form_Details', 'A confirmation email (double opt-in) verifies that the owner of the email address is also the person who signed up to your mailing list. This can reduce your emails from being marked as spam by unintending recipients.');

/**
* This gets shown before any info tip.
*/
define('LNG_Infotip_Intro', 'Email Marketing Tip #');
define('LNG_Infotip_ReadMore', 'Read&nbsp;more...');

define('LNG_Infotip_List_Intro', '15 "Simple Yet Effective" Email Marketing Tips');

define('LNG_Infotip_1_Intro', 'To avoid having your email marked as spam, keep clear of words such as \'Free\', \'$$$\', \'Save\'  and \'Discount\' in your subject line.');
define('LNG_Infotip_1_Title', 'Avoiding the Spam Filters');
define('LNG_Infotip_1_Details', 'The majority of large Internet service providers now use rigerous spam protection mechanisms to trap unsolicited email before it gets into their customers inboxes. Spam filters generally "rank" each email by a number of different criteria, and, if that email rates above a certain level (such as 10 spam points), then it is flagged as spam and deleted.<br/><br/>To make sure your emails don\'t get flagged as spam -- and deleted before they even get to your subscribers -- avoid using words such as \'Free\', \'$$$\', \'Save\', \'Discount\', etc in both the subject line and the content of your email.');

define('LNG_Infotip_2_Intro', 'For maximum click-thru rates when creating HTML emails, make sure your links are blue, underlined and optionally bold.');
define('LNG_Infotip_2_Title', 'Maximizing Click-Thru Rates');
define('LNG_Infotip_2_Details', 'Both web pages and emails can contain alot of text and graphics, and this sometimes makes it harder to get your subscribers to perform a certain task, such as clicking on a link to see your special offers.<br/><br/>Numerous research papers tell us that the majority of Internet users respond better to a plain, bold, blue text link -- <b><font color="blue">such as this</font></b> -- as opposed to a banner or button. So, if you\'re going to include links in your emails, make sure they are bold, blue and underlined. This will mean that more subscribers click through, meaning more conversions/sales for you.');

define('LNG_Infotip_3_Intro', 'Using personalization in your emails (such as \'Hi John\' instead of \'Hi there\') will increase your open rate by up to 650%');
define('LNG_Infotip_3_Title', 'The Power of Personalization');
define('LNG_Infotip_3_Details', 'If you were standing in a crowded mall, which of these would get your attention: "HEY, YOU!" or "HEY JOHN" (assuming your name is John). The power of personalization can and should be used in your emails. In-fact, by simply starting your email with "Hi [subscriber_name]" instead of the boring "Hi there", you can increase both your reading and click-thru rates by up to 650%. Why? Put simply, it\'s because your subscribers feel like they already have a relationship with you as you\'ve addressed them by their first name.');

define('LNG_Infotip_4_Intro', 'Always make sure you include an unsubscribe link. You can do this by adding the text %%UNSUBSCRIBELINK%% anywhere in your email.');
define('LNG_Infotip_4_Title', 'One-Click Unsubscription');
define('LNG_Infotip_4_Details', 'If you want to grow your mailing list, then there\'s 2 things that you absolutely must have: a double opt-in process, and a quick way to unsubscribe. In some countries, it\'s actually mandatory by law that every email has an unsubscribe link in it. The unsubscribe link should take the recipient directly to a page where they are then removed -- courteously -- from your mailing list.');

define('LNG_Infotip_5_Intro', 'To reduce the number of bogus email addresses in your mailing list, always use a double opt-in subscription system.');
define('LNG_Infotip_5_Title', 'Signup Confirmation');
define('LNG_Infotip_5_Details', 'Don\'t get accused of spamming -- always, and I mean always use a double opt-in confirmation process. Double opt-in means that after your visitor initially enters their email address to subscribe to your list, you should then send them a "confirmation" email. This email should contain a special link back to your email marketing program, which will then verify that this visitor did indeed sign up to your mailing list.');

define('LNG_Infotip_6_Intro', 'The best days to send a marketing or sales email to your subscribers has been proven to be Tuesday and Wednesday.');
define('LNG_Infotip_6_Title', 'Tuesday / Wednesday = Increased Response');
define('LNG_Infotip_6_Details', 'Studies conducted by online research analysts have shown that the best days to perform a mail-out to your list are Tuesday and Wednesday, as this is when people are more receptive to communication. This means that they are more likely to read your content and click on links, meaning more sales.<br/><br/>On Mondays, everyone is still recovering from a hectic weekend. On Thursday and Friday, people are already too busy looking forward to the weekend. We\'ve actually experimented with this, and received the best results by sending out emails at around 2-3pm (American Pacific Time) on a Wednesday.');

define('LNG_Infotip_7_Intro', 'Why not setup an autoresponder to send to your subscribers 1 hour after they signup. You can use it to tell them more abour your company, products or services.');
define('LNG_Infotip_7_Title', 'Repeat Email Communication');
define('LNG_Infotip_7_Details', 'An autoresponder is an email that is scheduled to be sent at a certain time interval after someone subscribes to your mailing list. Autoresponders are a great way to automatically follow up with your subscribers or provide them with more information on your products/services.<br/><br/>For example, if you provide a free newsletter, you could setup 3 autoresponders for new subscribers: the first is sent 1 hour after they subscribe. It contains a thank you message and a link to get 10% off your newly released eBook.<br/><br/>The second is sent 24 hours after they subscribe, telling them about your community message boards, and the third is sent 72 hours after they subscribe, in which you can offer them a special deal on becomming a paid member of your site.<br/><br/>Autoresponders help your subscribers build trust in both your company and your brand, and this can help make it easier when trying to close sales in the future.');

define('LNG_Infotip_8_Intro', 'Keep the theme of your email campaigns consistent. Create a text or HTML template and use that template whenever you create a new email.');
define('LNG_Infotip_8_Title', 'Consistency is the Key');
define('LNG_Infotip_8_Details', 'If you\'re running a newsletter or frequent email publication, make sure you keep the look and feel consistent from issue to issue. By keeping the look and feel consistent, you help to maintain and strengthen your brand and your image to your subscribers, which again will make it easier to close sales when you need to.<br/><br/>Create a template for your newsletter and whenever you need to create a new issue, use that template as the basis for each issue.');

define('LNG_Infotip_9_Intro', 'For best results when sending recurring email campaigns, always send it on the same day at the same time. For example, every 2nd Wednesday at 3pm.');
define('LNG_Infotip_9_Title', 'On Time, Every Time');
define('LNG_Infotip_9_Details', 'When sending an email to your subscribers, always make sure that it\'s sent on the same day, at the same time. For example, every Wednesday at 3pm. Your subscribers will come to "expect" your email to arrive in their inbox on the same day at the same time every week, meaning that they want to read your content and are generally more receptive to any special offers or promotions you may include.');

define('LNG_Infotip_10_Intro', 'Make sure your subject line is persuasive and catches your readers attention. Instead of using something like \'OurSite Newsletter Issue #1\', use a benefit, such as \'OurSite Newsletter: 10 Tips for Financial Freedom\'.');
define('LNG_Infotip_10_Title', 'The Half-a-Second Subject Line');
define('LNG_Infotip_10_Details', 'When your email arrives in your subscribers inbox, you generally have about half a second to catch their attention with the subject line of your email. After this, they will either delete your email or ignore it. In your subject line, try and specify a benefit that the subscriber can expect by reading your email. For example, instead of using \'OurSite Newsletter Issue #1\', use \'OurSite Newsletter: 10 Tips for Financial Freedom\'.');

define('LNG_Infotip_11_Intro', 'If running a newsletter, offer your customers a free bonus (such as an eBook or special report) for signing up. Then, create an autoresponder to email them a link to that bonus 1 hour after they subscribe.');
define('LNG_Infotip_11_Title', 'The Free Bonus Hook-In');
define('LNG_Infotip_11_Details', 'Free is overused these days, especially on the Internet. However, if you\'re looking to grow your subscriber list, then create or source a product of value to your visitors (such as an eBook or discount coupon) and offer it to them for free when they signup for to receive your newsletter.<br/><br/>To make sure they don\'t simply type any email address into your subscription form, setup an autoresponder to send them the free bonus 1 hour after they subscribe.');

define('LNG_Infotip_12_Intro', 'Always have some interesting content at the top of your email, as this is the part that will show in the preview window of your client\'s email program, such as MS Outlook.');
define('LNG_Infotip_12_Title', 'The Preview Pane');
define('LNG_Infotip_12_Details', 'Popular email clients such as MS Outlook show a preview of an email when it\'s selected in your inbox. Always have some interesting content at the very top of your email, as this is the part that will show in the preview window of your subscribers email program. If it\'s interesting enough, then your subscriber will open your email and continue on reading.');

define('LNG_Infotip_13_Intro', 'Try using different wording for links in your marketing emails. Then, click on the stats button above to track which links received the most clicks and use them for future campaigns.');
define('LNG_Infotip_13_Title', 'Link-Click Testing');
define('LNG_Infotip_13_Details', 'When creating marketing emails, try using different text for both content and links. Also try re-positioning images such as logos and buttons. After sending about 3 different emails, compare the click-thru stats and see which one worked best. Now, when you need to send marketing emails in the future, you know that you will be sending the right mix of content and images that will attract the most click-thrus, and ultimately the most sales.');

define('LNG_Infotip_14_Title', 'Email-Based Learning');
define('LNG_Infotip_14_Intro', 'Setup an email-based course for your subscribers. To do this, simply create a series of autoresponders (for example, 5) containing unique content. Then, schedule the first one to go out after 24 hours, the second after 48 hours, etc.');
define('LNG_Infotip_14_Details', 'Add value to your website, build trust in your visitors, establish your credibility and collect more subscriptions to your mailing list by setting up an email-based learning course. To do this, simply create a series of autoresponders (for example, 5) containing unique content. Then, schedule the first one to be sent after 24 hours, the second after 48 hours, etc..');

define('LNG_Infotip_15_Title', 'Always Sign on the Dotted Line');
define('LNG_Infotip_15_Intro', 'Always include a signature at the bottom of your emails. You can use your signature to link back to your website, and even to your other products. Here\'s a sample signature: Regards, John Doe. President - Company XYZ. Visit our website at www.companyxyz.com');
define('LNG_Infotip_15_Details', 'Always include a signature at the bottom of your emails, as it\'s one of the easiest ways to attract more traffic to your website. This signature should include your personal details, your company details, and an unsubscribe link. You can use your signature to link back to your website, and even to other products. Here\'s a sample signature:<br/><br/>Regards,<br/>John Doe.<br/>President - Company XYZ.<br/>Visit our website at http://www.companyxyz.com<br/>Unsubscribe from this newsletter at http://www.companyxyz.com/unsubscribe...');


/**
* To make context sensitive helptips.
* You can make up your own tips below
* And place them on specific pages by looking at the page & action from the url and placing them in the array.
*
* For example the 'Spam' info tip will be shown when the page is 'Newsletters' and action is 'Create'.
*
* Context sensitive help tips are NOT included in the generic helptips above.
*
* However you can include the generic ones as context sensitive ones if you prefer.
*
* Simply grab the tip 'number' and place it in the appropriate place..
*
* eg to show tip '15' when you are on the 'users' page (regardless of the Action).
* $GLOBALS['ContextSensitiveTips']['users'] = array('15');
*/
define('LNG_Infotip_Cron_Intro', 'Want faster sending?');
define('LNG_Infotip_Cron_Details', 'Did you know that enabling cron (see <a href="resources/tutorials/cron_intro.html" target="_blank">documentation</a> or contact your administrator) will allow you to schedule email campaigns to be sent at a future date, as well as sending emails much faster? It will also stop you from having to keep the popup window open.');

define('LNG_Infotip_Spam_Intro', 'Reduce your email being marked as spam.');
define('LNG_Infotip_Spam_Details', 'By testing your email in various email clients, including free accounts such as hotmail, gmail and yahoo you can reduce the chances of your email being marked as spam.');
define('LNG_Infotip_Spam_ReadMore', 'Read&nbsp;Tutorial...');

// The tutorials live in a specific folder - this simply points to a html file.
define('LNG_Infotip_Spam_ReadMoreLink', 'reduce_spam_tutorial.html');

$GLOBALS['ContextSensitiveTips']['newsletters']['create'] = array('Spam');


define('LNG_Infotip_Autoresponders_Intro', 'How to Setup an Autoresponder.');
define('LNG_Infotip_Autoresponders_Details', 'This guide will help get you started setting up your first autoresponder so you can email your subscribers automatically helping turn leads into customers.');
define('LNG_Infotip_Autoresponders_ReadMore', 'Read guide...');
define('LNG_Infotip_Autoresponders_ReadMoreLink', 'create_autoresponder.html');
$GLOBALS['ContextSensitiveTips']['autoresponders']['create'] = array('Autoresponders');

define('LNG_Infotip_AutoHowto_Intro', 'Autoresponders - The marketers magic trick.');
define('LNG_Infotip_AutoHowto_Details', 'Sending a series of emails to potential customers automatically is a great way to increase sales and customer loyalty with minimal fuss whether or not you run an online business.');
define('LNG_Infotip_AutoHowto_ReadMore', 'Find out how...');
define('LNG_Infotip_AutoHowto_ReadMoreLink', 'autoresponders.html');
$GLOBALS['ContextSensitiveTips']['autoresponders']['manage'] = array('AutoHowto');


define('LNG_Infotip_GettingStarted_Intro', 'Getting started.');
define('LNG_Infotip_GettingStarted_Details', 'Creating and sending your first email campaign is easy. Start by creating a mailing list, custom fields, a subscription form and content for your email campaign. Finally, send your campaign to your subscribers.');
define('LNG_Infotip_GettingStarted_ReadMore', 'See the guide...');
define('LNG_Infotip_GettingStarted_ReadMoreLink', 'getting_started.html');
$GLOBALS['ContextSensitiveTips']['index'] = array('GettingStarted');


define('LNG_Infotip_AddForm_Intro', 'Add a Subscription form to your website.');
define('LNG_Infotip_AddForm_Details', 'To collect leads from your website, you should add a subscription form to your website so your website visitors can subscribe to your mailing list to receive more information from you.');
define('LNG_Infotip_AddForm_ReadMore', 'Here\'s how...');
define('LNG_Infotip_AddForm_ReadMoreLink', 'subscription_form.html');
$GLOBALS['ContextSensitiveTips']['forms']['manage'] = array('AddForm');

define('LNG_Infotip_CustomFields_Intro', 'Learn how to collect your subscribers name, age, sex, etc.');
define('LNG_Infotip_CustomFields_Details', 'To collect more than just an email address from your subscribers you need to create and add custom fields to your subscription form. Collect simple information such as their name, or even advanced information such as their location or favorite color.');
define('LNG_Infotip_CustomFields_ReadMore', 'Here\'s how...');
define('LNG_Infotip_CustomFields_ReadMoreLink', 'custom_fields.html');
$GLOBALS['ContextSensitiveTips']['customfields']['manage'] = array('CustomFields');

?>
