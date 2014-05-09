<table cellspacing="0" cellpadding="3" width="95%" align="center">
	<tr>
		<td>

<div class="heading1">%%LNG_Stats_ListStatistics%%</div>

<script language="javascript">
	var TabSize = 7;
</script>

<div>
	<br>
	<ul id="tabnav">
		<li><a href="#" class="active" onClick="ShowTab(1)" id="tab1">%%LNG_ListStatistics_Snapshot%%</a></li>
		<li><a href="#" onClick="ShowTab(7)" id="tab7">%%LNG_ListStatistics_Snapshot_PerDomain%%</a></li>
		<li><a href="#" onClick="ShowTab(2)" id="tab2">%%LNG_ListStatistics_Snapshot_OpenStats%%</a></li>
		<li><a href="#" onClick="ShowTab(3)" id="tab3">%%LNG_ListStatistics_Snapshot_LinksStats%%</a></li>
		<li><a href="#" onClick="ShowTab(4)" id="tab4">%%LNG_ListStatistics_Snapshot_BounceStats%%</a></li>
		<li><a href="#" onClick="ShowTab(5)" id="tab5">%%LNG_ListStatistics_Snapshot_UnsubscribeStats%%</a></li>
		<li><a href="#" onClick="ShowTab(6)" id="tab6">%%LNG_ListStatistics_Snapshot_ForwardsStats%%</a></li>
	</ul>
</div>


<div id="div1">
	<div class="body">
		<br>%%GLOBAL_SummaryIntro%%
		<br><br>
	</div>
	<div>
		%%GLOBAL_Calendar%%
	</div>

	<br>

<table width="100%" border="0" class="text">
	<tr>
		<td valign=top width="250" rowspan="2">
			<div class="MidHeading" style="width:100%"><img src="images/m_stats.gif" width="20" height="20" align="absMiddle">&nbsp;Subscriber Summary</div>
				<UL class="Text">
					<LI>%%LNG_ListStatsTotalSubscribers%%%%GLOBAL_Total_subscribes%%</li>
					<LI>%%LNG_ListStatsTotalUnsubscribes%%%%GLOBAL_Total_unsubscribes%%</li>
					<LI>%%LNG_Stats_TotalBounces%%%%GLOBAL_Total_bounces%%</li>
					<LI>%%LNG_ListStatsTotalForwards%%%%GLOBAL_Total_forwards%%</li>
				</UL>
		</td>
		<td align="center" style="font: arial; color: #5F5F5F;">
			<b>%%LNG_List_Summary_Graph_subscribersummary%%</b>
		</td>
	</tr>
	<tr>
		<td>
			<script>PrintChart("%%GLOBAL_SummaryChart%%");</script>
		</td>
	</tr>
</table>

<table width="100%" border="0">
	<tr>
		<td width="100%" valign="top">

				<table border="0" width="100%" class=text cellpadding="5" cellspacing="1">
				<tr class="Heading3">
						<td nowrap align="left">
							%%LNG_DateTime%%
						</td>
						<td nowrap align="left">
							%%LNG_Subscribes%%
						</td>
						<td nowrap align="left">
							%%LNG_Unsubscribes%%
						</td>
						<td nowrap align="left">
							%%LNG_Bounces%%
						</td>
						<td nowrap align="left">
							%%LNG_Forwards%%
						</td>
					</tr>
					%%GLOBAL_DisplayList%%
					<tr class="Heading3">
						<td nowrap align="left">
							%%LNG_Total%%
						</td>
						<td nowrap align="left">
							%%GLOBAL_Total_subscribes%%
						</td>
						<td nowrap align="left">
							%%GLOBAL_Total_unsubscribes%%
						</td>
						<td nowrap align="left">
							%%GLOBAL_Total_bounces%%
						</td>
						<td nowrap align="left">
							%%GLOBAL_Total_forwards%%
						</td>
					</tr>
				</table>
			</td>
	</table>

</div>

%%TPL_DomainPage%%

%%TPL_OpensPage%%
%%TPL_LinksPage%%
%%TPL_BouncesPage%%
%%TPL_UnsubscribesPage%%
%%TPL_ForwardsPage%%

		</td>
	</tr>
</table>