<table cellspacing="0" cellpadding="3" width="95%" align="center">
	<tr>
		<td class="heading1">
			%%LNG_FormGetHTML_Heading%%
		</td>
	</tr>
	<tr>
		<td class="body">
			%%LNG_FormGetHTML_Introduction%%
		</td>
	</tr>
	<tr>
		<td>
			<input class="formbutton" type="button" value="%%LNG_Cancel%%" onClick='document.location="index.php?Page=Forms";'>&nbsp;&nbsp;
			<br />
			&nbsp;
			<table border="0" cellspacing="0" cellpadding="2" width="100%" class="panel">
				<tr>
					<td colspan="2" class="heading2">
						&nbsp;&nbsp;%%LNG_FormGetHTML_Options%%
					</td>
				</tr>
				<tr>
					<td valign="top" width="200" class="FieldLabel">
						%%TPL_Not_Required%%
						%%LNG_FormHTML%%:&nbsp;
					</td>
					<td>
						<TEXTAREA name="code" cols="60" rows="10">%%GLOBAL_HTMLCode%%</textarea>&nbsp;%%LNG_HLP_FormHTML%%
					</td>
				</tr>
				<tr>
					<td>
						&nbsp;
					</td>
					<td>
						<input class="formbutton" type="button" value="%%LNG_Cancel%%" onClick='document.location="index.php?Page=Forms";'>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
