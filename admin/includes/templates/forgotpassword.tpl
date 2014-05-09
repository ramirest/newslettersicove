<form action="index.php?Page=%%PAGE%%&Action=%%GLOBAL_SubmitAction%%" method="post" name="frmLogin" onSubmit="return CheckLogin()">
	<table cellspacing="0" cellpadding="3" width="95%" align="center">
		<tr>
			<td class="heading1">%%LNG_ForgotPasswordTitle%%</td>
		</tr>
		<tr>
			<td class="body">%%LNG_Help_ForgotPassword%%</td>
		</tr>
		<tr>
			<td>
				%%GLOBAL_Message%%
			</td>
		</tr>
		<tr>
			<td class="body">
				<table border="0" cellspacing="0" cellpadding="2" width="100%" class=panel>
					<tr>
						<td class="Heading2" colspan=2>%%LNG_ForgotPasswordDetails%%</td>
					</tr>
					<tr>
						<td class="SmallFieldLabel">%%LNG_UserName%%:</td>
						<td align="left">
							<input type="text" id="ss_username" name="ss_username" class="Field150" value="">
						</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>
							<input type="submit" name="SubmitButton" value="%%LNG_SendPassword%%"  class="Field150">
						</td>
					</tr>
					<tr><td class="Gap"></td></tr>
				</table>
			</td>
		</tr>
	</table>
</form>

		<script language=JavaScript>

			document.getElementById('ss_username').focus();
			document.getElementById('ss_username').select();

			function CheckLogin()
			{
				var f = document.frmLogin;
				
				if(f.ss_username.value == '')
				{
					alert('%%LNG_NoUsername%%');
					f.ss_username.focus();
					return false;
				}

				// Everything is OK
				return true;
			}

			</script>
