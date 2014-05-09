<form action="index.php?Page=%%PAGE%%&Action=ChangePassword" method="post" name="frmLogin" onSubmit="return CheckLogin()">
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
							%%GLOBAL_UserName%%
						</td>
					</tr>
					<tr>
						<td class="SmallFieldLabel">
							%%LNG_NewPassword%%:
						</td>
						<td align="left">
							<input type="password" id="ss_password" name="ss_password" class="Field150" value="">
						</td>
					</tr>
					<tr>
						<td class="SmallFieldLabel">
							%%LNG_PasswordConfirm%%:
						</td>
						<td align="left">
							<input type="password" name="ss_password_confirm" value="" class="field150">
						</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>
							<input type="submit" name="SubmitButton" value="%%LNG_ChangePassword%%"  class="Field150">
						</td>
					</tr>
					<tr><td class="Gap"></td></tr>
				</table>
			</td>
		</tr>
	</table>
</form>

<script language=JavaScript>

	document.getElementById('ss_password').focus();

	function CheckLogin()
	{
		var f = document.frmLogin;

		if (f.ss_password.value == '') {
			alert('%%LNG_NoPassword%%');
			f.ss_password.focus();
			return false;
		}
		
		if (f.ss_password_confirm.value == "") {
			alert("%%LNG_PasswordConfirmAlert%%");
			f.ss_password_confirm.focus();
			return false;
		}
		
		if (f.ss_password.value != f.ss_password_confirm.value) {
			alert("%%LNG_PasswordsDontMatch%%");
			f.ss_password_confirm.select();
			f.ss_password_confirm.focus();
			return false;
		}
		
		// Everything is OK
		return true;
	}

	</script>
