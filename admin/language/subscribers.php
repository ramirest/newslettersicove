<?php
/**
* Language file variables for the subscribers area (including adding, importing, removing, exporting, managing).
*
* @see GetLang
*
* @version     $Id: subscribers.php,v 1.45 2007/04/29 23:53:13 chris Exp $

*
* @package SendStudio
* @subpackage Language
*/

/**
* Here are all of the variables for the subscribers area... Please backup before you start!
*/

define('LNG_Subscribers_Manage', 'Gerenciar Assinantes');
define('LNG_Subscribers_Manage_Intro', 'Para iniciar, escolha uma lista de email para trabalhar.');
define('LNG_Subscribers_Manage_CancelPrompt', 'Tem certeza de que deseja cancelar o gerenciamento de assinantes?');

define('LNG_Subscribers_Add', 'Adicionar Assinantes');
define('LNG_Subscribers_Add_Step1', LNG_Subscribers_Add);
define('LNG_Subscribers_Add_Step1_Intro', 'Escolha uma lista de email para adicionar um assinantes a ela.');
define('LNG_Subscribers_Add_CancelPrompt', 'Tem certeza de que deseja cancelar a inscri&ccedil;&atilde;o de um novo assinante?');

define('LNG_Subscribers_EnterEmailAddress', 'Por favor informe um email para este assinante.');
define('LNG_ChooseValueForCustomField', 'Escolha um valor para este campo personalizado \'%s\'');
define('LNG_EnterValueForCustomField', 'Informe um valor para este campo personalizado \'%s\'');
define('LNG_ChooseOptionForCustomField', 'Escolha uma op&ccedil;&atilde;o para este campo personalizado \'%s\'');

define('LNG_Subscribers_Add_Step2', 'Adicionar Assinante');
define('LNG_Subscribers_Add_Step2_Intro', 'Complete o formul&aacute;rio abaixo para adicionar um &uacute;nico assinante &agrave; sua lista de email.');
define('LNG_NewSubscriberDetails', 'Detalhes do Novo Assinante');

define('LNG_SubscriberAddFail', 'Assinante n&atilde;o foi adicionado com sucesso');
define('LNG_SubscriberAddFail_Duplicate', 'Um assinante com o email \'%s\' j&aacute; existe.');
define('LNG_SubscriberAddFail_Unsubscribed', 'Um assinante com o email \'%s\' cancelou a assinatura desta lista de email. Para reativ&aacute;-lo, edite o assinante e altere seu status para "Ativo".');
define('LNG_SubscriberAddFail_Banned', 'O email \'%s\' est&aacute; banido de entrar nesta lista de email.');
define('LNG_SubscriberAddFail_InvalidEmailAddress', 'Um assinante com o email \'%s\' n&atilde;o pode ser adicionado a esta lista de email. Este &eacute; um email inv&aacute;lido.');

define('LNG_SubscriberAddSuccessful', 'Assinante adicionado com sucesso.');
define('LNG_SubscriberAddSuccessfulList', 'Assinante adicionado &agrave; lista \'%s\' com sucesso.');

define('LNG_Subscribers_Remove_Heading', 'Remover Assinantes');
define('LNG_RemoveOptions', 'Op&ccedil;&otilde;es de Remo&ccedil;&atilde;o');
define('LNG_HLP_RemoveOptions', 'O que voc&ecirc; quer que aconte&ccedil;a &agrave; lista de emails?<br><br/>Escolha \\\'Apagar\\\' para remov&ecirc;-lo completamente da lista.<br/><br/>Escolha \\\'Cancelar Assinatura\\\' para mov&ecirc;-lo para lista de assinaturas canceladas.');
define('LNG_EnterEmailAddressesToRemove', 'Por favor informe alguns emails para remover ou escolha um arquivo para enviar');

define('LNG_Unsubscribe', 'Cancelar Assinatura');

define('LNG_Subscribers_Remove', 'Remover Assinantes');
define('LNG_Subscribers_Remove_Intro', 'Escolha uma lista de email para remover os assinantes dela.');
define('LNG_Subscribers_Remove_CancelPrompt', 'Tem certeza de que deseja cancelar a remo&ccedil;&atilde;o de assinantes?');

define('LNG_Subscribers_Remove_Step2', 'Remover Assinantes');
define('LNG_Subscribers_Remove_Step2_Intro', 'Escolha op&ccedil;&otilde;es de remo&ccedil;&atilde;o usando o formul&aacute;rio abaixo.');

define('LNG_RemoveEmails', 'Remover Emails');
define('LNG_HLP_RemoveEmails', 'Digite ou cole uma lista de emails aqui que voc&ecirc; deseja remover. Voc&ecirc; tem que colocar cada email em uma nova linha.<br><br/>Use esta op&ccedil;&atilde;o se tiver um pequeno n&uacute;mero de emails para remover.');

define('LNG_RemoveFile', 'Remover do Arquivo');
define('LNG_HLP_RemoveFile', 'Escolha um arquivo para enviar que contenha emails para remover. Este arquivo dever&aacute; conter um email por linha.');

define('LNG_EmptyRemoveList', 'O arquivo que voc&ecirc; enviou n&atilde;o cont&eacute;m nenhum email.');

define('LNG_MassUnsubscribeFailed', 'Os seguintes emails n&atilde;o puderam ser cancelados ou apagados:<br/>');
define('LNG_MassUnsubscribeSuccessful', '%s emails foram removidos de sua lista com sucesso');
define('LNG_MassUnsubscribeSuccessful_Single', '1 email foi removido de sua lista com sucesso');

define('LNG_Subscribers_RemoveMore', 'Remover Mais Assinantes');

define('LNG_Subscribers_FoundOne', 'Sua busca retornou 1 assinante. Detalhes s&atilde;o mostrados abaixo.');
define('LNG_Subscribers_FoundMany', 'Sua busca retornou %s assinantes. Eles s&atilde;o mostrados abaixo.');
define('LNG_SubscribersManage', 'Gerenciar Assinantes Para a Lista \'%s\'');
define('LNG_SubscribersManageAnyList', 'Gerenciar Assinantes');
define('LNG_Help_SubscribersManage', 'Use o formul&aacute;rio abaixo para gerenciar seus assinantes.');
define('LNG_SubscriberEmailaddress', 'Emails');
define('LNG_DateSubscribed', 'Assinado');
define('LNG_SubscriberFormat', 'Formato');
define('LNG_DeleteSubscriberPrompt', 'Tem certeza de que deseja excluir este assinante?');

define('LNG_NoSubscribersToDelete', 'Nenhum assinante para excluir. Tente novamente.');

define('LNG_Subscriber_Deleted', '1 assinante foi exclu&iacute;do com sucesso');
define('LNG_Subscribers_Deleted', '%s assinantes foram exclu&iacute;dos com sucesso');

define('LNG_Subscriber_NotDeleted', '1 assinante n&atilde;o pode ser excl&iacute;do.');
define('LNG_Subscribers_NotDeleted', '%s assinantes n&atilde;o puderam ser exclu&iacute;dos.');

define('LNG_SubscriberStatus', 'Status');
define('LNG_SubscriberConfirmed', 'Confirmado');

define('LNG_NoSubscribersToChangeFormat', 'N&atilde;o h&aacute; assinantes para alterar os formatos de email.');

define('LNG_Subscriber_NotChangedFormat', '1 assinante n&atilde;o pode ser alterado para receber emails no formato %s.');
define('LNG_Subscribers_NotChangedFormat', '%s assinantes n&atilde;o puderam ser alterados para receberem emails no formato %s.');

define('LNG_Subscriber_ChangedFormat', '1 assinante foi alterado para receber emails no formato %s.');
define('LNG_Subscribers_ChangedFormat', '%s assinantes foram alterados para receberem emails no formato %s.');

define('LNG_NoSubscribersToChangeStatus', 'N&atilde;o h&aacute; assinantes para mudar o status.');

define('LNG_Subscriber_NotChangedStatus', '1 assinante n&atilde;o foi alterado para o status %s');
define('LNG_Subscribers_NotChangedStatus', '%s assinantes n&atilde;o foram alterados para o status %s');

define('LNG_Subscriber_ChangedStatus', '1 assinante foi alterado para o status %s');
define('LNG_Subscribers_ChangedStatus', '%s assinantes foram alterados para o status %s');

define('LNG_NoSubscribersToChangeConfirm', 'N&atilde;o h&aacute; assinantes para mudar o status de confirma&ccedil;&atilde;o.');

define('LNG_Subscriber_NotChangedConfirm', '1 assinante n&atilde;o pode ser alterado para o status de confirma&ccedil;&atilde;o \'%s\'.');
define('LNG_Subscribers_NotChangedConfirm', '%s assinantes n&atilde;o puderam ser alterados para o status de confirma&ccedil;&atilde;o \'%s\'.');

define('LNG_Subscriber_ChangedConfirm', '1 assinante foi alterado para o status de confirma&ccedil;&atilde;o \'%s\'.');
define('LNG_Subscribers_ChangedConfirm', '%s assinantes foram alterados para o status de confirma&ccedil;&atilde;o \'%s\'.');

define('LNG_Subscribers_Edit', 'Editar Assinante');
define('LNG_Subscribers_Edit_Intro', 'Modifique os detalhes do assinante no formul&aacute;rio abaixo e clique no bot&atilde;o \'Salvar\'.');
define('LNG_Subscribers_Edit_CancelPrompt', 'Tem certeza de que deseja cancelar a edi&ccedil;&atilde;o do cadastro deste assinante?');
define('LNG_EditSubscriberDetails', 'Detalhes da Edi&ccedil;&atilde;o do Assinante');

define('LNG_SubscriberAddFail_InvalidData', 'Dados inv&aacute;lidos foram digitados no campo personalizado \'%s\'.');
define('LNG_SubscriberAddFail_EmptyData_ChooseOption', '\'%s\' &eacute; um campo obrigat&oacute;rio. Por favor escolha uma op&ccedil;&atilde;o.');
define('LNG_SubscriberAddFail_EmptyData_EnterData', '\'%s\' &eacute; um campo obrigat&oacute;rio. Por favor preencha o campo abaixo.');

define('LNG_SubscriberEditFail_Duplicate', 'Algu&eacute;m j&aacute; assinou esta lista com o email \'%s\'.');
define('LNG_SubscriberEditSuccess', 'O assinante selecionado foi atualizado com sucesso');
define('LNG_SubscriberEditFail', 'N&atilde;o foi poss&iacute;vel atualizar as informa&ccedil;&otilde;es do assinante. Por favor, tente novamente.');
define('LNG_SubscriberEditFail_InvalidData', 'Dados inv&aacute;lidos foram digitados no campo personalizado \'%s\'.');
define('LNG_ChooseSubscribers', 'Por favor escolha pelo menos um assinante primeiro.');

define('LNG_Save_AddAnother', 'Salvar e Adicionar Outro');

define('LNG_UnsubscribeTime', 'Tempo de Cancelamento da Assinatura');
define('LNG_HLP_UnsubscribeTime', 'Quando o assinante cancelou a assinatura da lista de email.');
define('LNG_UnsubscribeIP', 'IP do Cancelamento da Assinatura');
define('LNG_HLP_UnsubscribeIP', 'O endere&ccedil;o ip do assinante quando ele cancelou a assinatura da lista de email.');

define('LNG_HLP_ConfirmedStatus', 'A Op&ccedil;&atilde;o confirmado &eacute; geralmente usado para o processo de duplo-optin onde os usu&aacute;rios confirmam suas assinaturas clicando no link no email de confirma&ccedil;&atilde;o. Se voc&ecirc; selecionar n&atilde;o confirmado, voc&ecirc; pode enviar em outra data aos assinantes, um email com um link de confirma&ccedil;&atilde;o para certificar-se de que eles desejam ser inclu&iacute;dos em sua lista de emails.');

define('LNG_HLP_Format', 'Qual formato de emails estes assinantes dever&atilde;o \\\'sinalizar\\\' para receber por padr&atilde;o? HTML ou Texto? Assinantes HTML podem receber emails tanto em HTML quanto em Texto, mas Assinantes Texto somente poder&atilde;o receber emails em Texto.<br><br>Se n&atilde;o tiver certeza, selecione HTML.');

define('LNG_HLP_SubscriberStatus', 'Assinantes ativos s&atilde;o aqueles que n&atilde;o cancelaram suas assinaturas e n&atilde;o tiveram suas mensagens devolvidas.<br/>O status \\\'devolvido\\\' &eacute; para aqueles que foram desabilitados de sua lista de emails por terem muitas mensagens retornadas de seus endere&ccedil;os de email, ou foram detecatados com muitos retornos.<br/>O status \\\'cancelado\\\' &eacute; para aqueles que especificamente cancelaram a assinatura para a lista de emails.');

/**
* Import Subscriber language variables.
*/
define('LNG_Subscribers_Import', 'Importar Assinantes');
define('LNG_Subscribers_Import_Intro', 'Escolha uma lista de emails para importar assinantes para ela.');
define('LNG_Import_From_file', 'Arquivo');
define('LNG_Subscribers_Import_Step2', 'Importar Assinantes');
define('LNG_Subscribers_Import_Step2_Intro', 'Especifique as configura&ccedil;&otilde;es de importa&ccedil;&atilde;o no formul&aacute;rio abaixo. Clique em \'Pr&oacute;ximo\' para continuar..');
define('LNG_ImportTutorialLink', 'Para um tutorial de como importar assinantes, clique aqui.');
define('LNG_ImportType', 'Tipo de Importa&ccedil;&atilde;o');
define('LNG_ImportDetails', 'Detalhes de Importa&ccedil;&atilde;o');
define('LNG_HLP_ImportType', 'Como voc&ecirc; ir&aacute; importar sua lista de assinantes?');
define('LNG_Subscribers_Import_CancelPrompt', 'Tem certeza de que deseja cancelar a importa&ccedil;&atilde;o de assinantes?');
define('LNG_ImportStatus', 'Status');
define('LNG_HLP_ImportStatus', 'Quando estes assinantes ser&atilde;o importados, qual deve ser seus status?');
define('LNG_ImportConfirmedStatus', 'Confirmado');
define('LNG_HLP_ImportConfirmedStatus', 'Os assinantes importados devem ser marcados como confirmados? A Op&ccedil;&atilde;o confirmado &eacute; geralmente usado para o processo de duplo-optin onde os usu&aacute;rios confirmam suas assinaturas clicando no link no email de confirma&ccedil;&atilde;o. Se voc&ecirc; selecionar n&atilde;o confirmado, voc&ecirc; pode enviar em outra data aos assinantes, um email com um link de confirma&ccedil;&atilde;o para certificar-se de que eles desejam ser inclu&iacute;dos em sua lista de emails.');

define('LNG_Subscribers_Import_Step3', 'Importar Assinantes');
define('LNG_Subscribers_Import_Step3_Intro', 'Use o formul&aacute;rio abaixo para definir quais campos da importa&ccedil;&atilde;o devem combinar com os campos de assinante.');
define('LNG_ImportFormat', 'Formato');
define('LNG_HLP_ImportFormat', 'Qual formato de emails estes assinantes dever&atilde;o \\\'sinalizar\\\' para receber por padr&atilde;o? HTML ou Texto? Assinantes HTML podem receber emails tanto em HTML quanto em Texto, mas Assinantes Texto somente poder&atilde;o receber emails em Texto. Se seu arquivo de importa&ccedil;&atilde;o conter um campo para uma formata&ccedil;&atilde;o espec&iacute;fica, ele ir&aacute; substituir esta configura&ccedil;&atilde;o.<br><br>Se n&atilde;o tiver certeza, selecione HTML.');

define('LNG_OverwriteExistingSubscriber', 'Substituir Existente');
define('LNG_YesOverwriteExistingSubscriber', 'Sim, substituir assinantes existentes');
define('LNG_HLP_OverwriteExistingSubscriber', 'Se um assinante j&aacute; existir na lista de emails com o mesmo email, devemos substituir os seus dados atuais?');

define('LNG_IncludeAutoresponder', 'Respostas autom&aacute;ticas');
define('LNG_YesIncludeAutoresponder', 'Sim, adicionar assinantes para respostas autom&aacute;ticas');
define('LNG_HLP_IncludeAutoresponder', 'Devemos adicionar os assinantes para qualquer resposta autom&aacute;tica que j&aacute; tiver sido configurada para a lista de email que eles est&atilde;o sendo adicionados?');

define('LNG_ImportFileDetails', 'Detalhes do Arquivo');
define('LNG_ContainsHeaders', 'Cont&eacute;m Cabe&ccedil;alhos');
define('LNG_YesContainsHeaders', 'Sim, este arquivo cont&eacute;m cabe&ccedil;alhos');
define('LNG_HLP_ContainsHeaders', 'A primeira linha de seu arquivo de importa&ccedil;&atilde;o cont&eacute;m cabe&ccedil;alhos? Se sim, cada cabe&ccedil;alho dever&aacute; ser separado com um campo separado, como:<br><br>Email, Nome, Sexo.');
define('LNG_FieldSeparator', 'Campo Separador');
define('LNG_EnterFieldSeparator', 'Por favor, digite um campo separador');

define('LNG_HLP_FieldSeparator', 'Qual &eacute; o caracter usado em seu arquivo de importa&ccedil;&atilde;o que separa o conte&uacute;do de cada novo campo em um registro?<br/>Se voc&ecirc; deseja usar o caracter de tabula&ccedil;&atilde;o digite a palavra &quot;TAB&quot; aqui.');
define('LNG_FieldEnclosed', 'Fechamento de Campo');
define('LNG_HLP_FieldEnclosed', 'Qual &eacute; o caracter com que cada campo ser&aacute; fechado? Todos os campos devem ter este caracter em torno deles. Por exemplo, um registro pode ser algo assim:<br><br>&quot;teste@teste.com&quot;, &quot;21&quot;, &quot;Masculino&quot;');
define('LNG_RecordSeparator', 'Separador de Registro');
define('LNG_HLP_RecordSeparator', 'Qual &eacute; o caracter usado em seu arquivo de importa&ccedil;&atilde;o que separa um registro do pr&oacute;ximo?');
define('LNG_ImportFile', 'Arquivo de Importa&ccedil;&atilde;o');
define('LNG_HLP_ImportFile', 'Escolha um arquivo para enviar que contenha detalhes do assinante que voc&ecirc; quer importar. Este deve ser um arquivo de texto simples.');
define('LNG_ImportFile_FieldSeparatorEmpty', 'Por favor informe um separador de campo.');
define('LNG_ImportFile_RecordSeparatorEmpty', 'Por favor informe um separador de registro.');
define('LNG_ImportFile_FileEmpty', 'Por favor escolha um arquivo para importar.');

define('LNG_MatchOption', 'Op&ccedil;&atilde;o correspondente para o campo \'%s\'');
define('LNG_ImportFields', 'Vincular Campos Importados');
define('LNG_EmailAddressNotLinked', 'O campo de email do assinante n&atilde;o est&aacute; vinculado. N&atilde;o podemos prosseguir sem vincul&aacute;-lo.');

define('LNG_MappingOption', 'Mapear Campo');
define('LNG_HLP_MappingOption', 'Qual campo do banco de dados se refere ao campo \\\'%s\\\' do arquivo?');

define('LNG_Subscribers_Import_Step4', 'Importar Assinantes');
define('LNG_Subscribers_Import_Step4_Intro', 'Clique em "Iniciar Importa&ccedil;&atilde;o" para iniciar a importa&ccedil;&atilde;o de seus assinantes.');
define('LNG_ImportStart', 'Iniciar Importa&ccedil;&atilde;o');

define('LNG_ImportSubscribers_success_Many', '%s assinantes foram importados com sucesso');
define('LNG_ImportSubscribers_success_One', '1 foi importado com sucesso');

define('LNG_ImportSubscribers_updates_Many', '%s assinantes foram atualizados com sucesso');
define('LNG_ImportSubscribers_updates_One', '1 foi atualizado com sucesso');

define('LNG_ImportSubscribers_duplicates_Many', '%s assinantes cont&ecirc;m emails duplicados');
define('LNG_ImportSubscribers_duplicates_Many_Link', '<a href="#" onclick="javascript: ShowReport(\'%s\'); return false;">%s assinantes cont&ecirc;m emails duplicados</a>');
define('LNG_ImportSubscribers_duplicates_One_Link', '<a href="#" onclick="javascript: ShowReport(\'%s\'); return false;">1 assinante cont&eacute;m email duplicado</a>');

define('LNG_InvalidSubscribeDate', ' <-- Data de Assinatura Inv&aacute;lida');
define('LNG_InvalidCustomFieldData', ' <-- Dados do campo personalizado inv&aacute;lidos para o campo \'%s\'');
define('LNG_InvalidSubscriberEmailAddress', ' <-- Email Inv&aacute;lido');
define('LNG_InvalidSubscriberImportLine_NotEnough', ' <-- Faltando um delimitador');
define('LNG_InvalidSubscriberImportLine_TooMany', ' <-- Muitos delimitadores');

define('LNG_InvalidReportURL', 'Voc&ecirc; acessou uma url de relat&oacute;rio inv&aacute;lida. Por favor tente novamente.');
define('LNG_ImportResults_Report_Invalid_Heading', 'URL de Relat&oacute;rio Inv&aacute;lida.');
define('LNG_ImportResults_Report_Invalid_Intro', 'Voc&ecirc; acessou uma url inv&aacute;lida. Por favor feche esta janela e tente novamente.');

define('LNG_ImportResults_Report_Duplicates_Heading', 'Emails duplicados encontrados');
define('LNG_ImportResults_Report_Duplicates_Intro', 'Os seguintes emails j&aacute; estavam em sua lista de emails ou no arquivo que voc&ecirc; enviou v&aacute;rias vezes e n&atilde;o foram importados novamente.');

define('LNG_ImportResults_Report_Failures_Heading', 'Falha ao importar emails');
define('LNG_ImportResults_Report_Failures_Intro', 'N&atilde;o foi poss&iacute;vel importar os seguintes emails. Por favor tente novamente.');

define('LNG_ImportSubscribers_failures_Many', '%s assinantes n&atilde;o foram importados com sucesso');
define('LNG_ImportSubscribers_failures_Many_Link', '<a href="#" onclick="javascript: ShowReport(\'%s\'); return false;">%s assinantes n&atilde;o foram importados com sucesso</a>');
define('LNG_ImportSubscribers_failures_One_Link', '<a href="#" onclick="javascript: ShowReport(\'%s\'); return false;">1 assinante n&atilde;o foi importado com sucesso</a>');

define('LNG_ImportResults_Report_Unsubscribed_Heading', 'Emails Cancelados');
define('LNG_ImportResults_Report_Unsubscribed_Intro', 'N&atilde;o foi poss&iacute;vel importar os seguintes emails pois eles tiveram as assinaturas canceladas desta lista.');

define('LNG_ImportSubscribers_unsubscribes_Many', '%s assinaturas est&atilde;o canceladas');
define('LNG_ImportSubscribers_unsubscribes_Many_Link', '<a href="#" onclick="javascript: ShowReport(\'%s\'); return false;">%s assinaturas est&atilde;o canceladas desta lista</a>');
define('LNG_ImportSubscribers_unsubscribes_One_Link', '<a href="#" onclick="javascript: ShowReport(\'%s\'); return false;">1 assinatura est&aacute; cancelada desta lista</a>');

define('LNG_ImportResults_Report_Banned_Heading', 'Email banido');
define('LNG_ImportResults_Report_Banned_Intro', 'N&atilde;o foi poss&iacute;vel importar os seguintes emails pois eles foram proibidos de entrar nesta lista.');

define('LNG_ImportSubscribers_bans_Many', '%s assinantes foram proibidos de entrar nesta lista de emails');
define('LNG_ImportSubscribers_bans_Many_Link', '<a href="#" onclick="javascript: ShowReport(\'%s\'); return false;">%s assinantes foram proibidos de entrar nesta lista de emails</a>');
define('LNG_ImportSubscribers_bans_One_Link', '<a href="#" onclick="javascript: ShowReport(\'%s\'); return false;">1 assinante foi proibido de entrar nesta lista de emails</a>');

define('LNG_ImportResults_Heading', 'Importar Assinantes');
define('LNG_ImportResults_Intro', 'A importa&ccedil;&atilde;o de assinantes foi conclu&iacute;da com sucesso');

define('LNG_DuplicateReport', '<b>Emails Duplicados</b>');
define('LNG_FailureReport', '<b>N&atilde;o &eacute; poss&iacute;vel subscrever estes emails</b>');

define('LNG_ImportResults_InProgress', 'Importa&ccedil;&atilde;o em Andamento');
define('LNG_ImportResults_InProgress_Message', 'Por favor aguarde enquanto tentamos importar os %s registro(s) de assinante(s)...');

define('LNG_ImportSubscribers_InProgress_success_Many', '%s assinantes foram importados at&eacute; agora');
define('LNG_ImportSubscribers_InProgress_success_One', '1 assinante foi importado at&eacute; agora');

define('LNG_ImportSubscribers_InProgress_updates_Many', '%s assinantes foram atualizados at&eacute; agora');
define('LNG_ImportSubscribers_InProgress_updates_One', '1 assinante foi atualizado at&eacute; agora');

define('LNG_ImportSubscribers_InProgress_duplicates_Many', '%s assinantes duplicados foram encontrados at&eacute; agora');
define('LNG_ImportSubscribers_InProgress_duplicates_One', '1 assinante duplicado foi encontrado at&eacute; agora');

define('LNG_ImportSubscribers_InProgress_failures_Many', '%s assinantes n&atilde;o foram importados at&eacute; agora');
define('LNG_ImportSubscribers_InProgress_failures_One', '1 assinante n&atilde;o foi importado at&eacute; agora');

define('LNG_ImportSubscribers_InProgress_unsubscribes_Many', '%s linhas cont&ecirc;m emails cancelados at&eacute; agora');
define('LNG_ImportSubscribers_InProgress_unsubscribes_One', '1 linha cont&eacute;m email cancelado at&eacute; agora');

define('LNG_ImportSubscribers_InProgress_bans_Many', '%s linhas cont&ecirc;m emails ou dom&iacute;nios banidos at&eacute; agora');
define('LNG_ImportSubscribers_InProgress_bans_One', '1 linha cont&eacute;m email ou dom&iacute;nio banido at&eacute; agora');


/**
* Export Subscribers.
*/
define('LNG_Subscribers_Export', 'Export Subscribers');
define('LNG_Subscribers_Export_Intro', 'Choose a mailing list to export subscribers from.');
define('LNG_Subscribers_Export_CancelPrompt', 'Are you sure you want to cancel exporting subscribers?');
define('LNG_Subscribers_Export_Step3', 'Export Subscribers');
define('LNG_Subscribers_Export_Step3_Intro', 'Select your export options to export your subscribers to a file.');
define('LNG_ExportStart', 'Start Export');
define('LNG_Subscribers_Export_FoundOne', '1 subscriber matched your search and can be exported.');
define('LNG_Subscribers_Export_FoundMany', '%s subscribers matched your search and can be exported.');
define('LNG_IncludeFields', 'Fields to Include');
define('LNG_ExportOptions', 'Export Options');

define('LNG_IncludeHeader', 'Include Field Headers?');
define('LNG_HLP_IncludeHeader', 'Should this export include field headers? If so, the first line of the file will look something like this:<br><br>Email, Status, Format');

define('LNG_FieldEnclosedBy', 'Field Enclosed By');
define('LNG_HLP_FieldEnclosedBy', 'Which character (if any) should each field be wrapped in? For example, if you enter a quote, then a sample record might look like this:<br><br>&quot;test@test.com&quot;, &quot;21&quot;, &quot;James&quot;');

define('LNG_Export_FieldSeparator', LNG_FieldSeparator);
define('LNG_HLP_Export_FieldSeparator', 'What character should be added to this export file to separate the contents of each new field in a record?');

define('LNG_ExportField', 'Field #%s');
define('LNG_SubscriberNone', 'None');
define('LNG_ExportSummary_FoundOne', 'Click the "Start Export" button below to start exporting your subscriber.');
define('LNG_ExportSummary_FoundMany', 'Click the "Start Export" button below to start exporting your %s subscribers.');
define('LNG_Subscribers_Export_Step4', 'Export Subscribers');
define('LNG_Subscribers_Export_Step4_Intro', 'Click the "Start Export" button to start exporting.');

define('LNG_ExportResults_InProgress_Message', 'Please wait while we attempt to export your %s subscriber(s).');

define('LNG_ExportResults_InProgress_Status', '%s of %s subscribers have been exported so far...');

define('LNG_ExportResults_Heading', 'Export Results');
define('LNG_ExportResults_Intro', 'The selected subscribers have been exported successfully. <a href=%s target=_blank>Click here to download the export file</a>. You should delete this file once you have finished downloading.');
define('LNG_ExportResults_Link', 'Click here to download your exported subscribers.');
define('LNG_ExportResults_InProgress', 'Exporting Subscribers');

define('LNG_SubscriberEmail', 'Email');

define('LNG_SubscribeDate_DMY', 'Data de Assinatura (dd/mm/aaaa)');
define('LNG_SubscribeDate_MDY', 'Data de Assinatura (mm/dd/aaaa)');
define('LNG_SubscribeDate_YMD', 'Data de Assinatura (aaaa/mm/dd)');

define('LNG_IncludeField', 'Include this field?');
define('LNG_HLP_IncludeField', 'Do you want to include this field in the export of your mailing list? If not, set this option to \\\'None\\\'');

define('LNG_DeleteExportFile', 'Delete export file');



/**
* Now for banned subscribers.
*/
define('LNG_BannedEmailDetails', 'Banned Email/Domain Details');
define('LNG_Subscribers_Banned_Add', 'Add Banned Email');
define('LNG_Subscribers_Banned_Add_Intro', 'Use the form or upload a file below to add one or more email addresses to your banned email list. <br>Each email address should be placed on a new line. You can also ban an entire domain using @DOMAINNAME. For example, @Hotmail.com.');
define('LNG_BannedEmails', 'Email(s) to Ban');
define('LNG_HLP_BannedEmails', 'Enter the list of emails addresses to ban here. Separate each email address with a new line. If you would like to ban a whole domain, simply enter @DOMAINNAME. For example, to ban everyone at Hotmail, enter @hotmail.com.');

define('LNG_BanSingleEmail', 'Email to Ban');
define('LNG_HLP_BanSingleEmail', 'Enter the email address to ban here. If you would like to ban a whole domain, simply enter @DOMAINNAME. For example, to ban everyone at Hotmail, enter @hotmail.com.');

define('LNG_BannedEmailsChooseList', 'Ban From Mailing List');
define('LNG_HLP_BannedEmailsChooseList', 'Choose a list to ban these email addresses from.');

// we duplicate it here so we can use a different helptip.
define('LNG_BannedEmailsChooseList_Edit', LNG_BannedEmailsChooseList);
define('LNG_HLP_BannedEmailsChooseList_Edit', 'Choose the mailing list to ban this email address or domain name from.');

define('LNG_BannedFile', 'Email Ban File');
define('LNG_HLP_BannedFile', 'Choose a file to upload that contains a list of email addresses to ban. The file should contain one email address per line.');
define('LNG_Subscribers_GlobalBan', '-- Global Ban (All Lists) --');
define('LNG_Subscribers_Banned_CancelPrompt', 'Are you sure you want to cancel banning emails?');
define('LNG_Banned_Add_EmptyList', 'Please enter an email address or domain name to ban.');
define('LNG_Banned_Add_EmptyFile', 'Please select a file that contains email addresses you want to ban.');
define('LNG_Banned_Add_ChooseList', 'Please choose a list to ban these email addresses from.');
define('LNG_EmptyBannedList', 'The file that you uploaded contains no email addresses.');
define('LNG_MassBanSuccessful', '%s email addresses were successfully added to your banned email list.');
define('LNG_MassBanSuccessful_Single', '1 email address has been banned successfully');
define('LNG_MassBanFailed', '<br>An error occurred while trying to ban the following email addresses:<br/>');
define('LNG_Subscriber_AlreadyBanned', 'Email address is already banned');

define('LNG_Subscribers_Banned', 'Manage Banned Emails');
define('LNG_Subscribers_Banned_Intro', 'Choose a mailing list to find email addresses that are banned from that list. <br>The \'Global Ban\' contains email addresses that are banned from all existing and future mailing lists.');
define('LNG_Subscribers_BannedManage_CancelPrompt', 'Are you sure you want to cancel managing your banned emails?');

define('LNG_Banned_Subscribers_FoundOne', 'Found 1 banned email address.');
define('LNG_Banned_Subscribers_FoundMany', 'Found %s banned email addresses.');

define('LNG_SubscribersManageBanned', 'Manage Banned Emails (For List \'%s\')');
define('LNG_Manage_Banned_Intro', 'Use the form below to manage banned email addresses.');
define('LNG_BannedSubscriberEmail', 'Email Address');
define('LNG_Delete_Banned_Selected', 'Delete Selected');
define('LNG_BannedDate', 'Date Banned');
define('LNG_DeleteBannedSubscriberPrompt', 'Are you sure you want to remove this ban?');
define('LNG_ConfirmBannedSubscriberChanges', 'Are you sure you want to make these changes?\nThis action cannot be undone.');
define('LNG_ConfirmRemoveBannedSubscribers', 'Are you sure you want to remove these banned emails?');
define('LNG_ChooseBannedSubscribers', 'Please choose some bans to remove.');

define('LNG_BannedAddButton', 'Add Banned Email');
define('LNG_NoBannedSubscribersOnList', 'The mailing list \'%s\' does not contain any banned email addresses.');

define('LNG_Subscriber_Ban_NotDeleted_One', '1 banned email address was not deleted from the list \'%s\'.');
define('LNG_Subscriber_Ban_Deleted_One', '1 banned email was deleted successfully from list \'%s\'.');

define('LNG_Subscriber_Ban_NotDeleted_Many', '%s banned email addresses bans were not deleted from list \'%s\'.');
define('LNG_Subscriber_Ban_Deleted_Many', '%s banned email addresses were deleted successfully from list \'%s\'.');

define('LNG_Subscribers_Banned_Edit', 'Edit Banned Email');
define('LNG_Subscribers_Banned_Edit_Intro', 'Modify the details of the banned email address in the form below and click on the \'Save\' button.');
define('LNG_Subscribers_Banned_Edit_CancelPrompt', 'Are you sure you want to cancel editing this banned email address?');
define('LNG_Banned_Edit_Empty', 'Please enter an email address to ban.');
define('LNG_Banned_Edit_ChooseList', 'Please choose a list to ban this email address from.');

define('LNG_SubscriberBan_Updated', 'The banned email has been updated successfully');
define('LNG_SubscriberBan_NotUpdated', 'Banned email address has not been updated.');
define('LNG_SubscriberBan_UnableToUpdate', 'Unable to update the banned information. Please try again.');

define('LNG_SubscriberBanListEmpty', '\'%s\' has no banned email addresses');

define('LNG_Ban_Count_Many', ' (%s bans)');
define('LNG_Ban_Count_One', ' (1 ban)');

/**
**************************
* Changed/added in NX1.0.5
**************************
*/
define('LNG_ImportResults_Report_Bads_Heading', 'Dados ruins foram encontrados');
define('LNG_ImportResults_Report_Bads_Intro', 'As seguintes linhas com os emails listados abaixo no arquivo de importa&ccedil;&atilde;o foram encontrados com dados ruins.');

define('LNG_ImportSubscribers_bads_One', '1 assinante cont&eacute;m dados ruins');
define('LNG_ImportSubscribers_bads_Many', '%s assinantes cont&ecirc;m dados ruins');
define('LNG_ImportSubscribers_bads_Many_Link', '<a href="#" onclick="javascript: ShowReport(\'%s\'); return false;">%s assinantes cont&ecirc;m dados ruins</a>');
define('LNG_ImportSubscribers_bads_One_Link', '<a href="#" onclick="javascript: ShowReport(\'%s\'); return false;">1 assinante cont&eacute;m dados ruins</a>');

define('LNG_ImportSubscribers_InProgress_bads_Many', '%s linhas cont&ecirc;m dados ruins at&eacute; agora');
define('LNG_ImportSubscribers_InProgress_bads_One', '1 linha cont&eacute;m dados ruins at&eacute; agora');

?>
