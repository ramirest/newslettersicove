<form method="post" action="index.php?Page=Subscribers&Action=Banned&SubAction=Update&id=%%GLOBAL_BanID%%">
	<table cellspacing="0" cellpadding="3" width="95%" align="center">
		<tr>
			<td class="heading1">
				%%LNG_Subscribers_Banned_Edit%%
			</td>
		</tr>
		<tr>
			<td class="body">
				%%LNG_Subscribers_Banned_Edit_Intro%%
			</td>
		</tr>
		<tr>
			<td>
				%%GLOBAL_Message%%
			</td>
		</tr>
		<tr>
			<td>
				<input class="formbutton" type="button" value="%%LNG_Cancel%%" onClick='if(confirm("%%LNG_Subscribers_Banned_Edit_CancelPrompt%%")) { document.location="index.php?Page=Subscribers&Action=Banned" }'>
				<input class="formbutton" type="submit" value="%%LNG_Save%%">
				<br />
				&nbsp;
				<table border="0" cellspacing="0" cellpadding="2" width="100%" class="panel">
					<tr>
						<td colspan="2" class="heading2">
							&nbsp;&nbsp;%%LNG_BannedEmailDetails%%
						</td>
					</tr>
					<tr>
						<td width="200" class="FieldLabel">
							%%TPL_Required%%
							%%LNG_BanSingleEmail%%:&nbsp;
						</td>
						<td>
							<input type="text" name="BannedEmail" value="%%GLOBAL_BannedAddress%%" class="field250">&nbsp;%%LNG_HLP_BanSingleEmail%%
						</td>
					</tr>
					<tr>
						<td width="200" class="FieldLabel">
							%%TPL_Required%%
							%%LNG_BannedEmailsChooseList_Edit%%:&nbsp;
						</td>
						<td>
							<select name="list" size="7" class="field250">
								%%GLOBAL_SelectList%%
							</select>&nbsp;%%LNG_HLP_BannedEmailsChooseList_Edit%%
						</td>
					</tr>
					<tr>
						<td>
							&nbsp;
						</td>
						<td>
							<input class="formbutton" type="button" value="%%LNG_Cancel%%" onClick='if(confirm("%%LNG_Subscribers_Banned_Edit_CancelPrompt%%")) { document.location="index.php?Page=Subscribers&Action=Banned" }'>
							<input class="formbutton" type="submit" value="%%LNG_Save%%">
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</form>
<script language="javascript">
	function CheckForm() {
		var f = document.forms[0];
		if (f.BannedEmail.value == "") {
			alert("%%LNG_Banned_Edit_Empty%%");
			f.BannedEmail.focus();
			return false;
		}
		if (f.list.selectedIndex == -1) {
			alert("%%LNG_Banned_Edit_ChooseList%%");
			f.list.focus();
			return false;
		}
		return true;
	}
</script>