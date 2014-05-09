	%%GLOBAL_CurrentList%%

	%%GLOBAL_ExtraList%%

	<script language="javascript">
		var CurrentSize = '%%GLOBAL_CurrentSize%%';

		function AddOption() {
			var table = document.getElementById('customFieldsTable');
			var lastRow = table.rows.length;
			var row = table.insertRow(lastRow - 2);
			var cell = row.insertCell(0);
			var cell2 = row.insertCell(1);

			var row2 = table.insertRow(lastRow - 1);
			var cell3 = row2.insertCell(0);
			var cell4 = row2.insertCell(1);

			CurrentSize++;

			cell.width= "200";
			cell.className = "FieldLabel";

			cell3.width= "200";
			cell3.className = "FieldLabel";
	
			cell.innerHTML = '%%TPL_Not_Required%%\n%%LNG_Dropdown_Key%%&nbsp;' + CurrentSize + ":&nbsp;";
			cell2.innerHTML = '<input type=text name=Key[' + CurrentSize + '] class=field250>&nbsp;';

			cell3.innerHTML = '%%TPL_Not_Required%%\n%%LNG_Dropdown_Value%%&nbsp;' + CurrentSize + ':&nbsp;';

			cell4.innerHTML = '<input type="text" name="Value[' + CurrentSize + ']" class="field250">';
		}

	</script>

	<tr id="additionalOption">
		<td>&nbsp;</td>
		<td><a href="javascript:AddOption()"><img src="images/plus.gif" border="0" style="margin-right: 5px">%%LNG_AddMore%%</a></td>
	</tr>
