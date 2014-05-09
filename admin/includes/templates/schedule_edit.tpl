<form method="post" action="index.php?Page=Schedule&Action=Update&job=%%GLOBAL_JobID%%">
	<table cellspacing="0" cellpadding="3" width="95%" align="center">
		<tr>
			<td class="heading1">
				%%LNG_Schedule_Edit%%
			</td>
		</tr>
		<tr>
			<td class="body">
				%%LNG_Help_Schedule_Edit%%
			</td>
		</tr>
		<tr>
			<td class="body">
				%%GLOBAL_Message%%
			</td>
		</tr>
		<tr>
			<td>
				<input class="formbutton" type="button" value="%%LNG_Cancel%%" onClick='if(confirm("%%LNG_ScheduleEditCancel_Prompt%%")) { document.location="index.php?Page=Schedule" }'>
				<input class="formbutton" type="submit" value="%%LNG_Save%%">
				<br />
				&nbsp;
				<table border="0" cellspacing="0" cellpadding="2" width="100%" class="panel">
					<tr>
						<td colspan="2" class="heading2">
							&nbsp;&nbsp;%%LNG_NewsletterDetails%%
						</td>
					</tr>
					<tr>
						<td class="FieldLabel">
							%%TPL_Not_Required%%
							%%LNG_MailingList%%:&nbsp;
						</td>
						<td>
							%%GLOBAL_Send_SubscriberList%%
						</td>
					</tr>
					<tr>
						<td class="FieldLabel">
							%%TPL_Not_Required%%
							%%LNG_SendNewsletter%%:&nbsp;
						</td>
						<td>
							%%GLOBAL_Send_NewsletterName%%&nbsp;&nbsp;
							<a href="#" onclick="javascript: PreparePreview(); return false;"><img src="images/magnify.gif" border="0">&nbsp;%%LNG_Preview%%</a>
						</td>
					</tr>
					<tr>
						<td width="200" class="FieldLabel">
							%%TPL_Required%%
							%%LNG_SendTime%%:&nbsp;
						</td>
						<td>
							<script language="JavaScript" src="includes/templates/timepicker_lib.js" type="text/javascript"></script>
							%%GLOBAL_SendTimeBox%%&nbsp;%%LNG_HLP_SendTime%%
						</td>
					</tr>
					<tr>
						<td>
							&nbsp;
						</td>
						<td>
							<input class="formbutton" type="button" value="%%LNG_Cancel%%" onClick='if(confirm("%%LNG_ScheduleEditCancel_Prompt%%")) { document.location="index.php?Page=Schedule" }'>
							<input class="formbutton" type="submit" value="%%LNG_Save%%">
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</form>

<script language="javascript">
	function PreparePreview() {
		var baseurl = "index.php?Page=Newsletters&Action=View&id=";
		var realId = %%GLOBAL_NewsletterID%%;
		window.open(baseurl + realId , "pp");
	}
</script>