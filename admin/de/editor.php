<?php
	// Include the initialise script to setup session etc.
	require_once(dirname(__FILE__).'/init.php');

	$_s =$_GET["name"];
	$ie=(is_numeric(strpos($_SERVER["HTTP_USER_AGENT"],"MSIE")))?true:false;
	$safari=(is_numeric(strpos($_SERVER["HTTP_USER_AGENT"],"Safari")))?true:false;
	$windows=(is_numeric(strpos($_SERVER["HTTP_USER_AGENT"],"Windows")))?true:false;
	$gecko=(is_numeric(strpos($_SERVER["HTTP_USER_AGENT"],"Gecko")))?true:false;

	if ($ie){
		// Only output the doctype if it has a < in it to avoid situations where
		// the doctype was somehow being set to 1 which was being output
		if (isset($_SESSION["dt".$_s]) && strpos($_SESSION['dt'.$_s], '<') !== false) {
			echo $_SESSION["dt".$_s]."\n";
		}
	}
?>
<html>
<head>
<title>Editor</title>
<script type="text/javascript" language="javascript" src="config.js"></script>
<?php
	if($_SESSION["useXHTML".$_s]=="1"){
		if ($ie){
			echo '<script type="text/javascript" language="javascript" src="xhtml.js"></script>';
		} else {
			echo '<script type="text/javascript" language="javascript" src="xhtml_gecko.js"></script>';
		}
	}

	if(isset($_SESSION["userdocroot"])){
		$_SERVER['DOCUMENT_ROOT'] = $_SESSION["userdocroot"];
	}

?>
<script type="text/javascript" language="javascript" src="common.js"></script>
<script type="text/javascript" language="javascript" src="advanced.js"></script>
<?php
	if ($safari) {
		echo '<script type="text/javascript" language="javascript" src="editor_simple.js"></script>';
	} else if ($gecko) {
		echo '<script type="text/javascript" language="javascript" src="editor_gecko.js"></script>';
	} else if ($ie && $windows) {
		echo '<script type="text/javascript" language="javascript" src="editor_ie.js"></script>';
	} else {
		echo '<script type="text/javascript" language="javascript" src="editor_simple.js"></script>';
	}

	$sPath = $_SESSION["sPath"];

	echo '<link href="'.$sPath.'styles.css" type="text/css" rel="stylesheet">';
	if ($ie) {
		echo '<link href="'.$sPath.'ie.css" type="text/css" rel="stylesheet">';
	} else {
		echo '<link href="'.$sPath.'moz.css" type="text/css" rel="stylesheet">';
	}
?>

<script type="text/javascript" language="javascript">
<?php
	if ($ie){
		if (isset($_SESSION["dt".$_s])){
			echo "new_layout=true;\n";
		} else {
			echo "new_layout=false;\n";
		}
	}
?>
	var s="";
	s=document.location.search;
	function getParameter(str,par){
		idx=str.indexOf("?"+par+"=",0);
		if(idx==-1)idx=str.indexOf("&"+par+"=",1);
		if (idx==-1) return 0;
		idx2=str.indexOf("&",idx+1);
		if((idx>=0)&&(idx2==-1))idx2=str.length;
		return str.substring(idx+par.length+2,idx2)
	}
	is_forcePasteWord=<?php echo $_SESSION["forcePasteWord".$_s]; ?>;
	is_forcePasteAsText=<?php echo $_SESSION["forcePasteAsText".$_s]; ?>;
	name="<?php echo $_s; ?>";
</script>

<script type="text/javascript" language="javascript">
	var editable_area_selected=false;
	var imageLoader = new ImageLoader();

	if (document.all){
		var skinPath = "<?php echo $_SESSION["skinPath"]; ?>";
		var skinPath2 = "<?php echo $_SESSION["skinPath2"]; ?>";
	}else{
		var skinPath = "<?php echo $_SESSION["skinPath2"]; ?>";
		var skinPath2 = "<?php echo $_SESSION["skinPath2"]; ?>";
	}
	var border_css_file = "borders.css";
	width="<?php echo $_SESSION["width".$_s]; ?>";
	height="<?php echo $_SESSION["height".$_s]; ?>";
	useXHTML=<?php echo $_SESSION["useXHTML".$_s]; ?>;
	docType=<?php echo $_SESSION["docType".$_s]; ?>;
	combostyle_flag = <?php echo $_SESSION["ComboStyles".$_s]; ?>;
	var useBR=<?php echo $_SESSION["SingleLineReturn".$_s]; ?>;
	spellLang="<?php echo $_SESSION["spellLang".$_s]; ?>";
	var customInserts=parent.customInserts[name];
	var CustomLink=parent.customLinks[name];
	var URL=parent.URL[name];
	var imageDir=parent.imageDir[name];
	var flashDir=parent.flashDir[name];
	var mediaDir=parent.mediaDir[name];
	var popup_color_src = "<?php echo $_SESSION["popup_color_src".$_s]; ?>";
	var linkPath=parent.linkDir[name];
	var showThumbnails=parent.showThumbnails[name];
	var showFlashThumbnails=parent.showFlashThumbnails[name];
	var disableImageUploading=parent.disableImageUploading[name];
	var loadedFile = "<?php echo $_SESSION["loadedFile"]; ?>";
	var oldbasehref = "<?php echo $_SESSION["doc_root"]; ?>";
	var doc_root = "<?php echo $_SESSION["doc_root"]; ?>";
	var serverurl = "<?php echo $_SESSION["serverurl"]; ?>";
	var doc_root2 = doc_root;
	doc_root = doc_root.substring(0,doc_root.substring(0,doc_root.length-2).lastIndexOf("/")+1);
	var deveditPath="<?php echo $_SESSION["deveditPath"]; ?>";
	var deveditPath1="<?php echo $_SESSION["deveditPath1"]; ?>";
	var additionalButtons = parent.additionalButtons[name];
	var additionalColorButtons = parent.additionalColorButtons[name];
	var eventListener = parent.eventListener[name];
	var showLineNumber = "<?php echo $_SESSION["showLineNumber".$_s]; ?>";
	var hideFlashTab = parent.hideFlashTab[name];
	var hideMediaTab = parent.hideMediaTab[name];
	var hideTagBar = parent.hideTagBar[name];

	loading_in_progress = true;

	// add additional buttons
	current_tb = 0;
	if (additionalButtons){
		for (var i = 0; i < additionalButtons.length; i++){
			if (additionalButtons[i][5]=="" || !DevEditToolbarSets[additionalButtons[i][5]]){
				var l = DevEditToolbarSets["Complete"].length;
				var j = DevEditToolbarSets["Complete"][l-1].length;
				DevEditToolbarSets["Complete"][l-1][j] = additionalButtons[i][0];
			} else {
				var mtb = additionalButtons[i][5];
				var l = DevEditToolbarSets[mtb].length;
				var j = DevEditToolbarSets[mtb][l-1].length;
				DevEditToolbarSets[mtb][l-1][j] = additionalButtons[i][0];
			}
		}
	}
	
	if (additionalColorButtons){
		var mtb;
		for (var i = 0; i < additionalColorButtons.length; i++){
			if (additionalColorButtons[i][4]=="" || !DevEditToolbarSets[additionalColorButtons[i][4]]){
				mtb = "Complete";
			} else {
				mtb = additionalColorButtons[i][4];
			}	
			var line = additionalColorButtons[i][5]-1;
			var pos = additionalColorButtons[i][6]-1;
			for (var j = DevEditToolbarSets[mtb][line].length; j>i ;j--){
				DevEditToolbarSets[mtb][line][j] = DevEditToolbarSets[mtb][line][j-1];
			}
			DevEditToolbarSets[mtb][additionalColorButtons[i][5]-1][additionalColorButtons[i][6]-1] = additionalColorButtons[i][0];
			
		}
	}

	if (loadedFile && loadedFile.lastIndexOf("/")>0)loadedFile = loadedFile.substr(0,loadedFile.lastIndexOf("/")+1);
		else loadedFile="";

	var r = new RegExp (deveditPath1,"gi")
	var web_root = deveditPath.replace(r,"");
	var r1 = new RegExp (web_root,"gi");
	if (web_root){
		loadedFile = loadedFile.replace(r1,serverurl);
	}

	var disableLinkUploading=parent.disableLinkUploading[name];
	var disableImageDeleting=parent.disableImageDeleting[name];
	var disableFlashUploading=parent.disableFlashUploading[name];
	var disableFlashDeleting=parent.disableFlashDeleting[name];
	var disableMediaUploading=parent.disableMediaUploading[name];
	var disableMediaDeleting=parent.disableMediaDeleting[name];
	var source_engine_enable = false;

	var HideWebImage=parent.HideWebImage[name];
	var HideWebFlash=parent.HideWebFlash[name];
	var HTTPStr=parent.HTTPStr[name];
	var imageLibs=parent.imageLibs[name];
	var flashLibs=parent.flashLibs[name];
	var myBaseHref=parent.myBaseHref[name];
	var isEditingHTMLPage=parent.isEditingHTMLPage[name];
	var ScriptName="<?php echo $_SESSION["ScriptName"]; ?>";
	ScriptName = ScriptName.substr(1)
	var snippetCSS = parent.snippetCSS[name];

	var skinName=parent.skinName[name];
	var toolbarmode = getParameter(s,"mode");
	if (!toolbarmode)toolbarmode = "<?php echo $_SESSION["mode".$_s]; ?>";
	var is_setCursor="<?php echo $_SESSION["setCursor".$_s]; ?>";

	var maximagewidth = "<?php echo $_SESSION["maximagewidth".$_s]; ?>";
	var maximageheight = "<?php echo $_SESSION["maximageheight".$_s]; ?>";
	var maxUploadFileSize = "<?php echo $_SESSION["maxUploadFileSize".$_s]; ?>";
	//var useSmartHistory = "<?php echo $_SESSION["useSmartHistory".$_s]; ?>";

var useSmartHistory = "1";

	var re3=parent.re3[name];
	var re4=parent.re4[name];
	var re5=parent.re5[name];
	var pathType=parent.pathType[name];
	var hideitems=new Array();
	var max_image_upload_width = 100;
	var max_image_upload_height = 100;
	getArray();
	buildFintList();

function buildFintList(){
	fontNameList="<?php echo $_SESSION["fontNameList".$_s]; ?>";
	if(fontNameList!=""){
		f=fontNameList.split(",")
		mFontname=new Array();
		mFontname[0]=["Font",""];
		var cnt = f.length;
		for(i=0;i<cnt;i++){
			mFontname[i+1]=[f[i],f[i]];
		}
		cnt++;
		mFontname[cnt] = ["-","",""];

		cnt++;
		mFontname[cnt] = ["Remove Font","",""];
	}
	fontSizeList="<?php echo $_SESSION["fontSizeList".$_s]; ?>";
	if(fontSizeList!=""){
		f=fontSizeList.split(",")
		mFontsize=new Array();
		mFontsize[0]=["Size",""];
		var cnt=0;
		for(i=0;i<f.length;i++){
			switch (f[i]){
				case "1":
					cnt++;
					mFontsize[cnt] = ["Size 1","8pt","1"];
					break;
				case "2":
					cnt++;
					mFontsize[cnt] = ["Size 2","10pt","2"];
					break;
				case "3":
					cnt++;
					mFontsize[cnt] = ["Size 3","12pt","3"];
					break;
				case "4":

					cnt++;
					mFontsize[cnt] = ["Size 4","14pt","4"];
					break;
				case "5":
					cnt++;
					mFontsize[cnt] = ["Size 5","18pt","5"];
					break;
				case "6":
					cnt++;
					mFontsize[cnt] = ["Size 6","24pt","6"];
					break;
				case "7":
					cnt++;
					mFontsize[cnt] = ["Size 7","36pt","7"];
					break;
			}
		}

		cnt++;
		mFontsize[cnt] = ["-","",""];

		cnt++;
		mFontsize[cnt] = ["Remove Size","","3"];
	}
}

function getArray(){
	disableHTMLEntities=<?php echo $_SESSION["d1".$_s]; ?>;
	hideitems["ContextMenu"]=<?php echo $_SESSION["disableContextMenu".$_s]; ?>;
	hideitems["Copy"]=<?php echo $_SESSION["Copy".$_s]; ?>;
	hideitems["Cut"]=<?php echo $_SESSION["Cut".$_s]; ?>;
	hideitems["Paste"]=<?php echo $_SESSION["Paste".$_s]; ?>;
	hideitems["Save"]=<?php echo $_SESSION["Save".$_s]; ?>;
	hideitems["Spellcheck"]=<?php echo $_SESSION["Spelling".$_s]; ?>;
	hideitems["RemoveFormat"]=<?php echo $_SESSION["RemoveTextFormatting".$_s]; ?>;
	hideitems["Fullscreen"]=<?php echo $_SESSION["FullScreen".$_s]; ?>;
	hideitems["Bold"]=<?php echo $_SESSION["Bold".$_s]; ?>;
	hideitems["Underline"]=<?php echo $_SESSION["Underline".$_s]; ?>;
	hideitems["Italic"]=<?php echo $_SESSION["Italic".$_s]; ?>;
	hideitems["Strikethrough"]=<?php echo $_SESSION["Strikethrough".$_s]; ?>;
	hideitems["OrderedList"]=<?php echo $_SESSION["NumberList".$_s]; ?>;
	hideitems["UnorderedList"]=<?php echo $_SESSION["BulletList".$_s]; ?>;
	hideitems["Indent"]=<?php echo $_SESSION["DecreaseIndent".$_s]; ?>;
	hideitems["Outdent"]=<?php echo $_SESSION["IncreaseIndent".$_s]; ?>;
	hideitems["SuperScript"]=<?php echo $_SESSION["SuperScript".$_s]; ?>;
	hideitems["SubScript"]=<?php echo $_SESSION["SubScript".$_s]; ?>;
	hideitems["JustifyLeft"]=<?php echo $_SESSION["LeftAlign".$_s]; ?>;
	hideitems["JustifyCenter"]=<?php echo $_SESSION["CenterAlign".$_s]; ?>;
	hideitems["JustifyRight"]=<?php echo $_SESSION["RightAlign".$_s]; ?>;
	hideitems["JustifyFull"]=<?php echo $_SESSION["Justify".$_s]; ?>;
	hideitems["Custominsert"]=<?php echo $_SESSION["Custom".$_s]; ?>;
	hideitems["Paragraph"]=<?php echo $_SESSION["Paragraph".$_s]; ?>;
	hideitems["HR"]=<?php echo $_SESSION["HorizontalRule".$_s]; ?>;
	hideitems["CreateLink"]=<?php echo $_SESSION["Link".$_s]; ?>;
	hideitems["Anchor"]=<?php echo $_SESSION["Anchor".$_s]; ?>;
	hideitems["CreateEmailLink"]=<?php echo $_SESSION["MailLink".$_s]; ?>;
	hideitems["Help"]=<?php echo $_SESSION["Help".$_s]; ?>;
	hideitems["Fontname"]=<?php echo $_SESSION["Font".$_s]; ?>;
	hideitems["Fontsize"]=<?php echo $_SESSION["Size".$_s]; ?>;
	hideitems["Formatblock"]=<?php echo $_SESSION["Format".$_s]; ?>;
	hideitems["Styles"]=<?php echo $_SESSION["Style".$_s]; ?>;
	hideitems["Fontcolor"]=<?php echo $_SESSION["ForeColor".$_s]; ?>;
	hideitems["Highlight"]=<?php echo $_SESSION["BackColor".$_s]; ?>;
	hideitems["Table"]=<?php echo $_SESSION["Table".$_s]; ?>;
	hideitems["Form"]=<?php echo $_SESSION["Form".$_s]; ?>;
	hideitems["Image"]=<?php echo $_SESSION["Image".$_s]; ?>;
	hideitems["Flash"]=<?php echo $_SESSION["Flash".$_s]; ?>;
	hideitems["Inserttextbox"]=<?php echo $_SESSION["TextBox".$_s]; ?>;
	hideitems["Insertchars"]=<?php echo $_SESSION["Symbols".$_s]; ?>;
	if (docType==1)hideitems["Pageproperties"]=<?php echo $_SESSION["Props".$_s]; ?>;
		else hideitems["Pageproperties"]=1;
	hideitems["Clearcode"]=<?php echo $_SESSION["Clean".$_s]; ?>;
	hideitems["Toggleposition"]=<?php echo $_SESSION["Absolute".$_s]; ?>;
	hideitems["EditMode"]=<?php echo $_SESSION["EditMode".$_s]; ?>;
	hideitems["SourceMode"]=<?php echo $_SESSION["SourceMode".$_s]; ?>;
	hideitems["PreviewMode"]=<?php echo $_SESSION["PreviewMode".$_s]; ?>;
	hideitems["Showborders"]=<?php echo $_SESSION["Guidelines".$_s]; ?>;
	hideitems["guidelinesOnByDefault"]=<?php echo $_SESSION["guidelinesOnByDefault".$_s]; ?>;
	hideitems["File"]=<?php echo $_SESSION["File".$_s]; ?>;
	hideitems["Media"]=<?php echo $_SESSION["Media".$_s]; ?>;

	hideitems["tlbmode"]="<?php echo $_SESSION["tlbmode".$_s]; ?>";

}

for (var i=0;i<parent.toolbarSetNames[name].length;i++){
	var tn = parent.toolbarSetNames[name][i];
	if(!DevEditToolbarSets[tn])DevEditToolbars[DevEditToolbars.length] = tn;
	DevEditToolbarSets[tn] = new Array();
	DevEditToolbarSets[tn][0] = new Array();
	var temp_row = 0;
	for (var j=0;j<parent.toolbarSet[name][tn].length;j++){
		if (parent.toolbarSet[name][tn][j]=="|"){
			temp_row++;
			DevEditToolbarSets[tn][temp_row] = new Array();
			continue;
		}
		DevEditToolbarSets[tn][temp_row][j] = parent.toolbarSet[name][tn][j];
	}
}

var cleared=false;
document.oncontextmenu=function(evt){return false;}
window.onunload=function(evt){
	if(!cleared){
		garbage.clearMemory();
	}
};
</script>

</head>
<body onfocus="if(E)E._window.focus();" style="overflow-y:hidden"  id="de_body" onload="javascript:if(E)E.setInitialValue();"  bgColor=#FFFFFF leftMargin=0 topMargin=0 marginheight="0" marginwidth="0">
<table width="100%" height="100%" cellspacing="0" cellpadding="0"><tr><td height="100%" width="100%" class="de_devborder">
<script type="text/javascript" language="javascript">
	var garbage = new Garbage();
	E=new Editor(name,width,height,skinName);
	E.Create();
	<?php echo "parent." . $_s . " = E;"; ?>
	E.writeContentWithCSS();
</script>
</td></tr></table>
</body>
</html>