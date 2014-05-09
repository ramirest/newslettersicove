<form method="post" action="index.php?Page=Lists&Action=%%GLOBAL_Action%%" onsubmit="return CheckForm()">
	<table cellspacing="0" cellpadding="3" width="95%" align="center">
		<tr>
			<td class="heading1">
				%%GLOBAL_Heading%%
			</td>
		</tr>
		<tr>
			<td class="body">
				%%GLOBAL_Intro%%
			</td>
		</tr>
		<tr>
			<td>
				%%GLOBAL_Message%%
			</td>
		</tr>
		<tr>
			<td>
				<input class="formbutton" type="button" value="%%LNG_Cancel%%" onClick='if(confirm("%%GLOBAL_CancelButton%%")) { document.location="index.php?Page=Lists" }'>
				<input class="formbutton" type="submit" value="%%LNG_Save%%">
				<br />&nbsp;
				<table border="0" cellspacing="0" cellpadding="2" width="100%" class="panel">
					<tr>
						<td colspan="2" class="heading2">
							&nbsp;&nbsp;%%GLOBAL_ListDetails%%
						</td>
					</tr>
					<tr>
						<td class="FieldLabel">
							%%TPL_Required%%
							%%LNG_ListName%%:&nbsp;
						</td>
						<td>
							<input type="text" name="Name" class="field250" value="%%GLOBAL_Name%%">&nbsp;%%LNG_HLP_ListName%%
						</td>
					</tr>
					<tr>
						<td class="FieldLabel">
							%%TPL_Required%%
							%%LNG_ListOwnerName%%:&nbsp;
						</td>
						<td>
							<input type="text" name="OwnerName" class="field250" value="%%GLOBAL_OwnerName%%">&nbsp;%%LNG_HLP_ListOwnerName%%
						</td>
					</tr>
					<tr>
						<td class="FieldLabel">
							%%TPL_Required%%
							%%LNG_ListOwnerEmail%%:&nbsp;
						</td>
						<td>
							<input type="text" name="OwnerEmail" class="field250" value="%%GLOBAL_OwnerEmail%%">&nbsp;%%LNG_HLP_ListOwnerEmail%%
						</td>
					</tr>
					<tr>
						<td class="FieldLabel">
							%%TPL_Required%%
							%%LNG_ListReplyToEmail%%:&nbsp;
						</td>
						<td>
							<input type="text" name="ReplyToEmail" class="field250" value="%%GLOBAL_ReplyToEmail%%">&nbsp;%%LNG_HLP_ListReplyToEmail%%
						</td>
					</tr>
					<tr style="display: %%GLOBAL_ShowBounceInfo%%">
						<td class="FieldLabel">
							%%TPL_Required%%
							%%LNG_ListBounceEmail%%:&nbsp;
						</td>
						<td>
							<input type="text" name="BounceEmail" class="field250" value="%%GLOBAL_BounceEmail%%">&nbsp;%%LNG_HLP_ListBounceEmail%%
						</td>
					</tr>
					<tr>
						<td class="FieldLabel">
							%%TPL_Not_Required%%
							%%LNG_NotifyOwner%%:&nbsp;
						</td>
						<td>
							<label for="NotifyOwner"><input type="checkbox" name="NotifyOwner" id="NotifyOwner" value="1" %%GLOBAL_NotifyOwner%%>%%LNG_NotifyOwnerExplain%%</label> %%LNG_HLP_NotifyOwner%%
						</td>
					</tr>
					<tr>
						<td class="EmptyRow" colspan="2" style="display: %%GLOBAL_ShowBounceInfo%%">
							&nbsp;
						</td>
					</tr>
					<tr style="display: %%GLOBAL_ShowBounceInfo%%">
						<td colspan="2" class="heading2">
							&nbsp;&nbsp;%%LNG_BounceAccountDetails%%
						</td>
					</tr>
					<tr style="display: %%GLOBAL_ShowBounceInfo%%">
						<td class="FieldLabel">
							%%TPL_Not_Required%%
							%%LNG_ListBounceServer%%:&nbsp;
						</td>
						<td>
							<input type="text" name="BounceServer" class="field250" value="%%GLOBAL_BounceServer%%">&nbsp;%%LNG_HLP_ListBounceServer%%
						</td>
					</tr>
					<tr style="display: %%GLOBAL_ShowBounceInfo%%">
						<td class="FieldLabel">
							%%TPL_Not_Required%%
							%%LNG_ListBounceUsername%%:&nbsp;
						</td>
						<td>
							<input type="text" name="BounceU" class="field250" value="%%GLOBAL_BounceUsername%%">&nbsp;%%LNG_HLP_ListBounceUsername%%
						</td>
					</tr>
					<tr style="display: %%GLOBAL_ShowBounceInfo%%">
						<td class="FieldLabel">
							%%TPL_Not_Required%%
							%%LNG_ListBouncePassword%%:&nbsp;
						</td>
						<td>
							<input type="password" name="BounceP" class="field250" value="%%GLOBAL_BouncePassword%%">&nbsp;%%LNG_HLP_ListBouncePassword%%
						</td>
					</tr>
					<tr style="display: %%GLOBAL_ShowBounceInfo%%">
						<td class="FieldLabel">
							%%TPL_Not_Required%%
							%%LNG_IMAPAccount%%:&nbsp;
						</td>
						<td>
							<label for="IMAPAccount"><input type="checkbox" name="IMAPAccount" id="IMAPAccount" value="1" %%GLOBAL_IMAPAccount%%>%%LNG_IMAPAccountExplain%%</label> %%LNG_HLP_IMAPAccount%%
						</td>
					</tr>
					<tr style="display: %%GLOBAL_ShowBounceInfo%%">
						<td class="FieldLabel">
							%%TPL_Not_Required%%
							%%LNG_UseExtraMailSettings%%:&nbsp;
						</td>
						<td>
							<label for="extramail"><input type="checkbox" name="extramail" id="extramail" value="1"%%GLOBAL_UseExtraMailSettings%% onClick="DisplayExtraMail(this);">%%LNG_UseExtraMailSettingsExplain%%</label> %%LNG_HLP_UseExtraMailSettings%%
						</td>
					</tr>
					<tr id="showextramailsettings" style="display: %%GLOBAL_DisplayExtraMailSettings%%">
						<td class="FieldLabel">
							%%TPL_Not_Required%%
							%%LNG_ExtraMailSettings%%:&nbsp;
						</td>
						<td>
							<input type="text" name="ExtraMailSettings" class="field250" value="%%GLOBAL_ExtraMailSettings%%">&nbsp;%%LNG_HLP_ExtraMailSettings%%
						</td>
					</tr>
					<tr>
						<td>
							&nbsp;
						</td>
						<td height="35">
							<input class="formbutton" type="button" value="%%LNG_Cancel%%" onClick='if(confirm("%%GLOBAL_CancelButton%%")) { document.location="index.php?Page=Lists" }'>
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

		if (f.Name.value == '') {
			alert("%%LNG_EnterListName%%");
			f.Name.focus();
			return false;
		}

		if (f.OwnerName.value == '') {
			alert("%%LNG_EnterOwnerName%%");
			f.OwnerName.focus();
			return false;
		}

		if (f.OwnerEmail.value == '') {
			alert("%%LNG_EnterOwnerEmail%%");
			f.OwnerEmail.focus();
			return false;
		}

		if (f.ReplyToEmail.value == '') {
			alert("%%LNG_EnterReplyToEmail%%");
			f.ReplyToEmail.focus();
			return false;
		}

		if (f.BounceEmail.value == '') {
			alert("%%LNG_EnterBounceEmail%%");
			f.BounceEmail.focus();
			return false;
		}
		return true;
	}

	function DisplayExtraMail(fld) {
		if (fld.checked) {
			document.getElementById('showextramailsettings').style.display = '';
			return;
		}
		document.getElementById('showextramailsettings').style.display = 'none';
	}

</script>
