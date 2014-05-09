<script language="javascript">
	function ImportCheck() {
		var checker = document.getElementById('textnewsletterurl');
		if (checker.value.length <= 7) {
			alert('%%LNG_Editor_ChooseWebsiteToImport%%');
			checker.focus();
			return false;
		}
		return true;
	}
</script>

	<tr>
		<td valign="top" width="150" class="FieldLabel">
			%%TPL_Required%%
			%%LNG_TextContent%%:
		</td>
		<td valign="top">
			<table width="100%" border="0">
				<tr>
					<td width="20">
						<input onClick="switchContentSource('text', 1)" id="tct1" name="tct" type="radio" checked>
					</td>
					<td>
						<label for="tct1">%%LNG_Text_Type%%</label><br>
					</td>
				</tr>
				<tr>
					<td width="20">
						<input onClick="switchContentSource('text', 2)" id="tct2" name="tct" type="radio">
					</td>
					<td>
						<label for="tct2">%%LNG_Editor_Upload_File%%</label><br>
					</td>
				</tr>
				<tr id="textNLFile" style="display:none">
					<td></td>
					<td>
						<iframe src="%%GLOBAL_APPLICATION_URL%%/admin/functions/remote.php?DisplayFileUpload&ImportType=Text" frameborder="0" height="30" id="newsletterfile"></iframe>
					</td>
				</tr>
				<tr>
					<td width="20">
						<input onClick="switchContentSource('text', 3)" id="tct3" name="tct" type="radio">
					</td>
					<td>
						<label for="tct3">%%LNG_Editor_Import_Website%%</label><br>
					</td>
				</tr>
				<tr id="textNLImport" style="display:none">
					<td></td>
					<td>
						&nbsp;&nbsp;&nbsp;
						<input type="text" name="textnewsletterurl" id="textnewsletterurl" value="http://" class="field" style="width:200px">
						<input class="formbutton" type="button" name="upload" value="%%LNG_ImportWebsite%%" onclick="ImportWebsite(this, '%%LNG_Editor_Import_Website_Wait%%', 'text');" class="field" style="width:60px">
					</td>
				</tr>
			</table>
			<div id="textEditor">
				<textarea name="TextContent" id="TextContent" rows="10" cols="60">%%GLOBAL_TextContent%%</textarea>
			</div>
		</td>
	</tr>
	<tr id="textCF">
		<td>
			&nbsp;
		</td>
		<td>
			<a href="#" onclick="javascript: ShowCustomFields('TextContent', null, '%%PAGE%%'); return false;">%%LNG_ShowCustomFields%%</a>
			&nbsp;&nbsp;
			<a href="#" onclick="javascript: InsertUnsubscribeLink('TextContent'); return false;">%%LNG_InsertUnsubscribeLink%%</a>
		</td>
	</tr>
