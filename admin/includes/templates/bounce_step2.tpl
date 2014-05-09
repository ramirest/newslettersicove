<form method="post" action="index.php?Page=Bounce&Action=Step3" onsubmit="return CheckForm();">
	<table cellspacing="0" cellpadding="3" width="95%" align="center">
		<tr>
			<td class="heading1">
				%%LNG_Bounce_Step2%%
			</td>
		</tr>
		<tr>
			<td class="body">
				%%LNG_Bounce_Step2_Intro%%
			</td>
		</tr>
		<tr>
			<td>
				%%GLOBAL_Message%%
			</td>
		</tr>
		<tr>
			<td>
				<input class="formbutton" type="button" value="%%LNG_Cancel%%" onClick='if(confirm("%%LNG_Bounce_CancelPrompt%%")) { document.location="index.php?Page=Bounce" }'>
				<input class="formbutton" type="submit" value="%%LNG_Next%%">
				<br />
				&nbsp;
				<table border="0" cellspacing="0" cellpadding="2" width="100%" class="panel">
					<tr>
						<td colspan="2" class="heading2">
							&nbsp;&nbsp;%%LNG_BounceAccountDetails%%
						</td>
					</tr>
					<tr>
						<td class="FieldLabel">
							%%TPL_Required%%
							%%LNG_BounceServer%%:&nbsp;
						</td>
						<td>
							<input type="text" name="BounceServer" class="field250" value="%%GLOBAL_BounceServer%%">&nbsp;%%LNG_HLP_BounceServer%%
						</td>
					</tr>
					<tr>
						<td class="FieldLabel">
							%%TPL_Required%%
							%%LNG_BounceUsername%%:&nbsp;
						</td>
						<td>
							<input type="text" name="BounceU" class="field250" value="%%GLOBAL_BounceUsername%%">&nbsp;%%LNG_HLP_BounceUsername%%
						</td>
					</tr>
					<tr>
						<td class="FieldLabel">
							%%TPL_Required%%
							%%LNG_BouncePassword%%:&nbsp;
						</td>
						<td>
							<input type="password" name="BounceP" class="field250" value="%%GLOBAL_BouncePassword%%">&nbsp;%%LNG_HLP_BouncePassword%%
						</td>
					</tr>
					<tr>
						<td class="FieldLabel">
							%%TPL_Not_Required%%
							%%LNG_IMAPAccount%%:&nbsp;
						</td>
						<td>
							<label for="IMAPAccount"><input type="checkbox" name="IMAPAccount" id="IMAPAccount" value="1" %%GLOBAL_IMAPAccount%%>%%LNG_IMAPAccountExplain%%</label> %%LNG_HLP_IMAPAccount%%
						</td>
					</tr>
					<tr>
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
						<td class="FieldLabel">
							%%TPL_Not_Required%%
							%%LNG_SaveBounceServerDetails%%:&nbsp;
						</td>
						<td>
							<label for="savebounceserverdetails"><input type="checkbox" name="savebounceserverdetails" id="savebounceserverdetails" value="1">%%LNG_SaveBounceServerDetailsExplain%%</label> %%LNG_HLP_SaveBounceServerDetails%%
						</td>
					</tr>
					<tr>
						<td>
							&nbsp;
						</td>
						<td>
							<input class="formbutton" type="button" value="%%LNG_Cancel%%" onClick='if(confirm("%%LNG_Bounce_CancelPrompt%%")) { document.location="index.php?Page=Bounce" }'>
							<input class="formbutton" type="submit" value="%%LNG_Next%%">
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
		if (f.BounceServer.value == "") {
			alert("%%LNG_EnterBounceServer%%");
			f.BounceServer.focus();
			return false;
		}

		if (f.BounceU.value == "") {
			alert("%%LNG_EnterBounceUsername%%");
			f.BounceU.focus();
			return false;
		}

		if (f.BounceP.value == "") {
			alert("%%LNG_EnterBouncePassword%%");
			f.BounceP.focus();
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
