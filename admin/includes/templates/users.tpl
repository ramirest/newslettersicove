<table cellspacing="0" cellpadding="3" width="95%" align="center">
	<tr>
		<td class="heading1">%%LNG_Users%%</td>
	</tr>
	<tr>
		<td class="body">%%LNG_Help_Users%%</td>
	</tr>
	<tr>
		<td>
			%%GLOBAL_Message%%
		</td>
	</tr>
	<tr>
		<td>
			<div class="UserInfo">
				<img src="images/user.gif" style="margin-top: -3px;" align="left">%%GLOBAL_UserReport%%
			</div>
		</td>
	</tr>
	<tr>
		<td class=body>
			<form name="bannedform" method="post" action="index.php?Page=Users&Action=Delete" onsubmit="return DeleteSelectedUsers(this);">
			<table border="0" width="100%" style="padding-top:5px">
				<tr>
					<td>
						%%GLOBAL_Users_AddButton%%
						%%GLOBAL_Users_DeleteButton%%
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
					<td width="30%">
						%%LNG_UserName%%&nbsp;<a href='index.php?Page=%%PAGE%%&SortBy=UserName&Direction=Up&%%GLOBAL_SearchDetails%%'><img src="images/sortup.gif" border=0></a>&nbsp;<a href='index.php?Page=%%PAGE%%&SortBy=UserName&Direction=Down&%%GLOBAL_SearchDetails%%'><img src="images/sortdown.gif" border=0></a>
					</td>
					<td width="30%">
						%%LNG_FullName%%&nbsp;<a href='index.php?Page=%%PAGE%%&SortBy=Name&Direction=Up&%%GLOBAL_SearchDetails%%'><img src="images/sortup.gif" border=0></a>&nbsp;<a href='index.php?Page=%%PAGE%%&SortBy=Name&Direction=Down&%%GLOBAL_SearchDetails%%'><img src="images/sortdown.gif" border=0></a>
					</td>
					<td width="20%">
						%%LNG_Status%%
					</td>
					<td width="20%">
						%%LNG_UserType%%
					</td>
					<td width="70">
						%%LNG_Action%%
					</td>
				</tr>
			%%TPL_Users_List_Row%%
			</table>
		%%TPL_Paging_Bottom%%
		</td>
	</tr>
</table>

<script language="javascript">
	function DeleteSelectedUsers(formObj) {
		users_found = 0;
		for (var i=0;i < formObj.length;i++)
		{
			fldObj = formObj.elements[i];
			if (fldObj.type == 'checkbox')
			{
				if (fldObj.checked) {
					users_found++;
				}
			}
		}

		if (users_found <= 0) {
			alert("%%LNG_ChooseUsersToDelete%%");
			return false;
		}

		if (confirm("%%LNG_ConfirmRemoveUsers%%")) {
			return true;
		}
		return false;
	}

	function ConfirmDelete(UserID) {
		if (!UserID) {
			return false;
		}
		if (confirm("%%LNG_DeleteUserPrompt%%")) {
			document.location='index.php?Page=%%PAGE%%&Action=Delete&id=' + UserID;
			return true;
		}
	}

</script>
