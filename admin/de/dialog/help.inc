<script language=JavaScript>
d = document;

function getParameter(par){
	var str = window.location.href;
	idx=str.indexOf(par+"=",1);
	if (idx>=0){
		idx2=str.indexOf("&",idx+1);
		if((idx>=0)&&(idx2==-1))idx2=str.length;
		return str.substring(idx+par.length+1,idx2);
	} else {
		return null;
	}
}

window.onload = function(){
	var id = getParameter("sectionid");
	if (id)doNav(id,false);
	this.focus();
}	

function getE(id){
	return d.getElementById(id)
}

</SCRIPT>

<HTML>
<TITLE>[sTxtHelpTitle]: </title>
<script tyle="text/javascript">
document.write('<link rel="stylesheet" href="'+window.opener.activeEditor.skinPath+window.opener.main_css_file+'" type="text/css">');
</script>

<body class="de_helpWindow" bgcolor="#FFFFFF" link="#0000CC" vlink="#0000CC" text="#000000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<div class="de_dialogWindow">
<img id="back" src="skins/default/back.gif" width=20 height=20 class="de_popupitem_off" onMouseover="this.className='de_popupitem_on'" onMouseout="this.className='de_popupitem_off'" onClick="goBack();">
<img id="forward" src="skins/default/forward.gif" width=20 height=20 class="de_popupitem_off" onMouseover="this.className='de_popupitem_on'" onMouseout="this.className='de_popupitem_off'" onClick="goForward();">
<img src="skins/default/print.gif" width=20 height=20 class="de_popupitem_off" onMouseover="this.className='de_popupitem_on'" onMouseout="this.className='de_popupitem_off'" onClick="window.print();">
</div>

<script>
	var pos = 0;
	if (document.all)
		document.write("<style>div.scroll { overflow: auto;	height: 94%; width: 100%; padding-left: 10px; } </style>")
	else
		document.write("<style>div.scroll { overflow: auto;	height: 93%; width: 98%; padding-left: 10px; } </style>")

	var History = new Array();
	History[pos]='contents';

	function goBack() {
		if (pos > 0){
			pos -= 1;
			option = History[pos];
			doNav(option, false);
		}
	}

	function goForward() {
		if (pos < History.length-1){
			pos += 1;
			option = History[pos];
			doNav(option, false);
		}
	}

	function doNav(show, addToHistory) {
		// Hide all
		document.getElementById('contents').style.display = 'none';
		document.getElementById('commands').style.display = 'none';
		document.getElementById('htmlbar').style.display = 'none';
		document.getElementById('features').style.display = 'none';
		document.getElementById('firefox').style.display = 'none';
		document.getElementById('shortcuts').style.display = 'none';

		document.getElementById(show).style.display = '';

		if (addToHistory) {
			pos=History.length;
			History[pos] = show;
		}
	}

</script>



<div class=scroll id=contents>

<br>
<p><b>Help</b></p>
<ul class="de_helpList">
<li><a href="#" onClick="doNav('commands',true)">The WYSIWYG Editor Commands</a><br><br></li>
<li><a href="#" onClick="doNav('htmlbar',true)">The HTML Tag Bar</a><br><br></li>
<li><a href="#" onClick="doNav('features',true)">Additional Features and Options</a><br><br></li>
<li><a href="#" onClick="doNav('firefox',true)">Enabling Restricted Features in FireFox</a><br><br></li>
<li><a href="#" onClick="doNav('ieclipboard',true)">Disable Allow This Webpage to Access Your Clipboard Pop-Up Warning Message in IE7</a><br><br></li>
<li><a href="#" onClick="doNav('shortcuts',true)">Shortcut Keys</a></li>
</ul>

<br><br>

</div>


<div class=scroll id=commands style="display:none">

<br>

<p><b>The WYSIWYG Editor Commands</b><br>
<a href="#" onClick="doNav('contents')">Help Home</a></p>
[sTxtHelpNote]<br><br>

<div style="clear: both">&nbsp</div>

<img src="skins/default/save.gif" class="de_helpIcon">
<div class=de_helpCol>[sTxtHelpSaveTitle]: [sTxtHelpSave]</div>

<div style="clear: both">&nbsp</div>

<img src="skins/default/fullscreen.gif" class="de_helpIcon">
<div class=de_helpCol>[sTxtHelpFullscreenTitle]: </b> [sTxtHelpFullscreen]</div>

<div style="clear: both">&nbsp</div>

<img src="skins/default/cut.gif" class="de_helpIcon">
<div class=de_helpCol>[sTxtHelpCutTitle]: </b> [sTxtHelpCut]</div>

<div style="clear: both">&nbsp</div>

<img src="skins/default/copy.gif" class="de_helpIcon">
<div class=de_helpCol>[sTxtHelpCopyTitle]: </b> [sTxtHelpCopy]</div>

<div style="clear: both">&nbsp</div>

<img src="skins/default/paste.gif" class="de_helpIcon">
<div class=de_helpCol>[sTxtHelpPasteTitle]: </b> [sTxtHelpPaste]</div>

<div style="clear: both">&nbsp</div>

<img src="skins/default/pastetext.gif" class="de_helpIcon" style="float: left">
<div class="de_helpCol">[sTxtHelpPasteTextTitle]: </b> [sTxtHelpPasteText]</div>

<div style="clear: both">&nbsp</div>

<img src="skins/default/pasteword.gif" class="de_helpIcon" style="float: left">
<div class="de_helpCol">[sTxtHelpPasteWordTitle]:  [sTxtHelpPasteWord]</div>

<div style="clear: both">&nbsp</div>

<img src="skins/default/find.gif" class="de_helpIcon">
<div class=de_helpCol>[sTxtHelpFindReplaceTitle]:  [sTxtHelpFindReplace]</div>

<div style="clear: both">&nbsp</div>

<img src="skins/default/undo.gif" class="de_helpIcon">
<div class=de_helpCol>[sTxtHelpUndoTitle]: </b> [sTxtHelpUndo]</div>

<div style="clear: both">&nbsp</div>

<img src="skins/default/redo.gif" class="de_helpIcon">
<div class=de_helpCol>[sTxtHelpRedoTitle]: </b> [sTxtHelpRedo]</div>

<div style="clear: both">&nbsp</div>

<img src="skins/default/spellcheck.gif" class="de_helpIcon">
<div class=de_helpCol>[sTxtHelpSpellcheckTitle]: </b> [sTxtHelpSpellcheck]</div>

<div style="clear: both">&nbsp</div>

<img src="skins/default/removeformat.gif" class="de_helpIcon">
<div class=de_helpCol>[sTxtHelpRemoveFormattingTitle]: </b> [sTxtHelpRemoveFormatting]</div>

<div style="clear: both">&nbsp</div>

<img src="skins/default/bold.gif" class="de_helpIcon">
<div class=de_helpCol>[sTxtHelpBoldTitle]: </b> [sTxtHelpBold]</div>

<div style="clear: both">&nbsp</div>

<img src="skins/default/underline.gif" class="de_helpIcon">
<div class=de_helpCol>[sTxtHelpUnderlineTitle]: </b> [sTxtHelpUnderline]</div>

<div style="clear: both">&nbsp</div>

<img src="skins/default/italic.gif" class="de_helpIcon">
<div class=de_helpCol>[sTxtHelpItalicTitle]: </b> [sTxtHelpItalic]</div>

<div style="clear: both">&nbsp</div>

<img src="skins/default/strikethrough.gif" class="de_helpIcon">
<div class=de_helpCol>[sTxtHelpStrikethroughTitle]: </b> [sTxtHelpStrikethrough]</div>

<div style="clear: both">&nbsp</div>

<img src="skins/default/insertorderedlist.gif" class="de_helpIcon">
<div class=de_helpCol>[sTxtHelpINListTitle]: </b> [sTxtHelpINList]</div>

<div style="clear: both">&nbsp</div>

<img src="skins/default/insertunorderedlist.gif" class="de_helpIcon">
<div class=de_helpCol>[sTxtHelpIBListTitle]: </b> [sTxtHelpIBList]</div>

<div style="clear: both">&nbsp</div>

<img src="skins/default/outdent.gif" class="de_helpIcon">
<div class=de_helpCol>[sTxtHelpDIndentTitle]: </b> [sTxtHelpDIndent]</div>

<div style="clear: both">&nbsp</div>

<img src="skins/default/indent.gif" class="de_helpIcon">
<div class=de_helpCol>[sTxtHelpIIndentTitle]: </b> [sTxtHelpIIndent]</div>

<div style="clear: both">&nbsp</div>

<img src="skins/default/superscript.gif" class="de_helpIcon">
<div class=de_helpCol>[sTxtHelpSuperscriptTitle]: </b> [sTxtHelpSuperscript]</div>

<div style="clear: both">&nbsp</div>

<img src="skins/default/subscript.gif" class="de_helpIcon">
<div class=de_helpCol>[sTxtHelpSubscriptTitle]: </b> [sTxtHelpSubscript]</div>

<div style="clear: both">&nbsp</div>

<img src="skins/default/justifyleft.gif" class="de_helpIcon">
<div class=de_helpCol>[sTxtHelpALeftTitle]: </b> [sTxtHelpALeft]</div>

<div style="clear: both">&nbsp</div>

<img src="skins/default/justifycenter.gif" class="de_helpIcon">
<div class=de_helpCol>[sTxtHelpACenterTitle]: </b> [sTxtHelpACenter]</div>

<div style="clear: both">&nbsp</div>

<img src="skins/default/justifyright.gif" class="de_helpIcon">
<div class=de_helpCol>[sTxtHelpARightTitle]: </b> [sTxtHelpARight]</div>

<div style="clear: both">&nbsp</div>

<img src="skins/default/justifyfull.gif" class="de_helpIcon">
<div class=de_helpCol>[sTxtHelpJustifyTitle]: </b> [sTxtHelpJustify]</div>

<div style="clear: both">&nbsp</div>

<img src="skins/default/hr.gif" class="de_helpIcon">
<div class=de_helpCol>[sTxtHelpIHLineTitle]: </b> [sTxtHelpIHLine]</div>

<div style="clear: both">&nbsp</div>

<img src="skins/default/createlink.gif" class="de_helpIcon">
<div class=de_helpCol>[sTxtHelpCMHyperLinkTitle]: </b> [sTxtHelpCMHyperLink]</div>

<div style="clear: both">&nbsp</div>

<img src="skins/default/createemaillink.gif" class="de_helpIcon">
<div class=de_helpCol>[sTxtHelpCELinkTitle]: </b> [sTxtHelpCELink]</div>

<div style="clear: both">&nbsp</div>

<img src="skins/default/anchor.gif" class="de_helpIcon">
<div class=de_helpCol>[sTxtHelpIMAnchorTitle]: </b> [sTxtHelpIMAnchor]</div>

<div style="clear: both">&nbsp</div>

<img src="images/help/image_font.gif" class="de_helpIcon">
<div class=de_helpCol style="width: 75%">[sTxtHelpFontTitle]: </b> [sTxtHelpFont]</div>

<div style="clear: both">&nbsp</div>

<img src="images/help/image_size.gif" class="de_helpIcon">
<div class=de_helpCol style="width: 85%">[sTxtHelpFSizeTitle]: </b> [sTxtHelpFSize]</div>

<div style="clear: both">&nbsp</div>

<img src="images/help/image_format.gif" class="de_helpIcon">
<div class=de_helpCol style="width: 75%">[sTxtHelpFormatTitle]: </b> [sTxtHelpFormat]</div>

<div style="clear: both">&nbsp</div>

<img src="images/help/image_style.gif" class="de_helpIcon">
<div class=de_helpCol style="width: 75%">[sTxtHelpStyleTitle]: </b> [sTxtHelpStyle]</div>

<div style="clear: both">&nbsp</div>

<img src="skins/default/fontcolor.gif" class="de_helpIcon">
<div class=de_helpCol>[sTxtHelpFColorTitle]: </b> [sTxtHelpFColor]</div>

<div style="clear: both">&nbsp</div>

<img src="skins/default/highlight.gif" class="de_helpIcon">
<div class=de_helpCol>[sTxtHelpHColorTitle]: </b> [sTxtHelpHColor]</div>

<div style="clear: both">&nbsp</div>

<img src="skins/default/table.gif" class="de_helpIcon">
<div class=de_helpCol>[sTxtHelpTFunctionsTitle]: </b> [sTxtHelpTFunctions]</div>

<div style="clear: both">&nbsp</div>

<img src="skins/default/inserttable.gif" class="de_helpIcon">
<div class=de_helpCol>[sTxtHelpITableTitle]: </b> [sTxtHelpITable]</div>

<div style="clear: both">&nbsp</div>

<img src="skins/default/modifytable.gif" class="de_helpIcon">
<div class=de_helpCol>[sTxtHelpMTPropertiesTitle]: </b> [sTxtHelpMTProperties]</div>

<div style="clear: both">&nbsp</div>

<img src="skins/default/modifycell.gif" class="de_helpIcon">
<div class=de_helpCol>[sTxtHelpMCPropertiesTitle]: </b> [sTxtHelpMCProperties]</div>

<div style="clear: both">&nbsp</div>

<img src="skins/default/insertcolumnright.gif" class="de_helpIcon">
<div class=de_helpCol>[sTxtHelpICttRightTitle]: </b> [sTxtHelpICttRight]</div>

<div style="clear: both">&nbsp</div>

<img src="skins/default/insertcolumnleft.gif" class="de_helpIcon">
<div class=de_helpCol>[sTxtHelpICttLeftTitle]: </b> [sTxtHelpICttLeft]</div>

<div style="clear: both">&nbsp</div>

<img src="skins/default/insertrowabove.gif" class="de_helpIcon">
<div class=de_helpCol>[sTxtHelpIRAboveTitle]: </b> [sTxtHelpIRAbove]</div>

<div style="clear: both">&nbsp</div>

<img src="skins/default/insertrowbelow.gif" class="de_helpIcon">
<div class=de_helpCol>[sTxtHelpIRBelowTitle]: </b> [sTxtHelpIRBelow]</div>

<div style="clear: both">&nbsp</div>

<img src="skins/default/deleterow.gif" class="de_helpIcon">
<div class=de_helpCol>[sTxtHelpDRowTitle]: </b> [sTxtHelpDRow]</div>

<div style="clear: both">&nbsp</div>

<img src="skins/default/deletecolumn.gif" class="de_helpIcon">
<div class=de_helpCol>[sTxtHelpDColumnTitle]: </b> [sTxtHelpDColumn]</div>

<div style="clear: both">&nbsp</div>

<img src="skins/default/increasecolumnspan.gif" class="de_helpIcon">
<div class=de_helpCol>[sTxtHelpICSpanTitle]: </b> [sTxtHelpICSpan]</div>

<div style="clear: both">&nbsp</div>

<img src="skins/default/decreasecolumnspan.gif" class="de_helpIcon">
<div class=de_helpCol>[sTxtHelpDCSpanTitle]: </b> [sTxtHelpDCSpan]</div>

<div style="clear: both">&nbsp</div>

<img src="skins/default/form.gif" class="de_helpIcon">
<div class=de_helpCol>[sTxtHelpFFunctionsTitle]: </b> [sTxtHelpFFunctions]</div>

<div style="clear: both">&nbsp</div>

<img src="skins/default/insertform.gif" class="de_helpIcon">
<div class=de_helpCol>[sTxtHelpIFormTitle]: </b> [sTxtHelpIForm]</div>

<div style="clear: both">&nbsp</div>

<img src="skins/default/modifyform.gif" class="de_helpIcon">
<div class=de_helpCol>[sTxtHelpMFPropertiesTitle]: </b> [sTxtHelpMFProperties]</div>

<div style="clear: both">&nbsp</div>

<img src="skins/default/inserttextfield.gif" class="de_helpIcon">
<div class=de_helpCol>[sTxtHelpIMTFieldTitle]: </b> [sTxtHelpIMTField]</div>

<div style="clear: both">&nbsp</div>

<img src="skins/default/inserttextarea.gif" class="de_helpIcon">
<div class=de_helpCol>[sTxtHelpIMTAreaTitle]: </b> [sTxtHelpIMTArea]</div>

<div style="clear: both">&nbsp</div>

<img src="skins/default/inserthidden.gif" class="de_helpIcon">
<div class=de_helpCol>[sTxtHelpIMHAreaTitle]: </b> [sTxtHelpIMHArea]</div>

<div style="clear: both">&nbsp</div>

<img src="skins/default/insertbutton.gif" class="de_helpIcon">
<div class=de_helpCol>[sTxtHelpIMButtonTitle]: </b> [sTxtHelpIMButton]</div>

<div style="clear: both">&nbsp</div>

<img src="skins/default/insertcheckbox.gif" class="de_helpIcon">
<div class=de_helpCol>[sTxtHelpIMCheckboxTitle]: </b> [sTxtHelpIMCheckbox]</div>

<div style="clear: both">&nbsp</div>

<img src="skins/default/insertradio.gif" class="de_helpIcon">
<div class=de_helpCol>[sTxtHelpIMRButtonTitle]: </b> [sTxtHelpIMRButton]</div>

<div style="clear: both">&nbsp</div>

<img src="skins/default/insertselect.gif" class="de_helpIcon">
<div class=de_helpCol>[sTxtHelpIMSListTitle]: </b> [sTxtHelpIMSList]</div>

<div style="clear: both">&nbsp</div>

<img src="skins/default/flash.gif" class="de_helpIcon">
<div class=de_helpCol>[sTxtHelpIMFlashTitle]: </b> [sTxtHelpIMFlash]</div>

<div style="clear: both">&nbsp</div>

<img src="skins/default/image.gif" class="de_helpIcon">
<div class=de_helpCol>[sTxtHelpIMImageTitle]: </b> [sTxtHelpIMImage]</div>

<div style="clear: both">&nbsp</div>

<img src="skins/default/media.gif" class="de_helpIcon">
<div class=de_helpCol>[sTxtHelpIMMediaTitle]: </b> [sTxtHelpIMMedia]</div>

<div style="clear: both">&nbsp</div>

<img src="skins/default/inserttextbox.gif" class="de_helpIcon">
<div class=de_helpCol>[sTxtHelpTextboxTitle]: </b> [sTxtHelpTextbox]</div>

<div style="clear: both">&nbsp</div>

<img src="skins/default/insertchars.gif" class="de_helpIcon">
<div class=de_helpCol>[sTxtHelpISCharactersTitle]: </b> [sTxtHelpISCharacters]</div>

<div style="clear: both">&nbsp</div>

<img src="skins/default/pageproperties.gif" class="de_helpIcon">
<div class=de_helpCol>[sTxtHelpMPPropertiesTitle]: </b> [sTxtHelpMPProperties]</div>

<div style="clear: both">&nbsp</div>

<img src="skins/default/clearcode.gif" class="de_helpIcon">
<div class=de_helpCol>[sTxtHelpCUHTMLCodeTitle]: </b> [sTxtHelpCUHTMLCode]</div>

<div style="clear: both">&nbsp</div>

<img src="skins/default/custominsert.gif" class="de_helpIcon">
<div class=de_helpCol>[sTxtHelpCustomHTMLTitle]: </b> [sTxtHelpCustomHTML]</div>

<div style="clear: both">&nbsp</div>

<img src="skins/default/toggleposition.gif" class="de_helpIcon">
<div class=de_helpCol>[sTxtHelpAbsoluteTitle]: </b> [sTxtHelpAbsolute]</div>

<div style="clear: both">&nbsp</div>

<img src="skins/default/showborders.gif" class="de_helpIcon">
<div class=de_helpCol>[sTxtHelpSHGuidelinesTitle]: </b> [sTxtHelpSHGuidelines]</div>

<div style="clear: both">&nbsp</div>

<img src="images/help/status_source.gif" class="de_helpIcon">
<div class=de_helpCol style="width: 75%">[sTxtHelpSModeTitle]: </b> [sTxtHelpSMode]</div>

<div style="clear: both">&nbsp</div>

<img src="images/help/status_preview.gif" class="de_helpIcon">
<div class=de_helpCol style="width: 75%">[sTxtHelpPModeTitle]: </b> [sTxtHelpPMode]</div>

<div style="clear: both">&nbsp</div>

</div>

<div class=scroll id=htmlbar style="display: none">
<br><p><b>The HTML Tag bar</b><br>
<a href="#" onClick="doNav('contents')">Help Home</a></p>
<p><b>What is the HTML tag bar?</b></p>
The HTML tag bar is located at the bottom of the editor, just above the "Edit, Source and Preview" tabs. It's purpose is to show you the HTML tag that is currently being edited, and allow you to select specific tags easily and remove or modify them. Because HTML allows you to have multiple tags overlapping, it becomes increasingly difficult to select specific tags using only the editor and your cursor. The HTML tag bar is extremely useful in these scenarios where there are overlapping HTML tags and allows you to select the specific HTML tag you would like to modify or remove.

<p><img src="skins/default/help_pathbar.gif" width="456" height="68"></p>

<p><b>How do I use HTML tag bar?</b></p>
Using the HTML tag bar is simple. When your cursor is placed in the editor inside a specific HTML tag, you will see that tag appear in the HTML tag bar. By clicking on this HTML tag in the HTML tag bar, the editor will automatically select that entire HTML tag. You can then modify that tag for example by selecting a new Style to be applied. Alternatively, you can remove the tag by clicking on the "Remove tag" option on the right side of that bar, or edit the HTML code of that tag only, by selecting the "Edit tag" option.<br><br>
Sometimes you will notice that a tag will also be followed by the name of a Class found in the stylesheet. (eg. &laquo;span.myClass>) This means that current HTML tag being selected has a style applied to it. This makes it easier for you to see what Style has been applied and to modify it in seconds.


<br><br>

</div>

<div class=scroll id=features style="display: none">
<br><p><b>Additional Features and Options</b><br>
<a href="#" onClick="doNav('contents')">Help Home</a></p>
The HTML editor contains additional features and options that are not contained on the toolbar.
<br><br>
<b>Switching Toolbar Modes</b>
<br><br>
At the bottom right hand corner of the HTML editor, you will see an option to switch between multiple modes. These may vary depending on your administrators application settings, but by default they are "Simple" and "Complete" mode.<br><br>
<img src="skins/default/help_modes.gif" width="456" height="63"><br><br>
Clicking on this option will switch from one mode to the other. These modes indicate the number of toolbar icons that are displayed in the editor. In "Simple" mode, only the most frequently used icons will be displayed, minimising the amount of clutter in the toolbar and increasing your workspace. Switching to "Complete" mode will turn on all available options, giving you greater features to work with.<br><br>
Once you have switched to a different mode, this setting is stored onto your computer. The next time the HTML editor is loaded, you will be automatically switched to the last used mode.
<br><br>

<b>Using the Right Click Context Menu</b><br><br>
Similar to other desktop based applications, the HTML editor features a right click "Context" menu.<br><br>
<img src="skins/default/help_context.gif" width="456" height="97"><br><br>
This menu enables quick and easy access to commonly used functions. Other options also appear depending on your current editing situation. If you select an Image and right click on that Image, you will be able to select the "Modify Image" option. If you select or click inside a Table, the various Table options will appear in the "Context" menu. Finally, right clicking inside a Form will present the "Modify Form" option.


<br><br>
<b>Using the Source and Preview Tabs</b><br><br>
Selecting the "Source" or "Preview" tab at the bottom of the editor will switch you to editing the HTML source code or to preview what the final content will look like in your web browser.<br><br>
<img src="skins/default/help_source.gif" width="456" height="65"><br>

<br><br>

</div>


<div class=scroll id=firefox style="display: none">
<br><p><b>Enabling Restricted Features in FireFox</b><br>
<a href="#" onClick="doNav('contents')">Help Home</a></p>
If you're using a Gecko based web browser, such as FireFox or Mozilla, you will need to enable a security option in order to use some functions inside the HTML editing component. These include Cut, Copy and Paste using the toolbar icons. To do this, follow the instructions here:
<br><br>
<a href="http://www.mozilla.org/editor/midasdemo/securityprefs.html" target="_new">http://www.mozilla.org/editor/midasdemo/securityprefs.html</a>

</div>


<div class=scroll id=ieclipboard style="display: none">
<br><p><b>Disable Allow This Webpage to Access Your Clipboard Pop-Up Warning Message in IE7</b><br>
<a href="#" onClick="doNav('contents')">Help Home</a></p>
Internet Explorer 7 alerts you with a confirmation "Allow this webpage to access your clipboard" everytime you try and use the HTML editor to copy, cut or paste. To disable this prompt, follow these instructions:

<br><br>
<ol>
<li>Go to Tools -> Internet Options.</li>
<li>Go to the Security tab.</li>
<li>Select "Internet" zone, then click on the "Custom level…" button.</li>
<li>Scroll down to "Scripting" section.</li>
<li>Under "Allow Programmatic clipboard access", select the "Enable" checkbox option.</li>
<li>Click Ok twice to exit from Internet Options.</li>
</ol>
</div>




<div class=scroll id=shortcuts style="display: none">
<br><p><b>ShortCut Keys</b><br>
<a href="#" onClick="doNav('contents')">Help Home</a></p>
The following table outlines the shortcut keys available inside the HTML editing component.<br><br>
<table class="de_helpTable">
	<tr>
		<td>Command</td><td>ShortCut Key</td>
	</tr>
	<tr>
		<td>Cut</td><td>Ctrl + X</td>
	</tr>
	<tr>
		<td>Copy</td><td>Ctrl + C</td>
	</tr>
	<tr>
		<td>Paste</td><td>Ctrl + V</td>
	</tr>
	<tr>
		<td>Paste from Word</td><td>Ctrl + D</td>
	</tr>
	<tr>
		<td>Insert or Modify Image</td><td>Ctrl + M</td>
	</tr>
	<tr>
		<td>Create or modify Link</td><td>Ctrl + K (I.E Only)</td>
	</tr>
</table>
</div>

</body>
</html>


