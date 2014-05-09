<?php
/**
* Language file variables for the newsletters area. (Now referred to as email campaigns)
*
* @see GetLang
*
* @version     $Id: newsletters.php,v 1.23 2007/05/28 06:57:15 scott Exp $

*
* @package SendStudio
* @subpackage Language
*/

/**
* Here are all of the variables for the newsletters area... Please backup before you start!
*/
define('LNG_CreateNewsletter', 'Criar Campanha de Email');
define('LNG_CreateNewsletterIntro', 'Digite os detalhes da campanha de email no formul&aacute;rio abaixo.');
define('LNG_CreateNewsletterCancelButton', 'Tem certeza de que deseja cancelar a cria&ccedil;&atilde;o desta campanha de email?');
define('LNG_CreateNewsletterHeading', 'Detalhes da Campanha de Email');

define('LNG_CreateNewsletterIntro_Step2', 'Complete o formul&aacute;rio abaixo para criar uma campanha de email. Quando terminar, clique no bot&atilde;o \'Salvar\'.');
define('LNG_Newsletter_Details', 'Detalhes da Campanha de Email');

define('LNG_EditNewsletter', 'Atualizar Campanha de Email');
define('LNG_EditNewsletterIntro', 'Complete o formul&aacute;rio abaixo para atualizar a campanha de email.');
define('LNG_EditNewsletterCancelButton', 'Tem certeza de que deseja cancelar a atualiza&ccedil;&atilde;o desta campanha de email?');
define('LNG_EditNewsletterHeading', 'Detalhes da Campanha de Email');

define('LNG_EditNewsletterIntro_Step2', 'Por favor, atualize seu conte&uacute;do abaixo. Clique em "Salvar" quando terminar.');

define('LNG_NewslettersManage', 'Gerenciar Campanhas de Email');
define('LNG_Help_NewslettersManage', 'Use o formul&aacute;rio abaixo para rever, editar e apagar campanhas de email.');
define('LNG_Help_NewslettersManage_HasAccess', ' Para criar uma campanha de email, clique no bot&atilde;o "Criar Campanha de Email" abaixo.');
define('LNG_EnterNewsletterName', 'Por favor, digite um nome para sua campanha de email');

define('LNG_NewsletterName', 'Nome da Campanha de Email');

define('LNG_NewsletterNameIsNotValid', 'Nome da Campanha de Email n&atilde;o &eacute; v&aacute;lido');
define('LNG_UnableToCreateNewsletter', 'N&atilde;o foi poss&iacute;vel crar uma campanha de email');
define('LNG_NewsletterCreated', 'A campanha de email foi salva com sucesso');

define('LNG_HLP_NewsletterName', 'O nome da campanha de email. Apenas para sua refer&ecirc;ncia. N&atilde;o ser&aacute; inclu&iacute;do no email quando voc&ecirc; envi&aacute;-lo.');

define('LNG_UnableToUpdateNewsletter', 'N&atilde;o foi poss&iacute;vel atualizar a campanha de email');
define('LNG_NewsletterUpdated', 'Campanha de Email atualizada com sucesso');

define('LNG_NoNewslettersToDelete', 'Nenhuma campanha de email foi selecionada. Tente novamente.');
define('LNG_Newsletter_NotDeleted', 'N&atilde;o foi poss&iacute;vel excluir a campanha de email selecionada');
define('LNG_Newsletters_NotDeleted', 'N&atilde;o foi poss&iacute;vel excluir as %s campanhas de email selecionadas');
define('LNG_Newsletter_Deleted', '1 campanha de email foi exclu&iacute;da com sucesso');
define('LNG_Newsletters_Deleted', '%s campanhas de email foram exclu&iacute;das com sucesso');

define('LNG_Newsletter_NotDeleted_SendInProgress', 'N&atilde;o foi poss&iacute;vel excluir a campanha de email \'%s\' - ela est&aacute; sendo enviada neste momento.');
define('LNG_Newsletters_NotDeleted_SendInProgress', 'N&atilde;o foi poss&iacute;vel excluir as seguintes campanhas de email - \'%s\' - elas est&atilde;o sendo enviadas neste momento.');

define('LNG_NoNewslettersToAction', LNG_NoNewslettersToDelete);
define('LNG_InvalidNewsletterAction', 'A&ccedil;&tilde;o inv&aacute;lida da campanha de email. Tente novamente.');

define('LNG_Newsletter_NotApproved', 'N&atilde;o foi poss&iacute;vel aprovar a campanha de email selecionada');
define('LNG_Newsletters_NotApproved', 'N&atilde;o foi poss&iacute;vel aprovar as %s campanhas de email selecionadas');
define('LNG_Newsletter_Approved', '1 campanha de email foi ativada com sucesso');
define('LNG_Newsletters_Approved', '%s campanhas de email foram ativadas com sucesso');

define('LNG_Newsletter_NotDisapproved', 'N&atilde;o foi poss&iacute;vel desativar a campanha de email selecionada');
define('LNG_Newsletters_NotDisapproved', 'N&atilde;o foi poss&iacute;vel desativar as %s campanhas de email selecionadas');
define('LNG_Newsletter_Disapproved', '1 campanha de email foi desativada com sucesso');
define('LNG_Newsletters_Disapproved', '%s campanhas de email foram desativadas com sucesso');

define('LNG_Newsletter_NotArchived', 'N&atilde;o foi poss&iacute;vel arquivar a campanha de email selecionada');
define('LNG_Newsletters_NotArchived', 'N&atilde;o foi poss&iacute;vel arquivar as %s campanhas de email selecionadas');
define('LNG_Newsletter_Archived', '1 campanha de email foi arquivada com sucesso');
define('LNG_Newsletters_Archived', '%s campanhas de email foram arquivadas com sucesso');

define('LNG_Newsletter_NotUnarchived', 'N&atilde;o foi poss&iacute;vel desarquivar a campanha de email selecionada');
define('LNG_Newsletters_NotUnarchived', 'N&atilde;o foi poss&iacute;vel desarquivar as %s campanhas de email selecionadas');
define('LNG_Newsletter_Unarchived', '1 campanha de email foi desarquivada com sucesso');
define('LNG_Newsletters_Unarchived', '%s campanhas de email foram desarquivadas com sucesso');

define('LNG_NewsletterFormat', 'Formato da Campanha de Email');
define('LNG_NewsletterContent', 'Digite o conte&uacute;do da sua campanha de email abaixo');

define('LNG_NewsletterCopySuccess', 'A campanha de email foi copiada.');
define('LNG_NewsletterCopyFail', 'A campanha de email n&atilde;o pode ser copiada.');

// newslettersubject is in language.php
define('LNG_PleaseEnterNewsletterSubject', 'Por favor digite o assunto da sua campanha de email.');
define('LNG_HLP_NewsletterSubject', 'A linha do assunto do email. Para a maioria dos clientes de email, eles ver&atilde;o a linha de assunto antes de verem o o corpo do email.<br /><br />Voc&ecirc; pode incluir campos personalizados na linha de assunto clicando no link \\\'Inserir Campos Personalizados\\\' abaixo no editor e copiar/colar dentro do campo de assunto.');

define('LNG_Newsletter_Send_Disabled_Inactive', 'Voc&ecirc; n&atilde;o pode enviar esta campanha de email pois ela est&aacute; inativa.');
define('LNG_Newsletter_Send_Disabled', 'Voc&ecirc; n&atilde;o pode enviar esta campanha de email pois n&atilde;o tem acesso.');
define('LNG_Newsletter_Edit_Disabled', 'Voc&ecirc; n&atilde;o pode editar esta campanha de email pois n&atilde;o tem acesso.');
define('LNG_Newsletter_Copy_Disabled', 'Voc&ecirc; n&atilde;o pode copiar esta campanha de email pois n&atilde;o tem acesso.');
define('LNG_Newsletter_Delete_Disabled', 'Voc&ecirc; n&atilde;o pode excluir esta campanha de email pois n&atilde;o tem acesso.');
define('LNG_Newsletter_Delete_Disabled_SendInProgress', 'Voc&ecirc; n&atilde;o pode excluir uma campanha de email enquanto ela est&aacute; sendo enviada.');

define('LNG_Archive', 'Arquivar');

define('LNG_DeleteNewsletterPrompt', 'Tem certeza de que deseja excluir esta campanha de email?');

define('LNG_ArchiveNewsletters', 'Arquivar');
define('LNG_UnarchiveNewsletters', 'Desarquivar');
define('LNG_ApproveNewsletters', 'Ativar');
define('LNG_DisapproveNewsletters', 'Desativar');

define('LNG_Newsletter_Title_Enable', 'Habilitar esta campanha de email');
define('LNG_Newsletter_Title_Disable', 'Desabilitar esta campanha de email');

define('LNG_Newsletter_Title_Archive_Enable', 'Habilitar arquivamento desta campanha de email');
define('LNG_Newsletter_Title_Archive_Disable', 'Desabilitar arquivamento desta campanha de email');

define('LNG_NewsletterArchive', 'Arquivar Campanha de Email');
define('LNG_NewsletterArchiveExplain', 'Sim, arquivar esta campanha de email');
define('LNG_HLP_NewsletterArchive', 'Esta campanha de email deve ser arquivada? Caso seja, ele ser&aacute; arquivado para a lista de emails que est&aacute; sendo enviada. Voc&ecirc; pode publicar os arquivos em seu website para seus visitantes l&ecirc;-los ');

define('LNG_NewsletterIsActive', 'Ativar Campanha de Email');
define('LNG_NewsletterIsActiveExplain', 'Sim, esta campanha de email est&aacute; ativa');
define('LNG_HLP_NewsletterIsActive', 'Esta campanha de email deve ser marcada como ativa? Campanhas de email inativas n&atilde;o podem ser enviada para uma lista de emails e devem ser aprovadas primeiro.');

define('LNG_NewsletterCannotBeInactiveAndArchive', 'Este email n&atilde;o ser&aacute; em seu arquivo, uma vez que foi desativado. Assim que tiver sido reativado ele ser&aacute; inclu&iacute; em seu arquivo.');

define('LNG_UnableToLoadNewsletter', 'N&atilde;o foi poss&iacute;vel carregar a campanha de email. Tente novamente.');

define('LNG_NewsletterFile', 'Arquivo da Campanha de Email');
define('LNG_HLP_NewsletterFile', 'Envie um arquivo html de seu computador para usar como sua campanha de email');
define('LNG_UploadNewsletter', 'Enviar');
define('LNG_NewsletterFileEmptyAlert', 'Por favor escolha um arquivo de seu computador antes de tentar envi&aacute;-lo.');
define('LNG_NewsletterFileEmpty', 'Por favor escolha um arquivo de seu computador antes de tentar envi&aacute;-lo.');

define('LNG_NewsletterURL', 'URL da Campanha de Email');
define('LNG_HLP_NewsletterURL', 'Importar de uma url');
define('LNG_NewsletterURLEmptyAlert', 'Por favor digite uma URL para importar dela');
define('LNG_NewsletterURLEmpty', 'Por favor digite uma URL para importar uma campanha de email dela');

define('LNG_NewsletterActivatedSuccessfully', 'Campanha de email ativada com sucesso');
define('LNG_NewsletterDeactivatedSuccessfully', 'Campanha de email desativada com sucesso');

define('LNG_NewsletterArchive_ActivatedSuccessfully', 'Arquivo da campanha de email foi ativado com sucesso');
define('LNG_NewsletterArchive_DeactivatedSuccessfully', 'Arquivo da campanha de email foi desativado com sucesso');

define('LNG_ChooseNewsletters', 'Por favor escolha uma ou mais campanhas primeiro.');

define('LNG_MiscellaneousOptions', 'Outras Op&ccedil;&otilde;es');

define('LNG_LastSent', '&Uacute;ltimo Envio');
define('LNG_NotSent', 'N&atilde;o Enviado');

define('LNG_Newsletter_Edit_Disabled_SendInProgress', 'Voc&ecirc; n&atilde;o pode editar uma campanha de email enquanto ela estiver sendo enviada');
define('LNG_Newsletter_ChangeActive_Disabled_SendInProgress', 'Voc&ecirc; n&atilde;o pode mudar este status enquanto esta campanha de email estiver sendo enviada');

/**
**************************
* Changed/added in NX1.0.7
**************************
*/
define('LNG_HLP_NewsletterFormat', 'Em que formato voc&ecirc; gostaria de criar este email? Selecione Texto para criar uma newsletter de texto simples. Escolha HTML para incluir links e gr&aacute;ficos. Escolha HTML e Texto para criar uma vers&atilde;o em Texto e HTML. Se n&atilde;o tiver certeza, escolha HTML.');

/**
**************************
* Changed/added in NX1.1.1
**************************
*/
define('LNG_NewsletterFilesCopyFail', 'The images and/or attachments for this email campaign were not copied successfully.');

?>
