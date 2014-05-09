<form method="post" action="index.php?Page=Newsletters&Action=%%GLOBAL_Action%%" onsubmit="return CheckForm()" enctype="multipart/form-data">
	<table cellspacing="0" cellpadding="3" width="95%" align="center">
		<tr>
			<td class="heading1">
				%%GLOBAL_Heading%%
			</td>
		</tr>
		<tr>
			<td class="body">
				%%GLOBAL_Intro%%
			</td>
		</tr>
		<tr>
			<td>
				%%GLOBAL_Message%%
			</td>
		</tr>
		<tr>
			<td>
				<input class="formbutton" type="button" value="%%LNG_Cancel%%" onClick='if(confirm("%%GLOBAL_CancelButton%%")) { document.location="index.php?Page=Newsletters" }'>
				<input class="formbutton" type="button" value="%%LNG_Save%%" onclick='Save();'>
				<input class="formbutton_wide" type="submit" value="%%LNG_SaveAndExit%%">
				<br />
				&nbsp;
				<table border="0" cellspacing="0" cellpadding="2" width="100%" class="panel">
					<tr>
						<td colspan="2" class="heading2">
							&nbsp;&nbsp;%%LNG_Newsletter_Details%%
						</td>
					</tr>
					<tr>
						<td class="FieldLabel">
							%%TPL_Required%%
							%%LNG_NewsletterSubject%%:
						</td>
						<td>
							<input type="text" name="subject" value="%%GLOBAL_Subject%%" class="field250" style="width:300px">&nbsp;%%LNG_HLP_NewsletterSubject%%
						</td>
					</tr>

					%%GLOBAL_Editor%%

					<tr>
						<td colspan="2" class="EmptyRow">
							&nbsp;
						</td>
					</tr>
					<tr>
						<td colspan="2" class="heading2">
							&nbsp;&nbsp;%%LNG_Attachments%%
						</td>
					</tr>
					<tr>
						<td valign="top" class="FieldLabel">
							%%TPL_Not_Required%%
							%%LNG_Attachments%%:&nbsp;
						</td>
						<td>
							<table border="0" cellspacing="0" cellpadding="0" id="AttachmentsTable">
								<tr>
									<td>
										<input type="file" name="attachments[]" value="" class="formbutton" id="fileUpload" style="width: 200px">&nbsp;%%LNG_HLP_Attachments%%

										<div id="files_list" style="margin-top: 5px"></div>

										<script>
											<!-- Create an instance of the multiSelector class, pass it the output target and the max number of files -->
											var multi_selector = new MultiSelector( document.getElementById( 'files_list' ), 5 );
											<!-- Pass in the file element -->
											multi_selector.addElement( document.getElementById( 'fileUpload' ) );
										</script>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td colspan="2" align="left">
							%%GLOBAL_AttachmentsList%%
						</td>
					</tr>
					<tr>
						<td colspan="2" class="EmptyRow">
							&nbsp;
						</td>
					</tr>
					<tr>
						<td colspan="2" class="heading2">
							&nbsp;&nbsp;%%LNG_MiscellaneousOptions%%
						</td>
					</tr>
					<tr>
						<td class="FieldLabel">
							%%TPL_Not_Required%%
							%%LNG_NewsletterIsActive%%:
						</td>
						<td>
							<label for="active">
							<input type="checkbox" name="active" id="active" value="1"%%GLOBAL_IsActive%%>
							%%LNG_NewsletterIsActiveExplain%%
							</label>
							%%LNG_HLP_NewsletterIsActive%%
						</td>
					</tr>
					<tr>
						<td class="FieldLabel">
							%%TPL_Not_Required%%
							%%LNG_NewsletterArchive%%:
						</td>
						<td>
							<label for="archive">
							<input type="checkbox" name="archive" id="archive" value="1"%%GLOBAL_Archive%%>
							%%LNG_NewsletterArchiveExplain%%
							</label>
							%%LNG_HLP_NewsletterArchive%%
						</td>
					</tr>
					<tr>
						<td colspan="2" class="EmptyRow">
							&nbsp;
						</td>
					</tr>
					<tr>
						<td colspan="2" class="heading2">
							&nbsp;&nbsp;%%LNG_SendPreview%%
						</td>
					</tr>
					<tr>
						<td valign="top" class="FieldLabel">
							%%TPL_Not_Required%%
							%%LNG_From%%:
						</td>
						<td>
							<input type="text" name="FromPreviewEmail" value="%%GLOBAL_FromPreviewEmail%%" class="field" style="width:150px">
						</td>
					</tr>
					<tr>
						<td valign="top" class="FieldLabel">
							%%TPL_Not_Required%%
							%%LNG_To%%:
						</td>
						<td>
							<input type="text" name="PreviewEmail" value="" class="field" style="width:150px">&nbsp;<input type="button" value="%%LNG_SendPreview%%" class="field" onclick="javascript: SendMePreview();">&nbsp;%%LNG_HLP_SendPreview%%
						</td>
					</tr>
					<tr>
						<td colspan="2">
							&nbsp;
						</td>
					</tr>
					<tr>
						<td>
							&nbsp;
						</td>
						<td height="35">
							<input class="formbutton" type="button" value="%%LNG_Cancel%%" onClick='if(confirm("%%GLOBAL_CancelButton%%")) { document.location="index.php?Page=Newsletters" }'>&nbsp;&nbsp;
							<input class="formbutton" type="button" value="%%LNG_Save%%" onclick='Save();'>
							<input class="formbutton_wide" type="submit" value="%%LNG_SaveAndExit%%">
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</form>
<script language="javascript">

	var f = document.forms[0];

	function CheckForm() {
		if (f.subject.value == '') {
			alert("%%LNG_PleaseEnterNewsletterSubject%%");
			f.subject.focus();
			return false;
		}
		return true;
	}

	function Upload() {
		if (f.newsletterfile.value == "") {
			alert('%%LNG_NewsletterFileEmptyAlert%%');
			f.newsletterfile.focus();
			return false;
		}
		Save();
	}

	function Import() {
		if (f.newsletterurl.value == "" || f.newsletterurl.value == 'http://') {
			alert('%%LNG_NewsletterURLEmptyAlert%%');
			f.newsletterurl.focus();
			return false;
		}
		Save();
	}

	function Save() {
		if (CheckForm()) {
			f.action = 'index.php?Page=Newsletters&Action=%%GLOBAL_SaveAction%%';
			f.submit();
		}
	}

	function SendMePreview() {
		if (f.PreviewEmail.value == "") {
			alert("%%LNG_EnterPreviewEmail%%");
			f.PreviewEmail.focus();
			return false;
		}
		var top = screen.height / 2 - (150);
		var left = screen.width / 2 - (200);

		f.target = "previewWin";
		window.open("","previewWin","left=" + left + ",top="+top+",toolbar=false,status=no,directories=false,menubar=false,scrollbars=false,resizable=false,copyhistory=false,width=400,height=300");

		prevAction = f.action;
		f.action = "index.php?Page=Newsletters&Action=SendPreview&id=%%GLOBAL_PreviewID%%";
		f.submit();

		f.target = "";
		f.action = prevAction;
	}

</script>
