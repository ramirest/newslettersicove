<form method="post" action="index.php?Page=Send&Action=Step4" onSubmit="return CheckForm();">
	<table cellspacing="0" cellpadding="3" width="95%" align="center">
		<tr>
			<td class="heading1">
				%%LNG_Send_Step3%%
			</td>
		</tr>
		<tr>
			<td class="body">
				%%LNG_Send_Step3_Intro%%
			</td>
		</tr>
		<tr>
			<td>
				%%GLOBAL_Message%%
			</td>
		</tr>
		<tr>
			<td>
				<input class="formbutton" type="button" value="%%LNG_Cancel%%" onClick='if(confirm("%%LNG_Send_CancelPrompt%%")) { document.location="index.php?Page=Newsletters" }'>
				<input class="formbutton" type="submit" value="%%LNG_Next%%">
				<input type="hidden" name="sendcharset" value="%%GLOBAL_SendCharset%%">
				<br />
				&nbsp;
				<table border="0" cellspacing="0" cellpadding="2" width="100%" class="panel">
				%%GLOBAL_CronOptions%%
					<tr>
						<td colspan="2" class="heading2">
							&nbsp;&nbsp;%%LNG_NewsletterDetails%%
						</td>
					</tr>
					<tr>
						<td width="200" class="FieldLabel">
							%%TPL_Required%%%%LNG_SendNewsletter%%:&nbsp;
						</td>
						<td>
							<select name="newsletter" style="margin-top:4px">
								%%GLOBAL_NewsletterList%%
							</select>&nbsp;
							%%LNG_HLP_SendNewsletter%%<a href="#" onclick="javascript: PreparePreview(); return false;"><img src="images/magnify.gif" border="0">&nbsp;&nbsp;%%LNG_Preview%%</a>
						</td>
					</tr>
					<tr>
						<td width="200" class="FieldLabel">
							%%TPL_Required%%
							%%LNG_SendFromName%%:&nbsp;
						</td>
						<td>
							<input type="text" name="sendfromname" value="%%GLOBAL_SendFromName%%" class="field250">%%LNG_HLP_SendFromName%%
						</td>
					</tr>
					<tr>
						<td width="200" class="FieldLabel">
							%%TPL_Required%%
							%%LNG_SendFromEmail%%:&nbsp;
						</td>
						<td>
							<input type="text" name="sendfromemail" value="%%GLOBAL_SendFromEmail%%" class="field250">%%LNG_HLP_SendFromEmail%%
						</td>
					</tr>
					<tr>
						<td width="200" class="FieldLabel">
							%%TPL_Required%%
							%%LNG_ReplyToEmail%%:&nbsp;
						</td>
						<td>
							<input type="text" name="replytoemail" value="%%GLOBAL_ReplyToEmail%%" class="field250">%%LNG_HLP_ReplyToEmail%%
						</td>
					</tr>
					<tr style="display: %%GLOBAL_ShowBounceInfo%%">
						<td width="200" class="FieldLabel">
							%%TPL_Required%%
							%%LNG_BounceEmail%%:&nbsp;
						</td>
						<td>
							<input type="text" name="bounceemail" value="%%GLOBAL_BounceEmail%%" class="field250">%%LNG_HLP_BounceEmail%%
						</td>
					</tr>
					<tr style="display: %%GLOBAL_DisplayNameOptions%%">
						<td class="FieldLabel">
							%%TPL_Not_Required%%
							%%LNG_SendTo_FirstName%%:
						</td>
						<td>
							<select name="to_firstname">
								<option value="0">%%LNG_SelectNameOption%%</option>
								%%GLOBAL_NameOptions%%
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
								<option value="0">%%LNG_SelectNameOption%%</option>
								%%GLOBAL_NameOptions%%
							</select>&nbsp;&nbsp;%%LNG_HLP_SendTo_LastName%%
						</td>
					</tr>
					<tr>
						<td width="200" class="FieldLabel">
							%%TPL_Not_Required%%
							%%LNG_SendMultipart%%:&nbsp;
						</td>
						<td>
							<label for="sendmultipart"><input type="checkbox" name="sendmultipart" id="sendmultipart" value="1" CHECKED>&nbsp;%%LNG_SendMultipartExplain%%</label>&nbsp;%%LNG_HLP_SendMultipart%%
						</td>
					</tr>
					<tr>
						<td width="200" class="FieldLabel">
							%%TPL_Not_Required%%
							%%LNG_TrackOpens%%:&nbsp;
						</td>
						<td>
							<label for="trackopens"><input type="checkbox" name="trackopens" id="trackopens" value="1" CHECKED>&nbsp;%%LNG_TrackOpensExplain%%</label>&nbsp;%%LNG_HLP_TrackOpens%%
						</td>
					</tr>
					<tr>
						<td width="200" class="FieldLabel">
							%%TPL_Not_Required%%
							%%LNG_TrackLinks%%:&nbsp;
						</td>
						<td>
							<label for="tracklinks"><input type="checkbox" name="tracklinks" id="tracklinks" value="1" CHECKED>&nbsp;%%LNG_TrackLinksExplain%%</label>&nbsp;%%LNG_HLP_TrackLinks%%
						</td>
					</tr>
					<tr>
						<td width="200" class="FieldLabel">
							%%TPL_Not_Required%%
							%%LNG_EmbedImages%%:&nbsp;
						</td>
						<td>
							<label for="embedimages"><input type="checkbox" name="embedimages" id="embedimages" value="1" CHECKED>&nbsp;%%LNG_EmbedImagesExplain%%</label>&nbsp;%%LNG_HLP_EmbedImages%%
						</td>
					</tr>
					<tr>
						<td>
							&nbsp;
						</td>
						<td height="30">
							<input class="formbutton" type="button" value="%%LNG_Cancel%%" onClick='if(confirm("%%LNG_Send_CancelPrompt%%")) { document.location="index.php?Page=Newsletters" }'>
							<input class="formbutton" type="submit" value="%%LNG_Next%%">
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</form>

<script language="javascript">
	var form = document.forms[0];

	function ShowSendTime(chkbox) {
		if (chkbox.checked) {
			document.getElementById('show_sendtime').style.display='none';
			document.getElementById('show_senddate').style.display='none';
		} else {
			document.getElementById('show_sendtime').style.display='';
			document.getElementById('show_senddate').style.display='';
		}
	}
	function PreparePreview() {
		var baseurl = "index.php?Page=Newsletters&Action=View&id=";
		if (form.newsletter.selectedIndex < 0) {
			alert("%%LNG_SelectNewsletterPrompt%%");
			form.newsletter.focus();
			return false;
		}
		if (form.newsletter.length > 1) {
			if (form.newsletter.selectedIndex == 0) {
				alert("%%LNG_SelectNewsletterPreviewPrompt%%");
				form.newsletter.focus();
				return false;
			}
		}
		var realId = form.newsletter[form.newsletter.selectedIndex].value;
		window.open(baseurl + realId , "pp");
	}

	function CheckForm() {
		var f = document.forms[0];

		newsletter_check = f.newsletter.selectedIndex;
		if (newsletter_check < 1) {
			alert("%%LNG_SelectNewsletterPrompt%%");
			form.newsletter.focus();
			return false;
		}

		if (f.sendfromname.value == '') {
			alert("%%LNG_EnterSendFromName%%");
			f.sendfromname.focus();
			return false;
		}

		if (f.sendfromemail.value == '') {
			alert("%%LNG_EnterSendFromEmail%%");
			f.sendfromemail.focus();
			return false;
		}

		if (f.replytoemail.value == '') {
			alert("%%LNG_EnterReplyToEmail%%");
			f.replytoemail.focus();
			return false;
		}

		if (f.bounceemail.value == '') {
			alert("%%LNG_EnterBounceEmail%%");
			f.bounceemail.focus();
			return false;
		}


		return true;
	}
</script>