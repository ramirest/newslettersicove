<script>
	function ChangeLink() {
		chooselink_id = document.getElementById('chooselink');
		selected = chooselink_id.selectedIndex;
		linkid = chooselink_id[selected].value;
		window.document.location = 'index.php?Page=Stats&Action=%%GLOBAL_LinkAction%%&SubAction=ViewSummary&id=%%GLOBAL_StatID%%&%%GLOBAL_LinkType%%&tab=3&link=' + linkid;
	}
</script>

<select name="chooselink" id="chooselink" style="display:%%GLOBAL_DisplayStatsLinkList%%">
	<option value="a">%%LNG_AnyLink%%</option>
	%%GLOBAL_StatsLinkList%%
</select>
<input type="button" value="%%LNG_Go%%" class="body" onclick="ChangeLink();" style="display:%%GLOBAL_DisplayStatsLinkList%%">
