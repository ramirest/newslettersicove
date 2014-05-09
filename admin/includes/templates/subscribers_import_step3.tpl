<form method="post" action="index.php?Page=Subscribers&Action=Import&SubAction=Step4" onsubmit="return CheckForm();" enctype="multipart/form-data">
	<table cellspacing="0" cellpadding="3" width="95%" align="center">
		<tr>
			<td class="heading1">
				%%LNG_Subscribers_Import_Step3%%
			</td>
		</tr>
		<tr>
			<td class="body">
				%%LNG_Subscribers_Import_Step3_Intro%%
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
							&nbsp;&nbsp;%%LNG_ImportFields%%
						</td>
					</tr>
					%%GLOBAL_ImportFieldList%%
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
		return true;
	}
</script>
