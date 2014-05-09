			<form action="index.php?Page=%%PAGE%%&Action=%%GLOBAL_SubmitAction%%" method="post" name="frmLogin" onSubmit="return CheckLogin()">
				<table cellspacing="0" cellpadding="3" width="95%" align="center">
					<tr>
						<td class="heading1">%%LNG_LoginTitle%%</td>
					</tr>
					<tr>
						<td class="body" style="padding-top: 10px; padding-bottom: 10px;"><div>%%LNG_Help_Login%%&nbsp;%%LNG_ForgotPasswordReminder%%</div></td>
					</tr>
					<tr>
						<td>
							%%GLOBAL_Message%%
						</td>
					</tr>
					<tr>
						<td class="body">
							<table class="Panel" cellspacing="0" cellpadding="2" border="0">
								<tr>
									<td class="Heading2" colspan="2">&nbsp;%%LNG_LoginDetails%%</td>
								</tr>
								<tr>
									<td nowrap class="SmallFieldLabel">&nbsp;&nbsp;&nbsp;%%LNG_UserName%%:</td>
									<td>
										<input type="text" id="ss_username" name="ss_username" class="Field150" value="%%GLOBAL_ss_username%%">
									</td>
								</tr>
								<tr>
									<td nowrap class="SmallFieldLabel">&nbsp;&nbsp;&nbsp;%%LNG_Password%%:</td>
									<td>
										<input type="password" id="ss_password" name="ss_password" class="Field150" value="">
									</td>
								</tr>
								<tr>
									<td nowrap>&nbsp;</td>
									<td align="left">
									<label for="rememberme"><input type="checkbox" name="rememberme" value="1" id="rememberme">&nbsp;%%LNG_RememberMe%%</label>
									</td>
								</tr>
								<tr>
									<td>&nbsp;</td>
									<td>
										<input type="submit" name="SubmitButton" value="%%LNG_Login%%" class="FormButton">
									</td>
								</tr>
								<tr><td class="Gap"></td></tr>
							</table>
						</td>
					</tr>
				</table>
			</form>

			<script language=JavaScript>

				var f = document.frmLogin;
				f.ss_username.focus();
				if (f.ss_username.value != '') {
					f.ss_password.focus();
				}

				function CheckLogin()
				{
					if(f.ss_username.value == '')
					{
						alert('%%LNG_NoUsername%%');
						f.ss_username.focus();
						f.ss_username.select();
						return false;
					}
					
					if(f.ss_password.value == '')
					{
						alert('%%LNG_NoPassword%%');
						f.ss_password.focus();
						f.ss_password.select();
						return false;
					}
					
					// Everything is OK
					return true;
				}

				// Needed to resize editor for 800x600
				createCookie("screenWidth", screen.availWidth, 1);

			</script>
		