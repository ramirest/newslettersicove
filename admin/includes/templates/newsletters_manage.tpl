	<table cellspacing="0" cellpadding="3" width="95%" align="center">
	<tr>
		<td class="heading1">%%LNG_NewslettersManage%%</td>
	</tr>
	<tr>
		<td class="body">%%LNG_Help_NewslettersManage%%%%GLOBAL_Newsletters_HasAccess%%</td>
	</tr>
	<tr>
		<td>
			%%GLOBAL_Message%%
		</td>
	</tr>
	<tr>
		<td class="body">
			<table width="100%" border="0">
				<tr>
					<td>
						<div style="padding-top:10px; padding-bottom:10px">
							%%GLOBAL_Newsletters_AddButton%%
						</div>
						<form name="ActionNewslettersForm" method="post" action="index.php?Page=Newsletters&Action=Change" onsubmit="return ConfirmChanges();" style="margin: 0px; padding: 0px;">
							<select name="ChangeType">
								<option value="" SELECTED>%%LNG_ChooseAction%%</option>
								%%GLOBAL_Option_DeleteNewsletter%%
								%%GLOBAL_Option_ArchiveNewsletter%%
								%%GLOBAL_Option_ActivateNewsletter%%
							</select>
							<input type="submit" value="%%LNG_Go%%" class="text">
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
					<td width="25%">
						%%LNG_Name%%&nbsp;<a href='index.php?Page=%%PAGE%%&SortBy=Name&Direction=Up&%%GLOBAL_SearchDetails%%'><img src="images/sortup.gif" border=0></a>&nbsp;<a href='index.php?Page=%%PAGE%%&SortBy=Name&Direction=Down&%%GLOBAL_SearchDetails%%'><img src="images/sortdown.gif" border=0></a>
					</td>
					<td width="30%">
						%%LNG_Subject%%&nbsp;<a href='index.php?Page=%%PAGE%%&SortBy=Subject&Direction=Up&%%GLOBAL_SearchDetails%%'><img src="images/sortup.gif" border=0></a>&nbsp;<a href='index.php?Page=%%PAGE%%&SortBy=Subject&Direction=Down&%%GLOBAL_SearchDetails%%'><img src="images/sortdown.gif" border=0></a>
					</td>
					<td width="14%">
						%%LNG_Created%%&nbsp;<a href='index.php?Page=%%PAGE%%&SortBy=Date&Direction=Up&%%GLOBAL_SearchDetails%%'><img src="images/sortup.gif" border=0></a>&nbsp;<a href='index.php?Page=%%PAGE%%&SortBy=Date&Direction=Down&%%GLOBAL_SearchDetails%%'><img src="images/sortdown.gif" border=0></a>
					</td>
					<td width="10%">
						%%LNG_LastSent%%
					</td>
					<td width="11%">
						%%LNG_Format%%
					</td>
					<td width="5%">
						%%LNG_Active%%
					</td>
					<td width="5%">
						%%LNG_Archive%%
					</td>
					<td width="150">
						%%LNG_Action%%
					</td>
				</tr>
				%%TPL_Newsletters_Manage_Row%%
			</table>
			%%TPL_Paging_Bottom%%
		</td>
	</tr>
</table>

<script language="javascript">
	function ConfirmChanges() {
		formObj = document.ActionNewslettersForm;
		
		if (formObj.ChangeType.selectedIndex == 0) {
			alert("%%LNG_PleaseChooseAction%%");
			formObj.ChangeType.focus();
			return false;
		}

		selectedValue = formObj.ChangeType[formObj.ChangeType.selectedIndex].value;

		newsletters_found = 0;
		for (var i=0;i < formObj.length;i++)
		{
			fldObj = formObj.elements[i];
			if (fldObj.type == 'checkbox')
			{
				if (fldObj.checked) {
					newsletters_found++;
				}
			}
		}

		if (newsletters_found <= 0) {
			alert("%%LNG_ChooseNewsletters%%");
			return false;
		}

		if (confirm("%%LNG_ConfirmChanges%%")) {
			return true;
		}

		return false;
	}

	function ConfirmDelete(NewsletterID) {
		if (!NewsletterID) {
			return false;
		}
		if (confirm("%%LNG_DeleteNewsletterPrompt%%")) {
			document.location='index.php?Page=%%PAGE%%&Action=Delete&id=' + NewsletterID;
			return true;
		}
	}
</script>
