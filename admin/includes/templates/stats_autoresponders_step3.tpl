<table cellspacing="0" cellpadding="3" width="95%" align="center">
	<tr>
		<td>

<div class="heading1">%%LNG_Stats_AutoresponderStatistics%%</div>

<script language="javascript">
	var TabSize = 6;
</script>

<div>
	<br>

	<ul id="tabnav">
		<li><a href="#" class="active" onClick="ShowTab(1)" id="tab1">%%LNG_AutoresponderStatistics_Snapshot%%</a></li>
		<li><a href="#" onClick="ShowTab(2)" id="tab2">%%LNG_AutoresponderStatistics_Snapshot_OpenStats%%</a></li>
		<li><a href="#" onClick="ShowTab(3)" id="tab3">%%LNG_AutoresponderStatistics_Snapshot_LinkStats%%</a></li>
		<li><a href="#" onClick="ShowTab(4)" id="tab4">%%LNG_AutoresponderStatistics_Snapshot_BounceStats%%</a></li>
		<li><a href="#" onClick="ShowTab(5)" id="tab5">%%LNG_AutoresponderStatistics_Snapshot_UnsubscribeStats%%</a></li>
		<li><a href="#" onClick="ShowTab(6)" id="tab6">%%LNG_AutoresponderStatistics_Snapshot_ForwardStats%%</a></li>
	</ul>

</div>


<div id="div1">
	<div class="body">
		<br>%%GLOBAL_SummaryIntro%%
	</div>
	<table width="100%" border="0">
		<tr>
			<td width="45%" valign="top" rowspan="2">
				<table border=0 width="100%" class=text>
					<tr class="Heading3">
						<td colspan="2" nowrap align="left">
							%%LNG_AutoresponderStatistics_Snapshot_Heading%%
						</td>
					</tr>
					<tr class="GridRow">
						<td width="30%" height="22" nowrap align="left">
							&nbsp;%%LNG_AutoresponderSubject%%
						</td>
						<td width="70%" nowrap align="left">
							<a title="%%LNG_PreviewThisAutoresponder%%" href="#" onclick="PreparePreview(); return false;">%%GLOBAL_AutoresponderSubject%%</a>
						</td>
					</tr>
					<tr class="GridRow">
						<td width="30%" height="22" nowrap align="left">
							&nbsp;%%LNG_SentToList%%
						</td>
						<td width="70%" nowrap align="left">
							%%GLOBAL_MailingList%%
						</td>
					</tr>
					<tr class="GridRow">
						<td width="30%" height="22" nowrap align="left" valign="top">
							&nbsp;%%LNG_AutoresponderStatistics_SentTo%%
						</td>
						<td width="70%" nowrap align="left">
							%%GLOBAL_SentToDetails%%
						</td>
					</tr>
					<tr class="GridRow">
						<td width="30%" height="22" nowrap align="left" valign="top">
							&nbsp;%%LNG_AutoresponderStatistics_SentWhen%%
						</td>
						<td width="70%" nowrap align="left">
							%%GLOBAL_SentWhen%%
						</td>
					</tr>
					<tr class="GridRow">
						<td width="30%" height="22" nowrap align="left" valign="top">
							&nbsp;%%LNG_AutoresponderStatistics_CreatedBy%%
						</td>
						<td width="70%" nowrap align="left">
							<a href="mailto:%%GLOBAL_UserEmail%%">%%GLOBAL_CreatedBy%%</a>
						</td>
					</tr>
					<tr class="GridRow">
						<td width="30%" height="22" nowrap align="left" valign="top">
							&nbsp;%%LNG_AutoresponderStatistics_Opened%%
						</td>
						<td width="70%" nowrap align="left">
							<a title="Click here to see the email address of everyone that opened this newsletter" href="%%GLOBAL_OpensURL%%">%%GLOBAL_TotalOpens%%</a> / <a title="Click here to view unique email addresses that opened this newsletter" href="%%GLOBAL_UniqueOpensURL%%">%%GLOBAL_UniqueOpens%%</a>
						</td>
					</tr>
					<tr class="GridRow">
						<td width="30%" height="22" nowrap align="left" valign="top">
							&nbsp;%%LNG_AutoresponderStatistics_Bounced%%
						</td>
						<td width="70%" nowrap align="left">
							%%GLOBAL_TotalBounces%%
						</td>
					</tr>
				</table>
			</td>

			<td align=center class=text style="font: arial; color: #5F5F5F; padding-top:20px"><b>Autoresponder Summary Chart</b></td>

			</tr><tr>
			<td width="55%">
				<script>PrintChart("%%GLOBAL_SummaryChart%%");</script>
			</td>
		</tr>
	</table>
</div>
%%GLOBAL_OpensPage%%
%%GLOBAL_LinksPage%%
%%GLOBAL_BouncesPage%%
%%GLOBAL_UnsubscribesPage%%
%%GLOBAL_ForwardsPage%%

<script>
	function PreparePreview() {
		var openurl = "index.php?Page=Autoresponders&Action=View&id=%%GLOBAL_AutoresponderID%%";
		window.open(openurl, "pp");
	}
</script>

		</td>
	</tr>
</table>