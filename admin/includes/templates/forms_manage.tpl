<table cellspacing="0" cellpadding="3" width="95%" align="center">
	<tr>
		<td class="heading1">%%LNG_FormsManage%%</td>
	</tr>
	<tr>
		<td class="body">%%LNG_Help_FormsManage%%</td>
	</tr>
	<tr>
		<td>%%GLOBAL_Message%%</td>
	</tr>
	<tr>
		<td class="body">
			<form name="formsform" method="post" action="index.php?Page=Forms&Action=Delete" onsubmit="return DeleteSelectedForms(this);">
			<table width="100%" border="0">
				<tr>
					<td valign="top">
						%%GLOBAL_Forms_AddButton%%
						%%GLOBAL_Forms_DeleteButton%%
					</td>
					<td align="right">
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
					<td width="60%">
						%%LNG_FormName%%&nbsp;<a href='index.php?Page=%%PAGE%%&SortBy=Name&Direction=Up&%%GLOBAL_SearchDetails%%'><img src="images/sortup.gif" border=0></a>&nbsp;<a href='index.php?Page=%%PAGE%%&SortBy=Name&Direction=Down&%%GLOBAL_SearchDetails%%'><img src="images/sortdown.gif" border=0></a>
					</td>
					<td width="20%">
						%%LNG_Created%%&nbsp;<a href='index.php?Page=%%PAGE%%&SortBy=Date&Direction=Up&%%GLOBAL_SearchDetails%%'><img src="images/sortup.gif" border=0></a>&nbsp;<a href='index.php?Page=%%PAGE%%&SortBy=Date&Direction=Down&%%GLOBAL_SearchDetails%%'><img src="images/sortdown.gif" border=0></a>
					</td>
					<td width="20%">
						%%LNG_FormType%%
					</td>
					<td width="180" nowrap>
						%%LNG_Action%%
					</td>
				</tr>
			%%TPL_Forms_Manage_Row%%
			</table>
			%%TPL_Paging_Bottom%%
		</td>
	</tr>
</table>

<script language="javascript">
	function DeleteSelectedForms(formObj) {
		forms_found = 0;
		for (var i=0;i < formObj.length;i++)
		{
			fldObj = formObj.elements[i];
			if (fldObj.type == 'checkbox')
			{
				if (fldObj.checked) {
					forms_found++;
				}
			}
		}

		if (forms_found <= 0) {
			alert("%%LNG_ChooseFormsToDelete%%");
			return false;
		}

		if (confirm("%%LNG_ConfirmRemoveForms%%")) {
			return true;
		}
		return false;
	}

	function ConfirmDelete(FormID) {
		if (!FormID) {
			return false;
		}
		if (confirm("%%LNG_DeleteFormPrompt%%")) {
			document.location='index.php?Page=%%PAGE%%&Action=Delete&id=' + FormID;
			return true;
		}
	}
</script>
