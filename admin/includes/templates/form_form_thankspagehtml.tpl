<tr>
	<td colspan="2" class="heading2">
		&nbsp;&nbsp;%%LNG_FormThanksPageOptions%%
	</td>
</tr>
<tr>
	<td class="FieldLabel" width="200">
		%%TPL_Not_Required%%
		%%LNG_ThanksPageHTML%%:&nbsp;
	</td>
	<td align="left">
		<table width="100%" border="0">
				<tr>
					<td width="20">
						<input onClick="document.getElementById('thanksurlField').style.display = 'none'; document.getElementById('thankshtmlField').style.display = '';" id="thankshtmlRadio" name="contentType" type="radio"%%GLOBAL_ThanksPageHTMLField%%>
					</td>
					<td>
						<label for="thankshtmlRadio">%%LNG_HTML_Using_Editor%%</label><br>
					</td>
				</tr>
				<tr>
					<td width="20">
						<input onClick="document.getElementById('thanksurlField').style.display = ''; document.getElementById('thankshtmlField').style.display = 'none';" id="thanksurlRadio" name="contentType" type="radio"%%GLOBAL_ThanksPageURLField%%>
					</td>
					<td>
						<label for="thanksurlRadio">%%LNG_Editor_Use_URL%%</label><br>
					</td>
				</tr>

				<tr id="thanksurlField" style="display: %%GLOBAL_ThanksPageUrlStyle%%">
					<td>&nbsp;</td>
					<td>
						&nbsp;&nbsp;&nbsp;<input type="text" name="thankspageurl" value="%%GLOBAL_ThanksPageURL%%" class="field250">
					</td>
				</tr>
		</table>
		<br>
		<div id="thankshtmlField" style="display: %%GLOBAL_ThanksPageHTMLStyle%%">
			%%GLOBAL_ThanksPage_HTML%%
		</div>
	</td>
</tr>
