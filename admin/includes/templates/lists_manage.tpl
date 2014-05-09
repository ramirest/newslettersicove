<table cellspacing="0" cellpadding="3" width="95%" align="center">
	<tr>
		<td class="heading1">%%LNG_ListsManage%%</td>
	</tr>
	<tr>
		<td class="Intro"><br>%%LNG_Help_ListsManage%%%%GLOBAL_Lists_Heading%%</td>
	</tr>
	<tr>
		<td>
			%%GLOBAL_Message%%
		</td>
	</tr>
	<tr>
		<td class=body>
			<span class=body>%%GLOBAL_ListsReport%%</span>
			<table width="100%" border="0">
				<tr>
					<td valign="bottom">
						<div style="padding-top:10px; padding-bottom:10px">
							%%GLOBAL_Lists_AddButton%%
						</div>
						<form name="ActionListsForm" method="post" action="index.php?Page=Lists&Action=Change" onsubmit="return ConfirmChanges();" style="margin: 0px;padding: 0px;">
							<select name="ChangeType">
								<option value="" SELECTED>%%LNG_ChooseAction%%</option>
								%%GLOBAL_Option_DeleteList%%
								%%GLOBAL_Option_DeleteSubscribers%%
								<option value="ChangeFormat_Text">%%LNG_ChangeFormat_Text%%</option>
								<option value="ChangeFormat_HTML">%%LNG_ChangeFormat_HTML%%</option>
								<option value="ChangeStatus_Confirm">%%LNG_ChangeStatus_Confirm%%</option>
								<option value="ChangeStatus_Unconfirm">%%LNG_ChangeStatus_Unconfirm%%</option>
								<option value="MergeLists">%%LNG_MergeLists%%</option>
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
					<td width="60%">
						%%LNG_ListName%%&nbsp;<a href='index.php?Page=%%PAGE%%&SortBy=Name&Direction=Up&%%GLOBAL_SearchDetails%%'><img src="images/sortup.gif" border=0></a>&nbsp;<a href='index.php?Page=%%PAGE%%&SortBy=Name&Direction=Down&%%GLOBAL_SearchDetails%%'><img src="images/sortdown.gif" border=0></a>
					</td>
					<td width="20%">
						%%LNG_Created%%&nbsp;<a href='index.php?Page=%%PAGE%%&SortBy=Date&Direction=Up&%%GLOBAL_SearchDetails%%'><img src="images/sortup.gif" border=0></a>&nbsp;<a href='index.php?Page=%%PAGE%%&SortBy=Date&Direction=Down&%%GLOBAL_SearchDetails%%'><img src="images/sortdown.gif" border=0></a>
					</td>
					<td width="20%">
						%%LNG_Subscribers%%&nbsp;<a href='index.php?Page=%%PAGE%%&SortBy=Subscribers&Direction=Up&%%GLOBAL_SearchDetails%%'><img src="images/sortup.gif" border=0></a>&nbsp;<a href='index.php?Page=%%PAGE%%&SortBy=Subscribers&Direction=Down&%%GLOBAL_SearchDetails%%'><img src="images/sortdown.gif" border=0></a>
					</td>
					<td width="100">
						%%LNG_Action%%
					</td>
					<td width="30">
						%%LNG_ArchiveLists%%
					</td>
				</tr>
				%%TPL_Lists_Manage_Row%%
			</table>
			</form>
			%%TPL_Paging_Bottom%%
		</td>
	</tr>
</table>

<script language="javascript">
	function ConfirmDelete(ListID) {
		if (!ListID) {
			return false;
		}
		if (confirm("%%LNG_DeleteListPrompt%%")) {
			document.location='index.php?Page=%%PAGE%%&Action=Delete&id=' + ListID;
			return true;
		}
	}

	function ConfirmDeleteAllSubscribers(ListID) {
		if (!ListID) {
			return false;
		}
		if (confirm("%%LNG_DeleteAllSubscribersPrompt%%")) {
			document.location='index.php?Page=%%PAGE%%&Action=DeleteAllSubscribers&id=' + ListID;
			return true;
		}
	}

	function ConfirmChanges() {
		formObj = document.ActionListsForm;
		
		if (formObj.ChangeType.selectedIndex == 0) {
			alert("%%LNG_PleaseChooseAction%%");
			formObj.ChangeType.focus();
			return false;
		}

		selectedValue = formObj.ChangeType[formObj.ChangeType.selectedIndex].value;

		lists_found = 0;
		for (var i=0;i < formObj.length;i++)
		{
			fldObj = formObj.elements[i];
			if (fldObj.type == 'checkbox')
			{
				if (fldObj.checked) {
					lists_found++;
				}
			}
		}

		if (lists_found <= 0) {
			alert("%%LNG_ChooseList%%");
			return false;
		}

		if (selectedValue == 'MergeLists') {
			if (lists_found < 2) {
				alert("%%LNG_ChooseMultipleLists%%");
				return false;
			}
		}

		if (confirm("%%LNG_ConfirmChanges%%")) {
			return true;
		}

		return false;
	}

</script>
