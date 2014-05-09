<div id="div4" style="display:none">
	<div class="body">
		<br>%%GLOBAL_DisplayBouncesIntro%%
		<br>
		<br>
		%%GLOBAL_Calendar%%
		<br>
		<select name="choosebouncetype" id="choosebt">
			%%GLOBAL_StatsBounceList%%
		</select>
		<input type="button" value="%%LNG_Go%%" class="body" onclick="ChangeBounceType();">
		<br>
		%%GLOBAL_Message%%
	</div>
</div>

<script>
	function ChangeBounceType() {
		cbouncetype = document.getElementById('choosebt');
		selected = cbouncetype.selectedIndex;
		bouncetype = cbouncetype.options[selected].value;
		window.document.location = 'index.php?Page=Stats&Action=%%GLOBAL_BounceAction%%&SubAction=ViewSummary&id=%%GLOBAL_StatID%%&tab=4&bouncetype=' + bouncetype;
	}
</script>
