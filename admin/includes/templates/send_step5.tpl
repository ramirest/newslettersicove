<table cellspacing="0" cellpadding="3" width="95%" align="center">
	<tr>
		<td class="heading1">
			%%LNG_Send_Step5%%
		</td>
	</tr>
	<tr>
		<td class="body">
			%%GLOBAL_Send_NumberAlreadySent%%<br/>
			%%GLOBAL_Send_NumberLeft%%<br/><br/>
			%%GLOBAL_SendTimeSoFar%%<br/>
			%%GLOBAL_SendTimeLeft%%
		</td>
	</tr>
	<tr>
		<td class="body">
			<a href="void(0)" onclick="javascript: PauseSending();">%%LNG_PauseSending%%</a>
		</td>
	</tr>
</table>
<script language="javascript">
	function PauseSending() {
		window.opener.document.location = 'index.php?Page=Send&Action=PauseSend&Job=%%GLOBAL_JobID%%';
		window.opener.focus();
		window.close();
	}
</script>

<script language="javascript">
	setTimeout('window.location="index.php?Page=Send&Action=Send&Job=%%GLOBAL_JobID%%&Started=1"', 1);
</script>
