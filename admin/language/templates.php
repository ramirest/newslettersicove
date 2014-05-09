<?php
/**
* Language file variables for the templates area.
*
* @see GetLang
*
* @version     $Id: templates.php,v 1.23 2007/05/28 07:04:32 scott Exp $

*
* @package SendStudio
* @subpackage Language
*/

/**
* Here are all of the variables for the template area... Please backup before you start!
*/
define('LNG_CreateTemplate', 'Criar Template');
define('LNG_CreateTemplateButton', 'Criar Template');
define('LNG_CreateTemplateIntro', 'Complete o formul&aacute;rio abaixo para criar um novo template.');
define('LNG_CreateTemplateCancelButton', 'Tem certeza de que deseja cancelar a cria&ccedil;&atilde;o de um novo template?');
define('LNG_CreateTemplateHeading', 'Detalhes do Novo Template');

define('LNG_CreateTemplateIntro_Step2', 'Complete o formul&aacute;rio abaixo para criar um template. Quando terminar, clique no bot&atilde;o \'Salvar\'.');

define('LNG_EditTemplate', 'Atualizar Template');
define('LNG_EditTemplateIntro', 'Complete o formul&aacute;rio abaixo para atualizar o template.');
define('LNG_EditTemplateCancelButton', 'Tem certeza de que deseja cancelar a edi&ccedil;&atilde;o deste template?');
define('LNG_EditTemplateHeading', 'Detalhes do Template');

define('LNG_EditTemplateIntro_Step2', 'Por favor atualize seu conte&uacute;do abaixo. Clique em "Salvar" quando terminar.');

define('LNG_TemplatesManage', 'Gerenciar Templates');
define('LNG_Help_TemplatesManage', 'Use o formul&aacute;rio abaixo para gerenciar seus templates.');
define('LNG_Help_TemplatesManage_Create', ' Para criar um novo template, clique no bot&atilde;o "Criar Template" abaixo.');

define('LNG_NoTemplatesBuiltIn', 'N&atilde;o existem templates predefinidos dispon&iacute;veis. Por favor entre em contato com o administrador.');
define('LNG_TemplatesManageBuiltIn', 'Templates Predefinidos');
define('LNG_Help_TemplatesManageBuiltIn', 'Estes templates s&atilde;o predefinidos e pode sem encontrados no diret&oacute;rio "resources/email_templates" no servidor para edi&ccedil;&atilde;o. Eles foram testados nos seguintes clientes de email: Gmail, Yahoo, Hotmail, Outlook e Thunderbird.');

define('LNG_EnterTemplateName', 'Por favor digite um nome para este template');

define('LNG_TemplateName', 'Nome');

define('LNG_TemplateNameIsNotValid', 'Nome do Template N&atilde;o &eacute; V&aacute;lido');
define('LNG_UnableToCreateTemplate', 'Ocorreu um erro na tentativa de criar seu novo template.');
define('LNG_TemplateCreated', 'Seu novo template foi criado com sucesso');

define('LNG_DeleteTemplatePrompt', 'Tem certeza de que deseja excluir este template?');

define('LNG_HLP_TemplateName', 'Escolha um nome para este template. Este nome ser&aacute; visto apenas no painel de controle e n&atilde;o ser&aacute; usado em nenhum de seus emails.');

define('LNG_UnableToUpdateTemplate', 'N&atilde;o foi poss&iacute;vel atualizar o template');
define('LNG_TemplateUpdated', 'Template atualizado com sucesso');

define('LNG_TemplateDeleteFail', 'N&atilde;o foi poss&iacute;vel excluir o template');
define('LNG_TemplateDeleteSuccess', 'Template exclu&iacute;do com sucesso');
define('LNG_NoTemplates', 'Nenhum template est&aacute; dispon&iacute;vel.%s');
define('LNG_NoTemplates_HasAccess', ' Clique no bot&atilde;o "Criar Template" abaixo para criar um.');

define('LNG_TemplateFormat', 'Formato do Template');
define('LNG_HLP_TemplateFormat', 'Em quais formatos voc&ecirc; gostaria de criar este template? Escolha HTML, texto, ou texto e HTML.');
define('LNG_TemplateContent', 'Digite o conte&uacute;do do seu template abaixo');

define('LNG_TemplateCopySuccess', 'Template foi copiado com sucesso.');
define('LNG_TemplateCopyFail', 'Template n&atilde;o foi copiado.');

define('LNG_Template_Title_Enable', 'Habilitar Template');
define('LNG_Template_Title_Disable', 'Desabilitar Template');

define('LNG_Template_Title_Global_Enable', 'Habilitar Template Global');
define('LNG_Template_Title_Global_Disable', 'Desabilitar Template Global');

define('LNG_Template_DeactivatedSuccessfully', 'Template desativado com sucesso.');
define('LNG_Template_ActivatedSuccessfully', 'Template ativado com sucesso.');

define('LNG_Template_Global_DeactivatedSuccessfully', 'Template desativado com sucesso.');
define('LNG_Template_Global_ActivatedSuccessfully', 'Template ativado com sucesso.');

define('LNG_TemplateCannotBeInactiveAndGlobal', 'Um template n&atilde;o pode ficar inativo e global ao mesmo tempo.');

define('LNG_TemplateIsActive', 'Ativar Template');
define('LNG_TemplateIsActiveExplain', 'Sim, este template est&aacute; ativo');
define('LNG_HLP_TemplateIsActive', 'Este template deve ser marcado como ativo? Templates inativos n&atilde;o podem ser usados para criar campanhas de email ou respostas autom&aacute;ticas.');

define('LNG_TemplateIsGlobal', 'Template Global');
define('LNG_TemplateIsGlobalExplain', 'Sim, este template &eacute; global');
define('LNG_HLP_TemplateIsGlobal', 'Este template deve ser marcado como global? Caso seja, todos os usu&aacute;rios ser&atilde;o capazes de visualiz&aacute;-lo quando estiverem criando uma campanha de email ou resposta autom&aacute;tica.');

define('LNG_UnableToLoadTemplate', 'N&atilde;o foi poss&iacute;vel carregar o template. Por favor tente novamente.');
define('LNG_UnableToLoadTemplateFromServer', 'N&atilde;o foi poss&iacute;vel localizar o template no servidor. Por favor tente novamente.');

define('LNG_TemplateFile', 'Template File');
define('LNG_HLP_TemplateFile', 'Upload a html file from your computer to use as your template');
define('LNG_UploadTemplate', 'Upload');
define('LNG_TemplateFileEmptyAlert', 'Please choose a file from your computer before trying to upload it.');
define('LNG_TemplateFileEmpty', 'Please choose a file from your computer before trying to upload it.');

define('LNG_TemplateURL', 'Template URL');
define('LNG_HLP_TemplateURL', 'Import a template from a url');
define('LNG_ImportTemplate', 'Import');
define('LNG_TemplateURLEmptyAlert', 'Please enter a url to import the template from');
define('LNG_TemplateURLEmpty', 'Please enter a url to import the template from');

define('LNG_Template_Edit_Disabled', 'You do not have access to edit this template');
define('LNG_Template_Delete_Disabled', 'You do not have access to delete this template');
define('LNG_Template_Copy_Disabled', 'You do not have access to copy this template');

define('LNG_ChooseTemplates', 'Please choose one or more templates first.');
define('LNG_ApproveTemplates', 'Activate');
define('LNG_DisapproveTemplates', 'Deactivate');
define('LNG_GlobalTemplates', 'Make Global');
define('LNG_DisableGlobalTemplates', 'Remove From Global');

define('LNG_NoTemplatesSelected', 'No templates have been selected. Please try again.');

define('LNG_Template_NotDeleted', '1 template n&atilde;o exclu&iacute;do. Por favor tente novamente.');
define('LNG_Templates_NotDeleted', '%s templates n&atilde;o exclu&iacute;dos. Por favor tente novamente.');
define('LNG_Template_Deleted', '1 template exclu&iacute;do com sucesso.');
define('LNG_Templates_Deleted', '%s templates exclu&iacute;dos com sucesso.');

define('LNG_Template_NotApproved', 'Unable to approve the selected template');
define('LNG_Templates_NotApproved', 'Unable to approve the %s selected templates');
define('LNG_Template_Approved', '1 template has been activated successfully');
define('LNG_Templates_Approved', '%s templates have been activated successfully');

define('LNG_Template_NotDispproved', 'Unable to disapprove the selected template');
define('LNG_Templates_NotDispproved', 'Unable to disapprove the %s selected templates');
define('LNG_Template_Disapproved', '1 template has been deactivated successfully');
define('LNG_Templates_Disapproved', '%s templates have been deactivated successfully');

define('LNG_Template_NotGlobal', 'Unable to globalise the selected template');
define('LNG_Templates_NotGlobal', 'Unable to globalise the %s selected templates');
define('LNG_Template_Global', '1 template has been globalised successfully');
define('LNG_Templates_Global', '%s templates have been globalised successfully');

define('LNG_Template_NotDisableGlobal', 'Unable to stop sharing the selected template');
define('LNG_Templates_NotDisableGlobal', 'Unable to stop sharing the %s selected templates');
define('LNG_Template_DisableGlobal', '1 template has been unshared successfully');
define('LNG_Templates_DisableGlobal', '%s templates have been unshared successfully');

define('LNG_CannotDeleteGlobalTemplate_NoAccess', 'You cannot delete template \'%s\' because you do not have access.');

define('LNG_ActivateTemplates', LNG_ApproveTemplates);
define('LNG_InactivateTemplates', LNG_DisapproveTemplates);

define('LNG_InvalidTemplateAction', 'Voc&ecirc; tentou executar uma opera&ccedil;&atilde;o desconhecida. Tente novamente.');

define('LNG_CreateTemplatePreview', '<br/>Para criar uma visualiza&ccedil;&atilde;o para estes templates crie uma imagem gif tamanho 247 x 200px e coloque no diret&oacute;rio admin/resources/user_template_previews. Voc&ecirc; precisar&aacute; cham&aacute;-la de ID_preview.gif. Ex. 4_preview.gif.');

define('LNG_DeleteTemplatePreview_Image', 'Voc&ecirc; precisar&aacute; excluir manualmente o arquivo admin/resources/user_template_previews/%s_preview.gif.');

define('LNG_TemplateID', '<span style="color: #A1A1A1">&nbsp;- id: %s</span>');

/**
**************************
* Changed/added in NX1.0.7
**************************
*/
define('LNG_BuiltInTemplate_Preview_TemplatePack', '%s (%s)');
define('LNG_BuiltInTemplate_Preview_Template', '%s');

/**
**************************
* Changed/added in NX1.1.1
**************************
*/
define('LNG_TemplateFilesCopyFail', 'The images and/or attachments for this template were not copied successfully.');
?>
