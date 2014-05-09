<script>

	function l(Img)
	{
		if(navigator.userAgent.indexOf("MSIE") > -1) {
			xPos = eval(Img).offsetLeft;
			tempEl = eval(Img).offsetParent;

			while(tempEl != null)
			{
				xPos += tempEl.offsetLeft;
				tempEl = tempEl.offsetParent;
			}
		}
		else {
			xPos = Img.x;
		}

		return xPos;
	}

	function t(Img)
	{
		if(navigator.userAgent.indexOf("MSIE") > -1) {
			yPos = eval(Img).offsetTop;
			tempEl = eval(Img).offsetParent;

			while(tempEl != null)
			{
				yPos += tempEl.offsetTop;
				tempEl = tempEl.offsetParent;
			}
		}
		else {
			yPos = Img.y;
		}

		return yPos;
	}


</script>

	<table cellspacing="0" cellpadding="3" width="95%" align="center">
	<tr>
		<td class="heading1">%%LNG_TemplatesManageBuiltIn%%</td>
	</tr>
	<tr>
		<td class="body">%%LNG_Help_TemplatesManageBuiltIn%%</td>
	</tr>
	<tr>
		<td class="body">
			<table border=0 cellspacing="1" cellpadding="2" width=100% class=text>
			%%TPL_Templates_BuiltIn_Manage_Row%%
			</table>
		</td>
	</tr>
</table>
