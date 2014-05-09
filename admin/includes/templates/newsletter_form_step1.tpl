<form method="post" action="index.php?Page=Newsletters&Action=%%GLOBAL_Action%%" onsubmit="return CheckForm()">
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
				<input class="formbutton" type="button" value="%%LNG_Cancel%%" onClick='if(confirm("%%GLOBAL_CancelButton%%")) { document.location="index.php?Page=Newsletters" }'>
				<input class="formbutton" type="submit" value="%%LNG_Next%%">
				<br />
				&nbsp;
				<table border="0" cellspacing="0" cellpadding="2" width="100%" class="panel">
					<tr>
						<td colspan="2" class="heading2">
							&nbsp;&nbsp;%%GLOBAL_NewsletterDetails%%
						</td>
					</tr>
					<tr>
						<td width="200" class="FieldLabel">
							%%TPL_Required%%
							%%LNG_NewsletterName%%:
						</td>
						<td>
							<input type="text" name="Name" class="field250" value="%%GLOBAL_Name%%">&nbsp;%%LNG_HLP_NewsletterName%%
						</td>
					</tr>
					<tr>
						<td width="200" class="FieldLabel">
							%%TPL_Required%%
							%%LNG_NewsletterFormat%%:
						</td>
						<td>
							<select name="Format">
								%%GLOBAL_FormatList%%
							</select>
							&nbsp;
							%%LNG_HLP_NewsletterFormat%%
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
							<a href="javascript:void(0);" onclick="javascript:ShowPreview();"><img id="imgPreview" src="images/nopreview.gif" width="247" height="200" style="border: 1px solid black" onerror="this.src=images/nopreview.gif"></a>

							<br><a href="#" onclick="javascript: ShowPreview(); return false;"><img src="images/magnify.gif" border="0" style="padding-right:5px">%%LNG_Preview_Template%%</a>
						</td>
					</tr>
					<tr>
						<td>
							&nbsp;
						</td>
						<td height="35">
							<input class="formbutton" type="button" value="%%LNG_Cancel%%" onClick='if(confirm("%%GLOBAL_CancelButton%%")) { document.location="index.php?Page=Newsletters" }'>
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
		if (f.Name.value == '') {
			alert("%%LNG_EnterNewsletterName%%");
			f.Name.focus();
			return false;
		}
	}
</script>
