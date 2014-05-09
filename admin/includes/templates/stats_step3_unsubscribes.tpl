<div id="div5" style="display:none">
	<div class="body">
		<br>%%GLOBAL_DisplayUnsubscribesIntro%%
		<br><br>
	</div>

	<div>
		%%GLOBAL_Calendar%%
	</div>
	<br>

<table width="100%" border="0" class="text">
		<tr><td valign=top width="250" rowspan="2">
		<div class="MidHeading" style="width:100%"><img src="images/m_stats.gif" width="20" height="20" align="absMiddle">&nbsp;%%LNG_Unsubscribe_Summary%%</div>
						<UL class="Text"> 
							<LI>%%LNG_Stats_TotalUnsubscribes%%: %%GLOBAL_TotalUnsubscribes%%</li>
							<LI>%%LNG_Stats_MostUnsubscribes%%: %%GLOBAL_MostUnsubscribes%%</li>
						</UL>
		</td>
		<td align="center" style="font: arial; color: #5F5F5F;">
		<b>%%LNG_UnsubscribesChart%%</b>
		</td>
		</tr>
		<tr>
			<td>
				<script>PrintChart("%%GLOBAL_UnsubscribeChart%%");</script>
			</td>
		</tr>
	</table>
		<table cellpadding="5" border="0" cellspacing="1" width="100%" class=text style="padding-top: 0px; margin-top: 0px;">
			<tr>
				<td width="100%" colspan="2">
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
				<td width="30%" nowrap align="left">
					%%LNG_EmailAddress%%
				</td>
				<td width="70%" nowrap align="left">
					%%LNG_UnsubscribeDate%%
				</td>
			</tr>
			%%GLOBAL_Stats_Step3_Unsubscribes_List%%
			<tr>
				<td align="right" colspan="2">
					%%GLOBAL_PagingBottom%%
				</td>
			</tr>
		</table>
		<br><br>
	</div>
</div>
