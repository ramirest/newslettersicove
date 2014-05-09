<form method="post" action="index.php?Page=Subscribers&Action=Edit&SubAction=Save&List=%%GLOBAL_list%%" onsubmit="return CheckForm();">
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
				<input class="formbutton" type="button" value="%%LNG_Cancel%%" onClick='if(confirm("%%LNG_Subscribers_Edit_CancelPrompt%%")) { document.location="index.php?Page=Subscribers&Action=Manage&SubAction=Step3" }'>
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
							</select>&nbsp;%%LNG_HLP_Format%%
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
							</select>&nbsp;%%LNG_HLP_ConfirmedStatus%%
						</td>
					</tr>
					<tr>
						<td width="200" class="FieldLabel">
							%%TPL_Required%%
							%%LNG_SubscriberStatus%%:&nbsp;
						</td>
						<td>
							<select name="status" class="field250">
								%%GLOBAL_StatusList%%
							</select>&nbsp;%%LNG_HLP_SubscriberStatus%%
						</td>
					</tr>
					<tr>
						<td width="200" class="FieldLabel">
							%%TPL_Not_Required%%
							%%LNG_SubscribeRequestDate%%:&nbsp;
						</td>
						<td>
							%%GLOBAL_requestdate%%&nbsp;%%LNG_HLP_SubscribeRequestDate%%
						</td>
					</tr>
					<tr>
						<td width="200" class="FieldLabel">
							%%TPL_Not_Required%%
							%%LNG_SubscribeRequestIP%%:&nbsp;
						</td>
						<td>
							%%GLOBAL_requestip%%&nbsp;%%LNG_HLP_SubscribeRequestIP%%
						</td>
					</tr>
					<tr>
						<td width="200" class="FieldLabel">
							%%TPL_Not_Required%%
							%%LNG_SubscribeConfirmDate%%:&nbsp;
						</td>
						<td>
							%%GLOBAL_confirmdate%%&nbsp;%%LNG_HLP_SubscribeConfirmDate%%
						</td>
					</tr>
					<tr>
						<td width="200" class="FieldLabel">
							%%TPL_Not_Required%%
							%%LNG_SubscribeConfirmIP%%:&nbsp;
						</td>
						<td>
							%%GLOBAL_confirmip%%&nbsp;%%LNG_HLP_SubscribeConfirmIP%%
						</td>
					</tr>
					<tr style='display: %%GLOBAL_ShowUnsubscribeInfo%%'>
						<td width="200" class="FieldLabel">
							%%TPL_Not_Required%%
							%%LNG_UnsubscribeTime%%:&nbsp;
						</td>
						<td>
							%%GLOBAL_unsubscribetime%%&nbsp;%%LNG_HLP_UnsubscribeTime%%
						</td>
					</tr>
					<tr style='display: %%GLOBAL_ShowUnsubscribeInfo%%'>
						<td width="200" class="FieldLabel">
							%%TPL_Not_Required%%
							%%LNG_UnsubscribeIP%%:&nbsp;
						</td>
						<td>
							%%GLOBAL_unsubscribeip%%&nbsp;%%LNG_HLP_UnsubscribeIP%%
						</td>
					</tr>
					%%GLOBAL_CustomFieldInfo%%
					<tr>
						<td>
							&nbsp;
						</td>
						<td>
							<input type="hidden" name="subscriberid" value="%%GLOBAL_subscriberid%%">
							<input class="formbutton" type="button" value="%%LNG_Cancel%%" onClick='if(confirm("%%LNG_Subscribers_Edit_CancelPrompt%%")) { document.location="index.php?Page=Subscribers&Action=Manage&SubAction=Step3" }'>
							<input class="formbutton" type="submit" value="%%LNG_Save%%">
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</form>
<script language="javascript">
	function CheckForm() {
		var f = document.forms[0];
		if (f.emailaddress.value == "") {
			alert("%%LNG_Subscribers_EnterEmailAddress%%");
			f.emailaddress.focus();
			return false;
		}
		%%GLOBAL_ExtraJavascript%%
		return true;
	}
</script>
