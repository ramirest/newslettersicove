<tr>
	<td colspan="2" class="heading2">
		&nbsp;&nbsp;%%LNG_CronSendOptions%%
	</td>
</tr>
<tr>
	<td width="200" class="FieldLabel">
		%%TPL_Not_Required%%
		%%LNG_SendImmediately%%:&nbsp;
	</td>
	<td>
		<label for="sendimmediately"><input type="checkbox" name="sendimmediately" id="sendimmediately" value="1" CHECKED onClick="ShowSendTime(this)">&nbsp;%%LNG_SendImmediatelyExplain%%</label>&nbsp;%%LNG_HLP_SendImmediately%%
	</td>
</tr>
<tr id="show_sendtime" style="display:none;" width="200" class="FieldLabel">
	<td width="200" class="FieldLabel">
		%%TPL_Required%%
		%%LNG_SendTime%%:&nbsp;
	</td>
	<td>
		<script language="JavaScript" src="includes/templates/timepicker_lib.js" type="text/javascript"></script>
		%%GLOBAL_SendTimeBox%%&nbsp;%%LNG_HLP_SendTime%%
	</td>
</tr>
<tr>
	<td width="200" class="FieldLabel">
		%%TPL_Not_Required%%
		%%LNG_NotifyOwner%%:&nbsp;
	</td>
	<td>
		<label for="notifyowner"><input type="checkbox" name="notifyowner" id="notifyowner" value="1" CHECKED>&nbsp;%%LNG_NotifyOwnerExplain%%</label>&nbsp;%%LNG_HLP_NotifyOwner%%
	</td>
</tr>
<tr>
	<td colspan="2" class="EmptyRow">
		&nbsp;
	</td>
</tr>

