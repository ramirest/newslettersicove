<div id="div2" style="display:none">
	<div class="body">
		<br>%%GLOBAL_DisplayOpensIntro%%
		<br><br>
	</div>

	<div>
		%%GLOBAL_Calendar%%
	</div>

	<br>

	<table width="100%" border="0" class="text">
		<tr><td valign=top width="250" rowspan="2">
		<div class="MidHeading" style="width:100%"><img src="images/m_stats.gif" width="20" height="20" align="absMiddle">&nbsp;%%LNG_Opens_Summary%%</div>
				<UL class="Text"> 
					<LI>%%LNG_TotalEmails%%%%GLOBAL_TotalEmails%%</li>
					<LI>%%LNG_TotalOpens%%%%GLOBAL_TotalOpens%%</li>
					<LI>%%LNG_MostOpens%%%%GLOBAL_MostOpens%%</li>
					<LI>%%LNG_TotalUniqueOpens%%%%GLOBAL_TotalUniqueOpens%%</li>
					<LI>%%LNG_AverageOpens%%%%GLOBAL_AverageOpens%%</li>
				</UL>
		</td>
		<td align="center" style="font: arial; color: #5F5F5F;">
		<b>%%LNG_OpensChart%%</b>
		</td>
		</tr>
		<tr>
			<td>
				<script>PrintChart("%%GLOBAL_OpenChart%%");</script>
			</td>
		</tr>
	</table>

	<table width="100%" cellpadding="5" border="0" cellspacing="1" class="text" style="padding-top: 0px; margin-top: 0px;">
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
					%%LNG_DateOpened%%
				</td>
			</tr>
			%%GLOBAL_Stats_Step3_Opens_List%%
			<tr>
				<td align="right" colspan="2">
					%%GLOBAL_PagingBottom%%
				</td>
			</tr>
		</table>

</div>
