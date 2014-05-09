<form method="post" action="index.php?Page=Subscribers&Action=Remove&SubAction=Step3&list=%%GLOBAL_list%%" enctype="multipart/form-data" onsubmit="return CheckForm();">
	<table cellspacing="0" cellpadding="3" width="95%" align="center">
		<tr>
			<td class="heading1">
				%%LNG_Subscribers_Remove_Step2%%
			</td>
		</tr>
		<tr>
			<td class="body">
				%%LNG_Subscribers_Remove_Step2_Intro%%
			</td>
		</tr>
		<tr>
			<td>
				%%GLOBAL_Message%%
			</td>
		</tr>
		<tr>
			<td>
				<input class="formbutton" type="button" value="%%LNG_Cancel%%" onClick='if(confirm("%%LNG_Subscribers_Remove_CancelPrompt%%")) { document.location="index.php?Page=Subscribers" }'>
				<input class="formbutton" type="submit" value="%%LNG_Next%%">
				<br />
				&nbsp;
				<table border="0" cellspacing="0" cellpadding="2" width="100%" class="panel">
					<tr>
						<td colspan="2" class="heading2">
							%%LNG_Subscribers_Remove_Heading%%
						</td>
					</tr>
					<tr>
						<td class="FieldLabel">
							%%TPL_Required%%
							%%LNG_RemoveOptions%%:&nbsp;
						</td>
						<td>
							<select name="RemoveOption">
								<option value="Unsubscribe">%%LNG_Unsubscribe%%</option>
								<option value="Delete">%%LNG_Delete%%</option>
							</select>
							&nbsp;%%LNG_HLP_RemoveOptions%%
						</td>
					</tr>
					<tr>
						<td class="FieldLabel">
							%%TPL_Required%%
							%%LNG_RemoveEmails%%:&nbsp;
						</td>
						<td>
							<textarea name="RemoveEmailList" cols="30" rows="5" style="width: 250px;"></textarea>&nbsp;&nbsp;&nbsp;%%LNG_HLP_RemoveEmails%%
						</td>
					</tr>
					<tr>
						<td class="FieldLabel">
							%%TPL_Required%%
							%%LNG_RemoveFile%%:&nbsp;
						</td>
						<td>
							<input type="file" name="RemoveFile" class="field250">
							%%LNG_HLP_RemoveFile%%
						</td>
					</tr>
					<tr>
						<td>
							&nbsp;
						</td>
						<td>
							<input class="formbutton" type="button" value="%%LNG_Cancel%%" onClick='if(confirm("%%LNG_Subscribers_Remove_CancelPrompt%%")) { document.location="index.php?Page=Subscribers" }'>
							<input class="formbutton" type="submit" value="%%LNG_Next%%">
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</form>

<script>
	function CheckForm() {
		var f = document.forms[0];
		if (f.RemoveEmailList.value == "" && f.RemoveFile.value =="") {
			alert("%%LNG_EnterEmailAddressesToRemove%%");
			f.RemoveEmailList.focus();
			return false;
		}
		return true;
	}
</script>