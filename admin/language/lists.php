<?php
/**
* Language file variables for the mailing lists area. This includes creating, editing, deleting, managing.
*
* @see GetLang
*
* @version     $Id: lists.php,v 1.27 2007/05/30 02:21:20 chris Exp $

*
* @package SendStudio
* @subpackage Language
*/

/**
* Here are all of the variables for the mailing lists area... Please backup before you start!
*/
define('LNG_CreateMailingList', 'Criar Lista de Email');
define('LNG_CreateMailingListIntro', 'Complete o formul&aacute;rio abaixo para criar uma nova lista de email.');
define('LNG_CreateListCancelButton', 'Tem certeza de que deseja cancelar a cria&ccedil;&atilde;o de uma nova lista de email?');
define('LNG_CreateMailingListHeading', 'Detalhes da Nova Lista');

define('LNG_EditMailingList', 'Editar Lista de Email');
define('LNG_EditMailingListIntro', 'Complete o formul&aacute;rio abaixo para atualizar a lista de email selecionada.');
define('LNG_EditListCancelButton', 'Tem certeza de que deseja cancelar a atualiza&ccedil;&atilde;o desta lista de email?');
define('LNG_EditMailingListHeading', 'Detalhes da Lista de Email');

define('LNG_ListsManage', 'Gerenciar Listas de Email');
define('LNG_Help_ListsManage', 'Use o formul&aacute;rio abaixo para gerenciar suas listas.');
define('LNG_Help_ListsManage_HasAccess', ' Para criar uma nova lista de email, clique no bot&atilde;o "Criar Lista de Email" abaixo.');

define('LNG_EnterListName', 'Por favor informe o nome da lista');
define('LNG_EnterOwnerName', 'Por favor o nome do propriet&aacute;rio desta lista.');
define('LNG_EnterOwnerEmail', 'Por favor informe o email do propriet&aacute;rio desta lista.');
define('LNG_EnterReplyToEmail', 'Por favor informe o endere&ccedil;o de \'Resposta\' padr&atilde;o para esta lista de email.');
define('LNG_EnterBounceEmail', 'Por favor informe o endere&ccedil;o de \'Retorno\' padr&atilde;o para esta lista de email.');

define('LNG_ListName', 'Nome da Lista');
define('LNG_ListOwnerName', 'Nome do Propriet&aacute;rio da Lista');
define('LNG_ListOwnerEmail', 'Email do Propriet&aacute;rio da Lista');
define('LNG_ListBounceEmail', 'Email de Retorno da Lista');
define('LNG_ListReplyToEmail', 'Endere&ccedil;o de Resposta da Lista');

define('LNG_NotifyOwner', 'Notificar o Propriet&aacute;rio da Lista');
define('LNG_NotifyOwnerExplain', 'Sim, notificar o propriet&aacute;rio da lista');
define('LNG_HLP_NotifyOwner', 'O propriet&aacute;rio da lista dever&aacute; ser notificado quando alguem assinar ou cancelar a assinatura de sua lista?');

define('LNG_ListNameIsNotValid', 'Nome da Lista n&atilde;o &eacute; v&aacute;lido');
define('LNG_ListOwnerNameIsNotValid', 'Nome do propriet&aacute;rio da lista n&atilde;o &eacute; v&aacute;lido');
define('LNG_ListOwnerEmailIsNotValid', 'Email do propriet&aacute;rio da lista n&atilde;o &eacute; v&aacute;lido');
define('LNG_ListBounceEmailIsNotValid', 'Email de retorno n&atilde;o &eacute; v&aacute;lido');
define('LNG_ListReplyToEmailIsNotValid', 'Email de resposta n&atilde;o &eacute; v&aacute;lido');
define('LNG_UnableToCreateList', 'N&atilde;o foi poss&iacute;vel criar a lista');
define('LNG_ListCreated', 'Sua lista de email foi salva com sucesso');

define('LNG_DeleteListPrompt', 'Tem certeza de que deseja excluir esta lista de email?');

define('LNG_HLP_ListName', 'O nome da lista como ir&aacute; aparecer tanto no painel de controle quanto em seus formul&aacute;rios de assinatura.');
define('LNG_HLP_ListOwnerName', 'Nome do propriet&aacute;rio da lista.');
define('LNG_HLP_ListOwnerEmail', 'Email do propriet&aacute;rio da lista.');
define('LNG_HLP_ListBounceEmail', 'Email para onde as mensagens ir&atilde;o retornar se um email inv&aacute;lido tiver sido assinado.');
define('LNG_HLP_ListReplyToEmail', 'O email para onde as respostas ser&atilde;o enviadas.');

define('LNG_UnableToUpdateList', 'N&atilde;o foi poss&iacute;vel atualizar a lista');
define('LNG_ListUpdated', 'A lista de email selecionada foi atualizada com sucesso');

define('LNG_ListDeleteFail', 'Ocorreu um erro ao tentar excluir a lista de email selecionada.');
define('LNG_ListsDeleteFail', 'Ocorreu um erro ao tentar excluir as listas de email selecioadas.');

define('LNG_ListDeleteSuccess', 'A lista de email selecionada foi exclu&iacute;da com sucesso');
define('LNG_ListsDeleteSuccess', 'As listas de email selecionadas foram exclu&iacute;das com sucesso');

define('LNG_TooManyLists', 'Voc&ecirc; tem muitas listas e alcan&ccedil;ou o limite m&aacute;ximo. Por favor exclua uma lista ou pe&ccedil;a para o administrador alterar a quantidade de listas que &eacute; permitido a voc&ecirc; criar.');

define('LNG_DeleteAllSubscribers', 'Excluir Todos os Assinantes');
define('LNG_DeleteAllSubscribersPrompt', 'Tem certeza de que deseja excluir todos os assinantes desta lista de email?');
define('LNG_ListDeleteAllSubscribersFail', 'N&atilde;o foi poss&iacute;vel excluir todos os assinantes desta lista de email');
define('LNG_ListDeleteAllSubscribersSuccess', 'Todos os assinantes desta lista de email foram excl&iacute;dos com sucesso');

define('LNG_ListsDeleteAllSubscribersFail', 'N&atilde;o foi poss&iacute;vel excluir todos os assinantes destas listas de email');
define('LNG_ListsDeleteAllSubscribersSuccess', 'Todos os assinantes destas listas de email foram exclu&iacute;dos com sucesso');

define('LNG_AllListSubscribersChangedFormat', 'Todos os assinantes foram atualizados para receber as campanhas de email no formato \'%s\'.');
define('LNG_AllListSubscribersNotChangedFormat', 'Todos os assinantes n&atilde;o puderam ser alterados para receber as campanhas de email no formato \'%s\'. Por favor, tente novamente.');

define('LNG_ChooseList', 'Por favor escolha uma ou mais listas primeiro.');
define('LNG_ChooseMultipleLists', 'Para executar esta acao voce precisa escolher mais de uma lista.');

define('LNG_MergeLists', 'Mesclar Listas');

define('LNG_ListCopySuccess', 'A lista selecionada foi copiada com sucesso');
define('LNG_ListCopyFail', 'A lista selecionada n&atilde;o pode ser copiada.');

define('LNG_AllListSubscribersChangedStatus', 'Todos os assinantes tiveram seus status alterados para \'%s\'.');

define('LNG_AllListSubscribersChangedConfirm', 'Todos os assinantes tiveram seus status alterados para \'%s\'.');
define('LNG_AllListSubscribersNotChangedConfirm', 'Todos os assinantes n&atilde;o tiveram seus status alterados para \'%s\'.');

define('LNG_ListBounceServer', 'Nome do Servidor de Email de Retorno');
define('LNG_HLP_ListBounceServer', 'Isto &eacute; usado para processar os emails retornados. Se voc&ecirc; informar seu servidor de email, usu&aacute;rio e senha, voc&ecirc; pode processar os retornos usando uma tarefa do cron.');

define('LNG_ListBounceUsername', 'Bounce Email User Name');
define('LNG_HLP_ListBounceUsername', 'This is used for processing bounced emails. If you enter your email server, username and password, you can process bounces using a cron job.');

define('LNG_ListBouncePassword', 'Bounce Email Password');
define('LNG_HLP_ListBouncePassword', 'This is used for processing bounced emails. If you enter your email server, username and password, you can process bounces using a cron job.');

define('LNG_IMAPAccount', 'IMAP Email Account');
define('LNG_IMAPAccountExplain', 'Yes, this is an IMAP Account');
define('LNG_HLP_IMAPAccount', 'Is the bounce email account an imap account? If it is not an imap account, it is a POP3 account.');

define('LNG_UseExtraMailSettingsExplain', 'Yes, use extra mail settings');
define('LNG_HLP_UseExtraMailSettings', 'You may need to set extra options to connect to an email account for bounce processing. If so, enable this option and fill in the required information below. If unsure, leave this unticked.');

define('LNG_ExtraMailSettings', 'Extra Mail Settings');
define('LNG_HLP_ExtraMailSettings', 'Please add any extra options that may be required to properly connect to a bounce email account. For example \\\'/notls\\\' or \\\'/nossl\\\'.');

define('LNG_MergeSuccessful', '%s lists have been merged together successfully');
define('LNG_MergeUnsuccessful', '%s lists couldn\\\'t be merged together.');
define('LNG_MergeDuplicatesRemoved_Success', 'Successfully removed %s duplicate subscriber(s) from the new merged list.');
define('LNG_MergeDuplicatesRemoved_Fail', 'Failed to remove %s duplicate subscriber(s) from the new merged list.');

define('LNG_Lists_DeleteAllSubscribers_Disabled', 'You cannot delete subscribers from this because you do not have access.');
define('LNG_ListCopyDisabled', 'You cannot copy this list because you do not have access.');
define('LNG_ListEditDisabled', 'You cannot edit this list because you do not have access.');
define('LNG_ListDeleteDisabled', 'You cannot delete this list because you do not have access.');
define('LNG_ListCopyDisabled_TooMany', 'You cannot copy this list, you have reached the maximum you can create');

define('LNG_BounceAccountDetails', 'Bounce Account Details');

define('LNG_RSS_Tip', 'Clique aqui para visualizar arquivos das campanhas de email enviados para esta lista de email.');
define('LNG_ArchiveLists', 'Arquivo');

/**
**************************
* Changed/added in NX1.0.5
**************************
*/
define('LNG_UseExtraMailSettings', 'Use Extra Mail Settings');

/**
**************************
* Changed/added in NX1.1.1
**************************
*/
define('LNG_AllListSubscribersNotChangedStatus', 'All subscribers have not had their status changed to \'%s\'.');

?>
