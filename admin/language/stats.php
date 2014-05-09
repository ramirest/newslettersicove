<?php
/**
* Language file variables for the stats area.
*
* @see GetLang
*
* @version     $Id: stats.php,v 1.40 2007/06/14 06:29:21 chris Exp $

*
* @package SendStudio
* @subpackage Language
*/

/**
* Here are all of the variables for the stats area... Please backup before you start!
*/

define('LNG_Stats_PrintReport', 'Imprimir Relat&oacute;rio');
define('LNG_Stats_ExportReport', 'Exportar Relat&oacute;rio');

define('LNG_Stats_TotalDelivered', 'Total Entregues');
define('LNG_Stats_TotalForwards', 'Total Encaminhados');
define('LNG_Stats_TotalOpens', 'Total Abertos');
define('LNG_Stats_TotalUniqueOpens', 'Total Abertos &Uacute;nico');
define('LNG_Stats_TotalLinkClicks', 'Total de Cliques no Link');
define('LNG_Stats_TotalClicks', 'Total Cliques');
define('LNG_Stats_TotalUniqueClicks', 'Total de Cliques &Uacute;nicos');
define('LNG_Stats_MostPopularLink', 'Mais Popular');
define('LNG_Stats_AverageClicks', 'M&eacute;dia de Cliques (Por Email Aberto');
define('LNG_LinksClickedChart', 'Gr&aacute;fico de Links Clicados');
define('LNG_OpensChart', 'Gr&aacute;fico de Abertos');
define('LNG_ForwardsChart', 'Gr&aacute;fico de Encaminhamentos');
define('LNG_Stats_TotalUnsubscribes', 'Total de Cancelamentos');
define('LNG_Stats_MostUnsubscribes', 'Mais Cancelados');
define('LNG_UnsubscribesChart', 'Gr&aacute;fico de Cancelamentos');
define('LNG_UnsubscribeDate', 'Data de Cancelamento');

define('LNG_Stats_ViewSummary', 'Visualizar Relat&oacute;rio');

define('LNG_Unsubscribe_Summary', 'Resumo de Cancelamentos');
define('LNG_LinkClicks_Summary', 'Resumo de Cliques nos Links');
define('LNG_Opens_Summary', 'Resumo de Abertos');
define('LNG_Forwards_Summary', 'Resumo de Encaminhamentos');

define('LNG_StatsDeleteDisabled', 'Voc&ecirc; n&atilde;o pode excluir as estat&iacute;sticas enquanto uma campanha de email ainda estiver sendo enviada.');
define('LNG_Delete_Stats_Selected', 'Excluir Estat&iacute;sticas');
define('LNG_ChooseStatsToDelete', 'Escolher alguma estat&iacute;stica para excluir');
define('LNG_DeleteStatsPrompt', 'Tem certeza de que deseja excluir estas estat&iacute;sticas?\nUma vez que elas forem exclu&iacute;das, n&atilde;o poder&atilde;o ser recuperadas.');

define('LNG_StatisticsDeleteSuccess_One', 'Estat&iacute;sticas exclu&iacute;das com sucesso.');
define('LNG_StatisticsDeleteSuccess_Many', '%s estat&iacute;sticas exclu&iacute;das com sucesso.');

define('LNG_StatisticsDeleteFail_One', 'Estat&iacute;sticas n&atilde;o puderam ser exclu&iacute;das. Tente novamente.');
define('LNG_StatisticsDeleteFail_Many', '%s estat&iacute;sticas n&atilde;o puderam ser exclu&iacute;das. Por favor, tente novamente.');

define('LNG_StatisticsDeleteNotFinished_One', 'Estat&iacute;sticas n&atilde;o podem ser exclu&iacute;das enquanto uma campanha de email ainda estiver sendo enviada.');
define('LNG_StatisticsDeleteNotFinished_Many', '%s estat&iacute;sticas n&atilde;o podem ser exclu&iacute;das enquanto uma campanha de email ainda estiver sendo enviada.');

define('LNG_DateStarted', 'Iniciado em');
define('LNG_DateFinished', 'Finalizado em');
define('LNG_TotalRecipients', 'Destinat&aacute;rios');
define('LNG_UnsubscribeCount', 'Cancelamentos');
define('LNG_ExportReport', 'Exportar Relat&oacute;rio');
define('LNG_PrintReport', 'Imprimir Relat&oacute;rio');
define('LNG_ViewSummary', 'Visualizar');

define('LNG_TotalEmails', 'Total de Emails: ');
define('LNG_TotalOpens', 'Total Abertos: ');
define('LNG_TotalUniqueOpens', 'Total Abertos &Uacute;nicos: ');
define('LNG_AverageOpens', 'M&eacute;dia de Aberturas: ');
define('LNG_MostOpens', 'Mais Abertos (Data/Hora): ');

/**
* Newsletter statistics language variables.
*/
define('LNG_Stats_NewsletterStatistics', 'Estat&iacute;sticas da Campanha de Email');
define('LNG_Stats_ChooseNewsletter', '-- Escolha uma campanha de email da lista abaixo --');
define('LNG_Stats_Newsletters_Step1_Heading', 'Estat&iacute;sticas da Campanha de Email');
define('LNG_Stats_Newsletters_Step1_Intro', 'Para iniciar, por favor escolha uma campanha de email para visualizar suas estat&iacute;sticas.');
define('LNG_Stats_Newsletters_Cancel', 'Tem certeza de que deseja cancelar a visuzaliza&ccedil;&atilde;o das estat&iacute;sticas de campanha de email?');
define('LNG_Stats_Newsletters_SelectList_Heading', 'Detalhes da Campanha de Email');
define('LNG_Stats_Newsletters_SelectList_Intro', 'Campanha de Email');
define('LNG_Stats_Newsletters_NoSelection', 'Por favor, escolha uma campanha de email.');
define('LNG_NoNewslettersHaveBeenSent', 'Como nenhuma newsletter foi enviada, nenhuma estat&iacute;stica dispon&iacute; para a campanha de email.');
define('LNG_Stats_Newsletters_Step2_Heading', 'Estat&iacute;sticas da Campanha de Email');
define('LNG_Stats_Newsletters_Step2_Intro', 'Visualizar estat&iacute;sticas para a campanha de email \'%s\'');
define('LNG_NoNewsletterStats', 'Nenhuma estat&iacute;stica para a campanha de email \'%s\' foi registrada.');
define('LNG_NewsletterSummaryChart', 'Resumo Gr&aacute;fico da Campanha de Email');



/**
* User statistics language variables.
*/

// this is used if someone tries to view another users statistics. This is so they can't get someone elses' username.
// whether the other user actually exists is not displayed.
define('LNG_Stats_Unknown_User', 'Ou o usu&aacute;rio n&atilde;o existe ou voc&ecirc; n&atilde;o tem permiss&atilde;o para acessar suas informa&ccedil;&otilde;es.');
define('LNG_Stats_UserStatistics', 'Estat&iacute;sticas dos usu&aacute;rios');
define('LNG_Stats_ChooseUser', '-- Escolha um usu&aacute;rio da lista abaixo --');
define('LNG_Stats_Users_Step1_Heading', 'Estat&iacute;sticas dos usu&aacute;rios');
define('LNG_Stats_Users_Step1_Intro', 'Para iniciar, por favor, escolha um usu&aacute;rio para visualizar suas estat&iacute;sticas.');
define('LNG_Stats_Users_Cancel', 'Tem certeza de que deseja cancelar a visualiza&ccedil;&atilde;o das estat&iacute;sticas de usu&aacute;rios?');
define('LNG_Stats_Users_SelectList_Heading', 'Detalhes do Usu&aacute;rio');
define('LNG_Stats_Users_SelectList_Intro', 'Nome de Usu&aacute;rio');
define('LNG_Stats_Users_NoSelection', 'Por favor escolha um usu&aacute;rio primeiro.');

define('LNG_User_Summary_Graph', 'Resumo Gr&aacute;fico do Usu&aacute;rio');
define('LNG_UserStatisticsSnapshot', 'Breve Estat&iacute;stica');
define('LNG_Stats_Users_Step3_Heading', 'Estat&iacute;sticas do Usu&aacute;rio');
define('LNG_Stats_Users_Step3_Intro', 'Visualizar um resumo para o usu&aacute;rio \'%s\'');
define('LNG_UserStatistics_Snapshot_EmailsSent', 'Emails Enviados');
define('LNG_Stats_UserCreateDate', 'Usu&aacute;rio Criado');
define('LNG_Stats_UserLastLoggedIn', '&Uacute;ltimo Login do Usu&aacute;rio');
define('LNG_UserLastNewsletterSent', '&Uacute;ltimo Envio de Campanha de Email');
define('LNG_UserNewslettersSent', 'Campanhas de Email Enviadas');
define('LNG_UserAutorespondersCreated', 'Resposta Autom&aacute;tica Criada');
define('LNG_Stats_TotalLists', 'Listas de Emails');
define('LNG_Stats_TotalSubscribers', 'Total de Assinantes');
define('LNG_Stats_TotalEmailsSent', 'Emails Enviados');

define('LNG_UserHasNotSentAnyNewsletters', 'O usu&aacute;rio selecionado n&atilde;o envio nenhuma campanha de email.');
define('LNG_UserHasNotLoggedIn', 'Usu&aacute;rio não logado');

define('LNG_Stats_ViewNewsletterStatistics_User', 'Visualizar estat&iacute;sticas para o usu&aacute;rio \'%s\'');
define('LNG_Stats_ViewNewsletterStatistics_Introduction_User', 'Visualizar estat&iacute;sticas para o usu&aacute;rio \'%s\'');
define('LNG_Stats_Users_Step3_EmailsSent_Intro', 'Ver n&uacute;mero de emails enviados pelo usu&aacute;rio \'%s\'.<br/><i>Isto n&atilde;o inclui campanhas de emails que ainda est&atilde;o sendo enviadas. (Em progresso)</i>');

/**
* Autoresponder statistics language variables.
*/
define('LNG_Stats_AutoresponderStatistics', 'Estat&iacute;sticas de Respostas Autom&aacute;ticas');
define('LNG_Stats_ChooseAutoresponder', '-- Escolha uma resposta autom&aacute;tica da lista abaixo --');
define('LNG_Stats_Autoresponders_Step1_Heading', 'Estat&iacute;sticas de Respostas Autom&aacute;ticas');
define('LNG_Stats_Autoresponders_Step1_Intro', 'Para iniciar, por favor escolha uma resposta autom&aacute;tica para visualizar suas estat&iacute;sticas.');
define('LNG_Stats_Autoresponders_Cancel', 'Tem certeza de que deseja cancelar a visualiza&ccedil;&atilde;o de estat&iacute;sticas de respostas autom&aacute;ticas?');
define('LNG_Stats_Autoresponders_SelectList_Heading', 'Detalhes da Resposta Autom&aacute;tica');
define('LNG_Stats_Autoresponders_SelectList_Intro', 'Resposta Autom&aacute;tica');
define('LNG_Stats_Autoresponders_NoSelection', 'Por favor escolha uma resposta autom&aacute;tica.');

define('LNG_Stats_Autoresponders_Step2_Heading', 'Autoresponder Statistics');
define('LNG_Stats_Autoresponders_Step2_Intro', 'View statistics for autoresponder \'%s\'');
define('LNG_NoAutoresponderStats', 'No statistics for autoresponder \'%s\' have been recorded.');
define('LNG_NoStatisticsToDelete', 'There are no statistics to delete.');
define('LNG_StatisticsDeleteNoStatistics_One', 'Cannot delete statistics when none are available.');
define('LNG_StatisticsDeleteNoStatistics_Many', 'Cannot delete statistics when none are available.');
define('LNG_NoAutorespondersHaveBeenSent', 'No autoresponder statistics have been recorded.');

/**
* Subscriber statistics language variables.
*/
define('LNG_Stats_ListStatistics', 'Mailing List Statistics');
define('LNG_Stats_List_Step1_Heading', 'Mailing List Statistics');
define('LNG_Stats_List_Step1_Intro', 'To get started, please choose a mailing list to view statistics for.');

define('LNG_Stats_List_Step2_Heading', 'Mailing List Statistics');
define('LNG_Stats_List_Step2_Intro', 'View statistics for mailing list \'%s\'');
define('LNG_NoSubscribersStats', 'No subscribers are on mailing list \'%s\'');


/**
* Newsletter Stats Snapshot
*/
define('LNG_Stats_Newsletter_Summary_Graph', 'Email Campaign Summary Information');

define('LNG_Newsletter_Summary_Graph_openchart', 'Email Campaign Opens');
define('LNG_Newsletter_Summary_Graph_unsubscribechart', 'Email Campaign Unsubscribes');
define('LNG_Newsletter_Summary_Graph_forwardschart', 'Email Campaign Forwards');
define('LNG_Newsletter_Summary_Graph_linkschart', 'Email Campaign Links');
define('LNG_Newsletter_Summary_Graph_bouncechart', 'Email Campaign Bounces');
define('LNG_Newsletter_Summary_Graph_userchart', 'Emails Sent');

define('LNG_NewsletterStatistics_Snapshot', 'Statistics Snapshot');
define('LNG_NewsletterStatistics_Snapshot_OpenStats', 'Open Stats');
define('LNG_NewsletterStatistics_Snapshot_LinkStats', 'Link Stats');
define('LNG_NewsletterStatistics_Snapshot_UnsubscribeStats', 'Unsubscribe Stats');
define('LNG_NewsletterStatistics_Snapshot_ForwardStats', 'Forwarding Stats');
define('LNG_NewsletterStatistics_Snapshot_Summary', 'View a summary for email campaign \'%s\', sent %s');
define('LNG_NewsletterStatistics_Snapshot_Heading', 'Statistics Snapshot');
define('LNG_NewsletterStatistics_StartSending', 'Start Sending');
define('LNG_NewsletterStatistics_FinishSending', 'Finished Sending');
define('LNG_NewsletterStatistics_SendingTime', 'Sending Time');
define('LNG_NewsletterStatistics_SentTo', 'Sent To');
define('LNG_NewsletterStatistics_SentBy', 'Sent By');
define('LNG_NewsletterStatistics_Opened', 'Opened');
define('LNG_NotFinishedSending', 'Not finished sending');
define('LNG_NewsletterStatistics_Snapshot_SendSize', '%s of %s');
Define('LNG_EmailOpens_Unique', '%s Unique Opens');
Define('LNG_EmailOpens_Total', '%s Total Opens');
define('LNG_PreviewThisNewsletter', 'Preview this email campaign');
define('LNG_SentToLists', 'Mailing Lists');
define('LNG_SentToList', 'Mailing List');

define('LNG_DateOpened', 'Date Opened');
define('LNG_NewsletterStatistics_Snapshot_OpenHeading', 'View open rates and email addresses for email campaign \'%s\', sent %s');
define('LNG_NewsletterStatistics_Snapshot_OpenHeading_Unique', 'View <b>unique</b> open rates and email addresses for email campaign \'%s\', sent %s');

define('LNG_NewsletterHasNotBeenOpened', 'This email campaign has not yet been opened by any recipients.');
define('LNG_NewsletterHasNotBeenOpened_CalendarProblem', 'This email campaign has not yet been opened by any recipients during the selected date range.');


define('LNG_NewsletterStatistics_Snapshot_LinkHeading', 'View link click statistics for email campaign \'%s\', sent %s');
define('LNG_NewsletterWasNotOpenTracked', 'Open tracking has been disabled for this email campaign.');
define('LNG_NewsletterHasNotBeenClicked', 'No links have been clicked by any subscribers yet.');
define('LNG_NewsletterHasNotBeenClicked_NoLinksFound', 'No links were found in this email campaign.');
define('LNG_NewsletterHasNotBeenClicked_CalendarProblem', 'No links have been clicked by any subscribers during the selected date range.');
define('LNG_NewsletterHasNotBeenClicked_LinkProblem', 'The link chosen has not been clicked by any subscribers.');
define('LNG_NewsletterHasNotBeenClicked_CalendarLinkProblem', 'The link chosen has not been clicked by any subscribers during the selected date range.');
define('LNG_NewsletterWasNotTracked_Links', 'Link tracking has been disabled for this email campaign.');

define('LNG_NewsletterStatistics_Snapshot_UnsubscribesHeading', 'View unsubscribe rates and email addresses for newsletter \'%s\', sent %s');
define('LNG_NewsletterHasNoUnsubscribes', 'This email campaign has not yet received any unsubscribe requests.');
define('LNG_NewsletterHasNoUnsubscribes_CalendarProblem', 'This email campaign has not yet received any unsubscribe requests during the selected date range.');

define('LNG_NewsletterStatistics_Snapshot_ForwardsHeading', 'View email forwarding details for email campaign \'%s\', sent %s');
define('LNG_NewsletterHasNoForwards', 'This email campaign has not yet been forwarded or did not include a send-to-friend link.');
define('LNG_NewsletterHasNoForwards_CalendarProblem', 'This email campaign has not been forwarded to anyone during the selected date range or did not contain a send-to-friend link.');


/**
* autoresponder stuff
*/
define('LNG_AutoresponderStatistics_Snapshot', 'Statistics Snapshot');
define('LNG_AutoresponderStatistics_Snapshot_OpenStats', 'Open Stats');
define('LNG_AutoresponderStatistics_Snapshot_LinkStats', 'Link Stats');
define('LNG_AutoresponderStatistics_Snapshot_UnsubscribeStats', 'Unsubscribe Stats');
define('LNG_AutoresponderStatistics_Snapshot_ForwardStats', 'Forwarding Stats');
define('LNG_AutoresponderStatistics_Snapshot_Summary', 'View a summary for autoresponder \'%s\'');
define('LNG_AutoresponderStatistics_Snapshot_Heading', 'Statistics Snapshot');
define('LNG_AutoresponderStatistics_StartSending', 'Start Sending');
define('LNG_AutoresponderStatistics_FinishSending', 'Finished Sending');
define('LNG_AutoresponderStatistics_SendingTime', 'Sending Time');
define('LNG_AutoresponderStatistics_SentTo', 'Sent To');
define('LNG_AutoresponderStatistics_SentBy', 'Sent By');
define('LNG_AutoresponderStatistics_Opened', 'Opened');
define('LNG_AutoresponderStatistics_Snapshot_SendSize', '%s of %s');
define('LNG_PreviewThisAutoresponder', 'Preview this autoresponder');
define('LNG_AutoresponderStatistics_Snapshot_OpenHeading', 'View open rates and email addresses for autoresponder \'%s\'');
define('LNG_AutoresponderStatistics_Snapshot_OpenHeading_Unique', 'View <b>unique</b> open rates and email addresses for autoresponder \'%s\'');

define('LNG_AutoresponderHasNotBeenOpened', 'The autoresponder has not been opened by any subscribers yet.');
define('LNG_AutoresponderHasNotBeenOpened_CalendarProblem', 'The autoresponder has not been opened by any subscribers during the selected date range.');


define('LNG_AutoresponderStatistics_Snapshot_LinkHeading', 'View link click statistics for autoresponder \'%s\'');
define('LNG_AutoresponderWasNotOpenTracked', 'Open tracking has been disabled for this autoresponder.');
define('LNG_AutoresponderHasNotBeenClicked', 'No links have been clicked by any subscribers yet.');
define('LNG_AutoresponderHasNotBeenClicked_NoLinksFound', 'No links were found in this autoresponder.');
define('LNG_AutoresponderHasNotBeenClicked_CalendarProblem', 'No links have been clicked by any subscribers during the selected date range.');
define('LNG_AutoresponderHasNotBeenClicked_LinkProblem', 'The link chosen has not been clicked by any subscribers.');
define('LNG_AutoresponderHasNotBeenClicked_CalendarLinkProblem', 'The link chosen has not been clicked by any subscribers during the selected date range.');
define('LNG_AutoresponderWasNotTracked_Links', 'Link tracking has been disabled for this autoresponder.');

define('LNG_AutoresponderStatistics_Snapshot_UnsubscribesHeading', 'View unsubscribe rates and email addresses for autoresponder \'%s\'');
define('LNG_AutoresponderHasNoUnsubscribes', 'The autoresponder has not had any unsubscribe requests yet.');
define('LNG_AutoresponderHasNoUnsubscribes_CalendarProblem', 'The autoresponder has not had any unsubscribe requests during the selected date range.');

define('LNG_AutoresponderStatistics_Snapshot_ForwardsHeading', 'View email forwarding details for autoresponder \'%s\'');
define('LNG_AutoresponderHasNoForwards', 'The autoresponder has not been forwarded to anyone yet, or did not have a send-to-friend link in it.');
define('LNG_AutoresponderHasNoForwards_CalendarProblem', 'The autoresponder has not been forwarded to anyone during the selected date range.');

define('LNG_AutoresponderStatistics_CreatedBy', 'Created By');
define('LNG_AutoresponderStatistics_SentWhen', 'Sent When');

define('LNG_ForwardedBy', 'Forwarded By');
define('LNG_ForwardedTo', 'Forwarded To');
define('LNG_ForwardTime', 'Date Forwarded');
define('LNG_HasSubscribed', 'Is Subscribed to List?');

define('LNG_LinkClicked', 'Link Clicked');
define('LNG_LinkClickTime', 'Click Time');
define('LNG_AnyLink', '-- View stats for all links --');

define('LNG_Today', 'Hoje');
define('LNG_Yesterday', 'Ontem');
define('LNG_Last24Hours', 'Últimas 24 Horas');
define('LNG_Last7Days', 'Últimos 7 Dias');
define('LNG_Last30Days', 'Últimas 30 Dias');
define('LNG_ThisMonth', 'Este M&ecirc;s');
define('LNG_LastMonth', '&Uacute;ltimo M&ecirc;s');
define('LNG_AllTime', 'Tudo');
define('LNG_DateRange', 'Per&iacute;odo');

define('LNG_Newsletter_Summary_Graph', 'Resumo Gr&aacute;fico da Campanha de Email');
define('LNG_Summary_Graph_Opened', 'Abertos (%s %%)');
define('LNG_Summary_Graph_Unopened', 'Não Abertos (%s %%)');

define('LNG_Autoresponder_Summary_Graph', 'Autoresponder Summary Graph');

define('LNG_Autoresponder_Summary_Graph_openchart', 'Autoresponder Opens');
define('LNG_Autoresponder_Summary_Graph_unsubscribechart', 'Autoresponder Unsubscribes');
define('LNG_Autoresponder_Summary_Graph_forwardschart', 'Autoresponder Forwards');
define('LNG_Autoresponder_Summary_Graph_linkschart', 'Autoresponder Links');

/**
* subscriber/mailing list stats.
*/
define('LNG_List_Summary_Graph_subscribersummary', 'Subscriber Summary');
define('LNG_ListStatistics_Snapshot', 'Subscribers Snapshot');
define('LNG_ListStatistics_Snapshot_PerDomain', 'Top 10 Domain Name Subscribers');
define('LNG_Subscribes', 'Subscribes');
define('LNG_Unsubscribes', 'Unsubscribes');
define('LNG_Forwards', 'Forwards');
define('LNG_DateTime', 'Data/Hora');
define('LNG_New_Subscribes', 'New Subscribes');
define('LNG_DomainName', 'Domain Name');
define('LNG_SubscribeCount', 'Subscribes');
define('LNG_ForwardCount', 'Forwards');
define('LNG_Summary_Domain_Name', '\'%s\' (%s %%)');
define('LNG_Unknown_List', 'Unknown Mailing List');
define('LNG_ListStatsTotalSubscribers', 'Total Subscribers: ');
define('LNG_ListStatsTotalUnsubscribes', 'Total Unsubscribes: ');
define('LNG_ListStatsTotalForwards', 'Total Forwards: ');
define('LNG_ListStatsTotalForwardSignups', 'Total Signups: ');

define('LNG_List_Summary_Graph_openchart', 'Open Statistics');
define('LNG_ListStatistics_Snapshot_OpenStats', 'Open Stats');
define('LNG_ListStatistics_Snapshot_OpenHeading', 'View open rates and email addresses for email campaigns and autoresponders sent to list \'%s\'');
define('LNG_ListStatistics_Snapshot_OpenHeading_Unique', 'View <b>unique</b> open rates and email addresses for email campaigns and autoresponders sent to list \'%s\'');
define('LNG_ListOpenStatsHasNotBeenOpened', 'No email campaigns or autoresponders have been opened.');
define('LNG_ListOpenStatsHasNotBeenOpened_CalendarProblem', 'No email campaigns or autoresponders have been opened in the date range selected.');


define('LNG_List_Summary_Graph_linkschart', 'Links Chart');
define('LNG_ListStatistics_Snapshot_LinksStats', 'Link Stats');
define('LNG_ListStatistics_Snapshot_LinkHeading', 'Link statistics for email campaigns and autoresponders sent to mailing list \'%s\'');
define('LNG_ListLinkStatsHasNotBeenClicked', 'No links in email campaigns or autoresponders sent to this list have been clicked.');
define('LNG_ListLinkStatsHasNotBeenClicked_CalendarProblem', 'No links have been found in any email campaigns or autoresponders sent to this mailing list in the date range selected.');

define('LNG_ListLinkStatsHasNotBeenClicked_NoLinksFound', 'No links have been found in any email campaigns or autoresponders sent to this mailing list.');
define('LNG_ListLinkStatsHasNotBeenClicked_CalendarLinkProblem', 'The selected link has not been clicked in the date range selected.');
define('LNG_ListLinkStatsHasNotBeenClicked_LinkProblem', 'No clicks have been detected for the link chosen');

define('LNG_List_Summary_Graph_unsubscribechart', 'Unsubscribe Chart');
define('LNG_ListStatistics_Snapshot_UnsubscribeStats', 'Unsubscribe Stats');
define('LNG_ListStatistics_Snapshot_UnsubscribesHeading', 'Unsubscribe statistics for email campaigns and autoresponders sent to mailing list \'%s\'');
define('LNG_ListHasNoUnsubscribes', 'No subscribers have unsubscribed from this mailing list.');
define('LNG_ListHasNoUnsubscribes_CalendarProblem', 'No subscribers have unsubscribed from this mailing list during the date range selected.');

define('LNG_List_Summary_Graph_forwardschart', 'Forwarding Statistics');
define('LNG_ListStatistics_Snapshot_ForwardsStats', 'Forward Stats');
define('LNG_ListStatistics_Snapshot_ForwardsHeading', 'Forward statistics for email campaigns and autoresponders sent to mailing list \'%s\'');
define('LNG_ListHasNoForwards', 'No subscribers have forwarded email campaigns or autoresponders sent to this list.');
define('LNG_ListHasNoForwards_CalendarProblem', 'No subscribers have forwarded email campaigns or autoresponders sent to this list in the date range selected.');


/**
* Bounce messages
*/
define('LNG_NewsletterStatistics_Snapshot_BounceStats', 'Bounce Stats');
define('LNG_NewsletterStatistics_Bounced', 'Bounced');
define('LNG_NewsletterStatistics_Snapshot_BounceHeading', 'View bounce statistics for email campaigns \'%s\', sent %s');
define('LNG_NewsletterHasNotBeenBounced', 'No bounce reports have been received for this email campaign.');
define('LNG_NewsletterHasNotBeenBounced_BounceType', 'No %ss have been received for this email campaign.');
define('LNG_NewsletterHasNotBeenBounced_CalendarProblem', 'No bounce reports have been received for this email campaign in the selected date range.');
define('LNG_NewsletterHasNotBeenBounced_CalendarProblem_BounceType', 'No %ss have occurred in the selected date range.');
define('LNG_AutoresponderStatistics_Snapshot_BounceStats', 'Bounce Stats');
define('LNG_AutoresponderStatistics_Bounced', 'Bounced');
define('LNG_AutoresponderStatistics_Snapshot_BounceHeading', 'View bounce statistics for autoresponder \'%s\'');

define('LNG_AutoresponderHasNotBeenBounced', 'No bounce reports have been received for this autoresponder.');
define('LNG_AutoresponderHasNotBeenBounced_BounceType', 'No %ss have been received for this autoresponder.');
define('LNG_AutoresponderHasNotBeenBounced_CalendarProblem', 'No bounce reports have been received for this autoresponder in the selected date range.');
define('LNG_AutoresponderHasNotBeenBounced_CalendarProblem_BounceType', 'No %ss have occurred in the selected date range.');

define('LNG_Summary_Graph_Bounced', 'Bounced (%s %%)');
define('LNG_Bounces', 'Bounces');
define('LNG_ListStatistics_Snapshot_BounceStats', 'Bounce Stats');
define('LNG_ListStatistics_Snapshot_BounceHeading', 'Bounce statistics for email campaigns and autoresponders sent to mailing list \'%s\'');
define('LNG_ListStatsHasNotBeenBounced', 'No emails that have been sent to this list have bounced.');
define('LNG_ListStatsHasNotBeenBounced_BounceType', 'No %ss have been received for subscribers on this mailing list.');
define('LNG_ListStatsHasNotBeenBounced_CalendarProblem', 'No bounce reports have been received for subscribers on this mailing list in the selected date range.');
define('LNG_ListStatsHasNotBeenBounced_CalendarProblem_BounceType', 'No %ss have occurred in the selected date range.');

define('LNG_BounceCount', 'Bounces');
define('LNG_Stats_TotalBounces', 'Total Bounces: ');
define('LNG_Bounce_Summary', 'Bounce Summary');
define('LNG_Stats_TotalSoftBounces', 'Total Soft Bounces: ');
define('LNG_Stats_TotalHardBounces', 'Total Hard Bounces: ');
define('LNG_BounceChart', 'Bounce Chart');
define('LNG_Bounce_Type_hard', 'Hard Bounce');
define('LNG_Bounce_Type_soft', 'Soft Bounce');
define('LNG_Bounce_Type_any', 'Any Bounce Type');
define('LNG_BounceType', 'Bounce Type');
define('LNG_BounceRule', 'Bounce Rule');
define('LNG_BounceDate', 'Bounce Date');
define('LNG_Bounce_Type_hard_bounce', LNG_Bounce_Type_hard);
define('LNG_Bounce_Type_soft_bounce', LNG_Bounce_Type_soft);
define('LNG_Bounce_Type_unknown_bounce', 'Unknown Bounce');

define('LNG_Bounce_Rule_emaildoesntexist', 'Email Address doesn\'t exist');
define('LNG_Bounce_Rule_domaindoesntexist', 'Domain Name doesn\'t exist');
define('LNG_Bounce_Rule_invalidemail', 'Invalid Email Address');
define('LNG_Bounce_Rule_relayerror', 'Relay Error');

define('LNG_Bounce_Rule_overquota', 'Over Quota');
define('LNG_Bounce_Rule_inactive', 'Inactive Email Account');

define('LNG_Stats_TotalBouncedEmails', 'Total Bounced Emails');
define('LNG_HardBounces', 'Hard Bounces');
define('LNG_SoftBounces', 'Soft Bounces');
define('LNG_Stats_NoSubscribersOnList', 'There are no subscribers on this mailing list.');
define('LNG_Stats_NoSubscribersOnList_DateRange', 'There are no subscribers on this mailing list in the selected date range.');

/**
**************************
* Changed/added in NX1.0.5
**************************
*/
define('LNG_Bounce_Rule_blockedcontent', 'Blocked due to content');

/**
**************************
* Changed/added in NX1.0.7
**************************
*/
define('LNG_Bounce_Rule_remoteconfigerror', 'Problem with remote servers configuration');
define('LNG_Bounce_Rule_localconfigerror', 'Problem with local servers configuration');

/**
**************************
* Changed/added in NX1.1.2
**************************
*/
define('LNG_Daily_Time_Display', 'ga');
define('LNG_DOW_Word_Display', 'D');
define('LNG_DOW_Word_Full_Display', 'l');
define('LNG_DOM_Number_Display', 'd');
define('LNG_Date_Display', 'D, jS M');

/**
**************************
* Changed/added in NX1.1.3
**************************
*/
define('LNG_Date_Display_Display', 'D, d. M');


?>
