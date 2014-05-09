<div id="div6" style="display:none">

	<div class="body">
		<br>%%GLOBAL_DisplayForwardsIntro%%
		<br><br>
	</div>

	<div>
		%%GLOBAL_Calendar%%
	</div>

	<br>

	<table width="100%" border="0" class="text">
		<tr><td valign=top width="250" rowspan="2">
		<div class="MidHeading" style="width:100%"><img src="images/m_stats.gif" width="20" height="20" align="absMiddle">&nbsp;%%LNG_Forwards_Summary%%</div>
				<UL class="Text"> 
					<LI>%%LNG_ListStatsTotalForwards%%%%GLOBAL_TotalForwards%%</li>
					<LI>%%LNG_ListStatsTotalForwardSignups%%%%GLOBAL_TotalForwardSignups%%</li>
				</UL>
		</td>
		<td align="center" style="font: arial; color: #5F5F5F;">
		<b>%%LNG_ForwardsChart%%</b>
		</td>
		</tr>
		<tr>
			<td>
				<script>PrintChart("%%GLOBAL_ForwardsChart%%");</script>
			</td>
		</tr>
	</table>

	<table width="100%" cellpadding="5" border="0" cellspacing="1" class="text" style="padding-top: 0px; margin-top: 0px;">
			<tr>
				<td width="100%" colspan="4">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td valign="top">
								&nbsp;
							</td>
							<td valign="top" align="right">
								%%GLOBAL_Paging%%
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr class="Heading3">
				<td nowrap align="left">
					%%LNG_ForwardedBy%%
				</td>
				<td nowrap align="left">
					%%LNG_ForwardedTo%%
				</td>
				<td nowrap align="left">
					%%LNG_ForwardTime%%
				</td>
				<td nowrap align="left">
					%%LNG_HasSubscribed%%
				</td>
			</tr>
			%%GLOBAL_Stats_Step3_Forwards_List%%
			<tr>
				<td align="right" colspan="4">
					%%GLOBAL_PagingBottom%%
				</td>
			</tr>
		</table>
		<br><br>
	</div>
</div>
