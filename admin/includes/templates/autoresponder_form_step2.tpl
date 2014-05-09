<form method="post" action="index.php?Page=Autoresponders&Action=%%GLOBAL_Action%%" onsubmit="return CheckForm()">
	<table cellspacing="0" cellpadding="3" width="95%" align="center">
		<tr>
			<td class="heading1">
				%%GLOBAL_Heading%%
			</td>
		</tr>
		<tr>
			<td class="body">
				%%GLOBAL_Intro%%<br>
				%%GLOBAL_Message%%
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
				<br />
				&nbsp;
				<table border="0" cellspacing="0" cellpadding="2" width="100%" class="panel">
					<tr>
						<td colspan="2" class="heading2">
							&nbsp;&nbsp;%%LNG_AutoresponderDetails%%
						</td>
					</tr>
					<tr>
						<td width="200" class="FieldLabel">
							%%TPL_Required%%
							%%LNG_AutoresponderName%%:&nbsp;
						</td>
						<td>
							<input type="text" name="name" class="field250" value="%%GLOBAL_Name%%">&nbsp;%%LNG_HLP_AutoresponderName%%
						</td>
					</tr>
					<tr>
						<td width="200" class="FieldLabel">
							%%TPL_Not_Required%%
							%%LNG_MatchEmail%%:&nbsp;
						</td>
						<td>
							<input type="text" name="emailaddress" value="%%GLOBAL_emailaddress%%" class="field250">&nbsp;%%LNG_HLP_MatchEmail%%
						</td>
					</tr>
					<tr>
						<td width="200" class="FieldLabel">
							%%TPL_Not_Required%%
							%%LNG_MatchFormat%%:&nbsp;
						</td>
						<td>
							<select name="format" class="field250">
								%%GLOBAL_FormatList%%&nbsp;
							</select>&nbsp;%%LNG_HLP_MatchFormat%%
						</td>
					</tr>
					<tr>
						<td width="200" class="FieldLabel">
							%%TPL_Not_Required%%
							%%LNG_MatchConfirmedStatus%%:&nbsp;
						</td>
						<td>
							<select name="confirmed" class="field250">
								%%GLOBAL_ConfirmList%%
							</select>&nbsp;%%LNG_HLP_MatchConfirmedStatus%%
						</td>
					</tr>
					<tr>
						<td width="200" class="FieldLabel">
							%%TPL_Not_Required%%
							%%LNG_Autoresponder_ClickedOnLink%%:
						</td>
						<td>
							<label for="clickedlink"><input type="checkbox" name="clickedlink" id="clickedlink" value="1" %%GLOBAL_clickedlink%% onClick="javascript: enable_ClickedLink(this, 'clicklink', 'linkid', '%%LNG_LoadingMessage%%')">&nbsp;%%LNG_Autoresponder_YesFilterByLink%%</label>
							&nbsp;%%LNG_HLP_Autoresponder_ClickedOnLink%%
							<br/>
							<div id="clicklink" style="display: %%GLOBAL_clickedlinkdisplay%%">
								<table border="0" cellspacing="0" cellpadding="0">
									<tr>
										<td valign="middle">
											<img src="images/nodejoin.gif" width="20" height="20" border="0">
										</td>
										<td>
											<select name="linkid" id="linkid"%%GLOBAL_LinkChange%%>
												%%GLOBAL_ClickedLinkOptions%%
											</select>
										</td>
									</tr>
								</table>
							</div>
						</td>
					</tr>
					<tr>
						<td width="200" class="FieldLabel">
							%%TPL_Not_Required%%
							%%LNG_Autoresponder_OpenedNewsletter%%:
						</td>
						<td>
							<label for="openednewsletter"><input type="checkbox" name="openednewsletter" id="openednewsletter" value="1" %%GLOBAL_openednewsletter%% onClick="javascript: enable_OpenedNewsletter(this, 'opennews', 'newsletterid', '%%LNG_LoadingMessage%%')">&nbsp;%%LNG_Autoresponder_YesFilterByOpenedNewsletter%%</label>
							&nbsp;%%LNG_HLP_Autoresponder_OpenedNewsletter%%
							<br/>
							<div id="opennews" style="display: %%GLOBAL_openednewsletterdisplay%%">
								<table border="0" cellspacing="0" cellpadding="0">
									<tr>
										<td valign="middle">
											<img src="images/nodejoin.gif" width="20" height="20" border="0">
										</td>
										<td>
											<select name="newsletterid" id="newsletterid"%%GLOBAL_NewsletterChange%%>
												%%GLOBAL_OpenedNewsletterOptions%%
											</select>
										</td>
									</tr>
								</table>
							</div>
						</td>
					</tr>
					%%GLOBAL_CustomFieldInfo%%
					<tr>
						<td>
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
		var f = document.forms[0];
		if (f.name.value == '') {
			alert("%%LNG_EnterAutoresponderName%%");
			f.name.focus();
			return false;
		}
	}
</script>
