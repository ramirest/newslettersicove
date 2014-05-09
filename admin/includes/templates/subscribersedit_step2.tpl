<form method="post" action="index.php?Page=Subscribers&Action=Edit&SubAction=Save&List=%%GLOBAL_list%%">
	<table cellspacing="0" cellpadding="3" width="95%" align="center">
		<tr>
			<td class="heading1">
				%%LNG_Subscribers_Edit%%
			</td>
		</tr>
		<tr>
			<td class="body">
				%%LNG_Subscribers_Edit_Intro%%
			</td>
		</tr>
		<tr>
			<td>
				%%GLOBAL_Message%%
			</td>
		</tr>
		<tr>
			<td>
				<input class="formbutton" type="button" value="%%LNG_Cancel%%" onClick='if(confirm("%%LNG_Subscribers_Edit_CancelPrompt%%")) { document.location="index.php?Page=Subscribers&Action=Manage&SubAction=Step3&List=%%GLOBAL_list%%" }'>&nbsp;&nbsp;
				<input class="formbutton" type="submit" value="%%LNG_Save%%">
				<br />
				&nbsp;
				<table border="0" cellspacing="0" cellpadding="2" width="100%" class="panel">
					<tr>
						<td colspan="2" class="heading2">
							&nbsp;&nbsp;%%LNG_EditSubscriberDetails%%
						</td>
					</tr>
					<tr>
						<td width="200" class="FieldLabel">
							%%TPL_Required%%
							%%LNG_Email%%:&nbsp;
						</td>
						<td>
							<input type="text" name="emailaddress" value="%%GLOBAL_emailaddress%%" class="field250">
						</td>
					</tr>
					<tr>
						<td width="200" class="FieldLabel">
							%%TPL_Required%%
							%%LNG_Format%%:&nbsp;
						</td>
						<td>
							<select name="format" class="field250">
								%%GLOBAL_FormatList%%
							</select>
						</td>
					</tr>
					<tr>
						<td width="200" class="FieldLabel">
							%%TPL_Required%%
							%%LNG_ConfirmedStatus%%:&nbsp;
						</td>
						<td>
							<select name="confirmed" class="field250">
								%%GLOBAL_ConfirmedList%%
							</select>
						</td>
					</tr>
					%%GLOBAL_CustomFieldInfo%%
					<tr>
						<td>
							&nbsp;
						</td>
						<td>
							<input type="hidden" name="subscriberid" value="%%GLOBAL_subscriberid%%">
							<input class="formbutton" type="button" value="%%LNG_Cancel%%" onClick='if(confirm("%%LNG_Subscribers_Edit_CancelPrompt%%")) { document.location="index.php?Page=Subscribers&Action=Manage&SubAction=Step3&List=%%GLOBAL_list%%" }'>&nbsp;&nbsp;
							<input class="formbutton" type="submit" value="%%LNG_Save%%">
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</form>
