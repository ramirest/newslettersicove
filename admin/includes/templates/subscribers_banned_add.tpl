<form method="post" action="index.php?Page=Subscribers&Action=Banned&SubAction=Ban" enctype="multipart/form-data" onsubmit="return CheckForm();">
	<table cellspacing="0" cellpadding="3" width="95%" align="center">
		<tr>
			<td class="heading1">
				%%LNG_Subscribers_Banned_Add%%
			</td>
		</tr>
		<tr>
			<td class="body">
				%%LNG_Subscribers_Banned_Add_Intro%%
			</td>
		</tr>
		<tr>
			<td>
				%%GLOBAL_Message%%
			</td>
		</tr>
		<tr>
			<td>
				<input class="formbutton" type="button" value="%%LNG_Cancel%%" onClick='if(confirm("%%LNG_Subscribers_Banned_CancelPrompt%%")) { document.location="index.php?Page=Subscribers&Action=Banned" }'>
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
							%%LNG_BannedEmails%%:&nbsp;
						</td>
						<td>

			<table width="100%" border="0">
				<tr>
					<td width="20">
						<input onClick="document.getElementById('fileField').style.display = 'none'; document.getElementById('emailField').style.display = '';" id="listRadio" name="bannedType" type="radio" checked>
					</td>
					<td>
						<label for="listRadio">Add banned emails using the form below</label><br>
					</td>
				</tr>
				<tr>
					<td width="20">
						<input onClick="document.getElementById('fileField').style.display = ''; document.getElementById('emailField').style.display = 'none';" id="fileRadio" name="bannedType" type="radio">
					</td>
					<td>
						<label for="fileRadio">Use an existing file</label><br>
					</td>
				</tr>

				<tr id="fileField" style="display:none;">
					<td>&nbsp;</td>
					<td>
						&nbsp;&nbsp;&nbsp;<input type="file" style="width:200px" name="BannedFile" class="field">&nbsp;%%LNG_HLP_BannedFile%%
					</td>
				</tr>
		</table>
				<br>
				<div id="emailField">
							<textarea name="BannedEmailList" style="width:250px" rows="5"></textarea>
							%%LNG_HLP_BannedEmails%%
				</div>

						</td>
					</tr>
					<!-- <tr>
						<td valign="top" class="FieldLabel">
							%%TPL_Required%%
							%%LNG_BannedFile%%:&nbsp;
						</td>
						<td>
							<input type="file" style="width:250px" name="BannedFile" class="fieldLabel">
							%%LNG_HLP_BannedFile%%
						</td>
					</tr> -->
					<tr>
						<td width="200" class="FieldLabel">
							%%TPL_Required%%
							%%LNG_BannedEmailsChooseList%%:&nbsp;
						</td>
						<td>
							<select name="list" size="7">
								%%GLOBAL_SelectList%%
							</select>
						</td>
					</tr>
					<tr>
						<td>
							&nbsp;
						</td>
						<td>
							<input class="formbutton" type="button" value="%%LNG_Cancel%%" onClick='if(confirm("%%LNG_Subscribers_Banned_CancelPrompt%%")) { document.location="index.php?Page=Subscribers&Action=Banned" }'>
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
		if (f.BannedEmailList.value == "" && f.listRadio.checked) {
			alert("%%LNG_Banned_Add_EmptyList%%");
			f.BannedEmailList.focus();
			return false;
		}

		if (f.BannedFile.value == "" && f.fileRadio.checked) {
			alert("%%LNG_Banned_Add_EmptyFile%%");
			f.BannedFile.focus();
			return false;
		}

		if (f.list.selectedIndex == -1) {
			alert("%%LNG_Banned_Add_ChooseList%%");
			f.list.focus();
			return false;
		}
		return true;
	}
</script>