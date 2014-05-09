<div id="div4" style="display:none">
	<div class="body">
		<br>%%GLOBAL_DisplayBouncesIntro%%
		<br><br>
	</div>

	<div>
		%%GLOBAL_Calendar%%
	</div>
	<br>

	<table width="100%" border="0" class="text">
		<tr><td valign=top width="250" rowspan="2">
		<div class="MidHeading" style="width:100%"><img src="images/m_stats.gif" width="20" height="20" align="absMiddle">&nbsp;%%LNG_Bounce_Summary%%</div>
			<UL class="Text">
				<LI>%%LNG_Stats_TotalBounces%%%%GLOBAL_TotalBounceCount%%</li>
				<LI>%%LNG_Stats_TotalSoftBounces%%%%GLOBAL_TotalSoftBounceCount%%</li>
				<LI>%%LNG_Stats_TotalHardBounces%%%%GLOBAL_TotalHardBounceCount%%</li>
			</UL>
		</td>
		<td align="center" style="font: arial; color: #5F5F5F;">
		<b>%%LNG_BounceChart%%</b>
		</td>
		</tr>
		<tr>
			<td>
				<script>PrintChart("%%GLOBAL_BounceChart%%");</script>
			</td>
		</tr>
	</table>

		<table border=0 width="100%" class=text style="padding-top: 0px; margin-top: 0px;">
			<tr>
				<td width="100%" colspan="4">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td valign="bottom">
								<select name="choosebouncetype" id="choosebt">
									%%GLOBAL_StatsBounceList%%
								</select>
								<input type="button" value="%%LNG_Go%%" class="body" onclick="ChangeBounceType();">
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
					%%LNG_EmailAddress%%
				</td>
				<td nowrap align="left">
					%%LNG_BounceType%%
				</td>
				<td nowrap align="left">
					%%LNG_BounceRule%%
				</td>
				<td nowrap align="left">
					%%LNG_BounceDate%%
				</td>
			</tr>
			%%GLOBAL_Stats_Step3_Bounces_List%%
			<tr>
				<td align="right" colspan="4">
					%%GLOBAL_PagingBottom%%
				</td>
			</tr>
		</table>
		<br><br>
	</div>
</div>

<script>
	function ChangeBounceType() {
		cbouncetype = document.getElementById('choosebt');
		selected = cbouncetype.selectedIndex;
		bouncetype = cbouncetype.options[selected].value;
		window.document.location = 'index.php?Page=Stats&Action=%%GLOBAL_BounceAction%%&SubAction=ViewSummary&id=%%GLOBAL_StatID%%&tab=4&bouncetype=' + bouncetype;
	}
</script>
