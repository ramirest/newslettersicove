<script>
	screenWidth = screen.availWidth / 2 - (200);
	screenHeight = screen.availHeight / 2 - (100);
</script>
	<table cellspacing="0" cellpadding="3" width="95%" align="center">
		<tr>
			<td class="heading1">
				%%LNG_Subscribers_Export_Step4%%
			</td>
		</tr>
		<tr>
			<td class="body">
				%%GLOBAL_SubscribersReport%%
			</td>
		</tr>
		<tr>
			<td><br>
				<input type="button" value="%%LNG_ExportStart%%" onclick="javascript: window.open('index.php?Page=Subscribers&Action=Export&SubAction=Export', 'ss_export', 'left=' + screenWidth + ',top=' + screenHeight + ',width=400,height=200'); return false;" class="formbutton_wide">
			</td>
		</tr>
	</table>

