<form method="post" action="index.php?Page=Forms&Action=%%GLOBAL_Action%%" onsubmit="return CheckForm();">
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
				<input class="formbutton" type="button" value="%%LNG_Cancel%%" onClick='if(confirm("%%GLOBAL_CancelButton%%")) { document.location="index.php?Page=Forms" }'>
				<input class="formbutton" type="submit" value="%%LNG_Save%%">
				<br />
				&nbsp;
				<table border="0" cellspacing="0" cellpadding="2" width="100%" class="panel">
					<tr>
						<td colspan="2" class="heading2">
							&nbsp;&nbsp;%%LNG_FormErrorPageOptions%%
						</td>
					</tr>
					<tr>
						<td class="FieldLabel" width="200">
							%%TPL_Not_Required%%
							%%LNG_ErrorPageHTML%%:&nbsp;
						</td>
						<td align="left">
							<table width="100%" border="0">
									<tr>
										<td width="20">
											<input onClick="document.getElementById('errorurlField').style.display = 'none'; document.getElementById('errorhtmlField').style.display = '';" id="errorhtmlRadio" name="contentType" type="radio"%%GLOBAL_ErrorPageHTMLField%%>
										</td>
										<td>
											<label for="errorhtmlRadio">%%LNG_HTML_Using_Editor%%</label><br>
										</td>
									</tr>
									<tr>
										<td width="20">
											<input onClick="document.getElementById('errorurlField').style.display = ''; document.getElementById('errorhtmlField').style.display = 'none';" id="errorurlRadio" name="contentType" type="radio"%%GLOBAL_ErrorPageURLField%%>
										</td>
										<td>
											<label for="errorurlRadio">%%LNG_Editor_Use_URL%%</label><br>
										</td>
									</tr>

									<tr id="errorurlField" style="display: %%GLOBAL_ErrorPageUrlStyle%%">
										<td>&nbsp;</td>
										<td>
											&nbsp;&nbsp;&nbsp;<input type="text" name="errorpageurl" value="%%GLOBAL_ErrorPageURL%%" class="field250">
										</td>
									</tr>
							</table>
							<br>
							<div id="errorhtmlField" style="display: %%GLOBAL_ErrorPageHTMLStyle%%">
								%%GLOBAL_ErrorHTML%%
							</div>
						</td>
					</tr>
					%%GLOBAL_EditFormHTML%%
					<tr>
						<td height="35">
							&nbsp;
						</td>
						<td>
							<input class="formbutton" type="button" value="%%LNG_Cancel%%" onClick='if(confirm("%%GLOBAL_CancelButton%%")) { document.location="index.php?Page=Forms" }'>
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
		return true;
	}
</script>

