<table cellspacing="0" cellpadding="3" width="95%" align="center">
	<tr>
		<td class="heading1">%%GLOBAL_SubscribersManage%%</td>
	</tr>
	<tr>
		<td class="body">%%LNG_Help_SubscribersManage%%</td>
	</tr>
	<tr>
		<td>
			%%GLOBAL_Message%%
		</td>
	</tr>
	<tr><td class=body height="10">%%GLOBAL_Subscribers_AddButton%%</td></tr>
	<tr>
		<td class=body valign="bottom">
			<table width="100%" border="0">
				<tr>
					<td valign="bottom">
						%%GLOBAL_Subscribers_AddButton%%
						<form name="ActionMembersForm" method="post" action="index.php?Page=Subscribers&Action=Manage&SubAction=Change&List=%%GLOBAL_List%%" onsubmit="return ConfirmChanges();">
						<div>
							<select name="ChangeType">
								<option value="" SELECTED>%%LNG_ChooseAction%%</option>
								<option value="Delete">%%LNG_Delete%%</option>
								<option value="ChangeFormat_Text">%%LNG_ChangeFormat_Text%%</option>
								<option value="ChangeFormat_HTML">%%LNG_ChangeFormat_HTML%%</option>
								<option value="ChangeStatus_Confirm">%%LNG_ChangeStatus_Confirm%%</option>
								<option value="ChangeStatus_Unconfirm">%%LNG_ChangeStatus_Unconfirm%%</option>
							</select>
							<input type="submit" value="%%LNG_Go%%" class="text">
						</div>
					</td>
					<td align="right" valign="bottom">
						%%TPL_Paging%%
					</td>
				</tr>
			</table>
			<span>%%GLOBAL_SubscribersReport%%</span>
			<table border=0 cellspacing="1" cellpadding="2" width=100% class=text>
				<tr class="Heading3">
					<td width="5" nowrap align="center">
						<input type="checkbox" name="toggle" onClick="javascript: toggleAllCheckboxes(this);">
					</td>
					<td width="5">&nbsp;</td>
					<td width="30%">
						%%LNG_SubscriberEmail%%&nbsp;<a href='index.php?Page=Subscribers&Action=Manage&SubAction=Step3&SortBy=EmailAddress&Direction=Up&'><img src="images/sortup.gif" border=0></a>&nbsp;<a href='index.php?Page=Subscribers&Action=Manage&SubAction=Step3&SortBy=EmailAddress&Direction=Down&'><img src="images/sortdown.gif" border=0></a>
					</td>
					<td width="15%">
						%%LNG_DateSubscribed%%&nbsp;<a href='index.php?Page=Subscribers&Action=Manage&SubAction=Step3&SortBy=SubscribeDate&Direction=Up&'><img src="images/sortup.gif" border=0></a>&nbsp;<a href='index.php?Page=Subscribers&Action=Manage&SubAction=Step3&SortBy=SubscribeDate&Direction=Down'><img src="images/sortdown.gif" border=0></a>
					</td>
					<td width="10%">
						%%LNG_SubscriberFormat%%&nbsp;<a href='index.php?Page=Subscribers&Action=Manage&SubAction=Step3&SortBy=Format&Direction=Up&'><img src="images/sortup.gif" border=0></a>&nbsp;<a href='index.php?Page=Subscribers&Action=Manage&SubAction=Step3&SortBy=Format&Direction=Down'><img src="images/sortdown.gif" border=0></a>
					</td>
					<td width="10%">
						%%LNG_SubscriberStatus%%&nbsp;<a href='index.php?Page=Subscribers&Action=Manage&SubAction=Step3&SortBy=Status&Direction=Up&'><img src="images/sortup.gif" border=0></a>&nbsp;<a href='index.php?Page=Subscribers&Action=Manage&SubAction=Step3&SortBy=Status&Direction=Down'><img src="images/sortdown.gif" border=0></a>
					</td>
					<td width="15%">
						%%LNG_SubscriberConfirmed%%&nbsp;<a href='index.php?Page=Subscribers&Action=Manage&SubAction=Step3&SortBy=Confirmed&Direction=Up&'><img src="images/sortup.gif" border=0></a>&nbsp;<a href='index.php?Page=Subscribers&Action=Manage&SubAction=Step3&SortBy=Confirmed&Direction=Down'><img src="images/sortdown.gif" border=0></a>
					</td>
					<td width="20%">
						%%LNG_MailingList%%&nbsp;<a href='index.php?Page=Subscribers&Action=Manage&SubAction=Step3&SortBy=ListName&Direction=Up&'><img src="images/sortup.gif" border=0></a>&nbsp;<a href='index.php?Page=Subscribers&Action=Manage&SubAction=Step3&SortBy=ListName&Direction=Down'><img src="images/sortdown.gif" border=0></a>
					</td>
					<td width="70" nowrap>
						%%LNG_Action%%
					</td>
				</tr>
				%%TPL_Subscribers_Manage_AnyList_Row%%
			</table>
			%%TPL_Paging_Bottom%%
			</form>
		</td>
	</tr>
</table>


<script language="javascript">
	function ConfirmDelete(EmailID, List) {
		if (!EmailID) {
			return false;
		}
		if (confirm("%%LNG_DeleteSubscriberPrompt%%")) {
			document.location='index.php?Page=Subscribers&Action=Manage&SubAction=Delete&List=' + List + '&id=' + EmailID;
			return true;
		}
	}

	function ConfirmChanges() {
		var found_members = false;
		
		formObj = document.ActionMembersForm;
		
		if (formObj.ChangeType.selectedIndex == 0) {
			alert("%%LNG_PleaseChooseAction%%");
			formObj.ChangeType.focus();
			return false;
		}
		
		for (var i=0;i < formObj.length;i++)
		{
			fldObj = formObj.elements[i];
			if (fldObj.type == 'checkbox')
			{
				if (fldObj.checked) {
					found_members = true;
					break;
				}
			}
		}

		if (!found_members) {
			alert("%%LNG_ChooseSubscribers%%");
			return false;
		}

		if (confirm("%%LNG_ConfirmSubscriberChanges%%")) {
			return true;
		}
		return false;
	}
	
</script>
