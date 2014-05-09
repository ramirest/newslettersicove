<table cellspacing="0" cellpadding="3" width="95%" align="center">
	<tr>
		<td class="heading1">
			%%LNG_Bounce_Step3%%
		</td>
	</tr>
	<tr>
		<td class="body">
			%%LNG_Bounce_Step3_Intro%%
		</td>
	</tr>
	<tr>
		<td>
			%%GLOBAL_Message%%
		</td>
	</tr>
	<tr>
		<td class="body"><br>
			<input type="button" value="%%LNG_StartProcessing%%" class="smallbutton" onclick="javascript: PopupWindow();">
		</td>
	</tr>
</table>
<script>
	function PopupWindow() {
		var top = screen.height / 2 - (170);
		var left = screen.width / 2 - (140);

		window.open("index.php?Page=Bounce&Action=Bounce","sendWin","left=" + left + ",top="+top+",toolbar=false,status=no,directories=false,menubar=false,scrollbars=false,resizable=false,copyhistory=false,width=360,height=200");
	}
</script>
