<div id="div3" style="display:none">
	<div class="body">
		<br>%%GLOBAL_DisplayLinksIntro%%
		<br><br>
	</div>

	<div>
		%%GLOBAL_Calendar%%
	</div>
	<br>

	<table width="100%" border="0" class="text">
		<tr><td valign=top width="250" rowspan="2">
		<div class="MidHeading" style="width:100%"><img src="images/m_stats.gif" width="20" height="20" align="absMiddle">&nbsp;%%LNG_LinkClicks_Summary%%</div>
			<UL class="Text"> 
				<LI>%%LNG_Stats_TotalClicks%%: %%GLOBAL_TotalClicks%%</li>
				<LI>%%LNG_Stats_TotalUniqueClicks%%: %%GLOBAL_TotalUniqueClicks%%</li>
				<LI>%%LNG_Stats_MostPopularLink%%: <a href="%%GLOBAL_MostPopularLink%%" title="%%GLOBAL_MostPopularLink%%" target="_blank">%%GLOBAL_MostPopularLink_Short%%</a></li>
				<LI>%%LNG_Stats_AverageClicks%%: %%GLOBAL_AverageClicks%%</li>
			</UL>
		</td>
		<td align="center" style="font: arial; color: #5F5F5F;">
		<b>%%LNG_LinksClickedChart%%</b>
		</td>
		</tr>
		<tr>
			<td>
				<script>PrintChart("%%GLOBAL_LinksChart%%");</script>
			</td>
		</tr>
	</table>

		<table cellpadding="5" border="0" cellspacing="1" width="100%" class=text style="padding-top: 0px; margin-top: 0px;">
			<tr>
				<td width="100%" colspan="3">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td valign="bottom">
								%%GLOBAL_StatsLinkDropDown%%
							</td>
							<td valign="top" align="right">
								%%GLOBAL_Paging%%
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr class="Heading3">
				<td width="30%" nowrap align="left">
					%%LNG_EmailAddress%%
				</td>
				<td width="50%" nowrap align="left">
					%%LNG_LinkClicked%%
				</td>
				<td width="20%" nowrap align="left">
					%%LNG_LinkClickTime%%
				</td>
			</tr>
			%%GLOBAL_Stats_Step3_Links_List%%
			<tr>
				<td align="right" colspan="3">
					%%GLOBAL_PagingBottom%%
				</td>
			</tr>
		</table>
		<br><br>
	</div>
</div>
