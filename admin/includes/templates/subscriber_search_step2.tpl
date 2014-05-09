<form method="post" action="index.php?Page=Subscribers&Action=%%GLOBAL_FormAction%%&SubAction=step3&List=%%GLOBAL_List%%">
	<table cellspacing="0" cellpadding="3" width="95%" align="center">
		<tr>
			<td class="heading1">
				%%GLOBAL_Heading%%
			</td>
		</tr>
		<tr>
			<td class="body">
				%%LNG_Subscribers_Search_Step2%%
			</td>
		</tr>
		<tr>
			<td>
				%%GLOBAL_Message%%
			</td>
		</tr>
		<tr>
			<td>
				<input class="formbutton" type="button" value="%%LNG_Cancel%%" onClick='if(confirm("%%LNG_Subscribers_Manage_CancelPrompt%%")) { document.location="index.php?Page=Subscribers" }'>
				<input class="formbutton" type="submit" value="%%LNG_Next%%">
				<br />
				&nbsp;
				<table border="0" cellspacing="0" cellpadding="2" width="100%" class="panel">
					<tr>
						<td colspan="2" class="heading2">
							&nbsp;&nbsp;%%LNG_FilterSearch%%
						</td>
					</tr>
					<tr>
						<td width="200" class="FieldLabel">
							%%TPL_Not_Required%%
							%%LNG_Email%%:&nbsp;
						</td>
						<td>
							<input type="text" name="emailaddress" value="" class="field250">&nbsp;%%LNG_HLP_FilterEmailAddress%%
						</td>
					</tr>
					<tr>
						<td width="200" class="FieldLabel">
							%%TPL_Not_Required%%
							%%LNG_Format%%:&nbsp;
						</td>
						<td>
							<select name="format" class="field250">
								%%GLOBAL_FormatList%%
							</select>&nbsp;%%LNG_HLP_FilterFormat%%
						</td>
					</tr>
					<tr>
						<td width="200" class="FieldLabel">
							%%TPL_Not_Required%%
							%%LNG_ConfirmedStatus%%:&nbsp;
						</td>
						<td>
							<select name="confirmed" class="field250">
								<option value="-1" SELECTED>%%LNG_Either_Confirmed%%</option>
								<option value="1">%%LNG_Confirmed%%</option>
								<option value="0">%%LNG_Unconfirmed%%</option>
							</select>&nbsp;%%LNG_HLP_FilterConfirmedStatus%%
						</td>
					</tr>
					<tr>
						<td width="200" class="FieldLabel">
							%%TPL_Not_Required%%
							%%LNG_Status%%:&nbsp;
						</td>
						<td>
							<select name="status" class="field250">
								<option value="-1">%%LNG_AllStatus%%</option>
								<option value="a" SELECTED>%%LNG_Active%%</option>
								<option value="b">%%LNG_Bounced%%</option>
								<option value="u">%%LNG_Unsubscribed%%</option>
							</select>&nbsp;%%LNG_HLP_FilterStatus%%
						</td>
					</tr>
					<tr>
						<td width="200" class="FieldLabel">
							%%TPL_Not_Required%%
							%%LNG_FilterByDate%%:
						</td>
						<td>
							<label for="datesearch[filter]"><input type="checkbox" name="datesearch[filter]" id="datesearch[filter]" value="1"%%GLOBAL_FilterChecked%% onClick="javascript: enableDate_SubscribeDate(this, 'datesearch')">&nbsp;%%LNG_YesFilterByDate%%</label>
							&nbsp;%%LNG_HLP_FilterByDate%%
							<br/>
							<div id="datesearch" style="display: none">
								<table border="0" cellspacing="0" cellpadding="0">
									<tr>
										<td valign="middle">
											<img src="images/nodejoin.gif" width="20" height="20" border="0">
										</td>
										<td>
											<select class="datefield" name="datesearch[type]" onChange="javascript: ChangeFilterOptionsSubscribeDate(this, 'datesearch');">
												<option value="after">%%LNG_After%%</option>
												<option value="before">%%LNG_Before%%</option>
												<option value="exactly">%%LNG_Exactly%%</option>
												<option value="between">%%LNG_Between%%</option>
											</select>
										</td>
										<td valign="top">
											%%GLOBAL_Display_subdate_date1_Field1%%
											%%GLOBAL_Display_subdate_date1_Field2%%
											%%GLOBAL_Display_subdate_date1_Field3%%
										</td>
									</tr>
									<tr style="display: none" id="datesearchdate2">
										<td colspan="2" align="right" valign="middle">
											<img src="images/nodejoin.gif" width="20" height="20" border="0">&nbsp;%%LNG_AND%%&nbsp;
										</td>
										<td valign="top">
											%%GLOBAL_Display_subdate_date2_Field1%%
											%%GLOBAL_Display_subdate_date2_Field2%%
											%%GLOBAL_Display_subdate_date2_Field3%%
										</td>
									</tr>
								</table>
							</div>
						</td>
					</tr>
					<tr>
						<td width="200" class="FieldLabel">
							%%TPL_Not_Required%%
							%%LNG_ClickedOnLink%%:
						</td>
						<td>
							<label for="clickedlink"><input type="checkbox" name="clickedlink" id="clickedlink" value="1" onClick="javascript: enable_ClickedLink(this, 'clicklink', 'linkid', '%%LNG_LoadingMessage%%')">&nbsp;%%LNG_YesFilterByLink%%</label>
							&nbsp;%%LNG_HLP_ClickedOnLink%%
							<br/>
							<div id="clicklink" style="display: none">
								<table border="0" cellspacing="0" cellpadding="0">
									<tr>
										<td valign="middle">
											<img src="images/nodejoin.gif" width="20" height="20" border="0">
										</td>
										<td>
											<select name="linkid" id="linkid">
											</select>
										</td>
									</tr>
								</table>
							</div>
						</td>
					</tr>
					<tr>
						<td width="200" class="FieldLabel">
							%%TPL_Not_Required%%
							%%LNG_OpenedNewsletter%%:
						</td>
						<td>
							<label for="openednewsletter"><input type="checkbox" name="openednewsletter" id="openednewsletter" value="1" onClick="javascript: enable_OpenedNewsletter(this, 'opennews', 'newsletterid', '%%LNG_LoadingMessage%%')">&nbsp;%%LNG_YesFilterByOpenedNewsletter%%</label>
							&nbsp;%%LNG_HLP_OpenedNewsletter%%
							<br/>
							<div id="opennews" style="display: none">
								<table border="0" cellspacing="0" cellpadding="0">
									<tr>
										<td valign="middle">
											<img src="images/nodejoin.gif" width="20" height="20" border="0">
										</td>
										<td>
											<select name="newsletterid" id="newsletterid">
											</select>
										</td>
									</tr>
								</table>
							</div>
						</td>
					</tr>
					%%GLOBAL_CustomFieldInfo%%
					<tr>
						<td>
							&nbsp;
						</td>
						<td height="35">
							<input class="formbutton" type="button" value="%%LNG_Cancel%%" onClick='if(confirm("%%LNG_Subscribers_Manage_CancelPrompt%%")) { document.location="index.php?Page=Subscribers" }'>
							<input class="formbutton" type="submit" value="%%LNG_Next%%">
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</form>

