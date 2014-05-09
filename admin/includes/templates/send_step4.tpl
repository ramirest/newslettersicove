<table cellspacing="0" cellpadding="3" width="95%" align="center">
	<tr>
		<td class="heading1">
			%%LNG_Send_Step4%%
		</td>
	</tr>
	<tr>
		<td class="body">
			%%GLOBAL_SentToTestListWarning%%
			%%LNG_Send_Step4_Intro%%
			<ul style="margin-bottom:0px">
				<li>%%GLOBAL_Send_NewsletterName%%</li>
				<li>%%GLOBAL_Send_SubscriberList%%</li>
				<li>%%GLOBAL_Send_TotalRecipients%%</li>
			</ul>
		</td>
	</tr>
	<tr>
		<td class="body">
			<input type="button" value="%%LNG_StartSending%%" class="smallbutton" onclick="javascript: PopupWindow();">
		</td>
	</tr>
</table>
<script>
	function PopupWindow() {
		var top = screen.height / 2 - (170);
		var left = screen.width / 2 - (140);

		window.open("index.php?Page=Send&Action=Send&Job=%%GLOBAL_JobID%%","sendWin","left=" + left + ",top="+top+",toolbar=false,status=no,directories=false,menubar=false,scrollbars=false,resizable=false,copyhistory=false,width=360,height=200");
	}
</script>
