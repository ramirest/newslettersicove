<table cellspacing="0" cellpadding="3" width="95%" align="center">
	<tr>
		<td class="heading1">%%LNG_CustomFieldsManage%%</td>
	</tr>
	<tr>
		<td class="body">%%LNG_Help_CustomFieldsManage%%%%GLOBAL_CustomFields_Heading%%</td>
	</tr>
	<tr>
		<td>
			%%GLOBAL_Message%%
		</td>
	</tr>
	<tr>
		<td class="body">
			<form name="formsform" method="post" action="index.php?Page=CustomFields&Action=Delete" onsubmit="return DeleteSelectedCustomFields(this);">
			<table width="100%" border="0">
				<tr>
					<td valign="top" valign="bottom">
						%%GLOBAL_CustomFields_AddButton%%
						%%GLOBAL_CustomFields_DeleteButton%%
					</td>
					<td align="right" valign="bottom">
						%%TPL_Paging%%
					</td>
				</tr>
			</table>
			<table border=0 cellspacing="1" cellpadding="2" width=100% class=text>
				<tr class="Heading3">
					<td width="5" nowrap align="center">
						<input type="checkbox" name="toggle" onClick="javascript: toggleAllCheckboxes(this);">
					</td>
					<td width="5">&nbsp;</td>
					<td width="55%">
						%%LNG_CustomFieldsName%%&nbsp;<a href='index.php?Page=%%PAGE%%&SortBy=Name&Direction=Up&%%GLOBAL_SearchDetails%%'><img src="images/sortup.gif" border=0></a>&nbsp;<a href='index.php?Page=%%PAGE%%&SortBy=Name&Direction=Down&%%GLOBAL_SearchDetails%%'><img src="images/sortdown.gif" border=0></a>
					</td>
					<td width="15%">
						%%LNG_Created%%&nbsp;<a href='index.php?Page=%%PAGE%%&SortBy=Date&Direction=Up&%%GLOBAL_SearchDetails%%'><img src="images/sortup.gif" border=0></a>&nbsp;<a href='index.php?Page=%%PAGE%%&SortBy=Date&Direction=Down&%%GLOBAL_SearchDetails%%'><img src="images/sortdown.gif" border=0></a>
					</td>
					<td width="15%">
						%%LNG_CustomFieldsType%%&nbsp;<a href='index.php?Page=%%PAGE%%&SortBy=Type&Direction=Up&%%GLOBAL_SearchDetails%%'><img src="images/sortup.gif" border=0></a>&nbsp;<a href='index.php?Page=%%PAGE%%&SortBy=Type&Direction=Down&%%GLOBAL_SearchDetails%%'><img src="images/sortdown.gif" border=0></a>
					</td>
					<td width="20%">
						%%LNG_CustomFieldRequired%%
					</td>
					<td width="100">
						%%LNG_Action%%
					</td>
				</tr>
				%%TPL_CustomFields_Manage_Row%%
			</table>
			%%TPL_Paging_Bottom%%
		</td>
	</tr>
</table>

<script language="javascript">
	function DeleteSelectedCustomFields(formObj) {
		fields_found = 0;
		for (var i=0;i < formObj.length;i++)
		{
			fldObj = formObj.elements[i];
			if (fldObj.type == 'checkbox')
			{
				if (fldObj.checked) {
					fields_found++;
				}
			}
		}

		if (fields_found < 1) {
			alert("%%LNG_ChooseFieldsToDelete%%");
			return false;
		}

		if (confirm("%%LNG_ConfirmChanges%%")) {
			return true;
		}
		return false;
	}

function ConfirmDelete(FieldID) {
	if (!FieldID) {
		return false;
	}
	if (confirm("%%LNG_DeleteCustomFieldPrompt%%")) {
		document.location='index.php?Page=%%PAGE%%&Action=Delete&id=' + FieldID;
		return true;
	}
	return false;
}

</script>
