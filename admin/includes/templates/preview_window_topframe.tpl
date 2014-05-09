<link rel="stylesheet" href="includes/styles/stylesheet.css" type="text/css">
<link rel="stylesheet" href="includes/styles/tabmenu.css" type="text/css">

<!--[if IE]>
<style type="text/css">
	@import url("includes/styles/ie.css");
</style>
<![endif]-->

<script>
	function switchView(i)
	{
		top.frames[1].document.location.href = '%%GLOBAL_APPLICATION_URL%%/admin/index.php?Page=Preview&Action=Display&Type=' + i;
	}
</script>

<body style="background-color: #F3F2E9">

<div align="right" style="padding-top: 8px">
	<select id="f" onChange="switchView(this.options[this.selectedIndex].value)">
		%%GLOBAL_SwitchOptions%%
	</select>
	&nbsp;
</div>