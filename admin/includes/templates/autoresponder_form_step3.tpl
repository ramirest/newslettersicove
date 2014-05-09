<form method="post" action="index.php?Page=Autoresponders&Action=%%GLOBAL_Action%%" onsubmit="return CheckForm()" autocomplete="off">
	<table cellspacing="0" cellpadding="3" width="95%" align="center">
		<tr>
			<td class="heading1">
				%%GLOBAL_Heading%%
			</td>
		</tr>
		<tr>
			<td class="body">
				%%GLOBAL_Intro%%
				<br>%%GLOBAL_Message%%
			</td>
		</tr>
		<tr>
			<td>
				%%GLOBAL_CronWarning%%
			</td>
		</tr>
		<tr>
			<td>
				<input class="formbutton" type="button" value="%%LNG_Cancel%%" onClick='if(confirm("%%GLOBAL_CancelButton%%")) { document.location="index.php?Page=Autoresponders&Action=Step2&list=%%GLOBAL_List%%" }'>
				<input class="formbutton" type="submit" value="%%LNG_Next%%">
				<input type="hidden" name="charset" value="%%GLOBAL_Charset%%">
				<br />
				&nbsp;
				<table border="0" cellspacing="0" cellpadding="2" width="100%" class="panel" id="AutoresponderDetails">
					<tr>
						<td colspan="2" class="heading2">
							&nbsp;&nbsp;%%LNG_AutoresponderDetails%%
						</td>
					</tr>
					<tr>
						<td width="200" class="FieldLabel">
							%%TPL_Required%%
							%%LNG_SendFromName%%:&nbsp;
						</td>
						<td>
							<input type="text" name="sendfromname" class="field250" value="%%GLOBAL_SendFromName%%">&nbsp;%%LNG_HLP_SendFromName%%
						</td>
					</tr>
					<tr>
						<td width="200" class="FieldLabel">
							%%TPL_Required%%
							%%LNG_SendFromEmail%%:&nbsp;
						</td>
						<td>
							<input type="text" name="sendfromemail" class="field250" value="%%GLOBAL_SendFromEmail%%">&nbsp;%%LNG_HLP_SendFromEmail%%
						</td>
					</tr>
					<tr>
						<td width="200" class="FieldLabel">
							%%TPL_Required%%
							%%LNG_ReplyToEmail%%:&nbsp;
						</td>
						<td>
							<input type="text" name="replytoemail" class="field250" value="%%GLOBAL_ReplyToEmail%%">&nbsp;%%LNG_HLP_ReplyToEmail%%
						</td>
					</tr>
					<tr style="display: %%GLOBAL_ShowBounceInfo%%">
						<td width="200" class="FieldLabel">
							%%TPL_Required%%
							%%LNG_BounceEmail%%:&nbsp;
						</td>
						<td>
							<input type="text" name="bounceemail" class="field250" value="%%GLOBAL_BounceEmail%%">&nbsp;%%LNG_HLP_BounceEmail%%
						</td>
					</tr>
					<tr>
						<td width="200" class="FieldLabel">
							%%TPL_Required%%
							%%LNG_HoursDelayed%%:&nbsp;
						</td>
						<td>
							<input type="text" id="hoursaftersubscription" name="hoursaftersubscription" class="field" style="width: 40px;" value="%%GLOBAL_HoursAfterSubscription%%">&nbsp;hrs&nbsp;&nbsp;
							<select name="othertimes" id="othertimes" style="width: 177px;" onchange="changeHours(this)">
								<option value="-1">%%LNG_ChooseATime%%</option>
								<option value="0">%%LNG_Immediately%%</option>
								<option value="24">%%LNG_1Day%%</option>
								<option value="48">%%LNG_2Days%%</option>
								<option value="72">%%LNG_3Days%%</option>
								<option value="96">%%LNG_4Days%%</option>
								<option value="120">%%LNG_5Days%%</option>
								<option value="144">%%LNG_6Days%%</option>
								<option value="168">%%LNG_1Week%%</option>
								<option value="336">%%LNG_2Weeks%%</option>
								<option value="504">%%LNG_3Weeks%%</option>
								<option value="672">%%LNG_1Month%%</option>
								<option value="1344">%%LNG_2Months%%</option>
								<option value="2016">%%LNG_3Months%%</option>
								<option value="2688">%%LNG_4Months%%</option>
								<option value="3360">%%LNG_5Months%%</option>
								<option value="4032">%%LNG_6Months%%</option>
								<option value="4704">%%LNG_7Months%%</option>
								<option value="5376">%%LNG_8Months%%</option>
								<option value="6048">%%LNG_9Months%%</option>
								<option value="6720">%%LNG_10Months%%</option>
								<option value="7392">%%LNG_11Months%%</option>
								<option value="8064">%%LNG_1Year%%</option>
								<option value="16128">%%LNG_2Years%%</option>
								<option value="24192">%%LNG_3Years%%</option>
							</select>
							&nbsp;
							%%LNG_HLP_HoursDelayed%%
						</td>
					</tr>
					<tr>
						<td width="200" class="FieldLabel">
							%%TPL_Required%%
							%%LNG_EmailFormat%%:&nbsp;
						</td>
						<td>
							<select name="format" onChange="adjustHtmlEmailPreferences();">
								%%GLOBAL_FormatList%%
							</select>
							&nbsp;
							%%LNG_HLP_EmailFormat%%
						</td>
					</tr>
					<tr style="display: %%GLOBAL_DisplayNameOptions%%">
						<td class="FieldLabel">
							%%TPL_Not_Required%%
							%%LNG_SendTo_FirstName%%:
						</td>
						<td>
							<select name="to_firstname">
								%%GLOBAL_FirstNameOptions%%
							</select>&nbsp;&nbsp;%%LNG_HLP_SendTo_FirstName%%
						</td>
					</tr>
					<tr style="display: %%GLOBAL_DisplayNameOptions%%">
						<td class="FieldLabel">
							%%TPL_Not_Required%%
							%%LNG_SendTo_LastName%%:
						</td>
						<td>
							<select name="to_lastname">
								%%GLOBAL_LastNameOptions%%
							</select>&nbsp;&nbsp;%%LNG_HLP_SendTo_LastName%%
						</td>
					</tr>
					<tr>
						<td width="200" class="FieldLabel">
							%%TPL_Not_Required%%
							%%LNG_AutoresponderIncludeExisting%%:&nbsp;
						</td>
						<td>
							<label for="includeexisting"><input type="checkbox" name="includeexisting" id="includeexisting" value="1">%%LNG_AutoresponderIncludeExistingExplain%%</label>&nbsp;%%LNG_HLP_AutoresponderIncludeExisting%%
						</td>
					</tr>
				</table>
				<table border="0" cellspacing="0" cellpadding="2" width="100%" class="panel" id="HTMLFormatDetails">
					<tr>
						<td colspan="2" class="heading2">
							&nbsp;&nbsp;%%LNG_HTMLFormatDetails%%
						</td>
					</tr>
					<tr>
						<td width="200" class="FieldLabel">
							%%TPL_Not_Required%%
							%%LNG_SendMultipart%%:&nbsp;
						</td>
						<td>
							<label for="multipart"><input type="checkbox" name="multipart" id="multipart" value="1" %%GLOBAL_multipart%%>&nbsp;%%LNG_SendMultipartExplain%%</label>&nbsp;%%LNG_HLP_SendMultipart%%
						</td>
					</tr>
					<tr>
						<td width="200" class="FieldLabel">
							%%TPL_Not_Required%%
							%%LNG_TrackOpens%%:&nbsp;
						</td>
						<td>
							<label for="trackopens"><input type="checkbox" name="trackopens" id="trackopens" value="1" %%GLOBAL_trackopens%%>&nbsp;%%LNG_TrackOpensExplain%%</label>&nbsp;%%LNG_HLP_TrackOpens%%
						</td>
					</tr>
					<tr>
						<td width="200" class="FieldLabel">
							%%TPL_Not_Required%%
							%%LNG_TrackLinks%%:&nbsp;
						</td>
						<td>
							<label for="tracklinks"><input type="checkbox" name="tracklinks" id="tracklinks" value="1" %%GLOBAL_tracklinks%%>&nbsp;%%LNG_TrackLinksExplain%%</label>&nbsp;%%LNG_HLP_TrackLinks%%
						</td>
					</tr>
					<tr>
						<td width="200" class="FieldLabel">
							%%TPL_Not_Required%%
							%%LNG_EmbedImages%%:&nbsp;
						</td>
						<td>
							<label for="embedimages"><input type="checkbox" name="embedimages" id="embedimages" value="1" %%GLOBAL_embedimages%%>&nbsp;%%LNG_EmbedImagesExplain%%</label>&nbsp;%%LNG_HLP_EmbedImages%%
						</td>
					</tr>
				</table>
				<table border="0" cellspacing="0" cellpadding="2" width="100%" class="panel" id="TemplateDetails" style="margin-bottom: 0;">
					<tr>
						<td colspan="2" class="heading2">
							&nbsp;&nbsp;%%LNG_TemplateDetails%%
						</td>
					</tr>
					<tr style="display: %%GLOBAL_DisplayTemplateList%%">
						<td width="200" class="FieldLabel">
							%%TPL_Not_Required%%
							%%LNG_ChooseTemplate%%:
						</td>
						<td>
							%%GLOBAL_TemplateList%%
							&nbsp;
							%%LNG_HLP_ChooseTemplate%%
						</td>
					</tr>
					<tr style="display: %%GLOBAL_DisplayTemplateList%%">
						<td width="200" class="FieldLabel">
							%%TPL_Not_Required%%
							%%LNG_Template_Preview%%:
						</td>
						<td>
							<a href="javascript:void(0);" onclick="javascript:ShowPreview();"><img id="imgPreview" src="images/nopreview.gif" width="247" height="200" style="border: 1px solid black"></a>

							<br><a href="#" onclick="javascript: ShowPreview(); return false;"><img src="images/magnify.gif" border="0" style="padding-right:5px">%%LNG_Preview_Template%%</a>
						</td>
					</tr>
				</table>
				<table border="0" cellspacing="0" cellpadding="2" width="100%" class="panel">
					<tr>
						<td width="200" class="FieldLabel">
							&nbsp;
						</td>
						<td height="30">
							<input class="formbutton" type="button" value="%%LNG_Cancel%%" onClick='if(confirm("%%GLOBAL_CancelButton%%")) { document.location="index.php?Page=Autoresponders&Action=Step2&list=%%GLOBAL_List%%" }'>
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
	}

	function changeHours(frm) {
		selected = frm.selectedIndex;
		hrs = frm[selected].value;
		if (hrs > -1) {
			document.getElementById('hoursaftersubscription').value = hrs;
		}
	}
	function adjustHtmlEmailPreferences() {
		selected = document.forms[0].format;
		if (selected.options[selected.selectedIndex].value=='t') {
			document.getElementById('AutoresponderDetails').style.marginBottom='0';
			document.getElementById('HTMLFormatDetails').style.display='none';
			document.getElementById('TemplateDetails').style.display='none';
		} else {
			document.getElementById('AutoresponderDetails').style.marginBottom='20px';
			document.getElementById('HTMLFormatDetails').style.display='';
			document.getElementById('TemplateDetails').style.display='';
		}
	}
	adjustHtmlEmailPreferences();
</script>
