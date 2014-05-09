<div id="div7" style="display: none">
	<div class="body">
		<br>%%GLOBAL_SummaryIntro%%
		<br><br>
	</div>
	<div>
		%%GLOBAL_Calendar%%
	</div>

	<table border="0" cellspacing="0" cellpadding="0" width="100%" class="text">
		<tr>
			<td width="40%" valign="top">
				<table width="100%" border=0 cellspacing=1 cellpadding=5 class="text">
					<tr class="Heading3">
						<td nowrap align="left">
							%%LNG_DomainName%%
						</td>
						<td nowrap align="left">
							%%LNG_SubscribeCount%%
						</td>
						<td nowrap align="left">
							%%LNG_UnsubscribeCount%%
						</td>
						<td nowrap align="left">
							%%LNG_BounceCount%%
						</td>
						<td nowrap align="left">
							%%LNG_ForwardCount%%
						</td>
					</tr>
					%%GLOBAL_DisplayDomainList%%
					<tr class="Heading3">
						<td nowrap align="left">
							%%LNG_Total%%
						</td>
						<td nowrap align="left">
							%%GLOBAL_Total_domain_subscribes%%
						</td>
						<td nowrap align="left">
							%%GLOBAL_Total_domain_unsubscribes%%
						</td>
						<td nowrap align="left">
							%%GLOBAL_Total_domain_bounces%%
						</td>
						<td nowrap align="left">
							%%GLOBAL_Total_domain_forwards%%
						</td>
					</tr>
				</table>
			</td>
			<td width="10%">&nbsp;</td>

			<td width="40%" valign="top" align="center" style="font: arial; color: #5F5F5F;">
				<b>%%LNG_ListStatistics_Snapshot_PerDomain%%</b>
				<script>PrintChart("%%GLOBAL_DomainChart%%");</script>
			</td>
		</tr>
	</table>

</div>
