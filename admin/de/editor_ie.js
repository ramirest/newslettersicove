// Name: Editor class
// Info: Contain all editors methonds for IE.

var cssloaded = 0;
var cached_Styles = null;
var cached_cssText = null;

var deEngine = new Replacer();
deEngine.addRule("(<script[^>]*?>[\\s\\S]*?</script>)");

// Save command

var Save = function()
{
	this.Name = 'save' ;
};

Save.prototype.execute = function(isUpdate)
{
	if (loading_in_progress && !isUpdate){
		parent.document.getElementById(E.InstanceName+"_html").value = parent.document.getElementById(E.InstanceName+"_input").value;
		parent.document.getElementById(E.InstanceName+"_save").click();			
		loading_in_progress = false;
		return;
	}
	try{
		aed = E._frame;

		var code = "";
		if (E.mode=="source") {
			code = aed.body.innerText;
		} else {
			E.commands.getCommand("Paragraph").hideChar();
			code = E.HTMLTags[1]+img2embed(removeWrap(aed.documentElement.innerHTML))+E.HTMLTags[3];
			E.commands.getCommand("Paragraph").showChar();
			code = removeBase(code);
			code = removeSpellSpan(code);
		}

		if (useXHTML==1) {
			code = getXHTML(code);
		}

		re = /&amp;/g;
		code = code.replace(re,'&');

		if (pathType == "1") {
			replaceImage = 'src="';
			code = code.replace(re3,replaceImage);
		}

		code = ConvertSSLImages(code);
		code = convert_align_middle_to_center(code);
		if (deEngine)code = deEngine.restore(code);
	
		if (E.mode=="edit") {
			code = convert_param_tag_to_xhtml(code);
		}

		if (E.mode=="source") {
			code = _de_save_special_char2(code);
		} else {
			code = _de_save_special_char(code);
		}
		
		parent.document.getElementById(E.InstanceName+"_html2").value = code;

		if (docType!=1 && E.mode=="edit"){ // for snippet mode
			h = code.match(BodyContents);
			code = h[2];
		}

		code = formatSaveCode(code);
		
		var donotformat = 0;
	    	if (E.isbegin && E.isend) {
		       code = replace_editable(code);
		     donotformat=1;
		}		

		parent.document.getElementById(E.InstanceName+"_html").value = code;

		if (isUpdate!=="no") {
			parent.document.getElementById(E.InstanceName+"_save").click();
		}
	} catch(e){
	}
};

Save.prototype.getMode = function()
{
	return OFF;
};
// ***** end of Save command *****


// Paste as Plain Text command
var PasteText = function()
{
	this.Name = "PasteText";
};

PasteText.prototype.execute = function()
{
	try {
		var s = clipboardData.getData("Text");
		s = s.replace( /\n/g, '<BR>' ) ;
		E._inserthtml(s) ;
	} catch(e){}	
};

PasteText.prototype.getMode = function()
{
	try
	{
		if (!E._frame.queryCommandEnabled( "paste" ) ) {
			return DISABLED ;
		} else {
			return E._frame.queryCommandState( "paste" ) ? ON : OFF ;
		}
	}
	catch ( e )	{
		return OFF ;
	}
};

// Source mode class
var SourceCommand = function()
{
	this.Name = "SourceMode";
};

SourceCommand.prototype.getMode = function()
{
};

SourceCommand.prototype.execute = function()
{
	ToolbarSets.SwitchToolbarSet("_source");

	var e = E._frame;

	E.commands.getCommand("Spellcheck").off();
	E.commands.getCommand("Paragraph").hideChar();

	var fr = document.getElementById(E.InstanceName+"_frame");

	if (E.showborders==ON) {
		E.commands.getCommand("Showborders").reset(true);
	}

	document.getElementById("ePath").style.display="none";
	if (fr.style.display=="none") {
		var Ifr = document.getElementById(E.InstanceName+"_preview");
		fr.style.display="";
		Ifr.style.display="none";
	}

	if (E.mode=="edit") {
		if (docType==1) { // DE_DOC_TYPE_HTML_PAGE
			var c = E.HTMLTags[1] + E._frame.documentElement.innerHTML + E.HTMLTags[3];
			if (useXHTML==1) {
				iHTML = getXHTML(c);
			} else {
				iHTML = c;
			}
		} else{
			var c = E.HTMLTags[1] + E._frame.documentElement.innerHTML + E.HTMLTags[3];
			E.part = c.match(BodyContents);
			if (useXHTML==1) {
				iHTML = getXHTML(e.body.innerHTML);
			} else {
				iHTML = e.body.innerHTML;
			}
		}

		re = /&amp;/g;
		iHTML = removeBlock(iHTML);
		iHTML = iHTML.replace(re,'&');
		iHTML = convert_align_middle_to_center(iHTML);
		iHTML = convert_param_tag_to_xhtml(iHTML);
		iHTML = removeBase(iHTML);
		iHTML = deEngine.restore(iHTML);

		// for nice looking
		e.body.style.fontFamily = "Verdana";
		e.body.style.fontSize = "11px";
		e.body.style.lineHeight = 1;		
		e.body.style.color = "#000000";
		e.body.color = "#000000";
		e.body.bgColor = '#FFFFFF';
		e.body.style.text = '#000000';
		e.body.style.background = '';
		e.body.style.marginTop = '10px';
		e.body.style.marginLeft = '10px';
		e.body.style.textAlign = 'left';

		// switch to source view
		e.body.innerText = img2embed(iHTML) ;
		var c=e.body.innerHTML;
		c = _de_parse_special_char(c);
		html = convert_attr_name_to_lowercase(addEditTag(colourCode(formatCodeIE(c))));
		e.body.innerHTML = html + "<div style='width:5000px;height:0px'></div>";
	}

	E.mode="source";
	E.lastmode="source";
	E.calculateNewSize();
			
	buffer["source"].devhistory.position = 0;
	E.Focus();
	set_cursor_at_begin();
	move_cursor_right_and_left();
};

// Edit mode
var EditCommand = function()
{
	this.Name = "EditMode";
};

EditCommand.prototype.getMode = function()
{
};

EditCommand.prototype.execute = function()
{
	var e = E._frame;

	var fr = document.getElementById(E.InstanceName+"_frame");
	document.getElementById("ePath").style.display="block";

	if (fr.style.display=="none") {
		var Ifr = document.getElementById(E.InstanceName+"_preview");
		fr.style.display="";
		Ifr.style.display="none";
	}

	var headTag = /<head\s*>/i;
	if (E.mode=="source") {
		//switch back to regular view
		iText = e.body.innerText;

		var addbase="";
		add_to_head = "";
		if (docType==1) { // DE_DOC_TYPE_HTML_PAGE
			if (myBaseHref && E.useBase) {
				//add_to_head += '<base href="'+myBaseHref+'" />';
			}
			var html = iText.replace(headTag,'<head>'+add_to_head+'<style id="'+E.InstanceName+'bs">'+border_style+'<\/style>'+addbase);
			var rh = html.match(HeadContents);
			if (rh) {
				E.headhtml = rh[2];
			}
		} else {
			var html = E.part[1] + iText + E.part[3];

			var add_to_head = '<style id="'+E.InstanceName+'bs">'+border_style+'<\/style>'//'<link disabled href="'+E.skinPath+'borders.css" type="text/css" rel="stylesheet" />';
			if (E.snippetCSS) {
				add_to_head += '<link href="'+E.snippetCSS+'" type="text/css" rel="stylesheet" />';
			}
			if (myBaseHref) {
				//add_to_head += '<base href="'+myBaseHref+'" />';
			}

			newhtml = html.match(HeadContents);
			html = newhtml[1]+add_to_head+newhtml[2]+newhtml[3];
		}
		html = addBlock(html);
		E.writeC(deEngine.preserve(embed2img(removeEditTag(html))),1);
	}

	E.mode="edit";
	E.lastmode="edit";
	E.calculateNewSize();	
	if (E.showborders==ON){
		E.commands.getCommand("Showborders").set(false);
	} else {
		E.commands.getCommand("Showborders").reset(false);
	}
	E.commands.getCommand("Paragraph").showChar();
	ToolbarSets.SwitchToolbarSet(toolbarmode);
	E.doStyles();
	E.Focus();
	move_cursor_right_and_left();
	checkImgLoaded();
	setTimeout("_de_scroll_top()",300);
};

// Preview mode class
var PreviewCommand = function()
{
	this.Name = "PreviewMode";
};

PreviewCommand.prototype.getMode = function()
{
};

PreviewCommand.prototype.execute = function()
{
	ToolbarSets.SwitchToolbarSet("_preview");

	var aed = E._frame;
	var e = document.getElementById(E.InstanceName+"_frame");
	if (E.showborders==ON) {
		E.commands.getCommand("Showborders").reset(false);
	}
	E.commands.getCommand("Paragraph").hideChar();

	document.getElementById("ePath").style.display="none";
	var Ifr = document.getElementById(E.InstanceName+"_preview");
	e.style.display="none";
	Ifr.style.display="";

	var addbase = "";
	if (myBaseHref && E.useBase) {
		addbase += '<base href="'+myBaseHref+'" />';
	}	
	if (E.mode=="source"){
		if (docType==1){ // DE_DOC_TYPE_HTML_PAGE
			var c = addbase+aed.body.innerText;
		} else {
			var bodyhtml = addbase+aed.body.innerText;
			var c = E.part[1] + bodyhtml + E.part[3];
		}
		c = img2embed22(c);
	} else{
		var c = "<html>" + img2embed2(E._frame.documentElement.innerHTML)+"</html>";
		c = img2embed22(img2embed2(c));
		c = removeBlock(c);
	}

	window.frames[E.InstanceName+"_preview"].document.write("<html>"+c.replace(/(&lt;([\s\S]*?)borders.css([\s\S]*?)&gt;)/gi,'') + "</html>");
	checkImgLoaded(window.frames[E.InstanceName+"_preview"].document);
	window.frames[E.InstanceName+"_preview"].document.close();
	E.calculateNewSize("preview");	
	E.lastmode="preview";
	window.frames[E.InstanceName+"_preview"].focus();
};


function clearCode(code)
{
	code = code.replace(/[”“]/gi,'"');
	code = code.replace(/[‘’]/gi,"'");
	code = code.replace(/<([\w]+) class=([^ |>]*)([^>]*)/gi, "<$1$3");
	code = code.replace(/<([\w]+) style="([^"]*)"([^>]*)/gi, "<$1$3");
	code = code.replace(/<\\?\??xml[^>]>/gi, "");
	code = code.replace(/<\/?\w+:[^>]*>/gi, "");
	code = code.replace(/<p([^>])*>(&nbsp;)*\s*<\/p>/gi,"");
	code = code.replace(/<span([^>])*>(&nbsp;)*\s*<\/span>/gi,"");
	code = code.replace(/<b([^>])*>(&nbsp;)*\s*<\/b>/gi,"");	
	code = code.replace(/<(\w[^>]*) lang=([^ |>]*)([^>]*)/gi, "<$1$3") ;
	code = code.replace( /\s*mso-[^:]+:[^;"]+;?/gi, "" ) ;
	code = code.replace(/<\\?\?xml[^>]*>/gi, "") ;
	code =  code.replace( /\s*style="\s*"/gi, '' ) ;
	code = code.replace( /<SPAN\s*[^>]*>\s*&nbsp;\s*<\/SPAN>/gi, '&nbsp;' ) ;
	code = code.replace( /<SPAN\s*[^>]*><\/SPAN>/gi, '' ) ;

	code = code.replace( /<([^\s>]+)[^>]*>\s*<\/\1>/g, '' ) ;
	code = code.replace( /<([^\s>]+)[^>]*>\s*<\/\1>/g, '' ) ;
	code = code.replace( /<([^\s>]+)[^>]*>\s*<\/\1>/g, '' ) ;
	
	return code;
}

// Clear Code command
var ClearCodeCommand = function()
{
	this.Name = 'ClearCode' ;
};

ClearCodeCommand.prototype.execute = function()
{
	if (confirm("Are you sure you want to clean your HTML code?")) {
		code = E._frame.body.innerHTML;
		code = clearCode(code);
		E._frame.body.innerHTML = code;
		E._window.focus();
	}
};

ClearCodeCommand.prototype.getMode = function()
{
	if (E.isbegin && E.isend) {
		return DISABLED;
	}
	return OFF;
};


// Fullscreen class
var FullscreenCommand = function()
{
	this.Name = 'Fullscreen' ;
	e = parent.document.getElementById(E.InstanceName+"main");
	this.oldw = e.style.width;
	this.oldh = e.style.height;
	this.style = null;
	this.scrollXPos = 0;
	this.scrollXPos = 0;	
};

FullscreenCommand.prototype.changemode = function(str,par, val)
{
	idx=str.indexOf(par+"=",1);
	idx2=str.indexOf("&",idx+1);
	if((idx>=0)&&(idx2==-1)) {
		idx2=str.length;
	}
	if (idx!=-1){
		str = str.substring(0,idx+par.length+1) + val + str.substring(idx2,str.length);
	} else {
		if (str.indexOf("?")!=-1){
			str = str + "&" + par + "=" + val;
		} else {
			str = str + "?"+par + "=" + val;
		}
	}	
	
	return str;	
};

function Resize_DE_Editor(){
	e = parent.document.getElementById(E.InstanceName+"main");
	e.style.width = Get_Window_Width();
	e.style.height = Get_Window_Height();
		
	E.calculateNewSize();
	try {E._frame.designMode = "On";}catch(e){}
	ToolbarSets.Redraw();
	try {E.Focus();}catch(e){}
}

function Get_Window_Height(){
	if (parent.document.documentElement && parent.document.documentElement.clientWidth) {
		x = parent.document.documentElement.clientHeight;
	} else {
		x = parent.document.body.clientHeight;
	}
	return x;
}

function Get_Window_Width(){
	if (parent.document.documentElement && parent.document.documentElement.clientWidth) {
		x = parent.document.documentElement.clientWidth;
	} else {
		x = parent.document.body.clientWidth;
	}
	return x;
}

FullscreenCommand.prototype.execute = function()
{
	e = parent.document.getElementById(E.InstanceName+"main");
	e1 = document.getElementById(E.InstanceName+"main1");

	if (E.fullscreen == OFF) {
		
		// hide sidebar
		this.style = SaveStyle(parent.document.body);
		parent.document.documentElement.style.overflow = "hidden";
		parent.document.body.style.overflow = "hidden";
	
		if (parent.document.documentElement && parent.document.documentElement.clientWidth) {
			sL = parent.document.documentElement.scrollLeft;
			sT = parent.document.documentElement.scrollTop;
		} else {
			sL = parent.document.body.scrollLeft;
			sT = parent.document.body.scrollTop;
		}
		w = Get_Window_Width();
		h = Get_Window_Height();
		this.scrollXPos = sL;
		this.scrollYPos = sT;
		
		e1.style.height = "100%";
		e.style.position = 'absolute';
		e.style.left = "0px";
		e.style.top = "0px";
		e.style.width = w+"px";
		e.style.height = h+"px";
		e.style.zIndex = 100;
		E.fullscreen = ON;
		
		E.href = this.changemode(E.href,"sizemode","on");		

		parent.document.documentElement.style.overflow = "hidden";
		parent.document.body.style.overflow = "hidden";	
		parent.scrollTo(0,0);
		parent.attachEvent( 'onresize', Resize_DE_Editor) ;		

	} else {
		parent.detachEvent( "onresize", Resize_DE_Editor ) ;
		e.style.position = '';
		e.style.left = 0;
		e.style.top = 0;
		e.style.width = E.oldw;
		e.style.height = E.oldh;
		E.fullscreen=OFF;
		RestoreStyle(parent.document.body,this.style);
		E.href = this.changemode(E.href,"sizemode","off");
		parent.scrollTo(this.scrollXPos,this.scrollYPos);
		//move_cursor_right_and_left();
	}
		
	E.calculateNewSize();
	try {E._frame.designMode = "On";}catch(e){}
	ToolbarSets.Redraw();
	try {E.Focus();}catch(e){}
};

FullscreenCommand.prototype.getMode = function()
{
	if (E.fullscreen==ON) {
		return ON;
	} else {
		return OFF;
	}
};

SaveStyle = function(o)
{
	var oSavedStyles = new Object() ;
	
	if ( o.className.length > 0 )
	{
		oSavedStyles.Class = o.className ;
		o.className = '' ;
	}

	var sInlineStyle = o.style.cssText ;

	if ( sInlineStyle.length > 0 )
	{
		oSavedStyles.Inline = sInlineStyle ;
		o.style.cssText = '' ;
	}
	
	return oSavedStyles ;
}

RestoreStyle = function(o, savedStyles){
	o.className		= savedStyles.Class || '' ;
	o.style.cssText	= savedStyles.Inline || '' ;
}


// InsertTextBox class
var InsertTextBox = function()
{
	this.Name = 'Inserttextboxe' ;
};

InsertTextBox.prototype.execute = function()
{
	E._inserthtml("<table id=de_textBox style='position:absolute;'><tr><td>Text box</td></tr></table>");
	textBox = E._frame.getElementById("de_textBox");
	textBox.removeAttribute("id");
	E._window.focus();
};

InsertTextBox.prototype.getMode = function()
{
	return OFF;
};

// Show borders
var ShowBordersCommand = function()
{
	this.Name = 'Showborders' ;
};

// function for set style
var el = new Object;
el.id 		= [];
el.className= [];

function setClassForInput()
{
	var elements = E._frame.getElementsByTagName("INPUT");
	for(var i = 0; i < elements.length; i++) {
		if (elements.item(i).type.toUpperCase()!="HIDDEN") {
			continue;
		}
		el.id[el.id.length] = elements.item(i);
		el.className[el.className.length] = elements.item(i).getAttribute("className");
		elements.item(i).className = "de_style_input";
	}
}

function setClassForAnchor()
{
	var elements = E._frame.getElementsByTagName("A");
	for(var i = 0; i < elements.length; i++) {
		if (!elements.item(i).name) {
			continue;
		}
		el.id[el.id.length] = elements.item(i);
		el.className[el.className.length] = elements.item(i).getAttribute("className");
		elements.item(i).className = "de_style_anchor";
	}
}


function resetClassForAll()
{
	for(var i = 0; i < el.id.length; i++) {
	 try {	
		el.id[i].className = el.className[i];
		if (!el.className[i]) el.id[i].removeAttribute("className");
	 } catch(e){}	
	}
	el.id.length=0;
	el.className.length=0;
}


ShowBordersCommand.prototype.switchCSS = function(on)
{
 try{
	E._frame.getElementById(E.InstanceName+"bs").disabled = on;
 }catch(e){}
};

ShowBordersCommand.prototype.set = function()
{
	var sheets = E._frame.styleSheets;
	if (el.id&&el.id.length>0) {
		resetClassForAll();
	}
	setClassForInput();
	setClassForAnchor();
	this.switchCSS(false);
};

ShowBordersCommand.prototype.reset = function()
{
	var sheets = E._frame.styleSheets;
	this.switchCSS(true);
	resetClassForAll();
};

ShowBordersCommand.prototype.execute = function(ignore)
{
	if (E.showborders==OFF) {
		if(ignore!==true) {
			E.showborders=ON;
		}
		this.set();
	} else {
		this.reset();
		if (ignore!==true) {
			E.showborders=OFF;
		}
	}
};

ShowBordersCommand.prototype.getMode = function()
{
	return E.showborders;
};

// paste from MS Word
var PasteFromMSWord = function()
{
	this.Name = 'pastefrommsword' ;
	this.mode = OFF;
};

PasteFromMSWord.prototype.execute = function()
{
	E.Focus();
	var oDiv = document.getElementById( '__HiddenDiv' ) ;

	if ( !oDiv ) {
		// use temp div to paste
		var oDiv = document.createElement( 'DIV' ) ;
		oDiv.id = '__HiddenDiv' ;
		oDiv.style.visibility	= 'hidden' ;
		oDiv.style.overflow		= 'hidden' ;
		oDiv.style.position		= 'absolute' ;
		oDiv.style.width		= 1 ;
		oDiv.style.height		= 1 ;

		document.body.appendChild( oDiv ) ;
	}

	oDiv.innerHTML = '' ;
	var oTextRange = document.body.createTextRange() ;
	oTextRange.moveToElementText( oDiv ) ;
	oTextRange.execCommand( 'Paste' ) ;
	var sData = oDiv.innerHTML ;
	oDiv.innerHTML = '' ;
	code = clearCode(sData);
	E.insertHTML( code ) ;
};


PasteFromMSWord.prototype.getMode = function()
{
	try
	{
		if (!E._frame.queryCommandEnabled( "paste" ) ) {
			return DISABLED ;
		} else {
			return E._frame.queryCommandState( "paste" ) ? ON : OFF ;
		}
	}
	catch ( e ) {
		return OFF ;
	}
};
// ***** end of [paste from MS Word] ******


// Undo command
var Undo = function(modename)
{
	this.Name = 'undo';
	this.mode = modename;
};

Undo.prototype.execute = function()
{
	buffer[E.mode].goHistory(-1);
	E._window.focus();
};

Undo.prototype.getMode = function(){
	if ((buffer[E.mode].devhistory.data.length <= 0) || (buffer[E.mode].devhistory.position <= 0)) {
		return DISABLED;
	} else {
		return OFF;
	}
};

// Redo command
var Redo = function(modename)
{
	this.Name = 'undo';
	this.mode = modename;
};

Redo.prototype.execute = function()
{
	buffer[E.mode].goHistory(1);
	E._window.focus();
};

Redo.prototype.getMode = function()
{
	if ((buffer[E.mode].devhistory.position >= buffer[E.mode].devhistory.data.length-1) || (buffer[E.mode].devhistory.data.length == 1)) {
		return DISABLED;
	} else {
		return OFF;
	}
};

// Buffer class (buffer for undo/redo)

var URBuffer = function()
{
	var devhistory = new Object;
	devhistory.data = [];
	devhistory.position = 0;
	devhistory.bookmark = [];
	devhistory.offset = [];
	devhistory.max = _de_max_history_item;
	this.devhistory = devhistory;
};

URBuffer.prototype.saveHistory = function(kk)
{
	var recpos;

	try {
		recpos = this.devhistory.position;		

		// new code
		oSel = E._frame.selection;
		range = oSel.createRange();
		switch (sel.type) {
			case "Control":
				p = range.item(0).parentNode;
				break;
			default:	
				p = range.parentElement();	
		}
		
		if (kk && kk==13)p=p.parentNode;
		
		// check if there is some changes
		if (p.innerHTML==this.devhistory.data[this.devhistory.length-1]){
			return;
		}
		if (this.devhistory.data.length >= this.devhistory.max) {
			if (this.devhistory.position==0){
				this.devhistory.data.length = 0;
				this.devhistory.bookmark.length = 0;
				this.devhistory.offset.length = 0;
			} else {
				this.devhistory.data.shift();
				this.devhistory.bookmark.shift();
				this.devhistory.offset.shift();
				this.devhistory.position--;
			}
		}
		this.devhistory.data.length = this.devhistory.position+1;
		
		this.devhistory.data[recpos]  = p.innerHTML;
		
		var next = p.parentNode;
		this.devhistory.offset[recpos] = new Array();
		var cnt=0;

		while (next){
			next = next.firstChild;
			this.devhistory.offset[recpos][cnt] = 0;
			while (next !== p){
			  this.devhistory.offset[recpos][cnt]++;
		      next = next.nextSibling;
			}
			cnt+=1;
			p = next.parentNode;
			if (p.tagName && p.tagName.toLowerCase()=="html") break;
			next = p.parentNode;   
		}   

		// end new code		

			if (E.GetType() != "Control") {
				this.devhistory.bookmark[recpos] = aed.selection.createRange().getBookmark();
			} else {
				oControl = aed.selection.createRange();
				this.devhistory.bookmark[recpos] = oControl(0);
			}

			this.devhistory.position++;
		
	} catch (e){}
};

URBuffer.prototype.goHistory = function(value)
{
	try{
	if (value == -1) {
		this.devhistory.position--;
	} else {
		this.devhistory.position++;	
	}

	if (this.devhistory.position < 0)	{
		this.devhistory.position = 0;
	}

	if (this.devhistory.position == this.devhistory.data.length-1 && value==-1)	{
		this.devhistory.position++;
		this.saveHistory();
		this.devhistory.position -= 2;
	}
	
	rp = this.devhistory.position;
	p = E._frame.body;
			
	var i = this.devhistory.offset[rp].length-2;
	if (i>=0)
	do {
		p = p.childNodes[this.devhistory.offset[rp][i]];
	}
	while (i--);
	p.innerHTML=this.devhistory.data[rp];

	this.setHistoryCursor();

	if (E.showborders==ON) {
		E.commands.getCommand("Showborders").set(true);
	} else {
		E.commands.getCommand("Showborders").reset(true);
	}
	} catch(e){}
}

URBuffer.prototype.setHistoryCursor = function()
{
	aed = E._frame;

	var range;
	if (this.devhistory.bookmark[this.devhistory.position]) {
		range = aed.body.createTextRange();
		if (this.devhistory.bookmark[this.devhistory.position] != "[object]") {
			if (range.moveToBookmark(this.devhistory.bookmark[this.devhistory.position])) {
				doSave = 1;
				range.select();
				doSave = 0;

			}
		}
	}
};

// initialize buffer
var buffer = new Array();
buffer["edit"] = new URBuffer();
buffer["source"] = new URBuffer();

// main class- EDITOR
var Editor = function( instanceName, width, height, skinName)
{
	window.activeEditor	= this;

	// Properties
	this.InstanceName	= instanceName ;
	this.Value			= '' ;
	this.Width			= width;
	this.Height			= height;
	this.skinName		= skinName ;
	this.part			= null;

	// auxiliary properties
	this._frame			= false;
	this.mode			= "edit";
	this.lastmode		= "edit";
	this.showborders	= OFF;
	this.customLink		= CustomLink;
	this.tempWin		= null;
	this.tempEl;
	this.arr			= null;
	this.toolbarpos		= 0;
	this.unlock			= false;
	this.skinPath		= serverurl + deveditPath1 + '/'+skinPath;
	this.snippetCSS		= snippetCSS;
	this.selectedtag	= null;
	this.lastinsert	= null;
	this.editable		= true;
	this.href			= window.location.href;
	this.norefresh		= false;
	this.initHTML		= "";
	this.use_old_base	= false;
	this.disableTabs	= false;
	this.useBase		= false;
	this.isbegin		= false;
	this.isend			= false;
	this.lastOffset		= null;
	this.lastContainer	= null;
	
	if (this.getParameter("sizemode")=="on") {
		this.fullscreen		= ON ;
	} else {
		this.fullscreen		= OFF ;
	}
	this.oldw=0;
	this.oldh=0;

	if (toolbarmode) {
		for (var i=0;i<DevEditToolbars.length;i++){
			if (DevEditToolbars[i]==toolbarmode) {
				this.toolbarpos = i;
				return;
			}
		}
	}
	toolbarmode = DevEditToolbars[0];
	this.toolbarpos = 0;
};

Editor.prototype.execCommand = function(name)
{
	if (this.commands.getCommand(name)) {
		this.commands.getCommand(name).execute();
		ToolbarSets.Redraw();
	}
};

Editor.prototype.switchModeTo = function(mode)
{
	if (!mode) return false;
	if (typeof(mode)!== "string") return false;
	if (mode.toLowerCase()==this.lastmode)return false;
	switch (mode.toLowerCase()){
		case "edit":
			this.execCommand("EditMode");
			this.sheet.Select("EditMode");
			break;
		case "source":
			this.execCommand("SourceMode");
			this.sheet.Select("SourceMode");
			break;
		case "preview":
			this.execCommand("PreviewMode");
			this.sheet.Select("PreviewMode");
	}
	return true;
};

Editor.prototype.getHTMLContent = function()
{
	return _de_getContent();
};

Editor.prototype.getTextContent = function()
{
	return _de_getTextContent();
};

Editor.prototype.setParameter = function(par, val)
{
	var str = this.href;
	idx=str.indexOf(par+"=",1);
	idx2=str.indexOf("&",idx+1);
	if((idx>=0)&&(idx2==-1)) {
		idx2=str.length;
	}
	if (idx!=-1){
		this.href = str.substring(0,idx+par.length+1) + val + str.substring(idx2,str.length);
	} else {
		if (str.indexOf("?")!=-1){
			this.href = str + "&" + par + "=" + val;
		} else {
			this.href = str + "?"+par + "=" + val;
		}
	}	
};

Editor.prototype.getParameter = function(par)
{
	var str = this.href;
	idx=str.indexOf(par+"=",1);
	idx2=str.indexOf("&",idx+1);
	if((idx>=0)&&(idx2==-1)) {
		idx2=str.length;
	}
	if (idx!=-1){
		return str.substring(idx+par.length+1,idx2);
	} else {
		return "0";
	}	
};

Editor.prototype.attachStyleSheet = function(name)
{
	this.addStyleSheet(this._frame, name);
}

// add StyleSheet (name) to document( d )
Editor.prototype.addStyleSheet = function(d,name)
{
	var e=d.createStyleSheet(name);
	return e;
};

Editor.prototype.setSize = function()
{
	var pDiv = parent.document.getElementById(E.InstanceName+"main");
	this.realHeight = pDiv.offsetHeight;
	var tDiv = document.getElementById(E.InstanceName+"main1");
	if (pDiv.offsetWidth<tDiv.offsetWidth) {
		pDiv.style.width = (tDiv.offsetWidth+10)+"px";
	}
	if (pDiv.offsetHeight<tDiv.offsetHeight) {
		pDiv.style.height = (tDiv.offsetHeight)+"px";
	}
	if (this.getParameter("oldw")=="0") {
		this.oldw = pDiv.offsetWidth;
		this.oldh = pDiv.offsetHeight;
		this.setParameter("oldw",this.oldw);
		this.setParameter("oldh",this.oldh);
	} else {
		this.oldw = this.getParameter("oldw")*1.0;
		this.oldh = this.getParameter("oldh")*1.0;
	}
};

Editor.prototype.checkLoad = function()
{
	if (hideitems["EditMode"]==1 && hideitems["SourceMode"]==1 && hideitems["PreviewMode"]==1) {
		this.disableTabs=true;
	}
	this.commands = new Commands();
	this.toolbaritems = new ToolbarItems();

	ToolbarSets.AddItem(toolbarmode,'eToolbar');

	this.setSize();

	ToolbarSets.AddItem("path",'ePath');
	if (!this.disableTabs) {
		ToolbarSets.AddItem("footer",'eFooter');
	}

	this.sheet = new Sheet();
	if (hideitems["EditMode"]==0) {
		this.sheet.AddItem("EditMode", "Edit mode", "status_edit", CHOOSE);
	}
	if (hideitems["SourceMode"]==0) {
		this.sheet.AddItem("SourceMode", "Source mode", "status_source", OFF);
	}
	if (hideitems["PreviewMode"]==0) {
		this.sheet.AddItem("PreviewMode", "Preview mode", "status_preview", OFF);
	}
};

Editor.prototype.calculateNewSize = function(mode)
{
	if (new_layout){
		H = parent.document.getElementById(this.InstanceName + 'main').style.pixelHeight;
		W = parent.document.getElementById(this.InstanceName + 'main').style.pixelWidth;

		if (mode && mode=="preview"){

			dH = 77;
			if (hideTagBar)dH = dH - 23;
		
			document.getElementById(this.InstanceName + "_preview").height = H-dH;
		} else {
			if (this.mode=="edit"){
				tlb_H = DevEditToolbarSets[toolbarmode].length * 27;
			} else {
				tlb_H = 2 * 27;
			}

			dH = 51+tlb_H;
			if (hideTagBar)dH = dH - 23;
			
			if (this.mode=="edit"){
				document.getElementById(this.InstanceName + "_frame").height = H-dH;
			} else {
				document.getElementById(this.InstanceName + "_frame").height = H-(dH-3);
			}
			//document.getElementById(this.InstanceName + "_frame").width = W-10;
		}
	}	
}

Editor.prototype.setInitialValue = function()
{
	findCustomTags();// ??
	
	if (this.init) {
		return;
	}
	
	// set default color	
	if (E.toolbaritems&&E.toolbaritems.GetItem('Fontcolor')) {
		E.toolbaritems.GetItem('Fontcolor').SetButtonColor("#000000");
	}
	if (E.toolbaritems&&this.toolbaritems.GetItem('Highlight')) {
		this.toolbaritems.GetItem('Highlight').SetButtonColor("yellow");
	}
	if (hideitems["guidelinesOnByDefault"]==1 && !(E.lastmode=="source" && E.showborders==OFF)) {
		this.commands.getCommand('Showborders').execute();
	}

	PopupSet.CreatePopup("spellmenu");
	PopupSet.CreatePopup("sourcetag");
	
	ToolbarSets.Redraw();
	E.sheet.Redraw();
	E.checkEditArea();

	var y = GetCookie("scroll_top");
	if (y) {
		parent.scrollTo(0,y);
	}
	setTimeout("_de_initialize()",10);
	
	checkImgLoaded();

	if (hideitems["EditMode"]==1){
		this.switchModeTo("source");
	}
	
	this.init = true;
	if (eventListener){
		for (var i = 0; i < eventListener.length; i++){
			if (eventListener[i][0].toLowerCase()=="onload"){
				eval("parent."+eventListener[i][1]+"()");
			}	
		}
	}
	loading_in_progress = false;
	setTimeout("_de_scroll_top()",500);
};

function _de_scroll_top(){
 try{	
	if (E._frame.documentElement && E._frame.documentElement.clientWidth) {
		E._frame.documentElement.scrollTop = 0 ;
	} else {
		E._frame.body.scrollTop = 0;
	}
 } catch (e) {}	
}

function _de_initialize(){
	try {
		move_cursor_right_and_left();
		E.doStyles();
		E.toolbaritems.GetItem('Styles').Redraw();
	} catch(e){}

}

setTimeout("_de_initialize2()","500");
	
function _de_initialize2(){
	try {
		if(loading_in_progress)E.commands.getCommand('Bold').execute();
		if(loading_in_progress)setTimeout("_de_initialize2()","500");
	} catch(e){}			
}

Editor.prototype.writeC = function(data,ignore)
{
	try{
		aed = this._frame;
		aed.open();
		data = convert_attr_name_to_lowercase(data);
		if (ignore) {
			aed.write(data);
		} else {
			aed.write(embed2img(addWrap(data)));
		}
		aed.close();
	} catch(e){}
};

Editor.prototype.writeHTMLContent = function(data)
{
	if (this.mode=="source"){
		data = data.replace(/</g,"&lt;");
		data = data.replace(/>/g,"&gt;");
	}	
	this.writeContentWithCSS(data);
};

Editor.prototype.writeContentWithCSS = function(data)
{
	var html="";
	if (parent.document.getElementById(name+"_html2").value!="" && !data) {
		html = parent.document.getElementById(name+"_html2").value;
		html = html.replace(/&amp;/g,'&');
		if (html.toLowerCase().indexOf("<html")>=0){
			var add_to_head = '<style id="'+this.InstanceName+'bs">'+border_style+'<\/style>';
			if (this.snippetCSS) {
				add_to_head += '<link href="'+this.snippetCSS+'" type="text/css" rel="stylesheet" />';
			}
			newhtml = html.match(HeadContents);
			html = newhtml[1]+add_to_head+newhtml[2]+newhtml[3];
		}
	} else {
		var h = data ? data : parent.document.getElementById(name+"_input").value;
		if (h.toLowerCase().indexOf("<html")>=0) {
			var add_to_head = '<style id="'+this.InstanceName+'bs">'+border_style+'<\/style>';
			if (this.snippetCSS) {
				add_to_head += '<link href="'+this.snippetCSS+'" type="text/css" rel="stylesheet" />';
			}
			newhtml = h.match(HeadContents);
			if (newhtml!==null) {
				html = newhtml[1]+add_to_head+newhtml[2]+newhtml[3];
			} else {
				html = h;
			}
		} else {
			html = "<html><head>";
			html += '<style id="'+this.InstanceName+'bs">'+border_style+'<\/style>';
			this.use_old_base = true;
			if (this.snippetCSS) {
				html += '<link href="'+this.snippetCSS+'" type="text/css" rel="stylesheet" />';
			}
			html+="</head>" + h + "</html>";
		}
	}
	// set initial HTML code for restricted mode
	var h = data ? data : parent.document.getElementById(name+"_input").value;
	if (h.toLowerCase().indexOf("<html")>=0){
		if (!this.initHTML) {
			this.initHTML = h;
		}
	} else {
		if (!this.initHTML) {
			this.initHTML = "<html><head></head>"+h+"</html>";
		}
	}

	this.HTMLTags=null;
	var pos = html.indexOf("<html");
	if (pos==-1) {
		pos=html.indexOf("<HTML");
	}
	if (pos>=0) {
		var pos1 = html.indexOf(">",pos+1);
		var pos2 = html.indexOf("</html",pos1+1);
		if (pos2==-1) {
			pos2 = html.indexOf("</HTML>",pos1+1);
		}
		if (pos2==-1) {
			pos2 = html.length;
		}
		this.HTMLTags = new Array();
		this.HTMLTags[0] = html;
		this.HTMLTags[1] = html.substring(0,pos1+1);
		this.HTMLTags[2] = html.substring(pos1+1,pos2);
		this.HTMLTags[3] = html.substring(pos2,html.length);
	}

	if (!this.HTMLTags) {
		this.HTMLTags = new Array();
		this.HTMLTags[0]=html;
		this.HTMLTags[1]="";
		this.HTMLTags[2]="";
		this.HTMLTags[3]="";

		var x = html.match(HtmlContents1);
		if (x) {
			this.HTMLTags[1]=x[1];
		}
		var y = html.match(HtmlContents2);
		if (y) {
			this.HTMLTags[3]=y[1];
		}
	}

	if ( html.toLowerCase().indexOf("<base")>=0){
		myBaseHref="base tag";
	}
		
	try{
		aed = this._frame;
		aed.open();
		var v = deEngine.preserve(embed2img(addWrap(html)));
		aed.write(v);
		aed.close();
	} catch(e){}
};


function setEvent () {
	if(E) {
		E.setEvent();
	}
}


Editor.prototype.setEvent = function()
{
	var wa = E._frame;
	try {
		wa.attachEvent( 'oncontrolselect', this.CheckEditable );	
	
		wa.attachEvent( 'onselectionchange', this.Redraw );
		wa.attachEvent( 'onkeydown', this.Redraw ) ;
		wa.attachEvent( 'onkeypress', this.KeyPress ) ;
		wa.attachEvent( 'onkeyup', this.CheckUp ) ;
		wa.attachEvent( 'onclick', this.Focus) ;
		wa.attachEvent( 'onmousedown', this.setdown ) ;
		wa.attachEvent( 'onmouseup', this.setup ) ;
		wa.attachEvent( 'oncontextmenu', this.ContextMenu) ;
		wa.body.attachEvent( 'ondragstart', this.CheckEditable) ;
		wa.body.attachEvent( 'ondrop', this.CheckEditable) ;
		
		element = parent.document.getElementById(E.InstanceName+"main");
		var el = element.parentNode ;
		while ( el ) {
			if (el.nodeName.toLowerCase() == "form") {
				break ;
			}
			el = el.parentNode ;
		}

		if (el){
			el.attachEvent( 'onsubmit', updateValue, true ) ;
		}		
				
		ToolbarSets.Redraw();
		if (is_setCursor==1){
			E.Focus(true, true);
		} else {
			updateValue();
		}
	} catch (e){}
};

function updateValue() {
	E.commands.getCommand('Save').execute("no");
	return true;
}

mouseisdown=false;

Editor.prototype.setdown = function()
{
	mouseisdown=true;
	return true;
};

Editor.prototype.setup = function(e)
{
	mouseisdown=false;
	if (e==null) {
		e = window.event;
	}
	if (!e) {
		return true;
	}

	if (e.srcElement && e.srcElement.tagName.toLowerCase()=="img" && e.srcElement.id ){
		if (e.srcElement.id.indexOf("de_add")>=0){
			addblock(e.srcElement, e);
			return false;
		} else if (e.srcElement.id.indexOf("de_del")>=0){
			delblock(e.srcElement, e);
			return false;
		}	
	}

	PopupSet.Redraw();
	ToolbarSets.Redraw(e);

	return true;
};

// create Editor object
Editor.prototype.Create = function()
{
	// instanceName should not be empty
	if ( !this.InstanceName || this.InstanceName.length == 0 ) {
		this.throwError( 1, 'You must specify a instance name.' ) ;
		return ;
	}

	// check browser compatible
	if (!this.isCompatibleBrowser()) {
		this.throwError( 2, 'Browser is not compatible.' ) ;
		return ;
	}

	// instert editor into page
	var htmlstr="";
	htmlstr+= '<div id="'+this.InstanceName+'main1" style="background-color:gray;height:100%;width:100%;">'
	htmlstr+= '<table  width="100%" height="100%" class="de_container" border="0px" cellspacing="0" cellpadding="0" ><tr><td>';
	htmlstr+= '<div id="eToolbar"></div></td></tr><tr><td width="100%" height="100%" class="editcontainer2">';
	htmlstr+= '<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0"><tr><td valign=top id="leftPanel"><img src="images/1x1.gif" width="1px">&nbsp</td><td valign=top width="100%" height="100%">';
	htmlstr+= '<iframe src="empty.html" onBlur="updateValue();" width="100%" height="100%" class="de_editcontainer" id="' + this.InstanceName + '_frame'+'"'+' onLoad="setEvent()" scrolling="yes" frameborder="no"></iframe>';
	htmlstr+= '<iframe src="empty.html" id="' + this.InstanceName + '_preview'+'" width="100%" height="100%" scrolling="yes"  frameborder="0" class="de_editcontainer" style="display:none;"></iframe>';
	htmlstr+= '</td><td valign=top width="1px"><img src="images/1x1.gif" width="1px">&nbsp;</td></tr></table>';
	htmlstr+= '</td></tr>';
	htmlstr+= '<tr><td><img id="'+this.InstanceName+'fb01" src="images/1x1.gif" height=5px></td></tr>';
	htmlstr+= '<tr '+(hideTagBar?'style="display:none;"':'')+'><td class="de_TB_Toolbar" class="pathtext"><table width="100%" border="0" cellspacing="0" cellpadding="0" ><tr><td><span id="ePath"></span></td><td><span style="float:right" class="de_pathcontainer" id="eRemoveTag"></span></td></tr></table></td></tr>';

	if (!(hideitems["EditMode"]==1 && hideitems["SourceMode"]==1 && hideitems["PreviewMode"]==1)){
		htmlstr+= '<tr><td class="de_TB_Toolbar"><table border="0" cellspacing="0" cellpadding="0" ><tr><td><div id="footersheet"></div></td>';
		htmlstr+= '<td width="100%" valign=top  class="de_TB_Toolbar"><table cellspacing="0" cellpadding="0" border="0" width="100%" ><tr><td width="100%"><div class="de_sheetline1" width="100%" height="1px"></div></td></tr>';
		htmlstr+= '<tr><td width="100%"><div class="de_sheetline2" width="100%" height="1px"></div></td></tr>';
		htmlstr+= '<tr><td align="right"><div id="eFooter"></div></td></tr>';
		htmlstr+= '</table></td></table>';
	}

	htmlstr+= '</td></tr></table>';
	htmlstr+= '</table>';
	
	htmlstr+= '</div>';

	document.write(htmlstr);
	aID = this.InstanceName + '_frame';
	window.aID = aID;
	this._frame=document.frames[aID].document;
	this._window=document.frames[aID];
	window.fr = this._frame;

	this._frame.designMode = "on";
	this._frame=document.frames[aID].document;

	if (new_layout){
		tlb_H = DevEditToolbarSets[toolbarmode].length * 27;
		dH = 51+tlb_H;
		if (hideTagBar)dH = dH - 23;
		H = parent.document.getElementById(this.InstanceName + 'main').style.pixelHeight;
		W = parent.document.getElementById(this.InstanceName + 'main').style.pixelWidth;
		document.getElementById(this.InstanceName + "_frame").height = H-dH;
	}
	
	this.checkLoad();
};

Editor.prototype.ContextMenu = function(evt)
{
	evt = (evt)?evt:(window.event)?window.event:"";

	if (hideitems["ContextMenu"]){
		evt.cancelBubble=true;
		evt.returnValue=false;
	} else {

		Selection.set();
				 
		if (!evt.ctrlKey) {
			if (!E.commands.getCommand("Contextmenu").isopening){
				E.commands.getCommand("Contextmenu").execute(null,evt.clientX,evt.clientY);
			}	
			evt.cancelBubble=true;
			evt.returnValue=false;
		}
		evt.cancelBubble=true;
		x = E._frame.fireEvent("onselectionchange");
		PopupSet.Redraw();
	}
};

Editor.prototype.checkEditArea = function()
{
	check = false;
	findAllEditable(this._frame.body);
	E.isbegin = check;
	E.isend = check;
};

Editor.prototype.lockPage = function()
{
	E.unlock = false;
	E.checkEditArea();
	E.Editable();
};

Editor.prototype.unlockPage = function()
{
	E.unlock = true;
	E.isbegin = false;
	E.isend = false;
};

var lastTag = null;
var must_edit = false;
Editor.prototype.Editable = function(e)
{
	var selectedRange = E._frame.selection.createRange();
	var sel = E._frame.selection;
	var range = sel.createRange();
	switch (sel.type) {
		case "Text":
			oContainer = range.parentElement();
			break;
		case "None":
			oContainer = range.parentElement();
			break;
		case "Control":
			oContainer = range.item(0);
			break;
		default:
			oContainer = aed.body;
			break;
	}

	if (e && e.type && e.type=="controlselect"){
		E.editable = true;
		lastTag = oContainer.tagName;
		must_edit = false;
		return true;
	}

	var isEditable = E.unlock || E.walkDOM(oContainer);

	if (insideWrap) {
		oContainer=wrap;
	}

	try{
		if (oContainer && oContainer.id && oContainer.id.substr(0,7)=="de_wrap") {
			var cR = E._frame.body.createTextRange();
			cR.moveToElementText(oContainer);

			// The current selection<br>
			var r = E._frame.selection.createRange();
			// We'll use this as a 'dummy'<br>
			var stored_range = r.duplicate();
			// Select all text<br>
			stored_range.moveToElementText( oContainer );
			// Now move 'dummy' end point to end point of original range<br>
			stored_range.setEndPoint( 'EndToEnd', range );
			// Now we can calculate start and end points<br>
			sStart = stored_range.text.length - range.text.length;
			sEnd = sStart + range.text.length;

			//isEditable = true;
			if ( (sStart==0 && e.keyCode==8 ) // start & backspace
				|| ((cR.text.length == sStart ) && (e.keyCode==46 || selectedRange.text ))//end & del
			) {
				isEditable=false;
			}
		}
	} catch(x){}

	if (e){
		if (E.walkDOM && isEditable) {
			E.editable = true;
			return true;
		} else {
			e.cancelBubble=true;
			e.returnValue=false;
			E.editable = false;
			return false;
		}

	} else {
		if (E.walkDOM && isEditable) {
			return true;
		} else {
			return false;
		}
	}
};

Editor.prototype.CheckEditable = function(evt)
{
	e = (evt)?evt:(window.event)?window.event:"";
	
	if (e.srcElement && e.srcElement.tagName.toLowerCase()=="img" && e.srcElement.id ){	
		if (e.srcElement.id.indexOf("de_add")>=0 || e.srcElement.id.indexOf("de_del")>=0){
			return false;
		}	
	}


	// 1 click for DIV	
	if (e.srcElement && e.srcElement.tagName && (e.srcElement.tagName.toLowerCase() == "div" || e.srcElement.tagName.toLowerCase() == "span")){
		one_click = true;
	}
	// end 1 click
		
	return E.Editable(e);
};

var edt = false;
var lastedt = false;
one_click = false;
			
// source engine var's
context_str="";
tag_typing = false;
startBookmark = null;

// redraw
Editor.prototype.Redraw = function(e)
{
	Selection.set();
	loading_in_progress = false;
	var cShow = false;

	if(!e) {
		e = window.event;
	}
	if (!e) {
		return true;
	}

	var x = e.clientX;
	var y = e.clientY;

	lastedt = edt;
	edt = E.Editable(e);
	
	var se = E.GetSelectedElement();
	if (!edt && !(se && se.tagName && (se.tagName.toLowerCase()=="div" || se.tagName.toLowerCase()=="span"))) {
		PopupSet.Redraw();
		ToolbarSets.Redraw(e);
		return false;
	}

	// 1 click for DIV
	if (se && se.tagName && (se.tagName.toLowerCase()=="div" || se.tagName.toLowerCase()=="span")){
		try {
			var range = E._frame.body.createTextRange();
			range.moveToPoint(x,y);
			range.collapse();
			range.select();
			one_click = true;
		}catch(x){}	
	}
	// end 1 click

	try{
	// check spell words
	var se = E.GetSelectedElement();
	if (!se&&E.GetSelection()=="") {
		se = E.firstParentNode();
	}
	if (se && se.id && se.id.substring(0,9) =="_de_spell") {

		// select word

		var sel = E._frame.selection;
		var newNode = sel.createRange();
		newNode.moveToElementText(se);
		newNode.select();

		var dg = se.id.match(/(\d+)/g);
		PopupSet.Items["spellmenu"].ReplaceItems(dg[0]*1.0);

		E.commands.getCommand("Spellmenu").execute(null,e.clientX,e.clientY);
		PopupSet.Redraw("spellmenu");
		e.cancelBubble = true;
		e.returnValue = false;
		return;
	}
	// end check spell
	cancel_event = false;

	if (!e.ctrlKey && e.type=="keydown" && E.mode=="edit"){
		var specialchar = false;
		for (var i=0;i<specialKeyCode.length;i++){
			if (specialKeyCode[i]==e.keyCode){
				specialchar=true;
				break;
			}
		}
		
		;
					
		if ((e.keyCode && (e.keyCode>32 && e.keyCode<256) && !e.ctrlKey && !sKey[e.keyCode])
		&& (buffer[E.mode].devhistory.position==0 || E._frame.selection.createRange().parentElement()!==this.lastContainer || ( E._frame.selection.createRange().htmlText.length!==this.lastOffset && E._frame.selection.createRange().htmlText.length!==this.lastOffset+1))){
			buffer[E.mode].saveHistory();
			PopupSet.Redraw();
			ToolbarSets.Redraw(e);		
			return true;
		} 
		if (!specialchar) {
			return E.editable;
		}
	}

	var f=false;
	var shortcut=false;
	if (E.toolbaritems.GetItem('Path')) {
		E.toolbaritems.GetItem('Path').Redraw();
	}
	if (e.ctrlKey) {
		var key = String.fromCharCode(e.keyCode).toUpperCase();
		if (key=="X" || key=="V"){
			buffer[E.mode].saveHistory();
		}
		for(var i=0; i < ShortCutKeys.length; i++){
			var k = ShortCutKeys[i][0];
			if (k.toUpperCase().indexOf("SHIFT")>=0 && e.shiftKey && key==k.substr(k.indexOf("+")+1,1)) {
				f=true;
			} else if (key == k) {
				f=true;
			}

			if (f) {
				if (E.commands.getCommand(ShortCutKeys[i][1]).getMode()==OFF){
					E.commands.getCommand(ShortCutKeys[i][1]).execute();
					shortcut = true;
					cShow = true;
				}	
				f = false;				
			}
		}
		PopupSet.Redraw();
		ToolbarSets.Redraw(e);
	}

	E.lastinsert = null;


	// Enter - if useBR=1 then use <br/>
if(E.parentNode("LI") == null) {

	if (e.keyCode==13 && cancel_event==false) {
		if (useBR==1) {
			var sel = E._frame.selection;
			if (sel.type == "Control") {
				return;
			}

			if (!e.shiftKey) {
				buffer[E.mode].saveHistory(e.keyCode);
				var r = sel.createRange();
				r.pasteHTML("<BR/>");
				e.cancelBubble = true;
				e.returnValue = false;

				r.select();
				r.moveEnd("character", 1);
				r.moveStart("character", 1);
				r.collapse(false);
				return false;
			} else {
				
				if (E.parentNode("LI")) {
					//
				} else {
					buffer[E.mode].saveHistory(e.keyCode);	
					var r = sel.createRange();
					if (r.parentElement() && r.parentElement().tagName && r.parentElement().tagName.toLowerCase()=="p"){
						p = r.parentElement();
						x = p.nextSibling;

						var range = E._frame.body.createTextRange();
						range.moveToElementText(p);
						ft = range.htmlText;
						range.setEndPoint("EndToStart", r);
						var rangeLength = range.htmlText.length;

						ft = ft.replace(/^[\r\n\f]+?<([^>]+)>/gi,"");
						ft = ft.replace(/<([^>]+)>$/gi,"");
						ft1 = ft.substr(0,rangeLength);						
						ft1 = ft1.replace(/^[\r\n\f]+?<([^>]+)>/gi,"");
						ft1 = ft1.replace(/<([^>]+)>$/gi,"");
						
						var newP = E._frame.createElement("P");
						newP.innerHTML = ft1;
						var newP2 = E._frame.createElement("P");
						newP2.innerHTML = ft.substr(rangeLength,ft.length-rangeLength);

						if (x){
							p.parentNode.insertBefore(newP2,x);
						} else {

							if (newP2.innerHTML){
								p.parentNode.appendChild(newP2);
								newP2.id="temp_p";
								p.parentNode.appendChild(newP2);
								var x = E._frame.getElementById("temp_p");
								r.moveToElementText(x);
								x.removeAttribute("id");
								r.collapse(true);
								r.select();
							} else {
								newP2.innerHTML="&nbsp;";
								newP2.id="temp_p";
								p.parentNode.appendChild(newP2);
								var x = E._frame.getElementById("temp_p");
								r.moveToElementText(x);
								x.removeAttribute("id");
								r.select();
							}	

						}	
						p.parentNode.replaceChild(newP,p);
						return false;
						
					} else {
						if (r.parentElement() && r.parentElement().tagName){
							buffer[E.mode].saveHistory(e.keyCode);
							var range = E._frame.body.createTextRange();
							range.moveToElementText(r.parentElement());
							r.setEndPoint("EndToEnd", range);
							
							if (r.htmlText){
								if (r.parentElement().tagName.toLowerCase()!=="body"){
									r.moveEnd("character",-1);
								}	
								r.select();

								r.pasteHTML("<p id='temp_p'>"+r.htmlText+"</p>");
								var p = E._frame.getElementById("temp_p");
								r.moveToElementText(p);
								p.removeAttribute("id");
								r.collapse(true);
								r.select();
							} else {
								r.select();
								r.pasteHTML("<p id='temp_p'>&nbsp;"+r.htmlText+"</p>");
								var p = E._frame.getElementById("temp_p");
								r.moveToElementText(p);
								p.removeAttribute("id");
								r.select();
							}

							e.cancelBubble = true;
							e.returnValue = false;

							return false;

						}
					}
				}	
			}	
		} else {
		
			var sel = E._frame.selection;
			if (!e.shiftKey && E.parentNode("DIV")) {
				buffer[E.mode].saveHistory(e.keyCode);
				var r = sel.createRange();
				r.pasteHTML("<p>");
				e.cancelBubble = true;
				e.returnValue = false;

				r.collapse(true);
				r.select();
				return false;
			}
		
			if (E.mode=="source") {
				buffer[E.mode].saveHistory();
				var r = sel.createRange();
				r.pasteHTML("<BR>");
				e.cancelBubble = true;
				e.returnValue = false;

				r.select();
				r.moveEnd("character", 1);
				r.moveStart("character", 1);
				r.collapse(false);

				return false;
			}
		}
		cShow = true;
	}
}

	if (!e.ctrlKey && (e.keyCode==0 && !mouseisdown && e.type!="keydown"&& e.type!="selectionchange")) {
		PopupSet.Redraw();
		ToolbarSets.Redraw(e);
		cShow = true;
	}
	if (shortcut || cancel_event) {
		e.cancelBubble = true;
		e.returnValue = false;
	}

	if (cShow){		
		setTimeout("E.commands.getCommand('Paragraph').showChar()",20);
	}

	if(!e.ctrlKey && e.keyCode != 90 && e.keyCode != 89) {
		if (e.keyCode == 32 || e.keyCode == 13 || e.keyCode == 46) {
			buffer[E.mode].saveHistory(e.keyCode);
		}
	}
			
	e.cancelBubble=true;
	} catch(e){}
	E.commands.getCommand('Paragraph').showChar();
	return true;
};

Editor.prototype.KeyPress = function(e)
{
	try{
		this.lastContainer = E._frame.selection.createRange().parentElement();
		this.lastOffset = E._frame.selection.createRange().htmlText.length;
	} catch(e){}	
}

Editor.prototype.CheckUp = function(e)
{
	e = (e)?e:(window.event)?window.event:"";

	if (e.keyCode >= 37 && e.keyCode <= 40) {
		PopupSet.Redraw();
		ToolbarSets.Redraw(e);
	}
	if (e.shiftKey && (e.keyCode==35 || e.keyCode==36)) {
		PopupSet.Redraw();
		ToolbarSets.Redraw(e);
	}
		
	if (edt && !lastedt){
		PopupSet.Redraw();
		ToolbarSets.Redraw(e);
	}
	if(!e.ctrlKey && e.keyCode != 90 && e.keyCode != 89) {
		if (e.keyCode == 32 || e.keyCode == 13 || e.keyCode == 46) {
			PopupSet.Redraw();
			ToolbarSets.Redraw(e);
		}
	}

	return true;
};

one_onclick = true;
Editor.prototype.Focus = function(set_focus,loaded_page){
	try{
		if (!one_onclick || set_focus==true)E._window.focus();
	} catch ( e ) {}
};

// this method check browser compatible
Editor.prototype.isCompatibleBrowser = function()
{
	if (browser.IE) {
		var sBrowserVersion = navigator.appVersion.match(/MSIE (.\..)/)[1] ;
		return ( sBrowserVersion >= 5.5 ) ;
	} else {
		return false ;
	}
};

// show errors
Editor.prototype.throwError = function( errorNumber, errorDescription )
{
	this.ErrorNumber		= errorNumber ;
	this.ErrorDescription	= errorDescription ;

	document.write( '<div style="COLOR: #ff0000">' ) ;
	document.write( '[ Editor Error ' + this.ErrorNumber + ': ' + this.ErrorDescription + ' ]' ) ;
	document.write( '</div>' ) ;
};

Editor.prototype.clearAll = function()
{
	var text = (browser.NS?'&nbsp;':'');
	this._frame.body.innerHTML = text;
};

Editor.prototype.clearContent = function()
{
	var text = (browser.NS?'&nbsp;':'');
	this._frame.body.innerHTML = text;
};

Editor.prototype._inserthtml = function(text, ignore)
{
	this.Focus(true);
	buffer[this.mode].saveHistory();
	var newNode = insertNodeAtSelectionIE(embed2img(addWrap(text,1),true),ignore);
	PopupSet.Redraw();
	ToolbarSets.Redraw();
	return newNode;
};

Editor.prototype._insertBefore = function(text)
{
	this.Focus(true);
	buffer[this.mode].saveHistory();
		
	var aed = E._frame;
	var sel = E._frame.selection;
	var newNode = sel.createRange();
	
	var range = aed.body.createTextRange();

	newNode.moveStart("character",-1);
	l = 100;
	while (newNode.text.substr(0,1)!=="<" && l){
		newNode.moveStart("character",-1);
		l--;
	}
	newNode.select();

	addText = newNode.text.replace(/([\/\n\f\r])/gi,"");
	addText = addText.replace(/</gi,"&lt;");
	addText = addText.replace(/>/gi,"&gt;");
			
	text = addText+text;
	
	if ( sel.type.toLowerCase() !== "none" ) {
		sel.clear() ;
	}

	text = colourCode(text);
	newNode.pasteHTML (text);
	newNode.moveEnd("character",-1);
	var x = newNode.parentElement();
	newNode.moveToElementText(x);
	newNode.collapse();
	newNode.select();

	PopupSet.Redraw();
	ToolbarSets.Redraw();
	return newNode;
};

Editor.prototype.insertHTML = function(text)
{
	E.Focus(true);
	buffer[this.mode].saveHistory();
	var newNode = insertNodeAtSelectionIE(embed2img(addWrap(text,1)),1,true);
	PopupSet.Redraw();
	ToolbarSets.Redraw();
	E.commands.getCommand('Save').execute("no");
	return newNode;
};

Editor.prototype.parentNode = function( nodeTagName )
{
	return this.parentNodeIE(nodeTagName);
};

Editor.prototype.GetType = function()
{
	return this.GetTypeIE();
};


Editor.prototype.getSelectedElement = function()
{
	return this.GetSelectedElementIE();
};

Editor.prototype.GetSelectedElement = function()
{
	return this.GetSelectedElementIE();
};

Editor.prototype.GetTypeIE = function()
{
	return this._frame.selection.type ;
};

Editor.prototype.GetSelectedElement2 = function() {
	sel = this._frame.selection;
	range = sel.createRange();
	var oContainer=null;
	switch (sel.type) {
		case "Text":
			oContainer = range.parentElement();
			break;
		case "None":
			oContainer = range.parentElement();
			break;
		case "Control":
			oContainer = range.item(0);
			break;
		}
		if (oContainer==this._frame.body) {
			oContainer=null;
		}
	return oContainer;
};

Editor.prototype.GetSelectedElementIE = function()
{
	if ( this.GetTypeIE() == 'Control' ) {
		var oRange = this._frame.selection.createRange() ;
		if ( oRange && oRange.item ) {
			return this._frame.selection.createRange().item(0) ;
		}
	}
};

Editor.prototype.parentNodeIE = function( nodeTagName )
{
	var oNode ;
	if ( E._frame.selection.type == "Control" ) {
		var oRange = E._frame.selection.createRange() ;
		for ( i = 0 ; i < oRange.length ; i++ ) {
			if (oRange(i).parentNode) {
				oNode = oRange(i).parentNode ;
				break ;
			}
		}
	} else {
		var oRange  = E._frame.selection.createRange() ;
		oNode = oRange.parentElement() ;
	}

	while ( oNode && oNode.nodeName != nodeTagName ) {
		oNode = oNode.parentNode ;
	}

	return oNode ;
};

Editor.prototype.firstParentNode = function()
{
	var oNode ;
	try {
		if ( E._frame.selection.type == "Control" ) {
			var oRange = E._frame.selection.createRange() ;
			for ( i = 0 ; i < oRange.length ; i++ ) {
				if (oRange(i).parentNode) {
					oNode = oRange(i).parentNode ;
					break ;
				}
			}
		} else {
			var oRange  = E._frame.selection.createRange() ;
			oNode = oRange.parentElement() ;
		}
	} catch (e) {}

	return oNode ;
};

Editor.prototype.selectNode = function(node){
	try	{
		var range = this._frame.body.createControlRange();
		range.add(node);
		range.select();
	} catch(e) {
		try	{
			var range = this._frame.body.createTextRange();
			range.moveToElementText(node);
			range.select();
		} catch(e){return;}
	}
};

function insertNodeAtSelectionIE (text, i, specialtag, before) {

	var aed = E._frame;
	var sel = E._frame.selection;
	if (sel!=null) {
		if ( sel.type.toLowerCase() != "none" ) {
			sel.clear() ;
		}
		var newNode = sel.createRange();

		var bm = null;
		var bm2 = null;
		var ti = false;
		
		if (specialtag && text.substr(0,1)=="<"){
			ti=true;
			text = "x"+text;
			bm = newNode.duplicate();
		}
		
		if (before){
			bm = newNode.duplicate();
		}

		newNode.pasteHTML (text);

		if (ti){
			bm2 = newNode.duplicate();
		}

		
		if (bm){
			newNode.setEndPoint("StartToStart",bm);
			newNode.select();
			newNode.collapse();
			newNode.select();
		}

		if (ti){
			newNode.moveEnd("character",1);
			newNode.text="";
			newNode.setEndPoint("StartToEnd",bm2);
			newNode.collapse(false);
			newNode.select();
		}

		var node = aed.getElementById("_de_temp_element");
		if (node){
			newNode.moveToElementText(node);
			newNode.select();
			node.removeAttribute("id");
			E.currentNode = node;
			this.selectedtag = node;
			E.lastinsert = node;
		}
		return newNode;
	}
}

Editor.prototype.GetSelection = function()
{
	return this.GetSelectionIE();
};

Editor.prototype.getSelectedText = function()
{
	return this.GetSelectionIE();
};

Editor.prototype.GetSelectionIE = function()
{
	var oRange = this._frame.selection.createRange();
	return oRange.text;
};

Editor.prototype.replaceNodeByText = function(obj)
{
	obj.outerHTML = obj.innerText;
};

Editor.prototype.doStyles = function()
{
	this.Styles			= new Array();
	this.cssText		= new Array();
	var sheets = this._frame.styleSheets;
	var through = false;
	if (sheets.length > 0) {
		// loop over each sheet
		for (var x = 0; x < sheets.length; x++) {
			if (sheets[x] && sheets[x].href && sheets[x].href.indexOf(border_css_file)>=0) {
				continue;
			}
			// grab stylesheet rules
			try
			{
				var rules = sheets[x].rules;
			}
			catch (err)
			{
				through = true;
			}
			if (through)
			{
				this.Styles = cached_Styles;
				this.cssText = cached_cssText;
				return true;
			}
			if (rules.length > 0) {
				cssloaded = 1;
				// check each rule
				for(var y = 0; y < rules.length; y++) {
					sT = rules[y].selectorText;
					if (sT.substr(0,1)=="."){
						sT=sT.substr(1);
						if (sT.indexOf(" ")>0) {
							sT = sT.substring(0,sT.indexOf(" "));
						}
						if (sT.toLowerCase().indexOf("wep_")==0 || sT.toLowerCase().indexOf("de_")==0)continue;
						style_twice = false;
						for (var z = 0; z < this.Styles.length; z++){
							if (sT == this.Styles[z]){
								this.cssText[z] = this.cssText[z] + (this.cssText[z].lastIndexOf(";")==this.cssText[z].length ? "" : ";") +rules[y].style.cssText;
								style_twice = true;
								break;
							}
						}
						if (!style_twice){
							this.Styles[this.Styles.length] = sT;
							this.cssText[this.cssText.length] = rules[y].style.cssText;
						}
					}
				}
				cached_Styles = this.Styles;
				cached_cssText = this.cssText;
			}
		}
	}
};

Editor.prototype.isFlash = function(x)
{
	var cn;
	cn = x.getAttribute("className");
	if (cn=="de_flash_file") {
		return true;
	} else {
		return false;
	}
};

Editor.prototype.isMedia = function(x)
{
	var cn;
	cn = x.getAttribute("className");
	if (cn=="de_media_file") {
		return true;
	} else {
		return false;
	}
};

Editor.prototype.checkTags = function(tags)
{
	var tags = tags.split("|");
	var r;
	for (var i=0; i<tags.length; i++) {
		switch (tags[i]) {
			case "empty":
				if (this.GetSelection()=="") {
					r = OFF;
				} else {
					r = DISABLED;
				}
				break;
			case "text":
				if (this.GetType()=="Control") {
					r = DISABLED;
				} else {
					if (this.GetSelection()!="") {
						r = OFF;
					} else {
						r = DISABLED;
					}
				}
				break;
			case "flash":
				var x = E.GetSelectedElement();
				if (x && this.isFlash(x)) {
					return OFF;
				} else {
					return DISABLED;
				}
				break;
			case "media":
				var x = E.GetSelectedElement();
				if (x && this.isMedia(x)) {
					return OFF;
				} else {
					return DISABLED;
				}
				break;
			case "link":
				if (E.GetType()=="Control") {
					var  x = Selection.parentNode("A");
					if (!x) {
						return DISABLED;
					}
					if (x.href) {
						if (x.href.indexOf("mailto:")==-1) {
							return OFF;
						} else {
							return DISABLED;
						}
					}
				} else if (E.GetSelection()!=""){
					var x = E.GetSelectedElement();
					if (!x) {
						return OFF;
					}
					if (x.href&&x.href.indexOf("mailto:")==-1) {
						return OFF;
					}
				}
				break;
			default:
				var x = this.GetSelectedElement();
				if (!x) {
					x = Selection.parentNode(tags[i]);
				}
				if (!x) {
					return DISABLED;
				}
				if (x && x.getAttribute("className") && (x.getAttribute("className")=="de_flash_file" || x.getAttribute("className")=="de_media_file")) {
					return DISABLED;
				}
				if (x && x.tagName && tags[i].toUpperCase()==x.tagName.toUpperCase()) {
					r=OFF;
				} else {
					r = DISABLED;
				}
		}
		if (r==OFF) {
			return OFF;
		}
	}
	return DISABLED;
};


Editor.prototype.storePos = function()
{
	this.Pos = null;
	var selection = this._frame.selection;
	if (selection.type.toLowerCase() == 'text'||selection.type.toLowerCase() == 'none') {
		this.Pos =  selection.createRange().getBookmark();
	}
};

Editor.prototype.restorePos = function()
{
	if (this.Pos){
		var range = this._frame.body.createTextRange();
		range.moveToBookmark(this.Pos);
		range.select();
	}
};

var checkbegin	= false;
var checkend	= false;
var traverse_end = false;
var check		= false;
var ret			= false;
var insideWrap	= false;
var wrap = null;

Editor.prototype.walkDOM = function(node)
{
	if (!node) {
		return false;
	}
	traverse_end = false;
	checkbegin	= false;
	checkend	= false;
	ret			= false;
	insideWrap	= false;
	wrap = null;
	if (E.unlock) {
		return true;
	}
	if (!E.isbegin && !E.isend) {
		return true;
	}
	traverse(this._frame.body,node);
	if (checkbegin && !checkend ) {
		return true;
	} else {
		return false;
	}
};

function traverse(node,selnode)
{
	if (ret) {
		return;
	}
	if (node && node.id && node.id.substr(0,7)=="de_wrap") {
		insideWrap=true;
		wrap=node;
	}
	if (node.nodeType == 8) {
		if (node.nodeValue.toLowerCase().indexOf("begineditable")>=0) {
			checkend = false;
			checkbegin = true;
		}
		if (node.nodeValue.toLowerCase().indexOf("endeditable")>=0) {
			checkend = true;
		}
		if (checkbegin && checkend) {
			check=true;
			checkend=false;
			checkbegin=false;
		}
	}
	if (node==selnode){
		ret=true;
		return;
	}
	if (node.childNodes != null) {
		for ( var i=0; i < node.childNodes.length; i++) {
			traverse(node.childNodes.item(i),selnode);
		}
	}
}

function findAllEditable(node)
{
	if (check==true) {
		return;
	}
	if (node.nodeType == 8) {
		if (node.nodeValue.toLowerCase().indexOf("begineditable")>=0) {
			checkend = false;
			checkbegin = true;
		}
		if (node.nodeValue.toLowerCase().indexOf("endeditable")>=0) {
			checkend = true;
		}
		if (checkbegin && checkend) {
			check=true;
			checkend=false;
			checkbegin=false;
		}
	}
	if (node.childNodes != null) {
		for ( var i=0; i < node.childNodes.length; i++) {
			findAllEditable(node.childNodes.item(i));
		}
	}
}

// function below fix IE bug

// convert attribute name inside style defenition to lower case.
function convert_attr_name_to_lowercase(code)
{
	r = /(style=["'][^"']*?["'])/gi;
	return code.replace(r,function(s1,s2){
							s2 = s2.replace(/;?([^:]*)/g,function(d1,d2){return d2.toLowerCase();}
						);
			return s2;
	});
}

// move cursor left by 1 character right and then left
function move_cursor_right_and_left() // fix_bug_02
{
	try{
		var sel = E._frame.selection;
		var r = sel.createRange();
		r.moveEnd("character", 1);
		r.moveStart("character", 1);
		r.collapse(false);
		r.select();
		window.setTimeout("move_cursor_right_to_100_and_back()",10);
	} catch(e){}
}

// move cursor right to 100 character and than back
function move_cursor_right_to_100_and_back() 
{
	try{
		var sel = E._frame.selection;
		var r = sel.createRange();
		r.moveEnd("character", -100);
		r.moveStart("character", -100);
		r.collapse(false);
		r.select();
	} catch(e){}
}

// convert align="center" to  align="middle"
function convert_align_middle_to_center(code)
{
	r = /(\salign="middle")/gi;
	r1 = /(\salign=middle)/gi;
	code = code.replace(r," align=\"center\"");
	code = code.replace(r1," align=center");
	return code;
}

// browser strip <param> tag from OBJECT TAG
function convert_param_tag_to_xhtml(code)
{
	if (useXHTML != 1) {
		// Make param tags <param .... ></param>
		r = /(<param(.*?)>)[^<\/param>]/gi;
		code = code.replace(r, "$1</PARAM>");
	}

	return code;
}

function set_cursor_at_begin()
{
	try{
	var sel = E._frame.selection;
		var r = sel.createRange();
		r.moveToElementText(E._frame.body);
		r.select();
		r.collapse(true);
		r.select();

	} catch(e){}
}

