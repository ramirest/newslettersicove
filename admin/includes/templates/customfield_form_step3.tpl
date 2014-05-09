<form method="post" action="index.php?Page=CustomFields&Action=Associate" onsubmit="return CheckForm();">
<input type="hidden" name="fieldid" value="%%GLOBAL_fieldid%%">
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
				<input class="formbutton" type="button" value="%%LNG_Cancel%%" onClick='if(confirm("%%GLOBAL_CancelButton%%")) { document.location="index.php?Page=CustomFields" }'>
				<input class="formbutton" type="submit" value="%%LNG_Save%%">
				<br />
				&nbsp;
				<table border="0" cellspacing="0" cellpadding="2" width="100%" class="panel">
					<tr>
						<td colspan="2" class="heading2">
							&nbsp;&nbsp;%%LNG_AssociateCustomField%%
						</td>
					</tr>
					<tr>
						<td width="200" class="FieldLabel">
							%%TPL_Required%%%%LNG_MailingLists%%:&nbsp;
						</td>
						<td>
							<table>
								%%GLOBAL_ListAssociations%%
							</table>
						</td>
					</tr>
					<tr>
						<td>
							&nbsp;
						</td>
						<td height="35">
							<input class="formbutton" type="button" value="%%LNG_Cancel%%" onClick='if(confirm("%%GLOBAL_CancelButton%%")) { document.location="index.php?Page=CustomFields" }'>
							<input class="formbutton" type="submit" value="%%LNG_Save%%">
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</form>

<script>
	function CheckForm() {
		l_count = CountLists();
		if (l_count < 1) {
			alert("%%LNG_ChooseCustomFieldLists%%");
			return false;
		}
		return true;
	}

	function CountLists() {
		var f = document.forms[0];
		found_lists = 0;
		for(i = 0; i < f.elements.length; i++) {
			if(f.elements[i].type == "checkbox") {
				if(f.elements[i].id.indexOf('listid') == 0) {
					if(f.elements[i].checked) {
						found_lists++;
					}
				}
			}
		}
		return found_lists;
	}

</script>