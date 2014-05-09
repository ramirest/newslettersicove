<form method="post" action="index.php?Page=Subscribers&Action=Add&SubAction=Save&list=%%GLOBAL_list%%" onsubmit="return CheckForm()">
	<table cellspacing="0" cellpadding="3" width="95%" align="center">
		<tr>
			<td class="heading1">
				%%LNG_Subscribers_Add_Step2%%
			</td>
		</tr>
		<tr>
			<td class="body">
				%%LNG_Subscribers_Add_Step2_Intro%%
			</td>
		</tr>
		<tr>
			<td>
				%%GLOBAL_Message%%
			</td>
		</tr>
		<tr>
			<td>
				<input class="formbutton" type="button" value="%%LNG_Cancel%%" onClick='if(confirm("%%LNG_Subscribers_Add_CancelPrompt%%")) { document.location="index.php?Page=Subscribers" }'>
				<input class="formbutton" type="button" value="%%LNG_Save%%" onClick="SaveAdd();">
				%%GLOBAL_SaveExitButton%%
				<br />
				&nbsp;
				<table border="0" cellspacing="0" cellpadding="2" width="100%" class="panel">
					<tr>
						<td colspan="2" class="heading2">
							&nbsp;&nbsp;%%LNG_NewSubscriberDetails%%
						</td>
					</tr>
					<tr>
						<td width="200" class="FieldLabel">
							%%TPL_Required%%
							%%LNG_Email%%:&nbsp;
						</td>
						<td>
							<input type="text" name="emailaddress" value="%%GLOBAL_emailaddress%%" class="field250">
						</td>
					</tr>
					<tr>
						<td width="200" class="FieldLabel">
							%%TPL_Required%%
							%%LNG_Format%%:&nbsp;
						</td>
						<td>
							<select name="format" class="field250">
								%%GLOBAL_FormatList%%
							</select>&nbsp;%%LNG_HLP_Format%%
						</td>
					</tr>
					<tr>
						<td width="200" class="FieldLabel">
							%%TPL_Required%%
							%%LNG_ConfirmedStatus%%:&nbsp;
						</td>
						<td>
							<select name="confirmed" class="field250">
								%%GLOBAL_ConfirmedList%%
							</select>&nbsp;%%LNG_HLP_ConfirmedStatus%%
						</td>
					</tr>
					%%GLOBAL_CustomFieldInfo%%
					<tr>
						<td>
							&nbsp;
						</td>
						<td height="35">
							<input class="formbutton" type="button" value="%%LNG_Cancel%%" onClick='if(confirm("%%LNG_Subscribers_Add_CancelPrompt%%")) { document.location="index.php?Page=Subscribers" }'>
							<input class="formbutton" type="button" value="%%LNG_Save%%" onClick="SaveAdd();">
							%%GLOBAL_SaveExitButton%%
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</form>
<script language="javascript">
	function SaveAdd() {
		if (!CheckForm()) {
			return false;
		}
		var f = document.forms[0];
		f.action = 'index.php?Page=Subscribers&Action=Add&SubAction=SaveAdd&list=%%GLOBAL_list%%';
		f.submit();
	}

	function CheckForm() {
		var f = document.forms[0];
		if (f.emailaddress.value == "") {
			alert("%%LNG_Subscribers_EnterEmailAddress%%");
			f.emailaddress.focus();
			return false;
		}
		%%GLOBAL_ExtraJavascript%%
		return true;
	}

</script>