<form method="post" action="index.php?Page=Subscribers&Action=Search&SubAction=step2">
	<table cellspacing="0" cellpadding="3" width="95%" align="center">
		<tr>
			<td class="heading1">
				%%LNG_Subscribers_Search%%
			</td>
		</tr>
		<tr>
			<td class="body">
				%%LNG_Subscribers_Search_Intro%%
			</td>
		</tr>
		<tr>
			<td>
				<input class="formbutton" type="button" value="%%LNG_Cancel%%" onClick='if(confirm("%%LNG_Subscribers_Search_CancelPrompt%%")) { document.location="index.php?Page=Subscribers" }'>&nbsp;&nbsp;
				<input class="formbutton" type="submit" value="%%LNG_Step2%%">
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
							%%TPL_Required%%
							%%LNG_MailingList%%:&nbsp;
						</td>
						<td>
							<select name="list[]" style="width: 350px" size="9" multiple onDblClick="this.form.submit()">
								%%GLOBAL_SelectList%%
							</select>&nbsp;%%LNG_HLP_Subscribers_Search_ListsMultiple%%
						</td>
					</tr>
					<tr>
						<td colspan="2" class="EmptyRow">
							&nbsp;
						</td>
					</tr>
					<tr>
						<td width="200" class="FieldLabel">
							%%TPL_Not_Required%%
							%%LNG_Email%%:&nbsp;
						</td>
						<td>
							<input type="text" name="emailaddress" value="" class="field250">
						</td>
					</tr>
					<tr>
						<td width="200" class="FieldLabel">
							%%TPL_Not_Required%%
							%%LNG_Format%%:&nbsp;
						</td>
						<td>
							<select name="format" class="field250">
								<option value="-1" selected>%%LNG_Either_Format%%</option>
								<option value="h">%%LNG_Format_HTML%%</option>
								<option value="t">%%LNG_Format_Text%%</option>
							</select>
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
							</select>
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
							</select>
						</td>
					</tr>
					<tr>
						<td width="200" class="FieldLabel">
							%%LNG_FilterByDate%%
						</td>
						<td>
							<input type="checkbox" name="datesearch[filter]" id="datesearch[filter]" value="1"%%GLOBAL_FilterChecked%% onClick="javascript: enableDate_SubscribeDate(this, 'datesearch')">&nbsp;%%LNG_YesFilterByDate%%<br/>
							<div id="datesearch" style="display: none">
								<table border="0" cellspacing="0" cellpadding="0">
									<tr>
										<td valign="middle">
											<img src="images/nodejoin.gif" width="20" height="20" border="0">
										</td>
										<td>
											<select class="datefield" name="datesearch[type]" onClick="javascript: ChangeFilterOptionsSubscribeDate(this, 'datesearch');">
												<option value="after">%%LNG_After%%</option>
												<option value="before">%%LNG_Before%%</option>
												<option value="exactly">%%LNG_Exactly%%</option>
												<option value="between">%%LNG_Between%%</option>
											</select>
										</td>
										<td valign="top">
											%%GLOBAL_Display_date1_Field1%%
											%%GLOBAL_Display_date1_Field2%%
											%%GLOBAL_Display_date1_Field3%%
										</td>
									</tr>
									<tr style="display: none" id="datesearchdate2">
										<td colspan="2" align="right" valign="middle">
											<img src="images/nodejoin.gif" width="20" height="20" border="0">&nbsp;%%LNG_AND%%&nbsp;
										</td>
										<td valign="top">
											%%GLOBAL_Display_date2_Field1%%
											%%GLOBAL_Display_date2_Field2%%
											%%GLOBAL_Display_date2_Field3%%
										</td>
									</tr>
								</table>
							</div>
						</td>
					</tr>
					<tr>
						<td colspan="2" class="EmptyRow">
							&nbsp;
						</td>
					</tr>
					<tr>
						<td>
							&nbsp;
						</td>
						<td>
							<input class="formbutton" type="button" value="%%LNG_Cancel%%" onClick='if(confirm("%%LNG_Subscribers_Search_CancelPrompt%%")) { document.location="index.php?Page=Subscribers&Action=Search&SubAction=step2" }'>&nbsp;&nbsp;
							<input class="formbutton" type="submit" value="%%LNG_Step2%%">
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</form>

