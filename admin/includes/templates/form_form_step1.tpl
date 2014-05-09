<form method="post" action="index.php?Page=Forms&Action=%%GLOBAL_Action%%" onsubmit="return CheckForm()">
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
				<input class="formbutton" type="button" value="%%LNG_Cancel%%" onClick='if(confirm("%%GLOBAL_CancelButton%%")) { document.location="index.php?Page=Forms" }'>
				<input class="formbutton" type="submit" value="%%LNG_Next%%">
				<br />
				&nbsp;
				<table border="0" cellspacing="0" cellpadding="2" width="100%" class="panel">
					<tr>
						<td colspan="2" class="heading2">
							&nbsp;&nbsp;%%GLOBAL_FormDetails%%
						</td>
					</tr>
					<tr>
						<td width="200" class="FieldLabel">
							%%TPL_Required%%
							%%LNG_FormName%%:&nbsp;
						</td>
						<td>
							<input type="text" name="FormName" class="field250" value="%%GLOBAL_FormName%%">&nbsp;%%LNG_HLP_FormName%%
						</td>
					</tr>
					<tr>
						<td width="200" class="FieldLabel">
							%%TPL_Required%%
							%%LNG_FormDesign%%:&nbsp;
						</td>
						<td>
							<select name="FormDesign" id="FormDesign">
								%%GLOBAL_DesignList%%
							</select>
							&nbsp;&nbsp;%%LNG_HLP_FormDesign%%<a href="#" onclick="javascript: ShowPreview(); return false;"><img src="images/magnify.gif" border="0" hspace="5">%%LNG_Preview%%</a>
						</td>
					</tr>
					<tr>
						<td width="200" class="FieldLabel">
							%%TPL_Required%%
							%%LNG_FormType%%:&nbsp;
						</td>
						<td>
							<select name="FormType" id="FormType" onchange="ChangeFormType(this);">
								%%GLOBAL_FormTypeList%%
							</select>&nbsp;&nbsp;&nbsp;%%LNG_HLP_FormType%%
						</td>
					</tr>
					<tr id="chooseformatstyle" style="display: %%GLOBAL_ChooseFormatStyle%%">
						<td width="200" class="FieldLabel">
							%%TPL_Required%%
							%%LNG_UserChooseFormat%%:&nbsp;
						</td>
						<td>
							<select id="SubscriberChooseFormat" name="SubscriberChooseFormat" onChange="ChangeOrderOptions(this);">
								%%GLOBAL_SubscriberChooseFormat%%
							</select>&nbsp;&nbsp;&nbsp;%%LNG_HLP_SubscriberChooseFormat%%
						</td>
					</tr>
					<tr id="changeformatstyle" style="display: %%GLOBAL_ChangeFormatStyle%%">
						<td width="200" class="FieldLabel">
							%%TPL_Required%%
							%%LNG_SubscriberChangeFormat%%:&nbsp;
						</td>
						<td>
							<input type="checkbox" id="SubscriberChangeFormat" name="SubscriberChangeFormat" value="1"%%GLOBAL_SubscriberChangeFormat%% onClick="ChangeDisplayOrderOptions(this);"><label for="SubscriberChangeFormat">%%LNG_SubscriberChangeFormatExplain%%</label>
							&nbsp;%%LNG_HLP_SubscriberChangeFormat%%
						</td>
					</tr>
					<tr id="requireconfirm" style="display: %%GLOBAL_RequireConfirmStyle%%">
						<td width="200" class="FieldLabel">
							%%TPL_Not_Required%%
							%%LNG_RequireConfirmation%%:&nbsp;
						</td>
						<td>
							<label for="RequireConfirmation"><input type="checkbox" id="RequireConfirmation" name="RequireConfirmation" value="1" %%GLOBAL_RequireConfirmation%%>%%LNG_RequireConfirmationExplain%%</label> %%LNG_HLP_RequireConfirmation%%
						</td>
					</tr>
					<tr id="sendthanks" style="display: %%GLOBAL_SendThanksStyle%%">
						<td width="200" class="FieldLabel">
							%%TPL_Not_Required%%
							%%LNG_SendThanks%%:&nbsp;
						</td>
						<td>
							<label for="SendThanks"><input type="checkbox" name="SendThanks" id="SendThanks" value="1" %%GLOBAL_SendThanks%%>%%LNG_SendThanksExplain%%</label> %%LNG_HLP_SendThanks%%
						</td>
					</tr>
					<tr id="contactformstyle" style="display: %%GLOBAL_ContactFormStyle%%">
						<td class="FieldLabel">
							%%TPL_Not_Required%%
							%%LNG_ContactForm%%:&nbsp;
						</td>
						<td>
							<label for="ContactForm"><input type="checkbox" name="ContactForm" id="ContactForm" value="1" %%GLOBAL_ContactForm%%>%%LNG_ContactFormExplain%%</label> %%LNG_HLP_ContactForm%%
						</td>
					</tr>
					<tr id="captchaformstyle" style="display: %%GLOBAL_CaptchaFormStyle%%">
						<td class="FieldLabel">
							%%TPL_Not_Required%%
							%%LNG_UseCaptcha%%:&nbsp;
						</td>
						<td>
							<label for="UseCaptcha"><input type="checkbox" name="UseCaptcha" id="UseCaptcha" value="1" %%GLOBAL_UseCaptcha%%>%%LNG_UseCaptchaExplain%%</label> %%LNG_HLP_UseCaptcha%%
						</td>
					</tr>
					<tr id="chooseliststyle3" style="display: %%GLOBAL_ChooseListOptionsStyle%%">
						<td colspan="2" class="EmptyRow">&nbsp;
						</td>
					</tr>
					<tr id="chooseliststyle1" style="display: %%GLOBAL_ChooseListOptionsStyle%%">
						<td colspan="2" class="heading2">
							&nbsp;&nbsp;%%LNG_ListsToInclude%%
						</td>
					</tr>
					<tr id="chooseliststyle2" style="display: %%GLOBAL_ChooseListOptionsStyle%%">
						<td width="200" class="FieldLabel">
							%%TPL_Required%%
							%%LNG_IncludeLists%%
						</td>
						<td>
							%%GLOBAL_MailingListBoxes%%
						</td>
					</tr>
					<tr id="chooseliststyle4" style="display: %%GLOBAL_ChooseListStyle%%">
						<td width="200" class="FieldLabel">
							%%TPL_Not_Required%%
							%%LNG_OrderCustomFields%%
						</td>
						<td>
							<table border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td>
										<select name="fieldorder[]" size="5" id="fieldorder" multiple>
											%%GLOBAL_FieldOrderList%%
										</select>
									</td>
									<td valign="top">
										<img src="images/up.gif" onclick="SortPage(0);">
										<br/>
										<img src="images/down.gif" onclick="SortPage(1);">
										<br/>
										%%LNG_HLP_OrderCustomFields%%
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td>
							&nbsp;
						</td>
						<td>
							<input class="formbutton" type="button" value="%%LNG_Cancel%%" onClick='if(confirm("%%GLOBAL_CancelButton%%")) { document.location="index.php?Page=Forms" }'>
							&nbsp;&nbsp;<input class="formbutton" type="submit" value="%%LNG_Next%%">
							<br>&nbsp;
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<input type="hidden" name="hidden_fieldorder" value="" id="hidden_fieldorder">
</form>
<script language="javascript">

	var FormLoaded = '%%GLOBAL_FormLoaded%%';
	var LoadFormType = '%%GLOBAL_LoadFormType%%';
	var LoadDesign = '%%GLOBAL_LoadDesign%%';

	var LoadChangeFormat = '%%GLOBAL_LoadChangeFormat%%';

	var LoadedLists = new Array(%%GLOBAL_LoadedLists%%);
	var LoadedFields = new Array(%%GLOBAL_LoadedFields%%);

	var LoadedOrder = new Array(%%GLOBAL_LoadedOrder%%);

	var RequiredFields_Ids = new Array('e');
	var RequiredFields_Values = new Array();

	RequiredFields_Values['e'] = '%%LNG_Email_Required_For_Form%%';
	RequiredFields_Values['cf'] = '%%LNG_SubscriberFormat_For_Form%%';
	RequiredFields_Values['cl'] = '%%LNG_ChooseList_For_Form%%';

	function SortPage(Order)
	{
		// Sort the selected page up or down
		var fldorder = document.getElementById("fieldorder");

		if (fldorder.options.length <= 0) {
			alert("%%LNG_ChooseCustomFieldsToInclude%%");
			return;
		}

		if (fldorder.options.length == 1) {
			return;
		}

		if (fldorder.selectedIndex < 0) {
			alert("%%LNG_ChooseCustomFieldToOrder%%");
			fldorder.focus();
			return;
		}

		if(Order == 0) {
			// Sort up
			if(fldorder.selectedIndex > 0) {
				aVal = fldorder.options[fldorder.selectedIndex-1].value;
				aTxt = fldorder.options[fldorder.selectedIndex-1].text;

				bVal = fldorder.options[fldorder.selectedIndex].value;
				bTxt = fldorder.options[fldorder.selectedIndex].text;

				fldorder.options[fldorder.selectedIndex-1].value = bVal;
				fldorder.options[fldorder.selectedIndex-1].text = bTxt;

				fldorder.options[fldorder.selectedIndex].value = aVal;
				fldorder.options[fldorder.selectedIndex].text = aTxt;

				fldorder.selectedIndex = fldorder.selectedIndex - 1;
				fldorder.focus();
			}
		} else {
			// Sort down
			if(fldorder.selectedIndex < fldorder.options.length-1) {
				aVal = fldorder.options[fldorder.selectedIndex+1].value;
				aTxt = fldorder.options[fldorder.selectedIndex+1].text;

				bVal = fldorder.options[fldorder.selectedIndex].value;
				bTxt = fldorder.options[fldorder.selectedIndex].text;

				fldorder.options[fldorder.selectedIndex+1].value = bVal;
				fldorder.options[fldorder.selectedIndex+1].text = bTxt;

				fldorder.options[fldorder.selectedIndex].value = aVal;
				fldorder.options[fldorder.selectedIndex].text = aTxt;

				fldorder.selectedIndex = fldorder.selectedIndex + 1;
				fldorder.focus();
			}
		}
	}

	function ShowFields(fld, listid) {

		mytype = document.getElementById('FormType');
		var selectedVal = mytype[mytype.selectedIndex].value;

		if (selectedVal != 'u')
		{
			if (fld.checked) {
				document.getElementById('customfields_' + listid).style.display = '';
			} else {
				for(i = 0; i < document.forms[0].elements.length; i++) {
					if(document.forms[0].elements[i].type == "checkbox") {
						fld = document.forms[0].elements[i];
						fldid = fld.id;
						fldcheck = fldid.indexOf('fields_' + listid);

						if (fldcheck == 0) {
							fld.checked = false;
							UpdateOrderBox(fld);
						}
					}
				}
				document.getElementById('customfields_' + listid).style.display = 'none';
			}
		}

		list_count = CountLists();

		fieldorderbox = document.getElementById('fieldorder');

		found_checklist = 0;
		for (k = 0; k < fieldorderbox.options.length; k++) {
			fldvalue = fieldorderbox.options[k].value;
			if (fldvalue == 'cl') {
				if (list_count <= 1) {
					fieldorderbox.options[k] = null;
				}
				return;
			}
		}

		if (list_count <= 1) {
			return;
		}

		size = fieldorderbox.length;
		fieldorderbox.options[size] = new Option(RequiredFields_Values['cl'], 'cl');

	}

	function UpdateOrderBox(fld) {
		fieldorderbox = document.getElementById('fieldorder');
		fldname = document.getElementById(fld.id + '_label');

		if (fld.checked) {

			found_options = new Array();
			for (i = 0; i < fieldorderbox.options.length; i++) {
				found_options.push(fieldorderbox.options[i].value);
			}

			if (!inArray(fld.value, found_options)) {
				size = fieldorderbox.length;
				if (fldname.innerText && typeof(fldname.innerText) != "undefined") {
					fieldorderbox.options[size] = new Option(fldname.innerText, fld.value);
				} else {
					fieldorderbox.options[size] = new Option(fldname.textContent, fld.value);
				}
			}
			return;
		}

		for (k = 0; k < fieldorderbox.options.length; k++) {
			if (fieldorderbox.options[k].value == fld.value) {
				fieldorderbox.options[k] = null;
				return;
			}
		}
	}

	function CountLists() {
		var f = document.forms[0];
		found_lists = 0;
		for(i = 0; i < f.elements.length; i++) {
			if(f.elements[i].type == "checkbox") {
				if(f.elements[i].id.indexOf('list_') == 0) {
					if(f.elements[i].checked) {
						found_lists++;
					}
				}
			}
		}
		return found_lists;
	}

	function CheckForm() {
		var f = document.forms[0];

		if (f.FormName.value == '') {
			alert("%%LNG_EnterFormName%%");
			f.FormName.focus();
			return false;
		}

		mytype = document.getElementById('FormType');
		var selectedVal = mytype[mytype.selectedIndex].value;
		if (selectedVal != 'f') {
			find_lists = CountLists();
			if (!find_lists) {
				alert('%%LNG_ChooseFormLists%%');
				return false;
			}
		}

		formchanged = FormHasChanged();

		hidden_fieldorder = "";
		fieldorderbox = document.getElementById('fieldorder');
		for (vk = 0; vk < fieldorderbox.options.length; vk++) {
			hidden_fieldorder = hidden_fieldorder + ";" + fieldorderbox.options[vk].value;
		}
		document.getElementById('hidden_fieldorder').value = hidden_fieldorder;

		if (formchanged) {
			if (confirm("%%LNG_FormHasBeenChanged%%")) {
				f.submit();
				return true;
			}
			return false;
		}

		return true;
	}

	function FormHasChanged() {
		if (FormLoaded <= 0) return;
		if (LoadFormType == 's' || LoadFormType == 'u') {
			return false;
		}

		var f = document.forms[0];
		for(i = 0; i < f.elements.length; i++) {
			if(f.elements[i].type == "checkbox") {
				if(f.elements[i].id.indexOf('list_') == 0) {
					lid = f.elements[i].id.replace('list_','');
					if(f.elements[i].checked) {
						if (!inArray(lid, LoadedLists)) {
							return true;
						}
					}
				}
			}
		}

		fieldorderbox = document.getElementById('fieldorder');
		for (vk = 0; vk < fieldorderbox.options.length; vk++) {
			fldval = fieldorderbox.options[vk].value;
			if (fldval != LoadedOrder[vk]) {
				return true;
			}
		}

		design = document.getElementById('FormDesign');
		form_design = design[design.selectedIndex].value;

		if (LoadDesign != form_design) {
			return true;
		}

		// send to friend only needs to check the design.
		if (LoadFormType == 'f') {
			return false;
		}

		changeformat_checked = document.getElementById('SubscriberChangeFormat').checked;
		if (changeformat_checked != LoadChangeFormat) {
			return true;
		}

		// since nothing else has changed, we can assume the form has not been changed.
		return false;
	}

	function ChangeFormType(mytype) {
		var selectedVal = mytype[mytype.selectedIndex].value;

		var fieldorderbox = document.getElementById('fieldorder');

		if (selectedVal == 'u') {
			document.getElementById('sendthanks').style.display = '';
			document.getElementById('requireconfirm').style.display = '';
			document.getElementById('chooseliststyle1').style.display = '';
			document.getElementById('chooseliststyle2').style.display = '';
			document.getElementById('chooseliststyle3').style.display = '';
			document.getElementById('chooseliststyle4').style.display = '';

			document.getElementById('captchaformstyle').style.display = '';

			document.getElementById('chooseformatstyle').style.display = 'none';
			document.getElementById('contactformstyle').style.display = 'none';
			document.getElementById('changeformatstyle').style.display = 'none';

			found_values = new Array();
			for (k = 0; k < fieldorderbox.options.length; k++) {
				found_values.push(fieldorderbox.options[k].value);
			}

			fieldorderbox.options.length = 0;

			ok_values = new Array('e', 'cl');

			list_count = CountLists();

			for (var fv = 0; fv < found_values.length; fv++) {
				var foundval = found_values[fv];
				if (!inArray(foundval, ok_values)) {
					continue;
				}

				if (list_count <= 1 && foundval == 'cl') {
					continue;
				}
				var size = fieldorderbox.options.length;

				fieldorderbox.options[size] = new Option(RequiredFields_Values[foundval], foundval);
			}

			return;
		}

		if (selectedVal == 'm') {
			document.getElementById('sendthanks').style.display = 'none';
			document.getElementById('requireconfirm').style.display = 'none';

			document.getElementById('chooseliststyle1').style.display = '';
			document.getElementById('chooseliststyle2').style.display = '';
			document.getElementById('chooseliststyle3').style.display = '';
			document.getElementById('chooseliststyle4').style.display = '';
			document.getElementById('chooseformatstyle').style.display = '';

			document.getElementById('contactformstyle').style.display = 'none';

			document.getElementById('captchaformstyle').style.display = '';

			found_values = new Array();
			for (k = 0; k < fieldorderbox.options.length; k++) {
				found_values.push(fieldorderbox.options[k].value);
			}

			fieldorderbox.options.length = 0;

			modify_ok_values = new Array('e', 'cl', 'cf');

			list_count = CountLists();

			for (var fv = 0; fv < found_values.length; fv++) {
				var foundval = found_values[fv];
				if (!inArray(foundval, modify_ok_values)) {
					continue;
				}

				if (list_count <= 1 && foundval == 'cl') {
					continue;
				}
				var size = fieldorderbox.options.length;

				fieldorderbox.options[size] = new Option(RequiredFields_Values[foundval], foundval);
			}

			return;
		}

		if (selectedVal == 'f') {
			document.getElementById('sendthanks').style.display = 'none';
			document.getElementById('requireconfirm').style.display = 'none';
			document.getElementById('chooseliststyle1').style.display = 'none';
			document.getElementById('chooseliststyle2').style.display = 'none';
			document.getElementById('chooseliststyle3').style.display = 'none';
			document.getElementById('chooseliststyle4').style.display = 'none';
			document.getElementById('chooseformatstyle').style.display = 'none';
			document.getElementById('contactformstyle').style.display = 'none';
			document.getElementById('captchaformstyle').style.display = 'none';
			document.getElementById('changeformatstyle').style.display = 'none';

			friend_ok_values = new Array('e');
			while(fieldorderbox.options.length > 1) {
				for (fk = 0; fk < fieldorderbox.options.length; fk++) {
					if (!inArray(fieldorderbox.options[fk].value, friend_ok_values)) {
						fieldorderbox.options[fk] = null;
					}
				}
			}

			return;
		}

		document.getElementById('sendthanks').style.display = '';
		document.getElementById('requireconfirm').style.display = '';
		document.getElementById('chooseliststyle1').style.display = '';
		document.getElementById('chooseliststyle2').style.display = '';
		document.getElementById('chooseliststyle3').style.display = '';
		document.getElementById('chooseliststyle4').style.display = '';
		document.getElementById('chooseformatstyle').style.display = '';
		document.getElementById('contactformstyle').style.display = '';
		document.getElementById('captchaformstyle').style.display = '';

		document.getElementById('changeformatstyle').style.display = 'none';

		found_values = new Array();
		for (k = 0; k < fieldorderbox.options.length; k++) {
			found_values.push(fieldorderbox.options[k].value);
		}

		for (k = 0; k < RequiredFields_Ids.length; k++) {
			id_key = RequiredFields_Ids[k];
			if (!inArray(id_key, found_values)) {
				size = fieldorderbox.options.length;
				fieldorderbox.options[size] = new Option(RequiredFields_Values[id_key], id_key);
			}
		}

		scf = document.getElementById('SubscriberChooseFormat');
		if (scf[scf.selectedIndex].value == 'c') {
			CheckFormat();
		}
	}

	function ShowPreview() {
		if (CheckForm()) {
			var f = document.forms[0];
			document.forms[0].target = "_blank";
			prevAction = document.forms[0].action;

			document.forms[0].action = 'index.php?Page=Forms&Action=Preview';
			document.forms[0].submit();
			document.forms[0].target = "";
			document.forms[0].action = prevAction;
		}
	}

	function ChangeDisplayOrderOptions(fld) {
		var fieldorderbox = document.getElementById('fieldorder');

		if (!fld.checked) {
			for (k = 0; k < fieldorderbox.options.length; k++) {
				fldvalue = fieldorderbox.options[k].value;
				if (fldvalue == 'cf') {
					fieldorderbox.options[k] = null;
					return;
				}
			}
			return;
		}
		CheckFormat();
	}

	function ChangeOrderOptions(fld) {
		var fieldorderbox = document.getElementById('fieldorder');

		var selectedVal = fld[fld.selectedIndex].value;
		if (selectedVal != 'c') {
			for (k = 0; k < fieldorderbox.options.length; k++) {
				fldvalue = fieldorderbox.options[k].value;
				if (fldvalue == 'cf') {
					fieldorderbox.options[k] = null;
					return;
				}
			}
			return;
		}
		CheckFormat();
	}

	function CheckFormat() {
		var fieldorderbox = document.getElementById('fieldorder');

		var found_format = 0;
		for (k = 0; k < fieldorderbox.options.length; k++) {
			fldvalue = fieldorderbox.options[k].value;
			if (fldvalue == 'cf') {
				found_format = 1;
				return;
			}
		}

		if (!found_format) {
			size = fieldorderbox.length;
			fieldorderbox.options[size] = new Option(RequiredFields_Values['cf'], 'cf');
		}
	}

</script>
