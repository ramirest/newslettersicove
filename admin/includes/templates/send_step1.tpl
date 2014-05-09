<form method="post" action="index.php?Page=Send&Action=Step2" onsubmit="return CheckForm();">
	<table cellspacing="0" cellpadding="3" width="95%" align="center">
		<tr>
			<td class="heading1">
				%%LNG_Send_Step1%%
			</td>
		</tr>
		<tr>
			<td class="body">
				%%LNG_Send_Step1_Intro%%
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
				<br />
				&nbsp;
				<table border="0" cellspacing="0" cellpadding="2" width="100%" class="panel">
					<tr>
						<td colspan="2" class="heading2">
							&nbsp;&nbsp;%%LNG_MailingListDetails%%
						</td>
					</tr>
					<tr>
						<td width="200" class="FieldLabel">
							%%TPL_Required%%
							%%LNG_SendMailingList%%:&nbsp;
						</td>
						<td>
							<select id="lists" name="lists[]" style="width: 350px" size="9" multiple onDblClick="this.form.submit()">
								%%GLOBAL_SelectList%%
							</select>&nbsp;%%LNG_HLP_SendMailingList%%
						</td>
					</tr>
					<tr>
						<td class="FieldLabel">
							%%TPL_Not_Required%%
							%%LNG_ShowFilteringOptions%%:&nbsp;
						</td>
						<td valign="top">
							<label for="ShowFilteringOptions"><input type="checkbox" name="ShowFilteringOptions" id="ShowFilteringOptions" value="1" %%GLOBAL_ShowFilteringOptions%%>%%LNG_ShowFilteringOptionsExplain%%</label> %%LNG_HLP_ShowFilteringOptions%%
						</td>
					</tr>
					<tr>
						<td>
							&nbsp;
						</td>
						<td>
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
	function CheckForm() {
		var f = document.forms[0];
		var listbox = document.getElementById('lists');
		if (listbox.selectedIndex < 0) {
			alert("%%LNG_SelectList%%");
			listbox.focus();
			return false;
		}
		return true;
	}
</script>
