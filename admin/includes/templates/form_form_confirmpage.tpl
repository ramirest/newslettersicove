<tr>
	<td colspan="2" class="heading2">
		&nbsp;&nbsp;%%LNG_FormDisplayPageOptions%%
	</td>
</tr>
<tr>
	<td class="FieldLabel" width="200">
		%%TPL_Required%%
		%%LNG_ConfirmPageHTML%%:&nbsp;
	</td>
	<td align="left">
		<table width="100%" border="0">
				<tr>
					<td width="20">
						<input onClick="document.getElementById('urlField').style.display = 'none'; document.getElementById('htmlField').style.display = '';" id="htmlRadio" name="contentType" type="radio"%%GLOBAL_ConfirmHTMLField%%>
					</td>
					<td>
						<label for="htmlRadio">%%LNG_HTML_Using_Editor%%</label><br>
					</td>
				</tr>
				<tr>
					<td width="20">
						<input onClick="document.getElementById('urlField').style.display = ''; document.getElementById('htmlField').style.display = 'none';" id="urlRadio" name="contentType" type="radio"%%GLOBAL_ConfirmUrlField%%>
					</td>
					<td>
						<label for="urlRadio">%%LNG_Editor_Use_URL%%</label><br>
					</td>
				</tr>

				<tr id="urlField" style="display: %%GLOBAL_ConfirmUrlStyle%%">
					<td>&nbsp;</td>
					<td>
						&nbsp;&nbsp;&nbsp;<input type="text" name="confirmpageurl" value="%%GLOBAL_ConfirmPageURL%%" class="field250">
					</td>
				</tr>
		</table>
		<br>
		<div id="htmlField" style="display: %%GLOBAL_ConfirmHTMLStyle%%">
			%%GLOBAL_ConfirmHTML%%
		</div>
	</td>
</tr>

<tr>
	<td colspan="2" class="EmptyRow">
		&nbsp;
	</td>
</tr>
<tr>
	<td colspan="2" class="heading2">
		&nbsp;&nbsp;%%LNG_FormConfirmEmailOptions%%
	</td>
</tr>
%%TPL_Form_EmailOptions%%
<tr>
	<td class="FieldLabel" width="200">
		%%TPL_Required%%
		%%LNG_ConfirmSubject%%:&nbsp;
	</td>
	<td>
		<input type="text" class="field250" name="confirmsubject" value="%%GLOBAL_ConfirmSubject%%">&nbsp;%%LNG_HLP_ConfirmSubject%%
	</td>
</tr>
<tr>
	<td class="FieldLabel" width="200">
		%%TPL_Not_Required%%
		%%LNG_ConfirmTextVersion%%:&nbsp;
	</td>
	<td align="left">
		%%GLOBAL_EditorText%%
	</td>
</tr>
<tr>
	<td class="FieldLabel" width="200">
		%%TPL_Not_Required%%
		%%LNG_ConfirmHTMLVersion%%:&nbsp;
	</td>
	<td>
		%%GLOBAL_EditorHTML%%
	</td>
</tr>
