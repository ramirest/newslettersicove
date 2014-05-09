<form method="post" action="index.php?Page=Templates&Action=%%GLOBAL_Action%%" onsubmit="return CheckForm()" enctype="multipart/form-data">
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
				<input class="formbutton" type="button" value="%%LNG_Cancel%%" onClick='if(confirm("%%GLOBAL_CancelButton%%")) { document.location="index.php?Page=Templates" }'>
				<input class="formbutton" type="button" value="%%LNG_Save%%" onclick='Save();'>
				<input class="formbutton_wide" type="submit" value="%%LNG_SaveAndExit%%">
				<br />
				&nbsp;
				<table border="0" cellspacing="0" cellpadding="2" width="100%" class="panel">
					<tr>
						<td colspan="2" class="heading2">
							&nbsp;&nbsp;%%LNG_EditTemplateHeading%%
						</td>
					</tr>
					<tr>
						<td colspan="2">
							%%GLOBAL_Editor%%
						</td>
					</tr>
					<tr>
						<td class="FieldLabel">
							%%TPL_Not_Required%%
							%%LNG_TemplateIsActive%%:
						</td>
						<td>
							<label for="active">
							<input type="checkbox" name="active" id="active" value="1"%%GLOBAL_IsActive%%>
							%%LNG_TemplateIsActiveExplain%%
							</label>
							%%LNG_HLP_TemplateIsActive%%
						</td>
					</tr>
					<tr style="display: %%GLOBAL_ShowGlobal%%">
						<td class="FieldLabel">
							%%TPL_Not_Required%%
							%%LNG_TemplateIsGlobal%%:
						</td>
						<td>
							<label for="isglobal">
							<input type="checkbox" name="isglobal" id="isglobal" value="1"%%GLOBAL_IsGlobal%%>
							%%LNG_TemplateIsGlobalExplain%%
							</label>
							%%LNG_HLP_TemplateIsGlobal%%
						</td>
					</tr>
					<tr>
						<td>
							&nbsp;
						</td>
						<td height="35">
							<input class="formbutton" type="button" value="%%LNG_Cancel%%" onClick='if(confirm("%%GLOBAL_CancelButton%%")) { document.location="index.php?Page=Templates" }'>
							<input class="formbutton" type="button" value="%%LNG_Save%%" onclick='Save();'>
							<input class="formbutton_wide" type="submit" value="%%LNG_SaveAndExit%%">
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</form>

<script language="javascript">

	var f = document.forms[0];

	function CheckForm() {
		return true;
	}

	function Save() {
		if (CheckForm()) {
			f.action = 'index.php?Page=Templates&Action=%%GLOBAL_SaveAction%%';
			f.submit();
		}
	}

</script>
