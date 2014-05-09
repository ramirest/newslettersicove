<form method="post" action="index.php?Page=CustomFields&Action=%%GLOBAL_Action%%" onsubmit="return CheckForm()">
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
				<input class="formbutton" type="button" value="%%LNG_Cancel%%" onClick='if(confirm("%%GLOBAL_CancelButton%%")) { document.location="index.php?Page=CustomFields" }'>
				<input class="formbutton" type="submit" value="%%LNG_Next%%">
				<br />
				&nbsp;
				<table border="0" cellspacing="0" cellpadding="2" width="100%" class="panel" id="customFieldsTable">
					<tr>
						<td colspan="2" class="heading2">
							&nbsp;&nbsp;%%GLOBAL_CustomFieldDetails%%
						</td>
					</tr>
					<tr>
						<td class="FieldLabel">
							%%TPL_Required%%
							%%LNG_CustomFieldName%%:&nbsp;
						</td>
						<td>
							<input type="text" name="FieldName" class="field250" value="%%GLOBAL_FieldName%%">&nbsp;%%LNG_HLP_CustomFieldName%%
						</td>
					</tr>
					<tr>
						<td class="FieldLabel">
							%%TPL_Not_Required%%
							%%LNG_CustomFieldRequired%%:&nbsp;
						</td>
						<td>
							<label for="FieldRequired"><input type="checkbox" id="FieldRequired" name="FieldRequired"%%GLOBAL_FieldRequired%%>%%LNG_CustomFieldRequiredExplain%%</label>
						</td>
					</tr>
					%%GLOBAL_SubForm%%
					<tr>
						<td>
							&nbsp;
						</td>
						<td height="35">
							<input class="formbutton" type="button" value="%%LNG_Cancel%%" onClick='if(confirm("%%GLOBAL_CancelButton%%")) { document.location="index.php?Page=CustomFields" }'>
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
		if (f.FieldName.value == '') {
			alert("%%LNG_EnterCustomFieldName%%");
			f.FieldName.focus();
			return false;
		}
	}
</script>
