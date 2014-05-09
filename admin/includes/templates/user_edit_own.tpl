<form name="settings" method="post" action="index.php?Page=%%PAGE%%&%%GLOBAL_FormAction%%" onsubmit="return CheckForm();">
	<table cellspacing="0" cellpadding="3" width="95%" align="center">
	<tr>
		<td class="heading1">%%LNG_MyAccount%%</td>
	</tr>
	<tr>
		<td class="body">%%LNG_Help_MyAccount%%</td>
	</tr>
	<tr>
		<td>
			%%GLOBAL_Message%%
		</td>
	</tr>
	<tr>
		<td class=body>
			<input class="formbutton" type="button" value="%%LNG_Cancel%%" onClick="ConfirmCancel()">
			<input class="formbutton" type="submit" value="%%LNG_Save%%">
		</td>
	</tr>
	<tr>
		<td><br>
			<table border="0" cellspacing="0" cellpadding="2" width="100%" class=panel>
				<tr><td class=heading2 colspan=2>%%LNG_UserDetails%%</td></tr>
				<tr>
					<td class="FieldLabel">
						%%TPL_Required%%
						%%LNG_UserName%%:
					</td>
					<td>
						%%GLOBAL_UserName%%
					</td>
				</tr>
				<tr>
					<td class="FieldLabel">
						%%TPL_Required%%
						%%LNG_Password%%:
					</td>
					<td>
						<input type="password" name="ss_p" value="" class="field250">
					</td>
				</tr>
				<tr>
					<td class="FieldLabel">
						%%TPL_Required%%
						%%LNG_PasswordConfirm%%:
					</td>
					<td>
						<input type="password" name="ss_p_confirm" value="" class="field250">
					</td>
				</tr>
				<tr>
					<td class="FieldLabel">
						%%TPL_Not_Required%%
						%%LNG_FullName%%:
					</td>
					<td>
						<input type="text" name="fullname" value="%%GLOBAL_FullName%%" class="field250">
					</td>
				</tr>
				<tr>
					<td class="FieldLabel">
						%%TPL_Required%%
						%%LNG_EmailAddress%%:
					</td>
					<td>
						<input type="text" name="emailaddress" value="%%GLOBAL_EmailAddress%%" class="field250">&nbsp;%%LNG_HLP_EmailAddress%%
					</td>
				</tr>
				<tr>
					<td class="FieldLabel">
						%%TPL_Required%%
						%%LNG_TimeZone%%:
					</td>
					<td>
						<select name="usertimezone">
							%%GLOBAL_TimeZoneList%%
						</select>&nbsp;&nbsp;&nbsp;%%LNG_HLP_TimeZone%%
					</td>
				</tr>
				<tr>
					<td class="FieldLabel">
						%%TPL_Not_Required%%
						%%LNG_ShowInfoTips%%:
					</td>
					<td>
						<label for="infotips"><input type="checkbox" id="infotips" name="infotips" value="1"%%GLOBAL_InfoTipsChecked%%> %%LNG_YesShowInfoTips%%</label> %%LNG_HLP_ShowInfoTips%%
					</td>
				</tr>
				<tr>
					<td class="FieldLabel">
						%%TPL_Not_Required%%
						%%LNG_HTMLFooter%%:
					</td>
					<td>
						<textarea name="htmlfooter" rows="3" cols="28" wrap="virtual">%%GLOBAL_HTMLFooter%%</textarea>&nbsp;&nbsp;&nbsp;%%LNG_HLP_HTMLFooter%%
					</td>
				</tr>
				<tr>
					<td class="FieldLabel">
						%%TPL_Not_Required%%
						%%LNG_TextFooter%%:
					</td>
					<td>
						<textarea name="textfooter" rows="3" cols="28" wrap="virtual">%%GLOBAL_TextFooter%%</textarea>&nbsp;&nbsp;&nbsp;%%LNG_HLP_TextFooter%%
					</td>
				</tr>
				<tr style="display: %%GLOBAL_ShowSMTPInfo%%">
					<td colspan="2" class="EmptyRow">
						&nbsp;
					</td>
				</tr>
				<tr style="display: %%GLOBAL_ShowSMTPInfo%%">
					<td colspan="2" class="heading2">
						%%LNG_SmtpServerIntro%%
					</td>
				</tr>
				<tr style="display: %%GLOBAL_ShowSMTPInfo%%">
					<td class="FieldLabel">
						%%TPL_Not_Required%%
						%%LNG_SmtpServer%%:
					</td>
					<td>
						<select name="smtptype" id="smtptype" class="field250" onChange="ToggleSMTPDetails(true)">
							<option value="0">%%LNG_SmtpDefault%%</option>
							<option value="1">%%LNG_SmtpCustom%%</option>
						</select>
						%%LNG_HLP_SmtpServer%%
					</td>
				</tr>
				<tr id="smtp1" style="display:none">
					<td class="FieldLabel">
						%%TPL_Required%%
						%%LNG_SmtpServerName%%:
					</td>
					<td>
						<input type="text" name="smtpserver" value="%%GLOBAL_SmtpServer%%" class="field250"> %%LNG_HLP_SmtpServerName%%
					</td>
				</tr>
				<tr id="smtp2" style="display:none">
					<td class="FieldLabel">
						%%TPL_Not_Required%%
						%%LNG_SmtpServerUsername%%:
					</td>
					<td>
						<input type="text" name="smtpusername" value="%%GLOBAL_SmtpUsername%%" class="field250"> %%LNG_HLP_SmtpServerUsername%%
					</td>
				</tr>
				<tr id="smtp3" style="display:none">
					<td class="FieldLabel">
						%%TPL_Not_Required%%
						%%LNG_SmtpServerPassword%%:
					</td>
					<td>
						<input type="password" name="smtp_p" value="%%GLOBAL_SmtpPassword%%" class="field250"> %%LNG_HLP_SmtpServerPassword%%
					</td>
				</tr>
				<tr id="smtp4" style="display:none">
					<td class="FieldLabel">
						%%TPL_Not_Required%%
						%%LNG_SmtpServerPort%%:
					</td>
					<td>
						<input type="text" name="smtpport" value="%%GLOBAL_SmtpPort%%" class="field50"> %%LNG_HLP_SmtpServerPort%%
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>
						<input class="formbutton" type="button" value="%%LNG_Cancel%%" onClick="ConfirmCancel()">
				<input class="formbutton" type="submit" value="%%LNG_Save%%">
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<script language="javascript">

	function ConfirmCancel()
	{
		if(confirm('Are you sure you want to cancel?'))
		{
			document.location.href='index.php?Page=ManageAccount';
		}
		else
		{
			return false;
		}
	}

	function CheckForm() {
		f = document.forms[0];
		if (f.ss_p.value != "") {
			if (f.ss_p_confirm.value == "") {
				alert("%%LNG_PasswordConfirmAlert%%");
				f.ss_p_confirm.focus();
				return false;
			}
			if (f.ss_p.value != f.ss_p_confirm.value) {
				alert("%%LNG_PasswordsDontMatch%%");
				f.ss_p_confirm.select();
				f.ss_p_confirm.focus();
				return false;
			}
		}
		return true;
	}

	function ToggleSMTPDetails(Override)
	{
		var state = "%%GLOBAL_CustomSmtpServer_Display%%";

		var displaystate = "%%GLOBAL_DisplaySMTP%%";

		if(Override) {
			state = document.getElementById("smtptype").options[document.getElementById("smtptype").selectedIndex].value;
		}

		var sel = document.getElementById("smtptype");
		var s1 = document.getElementById("smtp1");
		var s2 = document.getElementById("smtp2");
		var s3 = document.getElementById("smtp3");
		var s4 = document.getElementById("smtp4");

		if(state == 0) {
			vis = "none";
		} else {
			vis = "";
		}

		sel.selectedIndex = state;
		if (displaystate == 1) {
			s1.style.display = vis;
			s2.style.display = vis;
			s3.style.display = vis;
			s4.style.display = vis;
		}
	}

	ToggleSMTPDetails(false);

</script>
</form>