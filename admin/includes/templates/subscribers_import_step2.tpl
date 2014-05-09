<form method="post" action="index.php?Page=Subscribers&Action=Import&SubAction=Step3" onsubmit="return CheckForm();" enctype="multipart/form-data">
	<table cellspacing="0" cellpadding="3" width="95%" align="center">
		<tr>
			<td class="heading1">
				%%LNG_Subscribers_Import_Step2%%
			</td>
		</tr>
		<tr>
			<td class="body">
				%%LNG_Subscribers_Import_Step2_Intro%%&nbsp;<a href="%%GLOBAL_APPLICATION_URL%%/admin/resources/tutorials/import_tutorial.html" target="_blank">%%LNG_ImportTutorialLink%%</a>
			</td>
		</tr>
		<tr>
			<td>
				%%GLOBAL_Message%%
			</td>
		</tr>
		<tr>
			<td>
				<input class="formbutton" type="button" value="%%LNG_Cancel%%" onClick='if(confirm("%%LNG_Subscribers_Import_CancelPrompt%%")) { document.location="index.php?Page=Subscribers&Action=Import" }'>
				<input class="formbutton" type="submit" value="%%LNG_NextButton%%">
				<br />
				&nbsp;
				<table border="0" cellspacing="0" cellpadding="2" width="100%" class="panel">
					<tr>
						<td colspan="2" class="heading2">
							&nbsp;&nbsp;%%LNG_ImportDetails%%
						</td>
					</tr>
					<tr style="display:none">
						<td width="200" class="FieldLabel">
							%%TPL_Required%%
							%%LNG_ImportStatus%%:
						</td>
						<td>
							<select name="status">
								<option value="active" SELECTED>%%LNG_Active%%
							</select>&nbsp;%%LNG_HLP_ImportStatus%%
						</td>
					</tr>
					<tr>
						<td width="200" class="FieldLabel">
							%%TPL_Required%%
							%%LNG_ImportConfirmedStatus%%:
						</td>
						<td>
							<select name="confirmed">
								<option value="confirmed" SELECTED>%%LNG_Confirmed%%
								<option value="unconfirmed">%%LNG_Unconfirmed%%
							</select>&nbsp;%%LNG_HLP_ImportConfirmedStatus%%
						</td>
					</tr>
					<tr>
						<td width="200" class="FieldLabel">
							%%TPL_Required%%
							%%LNG_ImportFormat%%:
						</td>
						<td>
							<select name="format" onchange="ChangeOptions();">
								%%GLOBAL_ListFormats%%
							</select>&nbsp;%%LNG_HLP_ImportFormat%%
						</td>
					</tr>
					<tr>
						<td width="200" class="FieldLabel">
							%%TPL_Not_Required%%
							%%LNG_OverwriteExistingSubscriber%%:
						</td>
						<td>
							<label for="overwrite"><input type="checkbox" name="overwrite" id="overwrite" value="1">&nbsp;%%LNG_YesOverwriteExistingSubscriber%%</label> %%LNG_HLP_OverwriteExistingSubscriber%%
						</td>
					</tr>
					<tr>
						<td width="200" class="FieldLabel">
							%%TPL_Not_Required%%
							%%LNG_IncludeAutoresponder%%:
						</td>
						<td>
							<label for="autoresponder"><input type="checkbox" name="autoresponder" id="autoresponder" value="1">&nbsp;%%LNG_YesIncludeAutoresponder%%</label> %%LNG_HLP_IncludeAutoresponder%%
						</td>
					</tr>
					<tr>
						<td colspan="2" class="EmptyRow">
							&nbsp;
						</td>
					</tr>
					<tr style="display:none">
						<td colspan="2" class="heading2">
							&nbsp;&nbsp;%%LNG_ImportType%%
						</td>
					</tr>
					<tr style="display:none">
						<td width="200" class="FieldLabel">
							%%TPL_Required%%
							%%LNG_ImportType%%:&nbsp;
						</td>
						<td>
							<select name="importtype">
								%%GLOBAL_ImportTypes%%
							</select>&nbsp;%%LNG_HLP_ImportType%%
						</td>
					</tr>
					<tr style="display:none">
						<td colspan="2" class="EmptyRow">
							&nbsp;
						</td>
					</tr>
					<!-- here we go for importing of files. //-->
						<div id="importinfo_file">
						<tr>
							<td colspan="2" class="heading2">
								&nbsp;&nbsp;%%LNG_ImportFileDetails%%
							</td>
						</tr>
						<tr>
							<td width="200" class="FieldLabel">
								%%TPL_Not_Required%%
								%%LNG_ContainsHeaders%%:
							</td>
							<td>
								<label for="headers"><input type="checkbox" name="headers" id="headers" value="1">&nbsp;%%LNG_YesContainsHeaders%%</label> %%LNG_HLP_ContainsHeaders%%
							</td>
						</tr>
						<tr>
							<td width="200" class="FieldLabel">
								%%TPL_Required%%
								%%LNG_FieldSeparator%%:
							</td>
							<td>
								<input type="text" name="fieldseparator" class="field250" value="%%GLOBAL_fieldseparator%%">&nbsp;%%LNG_HLP_FieldSeparator%%
							</td>
						</tr>
						<tr id="fieldenclosed_info" style="display: %%GLOBAL_ShowFieldEnclosed%%">
							<td width="200" class="FieldLabel">
								%%TPL_Not_Required%%
								%%LNG_FieldEnclosed%%:
							</td>
							<td>
								<input type="text" name="fieldenclosed" class="field250" value="%%GLOBAL_fieldenclosed%%">&nbsp;%%LNG_HLP_FieldEnclosed%%
							</td>
						</tr>
						<tr>
							<td width="200" class="FieldLabel">
								%%TPL_Required%%
								%%LNG_RecordSeparator%%:
							</td>
							<td>
								<input type="text" name="recordseparator" class="field250" value="%%GLOBAL_recordseparator%%">&nbsp;%%LNG_HLP_RecordSeparator%%
							</td>
						</tr>
						<tr>
							<td width="200" class="FieldLabel">
								%%TPL_Required%%
								%%LNG_ImportFile%%:
							</td>
							<td>
								<input type="file" name="importfile" class="field250">&nbsp;%%LNG_HLP_ImportFile%%
							</td>
						</tr>
					</div>
					<!-- end of importing files //-->
					<tr>
						<td>
							&nbsp;
						</td>
						<td>
							<input class="formbutton" type="button" value="%%LNG_Cancel%%" onClick='if(confirm("%%LNG_Subscribers_Import_CancelPrompt%%")) { document.location="index.php?Page=Subscribers&Action=Import" }'>
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
		var import_index = f.importtype.selectedIndex;
		var importtype = f.importtype.options[import_index].value;

		if (importtype == 'file') {
			if (f.fieldseparator.value == '') {
				alert('%%LNG_ImportFile_FieldSeparatorEmpty%%');
				f.fieldseparator.focus();
				return false;
			}
			if (f.recordseparator.value == '') {
				alert('%%LNG_ImportFile_RecordSeparatorEmpty%%');
				f.recordseparator.focus();
				return false;
			}
			if (f.importfile.value == '') {
				alert('%%LNG_ImportFile_FileEmpty%%');
				f.importfile.focus();
				return false;
			}
			return true;
		}
	}
	
	function ChangeOptions() {
		var Options = Array('file');
		var f = document.forms[0];
		var import_index = f.importtype.selectedIndex;
		var importtype = f.importtype.options[import_index].value;
		for (var option in Options) {
			if (option == importtype) {
				document.getElementById('importinfo_' + option).display.style = '';
			} else {
				document.getElementById('importinfo_' + option).display.style = 'none';
			}
		}
	}

	function ImportTutorial()
	{
		window.open('index.php?Page=Subscribers&Action=Import&SubAction=ViewTutorial', "importWin", "left=" + (((screen.availWidth) / 2) - 225) + ", top="+ (((screen.availHeight) / 2) - 300) +", width=450, height=600, toolbar=0, statusbar=0, scrollbars=1");
	}
</script>
