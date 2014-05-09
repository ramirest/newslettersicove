<?php
/**
* Language file variables for the send management area.
*
* @see GetLang
*
* @version     $Id: send.php,v 1.20 2007/05/29 06:48:35 chris Exp $

*
* @package SendStudio
* @subpackage Language
*/

/**
* Here are all of the variables for the send area... Please backup before you start!
*/

define('LNG_NoLiveNewsletters', 'Nenhuma de suas campanhas de email est&atilde;o ativas.%s');
define('LNG_NoLiveNewsletters_HasAccess', ' Por favor v&aacute; para a p&aacute;gina de <a href="index.php?Page=Newsletters">Gerenciamento de Campanhas de Email</a> e ative uma campanha.');

define('LNG_Send_CancelPrompt', 'Tem certeza de que deseja cancelar o envio desta campanha?');


define('LNG_Send_Step1', 'Enviando Campanha de Email');
define('LNG_Send_Step1_Intro', 'Antes de poder enviar uma campanha de email, selecione para quais listas de email que deseja enviar.');

define('LNG_SendMailingList', LNG_MailingList);
define('LNG_HLP_SendMailingList', 'Para enviar para m&uacute;ltiplas listas de uma vez, pressione a tecla CTRL e clique nas listas.<br/>Para desmarcar uma lista, fa&ccedil;a o mesmo.');

define('LNG_Send_Step2', 'Enviar Campanha de Email');
define('LNG_Send_Step2_Intro', 'Use o formul&aacute;rio abaixo para escolher os destinat&aacute;rios desta campanha de email.');

define('LNG_Send_Step3', 'Enviar Campanha de Email');
define('LNG_Send_Step3_Intro', 'Use o formul&aacute;rio abaixo para configurar as op&ccedil;&otilde;es para esta lista de email.');

define('LNG_NewsletterDetails', 'Detalhes da Campanha');
define('LNG_SendNewsletter', 'Enviar Campanha de Email');
define('LNG_HLP_SendNewsletter', 'Qual campanha voc&ecirc; gostaria de enviar para seus assinantes?');

define('LNG_SendMultipart', 'Enviar Multipart');
define('LNG_HLP_SendMultipart', 'Enviando um email multipart permitir&aacute; aos programas de email dos assinantes decidirem em qual formato (HTML ou Texto) exibir o email.<br/><br/>&Eacute; melhor usar isto se voc&ecirc; n&atilde;o der aos seus assinantes a escolha do formato que eles ir&atilde;o receber (Ex. Todos eles assinar&atilde;o HTML), quando eles receberem em seus programas de email, ser&aacute; exibido no formato correto.<br/><br/>Se n&atilde;o tiver certeza do que fazer, deixe esta op&ccedil;&atilde;o marcada.');
define('LNG_SendMultipartExplain', 'Sim, enviar email como multipart');

define('LNG_TrackOpens', 'Seguir Abertura de Emails');
define('LNG_HLP_TrackOpens', 'Seguir abertura de emails quando um assinante receber um email. Isto se aplica apenas &agrave;as campanhas de email em HTML.');
define('LNG_TrackOpensExplain', 'Sim, seguir abertura de emails HTML');

define('LNG_TrackLinks', 'Seguir Links');
define('LNG_HLP_TrackLinks', 'Seguir todos os cliques nos links deste email. Depois que o email for enviado, voc&ecirc; pode visualizar os detalhes dos cliques nos links atrav&eacute;s da p&aacute;gina de estat&iacute;sticas.');
define('LNG_TrackLinksExplain', 'Sim, seguir todos os links desta campanha de email');

define('LNG_SelectNewsletterToSend', 'Selecione uma campanha de email');

define('LNG_SendImmediately', 'Enviar Imediatamente');
define('LNG_HLP_SendImmediately', 'Voc&ecirc; deseja enviar esta campanha imediatamente ou agend&aacute;-la para ser enviada em uma data futura?');
define('LNG_SendImmediatelyExplain', 'Sim, enviar esta campanha imediatamente');

define('LNG_SelectNewsletterPreviewPrompt', 'Por favor, selecione uma campanha de email primeiro.');
define('LNG_SelectNewsletterPrompt', 'Selecione da lista uma campanha para enviar.');
define('LNG_SendSize_One', 'Esta campanha ser&aacute; enviada para aproximadamente 1 assinante.');

define('LNG_SendSize_Many', 'Esta campanha ser&aacute; enviada para aproximadamente %s assinantes.');

define('LNG_ReadMore', 'Leia Mais');
define('LNG_ReadMoreWhyApprox', 'Se voc&ecirc; est&aacute; agendando esta campanha para ser enviada em uma data futura, ent&atilde;o o n&uacute;mero de pessoas a quem ela ser&aacute; enviada pode mudar a medida que a lista de emails &eacute; assinada ou cancelada pelos assinantes.');

define('LNG_EnterSendFromName','Por favor informe um \\\'Nome do remetente\\\'');
define('LNG_EnterSendFromEmail','Por favor informe um \\\'Email do remetente\\\'');
define('LNG_EnterReplyToEmail','Por favor informe um \\\'Email de resposta\\\'');
define('LNG_EnterBounceEmail','Por favor informe um \\\'Email de retorno\\\'');

define('LNG_CronSendOptions', 'Op&ccedil;&otilde;es de Envio');
define('LNG_SendTime', 'Hora do Envio');
define('LNG_SendDate', 'Data do Envio');
define('LNG_HLP_SendTime', 'Selecione a data e a hora que voc&ecirc; gostaria de enviar sua campanha.');
define('LNG_NotifyOwner', 'Notificar o Propriet&aacute;rio');
define('LNG_HLP_NotifyOwner', 'Notificar o(s) propriet&aacute;rio(s) da lista quando um agendamento inicia ou quando ele termina??');
define('LNG_NotifyOwnerExplain', 'Sim, notifique o(s) propriet&aacute;rio(s) via email');

define('LNG_StartSending', 'Iniciar Envio');
define('LNG_Send_Step4', 'Enviar Campanha de Email');
define('LNG_Send_Step4_Intro', 'Esta campanha de email ser&aacute; enviada imediamente. Clique em \'Iniciar Envio\' para come&ccedil;ar a enviar.');

define('LNG_Send_NewsletterName', 'Nome da Campanha: %s');
define('LNG_Send_SubscriberList', 'Lista(s) de Assinantes: %s');
define('LNG_Send_TotalRecipients', 'Total de Destinat&aacute;rio(s): %s');


define('LNG_Send_Step4_CronIntro', 'Esta campanha ser&aacute; agendada para enviar usando o sistema de envio programado, se voc&ecirc; clicar em "Sim" abaixo.<br/>Por favor verifique as informa&ccedil;&otilde;es abaixo antes de continuar.');

define('LNG_Send_Step4_CannotSendInPast', 'Voc&ecirc; tentou agendar a campanha para uma data passada. Por favor escolha uma data futura para enviar.');

define('LNG_Send_Step5', 'Enviando Campanha de Email...');
define('LNG_Send_NumberLeft_One', 'Restando 1 email para enviar. Por favor aguarde..');
define('LNG_Send_NumberLeft_Many', 'Restando %s emails para enviar. Por favor aguarde..');

define('LNG_Send_NumberSent_One', '1 email foi enviado.');
define('LNG_Send_NumberSent_Many', '%s emails foram enviados.');

define('LNG_Send_TimeSoFar', 'Tempo gasto at&eacute; agora (aprox): <b>%s</b>');
define('LNG_Send_TimeLeft', 'Tempo para conclus&atilde;o (aprox): <b>%s</b>');

define('LNG_Send_Finished_Heading', 'Enviar Campanha de Email');
define('LNG_Send_Finished', 'A campanha de email selecionada foi enviada. Levou %s para terminar.');
define('LNG_SendReport_Intro', 'A campanha de email selecionada foi enviada. Levou %s para terminar.');

define('LNG_SendReport_Success_One', 'A campanha de email selecionada foi enviada a 1 assinante com sucesso');
define('LNG_SendReport_Success_Many', 'A campanha de email selecionada foi enviada a %s assinantes com sucesso');

define('LNG_SendReport_Failure_One', 'A campanha de email selecionada na&atilde;o foi enviada a 1 assinante.');
define('LNG_SendReport_Failure_Many', 'A campanha de email selecionada na&atilde;o foi enviada a %s assinantes.');

define('LNG_PauseSending', 'Pausar envio. Voc&ecirc; pode voltar a enviar quando quiser.');
define('LNG_Send_Paused_Heading', 'Envio Pausado');
define('LNG_Send_Paused_Success', 'O envio de sua campanha de email foi pausada com sucesso.');
define('LNG_Send_Paused_Failure', 'O envio de sua campanha de email n&atilde;o pode ser pausado.');
define('LNG_Send_Paused', 'Voc&ecirc; pode voltar a enviar sua campanha de email atrav&eacute;s da p&aacute;gina "Gerenciar Campanhas de Email".<br/>');

define('LNG_JobScheduled', 'Seu trabalho foi agendado para executar em %s');
define('LNG_JobNotScheduled', 'Seu trabalho n&atilde;o foi agendado para executar em %s');

define('LNG_SendFinished', 'Sua campanha de email concluiu o envio.');

define('LNG_SendToTestListWarning', 'Esta campanha de email n&atilde;o foi enviada antes. &Eacute; recomend&aacute;vel que voc&ecirc; envie para uma lista de emails de teste antes de enviar para sua lista verdadeira.');
define('LNG_ApproveScheduledSend', 'Sim, enviar esta campanha');
define('LNG_CancelScheduledSend', 'N&atilde;o enviar esta campanha');

/**
* different helptips for sending a newsletter for "date subscribed", "opened newsletter" and "clicked link".
*/
define('LNG_Send_FilterByDate', LNG_FilterByDate);
define('LNG_HLP_Send_FilterByDate', 'Esta op&ccedil;&atilde;o permitir&aacute; que voc&ecirc; envie apenas para assinantes que assinaram antes, depois ou entre datas espec&iacute;ficas. Para enviar a todos os assinantes, deixe esta op&ccedil;&atilde;o desmarcada.');

define('LNG_Send_OpenedNewsletter', LNG_OpenedNewsletter);
define('LNG_HLP_Send_OpenedNewsletter', 'Esta op&ccedil;&atilde;o permitir&aacute; que voc&ecirc; envie apenas para assinantes que abriram uma campanha de email espec&iacute;fica ou resposta autom&aacute;tica enviada para esta lista de emails. Para enviar a todos os assinantes, deixe esta op&ccedil;&atilde;o desmarcada.');

define('LNG_Send_ClickedOnLink', LNG_ClickedOnLink);
define('LNG_HLP_Send_ClickedOnLink', 'Esta op&ccedil;&atilde;o permitir&aacute; que voc&ecirc; envie apenas para assinantes que clicaram em um link espec&iacute;fico em uma campanha de email ou resposta autom&aacute;tica que foi enviada para esta lista de emails. Para buscar por todos os assinantes, deixe esta op&ccedil;&atilde;o desmarcada.');


/**
**************************
* Changed/Added in NX1.1.1
**************************
*/
define('LNG_Send_Subscribers_Search_Step2', 'Use o formul&aacute;rio abaixo para encontrar assinantes para enviar sua campanha de email. Voc&ecirc; pode escolher enviar um email para assinantes baseado em um crit&eacute;rio de busca.<br/>Se voc&ecirc; gostaria de enviar para todos os assinantes, deixe esta op&ccedil;&atilde;o em branco e clique em "Pr&oacute;ximo".');

?>
