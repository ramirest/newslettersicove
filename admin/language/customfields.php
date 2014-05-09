<?php
/**
* Language file variables for custom fields. This includes creating, editing, deleting, updating, managing etc.
*
* @see GetLang
*
* @version     $Id: customfields.php,v 1.20 2007/02/24 13:44:00 chris Exp $

*
* @package SendStudio
* @subpackage Language
*/

/**
* Here are all of the variables for the customfields area... Please backup before you start!
*/
define('LNG_CustomFieldsManage', 'Gerenciar Campos Personalizados');
define('LNG_Help_CustomFieldsManage', 'Use o formul&aacute;rio abaixo para gerenciar seus campos personalizados.');
define('LNG_Help_CustomFieldsManage_HasAccess', ' Para criar um novo campo personalizado, clique no bot&atilde;o "Criar Campo Personalizado" abaixo.');
define('LNG_CustomFieldsName', 'Campo Personalizado');
define('LNG_CustomFieldsType', 'Tipo');
define('LNG_NoCustomFields', 'Nenhum campo personalizado foi criado. Clique no bot&atilde;o "Criar Campo Personalizado" abaixo para criar um.');
define('LNG_CreateCustomFieldButton', 'Criar Campo Personalizado');

define('LNG_CustomFieldDeleteSuccess_One', '1 campo personalizado foi apagado com sucesso. Voc&ecirc; precisar&aacute; atualizar seus formul&aacute;rios de assinantes para remover este campo como uma op&ccedil;&atilde;o.');

define('LNG_CustomFieldDeleteSuccess_Many', '%s campos personalizados foram apagados com sucesso. Voc&ecirc; precisar&aacute; atualizar seus formul&aacute;rios de assinantes para remover estes campos como uma op&ccedil;&atilde;o.');

define('LNG_CustomFieldDeleteFail_One', '1 campo personalizado n&atilde;o p&ocirc;de ser apagado. Por favor tente novamentePlease try again.');
define('LNG_CustomFieldDeleteFail_Many', '%s campos personalizados n&atilde;o puderam ser apagados. Por favor tente novamente.');

define('LNG_CustomField_Edit_Disabled', 'Voc&ecirc; n&atilde;o pode editar este campo porqu&ecirc; n&atilde;o tem acesso.');

define('LNG_EditCustomField', 'Editar Campo Personalizado');
define('LNG_EditCustomFieldIntro', 'Complete o formul&aacute;rio abaixo para atualizar o campo personalizado selecionado.');
define('LNG_EditCustomField_CancelPrompt', 'Tem certeza de que deseja cancelar a edi&ccedil;&atilde;o deste campo personalizado?');

define('LNG_CustomFieldCreated', 'Campo personalizado criado com sucesso');
define('LNG_UnableToCreateCustomField', 'N&atilde;o foi poss&iacute;vel criar o campo personalizado');

define('LNG_CustomFieldUpdated', 'O campo personalizado selecionado foi atualizado com sucesso');

define('LNG_UnableToUpdateCustomField', 'N&atilde;o foi poss&iacute;vel atualizar o campo personalizado');

define('LNG_CreateCustomField_CancelPrompt', 'Tem certeza de que deseja cancelar a cria&ccedil;&atilde;o deste campo personalizado?');

define('LNG_CreateCustomFieldCancelButton', 'Cancelar');
define('LNG_CreateCustomField', 'Criar Campo Personalizado');
define('LNG_CreateCustomFieldIntro', 'Digite os detalhes do campo personalizado no formul&aacute;rio abaixo.');
define('LNG_CreateCustomFieldHeading', 'Criar um novo campo personalizado abaixo');

define('LNG_CustomFieldName', 'Nome do Campo Personalizado');
define('LNG_HLP_CustomFieldName', 'Digite um nome para este campo personalizado. O nome aparecer&aacute; no seu formul&aacute;rio de cria&ccedil;&atilde;o de newsletter. Por exemplo, \\\'Nome\\\', \\\'Sexo\\\', etc.');

define('LNG_CustomFieldType', 'Tipo do Campo Personalizado');
define('LNG_HLP_CustomFieldType', 'Escolha o tipo de conte&uacute;do para este campo personalizado. Se voc&ecirc; escolher uma op&ccedil;&atilde;o de lista (como um radio box ou menu dropdown) voc&ecirc; pode especificar os valores na pr&oacute;xima p&aacute;gina.');

define('LNG_CustomFieldRequired', 'Obrigat&oacute;rio');
define('LNG_HLP_CustomFieldRequired', 'Este campo &eacute; obrigat&oacute;rio?');
define('LNG_CustomFieldRequiredExplain', 'Sim, este campo &eacute; obrigat&oacute;rio');

define('LNG_EnterCustomFieldName', 'Por favor digite o nome do campo personalizado');
define('LNG_CustomFieldDetails', 'Detalhes do Campo Personalizado');

define('LNG_CustomFieldType_text', 'Campo de Texto');
define('LNG_CustomFieldType_textarea', 'Caixa de Texto Multi-linhas');
define('LNG_CustomFieldType_date', 'Campo de Data');
define('LNG_CustomFieldType_number', 'Campo Num&eacute;rico');
define('LNG_CustomFieldType_checkbox', 'Campo Checkbox');
define('LNG_CustomFieldType_multicheckbox', 'Campo Checkbox M&uacute;ltiplo');
define('LNG_CustomFieldType_dropdown', 'Menu Dropdown');
define('LNG_CustomFieldType_radiobutton', 'Campo Radio');

define('LNG_CreateCustomField_Step2', 'Criar Campo Personalizado');
define('LNG_CreateCustomField_Step2_Intro', ' Digite os detalhes do campo personalizado no formul&aacute;rio abaixo.');

define('LNG_CreateCustomField_Step3', 'Criar Campo Personalizado');
define('LNG_CreateCustomField_Step3_Intro', 'Escolha a qual lista de emails este campo personalizado dever&aacute; ser associado.');
define('LNG_AssociateCustomField', 'Selecione as Listas de Emails');

define('LNG_EditCustomField_Step3', 'Editar Campo Personalizado');
define('LNG_EditCustomField_Step3_Intro', LNG_CreateCustomField_Step3_Intro);

define('LNG_FieldLength', 'Tamanho do Campo');
define('LNG_HLP_FieldLength', 'O tamanho da caixa de texto como aparecer&aacute; em seus formul&aacute;rios. Deixe em branco se n&atilde;o tiver certeza.');

define('LNG_DefaultValue', 'Valor Padr&atilde;o');
define('LNG_HLP_DefaultValue', 'Digite um valor padr&atilde;o opcional para este campo. A caixa de texto em todos seus formul&aacute;rios ser&aacute; preenchida com este texto.');

define('LNG_MaxLength', 'Tamanho m&aacute;ximo');
define('LNG_HLP_MaxLength', 'Digite o tamanho m&aacute;ximo para o texto digitado dentro deste campo. Por exemplo, digitando 2 limitar&aacute; a entrada a apenas 2 caracteres.');

define('LNG_MinLength', 'Tamanho m&iacute;nimo');
define('LNG_HLP_MinLength', 'Digite o tamanho m&iacute;nimo para o texto digitado dentro deste campo. Por exemplo, digitando 2 significa que o usu&aacute;rio precisar&aacute; digitar pelo menos 2 caracteres');

define('LNG_Instructions', 'Instru&ccedil;&otilde;es');
define('LNG_HLP_Instructions', 'Digite as instru&ccedil;&otilde;es que o usu&aacute;rio ver&aacute; ao selecionar uma op&ccedil;&atilde;o. Geralmente s&atilde;o instru&ccedil;&otilde;es como \\\'Por favor, selecione uma op&ccedil;&atilde;o\\\'.');

define('LNG_Dropdown_Key', 'Valor da Op&ccedil;&atilde;o');
define('LNG_Dropdown_Value', 'Texto de Apresenta&ccedil;&atilde;o da Op&ccedil;&atilde;o');
define('LNG_HLP_Dropdown_Key', 'Digite um valor para esta op&ccedil;&atilde;o. Isto &eacute; um valor associado &agrave; op&ccedil;&atilde;o selecionada. Geralmente, isto &eacute; uma breve vers&atilde;o de seu texto de apresenta&ccedil;&atilde;o, como M para Masculino');
define('LNG_HLP_Dropdown_Value', 'Digite o texto de apresenta&ccedil;&atilde;o para esta op&ccedil;&atilde;o. Isto &eacute; o que os assinantes ver&atilde;o quando eles escolherem esta op&ccedil;&atilde;o. ex: Masculino');

define('LNG_Checkbox_Key', 'Valor do Checkbox');
define('LNG_Checkbox_Value', 'Texto de Apresenta&ccedil;&atilde;o do Checkbox');
define('LNG_HLP_Checkbox_Key', 'Digite um valor para este checkbox. Isto &eacute; um valor associado &agrave; op&ccedil;&atilde;o selecionada. Geralmente, isto &eacute; uma breve vers&atilde;o de seu texto de apresenta&ccedil;&atilde;o, como M para Masculino');
define('LNG_HLP_Checkbox_Value', 'Digite um texto de apresenta&ccedil;&atilde;o para este checkbox. Isto &eacute; o que os assinantes ver&atilde;o quando eles escolherem esta op&ccedil;&atilde;o. ex: Masculino');

define('LNG_Radiobutton_Key', 'Valor do Radio');
define('LNG_Radiobutton_Value', 'Texto de Apresenta&ccedil;&atilde;o do Radio');
define('LNG_HLP_Radiobutton_Key', 'Digite um valor para este radio. Isto &eacute; um valor associado &agrave; op&ccedil;&atilde;o selecionada. Geralmente, isto &eacute; uma breve vers&atilde;o de seu texto de apresenta&ccedil;&atilde;o, como M para Masculino');
define('LNG_HLP_Radiobutton_Value', 'Digite um texto de apresenta&ccedil;&atilde;o para este radio . Isto &eacute; o que os assinantes ver&atilde;o quando eles escolherem esta op&ccedil;&atilde;o. ex: Masculino');

define('LNG_AddMore', 'Adicionar Mais');

define('LNG_CustomFieldListAssociation', 'Associar este campo personalizado "%s" com sua lista de emails');

define('LNG_DateDisplayOrder', 'Date Display Order');
define('LNG_HLP_DateDisplayOrder', 'In what order do you want the date values to display? Enter day, month or year in each of the three display fields.');
define('LNG_DateDisplayOrderFirst', 'Display Order (First)');
define('LNG_DateDisplayOrderSecond', 'Display Order (Second)');
define('LNG_DateDisplayOrderThird', 'Display Order (Third)');
define('LNG_DateDisplayStartYear', 'Start Year');
define('LNG_HLP_DateDisplayStartYear', 'When displaying the year dropdown list, which year should be first in the list?');
define('LNG_DateDisplayEndYear', 'End Year');
define('LNG_HLP_DateDisplayEndYear', 'When displaying the year dropdown list, which year should be last in the list?<br/>If 0 is entered, this will be the current year (' . date('Y') . ') and change automatically.');

define('LNG_DeleteCustomFieldButton', 'Delete Selected');
define('LNG_ChooseFieldsToDelete', 'Please choose a custom field first.');
define('LNG_DeleteCustomFieldPrompt', 'Are you sure you want to delete this custom field?');
define('LNG_CannotDeleteCustomField_NoAccess', 'You do not have permission to delete field \'%s\'.');
define('LNG_CustomField_Delete_Disabled', 'You do not have permission to delete this field.');

define('LNG_ChooseCustomFieldLists', 'Please select one or more mailing lists to associate this custom field with');

define('LNG_DropdownInstructions', '[ Select an option ]');

define('LNG_TextAreaRows', 'Number of Rows');
define('LNG_HLP_TextAreaRows', 'The number of rows to show in the multiline text box. This is how long the multiline text box will be.');

define('LNG_TextAreaColumns', 'Number of Columns');
define('LNG_HLP_TextAreaColumns', 'The number of columns to show in the multiline text box. This is how wide the multiline text box will be.');
?>
