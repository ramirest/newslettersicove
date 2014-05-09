<table cellspacing="0" cellpadding="3" width="95%" align="center">
	<tr>
		<td class="heading1">%%LNG_Stats_NewsletterStatistics%%</td>
	</tr>
	<tr>
		<td class="body">%%LNG_Stats_Newsletters_Step1_Intro%%</td>
	</tr>
	<tr>
		<td>
			%%GLOBAL_Message%%
		</td>
	</tr>
	<tr>
		<td class="body">
			<form name="mystatsform" method="post" action="index.php?Page=Stats&Action=Newsletters&SubAction=Delete" onsubmit="return DeleteSelectedStats();">
				<table width="100%" border="0">
					<tr>
						<td valign="top">
							<input type="submit" name="DeleteStatsButton" value="%%LNG_Delete_Stats_Selected%%" class="smallbutton">
						</td>
						<td align="right">
							%%TPL_Paging%%
						</td>
					</tr>
				</table>
				<table border=0 cellspacing="1" cellpadding="2" width=100% class=text>
					<tr class="Heading3">
						<td width="5"><input type="checkbox" name="toggle" onClick="javascript: toggleAllCheckboxes(this);"></td>
						<td width="5"></td>
						<td width="30%">
							%%LNG_NewsletterName%%&nbsp;<a href='index.php?Page=Stats&Action=Newsletters&SubAction=Step1&SortBy=Newsletter&Direction=Up'><img src="images/sortup.gif" border=0></a>&nbsp;<a href='index.php?Page=Stats&Action=Newsletters&SubAction=Step1&SortBy=Newsletter&Direction=Down'><img src="images/sortdown.gif" border=0></a>
						</td>
						<td width="26%">
							%%LNG_ListName%%&nbsp;<a href='index.php?Page=Stats&Action=Newsletters&SubAction=Step1&SortBy=List&Direction=Up'><img src="images/sortup.gif" border=0></a>&nbsp;<a href='index.php?Page=Stats&Action=Newsletters&SubAction=Step1&SortBy=List&Direction=Down'><img src="images/sortdown.gif" border=0></a>
						</td>
						<td width="22%" nowrap>
							%%LNG_DateStarted%%&nbsp;<a href='index.php?Page=Stats&Action=Newsletters&SubAction=Step1&SortBy=StartDate&Direction=Up'><img src="images/sortup.gif" border=0></a>&nbsp;<a href='index.php?Page=Stats&Action=Newsletters&SubAction=Step1&SortBy=StartDate&Direction=Down'><img src="images/sortdown.gif" border=0></a>
						</td>
						<td width="22%" nowrap>
							%%LNG_DateFinished%%&nbsp;<a href='index.php?Page=Stats&Action=Newsletters&SubAction=Step1&SortBy=FinishDate&Direction=Up'><img src="images/sortup.gif" border=0></a>&nbsp;<a href='index.php?Page=Stats&Action=Newsletters&SubAction=Step1&SortBy=FinishDate&Direction=Down'><img src="images/sortdown.gif" border=0></a>
						</td>
						<td width="90" nowrap>
							%%LNG_TotalRecipients%%&nbsp;<a href='index.php?Page=Stats&Action=Newsletters&SubAction=Step1&SortBy=Recipients&Direction=Up'><img src="images/sortup.gif" border=0></a>&nbsp;<a href='index.php?Page=Stats&Action=Newsletters&SubAction=Step1&SortBy=Recipients&Direction=Down'><img src="images/sortdown.gif" border=0></a>
						</td>
						<td width="110" nowrap>
							%%LNG_UnsubscribeCount%%&nbsp;<a href='index.php?Page=Stats&Action=Newsletters&SubAction=Step1&SortBy=Unsubscribes&Direction=Up'><img src="images/sortup.gif" border=0></a>&nbsp;<a href='index.php?Page=Stats&Action=Newsletters&SubAction=Step1&SortBy=Unsubscribes&Direction=Down'><img src="images/sortdown.gif" border=0></a>
						</td>
						<td width="90" nowrap>
							%%LNG_BounceCount%%&nbsp;<a href='index.php?Page=Stats&Action=Newsletters&SubAction=Step1&SortBy=Bounces&Direction=Up'><img src="images/sortup.gif" border=0></a>&nbsp;<a href='index.php?Page=Stats&Action=Newsletters&SubAction=Step1&SortBy=Bounces&Direction=Down'><img src="images/sortdown.gif" border=0></a>
						</td>
						<td width="100">
							%%LNG_Action%%
						</td>
					</tr>
				%%TPL_Stats_Newsletter_Manage_Row%%
				</table>
			</form>
			%%TPL_Paging_Bottom%%
		</td>
	</tr>
</table>

<script language="javascript">

	function DeleteSelectedStats() {
		formObj = document.forms['mystatsform'];
		stats_found = 0;
		for (var i=0;i < formObj.length;i++)
		{
			fldObj = formObj.elements[i];
			if (fldObj.type == 'checkbox')
			{
				if (fldObj.checked) {
					stats_found++;
				}
			}
		}

		if (stats_found <= 0) {
			alert("%%LNG_ChooseStatsToDelete%%");
			return false;
		}

		if (confirm("%%LNG_DeleteStatsPrompt%%")) {
			return true;
		}
		return false;
	}

	function ConfirmDelete(StatsID) {
		if (!StatsID) {
			return false;
		}

		if (confirm("%%LNG_DeleteStatsPrompt%%")) {
			document.location='index.php?Page=Stats&Action=Newsletters&SubAction=Delete&id=' + StatsID;
		}
	}
</script>
