<?php
/**
* Language file variables. These are used all over the place - menus, paging, searching, templates, newsletters and so on.
*
* @see GetLang
*
* @version     $Id: language.php,v 1.91 2007/06/22 02:39:16 chris Exp $

*
* @package SendStudio
* @subpackage Language
*/

/**
* Here are the general language variables. These are used all over the place - menus, custom fields, paging, searching, templates etc etc.
*/
ini_set('display_errors', true);
error_reporting(E_ALL);

define('LNG_EmailAddress', 'Email');

define('LNG_ControlPanel', "Painel de Controle");
define('LNG_Users', "Usu&aacute;rios");
define('LNG_Statistics', "Estat&iacute;sticas");
define('LNG_Forms', 'Formul&aacute;rios de Website');
define('LNG_Settings', "Configura&ccedil;&otilde;es");
define('LNG_Go', 'Ir');
define('LNG_ShowHelp', 'Mostrar Ajuda');
define('LNG_HideHelp', 'Ocultar Ajuda');
define('LNG_Logout', 'Sair');
define('LNG_To', 'Para');
define('LNG_From', 'De');
define('LNG_N/A', 'N/A');

define('LNG_Action', 'A&ccedil;&atilde;o');

define('LNG_ViewAll', 'Ver Todos');

define('LNG_Page', 'P&aacute;gina');
define('LNG_Of', 'de');

define('LNG_GoToFirst', 'Ir Para Primeira P&aacute;gina');
define('LNG_GoToLast', 'Ir Para &Uacute;ltima P&aacute;gina');

define('LNG_UnsubLinkPlaceholder', 'Cancelar minha assinatura desta lista de email');

define('LNG_Delete_Selected', 'Apagar Selecionados');

define('LNG_NumberFormat_Dec', '.');
define('LNG_NumberFormat_Thousands', ',');
define('LNG_DateFormat', 'd M Y');
define('LNG_Quickstats_DateFormat', 'd-M-Y');
define('LNG_TimeFormat', 'F j Y, g:i a');
define('LNG_UserDateHeader', 'A hora atual do seu sistema &eacute;: %s - (%s)');
define('LNG_UserDateFormat', 'g:i a, d M Y');
define('LNG_Stats_TimeFormat', 'g:i a');

define('LNG_Yesterday_Date', 'Ontem');
define('LNG_Today_Date', 'Hoje');
define('LNG_Tomorrow_Date', 'Amanh&atilde;');

define('LNG_Yesterday_Time', 'Ontem @ %s');
define('LNG_Today_Time', 'Hoje @ %s');
define('LNG_Tomorrow_Time', 'Amanh&atilde; @ %s');

define('LNG_ViewingResultsFor', 'Visualizando resultados para');

define('LNG_Home', "Home");

define('LNG_Either_Confirmed', 'Ambos confirmados e n&atilde;o confirmados');
define('LNG_Either_Format', 'Qualquer Formato');
define('LNG_Either_Status', 'Qualquer Status');

define('LNG_Total', "Total");

define('LNG_Yes', 'Sim');
define('LNG_No', 'N&atilde;o');

define('LNG_ShowMore', 'Mostrar Mais');
define('LNG_HideMore', 'Ocultar');

define('LNG_Created', 'Criado');
define('LNG_Date_Created', 'Data de Cria&ccedil;&atilde;o');
define('LNG_Subscribers', 'Assinantes');

define('LNG_Mon', 'Segunda');
define('LNG_Tue', 'Ter&ccedil;a');
define('LNG_Wed', 'Quarta');
define('LNG_Thu', 'Quinta');
define('LNG_Fri', 'Sexta');
define('LNG_Sat', 'S&aacute;bado');
define('LNG_Sun', 'Domingo');

define('LNG_Jan', 'Jan');
define('LNG_Feb', 'Fev');
define('LNG_Mar', 'Mar');
define('LNG_Apr', 'Abr');
define('LNG_May', 'Mai');
define('LNG_Jun', 'Jun');
define('LNG_Jul', 'Jul');
define('LNG_Aug', 'Ago');
define('LNG_Sep', 'Set');
define('LNG_Oct', 'Out');
define('LNG_Nov', 'Nov');
define('LNG_Dec', 'Dez');

define('LNG_HoldMouseOver', "Segure o mouse sobre o texto sublinhado para mais informa&ccedil;&otilde;es");

define('LNG_PagingNext', "Pr&oacute;ximo");
define('LNG_PagingBack', "Voltar");

define('LNG_Next', "Pr&oacute;ximo >>");
define('LNG_Back', "Voltar");
define('LNG_ResultsPerPage',"Resultados por p&aacute;gina");

define('LNG_ErrCouldntLoadTemplate', 'N&atilde;o foi poss&iacute;vel carregar o template: %s');
define('LNG_PageTitle', 'Painel de Controle');
define('LNG_Status', 'Status');
define('LNG_Edit', 'Editar');
define('LNG_Delete', 'Apagar');
define('LNG_Save', 'Salvar');
define('LNG_SaveAndExit', 'Salvar e Sair');
define('LNG_Cancel', 'Cancelar');
define('LNG_Copy', 'Copiar');

define('LNG_Password', 'Senha');
define('LNG_PasswordConfirm', 'Senha (Confirma&ccedil;&atilde;o)');
define('LNG_PasswordConfirmAlert', 'Por favor confirme sua nova senha');
define('LNG_PasswordsDontMatch', 'As senhas informadas n&atilde;o combinam. Por favor tente novamente.');

define('LNG_GoBack', 'Voltar');
define('LNG_NoAccess', 'Permiss&atilde;o negada. Voc&ecirc; n&atilde;o tem acesso a esta &aacute;rea ou para executar a a&ccedil;&atilde;o solicitada. Por favor entre em contato com o administrador.');

define('LNG_ConfirmCancel', 'Tem certeza de que deseja cancelar?');

define('LNG_NoLists', 'Nenhuma lista de email est&aacute; dispon&iacute;vel. %s');
define('LNG_ListCreate', '&nbsp;Clique no bot&atilde;o "Criar Lista de Email" abaixo para criar uma.');
define('LNG_ListAssign', '&nbsp;Pora favor entre em contato com o administrador do sistema para atribuir uma lista para voc&ecirc;.');
define('LNG_CreateListButton', 'Criar Lista de Email');

define('LNG_MyAccount', 'Minha Conta');

define('LNG_Format', 'Formato');

define('LNG_Format_Text', 'Texto');
define('LNG_Format_HTML', 'HTML');
define('LNG_Format_TextAndHTML', 'HTML e Texto');
define('LNG_FilterFormat', LNG_Format);
define('LNG_HLP_FilterFormat', 'Esta op&ccedil;&atilde;o permitir&aacute; que voc&ecirc; pesquise por assinantes que assinaram um formato espec&iacute;fico. Para pesquisar por todos assinantes, deixe esta op&ccedil;&atilde;o definido como \\\'Qualquer Formato\\\'');

define('LNG_HTMLContent', 'Conte&uacute;do HTML');
define('LNG_TextContent', 'Conte&uacute;do Texto');

define('LNG_HTMLPreview', 'Visualiza&ccedil;&atilde;o HTML');
define('LNG_TextPreview', 'Visualiza&ccedil;&atilde;o Texto');

define('LNG_Step1', 'Passo 1');
define('LNG_Step2', 'Passo 2');
define('LNG_Step3', 'Passo 3');
define('LNG_Step4', 'Passo 4');

define('LNG_CustomFieldRequired_Popup', '* ');
define('LNG_PopupCloseWindow', '[ x Fechar ]');

define('LNG_View', 'Visualizar');

define('LNG_Menu_MailingLists_Manage', 'Gerenciar&nbsp;Lista&nbsp;de&nbsp;Emails');
define('LNG_Menu_MailingLists_Create', 'Criar&nbsp;Lista&nbsp;de&nbsp;Email');
define('LNG_Menu_MailingLists_CustomFields', 'Gerenciar&nbsp;Campos&nbsp;Personalizados');
define('LNG_Menu_MailingLists_Title', 'Criar, gerenciar e apagar listas de emails.');
define('LNG_Menu_MailingLists_Bounce', 'Processar Emails Retornados');

define('LNG_Menu_Members_Manage', 'Gerenciar&nbsp;Assinantes');
define('LNG_Menu_Members_Import', 'Importar&nbsp;Assinantes');
define('LNG_Menu_Members_Export', 'Exportar&nbsp;Assinantes');
define('LNG_Menu_Members_Add', 'Adicionar&nbsp;Assinante');
define('LNG_Menu_Members_Remove', 'Remover&nbsp;Assinantes');
define('LNG_Menu_Members_Banned_Manage', 'Gerenciar&nbsp;Emails&nbsp;Banidos');
define('LNG_Menu_Members_Banned_Add', 'Adicionar&nbsp;Email&nbsp;Banido');
define('LNG_Menu_Members_Title', 'Criar, gerenciar e excluir assinantes.');

define('LNG_Menu_Templates_Create', 'Criar Templates');
define('LNG_Menu_Templates_Manage', 'Gerenciar Templates');
define('LNG_Menu_Templates_Title', 'Criar, gerenciar e apagar seus templates.');
define('LNG_Menu_Templates_Manage_BuiltIn', 'Templates Predefinidos');
define('LNG_Templates_BuiltIn', LNG_Menu_Templates_Manage_BuiltIn);
define('LNG_Templates_User', 'Templates do Usu&aacute;rio');

define('LNG_Menu_Newsletters_Create', 'Criar Campanha de Email');
define('LNG_Menu_Newsletters_Manage', 'Gerenciar Campanhas de Email');
define('LNG_Menu_Newsletters_Send', 'Enviar Campanha de Email');
define('LNG_Menu_Newsletters_ManageSchedule', 'Gerenciar Agendamento de Email');
define('LNG_Menu_Newsletters_Title', 'Criar, gerenciar e apagar suas campanhas de email.');

define('LNG_Menu_Statistics_Title', 'Visualizar Estat&iacute;sticas');
define('LNG_Menu_Statistics_Newsletters', 'Estat&iacute;sticas da Campanha de Email');
define('LNG_Menu_Statistics_Users', 'Estat&iacute;sticas do Usu&aacute;rio');
define('LNG_Menu_Statistics_Lists', 'Estat&iacute;sticas da Lista de Email');
define('LNG_Menu_Statistics_Autoresponders', 'Estat&iacute;sticas das Respostas Autom&aacute;ticas');

define('LNG_Menu_Autoresponders_Title', 'Criar, gerenciar e apagar suas respostas autom&aacute;ticas.');
define('LNG_Menu_Autoresponders_Manage', 'Gerenciar Respostas Autom&aacute;ticas');
define('LNG_Menu_Autoresponders_Create', 'Criar Respostas Autom&aacute;ticas');

define('LNG_RSS', 'RSS');

define('LNG_Subscriber_Count_Many', ' (%s assinantes)');
define('LNG_Subscriber_Count_One', ' (1 assinante)');

define('LNG_Email', 'Email');
define('LNG_HLP_Email', 'Email');

define('LNG_FilterEmailAddress', LNG_Email);
define('LNG_HLP_FilterEmailAddress', 'Esta op&ccedil;&atilde;o permitir&aacute; a voc&ecirc; buscar por assinantes com um nome de dom&iacute;nio espec&iacute;fico ou parte de seus emails. Para buscar por todos os assinantes, deixe este campo vazio.');

define('LNG_ConfirmedStatus', 'Status de Confirma&ccedil;&atilde;o');
define('LNG_FilterConfirmedStatus', LNG_ConfirmedStatus);
define('LNG_HLP_FilterConfirmedStatus', 'Esta op&ccedil;&atilde;o permitir&aacute; a voc&ecirc; buscar por assinantes baseado em se eles confirmaram suas assinaturas ou n&atilde;o. Para buscar por todos os assinantes, deixe esta op&ccedil;&atilde;o selecionada em \\\'Ambos confirmados e n&atilde;o confirmados\\\'');

define('LNG_Active', 'Ativo');
define('LNG_Inactive', 'Inativo');

define('LNG_Confirmed', 'Confirmado');
define('LNG_Unconfirmed', 'N&atilde;o Confimado');

define('LNG_UnableToOpenFile', 'N&atilde;o foi poss&iacute;vel abrir o arquivo \'%s\'');
define('LNG_EmptyFile', 'O Arquivo \'%s\' est&aacute; vazio');

define('LNG_FilterSearch', 'Crit&eacute;rio de Pesquisa');
define('LNG_Subscribers_Search_Step2', 'Encontrar assinantes que combinam com os crit&eacute;rios de pesquisa abaixo. Deixe as op&ccedil;&otilde;es em branco para localizar todos os assinantes.');

define('LNG_Copyright', '&copy; 2010 SEITEC - Inform&aacute;tica e Conhecimento - Todos os direitos reservados</a>');

define('LNG_OK', 'OK');

define('LNG_Preview', 'Visualizar');
define('LNG_SelectTemplate', 'Por favor selecione um template para visualizar.');
define('LNG_ChooseTemplate', 'Template de Email');
define('LNG_HLP_ChooseTemplate', 'Choose a pre-designed email template as the basis of your email campaign. To create a new template, use the template menu at the top of the page.');

define('LNG_Preview_Template', 'Visualizar Template Selecionado');
define('LNG_Template_Preview', 'Visualiza&ccedil;&atilde;o do Template');

define('LNG_SelectAll', 'Selecionar Todos');
define('LNG_UnselectAll', 'Desmarcar Todos');

define('LNG_PleaseChooseAction', 'Por favor escolha uma acao primeiro.');
define('LNG_ConfirmSubscriberChanges', 'Tem certeza de que deseja executar a acao selecionada? Isto nao pode ser desfeito.');
define('LNG_ChangeFormat_Text', 'Mudar para Formato Texto');
define('LNG_ChangeFormat_HTML', 'Mudar para Formato HTML');
define('LNG_BulkAction', 'A&ccedil;&atilde;o em Massa');
define('LNG_ChooseAction', 'Escolha uma a&ccedil;&atilde;o');

define('LNG_ConfirmChanges', 'Tem certeza de que deseja executar a acao selecionada? Isto nao pode ser desfeito.');
define('LNG_NextButton', 'Pr&oacute;ximo &raquo;');

define('LNG_FileNotUploadedSuccessfully', 'File was not uploaded successfully. Please try again.');
define('LNG_FileNotUploadedSuccessfully_TooBig', 'File was not uploaded successfully. It may be too large to upload through your browser.');

define('LNG_None', 'Nenhum');

define('LNG_CopyPrefix', 'Copiar de '); // this is used for lists, templates and newsletters.

define('LNG_MergePrefix', 'Merge of '); // this is used for lists.

define('LNG_Bounced', 'Retornado');
define('LNG_Unsubscribed', 'Assinatura Cancelada');
define('LNG_AllStatus', 'Qualquer Status');

define('LNG_Attachments', 'Anexos');
define('LNG_HLP_Attachments', 'To add an attachment, click the browse button and select a file from your computer. Once you\\\'ve selected a file, you can then add another attachment and so on. You can upload 5 attachments at any one time.');
define('LNG_ExistingAttachments', 'Existing Attachments:');

define('LNG_UnableToCreateDirectory', 'Unable to create directory to save attachments. Please check your server permissions or contact your administrator.');

define('LNG_FileUploadSuccessful_One', 'Successfully uploaded one file.');
define('LNG_FileUploadSuccessful_Many', 'Successfully uploaded %s files.');
define('LNG_FileUploadFailure', 'Unable to upload the following files:');
define('LNG_FileExtensionNotValid', 'File extension %s is not allowed to be uploaded.');
define('LNG_NotUploadedFile', 'This file was not uploaded through the browser.');
define('LNG_FileTooBig', 'File is too big (%s). Must be less than %s');
define('LNG_UnableToUploadFile', 'Unable to upload the file.');
define('LNG_DirectoryNotWritable', 'The destination directory \'%s\' is not writable. Please check the permissions on that directory and try again or contact your administrator.');

define('LNG_HLP_DeleteAttachment', 'Check the box to delete this attachment. The attachment will be deleted after you have saved your work. This cannot be undone.');
define('LNG_DeleteAttachment', 'Delete Attachment? ');

define('LNG_FileDeleteSuccessful_One', 'Successfully deleted one file.');
define('LNG_FileDeleteSuccessful_Many', 'Successfully deleted %s files.');
define('LNG_FileDeleteFailure', 'Unable to delete the following files:');
define('LNG_FileNotFound', 'File not found');

define('LNG_ChangeStatus_Active', 'Alterar Status (Ativo)');
define('LNG_ChangeStatus_Inactive', 'Alterar Status (Inativo)');

define('LNG_ChangeStatus_Confirm', 'Alterar Status (Confirmado)');
define('LNG_ChangeStatus_Unconfirm', 'Alterar Status (N&atilde;o Confirmado)');

define('LNG_Status_Active', 'Ativo');
define('LNG_Status_Inactive', 'Inativo');
define('LNG_Status_Confirmed', 'Confirmado');
define('LNG_Status_Unconfirmed', 'N&atilde;o Confirmado');

define('LNG_FilterStatus', LNG_Status);
define('LNG_HLP_FilterStatus', 'Esta op&ccedil;&atilde;o permitir&aacute; a voc&ecirc; buscar por assinantes baseado em seus status na lista de email.<br/>Assinantes ativos s&atilde;o aqueles que n&atilde;o retornaram e n&atilde;o cancelaram suas assinaturas na lista de email.<br/>O status \\\'retornado\\\' &eacute; para aqueles que foram desabilitados da lista de email porque tiveram muitas mensagens retornadas de seus emails, ou foram detectados com muitos retornos.<br/>O status \\\'Assinatura cancelada\\\' &eacute; para aqueles que cancelaram suas assinaturas da lista de email.<br/><br/>Para buscar por todos os assinantes, Selecione a op&ccedil;&atilde;o \\\'Qualquer Status\\\'');

// used in forms.
define('LNG_MailingLists', 'Listas de Emails');

define('LNG_NoTemplate', 'Nenhum Template');

define('LNG_Global', 'Global');

// used anywhere to do with lists.
define('LNG_MailingListDetails', 'Detalhe da Lista de Email');
define('LNG_MailingList', 'Lista de Email');
define('LNG_CustomFields', 'Campo Personalizado');
define('LNG_CustomFields_Manage', 'Gerenciar Campos Personalizados');
define('LNG_HLP_MailingList', 'Para iniciar, escolha uma lista de email para trabalhar. Voc&ecirc; pode selecionar uma lista de email clicando duas vezes em uma op&ccedil;&atilde;o.');

define('LNG_SelectList', 'Por favor, selecione uma lista de email antes de continuar.');

// used for preview emails.
define('LNG_SendPreview', 'Visualizar Envio');
define('LNG_HLP_SendPreview', 'Digite seu email e clique em \\\'Visualizar Envio\\\' para receber uma c&oacute;pia deste email.<br/><br/>Se voc&ecirc; enviou novos anexos, eles n&atilde;o ser&atilde;o inclu&iacute;dos neste email de visualiza&ccedil;&atilde;o.');

define('LNG_EnterPreviewEmail', 'Por favor digite um email.');
define('LNG_NoContentToEmail', 'Nenhum conte&uacute;do foi digitado, ent&atilde;o nenhum email de visualiza&ccedil;&atilde;o foi enviado.');
define('LNG_NoEmailAddressSupplied', 'Nenhum email foi fornecido. Tente novamente.');

define('LNG_PreviewEmailSent', 'Uma visuliza&ccedil;&atilde;o foi enviada para o email %s.');
define('LNG_Preview_CustomFieldsNotAltered', '<b>Por favor observe:</b> Campos personalizados, Links de cancelamento e anexos n&atilde;o ser&atilde;o mostrados neste email de visualiza&ccedil;&atilde;o pois eles s&atilde;o espec&iacute;ficos para cada assinante.<br><br>Para testar seus emails com campos personalizados, links de cancelamento e anexos, crie uma lista de email com voc&ecirc; como assinante e envie o email para essa lista.');

define('LNG_Send', 'Enviar');
define('LNG_Resume', 'Continuar');
define('LNG_Pause', 'Pausar');

define('LNG_DefaultUnsubscribeFooter_html', '<br/><a href="%%UNSUBSCRIBELINK%%">Clique aqui para cancelar a assinatura</a>');
// need to use " so \n gets processed correctly.
define('LNG_DefaultUnsubscribeFooter_text', "\nClique neste link para cancelar assinatura: %%UNSUBSCRIBELINK%%");

define('LNG_DefaultModifyFooter_html', '<br/><a href="%%MODIFYLINK%%">Clique aqui para atualizar seus detalhes</a>');
// need to use " so \n gets processed correctly.
define('LNG_DefaultModifyFooter_text', "\nClique neste link para atualizar seus detalhes: %%MODIFYLINK%%");

define('LNG_TimeTaken_Seconds_One', '1 segundo');
define('LNG_TimeTaken_Seconds_Many', '%s segundos');

define('LNG_TimeTaken_Minutes_One', '1 minuto');
define('LNG_TimeTaken_Minutes_Many', '%s minutos');

define('LNG_TimeTaken_Hours_One', '1 hora');
define('LNG_TimeTaken_Hours_One_Minutes', '1 hora, %s minutos');
define('LNG_TimeTaken_Hours_Many', '%s horas');
define('LNG_TimeTaken_Hours_Many_Minutes', '%s horas, %s minutos');

define('LNG_TimeTaken_Days_One', '1 dia');
define('LNG_TimeTaken_Days_One_Hours', '1 dia, %s horas');
define('LNG_TimeTaken_Days_Many', '%s dias');
define('LNG_TimeTaken_Days_Many_Hours', '%s dias, %s horas');

define('LNG_TimeTaken_Months_One', '1 m&ecirc;s');
define('LNG_TimeTaken_Months_One_Days', '1 m&ecirc;s, %s dias');
define('LNG_TimeTaken_Months_Many', '%s meses');
define('LNG_TimeTaken_Months_Many_Days', '%s meses, %s dias');

define('LNG_TimeTaken_Years_One', '1 ano');
define('LNG_TimeTaken_Years_Many', '%s anos');

define('LNG_CronNotEnabled', 'O envio pelo Cron n&atilde;o foi habilitado. Por favor fale para o administrador para configurar isto.');

define('LNG_CronNotSetup', 'Voc&ecric; habilitou o suporte ao cron, mas o sistema ainda n&atilde;o detectou uma tarefa sendo executada com sucesso. <a href="resources/tutorials/cron_intro.html" target="_blank">Por favor certifique-se de que o cron foi configurado corretamente em seu servidor e est&aacute; sendo executado corretamente</a>. Esta mensagem ir&aacute; desaparecer uma vez que o sistema detectar que as tarefas do cron est&atilde;o sendo executadas com sucesso.');

define('LNG_Custom', 'Personalizado');

define('LNG_ShowCustomFields', 'Inserir Campos Personalizados');
define('LNG_InsertUnsubscribeLink', 'Inserir Link de Cancelamento');

define('LNG_Approve', 'Aprovar');
define('LNG_Approved', 'Aprovado');
define('LNG_Disapprove', 'Desaprovar');
define('LNG_Disapproved', 'Desaprovado');

define('LNG_NewsletterSubject', 'Assunto do Email');
define('LNG_Subject', 'Assunto');
define('LNG_Name', 'Nome');

define('LNG_YesFilterByCustomDate', 'Sim, filtrar pelo campo \'%s\'');

define('LNG_AlreadySentTo_Heading', 'Informa&ccedil;&otilde;es do &Uacute;ltimo Envio');
define('LNG_AlreadySentTo_SoFar', 'Enviado para %s / %s at&eacute; agora');

// used by "manage schedule" page.
define('LNG_AlreadySentTo', ' (Enviado para %s / %s)');

define('LNG_ShowFilteringOptions', 'Mostrar op&ccedil;&otilde;es de filtragem');
define('LNG_ShowFilteringOptionsExplain', 'Sim, mostrar op&ccedil;&otilde;es de filtragem na pr&oacute;xima p&aacute;gina');
define('LNG_HLP_ShowFilteringOptions', 'Marque esta op&ccedil;&atilde;o para mostrar op&ccedil;&otilde;es de filtragem na pr&oacute;xima tela. Op&ccedil;&otilde;es de filtragem permite a voc&ecirc; localizar assinantes que atendem aos crit&eacute;rios da busca como: email ou nome de dom&iacute;nio. Voc&ecirc; pode tamb&eacute;m filtrar baseado em campos personalizados.');

/**
* Common custom field stuff.
* This is used by searching, exporting.
*/
define('LNG_FilterByDate', 'Filtrar por Data');
define('LNG_YesFilterByDate', 'Sim, filtrar por data de assinatura');
define('LNG_After', 'Depois');
define('LNG_Before', 'Antes');
define('LNG_Between', 'Entre');
define('LNG_Exactly', 'Exatamente');
define('LNG_AND', 'E');
define('LNG_HLP_FilterByDate', 'Esta op&ccedil;&atilde;o permitir&aacute; a voc&ecirc; filtrar assinantes que assinaram antes, depois ou entre datas espec&iacute;ficas. Para buscar por todos os assinantes, deixe esta op&ccedil;&atilde;o desmarcada.');


define('LNG_ExportFileDeleted', 'O arquivo exportado foi exclu&iacute;do com sucesso.');
define('LNG_ExportFileNotDeleted', 'O arquivo exportado n&atilde;o pode ser exclu&iacute;do com sucesso. Por favor tente novamente.');

/**
* Jobs
*/
define('LNG_Waiting', 'Aguardando');
define('LNG_Job_Waiting', 'Enviando em');
define('LNG_Job_Complete', 'Completo');
define('LNG_Job_InProgress', 'Em Progresso');
define('LNG_Job_Paused', 'Pausado');
define('LNG_WaitingToSend', 'Aguardando para enviar'); // this is used if 2 cron jobs have not run yet, so we can't work out the time difference.
define('LNG_ImapSupportMissing', 'Suporte a IMAP n&atilde;o est&aacute; dispon&iacute;vel. Emails retornados n&atilde;o podem ser processados sem suporte a IMAP.');


define('LNG_AnyList', '-- Todas as Listas --');

/**
* Subscriber stuff.
*/
define('LNG_UserChooseFormat', 'Escolha o Formato');
define('LNG_Unknown', 'Desconhecido');
define('LNG_SubscribeRequestDate', 'Data do Pedido de Assinatura');
define('LNG_HLP_SubscribeRequestDate', 'A data e a hora em que este assinante solicitou a entrada para esta lista de email.');
define('LNG_SubscribeRequestIP', 'IP do Pedido de Assinatura');
define('LNG_HLP_SubscribeRequestIP', 'O IP do computador que o assinante solicitou a entrada para esta lista de email.');
define('LNG_SubscribeConfirmDate', 'Data de Confirma&ccedil;&atilde;o da Assinatura');
define('LNG_HLP_SubscribeConfirmDate', 'A data e a hora em que este assinante confirmou seu pedido de entrada para esta lista de email.');
define('LNG_SubscribeConfirmIP', 'IP de Confirma&ccedil;&atilde;o do Assinante');
define('LNG_HLP_SubscribeConfirmIP', 'O endere&ccedil;o IP do computador que este assinante confirmou sua entrada para esta lista de email.');

define('LNG_Subscriber_NotSubscribed', 'O email \'%s\' n&atilde;o est&aacute; assinando esta lista');

define('LNG_NoSubscribersOnList', 'N&atilde;o h&aacute; nenhum assinante na(s) lista(s) que voc&ecirc; selecionou.');
define('LNG_NoSubscribersMatch', 'Nenhum assinante corresponde aos seus crit&eacute;rios de busca. Por favor, tente novamente.');

define('LNG_ViewSchedule', 'View Sending Schedule');

/**
* Handles importing / uploading of a template / newsletter / autoresponder.
*/
define('LNG_UploadedFileEmpty', 'Arquivo enviado est&aacute; vazio. Tente novamente.');
define('LNG_UploadedFileBad', 'N&atilde;o foi poss&iacute;vel enviar o arquivo. Tente novamente.');
define('LNG_UploadFileTooBig', 'N&atilde;o foi poss&iacute;vel enviar o arquivo. Ele &eacute; muito grande. Tente enviar um arquivo menor.');

define('LNG_UploadedFileCantBeRead', 'N&atilde;o foi poss&iacute;vel ler o arquivo enviado. Tente novamente.');
define('LNG_URLIsEmpty', 'URL est&aacute; vazia. Tente novamente.');
define('LNG_URLCantBeRead', 'N&atilde;o foi poss&iacute;vel obter url. Certifique-se de que ela seja v&aacute;lida e tente novamente.');
define('LNG_NoCurlOrFopen', 'Infelizmente seu servidor n&atilde;o pode abrir arquivos remotos.<br/>Fale para o administrador para que ele habilite o suporte a "curl" ou "remote fopen".');

/**
* Used for the settings page and the users page.
*/
define('LNG_UseSMTP', 'Usar Servidor SMTP');
define('LNG_UseSMTPExplain', 'Sim, usar um servidor SMTP');
define('LNG_HLP_UseSMTP', 'Check this option to specify an external smtp server. If unchecked, the default php mail system will be used to send emails.');

define('LNG_SmtpServer', 'Servidor SMTP');
define('LNG_HLP_SmtpServer', 'Choose to use the default SMTP server specified from the settings page, or choose to use a custom SMTP server for this client.');
define('LNG_SmtpDefault', 'Use default SMTP server');
define('LNG_SmtpCustom', 'Specify a custom SMTP server');
define('LNG_SmtpServerIntro', 'Mail Server Details');
define('LNG_SmtpServerName', 'SMTP Hostname');
define('LNG_HLP_SmtpServerName', 'Enter the SMTP host name here, such as &quot;192.168.0.50&quot; or &quot;mail.mysite.com&quot;');
define('LNG_SmtpServerUsername', 'SMTP Username');
define('LNG_HLP_SmtpServerUsername', 'If your SMTP server requires authentication, enter the username here.');
define('LNG_SmtpServerPassword', 'SMTP Password');
define('LNG_HLP_SmtpServerPassword', 'If your SMTP server requires authentication, enter the password here.');
define('LNG_SmtpServerPort', 'SMTP Port');
define('LNG_HLP_SmtpServerPort', 'To use a non-standard SMTP port (the default is 25), enter it here.');

define('LNG_OverLimit_MaxEmails', 'You have gone over the number of emails you are allowed to send.<br/>Please restrict who you are sending to, or speak to your administrator about increasing this limit.');
define('LNG_OverLimit_PerMonth', 'You have gone over the number of emails you are allowed to send.<br/>Please restrict who you are sending to, or speak to your administrator about increasing this limit.');

/**
* Used by the email class and testsmtp scripts.
*/
define('LNG_UnableToConnectToEmailServer', 'Unable to connect to mail server: %s');
define('LNG_UnableToSendEmail_MailFrom', 'Unable to set mail from address.');
define('LNG_UnableToSendEmail_RcptTo', 'Unable to set receipt to address.');
define('LNG_UnableToSendEmail_Data', 'Unable to set data.');
define('LNG_UnableToSendEmail_DataWriting', 'Unable to send data.');
define('LNG_UnableToSendEmail_DataFinished', 'Unable to send "." to server.');
define('LNG_UnableToConnectToMailServer_EHLO', 'Unable to send "EHLO" command.');
define('LNG_UnableToConnectToMailServer_RequiresAuthentication', 'Server requires authentication but no username or password has been set.');
define('LNG_UnableToConnectToMailServer_AuthLogin', 'Unable to send "auth login" to server.');
define('LNG_UnableToConnectToMailServer_AuthLoginNotSupported', 'Unable to authenticate with server. Doesn\'t support "AUTH LOGIN"');
define('LNG_UnableToConnectToMailServer_UsernameNotWritten', 'Unable to authenticate with server. Username not written.');
define('LNG_UnableToConnectToMailServer_PasswordNotWritten', 'Unable to authenticate with server. Password not written.');

/**
* Used with newsletters, templates, autoresponders, form creation
*/
define('LNG_ImportWebsite', 'Importar');

define('LNG_HTML_Using_Editor', 'Criar conte&uacute;do usando o editor WYSIWYG abaixo');
define('LNG_Editor_Upload_File', 'Enviar um arquivo do meu computador');
define('LNG_Editor_Import_Website', 'Importar um arquivo de um web site');
define('LNG_Editor_Use_URL', 'Usar uma URL existente');
define('LNG_Text_Type', 'Digite o texto dentro do campo abaixo');
define('LNG_Editor_Import_File_Wait', 'Importando arquivo, por favor aguarde...');
define('LNG_Editor_Import_Website_Wait', 'Importando website, por favor aguarde...');
define('LNG_Editor_ProblemImportingWebsite', 'Houve um problema na importa&ccedil;&atilde;o da url especificada. Tente novamente.');
define('LNG_Editor_ChooseFileToUpload', 'Por favor escolha uma arquivo para enviar');
define('LNG_Editor_ChooseWebsiteToImport', 'Por favor digite uma url completa do website que voc&ecirc; quer importar.');
define('LNG_Editor_ImportButton', 'Importar');

define('LNG_SendNewsletterButton', 'Enviar Campanha de Email');

/**
* Used for sending and autoresponders
*/
define('LNG_EmbedImages', 'Incorporar Imagens');
define('LNG_HLP_EmbedImages', 'Isto ir&aacute; incorporar as imagens do conte&uacute;do dentro do email que seus assinantes receberem. Isto pode tornar o email significativamente maior mas ir&aacute; permitir que seus assinantes vejam o conte&uacute;do offline.');
define('LNG_EmbedImagesExplain', 'Sim, incorporar as imagens no conte&uacute;do');

define('LNG_SendTo_FirstName', 'Nome do Destinat&aacute;rio');
define('LNG_HLP_SendTo_FirstName', 'Se voc&ecirc; tem um campo personalizado para o \\\'nome\\\' de seus assinantes, escolha-o aqui e a newsletter pode ser endere&ccedil;ada &agrave; pessoa individualmente.<br/>Se voc&ecirc; tem um campo personalizado para o nome das pessoas (que &eacute;, apenas um chamado \\\'nome\\\') ent&atilde;o escolha o campo personalizado aqui.');

define('LNG_SendTo_LastName', 'Sobrenome do Destinat&aacute;rio');
define('LNG_HLP_SendTo_LastName', 'Se voc&ecirc; tem um campo personalizado para o \\\'sobrenome\\\' de seus assinantes, escolha-o aqui e a newsletter pode ser endere&ccedil;ada &agrave; pessoa individualmente.<br/>Se voc&ecirc; tem um campo personalizado para o nome das pessoas (que &eacute;, apenas um chamado \\\'nome\\\') ent&atilde;o deixe esta op&ccedil;&atilde;o em branco.');

define('LNG_SelectNameOption', 'Selecione o campo personalizado para: "nome"');

// used all over the place with newsletters
define('LNG_CreateNewsletterButton', 'Criar Campanha de Email');
define('LNG_NoNewsletters', 'Nenhuma campanha de email foi criada.%s');
define('LNG_NoNewsletters_HasAccess', ' Por favor clique no bot&atilde;o "Criar Campanha de Email" para criar uma.');

// used by autoresponders & stats
define('LNG_SentWhen', 'Sent');
define('LNG_Immediately', 'Immediately after signup');
define('LNG_HoursAfter_One', '1 hour after signup');
define('LNG_HoursAfter_Many', '%s hours after signup');

define('LNG_ClickedOnLink', 'Links Clicados');
define('LNG_YesFilterByLink', 'Sim, filtrar por link');
define('LNG_LoadingMessage', 'Carregando, por favor aguarde...');
define('LNG_FilterAnyLink', 'Qualquer Link');
define('LNG_HLP_ClickedOnLink', 'Esta op&ccedil&atilde;o permitir&aacute; a voc&ecirc; filtrar assinantes que clicaram em links espec&iacute;ficos de uma campanha de email ou resposta autom&aacute;tica enviada para esta lista de emails. Para buscar por todos os assinantes, deixe esta op&ccedil;&atilde;o desmarcada.');

define('LNG_OpenedNewsletter', 'Campanha de Email Aberta');
define('LNG_YesFilterByOpenedNewsletter', 'Sim, filtrar por campanha de email aberta');
define('LNG_FilterAnyNewsletter', 'Qualquer Campanha de Email');
define('LNG_HLP_OpenedNewsletter', 'Esta op&ccedil&atilde;o permitir&aacute; a voc&ecirc; filtrar assinantes que abriram uma campanha de email espec&iacute;fica ou resposta autom&aacute; enviada para esta lista de emails. Para buscar por todos os assinantes, deixe esta op&ccedil;&atilde;o desmarcada.');

define('LNG_UnableToOpenPopupWindow', 'Error: Could not open required web browser window. Please check that you have disabled your popup blocker and you don\'t have Norton internet security, ZoneAlarm or any other \'security\' script that could be blocking the web browser window from opening and then try again.');


define('LNG_NoUnsubscribeLinkInHTMLContent', 'No unsubscribe link was found in the html version of your email. It is recommended you add one so subscribers can easily remove themselves from your mailing list.');

define('LNG_NoUnsubscribeLinkInTextContent', 'No unsubscribe link was found in the text version of your email. It is recommended you add one so subscribers can easily remove themselves from your mailing list.');


/**
* used by sending and forms.
*/
define('LNG_SendFromName', 'Nome do Remetente');
define('LNG_HLP_SendFromName', 'Qual pessoa ou institui&ccedil;&atilde;o deve aparecer no campo \\\'Nome do Remetente\\\' para este email?');

define('LNG_SendFromEmail', 'Email do Remetente');
define('LNG_HLP_SendFromEmail', 'Qual email deve aparecer no campo \\\'Email do Remetente\\\' para este email?');

define('LNG_ReplyToEmail', 'Email p/ Resposta');
define('LNG_HLP_ReplyToEmail', 'Quando um assinante receber seus email e clicar em "Responder", para qual email deve ser enviado?');

define('LNG_BounceEmail', 'Email p/ Retorno');
define('LNG_HLP_BounceEmail', 'Quando um email voltar, ou for rejeitado pelo servidor, para qual endere&ccedil;o esse erro deve ser enviado? Se voc&ecirc; planeja usar um manipulador para esses erros, ent&atilde;o certifique-se de que nenhum outro email seja enviado para este endere&ccedil;o.');

/**
* Searching custom fields.
*/
define('LNG_Filter_Number', 'Filtrar Campo Num&eacute;rico');
define('LNG_HLP_Filter_Number', 'Para restringir o filtro deste campo, voc&ecirc; pode usar >, = e <. Por exemplo, para buscar por assinantes que tenham menos de 25 anos, digite < 25.');

define('LNG_Filter_Checkbox', 'Filtrar Campo Checkbox');
define('LNG_HLP_Filter_Checkbox', 'Para restringir o filtro deste campo, marque as op&ccedil;&otilde;es que voc&ecirc; quiser pesquisar.');

define('LNG_Filter_Date', 'Filtrar Campos Data');

define('LNG_Filter_Dropdown', 'Filtrar Campo Dropdown');
define('LNG_HLP_Filter_Dropdown', 'Para restringir o filtro deste campo, escolha uma op&ccedil;&atilde;o que voc&ecirc; quiser pesquisar.');

define('LNG_Filter_Radiobutton', 'Filtrar Campo Radio Button');
define('LNG_HLP_Filter_Radiobutton', 'Para restringir o filtro deste campo, escolha uma op&ccedil;&atilde;o que voc&ecirc; quiser pesquisar.');

define('LNG_Filter_Text', 'Filtrar Campo Texto');
define('LNG_HLP_Filter_Text', 'Para restringir o filtro deste campo, digite algum texto que dever&aacute; aparecer. Isto ser&aacute; encontrado em qualquer texto, n&atilde;o apenas palavras exatas.');

define('LNG_Link_MailingListArchives', 'Link to Mailing List Archives');
define('LNG_Link_WebVersion', 'Web Version of Email');
define('LNG_Link_Unsubscribe', 'Unsubscribe Link');


define('LNG_SendingSystem', 'SEITEC');
define('LNG_SendingSystem_From', 'email@domain.com');
define('LNG_UserLimitReached', 'Voc&ecirc; alcan&ccedil;ou o n&uacute;mero m&aacute;ximo de usu&aacute;rios e n&atilde;o poder&aacute; mais criar nenhum.');

define('LNG_User_OverQuota_Email', 'Hi,
This email is to notify you that user \'%s\' (email address %s) has scheduled a newsletter to go out on %s. They have gone over their %s limit by %s email(s).

%s

You can email them by clicking "Reply" in your email program.
');
define('LNG_User_OverLimit_MaxEmails', 'maximum number of emails');
define('LNG_User_OverLimit_PerMonth', 'maximum number of emails per month');
define('LNG_User_OverQuota_StoppedSend', 'The send has been disapproved.');
define('LNG_User_OverQuota_Subject', 'User over-quota notification');

define('LNG_User_OverQuota_ToUser_Email', 'Hi,
This email is to notify you that your send, scheduled to go out on %s has gone over your %s limit by %s email(s).

%s

You can email the administrator by clicking "Reply" in your email program.
');

define('LNG_User_OverQuota_ToUser_Subject', 'Over-quota notification');

define('LNG_RunningVersion', 'You are running version ');

/**
**************************
* Changed/Added in NX1.0.5
**************************
*/

/**
* these MUST be space separated.
* This is only used to display the date/time options when sending a newsletter
* and has to be in the correct format and only contain the 3 options mentioned:
* - 'd' for day
* - 'm' for month
* - 'y' for year
* The order does not matter but the values do.
*/
define('LNG_DateTimeBoxFormat', 'd m y');

/**
**************************
* Changed/Added in NX1.0.7
**************************
*/
define('LNG_HLP_Filter_Date', 'Choose the date or date range to filter subscribers.<br/>To search between months, put * as the year.<br/>This only works when searching between dates.');

define('LNG_User_Total_CreditsLeft', '<br/>You have %s email credits left in total');
define('LNG_User_Monthly_CreditsLeft', '<br/>You have %s email credits left in %s');


/**
**************************
* Changed/Added in NX1.1.3
**************************
*/
define('LNG_PreviewEmailNotSent', 'A preview couldn\'t be sent to the email address %s: %s');

?>
