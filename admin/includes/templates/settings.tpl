<form name="settings" method="post" action="index.php?Page=%%PAGE%%&%%GLOBAL_FormAction%%" onsubmit="return CheckForm();">
<table cellspacing="0" cellpadding="3" width="95%" align="center">
	<tr>
		<td class="heading1">%%LNG_Settings%%</td>
	</tr>
	<tr>
		<td class="body">%%LNG_Help_Settings%%</td>
	</tr>
	<tr>
		<td>
			%%GLOBAL_Message%%
			<span style="display: %%GLOBAL_DisplayDbUpgrade%%">
				%%GLOBAL_DbUpgradeMessage%%
			</span>
		</td>
	</tr>
	<tr>
		<td>
			%%GLOBAL_CronWarning%%
		</td>
	</tr>
	<tr>
		<td class="body">
			<input class="formbutton" type="button" value="%%LNG_Cancel%%" onClick="ConfirmCancel()">
			<input class="formbutton" type="submit" value="%%LNG_Save%%">
		</td>
	</tr>
	<tr>
		<td>
			<br>
			<table border="0" cellspacing="0" cellpadding="2" width="100%" class=panel>
				<tr>
					<td colspan="2" class="heading2">
						&nbsp;&nbsp;%%LNG_Miscellaneous%%
					</td>
				</tr>
				<tr>
					<td width="200" class="FieldLabel">
						%%TPL_Required%%
						%%LNG_ApplicationURL%%:
					</td>
					<td>
						<input type="text" name="application_url" value="%%GLOBAL_ApplicationURL%%" class="field250"> %%LNG_HLP_ApplicationURL%%
					</td>
				</tr>
				<tr>
					<td class="FieldLabel">
						<span class=required>*</span> %%LNG_ApplicationEmail%%:
					</td>
					<td>
						<input type="text" name="email_address" value="%%GLOBAL_EmailAddress%%" class="field250"> %%LNG_HLP_ApplicationEmail%%
					</td>
				</tr>
				<tr>
					<td class="FieldLabel">
						%%TPL_Not_Required%%
						%%LNG_ForceUnsubLink%%:
					</td>
					<td>
						<label for="force_unsublink"><input type="checkbox" name="force_unsublink" id="force_unsublink" value="1"%%GLOBAL_ForceUnsubLink%%>%%LNG_ForceUnsubLinkExplain%%</label> %%LNG_HLP_ForceUnsubLink%%
					</td>
				</tr>
				<tr>
					<td class="FieldLabel">
						%%TPL_Not_Required%%
						%%LNG_IpTracking%%:
					</td>
					<td>
						<label for="iptracking"><input type="checkbox" name="iptracking" id="iptracking" value="1"%%GLOBAL_IpTracking%%>%%LNG_IpTrackingExplain%%</label> %%LNG_HLP_IpTracking%%
					</td>
				</tr>
				<tr>
					<td class="FieldLabel">
						%%TPL_Not_Required%%
						%%LNG_CronEnabled%%:
					</td>
					<td>
						<label for="cron_enabled"><input type="checkbox" name="cron_enabled" id="cron_enabled" value="1"%%GLOBAL_CronEnabled%%>%%LNG_CronEnabledExplain%%</label> %%LNG_HLP_CronEnabled%%
					</td>
				</tr>
				<tr>
					<td width="200" class="FieldLabel">
						%%TPL_Not_Required%%
						%%LNG_CronPath%%:
					</td>
					<td>
						<input type="text" name="cronpath" value="%%GLOBAL_CronPath%%" class="field250" readonly onFocus="select(this);"> %%LNG_HLP_CronPath%%
					</td>
				</tr>
				<tr>
					<td class="FieldLabel">
						%%TPL_Not_Required%%
						%%LNG_ServerTimeZone%%:
					</td>
					<td valign="top">
						<input type="hidden" name="servertimezone" value="%%GLOBAL_ServerTimeZone%%">
						%%GLOBAL_ServerTimeZoneDescription%%&nbsp;&nbsp;&nbsp;%%LNG_HLP_ServerTimeZone%%
					</td>
				</tr>
				<tr>
					<td class="FieldLabel">
						%%TPL_Not_Required%%
						%%LNG_CurrentServerTime%%:
					</td>
					<td>
						%%GLOBAL_ServerTime%%
					</td>
				</tr>
				<tr>
					<td colspan="2" class="EmptyRow">
						&nbsp;
					</td>
				</tr>
				<tr>
					<td colspan="2" class="heading2">
						&nbsp;&nbsp;%%LNG_EmailSettings%%
					</td>
				</tr>
				<tr>
					<td class="FieldLabel">
						%%TPL_Not_Required%%
						%%LNG_Charset%%:
					</td>
					<td>
						<input type="hidden" name="defaultcharset" value="%%GLOBAL_DefaultCharset%%">
						%%GLOBAL_CharsetDescription%%&nbsp;&nbsp;&nbsp;%%LNG_HLP_Charset%%
					</td>
				</tr>
				<tr>
					<td class="FieldLabel">
						%%TPL_Not_Required%%
						%%LNG_MaxHourlyRate%%:
					</td>
					<td>
						<input type="text" name="maxhourlyrate" value="%%GLOBAL_MaxHourlyRate%%" class="field250"> %%LNG_HLP_MaxHourlyRate%%
					</td>
				</tr>
				<tr>
					<td class="FieldLabel">
						%%TPL_Not_Required%%
						%%LNG_MaxOverSize%%:
					</td>
					<td>
						<input type="text" name="maxoversize" value="%%GLOBAL_MaxOverSize%%" class="field250"> %%LNG_HLP_MaxOverSize%%
					</td>
				</tr>
				<tr>
					<td class="FieldLabel">
						%%TPL_Not_Required%%
						%%LNG_MaxImageWidth%%:
					</td>
					<td>
						<input type="text" name="max_imagewidth" value="%%GLOBAL_MaxImageWidth%%" class="field250"> %%LNG_HLP_MaxImageWidth%%
					</td>
				</tr>
				<tr>
					<td class="FieldLabel">
						%%TPL_Not_Required%%
						%%LNG_MaxImageHeight%%:
					</td>
					<td>
						<input type="text" name="max_imageheight" value="%%GLOBAL_MaxImageHeight%%" class="field250"> %%LNG_HLP_MaxImageHeight%%
					</td>
				</tr>
				<tr>
					<td class="FieldLabel">
						%%TPL_Not_Required%%
						%%LNG_GlobalHTMLFooter%%:
					</td>
					<td>
						<textarea name="htmlfooter" rows="3" cols="28" wrap="virtual" style="width: 250px;">%%GLOBAL_HTMLFooter%%</textarea>&nbsp;&nbsp;&nbsp;%%LNG_HLP_GlobalHTMLFooter%%
					</td>
				</tr>
				<tr>
					<td class="FieldLabel">
						%%TPL_Not_Required%%
						%%LNG_GlobalTextFooter%%:
					</td>
					<td>
						<textarea name="textfooter" rows="3" cols="28" wrap="virtual" style="width: 250px;">%%GLOBAL_TextFooter%%</textarea>&nbsp;&nbsp;&nbsp;%%LNG_HLP_GlobalTextFooter%%
					</td>
				</tr>
				<tr>
					<td colspan="2" class="EmptyRow">
						&nbsp;
					</td>
				</tr>
				<tr>
					<td colspan="2" class="heading2">
						&nbsp;&nbsp;%%LNG_SmtpServerIntro%%
					</td>
				</tr>
				<tr>
					<td class="FieldLabel">
						%%TPL_Not_Required%%
						%%LNG_UseSMTP%%:
					</td>
					<td>
						<label for="usesmtp"><input type="checkbox" name="usesmtp" id="usesmtp" value="1"%%GLOBAL_UseSMTP%% onClick="DisplaySMTP(this);">%%LNG_UseSMTPExplain%%</label> %%LNG_HLP_UseSMTP%%
					</td>
				</tr>
				<tr id="smtp1" style="display: %%GLOBAL_DisplaySMTP%%">
					<td class="FieldLabel">
						%%TPL_Not_Required%%
						%%LNG_SmtpServerName%%:
					</td>
					<td>
						<input type="text" name="smtp_server" id="smtp_server" value="%%GLOBAL_Smtp_Server%%" class="field250"> %%LNG_HLP_SmtpServerName%%
					</td>
				</tr>
				<tr id="smtp2" style="display: %%GLOBAL_DisplaySMTP%%">
					<td class="FieldLabel">
						%%TPL_Not_Required%%
						%%LNG_SmtpServerUsername%%:
					</td>
					<td>
						<input type="text" name="smtp_u" id="smtp_u" value="%%GLOBAL_Smtp_Username%%" class="field250"> %%LNG_HLP_SmtpServerUsername%%
					</td>
				</tr>
				<tr id="smtp3" style="display: %%GLOBAL_DisplaySMTP%%">
					<td class="FieldLabel">
						%%TPL_Not_Required%%
						%%LNG_SmtpServerPassword%%:
					</td>
					<td>
						<input type="password" name="smtp_p" id="smtp_p" value="%%GLOBAL_Smtp_Password%%" class="field250"> %%LNG_HLP_SmtpServerPassword%%
					</td>
				</tr>
				<tr id="smtp4" style="display: %%GLOBAL_DisplaySMTP%%">
					<td class="FieldLabel">
						%%TPL_Not_Required%%
						%%LNG_SmtpServerPort%%:
					</td>
					<td>
						<input type="text" name="smtp_port" id="smtp_port" value="%%GLOBAL_Smtp_Port%%" class="field250"> %%LNG_HLP_SmtpServerPort%%
					</td>
				</tr>
				<tr>
					<td colspan="2" class="EmptyRow">
						&nbsp;
					</td>
				</tr>
				<tr>
					<td colspan="2" class="heading2">
						&nbsp;&nbsp;%%LNG_BounceAccountIntro%%
					</td>
				</tr>
				<tr>
					<td class="FieldLabel">
						%%TPL_Not_Required%%
						%%LNG_SetDefaultBounceAccountDetails%%:
					</td>
					<td>
						<label for="setbounce"><input type="checkbox" name="setbounce" id="setbounce" value="1"%%GLOBAL_SetDefaultBounceAccountDetails%% onClick="DisplayBounceAccountDetails(this);">%%LNG_SetDefaultBounceAccountDetailsExplain%%</label> %%LNG_HLP_SetDefaultBounceAccountDetails%%
					</td>
				</tr>
				<tr id="bounce1" style="display: %%GLOBAL_DisplayDefaultBounceAccountDetails%%">
					<td class="FieldLabel">
						%%TPL_Not_Required%%
						%%LNG_DefaultBounceAddress%%:
					</td>
					<td>
						<input type="text" name="bounce_address" id="bounce_address" value="%%GLOBAL_Bounce_Address%%" class="field250"> %%LNG_HLP_DefaultBounceAddress%%
					</td>
				</tr>
				<tr id="bounce2" style="display: %%GLOBAL_DisplayDefaultBounceAccountDetails%%">
					<td class="FieldLabel">
						%%TPL_Not_Required%%
						%%LNG_DefaultBounceServer%%:
					</td>
					<td>
						<input type="text" name="bounce_server" id="bounce_server" value="%%GLOBAL_Bounce_Server%%" class="field250"> %%LNG_HLP_DefaultBounceServer%%
					</td>
				</tr>
				<tr id="bounce3" style="display: %%GLOBAL_DisplayDefaultBounceAccountDetails%%">
					<td class="FieldLabel">
						%%TPL_Not_Required%%
						%%LNG_DefaultBounceUsername%%:
					</td>
					<td>
						<input type="text" name="bounce_u" id="bounce_u" value="%%GLOBAL_Bounce_Username%%" class="field250"> %%LNG_HLP_DefaultBounceUsername%%
					</td>
				</tr>
				<tr id="bounce4" style="display: %%GLOBAL_DisplayDefaultBounceAccountDetails%%">
					<td class="FieldLabel">
						%%TPL_Not_Required%%
						%%LNG_DefaultBouncePassword%%:
					</td>
					<td>
						<input type="password" name="bounce_p" id="bounce_p" value="%%GLOBAL_Bounce_Password%%" class="field250"> %%LNG_HLP_DefaultBouncePassword%%
					</td>
				</tr>
				<tr id="bounce5" style="display: %%GLOBAL_DisplayDefaultBounceAccountDetails%%">
					<td class="FieldLabel">
						%%TPL_Not_Required%%
						%%LNG_DefaultBounceImap%%:
					</td>
					<td>
						<label for="bounce_imap"><input type="checkbox" name="bounce_imap" id="bounce_imap" value="1"%%GLOBAL_Bounce_Imap%%>%%LNG_DefaultBounceImapExplain%%</label> %%LNG_HLP_DefaultBounceImap%%
					</td>
				</tr>
				<tr id="bounce6" style="display: %%GLOBAL_DisplayDefaultBounceAccountDetails%%">
					<td class="FieldLabel">
						%%TPL_Not_Required%%
						%%LNG_UseDefaultBounceExtraSettings%%:
					</td>
					<td>
						<label for="bounce_useextrasettings"><input type="checkbox" name="bounce_useextrasettings" id="bounce_useextrasettings" value="1"%%GLOBAL_Bounce_ExtraSettings%% onClick="DisplayExtraMail(this);">%%LNG_UseDefaultBounceExtraSettingsExplain%%</label> %%LNG_HLP_UseDefaultBounceExtraSettings%%
					</td>
				</tr>
				<tr id="bounce7" style="display: %%GLOBAL_DisplayDefaultBounceAccountExtraSettings%%">
					<td class="FieldLabel">
						%%TPL_Not_Required%%
						%%LNG_DefaultBounceExtraSettings%%:
					</td>
					<td>
						<input type="text" name="bounce_extrasettings" id="bounce_extrasettings" value="%%GLOBAL_Bounce_Extrasettings%%" class="field250"> %%LNG_HLP_DefaultBounceExtraSettings%%
					</td>
				</tr>
				<tr>
					<td colspan="2" class="EmptyRow">
						&nbsp;
					</td>
				</tr>
				<tr>
					<td colspan="2" class="heading2">
						&nbsp;&nbsp;%%LNG_DatabaseIntro%%
					</td>
				</tr>
				<tr>
					<td class="FieldLabel">
						%%TPL_Not_Required%%
						%%LNG_DatabaseType%%:
					</td>
					<td class=body>
						[%%GLOBAL_DatabaseType%%]
					</td>
				</tr>
				<tr>
					<td class="FieldLabel">
						%%TPL_Required%%
						%%LNG_DatabaseUser%%:
					</td>
					<td>
						<input type="text" name="database_u" value="%%GLOBAL_DatabaseUser%%" class="field250" readonly onFocus="select(this);"> %%LNG_HLP_DatabaseUser%%
					</td>
				</tr>
				<tr>
					<td class="FieldLabel">
						%%TPL_Not_Required%%
						%%LNG_DatabasePassword%%:
					</td>
					<td>
						<input type="password" name="database_p" value="%%GLOBAL_DatabasePass%%" class="field250" readonly onFocus="select(this);"> %%LNG_HLP_DatabasePassword%%
					</td>
				</tr>
				<tr>
					<td class="FieldLabel">
						%%TPL_Required%%
						%%LNG_DatabaseHost%%:
					</td>
					<td>
						<input type="text" name="database_host" value="%%GLOBAL_DatabaseHost%%" class="field250" readonly onFocus="select(this);"> %%LNG_HLP_DatabaseHost%%
					</td>
				</tr>
				<tr>
					<td class="FieldLabel">
						%%TPL_Required%%
						%%LNG_DatabaseName%%:
					</td>
					<td>
						<input type="text" name="database_name" value="%%GLOBAL_DatabaseName%%" class="field250" readonly onFocus="select(this);"> %%LNG_HLP_DatabaseName%%
					</td>
				</tr>
				<tr>
					<td class="FieldLabel">
						%%TPL_Not_Required%%
						%%LNG_DatabaseTablePrefix%%:
					</td>
					<td>
						<input type="text" name="tableprefix" value="%%GLOBAL_DatabaseTablePrefix%%" class="field250" readonly onFocus="select(this);"> %%LNG_HLP_DatabaseTablePrefix%%
					</td>
				</tr>
				<tr>
					<td colspan="2" class="EmptyRow">
						&nbsp;
					</td>
				</tr>
				<tr>
					<td colspan="2" class="heading2">
						&nbsp;&nbsp;%%LNG_LicenseKeyIntro%%
					</td>
				</tr>
				<tr>
					<td class="FieldLabel">
						%%LNG_LicenseKey%%:
					</td>
					<td>
						Removed.
					</td>
				</tr>
				<tr>
					<td colspan="2" class="EmptyRow">
						&nbsp;
					</td>
				</tr>
				<tr>
					<td colspan="2" class="heading2">
						&nbsp;&nbsp;%%LNG_SendTestIntro%%
					</td>
				</tr>
				<tr>
					<td class="FieldLabel">
						%%TPL_Not_Required%%
						%%LNG_TestEmailAddress%%:
					</td>
					<td>
						<input type="text" name="PreviewEmail" value="" class="field250">&nbsp;<input type="button" value="%%LNG_Send%%" class="field" onclick="javascript: SendMePreview();">&nbsp;%%LNG_HLP_TestEmailAddress%%
					</td>
				</tr>
				<tr>
					<td>&nbsp</td>
					<td>
						
						<input type="hidden" name="database_type" value="%%GLOBAL_DatabaseType%%">
						<input class="formbutton" type="button" value="%%LNG_Cancel%%" onClick="ConfirmCancel()">
						<input class="formbutton" type="submit" value="%%LNG_Save%%">
					</td>
				</tr>
				<tr>
					<td>&nbsp;
					</td>
					<td>
						%%LNG_RunningVersion%% %%LNG_SENDSTUDIO_VERSION%%
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</form>

	<script language="javascript">
	function ConfirmCancel()
	{
		if(confirm('Are you sure you want to cancel?'))
		{
			document.location.href='index.php';
		}
		else
		{
			return false;
		}
	}

	function CheckForm() {
		f = document.forms[0];
		smtp_check = document.getElementById('usesmtp');
		if (!smtp_check.checked) {
			document.getElementById('smtp_server').value = '';
			document.getElementById('smtp_u').value = '';
			document.getElementById('smtp_p').value = '';
			document.getElementById('smtp_port').value = '';
		}
		return true;
	}

	function SendMePreview() {
		f = document.forms[0];
		if (f.PreviewEmail.value == "") {
			alert("%%LNG_EnterPreviewEmail%%");
			f.PreviewEmail.focus();
			return false;
		}
		var top = screen.height / 2 - (100);
		var left = screen.width / 2 - (200);

		f.target = "previewWin";
		window.open("","previewWin","left=" + left + ",top="+top+",toolbar=false,status=no,directories=false,menubar=false,scrollbars=false,resizable=false,copyhistory=false,width=400,height=200");

		prevAction = f.action;
		f.action = "index.php?Page=Settings&Action=SendPreview";
		f.submit();

		f.target = "";
		f.action = prevAction;
	}

	function DisplaySMTP(fld) {
		if (fld.checked) {
			document.getElementById('smtp1').style.display = '';
			document.getElementById('smtp2').style.display = '';
			document.getElementById('smtp3').style.display = '';
			document.getElementById('smtp4').style.display = '';
			return;
		}
		document.getElementById('smtp1').style.display = 'none';
		document.getElementById('smtp2').style.display = 'none';
		document.getElementById('smtp3').style.display = 'none';
		document.getElementById('smtp4').style.display = 'none';
	}

	function DisplayBounceAccountDetails(fld) {
		if (fld.checked) {
			newdisplay = '';
		} else {
			newdisplay = 'none';
		}
		for (i = 1; i <= 6; i++) {
			x = 'bounce' + i;
			document.getElementById(x).style.display = newdisplay;
		}

		if (!fld.checked) {
			document.getElementById('bounce7').style.display = 'none';
			return;
		}

		if (document.getElementById('bounce_useextrasettings').checked) {
			document.getElementById('bounce7').style.display = '';
		} else {
			document.getElementById('bounce7').style.display = 'none';
		}
	}

	function DisplayExtraMail(fld) {
		if (fld.checked) {
			document.getElementById('bounce7').style.display = '';
			return;
		}
		document.getElementById('bounce7').style.display = 'none';
	}

	function UpgradeDb()
	{
		f = document.forms[0];
		var top = screen.height / 2 - (100);
		var left = screen.width / 2 - (200);

		f.target = "upgradeDb";
		window.open("","upgradeDb","left=" + left + ",top="+top+",toolbar=false,status=no,directories=false,menubar=false,scrollbars=false,resizable=false,copyhistory=false,width=400,height=200");

		prevAction = f.action;
		f.action = "index.php?Page=Settings&Action=UpgradeDb";
		f.submit();

		f.target = "";
		f.action = prevAction;
	}

	</script>

%%GLOBAL_ExtraScript%%
