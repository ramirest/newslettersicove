<script>
	screenWidth = screen.availWidth / 2 - 200;
	screenHeight = screen.availHeight / 2 - 100;
</script>
		<table cellspacing="0" cellpadding="3" width="95%" align="center">
		<tr>
			<td class="heading1">
				%%LNG_Subscribers_Import_Step4%%
			</td>
		</tr>
		<tr>
			<td class="body">
				%%LNG_Subscribers_Import_Step4_Intro%%
			</td>
		</tr>
		<tr>
			<td><br>
				<input type="button" value="%%LNG_ImportStart%%" onclick="startImport(); return false;" class="field">
			</td>
		</tr>
	</table>

<script>

function startImport() {
	var importWindow = window.open('index.php?Page=Subscribers&Action=Import&SubAction=Import', 'ss_import', 'left=' + screenWidth + ',top=' + screenHeight + ',width=400,height=280');

	if (!importWindow) {
		alert("%%LNG_UnableToOpenPopupWindow%%");
	}

	return false;
}

</script>