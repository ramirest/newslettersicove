<table cellspacing="0" cellpadding="3" width="95%" align="center">
	<tr>
		<td class="heading1">%%LNG_AutorespondersManage%%</td>
	</tr>
	<tr>
		<td class="body">%%LNG_Help_AutorespondersManage%%</td>
	</tr>
	<tr>
		<td>%%GLOBAL_Message%%</td>
	</tr>
	<tr>
		<td>%%GLOBAL_CronWarning%%</td>
	</tr>
	<tr>
		<td class="body">
			<table width="100%" border="0">
				<tr>
					<td valign="bottom">
						<div style="padding-top:10px; padding-bottom:10px">
							%%GLOBAL_Autoresponders_AddButton%%
						</div>
						<form name="ActionAutorespondersForm" method="post" action="index.php?Page=Autoresponders&Action=Change&list=%%GLOBAL_List%%" onsubmit="return ConfirmChanges();" style="margin: 0px; padding: 0px;">
							<select name="ChangeType">
								<option value="" SELECTED>%%LNG_ChooseAction%%</option>
								%%GLOBAL_Option_DeleteAutoresponder%%
								%%GLOBAL_Option_ActivateAutoresponder%%
							</select>
							<input type="submit" value="%%LNG_Go%%" class="text">
					</td>
					<td align="right" valign="bottom">
						%%TPL_Paging%%
					</td>
				</tr>
			</table>
			<table border=0 cellspacing="1" cellpadding="2" width=100% class=text>
				<tr class="Heading3">
					<td width="5" nowrap>
						<input type="checkbox" name="toggle" onClick="javascript: toggleAllCheckboxes(this);">
					</td>
					<td width="5">&nbsp;</td>
					<td width="40%">
						%%LNG_AutoresponderName%%&nbsp;<a href='index.php?Page=%%PAGE%%&SortBy=Name&Direction=Up'><img src="images/sortup.gif" border=0></a>&nbsp;<a href='index.php?Page=%%PAGE%%&SortBy=Name&Direction=Down'><img src="images/sortdown.gif" border=0></a>
					</td>
					<td width="13%">
						%%LNG_Created%%&nbsp;<a href='index.php?Page=%%PAGE%%&SortBy=Date&Direction=Up'><img src="images/sortup.gif" border=0></a>&nbsp;<a href='index.php?Page=%%PAGE%%&SortBy=Date&Direction=Down'><img src="images/sortdown.gif" border=0></a>
					</td>
					<td width="27%" valign="top">
						%%LNG_SentWhen%%&nbsp;<a href='index.php?Page=%%PAGE%%&SortBy=Hours&Direction=Up'><img src="images/sortup.gif" border=0></a>&nbsp;<a href='index.php?Page=%%PAGE%%&SortBy=Hours&Direction=Down'><img src="images/sortdown.gif" border=0></a>
					</td>
					<td width="15%">
						%%LNG_Format%%
					</td>
					<td width="5%">
						%%LNG_Active%%
					</td>
					<td width="120">
						%%LNG_Action%%
					</td>
				</tr>
				%%TPL_Autoresponders_Manage_Row%%
			</table>
			%%TPL_Paging_Bottom%%
		</td>
	</tr>
</table>

<script language="javascript">

	function ConfirmChanges() {
		formObj = document.ActionAutorespondersForm;

		if (formObj.ChangeType.selectedIndex == 0) {
			alert("%%LNG_PleaseChooseAction%%");
			formObj.ChangeType.focus();
			return false;
		}

		selectedValue = formObj.ChangeType[formObj.ChangeType.selectedIndex].value;

		autos_found = 0;
		for (var i=0;i < formObj.length;i++)
		{
			fldObj = formObj.elements[i];
			if (fldObj.type == 'checkbox')
			{
				if (fldObj.checked) {
					autos_found++;
				}
			}
		}

		if (autos_found <= 0) {
			alert("%%LNG_ChooseAutoresponders%%");
			return false;
		}

		if (confirm("%%LNG_ConfirmChanges%%")) {
			return true;
		}
		return false;
	}

	function ConfirmDelete(AutoresponderID) {
		if (!AutoresponderID) {
			return false;
		}
		if (confirm("%%LNG_DeleteAutoresponderPrompt%%")) {
			document.location='index.php?Page=%%PAGE%%&Action=Delete&list=%%GLOBAL_list%%&id=' + AutoresponderID;
		}
	}
</script>
