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
define('LNG_CustomFieldsManage', 'Manage Custom Fields');
define('LNG_Help_CustomFieldsManage', 'Use the form below to manage your custom fields.');
define('LNG_Help_CustomFieldsManage_HasAccess', ' To create a new custom field, click on the "Create Custom Field" button below.');
define('LNG_CustomFieldsName', 'Custom Field');
define('LNG_CustomFieldsType', 'Type');
define('LNG_NoCustomFields', 'No custom fields have been created. Click the "Create Custom Field" button below to create one.');
define('LNG_CreateCustomFieldButton', 'Create Custom Field');

define('LNG_CustomFieldDeleteSuccess_One', '1 custom field has been deleted successfully. You will need to update your subscriber forms to remove this field as an option.');

define('LNG_CustomFieldDeleteSuccess_Many', '%s custom fields have been deleted successfully. You will need to update your subscriber forms to remove this field as an option.');

define('LNG_CustomFieldDeleteFail_One', '1 custom field has not been deleted successfully. Please try again.');
define('LNG_CustomFieldDeleteFail_Many', '%s custom fields have not been deleted successfully. Please try again.');

define('LNG_CustomField_Edit_Disabled', 'You cannot edit this custom field because you do not have access.');

define('LNG_EditCustomField', 'Edit Custom Field');
define('LNG_EditCustomFieldIntro', 'Complete the form below to update the selected custom field.');
define('LNG_EditCustomField_CancelPrompt', 'Are you sure you want to cancel editing of this custom field?');

define('LNG_CustomFieldCreated', 'Custom field created successfully');
define('LNG_UnableToCreateCustomField', 'Unable to create custom field');

define('LNG_CustomFieldUpdated', 'The selected custom field has been updated successfully');

define('LNG_UnableToUpdateCustomField', 'Unable to update custom field');

define('LNG_CreateCustomField_CancelPrompt', 'Are you sure you want to cancel creating this custom field?');

define('LNG_CreateCustomFieldCancelButton', 'Cancel');
define('LNG_CreateCustomField', 'Create Custom Field');
define('LNG_CreateCustomFieldIntro', 'Enter the details of the custom field in the form below.');
define('LNG_CreateCustomFieldHeading', 'Create a new custom field below');

define('LNG_CustomFieldName', 'Custom Field Name');
define('LNG_HLP_CustomFieldName', 'Enter a name for this custom field. The name will appear on your newsletter subscription form. For example, \\\'First Name\\\', \\\'Gender\\\', etc.');

define('LNG_CustomFieldType', 'Custom Field Type');
define('LNG_HLP_CustomFieldType', 'Choose the type of content for this custom field. If you choose a list option (such as radio box or dropdown list) you can specify values on the next page.');

define('LNG_CustomFieldRequired', 'Required');
define('LNG_HLP_CustomFieldRequired', 'Is this field required?');
define('LNG_CustomFieldRequiredExplain', 'Yes, this field is required');

define('LNG_EnterCustomFieldName', 'Please enter the custom field name');
define('LNG_CustomFieldDetails', 'Custom Field Details');

define('LNG_CustomFieldType_text', 'Text Field');
define('LNG_CustomFieldType_textarea', 'Multiline Text Box');
define('LNG_CustomFieldType_date', 'Date Field');
define('LNG_CustomFieldType_number', 'Number Field');
define('LNG_CustomFieldType_checkbox', 'Checkbox Field');
define('LNG_CustomFieldType_multicheckbox', 'Multiple Checkbox Field');
define('LNG_CustomFieldType_dropdown', 'Dropdown List');
define('LNG_CustomFieldType_radiobutton', 'Radio Button Field');

define('LNG_CreateCustomField_Step2', 'Create Custom Field');
define('LNG_CreateCustomField_Step2_Intro', ' Enter the details of the custom field in the form below.');

define('LNG_CreateCustomField_Step3', 'Create Custom Field');
define('LNG_CreateCustomField_Step3_Intro', 'Choose which mailing lists this custom field should be associated with.');
define('LNG_AssociateCustomField', 'Select Mailing Lists');

define('LNG_EditCustomField_Step3', 'Edit Custom Field');
define('LNG_EditCustomField_Step3_Intro', LNG_CreateCustomField_Step3_Intro);

define('LNG_FieldLength', 'Field Length');
define('LNG_HLP_FieldLength', 'The length of the text box as it will appear on your forms. Leave this field blank if you are unsure.');

define('LNG_DefaultValue', 'Default Value');
define('LNG_HLP_DefaultValue', 'Enter an optional default value for this field. The text box on all of your forms will be prefilled with this text.');

define('LNG_MaxLength', 'Maximum Length');
define('LNG_HLP_MaxLength', 'Enter a maximum length for text typed into this custom field. For example, entering 2 will limit input to only 2 characters.');

define('LNG_MinLength', 'Minimum Length');
define('LNG_HLP_MinLength', 'Enter a minimum length for text typed into this custom field. For example, entering 2 means the user has to type in a minimum of 2 characters.');

define('LNG_Instructions', 'Instructions');
define('LNG_HLP_Instructions', 'Enter in the instructions the user will see when prompted to select an option. This is usually instructions such as \\\'Please select an option\\\'.');

define('LNG_Dropdown_Key', 'Option Value');
define('LNG_Dropdown_Value', 'Option Display Text');
define('LNG_HLP_Dropdown_Key', 'Enter a value for this option. This is the value associated to the selected option. Usually, this is a short version of your display text, such as M for Male');
define('LNG_HLP_Dropdown_Value', 'Enter the display text for this option. This is what the subscriber sees when they choose this option. eg Male');

define('LNG_Checkbox_Key', 'Checkbox Value');
define('LNG_Checkbox_Value', 'Checkbox Display Text');
define('LNG_HLP_Checkbox_Key', 'Enter a value for this checkbox. This is the value associated to the selected option. Usually, this is a short version of your display text, such as M for Male');
define('LNG_HLP_Checkbox_Value', 'Enter the display text for this checkbox. This is what the subscriber sees when they choose this option. eg Male');

define('LNG_Radiobutton_Key', 'Radio Value');
define('LNG_Radiobutton_Value', 'Radio Display Text');
define('LNG_HLP_Radiobutton_Key', 'Enter a value for this radio button. This is the value associated to the selected option. Usually, this is a short version of your display text, such as M for Male');
define('LNG_HLP_Radiobutton_Value', 'Enter the display text for this radio button. This is what the subscriber sees when they choose this option. eg Male');

define('LNG_AddMore', 'Add More');

define('LNG_CustomFieldListAssociation', 'Associate the custom field "%s" with your mailing lists');

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
