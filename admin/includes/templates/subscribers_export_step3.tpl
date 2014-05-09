<form method="post" action="index.php?Page=Subscribers&Action=Export&SubAction=Step4" onsubmit="return CheckForm()">
	<table cellspacing="0" cellpadding="3" width="95%" align="center">
		<tr>
			<td class="heading1">
				%%LNG_Subscribers_Export_Step3%%
			</td>
		</tr>
		<tr>
			<td class="body">
				%%LNG_Subscribers_Export_Step3_Intro%%<br>
				
			</td>
		</tr>
		<tr>
			<td>
				%%GLOBAL_Message%%
				%%GLOBAL_SubscribersReport%%
			</td>
		</tr>
		<tr>
			<td>
				<input class="formbutton" type="button" value="%%LNG_Cancel%%" onClick='if(confirm("%%LNG_Subscribers_Export_CancelPrompt%%")) { document.location="index.php?Page=Subscribers" }'>
				<input class="formbutton" type="submit" value="%%LNG_NextButton%%">
				<br />
				&nbsp;
				<table border="0" cellspacing="0" cellpadding="2" width="100%" class="panel">
					<tr>
						<td colspan="2" class="heading2">
							&nbsp;&nbsp;%%LNG_ExportOptions%%
						</td>
					</tr>
					<tr>
						<td width="200" class="FieldLabel">
							%%TPL_Not_Required%%
							%%LNG_IncludeHeader%%
						</td>
						<td>
							<select name="includeheader">
								<option value="1">%%LNG_Yes%%</option>
								<option value="0">%%LNG_No%%</option>
							</select>&nbsp;&nbsp;%%LNG_HLP_IncludeHeader%%
						</td>
					</tr>
					<tr>
						<td width="200" class="FieldLabel">
							%%TPL_Required%%
							%%LNG_Export_FieldSeparator%%:
						</td>
						<td>
							<input type="text" name="fieldseparator" value="," class="field250">%%LNG_HLP_Export_FieldSeparator%%
						</td>
					</tr>
					<tr>
						<td width="200" class="FieldLabel">
							%%TPL_Not_Required%%
							%%LNG_FieldEnclosedBy%%:
						</td>
						<td>
							<input type="text" name="fieldenclosedby" value='"' class="field250">%%LNG_HLP_FieldEnclosedBy%%
						</td>
					</tr>
					<tr>
						<td colspan="2" class="EmptyRow">
							&nbsp;
						</td>
					</tr>
					<tr>
						<td colspan="2" class="heading2">
							&nbsp;&nbsp;%%LNG_IncludeFields%%
						</td>
					</tr>
					%%GLOBAL_FieldOptions%%
					<tr>
						<td>
							&nbsp;
						</td>
						<td height="35">
							<input class="formbutton" type="button" value="%%LNG_Cancel%%" onClick='if(confirm("%%LNG_Subscribers_Export_CancelPrompt%%")) { document.location="index.php?Page=Subscribers" }'>
							<input class="formbutton" type="submit" value="%%LNG_NextButton%%">
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

		if (f.fieldseparator.value == '') {
			alert("%%LNG_EnterFieldSeparator%%");
			f.fieldseparator.focus();
			return false;
		}

		return true;
	}
</script>