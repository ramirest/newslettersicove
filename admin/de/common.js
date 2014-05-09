// common classes for IE and Gecko

// spell words
var _de_spell_words = "1";

// Garbage Collector
var Garbage = function()
{
	this.id=0;
	this.cnt=0;
	this.Items = new Array();
};

Garbage.prototype.add = function(g)	// add item to garbage collection
{
	this.Items[this.cnt] = g;
	this.cnt++;
}

Garbage.prototype.generateId = function()	// generate new ID for item
{
	this.id++;
	return "gc"+this.id;
}

Garbage.prototype.clearMemory = function()	// remove all items from collection - clear memory
{
	var iter = this.cnt;
	do {
		x = document.getElementById(this.Items[iter-1]);
		if (x){
			if (x.ToolbarButton) {
				x.ToolbarButton = null;
			}
			if (x.Combo) {
				x.Combo = null;
			}
			if (x.Tab) {
				x.Tab = null;
			}
		}
	} while (--iter);
	for (i in PopupSet.Items) {
		if (i=="clone") {
			continue;
		}
		for (var j = 0 ; j < PopupSet.Items[i].Items.length ; j++) {
			if (PopupSet.Items[i].Items[j].DOMDiv) {
				PopupSet.Items[i].Items[j].DOMDiv.PopupItem = null;
			}
		}
	}
}

var browser = new Browser();

// Browser class (browser ver., platform, etc)

function Browser()
{
	this.IE    = false;
	this.NS    = false;
	this.isFireFox1_5 = false;
	this.Opera = false;
	this.Safari = false;
	this.Mac = false;

	var userAgent = navigator.userAgent;
	if ((userAgent.indexOf("Opera")) >= 0) {
		this.Opera = true;
		return;
	}
	if ((userAgent.indexOf("Safari")) >= 0) {
		this.Safari = true;
		return;
	}

	if ((userAgent.indexOf("MSIE")) >= 0) {
		this.IE = true;
		return;
	}

	if ((userAgent.indexOf("Netscape6/")) >= 0) {
		this.NS = true;
		return;
	}
	if ((userAgent.indexOf("Gecko")) >= 0) {
		if (navigator.productSub>=20051111) {
			this.isFireFox1_5 = true;
		}
		this.NS = true;
		return;
	}

	if ((userAgent.indexOf("Mac")) >= 0) {
		this.Mac = true;
		return;
	}
}

// It is pre-loader image class
// this class story array of preloaded images
var ImageLoader = function()
{
	this.Name = "imageloader";
	this.img = new Array();
};

ImageLoader.prototype.addImage = function(name,src)
{
	if (!this.img[name]) {
		this.img[name] = new Image();
		this.img[name].src = src;
	}
};

ImageLoader.prototype.getImage = function(name)
{
	if (this.img[name]) {
		return this.img[name];
	} else {
		return false;
	}
};

// Name: Path class
// Info: Show tags tree and do selection for each tag.

var pathtags = new Array();

var Path = function(){
	this.Name = "Path";
};

Path.prototype.CreateInstance = function(parentToolbar)
{
	this.parenttoolbar = parentToolbar ;
	this.cDiv = document.createElement( 'div' ) ;
	this.cDiv.className = "de_pathcontainer";
	var oCell = parentToolbar.DOMRow.insertCell(parentToolbar.DOMRow.cells.length) ;
	oCell.appendChild( this.cDiv ) ;

	var rDiv = document.createElement('div');
	document.getElementById("eRemoveTag").appendChild(rDiv);

	this.rDiv = rDiv;
	this.currentNode = null;
}

Path.prototype.Redraw = function(e)
{
	if (this.cDiv.childNodes){
		var ln = this.cDiv.childNodes.length;
		for (var i=0; i<ln;i++) {
			var a = this.cDiv.childNodes[i];
			try{
				a.cDiv= null;
				a.rDiv = null;
			} catch(e){}
		}
	}

	var pathstr="&nbsp;&nbsp;HTML Tag:";
	var oNode ;
	var pathtree = new Array();
	pathtags = new Array();
	aed = E._frame;

	Selection.set();
	var tag_count=0;
	var i = Selection.parentTags.length;

	while (i--) {
		oContainer = Selection.parentTags[i];

		if (browser.IE) {
			var cn = oContainer.getAttribute("className");
		} else {
			var cn = oContainer.getAttribute("class");
		}

		var label;
		if (cn && (cn=="de_media_file"|| cn=="de_flash_file") ) {
			label="embed";
		} else {
			label=oContainer.tagName;
		}
		if (!label) {
			break;
		}
		var cl = "de_linklabel";
		pathtree.unshift('<A class="'+cl+'" title="'+label+'" href="javascript:void(0)">'+label.toUpperCase()+'</A>');
		pathtags.unshift(oContainer);
		tag_count+=1;
		oContainer = oContainer.parentNode ;
		if (label.toLowerCase()=="body"||tag_count>=_de_max_tag) {
			break;
		}
	}

	if (pathtree.length>=_de_max_tag) {
		pathstr+= "&nbsp;...&nbsp;";
	}
	var map= new Array();
	var j=0;
	for (var i=0; i<pathtree.length;i++) {
		if (browser.IE) {
			var cn = pathtags[i].getAttribute("className");
		} else {
			var cn = pathtags[i].getAttribute("class");
		}

		if (pathtags[i].getAttribute("id") && pathtags[i].getAttribute("id").indexOf("de_wrap_div")>=0) {
			continue;
		}
		if (cn=="de_style_anchor" || cn=="de_style_input" ) {
			continue;
		}
		pathstr+="<"+pathtree[i];
		if ( cn && cn!="de_media_file" && cn!="de_flash_file") {
			pathstr+="."+cn;
		}
		pathstr+="> ";
		map[j]=i;
		j++;
	}
	E.selectedtag = pathtags[pathtree.length-1];
	this.cDiv.innerHTML = pathstr;

	j=0;
	var ln = this.cDiv.childNodes.length;
	for (var i=0; i<ln;i++){
		atag = this.cDiv.childNodes[i];
		if (atag.tagName&&atag.tagName.toLowerCase()=="a") {
			atag.name = map[j];
			atag.cDiv= this.cDiv;
			atag.rDiv = this.rDiv;
			if (browser.IE){
				atag.attachEvent( 'onclick', this.selectNodeContents);
			} else {
				atag.addEventListener( 'click', this.selectNodeContents , false) ;
			}
			j++;
		}
	}
	this.rDiv.innerHTML="";
}

// Selects the contents inside the given node
Path.prototype.selectNodeContents = function()
{
	var a,rDiv,node,cDiv;
	if (browser.IE){
		a = window.event.srcElement;
	} else {
		a = this;
	}
	node = pathtags[a.name];
	rDiv = a.rDiv;
	cDiv = a.cDiv;

	E.selectedtag = node;
	var aed = E._frame;
	var aew = E._window;
	E.Focus();
	var range;
	var collapsed = (typeof pos != "undefined");
	if (browser.IE) {
		E.lastinsert = node;
		var range = aed.body.createControlRange();
		try	{
			range.add(node);
			range.select();
		} catch(e) {
			try	{
				var range = aed.body.createTextRange();
				range.moveToElementText(node);
			} catch(e){return;}
		}

		range.select();

	} else {
		if (node.childNodes && node.childNodes[0] && node.childNodes[0].nodeValue.toLowerCase().indexOf("begineditable")>0){
			node = node.childNodes[1];
			if (node.childNodes) {
				node = node.childNodes[0];
			}
			editable_area_selected = true;
		}
		var sel = aew.getSelection();
		range = aed.createRange();
		range.selectNode(node);
		sel.removeAllRanges();
		sel.addRange(range);
	}

	for (var i=0; i<cDiv.childNodes.length;i++){
		atag = cDiv.childNodes[i];
		if (atag.tagName&&atag.tagName.toLowerCase()=="a"&&atag.name==a.name) {
			atag.className = "linklabelselected";
		}
	}

	if (node!==aed.body){
		rDiv.innerHTML='<A id="_de_remove_tag" class="de_linklabel" href="javascript:void(0)">Remove&nbsp;Tag</A>&nbsp;&nbsp;|&nbsp;&nbsp;'+
						'<A id="_de_edit_tag" class="de_linklabel" href="javascript:void(0)">Edit&nbsp;Tag&nbsp;&nbsp;</A>';
		var btag = document.getElementById("_de_edit_tag");
		var atag = document.getElementById("_de_remove_tag");
		var nodeclass=node.className;
		if (nodeclass !=="de_style_anchor" && nodeclass !== "de_style_input"){
			btag.name2 = node;
			btag.onclick = BTagOnClick;
		} else {
			btag.onclick = NullFunc;
		}
		atag.name2 = node;
		atag.onclick = ATagOnClick;
	}

	ToolbarSets.Redraw("path");
	E.Editable();
	return true;
};

NullFunc = function(){};

BTagOnClick = function()
{
	if (!E.editable) {
		return;
	}
	var node = this.name2;
	E.selectedtag = node;
	E.commands.getCommand('Edittag').execute();
};

ATagOnClick = function()
{
	E.Editable();
	if (!E.editable) {
		return;
	}
	var node = this.name2;
	if (node&&node.parentNode){
		if (E.GetSelection() && (!node.getAttribute("name2"))){
			var newnode = aed.createTextNode(E.GetSelection());
			try{
				node.parentNode.replaceChild(newnode,node);
				ToolbarSets.Redraw();
			} catch(e){}
		}else{
			try{
				node.parentNode.removeChild(node);
				ToolbarSets.Redraw();
			} catch(e){}
		}
	}
};

// Remove Tag
Path.prototype.removeTag = function()
{
	this.node.parentNode.removeChild(this.node);
};

_ie_popup_class = new Array();
_ie_popup_class_prop = new Array();
render_css(parent.iepopupstyle);

// convert css class to inner style, like this style="... "
function render_css(data1){
	var news;
	firstclasspos = 0;
	
	data1.replace(/(\.(\w+)\s?\{([^\}]+)\})/g,function(s1,s2,s3,s4){
		_ie_popup_class[s3] = s4;
		_ie_popup_class_prop[s3] = new Array();

		s = s4.replace(/(([^:]+):([^;]+);)/gi,function(a1,ax,a2,ay){
			a2=a2+"";
			a2 = a2.toLowerCase();
			a2 = a2.replace(/(-.)/g,function(b1,b2){return b2.toUpperCase().substr(1,1);})
			a2 = a2.replace(/(\s)/gi,"");
			
			ay=ay.replace(/\"/,"'");			
			ay=ay.replace(/^\s+/,"");
			var lasti = _ie_popup_class_prop[s3].length;
			_ie_popup_class_prop[s3][lasti] = a2;
			_ie_popup_class_prop[s3][lasti+1]= ay;
	});	

	}
	);
}

// append css class to html code
function convert_popup(data, f){

	return data.replace(/(class="?(\w+)"?)/gi,function(s1,s2,s3){
			if (!s3)return s2;
			return ((f)?'':'style="')+_ie_popup_class[s3]+'"';
		}
	);	
}


function convert_class(obj,c){
	if (obj.name2 && obj.name2 == c)return;
	try{
	if (obj.name2){
		for (var i=0;i<_ie_popup_class_prop[obj.name2].length;i=i+2){
			switch (_ie_popup_class_prop[obj.name2][i]){
				case "border":
					obj.style.setAttribute(_ie_popup_class_prop[obj.name2][i],"");
					break;
				case "margin":
					obj.style.setAttribute(_ie_popup_class_prop[obj.name2][i],"0px");
					break;
				case "padding":
					obj.style.setAttribute(_ie_popup_class_prop[obj.name2][i],"");
					break;

				default:	
					obj.style.removeAttribute(_ie_popup_class_prop[obj.name2][i]);
			}
		}
	}

	if (c){
		for (var i=0;i<_ie_popup_class_prop[c].length;i=i+2){
			obj.style.setAttribute(_ie_popup_class_prop[c][i], _ie_popup_class_prop[c][i+1]);
		}
	}
	obj.name2 = c;	
	} catch(e){}
}

// Popup commands (show/hide popup window)
var PopupCommand = function(name)
{
	this.Name = name.toLowerCase();
	this.opening = false;
};

PopupCommand.prototype.execute = function(e,x,y){
ToolbarSets.Redraw(e);
	this.opening = true;
	if (!PopupSet.isInitial) {
		PopupSet.CreatePopup("contextmenu");
		PopupSet.CreatePopup("table");
		PopupSet.CreatePopup("form");
		PopupSet.CreatePopup("paste");
		PopupSet.CreatePopup("sourcetag");
	}

	var fr = document.getElementById(E.InstanceName + '_frame');
	var pLeft =  this.getLeft(fr);
	var pTop =  this.getTop(fr);

	var fr1 = parent.document.getElementById(E.InstanceName + 'main');
	var frameLeft = this.getLeft(fr1)*1;
	var frameTop = this.getTop(fr1)*1;

	var popup,oItem,new_popup;
	var doc = parent.document;

	oItem = PopupSet.Items[this.Name];
	new_popup=0;

	var p = oItem.DOMDiv;
	if (x){
		if (browser.IE){
			p_left = x + pLeft + 2;
			p_top = y + pTop + 2;
		} else {
			p_left = x + frameLeft + 2 + pLeft;
			p_top = y + frameTop + 2 + pTop;
		}
	} else {
		p_left = this.getLeft(e)*1 + (browser.NS ? frameLeft : 1);
		p_top = this.getTop(e)*1 + e.offsetHeight-1 + (browser.NS ? frameTop : 1);
	}

	if (browser.IE){
		E.Focus();
		oItem.Redraw();

		if(this.Name=="sourcetag"){
			if (oItem.Items.length<13){
				p.firstChild.style.height =  "auto";
			} else {
				p.firstChild.style.height =  "300px";
				p.firstChild.style.overflow = "scroll";			
			}
		}
				
		oItem.popup.show(p_left, p_top, 0, 0, document.body);
		var realHeight = oItem.popup.document.body.scrollHeight;
		var realWidth = oItem.popup.document.body.scrollWidth;
		if (!PopupSet.isInitial){
			var d = document.createElement("div");
			d.innerHTML = convert_popup(oItem.DOMDiv.innerHTML);
			document.body.appendChild(d);
			realHeight = d.offsetHeight;
			realWidth = 197;
			document.body.removeChild(d);
			d = null;
		}

		// Hides the dimension detector popup object.
		oItem.popup.hide();

		// Shows the actual popup object with correct height.
		if (realHeight > 0) {
			oItem.popup.show(p_left, p_top, realWidth, realHeight, document.body);
		}
		
		oItem.isOpen = true;
		
		oItem.Redraw();
	} else {
		PopupSet.CloseAll();
		p.style.left 	= p_left+"px";
		p.style.top 	= p_top+"px";
		p.style.visibility = "visible";
		
		p.style.position = "absolute";
		if(this.Name=="sourcetag"){
			p.firstChild.style.height =  "auto";
			p.firstChild.style.maxHeight =  "300px";
			p.firstChild.style.overflow = "scroll";
		}	
		p.style.display	= "";
		oItem.Redraw();
	}

	oItem.isOpen = true;
			
	if (!PopupSet.isInitial) {
		PopupSet.isInitial = true;
	}
	this.opening = false;
};

PopupCommand.prototype.getMode = function(){
	if (this.Name=="Modifyform"){
		if (!E.parentNode("FORM")) {
			return DISABLED;
		}
	}
	return OFF;
};

PopupCommand.prototype.getLeft = function(e)
{
	var nLeftPos = e.offsetLeft;
	var eParElement = e.offsetParent;
	while (eParElement != null) {
		nLeftPos += eParElement.offsetLeft;
		eParElement = eParElement.offsetParent;
	}
	return nLeftPos;
};

PopupCommand.prototype.getTop = function(e)
{
	var nTopPos = e.offsetTop;
	var eParElement = e.offsetParent;
	while (eParElement != null) {
		nTopPos += eParElement.offsetTop;
		eParElement = eParElement.offsetParent;
	}
	return nTopPos;
};

// Name: Popup class
// Info: Popup menu.

function PopupOnContextMenu() { return false ; }

var Popup = function( label, popupset)
{
	this.opening = true;
	if (PopupSet.Items[popupset]) return PopupSet.Items[popupset];
	
	var doc,popup;
	if (browser.IE) {
		popup = createPopup(); //parent.
		popup.document.oncontextmenu = PopupOnContextMenu;
		this.popup = popup;
		doc = popup.document;
	} else {
		doc = parent.document;
	}

	this.doc = doc;
	this.lastResult = -1;
	this.isOpen = false;
	this.Items = new Array();
	this.Separators = new Array();
	this.name = popupset;
	this.id = popupset+"_popup";

	if (browser.NS) {
		if (x=doc.getElementById(this.id)) {
			doc.body.removeChild(x);
		}
	}

	this.DOMDiv = doc.createElement( 'div' ) ;
	this.DOMDiv.id = popupset + "_popup";
	if (browser.NS) {
		this.DOMDiv.style.position = "absolute";
		this.DOMDiv.style.left=-1000;
		this.DOMDiv.style.top=-1000;
	}
	if (browser.NS) {
		this.DOMDiv.style.visibility = "hidden";
		this.DOMDiv.style.zIndex=100;
	}

	this.d2 = doc.createElement('div') ;
	if (browser.IE){
		convert_class(this.d2 , 'de_popup');
	} else {
		this.d2.className = 'de_popup' ;	
	}

	this.DOMTable = doc.createElement( 'table' ) ;
	this.DOMTable.cellPadding = 0 ;
	this.DOMTable.cellSpacing = 0 ;
	this.DOMTable.border = 0 ;
	this.DOMTable.width = "100%";

	doc.body.appendChild( this.DOMDiv ) ;
	this.DOMDiv.appendChild( this.d2 ) ;
	this.d2.appendChild( this.DOMTable ) ;

	for (var i=0; i<Popups[popupset].length;i++) {
		var p = Popups[popupset] ;
		this.DOMRow = this.DOMTable.insertRow(this.DOMTable.rows.length) ;
		if (p[i][0]=='-') {
			this.AddSeparator(p[i][3]);
		} else {
			this.AddItem(p[i][0], p[i][1], p[i][2], doc, p[i][3], this.name);
		}
	}
	this.opening = false;
	return this;
};

Popup.prototype.ReplaceItems = function(id)
{
	var l = this.DOMTable.rows.length;
	for (var i=0;i<l;i++) {
		this.DOMTable.deleteRow(-1);
	}
	for (var i=1; i<_de_spell_words[id].length;i++) {
		if (i>10) {
			break;
		}
		this.DOMRow = this.DOMTable.insertRow(-1) ;
		this.AddItem("ReplaceWord", _de_spell_words[id][i], null, this.doc, null, this.name,_de_spell_words[id][i]);
	}
	this.DOMRow = this.DOMTable.insertRow(-1) ;
	this.AddSeparator(null);
	this.DOMRow = this.DOMTable.insertRow(-1) ;
	this.AddItem("Ignore", "Ignore", '', this.doc, null, this.name, _de_spell_words[id][0]);
	this.DOMRow = this.DOMTable.insertRow(-1) ;
	this.AddItem("IgnoreAll", "Ignore All", '', this.doc, null, this.name, _de_spell_words[id][0]);
	this.DOMRow = this.DOMTable.insertRow(-1) ;
	this.AddItem("AddToDictionary", "Add to Dictionary", 'addtodictionary.gif', this.doc, null, this.name, _de_spell_words[id][0]);
};

Popup.prototype.RemoveAllItems = function()
{
	this.Items = new Array();
	var l = this.DOMTable.rows.length;
	for (var i=0;i<l;i++) {
		this.DOMTable.deleteRow(-1);
	}
};

Popup.prototype.AddItem = function( commandName, tooltip, icon, doc, tags, popup_name, add_par)
{
	var oPopupItem = new PopupItem(commandName, tooltip, icon, doc, tags, popup_name, add_par);
	this.Items[ this.Items.length ] = oPopupItem ;
	oPopupItem.CreateInstance( this ) ;
};

Popup.prototype.AddItem2 = function( commandName, tooltip, icon, add_par)
{
	this.DOMRow = this.DOMTable.insertRow(-1) ;
	var oPopupItem = new PopupItem(commandName, tooltip, icon, this.doc, null, this.name, add_par);
	this.Items[ this.Items.length ] = oPopupItem ;
	oPopupItem.CreateInstance( this ) ;
};

Popup.prototype.find = function( str )
{
	if (this.lastResult!==-1){
		this.Items[this.lastResult].Redraw(OFF);
	}
	if (!str) {
		return;
	}	
	for (var i = 0; i < this.Items.length ; i++){
		var l = str.length
		if (str.toLowerCase() == this.Items[i].parameter.substr(0,str.length).toLowerCase() ){
			this.Items[i].Redraw(ON);
			if (browser.IE){
				this.Items[i].DOMDiv.scrollIntoView();
			} else {
				var p = new Position();
				t = p.getTopToElement(this.Items[i].DOMDiv, this.DOMDiv);
				this.d2.scrollTop = t;
			}
								
			this.lastResult = i;
			break;
		}
	}
};

Popup.prototype.selectNext = function()
{
	if (this.lastResult!==-1){
		this.Items[this.lastResult].Redraw(OFF);
		if(this.lastResult<this.Items.length-1)this.lastResult++;
	} else {
		this.lastResult = 0;
	}	
	this.Items[this.lastResult].Redraw(ON);
	if (browser.IE){
		this.Items[this.lastResult].DOMDiv.scrollIntoView();
	} else {
		var p = new Position();
		t = p.getTopToElement(this.Items[this.lastResult].DOMDiv, this.DOMDiv);
		this.d2.scrollTop = t;
	}
};

Popup.prototype.selectPrevious = function()
{
	if (this.lastResult!==-1){
		this.Items[this.lastResult].Redraw(OFF);
		if(this.lastResult>0)this.lastResult--;
	} else {
		return;
	}	

	this.Items[this.lastResult].Redraw(ON);
	if (browser.IE){
		this.Items[this.lastResult].DOMDiv.scrollIntoView();
	} else {
		var p = new Position();
		t = p.getTopToElement(this.Items[this.lastResult].DOMDiv, this.DOMDiv);
		this.d2.scrollTop = t;
	}	

};

Popup.prototype.completeWord = function( str )
{
	if (this.lastResult!==-1){
		this.Items[this.lastResult].Redraw(OFF);
		E.commands.getCommand("NewTag").execute( str , this.Items[this.lastResult].parameter);
		context_str = this.Items[this.lastResult].parameter;
		return this.Items[this.lastResult].parameter;
	}	
};

var Separator = function(tags)
{
	this.separator = true;
	this.tags = tags;
	this.mode=ON;
};

Separator.prototype.CreateInstance = function(parentToolbar)
{
	if (!parentToolbar.DOMRow) {
		return;
	}
	var oCell = parentToolbar.DOMRow.insertCell(-1) ;
	oCell.unselectable = 'on' ;
	if (browser.IE){
		oCell.innerHTML = convert_popup('<div class="de_popup_vert_seperator"><div></div></div>');	
	} else {
		oCell.innerHTML = '<div class="de_popup_vert_seperator"><div></div></div>';
	}	
	this.Div = oCell.firstChild ;
};

Separator.prototype.Redraw = function()
{
	this.mode=ON;
	if (this.tags && E.checkTags(this.tags)==DISABLED){
		if (browser.IE){
			convert_class(this.Div.parentNode.parentNode, 'de_hide_popup_item');
		} else {
			this.Div.parentNode.parentNode.className = 'de_hide_popup_item';
		}	
		this.mode=EXCLUDE;
	} else {
		if (this.Div.parentNode && this.Div.parentNode.parentNode) {
			if (browser.IE){
				convert_class(this.Div.parentNode.parentNode , '');
			} else {
				this.Div.parentNode.parentNode.className = '';
			}	
		}	
	}
};

Popup.prototype.AddSeparator = function(tag)
{
	var s = new Separator(tag);
	this.Items[ this.Items.length ] = s ;
	s.CreateInstance( this ) ;
};


Popup.prototype.Redraw = function()
{
	for (var i = 0 ; i < this.Items.length ; i++) {
		this.Items[i].Redraw() ;
	}
};

// Name: Popup Item class
// Info: item from popup menu

var PopupItem = function( commandName, tooltip, icon, doc, tags, popup_name,add_par)
{
	this.popup_name = popup_name;
	this.tags = tags;
	this.doc		= doc;
	this.Command	= commandName ;
	this.Name = commandName.toLowerCase();

	imageLoader.addImage(this.Command, serverurl + deveditPath1 + '/skins/' + E.skinName+ "/tb.gif");
	if (typeof(tooltip) == "string"){
		this.Label		= tooltip ? tooltip : commandName;
		this.Tooltip	= tooltip ? tooltip : commandName;
	} else {
		this.Label		= tooltip[0];
		this.Tooltip	= tooltip[0];
		this.Label2		= tooltip[1];
		this.Tooltip2	= tooltip[1];
	}
	this.IconPath	= serverurl + deveditPath1 + '/skins/' + E.skinName+ "/tb.gif" ;
	this.mode 		= OFF;
	this.IconOff	= icon;
	if (add_par) {
		this.parameter	= add_par;
	} else {
		this.parameter	= null;
	}
};

PopupItemOnOver = function()
{
	if (this.PopupItem.mode!=DISABLED){
		if (browser.IE){
			convert_class(this, 'de_popupitem_on') ;
		} else {
			this.className = 'de_popupitem_on' ;		
		}
	}
};

PopupItemOnOut = function()
{
	if (this.PopupItem.mode==OFF && this.PopupItem.mode!=DISABLED){
		if (browser.IE){
			convert_class(this, 'de_popupitem');
		}else{
			this.className = 'de_popupitem' ;
		}
	}
};

PopupItemOnClick = function()
{
	if (this.PopupItem.mode!==DISABLED){
		oCommand = E.commands.getCommand(this.PopupItem.Command);
		oCommand.execute(this.PopupItem.parameter);
		if (this.PopupItem.popup) {
			this.PopupItem.popup.hide();
		}
		this.PopupItem.mode=OFF;
		if (browser.IE){
			convert_class(this, 'de_popupitem') ;
		}else{
			this.className = 'de_popupitem' ;
		}
		PopupSet.Redraw();
		return false ;
	}
};

PopupItem.prototype.CreateInstance = function( parentToolbar )
{
	if (parentToolbar.popup) {
		this.popup = parentToolbar.popup;
	}
	this.DOMDiv = this.doc.createElement( 'div' ) ;
	this.DOMDiv.PopupItem	= this ;
	this.DOMDiv.id = garbage.generateId();
	garbage.add(this.DOMDiv.id);
	this.DOMDiv.unselectable = 'on' ;

	if (browser.IE){
		convert_class(this.DOMDiv, 'de_popupitem');
	} else {
		this.DOMDiv.className		= 'de_popupitem' ;	
	}
	
	this.DOMDiv.onmouseover = PopupItemOnOver;
	this.DOMDiv.onmouseout	= PopupItemOnOut;
	this.DOMDiv.onclick = PopupItemOnClick;
	if (!parentToolbar.DOMRow) {
		return;
	}
	var oCell = parentToolbar.DOMRow.insertCell(parentToolbar.DOMRow.cells.length);
	oCell.appendChild(this.DOMDiv);

	if (browser.IE){
	this.DOMDiv.innerHTML = convert_popup(
		'<table width="100%" title="' + this.Tooltip +'" cellspacing="0" cellpadding="0" border="0"><tr>' +
		(this.IconOff ? '<td class="de_popupitem_icon"><div style="background-repeat: no-repeat;overflow:hidden;width:21px;height:20px;" unselectable = "on"></div></td>' : '<td class="de_popupitem_icon"></td>' )+
		'<td align="left" class="de_popuptext">&nbsp;&nbsp;&nbsp;' + this.Label + '</td></tr></table>' );
	} else {
	this.DOMDiv.innerHTML =
		'<table width="100%" title="' + this.Tooltip +'" cellspacing="0" cellpadding="0" border="0"><tr>' +
		(this.IconOff ? '<td class="de_popupitem_icon"><div style="background-repeat: no-repeat;overflow:hidden;width:21px;height:20px;" unselectable = "on"></div></td>' : '<td class="de_popupitem_icon"></td>' )+
		'<td align="left" class="de_popuptext">&nbsp;&nbsp;&nbsp;' + this.Label + '</td></tr></table>' ;
	}
	
	
	if (this.IconOff) {
		this.icon = this.DOMDiv.firstChild.firstChild.firstChild.childNodes.item(0).firstChild;
		this.icon.id = garbage.generateId();
		garbage.add(this.icon.id);

		this.icon.src = imageLoader.getImage(this.Command).src;

		this.icon.style.background = "url("+imageLoader.getImage(this.Command).src+")";
		this.icon.style.backgroundRepeat = "no-repeat";
		this.icon.style.overflow = "hidden";
		try{
			this.icon.style.backgroundPosition = "0px -"+(tlb_pos[this.Name]*20)+"px";
			// ToDo - change ToolbarDropButton
			if(this.Name=="paste")this.icon.style.backgroundPosition="0px 0px";

		}catch(e){}

	}

	this.textitem = this.DOMDiv.firstChild.firstChild.firstChild.childNodes.item(1);
};

PopupItem.prototype.changeLabel = function(l)
{
	if (l=="not found"){
		this.textitem.innerHTML = "&nbsp;&nbsp;&nbsp;"+this.Label;
		this.DOMDiv.firstChild.title = this.Tooltip;
	} else {
		this.textitem.innerHTML = "&nbsp;&nbsp;&nbsp;"+this.Label2;
		this.DOMDiv.firstChild.title = this.Tooltip2;
	}
};

PopupItem.prototype.getMode = function()
{
	return this.mode;
}

PopupItem.prototype.Redraw = function(modex)
{
	var m ;
	oCommand	= E.commands.getCommand(this.Command);
	if (!oCommand){
		return;
	}
	if(oCommand.mode&&this.Label2) {
		this.changeLabel(oCommand.mode);
	}

	if (this.tags &&
		this.popup_name == "contextmenu" &&
		this.Command!="Copy" &&
		this.Command!="Cut" &&
		this.Command!="Paste" &&
		this.Command!="Pastetext" &&
		this.Command!="Pasteword" ) {
		var mode = E.checkTags(this.tags);
		if (mode==DISABLED) {
			mode=EXCLUDE;
		}
		m = mode;
	} else {
		if (this.Name=="newtag"){
			m = this.getMode();
		} else {	
			m = oCommand.getMode() ;
		}	
	}

	if (!E.editable) {
		m=DISABLED;
	}
	if (this.Name=="newtag"){
		if(modex>=0)m=modex;
	}
	if (modex && m!==DISABLED )m=modex;
	this.mode = m ;
	if (this.DOMDiv.parentNode) {
		if (browser.IE){
			convert_class(this.DOMDiv.parentNode.parentNode, '');
		} else {
			this.DOMDiv.parentNode.parentNode.className = '';
		}
	}
	try{
		switch ( this.mode ) {
			case ON :
				if (this.IconOff) {
					if (browser.IE){
						convert_class(this.icon, "de_popupitem_icon");
					} else {
						this.icon.className = "de_popupitem_icon";
					}
				}

				if (browser.IE){
					convert_class(this.textitem, "de_popuptext");
					convert_class(this.DOMDiv, 'de_popupitem_on');
				} else {
					this.textitem.className = "de_popuptext";
					this.DOMDiv.className = 'de_popupitem_on' ;
				}
				break ;
			case OFF :
				if (this.IconOff) {
					if (browser.IE){
						convert_class(this.icon, "de_popupitem_icon");
					} else {
						this.icon.className = "de_popupitem_icon";
					}
				}

				if (browser.IE){
					convert_class(this.textitem, "de_popuptext");
					convert_class(this.DOMDiv, 'de_popupitem') ;
				} else {
					this.textitem.className = "de_popuptext";
					this.DOMDiv.className = 'de_popupitem' ;
				}
				break ;
			case EXCLUDE:
				if (browser.IE){
					convert_class(this.DOMDiv.parentNode.parentNode, 'de_hide_popup_item') ;
				} else {
					this.DOMDiv.parentNode.parentNode.className = 'de_hide_popup_item' ;
				}
				break ;
			default :
				
				if (browser.IE){
					convert_class ( this.DOMDiv, 'de_popupitem') ;
				} else {
					this.DOMDiv.className = 'de_popupitem' ;
				}
				if (this.IconOff){
					if (browser.IE){
						convert_class(this.icon, "de_popupitem_icon_disabled");
					} else {
						this.icon.className = "de_popupitem_icon_disabled";
					}
				}
				if (browser.IE){
					convert_class( this.textitem, "de_popupitem_text_disabled");
				} else {
					this.textitem.className = "de_popupitem_text_disabled";
				}
		}
	} catch(e){}
};

// Name: Button class
// Info: Toolbar button object.

var ToolbarButton = function( commandName, tooltip, width, height, src)
{
	if (src){
		src = setFixedSrc(src);
	}
	if (src){
		imageLoader.addImage(commandName, src);
	} else {
		imageLoader.addImage(commandName, 'skins/' + E.skinName+ "/tb.gif");
	}
	this.Command	= commandName ;
	this.Name = commandName.toLowerCase();
	this.Tooltip	= tooltip ? tooltip : commandName ;
	this.width = width;
	this.height = height;
	if (src){
		this.IconPath	= src ;
	} else {
		this.IconPath	= 'skins/' + E.skinName+ "/tb.gif" ;
	}
	this.mode 		= OFF;
};

var clone = document.createElement( 'div' ) ;

ToolbarButtonOver = function()
{
	if (this.ToolbarButton.mode!==DISABLED&&this.ToolbarButton.mode!==ON) {
		this.className = 'de_TB_Button_Over' ;
	}
};

ToolbarButtonOut = function()
{
	if (this.ToolbarButton && this.ToolbarButton.mode==OFF) {
		this.className = 'de_TB_Button_Off' ;
	}
};

ToolbarButtonDown = function()
{
	E.Focus();
	if (this.ToolbarButton.mode==OFF) {
		this.className = 'de_TB_Button_On';
		if (this.ToolbarButton.width){
			this.style.width = this.ToolbarButton.width;
			this.style.height = this.ToolbarButton.height;
		}
	}
};

ToolbarButtonOnClick = function()
{
	oCommand = E.commands.getCommand(this.ToolbarButton.Command);
	if (this.ToolbarButton.mode!==DISABLED) {
		oCommand.execute(this.ToolbarButton.DOMDiv);
		ToolbarSets.Redraw();
		PopupSet.Redraw(this.ToolbarButton.Command.toLowerCase());
		return false ;
	}
};

ToolbarButton.prototype.CreateInstance = function( parentToolbar )
{
	var d = clone.cloneNode(false) ;
	d.id  = garbage.generateId();
	garbage.add(d.id);
	d.className		= 'de_TB_Button_Off' ;
	d.unselectable = "on";
	d.style.margin = "1px";
	d.ToolbarButton	= this ;

	d.onmouseover = ToolbarButtonOver;
	d.onmouseout = ToolbarButtonOut;
	d.onmousedown = ToolbarButtonDown;
	d.onclick = ToolbarButtonOnClick;

	d.innerHTML = '<div '+'title="'+this.Tooltip+'"'+'class="Button" style="background-repeat: no-repeat;overflow:hidden;width:'+(this.width ? this.width : '21px')+';height:'+(this.height ? this.height : '20px')+';" unselectable = "on"></div>';
	d.firstChild.style.background = "url("+imageLoader.getImage(this.Command).src+")";
	d.firstChild.style.backgroundRepeat = "no-repeat";
	d.firstChild.style.overflow = "hidden";
	d.firstChild.style.display = "block";
	try{
		d.firstChild.style.backgroundPosition = "0px -"+(tlb_pos[this.Name]*20)+"px";
	}catch(e){}

	var oCell = parentToolbar.DOMRow.insertCell(parentToolbar.DOMRow.cells.length) ;
	var pic = oCell.appendChild( d ) ;
	this.DOMDiv = d;
};

ToolbarButton.prototype.Redraw = function(e)
{
	var m ;

	oCommand = E.commands.getCommand(this.Command);

	m = oCommand.getMode();
	if (!E.editable && this.Command!=="Help" && this.Command!=="Pageproperties" && this.Command!=="Fullscreen" && this.Command!=="Save") {
		m=DISABLED;
	}
	if ( m == this.mode ) {
		return ;
	}

	// Sets the actual state.
	this.mode = m ;
	switch ( this.mode ) {
		case ON :
			this.DOMDiv.className = 'de_TB_Button_On' ;
			break ;
		case OFF :
			this.DOMDiv.className = 'de_TB_Button_Off' ;
			break ;
		default :
			this.DOMDiv.className = 'de_TB_Button_Disabled' ;
			break ;
	}
};

// Name: DropButton class
// Info: DropButton object.

var ToolbarButtonDrop = function( commandName, tooltip, style, sourceView ,oPopup)
{
	imageLoader.addImage(commandName, 'skins/' + E.skinName+ "/" + commandName.toLowerCase() + '.gif');
	this.Command	= commandName ;
	this.Command2	= commandName + "down" ;
	this.Label		= "";
	this.customCommand = false;
	this.Tooltip	= tooltip ? tooltip : commandName ;
	this.SourceView	= sourceView ? true : false ;
	this.icons = new Array();
	this.icons[0] = new Image();
	
	// _custom
	if (commandName.toLowerCase().indexOf("_custom")>=0)this.customCommand=true;
	if (this.customCommand){
		this.jsfunc = sourceView;
		if (!style || style=="") {
			commandName = "fontcolor";
		} else {
			commandName = style;
		}	
	}
	this.icons[0].src = 'skins/' + E.skinName+ "/" + commandName.toLowerCase() + '.gif';
	if (commandName.toLowerCase()=="fontcolor"||commandName.toLowerCase()=="highlight"){
		this.icons[1] = new Image();
		this.icons[1].src = 'skins/' + E.skinName+ "/" + commandName.toLowerCase() + '2.gif' ;
	}
	//  || this.customCommand
	this.IconPath2	= 'skins/' + E.skinName+ "/dropmenu.gif" ;
	this.mode 		= OFF;
	this.oPopup		= oPopup ? oPopup : false;
	this.data		= null;
	this.range		= null;
};

ToolbarButtonDrop.prototype.SetButtonColor = function( color )
{
	b = this.button.style;
	if (color === "") {
		if (this.Command=="Fontcolor")	{
			b.backgroundColor = "#000000";
			color="#000000";
		} else {
			b.backgroundColor = "#FFFFFF";
			color="#FFFFFF";
		}
	} else {
		b.backgroundColor = color;
	}
	this.data = color;

};

ToolbarButtonDrop.prototype.SetColor = function( color )
{
	b = this.button.style;
	if (color === "") {
		if (this.Command=="Fontcolor")	{
			b.backgroundColor = "#000000";
			color="#000000";
		} else {
			b.backgroundColor = "#FFFFFF";
			color="#FFFFFF";
		}
	} else {
		b.backgroundColor = color;
	}
	this.data = color;
	E.Focus();
	if (browser.IE) {
		E.restorePos();
	}

	oCommand = E.commands.getCommand(this.Command);
	if (this.customCommand){
		oCommand.execute(this.jsfunc,color);
	} else {
		oCommand.execute(color);
	}
};

ToolbarButtonDropOnClick = function()
{
	oCommand	= E.commands.getCommand(this.ToolbarButton.Command);
	if (this.ToolbarButton.mode!==DISABLED) {
		this.ToolbarButton.button.className = 'de_TB_ButtonDrop_On' ;
		if (this.ToolbarButton.customCommand){
			oCommand.execute(this.ToolbarButton.jsfunc,this.ToolbarButton.data);
		} else {
			oCommand.execute(this.ToolbarButton.data);
		}

		ToolbarSets.Redraw(this.ToolbarButton.Command.toLowerCase());
		PopupSet.Redraw(this.ToolbarButton.Command.toLowerCase());
		return false ;
	}
};

ToolbarButtonDropOnMouseDown = function()
{
	if (this.ToolbarButton.mode==OFF) {
		this.className = 'de_TB_ButtonDrop_On' ;
	}
};

ToolbarButtonDownDropOnDown = function()
{
	if (this.ToolbarButton.mode==OFF) {
		this.className = 'de_TB_ButtonDrop_Right_On' ;
	}
};

ToolbarButtonDownDropOnClick = function()
{
	oCommand2	= E.commands.getCommand(this.ToolbarButton.Command2);
	if (this.ToolbarButton.mode!==DISABLED) {
		if (browser.IE) {
			E.storePos();
		}
		this.ToolbarButton.buttondown.className = 'de_TB_ButtonDrop_Right_On' ;
		window.tempcolorbut=this.ToolbarButton;
		oCommand2.execute(this.ToolbarButton.DOMDiv);
		ToolbarSets.Redraw(this.ToolbarButton.Command2.toLowerCase());
		PopupSet.Redraw(this.ToolbarButton.Command.toLowerCase());
		return false ;
	}
};

ToolbarButtonDownDropOnMouseOver = function()
{
	if (this.ToolbarButton.mode!==DISABLED){
		if (this.ToolbarButton.icons[1]) {
			this.ToolbarButton.button.src = this.ToolbarButton.icons[1].src;
		}
		this.ToolbarButton.button.className = 'de_TB_ButtonDrop_Over' ;
		this.ToolbarButton.buttondown.className = 'de_TB_ButtonDrop_Over' ;
	}
};

ToolbarButtonDownDropOnMouseOut = function()
{
	if (this.ToolbarButton.mode==OFF){
		if (this.ToolbarButton.icons[1]) {
			this.ToolbarButton.button.src = this.ToolbarButton.icons[0].src;
		}
		this.ToolbarButton.button.className = 'de_TB_ButtonDrop_Off' ;
		this.ToolbarButton.buttondown.className = 'de_TB_ButtonDrop_Right' ;
	}
};

ToolbarButtonDropOnMouseOver = function()
{
	if (this.ToolbarButton.mode!==DISABLED){
		if (this.ToolbarButton.icons[1]) {
			this.ToolbarButton.button.src = this.ToolbarButton.icons[1].src;
		}
		this.ToolbarButton.button.className = 'de_TB_ButtonDrop_Over' ;
		this.ToolbarButton.buttondown.className = 'de_TB_ButtonDrop_Over' ;
	}
};

ToolbarButtonDropOnMouseOut = function()
{
	if (this.ToolbarButton.mode==OFF){
		if (this.ToolbarButton.icons[1]) {
			this.ToolbarButton.button.src = this.ToolbarButton.icons[0].src;
		}
		this.ToolbarButton.button.className = 'de_TB_ButtonDrop_Off' ;
		this.ToolbarButton.buttondown.className = 'de_TB_ButtonDrop_Right' ;
	}
}

ToolbarButtonDrop.prototype.CreateInstance = function( parentToolbar )
{
	var str =
		'<img class="de_buttondrop" title="'+this.Tooltip+'" src="' + this.icons[0].src + '" unselectable="on">'+
		'<img class="de_buttondrop" title="'+this.Tooltip+'" src="' + this.IconPath2 + '" unselectable="on">';


	var oCell = parentToolbar.DOMRow.insertCell(parentToolbar.DOMRow.cells.length) ;
	oCell.innerHTML = str ;
	this.button	= oCell.firstChild;
	this.buttondown = oCell.firstChild.nextSibling;
	this.button.id  = garbage.generateId();
	garbage.add(this.button.id);
	this.buttondown.id  = garbage.generateId();
	garbage.add(this.buttondown.id);

	this.buttondown.ToolbarButton	= this ;
	this.button.ToolbarButton	= this ;

	this.buttondown.ToolbarButton	= this ;
	this.button.ToolbarButton	= this ;
	this.button.onmousedown		= ToolbarButtonDropOnMouseDown;
	this.button.onclick			= ToolbarButtonDropOnClick;
	this.buttondown.onmousedown	= ToolbarButtonDownDropOnDown;
	this.buttondown.onclick		= ToolbarButtonDownDropOnClick;
	this.buttondown.onmouseover = ToolbarButtonDownDropOnMouseOver;
	this.buttondown.onmouseout	= ToolbarButtonDownDropOnMouseOut;
	this.button.onmouseover		= ToolbarButtonDropOnMouseOver;
	this.button.onmouseout		= ToolbarButtonDropOnMouseOut;

	this.DOMDiv = this.button;

};

ToolbarButtonDrop.prototype.Redraw = function()
{
	var m ;
	oCommand = E.commands.getCommand(this.Command);
	m = oCommand.getMode() ;
	if (!E.editable) {
		m=DISABLED;
	}
	if ( m == this.mode ) {
		return ;
	}

	this.mode = m ;
	switch ( this.mode ) {
		case ON :
			this.button.className = 'de_TB_ButtonDrop_On' ;
			this.buttondown.className = 'de_TB_ButtonDrop_On' ;
			break ;
		case OFF :
			this.button.className = 'de_TB_ButtonDrop_Off' ;
			this.buttondown.className = 'de_TB_ButtonDrop_Right' ;
			break ;
		default :
			this.button.className = 'de_TB_ButtonDrop_Disabled' ;
			this.buttondown.className = 'de_TB_ButtonDrop_Disabled' ;
			break ;
	}
};

// Name: LinkLabel class
// Info: Label with link in the toolbar.

var LinkLabel = function(name,label)
{
	this.Name = name;
	this.Label = label;
};

LinkLabelOnClick = function()
{
	oCommand = E.commands.getCommand(this.ToolbarButton.Name);
	oCommand.execute();
};

LinkLabel.prototype.CreateInstance = function( parentToolbar )
{
	this.cDiv = document.createElement( 'div' ) ;
	this.cDiv.unselectable = "on";

	var pos = 0;
	if (E.toolbarpos < DevEditToolbars.length-1) {
		pos = E.toolbarpos+1;
	}
	var str = this.Label=="Switch mode"? DevEditToolbars[pos] + " mode":str=this.Label;
	if (this.Name=="EditorMode" && hideitems["tlbmode"]=="1") {
		str = "";
	}
	this.cDiv.innerHTML = '<A class="de_linklabel" title="'+this.Label+'" href="#">'+str+'</A>&nbsp;&nbsp;&nbsp;';

	var oCell = parentToolbar.DOMRow.insertCell(parentToolbar.DOMRow.cells.length) ;
	oCell.appendChild( this.cDiv ) ;
	oCell.unselectable = "on";
	this.cDiv.firstChild.ToolbarButton	= this ;
	this.cDiv.firstChild.id = garbage.generateId();
	garbage.add(this.cDiv.firstChild.id);

	this.cDiv.firstChild.onclick = LinkLabelOnClick;
};

LinkLabel.prototype.ChangeLabel = function()
{
	var pos = 0;
	if (E.toolbarpos < DevEditToolbars.length-1) {
		pos = E.toolbarpos+1;
	}
	var str = "Switch to " + DevEditToolbars[pos] + " mode";

	this.cDiv.innerHTML = '<A class="de_linklabel" title="'+this.Label+'" href="#">'+str+'</A>';
};

LinkLabel.prototype.Redraw = function(e)
{
	return ON;
};

// Name: Combo class
// Info: Combo component.

var Combo = function( commandName, label, items, classname, stylename)
{
	this.Command	= commandName;
	this.Label		= label ? label : commandName ;
	this.Tooltip	= this.Label;
	this.Items		= items;
	this.classname	= classname?classname:false;
	this.collapsed	= true;
	this.stylename  = stylename;
};

Combo.prototype.Redraw = function( parentToolbar )
{
	try{
		var list = this.SelectElement.Combo.ComboList;
		if (!this.SelectElement.Combo.collapsed){
			list.style.top = "0px";
			list.style.left = "0px";
			list.style.visibility = "hidden";
			this.SelectElement.Combo.collapsed = true;
		}

		var sValue=null;
		try {
			sValue = E._frame.queryCommandValue( this.Command ) ;
		}
		catch(e) {sValue=null;}
		var sel=0;
		var find=false;
		if (this.Command=="Fontsize"){
			if (Selection && Selection.currentNode && Selection.currentNode.style) {
				for(var i=0;i<this.Items.length;i++) {
					if (Selection.currentNode.style.fontSize==this.Items[i][1])sValue = this.Items[i][1];
				}
			}
		}
		for (var i=0;i<this.Items.length;i++){
			if (sValue && typeof(sValue)=="number") {
				sValue = sValue.toString();
			}
			if (sValue && this.Items[i][1] && this.Items[i][1].toLowerCase().indexOf(sValue.toLowerCase())>=0) {
				find=true;
				sValue=this.Items[i][0];
				break;
			}
			if (sValue && this.Items[i][0] && this.Items[i][0].toLowerCase().indexOf(sValue.toLowerCase())>=0) {
				find=true;
				sValue=this.Items[i][0];
				break;
			}
		}
		if (!find && this.Command!=="Formatblock") {
			sValue=null;
		}
		if (!find && this.Command=="Formatblock") {
			sValue="Normal";
		}

		if(sValue) {
			this.Head.innerHTML = sValue;
		} else {
			this.Head.innerHTML = this.Items[0][0];
		}
	} catch(e){}
};

ComboOnClick = function()
{
	if (!E.editable) {
		return;
	}
	closePrevCombo();
	E.Focus();
	var list = this.Combo.ComboList;
	var c = this.Combo.SelectElement;
	var pos = new Position();
	var left = pos.getLeft(c);
	var top = pos.getTop(c)+c.offsetHeight;
	if (list.offsetWidth < c.offsetWidth){
		list.style.width = c.offsetWidth;
		list.firstChild.style.width = c.offsetWidth;
	}

	if (this.Combo.collapsed) {
		list.style.top = top;
		list.style.left = left;
		list.style.visibility = "visible";
		this.Combo.collapsed = false;

		// clip height if needed
		if ((E.realHeight - 50) < list.offsetHeight){
			list.style.height = (E.realHeight - 50)+ "px";
			list.style.overflow = "auto";
		}

	} else {
		list.style.top = "0px";
		list.style.left = "0px";
		list.style.visibility = "hidden";
		this.Combo.collapsed = true;
	}
	last_combo = this.Combo;
};

ComboOnChange = function(command,name)
{
	oCommand	= E.commands.getCommand(command);
	oCommand.execute(name);
	ToolbarSets.Redraw();
};

var last_combo = null;
closePrevCombo = function()
{
	if (!last_combo) {
		return;
	}
	var list = last_combo.ComboList;

	if (!last_combo.collapsed) {
		list.style.top = "0px";
		list.style.left = "0px";
		list.style.visibility = "hidden";
	}
};

ComboOnFocus = function(){}

Combo.prototype.CreateInstance = function( parentToolbar )
{
	var d = clone.cloneNode(false);
	this.maindiv = d;

	// combo lists
	var combolist = document.createElement("div");
	combolist.id = this.Command+"combolist";
	combolist.className = "de_combolist";
	combolist.style.left = "0px";
	combolist.style.top = "0px";
	var opt='<table class="de_combo_table" unselectable="on" cellspacing="0" cellpadding="0" border="0">';
	for (i=0;i<this.Items.length;i++){
		var add_style = "";
		if (this.stylename=="see_array_2"){
			add_style = ((combostyle_flag)?unescape(this.Items[i][1].replace(/>/g,' unselectable="on" >')):"") + " "+unescape(this.Items[i][0]) + ((combostyle_flag)?unescape(this.Items[i][1].replace(/</g,'</')):"");
		} else {
			add_style = '<span unselectable="on"><span unselectable="on" ' + ((combostyle_flag)?'style="'+((this.Items[i][0]!=="" && this.Items[i][0]!=="Font" && this.Items[i][0]!=="Default" && i!=this.Items.length-1)?this.stylename+':'+unescape(this.Items[i][1]):'')+';"':"") + '>' + unescape(this.Items[i][0]) + "</span></span>";
		}
		if (this.Items[i][0]=="-"){
			opt=opt+'<tr><td><hr/ noshade size=1></td></tr>';
		} else {
			opt=opt+'<tr><td style="padding-left:3px;" unselectable="on" onclick=\'javascript:ComboOnChange("'+this.Command+'","'+unescape(this.Items[i][1])+'")\' onmouseout=\'javascript:this.className="de_combolist_item"\' onmouseover=\'javascript:this.className="de_combolist_item_highlight"\'>'+add_style+'</td></tr>';
		}
	}
	opt+="</table>";
	combolist.innerHTML=opt;
	document.body.appendChild(combolist);
	this.ComboList = combolist;

	// select element
	d.innerHTML = '<div title="' + this.Tooltip + '"  unselectable="on" class="'+this.classname+'" onmouseover=\'javascript:this.className="' +
					this.classname+ ' de_ComboOver"\' onmouseout=\'javascript:this.className="' +
					this.classname+ '"\'>'+
					'<table width="100%" cellspacing="0" cellpadding="0" border="0" unselectable="on">' +
					'<tr><td unselectable="on" class="de_Combo_head" width="100%"><span unselectable="on" style="margin:1px">' +
					unescape(this.Items[0][0])+ '</span></td>' +
					'<td ><div unselectable="on" class="de_combo_drop"></div></td>'+
					'</tr></table></div>';

	// Gets the SELECT element.
	this.SelectElement = d.firstChild;
	this.Head = d.firstChild.firstChild.firstChild.firstChild.firstChild.firstChild;

	this.SelectElement.id  = garbage.generateId();
	garbage.add(this.SelectElement.id);

	this.SelectElement.Combo = this ;

	this.SelectElement.onclick = ComboOnClick;
	this.SelectElement.onchange = ComboOnChange;
	this.SelectElement.onfocus = ComboOnFocus;

	var oCell = parentToolbar.DOMRow.insertCell(parentToolbar.DOMRow.cells.length) ;
	oCell.appendChild( d ) ;
	this.cDiv = d;
};

// Set of ToolbarSet
var ToolbarSets = new Object() ;

ToolbarSets.Items = new Array() ;
ToolbarSets.Itemsnum = new Array() ;

ToolbarSets.SwitchToolbarSet = function( toolbarsetName ){
	if (!this.Items[toolbarsetName]){
		this.AddItem(toolbarsetName,'eToolbar');
	}
	this.Items[toolbarmode].el.style.display="none";
	if (this.Items["_source"]) {
		this.Items["_source"].el.style.display="none";
	}
	if (this.Items["_preview"]) {
		this.Items["_preview"].el.style.display="none";
	}

	this.Items[toolbarsetName].el.style.display="";
	if (browser.NS) {
		var d = document.getElementById(E.InstanceName+"main1");
		if (d) {
			var tempH = d.offsetHeight;
			if (tempH){
				d.style.height = tempH - 1;
				d.style.height = tempH;
			}
		}
	}
	PopupSet.CloseAll();
};

ToolbarSets.AddItem = function(name,parentId){
	if (this.Items[name]) return;
	oToolbarSet = new ToolbarSet(name);
	oToolbarSet.Create();
	var toolbarplace = document.getElementById( parentId ) ;
	toolbarplace.appendChild(oToolbarSet.el);
	this.Items[name] = oToolbarSet;
	if (name=="_source"||name=="_preview") {
		this.Items[name].el.style.display="none";
	}
	this.Itemsnum[this.Itemsnum.length] = oToolbarSet;
};

ToolbarSets.Redraw = function(e)
{
	for (var i = 0 ; i < this.Itemsnum.length ; i++ ) {
		ToolbarSets.Itemsnum[i].Redraw(e) ;
	}
};

// Name: ToolbarSet class
// Info: Set of the toolbars

var ToolbarSet = function(toolbarSetName)
{
	this.Toolbars = new Array();
	this.Name = toolbarSetName;
	this.toolbarsetTable = document.createElement( 'table' ) ;
	this.el = this.toolbarsetTable;
	this.toolbarsetTable.cellPadding=0;
	this.toolbarsetTable.cellSpacing=0;
	this.toolbarsetTable.border=0;
	if (toolbarSetName!="footer") {
		this.toolbarsetTable.style.width = "100%";
	}
};

ToolbarSet.prototype.Create = function()
{
	var Set = DevEditToolbarSets[this.Name] ;

	for ( var x = 0 ; x < Set.length ; x++ ) {
		var cRow = this.toolbarsetTable.insertRow(this.toolbarsetTable.rows.length) ; //-1
		var cCell = cRow.insertCell(cRow.cells.length) ;
		if (x>0) { // insert horiz line
			cCell.className="body";
			//cCell.colspan = 2;
			cCell.bgColor = "#808080";
			cCell.innerHTML='<img src="images/1x1.gif" width="100%" height="1">';
			var cRow = this.toolbarsetTable.insertRow(this.toolbarsetTable.rows.length) ;
			var cCell = cRow.insertCell(cRow.cells.length) ;

			cCell.className="body";
			cCell.bgColor = "#FFFFFF";
			//cCell.colspan = 2;
			cCell.innerHTML='<img src="images/1x1.gif" width="100%" height="1">';
			var cRow = this.toolbarsetTable.insertRow(this.toolbarsetTable.rows.length) ;
			var cCell = cRow.insertCell(cRow.cells.length) ;
		}

		if (this.Name!="path" && this.Name!="footer") {
			cCell.className="de_TB_Toolbar";
		}

		var oToolbar = new Toolbar(cCell,this.Name) ;


		var prevItem='';
		for ( var j = 0 ; j < Set[x].length ; j++ ) {
			var sItem = Set[x][j] ;
			if (hideitems[sItem]=="1") {
				continue;
			}
			if ( sItem == '-'){
				if (prevItem!=='-') {
					oToolbar.AddSeparator() ;
				}
			} else {
				var oItem = E.toolbaritems.CreateItem( sItem ) ;
				if ( oItem ) {
					oToolbar.AddItem( oItem ) ;
				}
			}
			prevItem = sItem;
		}

		cCell.appendChild( oToolbar.DOMTable ) ;
		this.Toolbars[ this.Toolbars.length ] = oToolbar ;
	}
};

ToolbarSet.prototype.Redraw = function(e)
{
	try {
	if (e && this.Name==e) {
		return;
	}
	for ( var i = 0 ; i < this.Toolbars.length ; i++ ) {
		var oToolbar = this.Toolbars[i] ;
		for ( var j = 0 ; j < oToolbar.Items.length ; j++ ) {
			oToolbar.Items[j].Redraw(e);
		}
	}
	} catch (e){}
};

// Name: Toolbar class
// Info: Toolbar

var Toolbar = function(cCell,name)
{
	this.Items = new Array() ;
	this.Name = name;
	this.DOMTable = document.createElement( 'table' ) ;
	with ( this.DOMTable ) {
		cellPadding = 0 ;
		cellSpacing = 0 ;
		border = 0 ;
		nowrap = "nowrap";
	}

	this.DOMRow = this.DOMTable.insertRow(this.DOMTable.rows.length) ;
	this.firstItem = true;
};

Toolbar.prototype.AddItem = function( toolbarItem )
{
	if (this.firstItem) {
		var cCell = this.DOMRow.insertCell(this.DOMRow.cells.length) ;
		cCell.innerHTML='<img src="images/1x1.gif" width="2px" height="1px">';
		this.firstItem = false;
	}
	this.Items[ this.Items.length ] = toolbarItem ;
	toolbarItem.CreateInstance( this ) ;
};

Toolbar.prototype.AddSeparator = function()
{
	var oCell = this.DOMRow.insertCell(this.DOMRow.cells.length) ;
	oCell.unselectable = 'on' ;
	oCell.innerHTML = '<img class="de_seperator" src="'+deveditPath1+'/skins/'+E.skinName+'/seperator.gif" unselectable="on">' ;
};

// Name: Tab class
// Info:

var Tab = function( commandName, tooltip, imagepath, state )
{
	imageLoader.addImage("tabs",deveditPath1 + '/skins/' + E.skinName+ "/" +  'tabs.gif');
	this.Command	= commandName ;
	this.Tooltip	= tooltip ? tooltip : commandName ;
	this.mode 		= state;
};

TabOnClick = function()	{
	if (this.Tab.mode==OFF){
		oCommand	= E.commands.getCommand(this.Tab.Command);
		oCommand.execute();
		this.Tab.mode = CHOOSE;
		this.Tab.parentSheet.Redraw();
		return false ;
	}
};

Tab.prototype.CreateInstance = function( parentSheet )
{
	this.DOMDiv = document.createElement( 'div' ) ;

	this.DOMDiv.Tab	= this ;
	this.DOMDiv.id = garbage.generateId();
	garbage.add(this.DOMDiv.id);

	this.parentSheet = parentSheet;
	this.DOMDiv.onclick = TabOnClick;

	this.DOMDiv.innerHTML = '<div style="width:98px;height:22px" unselectable="on" class="de_tab" unselectable="on"></div>' ;

	this.DOMDiv.firstChild.style.background = "url("+imageLoader.getImage("tabs").src+")";
	this.DOMDiv.firstChild.style.backgroundRepeat = "no-repeat";
	this.DOMDiv.firstChild.style.overflow = "hidden";
	this.DOMDiv.firstChild.style.display = "block";
	try{
		this.DOMDiv.firstChild.style.backgroundPosition = "0px -"+(tab_pos[this.Command]*22)+"px";
	}catch(e){}
	
	var oCell = parentSheet.DOMRow.insertCell(-1) ;
	oCell.appendChild( this.DOMDiv ) ;
};

Tab.prototype.Redraw = function()
{
	switch ( this.mode ) {
		case ON :
			this.DOMDiv.firstChild.style.backgroundPosition = "0px -"+(tab_pos[this.Command+"up"]*22)+"px";
			break ;
		default :
			this.DOMDiv.firstChild.style.backgroundPosition = "0px -"+(tab_pos[this.Command]*22)+"px";
	}
};

// Name: Sheet class
// Info: Sets of tabs.

var Sheet = function()
{
	try{
		this.Items = new Array() ;
		this.DOMTable = document.createElement( 'table' ) ;
		this.DOMTable.className = 'de_TB_Toolbar' ;
		document.getElementById("footersheet").appendChild( this.DOMTable );
		with (this.DOMTable){
			cellPadding = 0 ;
			cellSpacing = 0 ;
			border = 0 ;
		}
		this.DOMRow = this.DOMTable.insertRow(-1) ;
	} catch (e){}
};

Sheet.prototype.Select = function( mode )
{
	for (var i=0;i<this.Items.length;i++){
		var t = this.Items[i];
		if (t.Command == mode){
			if (t.mode==OFF){
				t.mode = CHOOSE;
				t.parentSheet.Redraw();
			}
		}
	}
};

Sheet.prototype.AddItem = function( commandName, tooltip, imagepath, state)
{
	var oTab = new Tab(commandName, tooltip, imagepath, state);
	this.Items[ this.Items.length ] = oTab ;
	oTab.CreateInstance( this ) ;
};

Sheet.prototype.Redraw = function()
{
	for ( var j = 0 ; j < this.Items.length ; j++ ) {
		if (this.Items[j].mode == CHOOSE){
			this.Items[j].mode = ON;
		} else {
			this.Items[j].mode = OFF;
		}
		this.Items[j].Redraw() ;
	}
};

// Info: FontColor and Highlight classes

var ColorCommand = function(menu)
{
	this.Name = "colormenu";
	this.isLoad = false;
};

ColorCommand.prototype.execute = function(parentEl,offsetY)
{
	var p = parent.document.getElementById("colormenu"+E.InstanceName);
	if (!this.isLoad){
		var cf = parent.document.getElementById("color_frame"+E.InstanceName);
		cf.src = popup_color_src;
		this.isLoad = true;
	}
	var fr = parent.document.getElementById(E.InstanceName + 'main');
	var frameLeft =  this.getLeft(fr);
	var frameTop =  this.getTop(fr);

	this.oColorMenu = this.oItem;
	p.style.visibility="visible";
	var e = parentEl;
	p.style.zIndex=p.style.zIndex+100;
	p.style.left = this.getLeft(e)+frameLeft+(browser.IE?1:0)+"px";
	p.style.top = this.getTop(e) + e.offsetHeight-1+frameTop+(browser.IE?1:0)+"px";

	var f = p.firstChild;
	f.className="de_colorpopupcontainer";
};

ColorCommand.prototype.getMode = function()
{
	return OFF;
};

ColorCommand.prototype.getLeft = function(e)
{
	var nLeftPos = e.offsetLeft;
	var eParElement = e.offsetParent;
	while (eParElement != null) {
		nLeftPos += eParElement.offsetLeft;
		eParElement = eParElement.offsetParent;
	}
	return nLeftPos;
}

ColorCommand.prototype.getTop = function(e)
{
	var nTopPos = e.offsetTop;
	var eParElement = e.offsetParent;
	while (eParElement != null) {
		nTopPos += eParElement.offsetTop;
		eParElement = eParElement.offsetParent;
	}
	return nTopPos;
}

// CustomcolorCommand
var CustomColorCommand = function(){};

CustomColorCommand.prototype.execute = function(jsfunc,p){
	try{
		buffer[E.mode].saveHistory();
		eval("parent."+jsfunc+"('"+p+"');");
	} catch(e){}
	E.Focus();
};

CustomColorCommand.prototype.getMode = function(){
	return OFF ;
};

// FontcolorCommand
var FontColorCommand = function()
{
	this.Name = "forecolor";
};

FontColorCommand.prototype.execute = function(pStr,range)
{
	try{
		var p = pStr?pStr:null;
		buffer[E.mode].saveHistory();
		if (range) {
			range.execCommand( this.Name, false, p );
		} else {
			E._frame.execCommand( this.Name, false, p );
		}
	} catch(e){}
	E.Focus();
};

FontColorCommand.prototype.getMode = function()
{
	try
	{
		if ( !E._frame.queryCommandEnabled( this.Name ) ) {
			return DISABLED ;
		} else {
			return E._frame.queryCommandState( this.Name ) ? ON : OFF ;
		}
	}
	catch ( e )
	{
		return OFF ;
	}
};


// HighlightCommand
var HighlightCommand = function()
{
	if (browser.NS)this.Name = "hilitecolor";
	else this.Name = "BackColor";
};

HighlightCommand.prototype.execute = function(pStr,range)
{
	try{
		var p = pStr?pStr:null;
		buffer[E.mode].saveHistory();
		if (range) {
			range.execCommand( this.Name, false, p );
		} else {
			E._frame.execCommand( this.Name, false, p );
		}
	} catch(e){}
	E.Focus();
};

HighlightCommand.prototype.getMode = function()
{
	try
	{
		if ( !E._frame.queryCommandEnabled( this.Name ) ) {
			return DISABLED ;
		} else {
			return E._frame.queryCommandState( this.Name ) ? ON : OFF ;
		}
	}
	catch ( e )
	{
		return OFF ;
	}
};


// Name: Dialog commands
// Info: represent dialog window.

var DialogCommand = function(name, srcfile, w, h, checkTag, selTag, elementType)
{
	this.Name = name;
	this.srcfile = srcfile;
	this.width = w;
	this.checkTag = checkTag || false;
	this.selTag = selTag || false;
	this.elType = elementType || false;
	this.height = h;
	this.mode = "not found";
};

DialogCommand.prototype.execute = function()
{
	if (this.getMode()==DISABLED)return;
	var add="";
	switch (this.Name.toLowerCase()){
		case "image":
			add = "scrollbars=yes";
			break;
		default:
			add = "scrollbars=0";
	}

	if (this.selTag) {
		if (E.GetSelectedElement()) {
			el = E.GetSelectedElement();
			if (el.tagName==this.selTag&&(this.elType?this.elType.indexOf(el.type.toUpperCase())>=0:true)) {
				src = "Modify"+this.Name;
			} else {
				src = "Insert"+this.Name;
			}
		} else {
			src = "Insert"+this.Name;
		}
	} else {
		src = this.Name;
	}
	var left = Math.round((screen.availWidth-this.width) / 2);
	var top = Math.round((screen.availHeight-this.height) / 2 );

	sizestr = 'width='+this.width +',height='+this.height;
	if (this.Name=="MediaManager") {
		searchstr="";
	} else {
		searchstr="";
	}
	if (browser.NS){
		var opt = "status=no,location=no,menubar=no,resizable=yes,toolbar=no,dependent=yes,dialog=yes,minimizable=no,modal=yes,alwaysRaised=yes";
	} else {
		var opt = "status=yes";
	}

	var rnd = Math.floor(Math.random()*1000);
	var newWin = window.open(HTTPStr.toLowerCase() + '://' + URL +"/"+ScriptName+'?rnd='+rnd+'&ToDo='+src+searchstr+'&'+this.srcfile,'_blank',opt+","+add+","+sizestr+",left="+left+",top="+top,true);
	if (!newWin)
	{
		alert("Your web browsers popup blocker appears to have blocked the new window. Please disable your popup blocker and try again. You may see the popup blocker appear at the top of your web browser in a yellow bar. You can click the yellow bar for more more options.");
	}	
};

DialogCommand.prototype.isFlash = function(x){
	var cn;
	if (browser.IE) {
		cn = x.getAttribute("className");
	} else {
		cn = x.getAttribute("class");
	}
	if (cn=="de_flash_file") {
		return true;
	} else {
		return false;
	}
};

DialogCommand.prototype.isMedia = function(x)
{
	var cn;
	if (browser.IE) {
		cn = x.getAttribute("className");
	} else {
		cn = x.getAttribute("class");
	}
	if (cn=="de_media_file") {
		return true;
	} else {
		return false;
	}
};

DialogCommand.prototype.getMode = function()
{
	this.mode="not found";

	var elT = this.elType;
	if (this.checkTag) {
		try{
			var tags = this.checkTag.split("|");
			var types="";
			var r = DISABLED;
			if (elT) {
				types=elT.split("|");
			}

			for (var i=0; i<tags.length;i++) {
				switch (tags[i]){
					case "empty":
						if (E.GetType()!="Control" && E.GetSelection()=="") {
							return OFF;
						}
						break;
					case "text":
						if (E.GetType()=="Control") {
							return DISABLED;
						}
						if (E.GetSelection()!="") {
							return OFF;
						}
						break;
					case "flash":
						var x = E.GetSelectedElement();
						if (x && this.isFlash(x)) {
							this.mode="found";return OFF;
						} else {
							return DISABLED;
						}
						break;
					case "media":
						var x = E.GetSelectedElement();
						if (x && this.isMedia(x)) {
							this.mode="found";return OFF;
						} else {
							return DISABLED;
						}
						break;
					case "link":
						if (E.GetType()=="Control"){
							var x = E.GetSelectedElement();
							if (x && x.tagName && x.tagName=="IMG") {
								return OFF;
							}
							var  x = E.parentNode("A");
							if (!x) {
								return DISABLED;
							}
							if (x.href) {
								if(x.href.indexOf("mailto:")==-1) {
									return OFF;
								} else {
									return DISABLED;
								}
							}
						} else	if (E.GetSelection()!="") {
							var x = E.GetSelectedElement();
							if (!x) {
								return OFF;
							}
							if (x.href&&x.href.indexOf("mailto:")==-1) {
								return OFF;
							}
						}
						break;
					case "emaillink":
						if (E.GetType()=="Control"){
							var x = E.GetSelectedElement();
							if (x && x.tagName && x.tagName=="IMG") {
								return OFF;
							}
							var x = E.parentNode("A");
							if (!x) {
								return DISABLED;
							}
							if (x.href) {
								if(x.href.indexOf("mailto:")>=0) {
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
							if (x.href) {
								if (x.href.indexOf("mailto:")>=0) {
									return OFF;
								} else {
									return DISABLED;
								}
							}
						}
						break;

					default:
						var x = E.GetSelectedElement();
						if (!x) {
							x = Selection.parentNode(tags[i]);
						}
						if (x) {
							if (x && x.getAttribute("className") && (x.getAttribute("className")=="de_flash_file" || x.getAttribute("className")=="de_media_file")) {
								return DISABLED;
							}
							if (types==""&&x.tagName&&tags[i].toUpperCase()==x.tagName.toUpperCase()) {
								this.mode="found";
								return OFF;
							} else if (types!="") {
								for (var j=0; j<types.length;j++){
									if (x.type&&types[j].toUpperCase()==x.type.toUpperCase()) {
										this.mode="found";
										return OFF;
									}
								}
							}
						}
				}
			}
			return DISABLED;
		} catch(e){}
	} else {
		aed = E._frame;
		try
		{
			if ( !aed.queryCommandEnabled(this.Name) ) {
				return DISABLED ;
			} else {
				return aed.queryCommandState(this.Name) ? ON : OFF ;
			}
		}
		catch ( e )
		{
			return OFF ;
		}
	}
};

// Name: Inner commands
// Info: Commands that supports by editor component.

var InnerCommand = function(name)
{
	this.Name = name.toLowerCase();
};

InnerCommand.prototype.execute = function(pStr)
{
	
	var p = pStr?pStr:null;
	try{
		buffer[E.mode].saveHistory();
		if (browser.IE && (this.Name=="insertunorderedlist" || this.Name=="insertorderedlist") && E._frame.selection.createRange().htmlText && (( this.Name=="insertorderedlist" && E.commands.getCommand("InsertUnorderedList").getMode()!==ON) || (this.Name=="insertunorderedlist" && E.commands.getCommand("InsertOrderedList").getMode()!==ON))){
			if (this.getMode()==OFF){
				if (this.Name=="insertunorderedlist"){
					u="ul";
				} else{
					u="ol";
				}
				sln = E._frame.selection.createRange().htmlText;
				sptext = "<"+u+"  id=_de_temp_element><li>" + sln.replace(/<br\/?>/gi,"</li><li>").replace(/<\/?p>/gi,"") + "</li></"+u+">";
				sptext = sptext.replace(/<li><\/li><\/ul>/gi,"</ul>");
				sptext = sptext.replace(/<li><\/li><\/ol>/gi,"</ol>");
				E._inserthtml(sptext);
			} else {
				sln = E._frame.selection.createRange().htmlText;
				p = E._frame.selection.createRange().parentElement();
				p.parentNode.removeChild(p);
				sptext = sln.replace(/<\/li>/gi,"<br/>").replace(/<\/?[o|u]l>/gi,"").replace(/<li>/gi,"");
				E._inserthtml(sptext.replace(/<br\/?>$/,""));
			}	

		} else {

		if ((browser.IE)&& (this.Name == "inserthorizontalrule")) {
			E._frame.execCommand( this.Name, false );
		} else {
			switch ( this.Name )
			{
				case 'paste' :
					try			{ E._frame.execCommand( 'Paste', null, true ) ; }
					catch (e)	{ alert(security_error) ; }
					break ;
				case 'cut' :
					try			{ E._frame.execCommand( 'Cut', null, true ) ; }
					catch (e)	{ alert(security_error) ; }
					break ;
				case 'copy' :
					try			{ E._frame.execCommand( 'Copy', null, true ) ; }
					catch (e)	{ alert(security_error) ; }
					break ;
				default:
					E._frame.execCommand( this.Name, false, p );					
			}
}

		}
		E.commands.getCommand("Save").execute("no");

		if ((browser.IE)&&(this.Name.toLowerCase()=="paste")) {
			ToolbarSets.Redraw();
		}
		if (this.Name == "absoluteposition") {
			E._frame.execCommand("2D-Position",false, true);
		}
	} catch(e){}
	E.Focus();
};

InnerCommand.prototype.getMode = function()
{
	try	{
		if (browser.IE){
			if (this.Name=="copy") {
				if (E.GetType()=="Text" && E.GetSelection()=="") return DISABLED;
			}
		}

		// normal behavior
		if (!E._frame.queryCommandEnabled( this.Name ) ) {
			return DISABLED ;
		} else {
			return E._frame.queryCommandState( this.Name ) ? ON : OFF ;
		}
	}
	catch ( e )
	{
		return OFF ;
	}
};

// Name: Custom commands
// Info: User commands

var CustomCommand = function(jsfunc)
{
	this.jsfunc = jsfunc;
};

CustomCommand.prototype.execute = function()
{
	if (this.jsfunc) {
		if (this.jsfunc.indexOf("(")==-1){
			eval("parent."+this.jsfunc+"();")
		} else {
			eval("parent."+this.jsfunc+";")
		}	
	}
};

CustomCommand.prototype.getMode = function()
{
	return OFF ;
};

// Name: Label class
// Info: Label in the toolbar

var Label = function( text, icon)
{
	this.Label		= text;
	this.IconPath	= deveditPath1 + '/skins/' + E.skinName+ "/" + icon.toLowerCase() + '.gif' ;
};

Label.prototype.CreateInstance = function( parentToolbar )
{
	this.DOMDiv = document.createElement( 'div' ) ;
	this.DOMDiv.className		= 'de_TB_Button_Off' ;

	this.DOMDiv.innerHTML =
		'<table width="100" cellspacing="0" cellpadding="0" border="0" unselectable="on">' +
			'<tr>' +
				'<td class="Button" unselectable="on"><img src="' + this.IconPath + '" unselectable="on"></td>' +
				'<td class="de_TB_Text" unselectable="on">' + this.Label + '</td>' +
			'</tr>' +
		'</table>' ;

	var oCell = parentToolbar.DOMRow.insertCell(parentToolbar.DOMRow.cells.length) ;
	oCell.appendChild( this.DOMDiv ) ;
};

Label.prototype.Redraw = function(e){}

// Name: PopupSet class
// Info: Sets of popups.

var PopupSet = new Object() ;

PopupSet.Items = new Array() ;
PopupSet.isInitial = false;

PopupSet.CreatePopup = function(name)
{
	this.Items[name] = new Popup('',name);
};

PopupSet.CloseAll = function()
{
	cp = parent.document.getElementById("colormenu"+E.InstanceName);
	if (cp) {
		cp.style.visibility="hidden";
	}

	for (var i in PopupSet.Items )	{
		if (i!=="clone") {
			if (browser.IE){
				PopupSet.Items[i].popup.hide();
				PopupSet.Items[i].lastResult=-1;
				PopupSet.Items[i].isOpen = false;
			} else {	
				var p = parent.document.getElementById(PopupSet.Items[i].id);
				PopupSet.Items[i].isOpen = false;
				PopupSet.Items[i].lastResult=-1;
				
				if (p) {
					p.style.visibility="hidden";
				}
			}
		}
	}
};

PopupSet.Redraw = function(pname)
{
	var i;
	if ((!pname)||((pname!=="fontcolor") && (pname!=="highlight") && (pname.indexOf("_custom")==-1))) {
		cp = parent.document.getElementById("colormenu"+E.InstanceName);
		if (cp) {
			cp.style.visibility="hidden";
		}
	}

	for ( i in PopupSet.Items )  {
		if (i!=="clone") {
			if (PopupSet.Items[i].name!==pname&&browser.NS){
				var p = parent.document.getElementById(PopupSet.Items[i].id);
				if (p) {
					p.style.visibility="hidden";
				}
			}
			PopupSet.Items[i].Redraw() ;
		}
	}
};

// Name: selection class
// Info: Selection object.

var Selection = new Object();

Selection.parentTags = new Array();
Selection.currentNode = null;
Selection.changed = false;

Selection.set = function()
{
	aed = E._frame;

	if (this.currentNode!==E.selectedtag) {
		E.selectedtag = null;
	}

	oContainer = false;

	if (browser.IE) {
		try{
			sel = aed.selection;
			range = sel.createRange();
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
		} catch (e) {}
	} else {
		try {
			//E.Focus();
			oSel = E._window.getSelection();
			range = oSel.getRangeAt(0);
			oContainer = range;
			var p = range.commonAncestorContainer;
			if (!range.collapsed && range.startContainer == range.endContainer && range.startOffset - range.endOffset <= 1 && range.startContainer.hasChildNodes()){
				p = range.startContainer.childNodes[range.startOffset];
				oContainer = p;
			}

			while (p.nodeType == 3) {
				p = p.parentNode;
				oContainer = p;
			}
		}
		catch (e) {
			oContainer = null;
		}
	}

	if (!oContainer||!oContainer.tagName){
		oContainer = aed.body;
	}

	if (this.currentNode == oContainer) {
		this.changed = false;
		return;
	} else {	
		this.changed = true;
	}
	
	//editable_area_selected = false;
	this.parentTags = new Array();
	this.currentNode = oContainer;
	while ( oContainer ) {
		this.parentTags.unshift(oContainer);
		oContainer = oContainer.parentNode ;
	}
};

Selection.parentNode = function(tag)
{
	var i = this.parentTags.length;
	if (i) {
		while (i--){
			if (this.parentTags[i].id && this.parentTags[i].id.substr(0,7)=="de_wrap") {
				return false;
			}
			if (this.parentTags[i].tagName && this.parentTags[i].tagName.toLowerCase()==tag.toLowerCase()) {
				return this.parentTags[i];
			}
		}
	}
	return false;
};

// Name: ToolbarItems class
// Info: Item in the Toolbar

var ToolbarItems = function()
{
	this.Items = new Object() ;
	return this;
};

ToolbarItems.prototype.CreateItem = function(Name)
{
	var item;

	var tl="";
	var titlesecurity="";
	if (browser.NS){
		if (!E.XPCOM){
			tl = "Feature disabled. See help for details";
			titlesecurity="FireFox Restrictions";
		}
	}
	
	ab = -1;
	if (additionalButtons)
	for (var i=0;i<additionalButtons.length;i++){
		if (additionalButtons[i] && Name==additionalButtons[i][0]){
			ab = i;
			break;
		}
	}

	if (ab!==-1){
		item = new ToolbarButton( '_custom_'+ additionalButtons[ab][0] , additionalButtons[ab][0],additionalButtons[ab][1], additionalButtons[ab][2],additionalButtons[ab][3]);
		this.Items["_custom_" + additionalButtons[ab][0]] = item;
		return item ;
	}

	ab = -1;
	if (additionalColorButtons)
	for (var i=0;i<additionalColorButtons.length;i++){
		if (additionalColorButtons[i] && Name==additionalColorButtons[i][0]){
			ab = i;
			break;
		}
	}

	if (ab!==-1){
		item = new ToolbarButtonDrop( '_custom_'+additionalColorButtons[ab][0], additionalColorButtons[ab][1], additionalColorButtons[ab][2], additionalColorButtons[ab][3]) ;
		this.Items["_custom_" + additionalColorButtons[ab][0]] = item;
		return item ;
	}
	
	switch ( Name ){
		case 'Paragraph':item = new ToolbarButton( 'Paragraph' , 'Paragraph') ;break;
		case 'Previewlabel':item = new Label( 'Preview Mode' , 'Preview') ;break;
		case 'Save':item = new ToolbarButton( 'Save' , 'Save') ;break;
		case 'Fullscreen': item	= new ToolbarButton( 'Fullscreen' , 'Fullscreen mode' ) ; break;
		case 'Cut': item	= new ToolbarButton( 'Cut',tl?tl:'Cut') ; break;
		case 'Copy': item	= new ToolbarButton( 'Copy',tl?tl:'Copy') ; break;
		case 'Paste': item	= new ToolbarButtonDrop( 'Paste',tl?tl:'Paste' ) ; break;
		case 'Pastetext': item	= new ToolbarButtonDrop( 'Pastetext','Paste as Plain Text' ) ; break;
		case 'Spellcheck': item = new ToolbarButton( 'Spellcheck', 'Check spelling' ) ; break;
		case 'Bold': item	= new ToolbarButton( 'Bold'		) ; break;
		case 'Italic': item	= new ToolbarButton( 'Italic'	) ; break;
		case 'Underline': item	= new ToolbarButton( 'Underline') ; break;
		case 'Strikethrough': item	= new ToolbarButton( 'Strikethrough') ; break;
		case 'OrderedList': item	= new ToolbarButton( 'InsertOrderedList' , 'Insert ordered list'	) ; break;
		case 'UnorderedList': item	= new ToolbarButton( 'InsertUnorderedList', 'Insert unorderred list'	) ; break;
		case 'Indent': item	= new ToolbarButton( 'Indent', 'Increase Indent'	) ; break;
		case 'Outdent': item	= new ToolbarButton( 'Outdent', 'Decrease Indent'	) ; break;
		case 'JustifyLeft': item = new ToolbarButton( 'JustifyLeft', 'Align Left'	 ) ; break;
		case 'JustifyCenter': item	= new ToolbarButton( 'JustifyCenter', 'Align Center' ) ; break;
		case 'JustifyRight': item	= new ToolbarButton( 'JustifyRight', 'Align Right'	 ) ; break;
		case 'JustifyFull': item	= new ToolbarButton( 'JustifyFull', 'Justify'	 ) ; break;
		case 'SourceMode': item	= new ToolbarButton( 'SourceMode'	 ) ; break;
		case 'NewPage': item	= new ToolbarButton( 'NewPage', 'Clear HTML code'	 ) ; break;
		case 'Undo': item	= new ToolbarButton( 'Undo'	 ) ; break;
		case 'Redo': item	= new ToolbarButton( 'Redo'	 ) ; break;
		case 'UndoSource': item	= new ToolbarButton( 'Undo'	,'Undo' ) ; break;
		case 'RedoSource': item	= new ToolbarButton( 'Redo'	, 'Redo' ) ; break;
		case 'Print': item	= new ToolbarButton( 'Print'	 ) ; break;
		case 'SelectAll': item	= new ToolbarButton( 'SelectAll', 'Select all'	 ) ; break;
		case 'RemoveFormat': item	= new ToolbarButton( 'RemoveFormat', 'Remove Text Formatting'	 ) ; break;
		case 'SuperScript': item	= new ToolbarButton( 'SuperScript', 'Superscript'	 ) ; break;
		case 'SubScript': item	= new ToolbarButton( 'SubScript', 'Subscript'	 ) ; break;
		case 'CreateLink': item	= new ToolbarButton( 'CreateLink', 'Insert link' ) ; break;
		case 'Unlink': item	= new ToolbarButton( 'Unlink', 'Remove link' ) ; break;
		case 'HR': item	= new ToolbarButton( 'InsertHorizontalRule', 'Insert Horizontal Line' ) ; break;
		case 'Clearcode': item	= new ToolbarButton( 'Clearcode', 'Clear code'	 ) ; break;
		case 'Help': item	= new ToolbarButton( 'Help', 'Help' ) ; break;
		case 'Fontname': item	= new Combo( 'Fontname' ,'Font name' , mFontname,"de_Combo120","font-family")  ; break;
		case 'Fontsize': item	= new Combo( 'Fontsize' ,'Font size' , mFontsize , "de_Combo50" , "text-align:center;font-size") ; break;
		case 'Formatblock': item	= new Combo( 'Formatblock' ,'Format' , mFormatblock, "de_Combo80","see_array_2") ; break;
		case 'Styles': item	= new StyleCombo( 'Style' ,'Style' , "de_Combo90") ; break;
		case 'Form': item	= new ToolbarButton( 'Form', 'Form Functions' ) ; break;
		case 'Insertchars': item	= new ToolbarButton( 'Insertchars', 'Insert character' ) ; break;
		case 'Inserttextbox': item	= new ToolbarButton( 'Inserttextbox', 'Insert TextBox' ) ; break;
		case 'Table': item	= new ToolbarButton( 'Table', 'Table Functions' ) ; break;
		case 'Showborders': item	= new ToolbarButton( 'Showborders', 'Show borders' ) ; break;
		case 'Fontcolor': item	= new ToolbarButtonDrop( 'Fontcolor', 'Font Color' ) ; break;
		case 'Highlight': item	= new ToolbarButtonDrop( 'Highlight', 'Highlight' ) ; break;
		case 'CreateEmailLink': item	= new ToolbarButton( 'CreateEmailLink', 'Create Email Link' ) ; break;
		case 'Anchor': item	= new ToolbarButton( 'Anchor', 'Insert / Modify Anchor' ) ; break;
		case 'Findreplace': item	= new ToolbarButton( 'Findreplace', 'Find and replace' ) ; break;
		case 'Pageproperties': item	= new ToolbarButton( 'Pageproperties', 'Modify Page Properties' ) ; break;
		case 'Toggleposition': item	= new ToolbarButton( 'Toggleposition', "Toggle Absolute Positioning" ) ; break;
		case 'Image': item	= new ToolbarButton( 'Image', 'Insert / Modify Image' ) ; break;
		case 'Flash': item	= new ToolbarButton( 'Flash', "Insert / Modify flash" ) ; break;
		case 'Media': item	= new ToolbarButton( 'Media', 'Insert / Modify Media file' ) ; break;
		case 'File': item	= new ToolbarButton( 'File', "Link to file" ) ; break;

		case 'Custominsert': item	= new ToolbarButton( 'Custominsert', "Insert Custom HTML" ) ; break;
		case 'Emptylabel':item = new Label( '' , '') ;break;
		case 'GeckoXPConnect':item = new LinkLabel('GeckoXPConnect',titlesecurity) ;break;
		case 'EditorMode':item = new LinkLabel('EditorMode','Switch mode') ;break;
		case 'Path':item = new Path() ;break;
		case 'Edittag':item = new LinkLabel('Edittag',"Edit Tag") ;break;
	}
	var sName = Name;
	if ( this.Items[Name] ) {
		sName=Name+"_add";
	}
	this.Items[sName] = item;
	return item ;
};

ToolbarItems.prototype.GetItem = function(Name)
{
	return this.Items[Name];
};

// Name: Commands class
// Info: All DevEdit commands.

var Commands = function()
{
	this.allCommands = new Object() ;
	var c = this.allCommands;

	for (var i=0; i<additionalButtons.length;i++) {
		c["_custom_"+additionalButtons[i][0]] = new CustomCommand(additionalButtons[i][4]);
	}

	for (var i=0; i<additionalColorButtons.length;i++) {
		c["_custom_"+additionalColorButtons[i][0]] = new CustomColorCommand(additionalColorButtons[i][3]);
		c["_custom_"+additionalColorButtons[i][0]+"down"]	= new ColorCommand("colormenu.php");
	}

	// load all commands
	c["ReplaceWord"]	= new ReplaceWord();
	c["NewTag"]	= new NewTag();	
	c["Paragraph"] = new ShowParagraphCommans();
	c["AddToDictionary"] = new AddToDictionary();
	c["Ignore"] = new Ignore();
	c["IgnoreAll"] = new IgnoreAll();
	c["Save"]	= new Save();
	c["Fullscreen"]	= new FullscreenCommand();
	c["Cut"]		= new InnerCommand("Cut");
	c["Copy"]		= new InnerCommand("Copy");
	if (is_forcePasteWord) {
		c["Paste"]		= new PasteFromMSWord();
	} else {
		if (is_forcePasteAsText) {
			c["Paste"]		= new PasteText();
		} else {	
			c["Paste"]		= new InnerCommand("Paste");
		}	
	}
	c["Pastetext"]	= new PasteText();
	c["Pastedown"]	= new PopupCommand("Paste");
	c["Pastefrommsword"]	= new PasteFromMSWord();
	c["Bold"]			= new InnerCommand("Bold",66);
	c["Italic"]		= new InnerCommand("Italic",73);
	c["Underline"]	= new InnerCommand("Underline",85);
	c["Strikethrough"]	= new InnerCommand("Strikethrough");
	if (useSmartHistory == "1"){
	   c["Undo"]		= new Undo("edit");
	   c["Redo"]		= new Redo("edit");
	   c["UndoSource"]	= new Undo("source");
	   c["RedoSource"]	= new Redo("source");
	} else {
	   c["Undo"]		= new InnerCommand("undo");
	   c["Redo"]		= new InnerCommand("redo");
	   c["UndoSource"]	= new InnerCommand("undo");
	   c["RedoSource"]	= new InnerCommand("redo");
	}   
	c["Spellcheck"]		= new Spellcheck;
	c["Print"]		= new InnerCommand("Print");
	c["SelectAll"]	= new InnerCommand("SelectAll");
	c["RemoveFormat"]	= new InnerCommand("RemoveFormat");
	c["SuperScript"]	= new InnerCommand("SuperScript");
	c["SubScript"]	= new InnerCommand("SubScript");
	c["JustifyLeft"]	= new InnerCommand("JustifyLeft");
	c["JustifyCenter"]= new InnerCommand("JustifyCenter");
	c["JustifyRight"]	= new InnerCommand("JustifyRight");
	c["JustifyFull"]	= new InnerCommand("JustifyFull");
	c["Indent"]				= new InnerCommand("Indent");
	c["Outdent"]				= new InnerCommand("Outdent");
	c["InsertOrderedList"]	= new InnerCommand("InsertOrderedList");
	c["InsertUnorderedList"]	= new InnerCommand("InsertUnorderedList");
	c["InsertHorizontalRule"]	= new InnerCommand("InsertHorizontalRule");
	c["Anchor"]				= new DialogCommand("Anchor","anchor.php",400,162,false,"A");
	c["SourceMode"]	= new SourceCommand();
	c["EditMode"]		= new EditCommand();
	c["PreviewMode"]	= new PreviewCommand();
	c["Fontname"]	= new SetStyle("font-family","fontFamily");
	c["Fontsize"]	= new SetStyle("font-size","fontSize");
	if (browser.IE) {
		c["Formatblock"]	= new SetFormatBlock();
	} else {
		c["Formatblock"]	= new InnerCommand("Formatblock");
	}
	c["Clearcode"]	= new ClearCodeCommand();
	c["Help"]			= new DialogCommand("ShowHelp", "", 500, 400);
	c["Form"]	= new PopupCommand("Form");
	c["Insertform"]	= new DialogCommand("InsertForm","",400,223);
	c["Modifyform"]	= new DialogCommand("ModifyForm","",400,223,"FORM");
	c["Inserttextfield"]	= new DialogCommand("TextField","",400,230,"empty|INPUT","INPUT","TEXT|PASSWORD");
	c["Inserttextarea"]	= new DialogCommand("TextArea","",400,230,"empty|TEXTAREA","TEXTAREA");
	c["Inserthidden"]		= new DialogCommand("Hidden","",350,192,"empty|INPUT","INPUT","HIDDEN");
	c["Insertbutton"]		= new DialogCommand("Button","",400,192,"empty|INPUT","INPUT","BUTTON|RESET|SUBMIT");
	c["Insertcheckbox"]	= new DialogCommand("Checkbox","",400,192,"empty|INPUT","INPUT","CHECKBOX");
	c["Insertradio"]		= new DialogCommand("Radio","",400,192,"empty|INPUT","INPUT","RADIO");
	c["Insertselect"]		= new DialogCommand("Select","",520,350,"empty|SELECT","SELECT");
	c["Inserttextbox"]	= new InsertTextBox("InsertTextbox");
	if (window.InsertColumnRight) {
		c["Table"]	= new PopupCommand("Table");
		c["Inserttable"]	= new DialogCommand("InsertTable","",470,293);
		c["Quicktable"]	= new DialogCommand("QuickTable","",700,500);
		c["Modifytable"]	= new DialogCommand("ModifyTable","",450,262,"TABLE");
		c["Modifycell"]	= new DialogCommand("ModifyCell","modify_cell.html",400,234,"TD");
		c["Insertcolumnright"]	= new InsertColumnRight();
		c["Insertcolumnleft"]	= new InsertColumnLeft();
		c["Insertrowabove"]	= new InsertRowAbove();
		c["Insertrowbelow"]	= new InsertRowBelow();
		c["Deleterow"]	= new DeleteRow();
		c["Deletecolumn"]	= new DeleteColumn();
		c["Increasecolumnspan"]	= new IncreaseColumnSpan();
		c["Decreasecolumnspan"]	= new DecreaseColumnSpan();
		c["Increaserowspan"]	= new IncreaseRowSpan();
		c["Decreaserowspan"]	= new DecreaseRowSpan();
	}
	c["Showborders"]	= new ShowBordersCommand();
	c["Fontcolor"]	= new FontColorCommand();
	c["Fontcolordown"]	= new ColorCommand("colormenu.php");
	c["Highlight"]	= new HighlightCommand();
	c["Highlightdown"]	= new ColorCommand("colormenu.php");
	c["Findreplace"]	= new DialogCommand("Findreplace","",385,165);
	c["Style"]	= new SetStyle();
	c["Image"]	= new DialogCommand("MediaManager","obj=image",655,500,'empty|IMG');
	c["Flash"]	= new DialogCommand("MediaManager","obj=flash",655,500,'empty|flash');
	c["Media"]	= new DialogCommand("MediaManager","obj=media",655,500,'empty|media');
	c["CreateLink"]	= new DialogCommand("FileManager","obj=link",500,400,"link|IMG");
	c["CreateEmailLink"] = new DialogCommand("FileManager","obj=email",500,400,"emaillink|IMG");
	c["MoreColors"]	= new DialogCommand("MoreColors","",420,370);
	c["Custominsert"]	= new DialogCommand("Custominsert","",450,297);
	c["Contextmenu"] = new PopupCommand("contextmenu");
	c["Spellmenu"] = new PopupCommand("spellmenu");
	c["Sourcetag"] = new PopupCommand("sourcetag");

	c["GeckoXPConnect"] = new DialogCommand("ShowHelp","sectionid=firefox", 500, 400);
	c["EditorMode"] = new EditorModeCommand();

	c["Edittag"]	= new DialogCommand("Edittag","",385,230);

	// different between IE and Moz commands
	if (browser.IE) {
		c["Toggleposition"]	= new InnerCommand("Absoluteposition");
		c["Pageproperties"]	= new DialogCommand("Pageproperties","",400,410);
		c["Insertchars"]	= new DialogCommand("Insertchars","",420,450);
	} else {
		c["Toggleposition"]	= new TogglePosition("Absoluteposition");
		c["Pageproperties"]	= new DialogCommand("Pageproperties","",400,420);
		c["Insertchars"]	= new DialogCommand("Insertchars","",420,400);
	}
	return this;
};

Commands.prototype.getCommand = function(commandName)
{
	return this.allCommands[commandName];
}

function SetCookie(name,value,expires,path,domain,secure)
{
	parent.document.cookie = name + "=" + escape(value) + ((expires) ? ";expires=" + expires.toGMTString() : "") + ((path) ? "; path=" + path : "") + ((domain) ? "; domain=" + domain : "") + ((secure) ? "; secure" : "");
}

function getCookieVal (offset)
{
	var endstr = parent.document.cookie.indexOf(";", offset);
	if (endstr == -1) {
		endstr = parent.document.cookie.length;
	}
	return unescape(parent.document.cookie.substring(offset, endstr));
}

function GetCookie (name)
{
	var arg = name + "=";
	var alen = arg.length;
	var clen = parent.document.cookie.length;
	var i = 0;
	while (i < clen) {
		var j = i + alen;
		if (parent.document.cookie.substring(i, j) == arg) {
			return getCookieVal (j);
		}
		i = parent.document.cookie.indexOf(" ", i) + 1;
		if (i == 0) {
			break;
		}
	}
	return null;
}

// EditorModeCommand command
var EditorModeCommand = function()
{
	this.Name = 'EditorModeCommand' ;
};

EditorModeCommand.prototype.changemode = function(str,par, val){
	idx=str.indexOf(par+"=",1);
	idx2=str.indexOf("&",idx+1);
	if ((idx>=0)&&(idx2==-1)) {
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

EditorModeCommand.prototype.execute = function()
{
	if (E.toolbarpos < DevEditToolbars.length-1) {
		E.toolbarpos++;
	} else {
		E.toolbarpos=0;
	}
	mode = DevEditToolbars[E.toolbarpos];

	parent.document.getElementById(E.InstanceName+"_mode").value = mode;
	var new_href = this.changemode(E.href, "mode", mode);

	var expDate = new Date;
	expDate.setTime (expDate.getTime() + (24*60*60*1000));
	SetCookie("de_mode",mode,expDate);

	E.commands.getCommand('Save').execute("no");

	var y;
	if(browser.IE) {
		y = parent.document.body.scrollTop;
		SetCookie("scroll_top",y,expDate);
	}
	var x = new_href.replace(/&/gi,"&\n");
	window.location.href = new_href;
};

EditorModeCommand.prototype.getMode = function(){}

var Api = new Object() ;

Api.GetParent = function( element, parentTagName )
{
	var e = element.parentNode ;
	while ( e ) {
		if (e.nodeName == parentTagName) {
			return e ;
		}
		e = e.parentNode ;
	}
	return false;
};

var Position = function()
{
	this.Name = 'postion';
};

Position.prototype.getTop = function(e)
{
	var nTopPos = e.offsetTop;
	var eParElement = e.offsetParent;
	while (eParElement != null) {
		nTopPos += eParElement.offsetTop;
		eParElement = eParElement.offsetParent;
	}
	return nTopPos;
};

Position.prototype.getTopToElement = function(e,t)
{
	var nTopPos = e.offsetTop;
	var eParElement = e.offsetParent;
	while (eParElement != null && eParElement!=t ) {
		nTopPos += eParElement.offsetTop;
		eParElement = eParElement.offsetParent;
	}
	return nTopPos;
};

Position.prototype.getLeft = function(e)
{
	var nLeftPos = e.offsetLeft;
	var eParElement = e.offsetParent;
	while (eParElement != null) {
		nLeftPos += eParElement.offsetLeft;
		eParElement = eParElement.offsetParent;
	}
	return nLeftPos;
};

var edit_begin_tags	= new Array();
var edit_end_tags	= new Array();

var bth	= new Array();
var eth	= new Array();

// Add wrap for restricted area - edit mode
function addWrap(c,ignore)
{
	if(ignore) {
		return c;
	}
	//code = removeWrap(code);
	c1 = begin_edit_expr;
	c2 = end_edit_expr;

	var b = false;
	if (E.mode=="edit") {
		b = c.match(BodyContents);
		if (b) {
			c = b[2];
		}
	}

	edit_begin_tags	= c.match(begin_edit_expr);
	edit_end_tags	= c.match(end_edit_expr);
	var count = 0;

	c = c.replace(c1,function(){count++; return edit_begin_tags[count-1]+"<div id=de_wrap_div"+count+">";});
	c = c.replace(c2,"</div>$1");

	c = addBlock(c);

	c = c.replace(/(<div id=de_wrap_div\d+><\/div>)/gi,function(s1,s2){return s2.replace(/<\/div>/gi,"&nbsp;</div>");});

	if (b) {
		return b[1] + c + b[3];
	} else {
		return c;
	}
}

function addblock(s,e){

	if (useSmartHistory=="1"){
		if (buffer[E.mode])buffer[E.mode].saveHistory();
		ToolbarSets.Redraw(e);		
	}
	
	s = s.parentNode;
	c = s.cloneNode(true);
	fc = s.previousSibling.cloneNode(true);
	lc = s.nextSibling.cloneNode(true);

	if (fc.nodeValue.toLowerCase().indexOf("no")>=0){
		var i = E._frame.createElement("img");
		i.id="de_delicon";
		i.src=E.skinPath+"delete.png";
		i.style.styleFloat ="right";
		if (browser.IE){
			i.style.marginRight ="-6px";
		}	

		var newStyle = "align:right;float:right;position:relative;top:0px;"
		i.setAttribute("style", newStyle)
		c.insertBefore(i,c.firstChild.nextSibling);
	}
	fc.nodeValue = "StartBlockCanDelete";
	lc.nodeValue = "EndBlockCanDelete";
    s.parentNode.insertBefore(fc,s.nextSibling.nextSibling);
    s.parentNode.insertBefore(c,s.nextSibling.nextSibling.nextSibling);
	s.parentNode.insertBefore(lc,s.nextSibling.nextSibling.nextSibling.nextSibling);

	if (browser.IE){
		e.cancelBubble=true;
		e.returnValue=false;
	    return false;
	} else {
		e.stopPropagation();
		e.preventDefault();		
	   	return false;
	}	
}

function delblock(s,e){

	if (useSmartHistory=="1"){
		buffer[E.mode].saveHistory();
		ToolbarSets.Redraw(e);
	}
		
	s = s.parentNode;
	fc = s.previousSibling;
	lc = s.nextSibling;
	var p = s.parentNode
	p.removeChild(s);
	p.removeChild(fc);
	p.removeChild(lc);
	

	if (browser.IE){
		e.cancelBubble=true;
		e.returnValue=false;
		return false;
	} else {
		e.stopPropagation();
		e.preventDefault();		
		return false;
	}
}

function addBlock(c){
	// if already have area
	if (c.indexOf("de_addicon")>=0){
		return c;
	}	
	
	var count = 0;	
	if (browser.IE){
		c = c.replace(start_block_expr,function(s1,s2){count++; return s2+'<div id="de_addblock'+count+'" style="border:green 1px dashed;"><img id="de_addicon'+count+'" style="margin-right:-4px;cursor:pointer;align:right;float:right;position:relative;top:0px;" src="'+E.skinPath+'add.png">'});
	} else {
		c = c.replace(start_block_expr,function(s1,s2){count++; return s2+'<div id="de_addblock'+count+'" style="border:green 1px dashed;"><img id="de_addicon'+count+'" style="cursor:pointer;align:right;float:right;position:relative;top:0px;" src="'+E.skinPath+'add.png">'});
	}	
	c = c.replace(end_block_expr,"</div>$1");
	
	if (browser.IE){
		c = c.replace(start_block_expr2,function(s1,s2){count++; return s2+'<div id="de_addblock'+count+'" style="border:green 1px dashed;"><img id="de_addicon'+count+'" style="margin-right:-4px;cursor:pointer;align:right;float:right;position:relative;top:0px;" src="'+E.skinPath+'add.png"><img id="de_delicon'+count+'" style="margin-right:-6px;align:right;float:right;position:relative;top:0px;" src="'+E.skinPath+'delete.png">'});
	} else {
		c = c.replace(start_block_expr2,function(s1,s2){count++; return s2+'<div id="de_addblock'+count+'" style="border:green 1px dashed;"><img id="de_addicon'+count+'" style="cursor:pointer;align:right;float:right;position:relative;top:0px;" src="'+E.skinPath+'add.png"><img id="de_delicon'+count+'" style="align:right;float:right;position:relative;top:0px;" src="'+E.skinPath+'delete.png">'});
	}	

	c = c.replace(end_block_expr2,"</div>$1");
	return c;
}

function removeBlock(c){
	return c.replace(remove_block_expr,function(s1,s2, s3,s4,s5,s6,s7){return s3+s5+s7;});
}

// Remove wrap for restricted area
function removeWrap(c)
{
	if (c){

		var b = false;
		if (E.mode=="edit") {
			b = c.match(BodyContents);
			if (b) {
				c = b[2];
			}
		}
		c1 = wrap_tag_expr;
		c2 = wrap_end_tag_expr;
		var count=0;var count2=0;

		edit_begin_tags	= c.match(begin_edit_expr);
		edit_end_tags	= c.match(end_edit_expr);

		//if (c1.test(code)){
			c = c.replace(c1,function(s1,s2){count++;return edit_begin_tags[count-1];});
			c = c.replace(c2,function(s1,s2){count2++;return edit_end_tags[count2-1];});
		//}
		c = removeBlock(c);
		if (b) {
			return b[1] + c + b[3];
		} else {
			return c;
		}

	} else {
		return "";
	}
}

// Insert Begin - End area
function addEditTag(code)
{
	// wrap for edit area
	c1 = /(<font color=#000080>&lt;div([^>]+)de_wrap_div([^>]*?)&gt;<\/font>)/gi;
	c2 = /(<font color=#000080>&lt;\/div&gt;<\/font>(?=<font color=#000080><font color=#808080>&lt;!--[#\s]*?(?:Instance|Template|)EndEditable\s*?--&gt;<\/font><\/font>))/gi;

	var count = 0; var count2 = 0;
	code = code.replace(c1,"<!-- BeginEditable --><div>");
	code = code.replace(c2,"</div><!-- EndEditable -->");
	return code;
}

function removeEditTag(code)
{

	var b = false;
	b = code.match(BodyContents);
	if (b) {
		code = b[2];
	}

	c1 = begin_edit_expr;
	c2 = end_edit_expr;
	var count=0;
	var count2=0;
	code = code.replace(c1,function(x1,x2){
		count++;
		return (edit_begin_tags ? edit_begin_tags[count-1]:"")+"<div id=de_wrap_div"+count+">";
	}
	);
	code = code.replace(c2,function(){
		count2++;
		return "</div>"+(edit_end_tags ? edit_end_tags[count2-1]:"");
	}
	);
	if (b) {
		return b[1] + code + b[3];
	} else {
		return code;
	}	
}

function replace_editable(html)
{
	if (E.isbegin && E.isend){
		var b = html.match(BodyContents);
		var count=0;
		var code;
		if (b){
			var save_code = b[2].match(edit_expr);
			var oldb = E.initHTML.match(BodyContents);
			code = oldb[2].replace(edit_expr,function(){count++;return save_code[count-1];});
			code = oldb[1] + code + oldb[3];
		} else {
			var save_code = html.match(edit_expr);
			code = E.initHTML.replace(edit_expr,function(){count++;return save_code[count-1];});
		}
		if (meta_keywords_expr.test(html)){	
			count = 0;
			var meta_code = html.match(meta_keywords_expr);
			code = code.replace(meta_keywords_expr,function(s,s1){count++;if (count==1)return meta_code[count-1];else return s1;});
		}	
	
		if (meta_description_expr.test(html)){		
			count = 0;
			var meta_code = html.match(meta_description_expr);
			code = code.replace(meta_description_expr,function(s,s1){count++;if (count==1)return meta_code[count-1];else return s1;});
		}	

		if (title_expr.test(html)){	
			count = 0;
			var meta_code = html.match(title_expr);
			code = code.replace(title_expr,function(){count++;return meta_code[count-1];});
		}	
		return code;
	} else {
		return html;
	}	
}

function formatSaveCode(c)
{
	linkTag = /(&lt;link([^>]+)borders.css([^>]+)&gt;)/gi;
	c = c.replace(linkTag,"");
	linkTag2 = /(<link([^>]+)borders.css([^>]+)>)/gi;
	c = c.replace(linkTag2,"");
	classTag = /(class([^>]+)de_style_anchor"?)/gi;
	c = c.replace(classTag,"");
	classTag2 = /(class([^>]+)de_style_input"?)/gi;
	c = c.replace(classTag2,"");
	return c;
}

// convert src=https to just src=http
function ConvertSSLImages(c)
{
	replaceImage = 'src=\"http://' + URL;
	c = c.replace(re4,replaceImage);
	return c;
}

function formatCodeIE(c)
{
	htmlTag = /(&lt;(html)([\s\S]*?)&gt;)/gi;
	html2Tag = /(&lt;(\/html)([\s\S]*?)&gt;)/gi;
	body2Tag = /(&lt;(\/body)([\s\S]*?)&gt;)/gi;
	tableEndTag = /(&lt;(\/table)&gt;)/gi;

	c = c.replace(htmlTag,"$1<br/>");
	c = c.replace(html2Tag,"<br/>$1");
	c = c.replace(body2Tag,"<br/>$1");
	c = c.replace(tableEndTag,"<br/>$1<br/>");

	if (pathType == 1) {
		c1 = new RegExp(doc_root2,"gi");
		c = c.replace(c1,"");
		c2 = new RegExp(doc_root,"gi");
		c = c.replace(c2,"/");
	}
	return c;
}

// Format code for Mozilla
function formatCode(c)
{
	metaTag = /((<br\/?>)?\s?&lt;(meta))/gi;
	htmlTag = /(&lt;(html)[^(&gt;)]*?&gt;\s?(<br\/?>)?)/gi;
	html2Tag = /((<br\/?>)?\s?&lt;(\/html)([\s\S]*?)&gt;)/gi;
	titleTag = /(&lt;(\/title)&gt;\s?(<br\/?>)?)/gi;
	title2Tag = /((<br\/?>)?\s?&lt;(title)&gt;)/gi;
	tableTag = /(&lt;(tbody|th|tr|td|\/tbody|\/th|\/tr|\/td)([\s\S]*?)&gt;\s?(<br\/?>)?)/gi;
	tableBeginTag = /((<br\/?>)?\s?&lt;(table)([\s\S]*?)&gt;)/gi;
	tableEndTag = /(&lt;(\/table)&gt;\s?(<br\/?>)?)/gi;
	headTag = /(&lt;(head)&gt;\s?(<br\/?>)?)/gi;
	head2Tag = /(&lt;(\/head)[^(&gt;)]*?&gt;\s?(<br\/?>)?)/gi;
	bodyTag = /(&lt;(body)([\s\S]*?)&gt;\s?(<br\/?>)?)/gi;
	body2Tag = /((<br\/?>)?\s?&lt;(\/body)([\s\S]*?)&gt;)/gi;
	scriptTag = /(&lt;(script|\/script)([\s\S]*?)&gt;\s?(<br\/?>)?)/gi;

	c = c.replace(metaTag,function(s1,s2){return "<br/>"+s2.replace(/(<br\/?>)/gi,"");});
	c = c.replace(tableEndTag,function(s1,s2){return s2.replace(/(<br\/?>)/gi,"")+"<br/>";});
	c = c.replace(tableBeginTag,function(s1,s2){return "<br/>"+s2.replace(/(<br\/?>)/gi,"");});
	c = c.replace(htmlTag,function(s1,s2){return s2.replace(/(<br\/?>)/gi,"")+"<br/>";});
	c = c.replace(html2Tag,function(s1,s2){return "<br/>"+s2.replace(/(<br\/?>)/gi,"");});
	c = c.replace(title2Tag,function(s1,s2){return "<br/>"+s2.replace(/<br\/?>/gi,"");});
	c = c.replace(titleTag,function(s1,s2){return s2.replace(/<br\/?>/gi,"")+"<br/>";});
	c = c.replace(tableTag,function(s1,s2){return s2.replace(/(<br\/?>)/gi,"")+"<br/>";});
	c = c.replace(headTag,function(s1,s2){return s2.replace(/(<br\/?>)/gi,"")+"<br/>";});
	c = c.replace(head2Tag,function(s1,s2){return s2.replace(/(<br\/?>)/gi,"")+"<br/>";});
	c = c.replace(bodyTag,function(s1,s2){return s2.replace(/(<br\/?>)/gi,"")+"<br/>";});
	c = c.replace(body2Tag,function(s1,s2){return "<br/>"+s2.replace(/(<br\/?>)/gi,"");});
	c = c.replace(scriptTag,function(s1,s2){return s2.replace(/(<br\/?>)/gi,"")+"<br/>";});
	return c;
}
// End format

// Colorize Code in Source Mode
function colourCode(c)
{
	htmlTag = /(&lt;([\s\S]*?)&gt;)/gi;
	tableTag = /(&lt;(table|tbody|th|tr|td|\/table|\/tbody|\/th|\/tr|\/td)([\s\S]*?)&gt;)/gi;
	commentTag = /(&lt;!--([\s\S]*?)&gt;)/gi;
	imageTag = /(&lt;img([\s\S]*?)&gt;)/gi;
	objectTag = /(&lt;(object|\/object)([\s\S]*?)&gt;)/gi;
	linkTag = /(&lt;(a|\/a)([\s\S]*?)&gt;)/gi;
	scriptTag = /(&lt;(script|\/script)([\s\S]*?)&gt;)/gi;
	headlinkTag = /(&lt;link([^&]*?)(borders.css)([^&]*?)&gt;<br\/>)/gi;
	c = c.replace(headlinkTag,"");
	headlinkTag2 = /(&lt;link([^&]*?)(borders.css)([^&]*?)&gt;)/gi;
	c = c.replace(headlinkTag2,"");

	b2 = "</font>";
	b1 = "<font color=#000080>";
	c = c.replace(headlinkTag,"");
	c = c.replace(htmlTag,b1+"$1"+b2);
	c = c.replace(tableTag,"<font color=#008080>$1"+b2);
	c = c.replace(commentTag,"<font color=#808080>$1"+b2);
	c = c.replace(imageTag,"<font color=#800080>$1"+b2);
	c = c.replace(objectTag,"<font color=#840000>$1"+b2);
	c = c.replace(linkTag,"<font color=#008000>$1"+b2);
	c = c.replace(scriptTag,"<font color=#800000>$1"+b2);
	return c;
}
// End colorize

// Replace IMG to EMBED
function img2embed(code) {
	if (useXHTML == 1) {
		code = code.replace(/<\/embed\/?>/gi, "");
	}
	c1 = /(<img[^>]*?name2="([\s\S]*?)"[^>]*?>)/gi;

	code = code.replace(c1,function(s1,s2,s3){
			r1 = s2.match(/width\s*[:|=]\s*(\S+)[%|px]?/gi);
			r2 = s2.match(/height\s*[:|=]\s*(\S+)[%|px]?/gi);
			s3 = unescape(s3);

			if (useXHTML == 1) {
				s3 = getXHTML(s3);
			}

			var h = r2[0].replace(/:/gi,"=");
			var w = r1[0].replace(/:/gi,"=");
			w = w.replace(/[>|;|\/" | ]/gi,"");
			h = h.replace(/[>|;|\/|" ]/gi,"");
			h = h.replace(/=/gi,'="')+'"';
			w = w.replace(/=/gi,'="')+'"';

			s3 = s3.replace(/(height\s*=\s*[\d\"\']+)/gi,h);
			s3 = s3.replace(/(width\s*=\s*[\d\"\']+)/gi,w);
			return s3;
		}
	);

	// media UPL
	c1 = /(\<(?:style[^\>]+?\>|)[^\>]+?(url\(([^\)]+)\))[^\>]+\>)/gi;
	var cnt = 0;
	code = code.replace(c1,function(s1,sx,s2,s3){
		var r = new RegExp(s3,"gi");
		cnt+=1;
		if (sx.indexOf(media_urls[cnt-1])>=0){
			if (!media_urls[cnt-1])return sx;
			if (s2.indexOf('"')!==-1){
				return sx.replace(r,'"'+media_urls[cnt-1]+'"');
			} else {
				return sx.replace(r,media_urls[cnt-1]);
			}	
		} else {
			cnt--;
			return sx;
		}
	}
	);
	
	// relative path
	c1 = /(<([^>]+)realsrc="([^"]+)"([^>]*)>)/gi;
	code = code.replace(c1,function(s1,s2,sx,s3){
		rs = /(src="([^"]+)")/gi;

		s2 = s2.replace(rs,function(w1,w2,w3){
		   if (w2.indexOf("src=")>=0)w2=w3;
			if (w2.indexOf(s3)>=0 || s3.indexOf("..")>=0) return 'src="'+unescape(s3)+'"'; // ???
				else return 'src="'+unescape(w2)+'"';
		});

		rs = /(href="([^"]+)")/gi;
		s2 = s2.replace(rs,function(w1,w2){
			if (w2.indexOf(s3)>=0 && !isSpecialHref(s2) && w2.indexOf("mailto:")==-1 &&w2.indexOf("javascript:")==-1) return 'href="'+s3+'"';
				else return 'href="'+w2+'"';
		});

		rs = /(background="?([^"|\s]+)"?)/gi;
		s2 = s2.replace(rs,function(w1,w2){
			if (w2.indexOf(s3)>=0) return 'background="'+s3+'"';
				else return 'background="'+w2+'"';
		});

		s2 = s2.replace(/(realsrc="([^"]+)")/gi,"");

		return s2;

	}
	);
	
	// remove -moz- style
	c1 = /(style="([^"]+)")/gi;
	code = code.replace(c1,function(s1,s2,s3){
		return s2.replace(/(-moz-[^;]+;)/gi,"");
	}
	);

	return code;
}

// Replace IMG to EMBED
function img2embed2(code) {
	if (useXHTML == 1) {
		code = code.replace(/<\/embed\/?>/gi, "");
	}

	c1 = /(<img[^>]*?name2="([\s\S]*?)"[^>]*?>)/gi;

	code = code.replace(c1,function(s1,s2,s3){
			r1 = s2.match(/width\s*[:|=]\s*(\S+)[%|px]?/gi);
			r2 = s2.match(/height\s*[:|=]\s*(\S+)[%|px]?/gi);
			s3 = unescape(s3);

			if (useXHTML == 1) {
				s3 = getXHTML(s3);
			}

			var h = r2[0].replace(/:/gi,"=");
			var w = r1[0].replace(/:/gi,"=");
			w = w.replace(/[>|;|\/" | ]/gi,"");
			h = h.replace(/[>|;|\/|" ]/gi,"");
			h = h.replace(/=/gi,'="')+'"';
			w = w.replace(/=/gi,'="')+'"';

			s3 = s3.replace(/(height\s*=\s*[\d\"\']+)/gi,h);
			s3 = s3.replace(/(width\s*=\s*[\d\"\']+)/gi,w);

			return s3;

		}
	);
	return code;
}

// Replace IMG to EMBED
function img2embed22(code) {
	// relative path
	c1 = /(src="([^"]+)")/gi;
	code = code.replace(c1,function(s1,s2,s3){
		var t = (s3.substr(0,1)=="/" || s3.indexOf("itpc://")>=0 ||  s3.indexOf("http://")>=0 || s3.indexOf("www.")>=0 || s3.indexOf("https://")>=0 );
		if (t) return s2;
		else {
		if (loadedFile) return 'realsrc="'+s3+'" src="'+loadedFile+s3+'"';
			else return 'realsrc="'+s3+'" src="'+doc_root+s3+'"';
		}
		}
	);

	c1 = /(href="([^"]+)")/gi;
	code = code.replace(c1,function(s1,s2,s3){
		var t = (s3.substr(0,1)=="/" || isSpecialHref(s3) || s3.indexOf("javascript:")>=0 || s3.indexOf("itpc://")>=0 || s3.indexOf("http://")>=0 || s3.indexOf("www.")>=0 || s3.indexOf("https://")>=0 );
		if (t) return s2;
		else {
		if (loadedFile) return 'realsrc="'+s3+'" href="'+loadedFile+s3+'"';
			else return 'realsrc="'+s3+'" href="'+doc_root+s3+'"';
		}
		}
	);

	c1 = /(background="([^"]+)")/gi;
	code = code.replace(c1,function(s1,s2,s3){
		var t = (s3.substr(0,1)=="/" || s3.indexOf("itpc://")>=0 || s3.indexOf("http://")>=0 || s3.indexOf("www.")>=0 || s3.indexOf("https://")>=0 );
		if (t) return s2;
		else {
		if (loadedFile) return 'realsrc="'+s3+'" background="'+loadedFile+s3+'"';
			else return 'realsrc="'+s3+'" background="'+doc_root+s3+'"';
		}
		}
	);

	// media UPL
	c1 = /(url\("([^"]+)"\))/gi;
	code = code.replace(c1,function(s1,s2,s3){
		if (is_path_full(s3)) {
			return s2;
		}
		else {
			if (loadedFile) return 'url("'+loadedFile+s3+'")';
			else return 'url("'+doc_root+s3+'")';
		}
		}
	);

	return code;
}

function is_path_full(s3) {
	return (myBaseHref!=="" || s3.substr(0,1)=="/" || s3.substr(0,1)=="{" || s3.indexOf("media_file.gif")>=0  || s3.indexOf("mailto:")>=0 || s3.indexOf("javascript:")>=0 || s3.indexOf("itpc://")>=0 ||s3.indexOf("http://")>=0 || s3.indexOf("www.")>=0 || s3.indexOf("https://")>=0 );
}

var media_urls = new Array();

function embed2img(code, ignore_media_url) {
	if (!ignore_media_url){
		media_urls.length=0;
	}	
	
	c1 = /(<embed [\s\S]*?>)/gi;
	code = code.replace(c1,function(s1,s2){
				r1 = s2.match(/width=\s*(\S+)[\s\S]*?/gi);
				r2 = s2.match(/height=\s*(\S+)[\s\S]*?/gi);
				r3 = s2.match(/src=\s*(\S+)\s?/gi);
				w = r1[0].replace(/[>|;|]/gi,"");
				h = r2[0].replace(/[>|;|]/gi,"");
				alt = r3[0].replace(/[>|;|"]/gi,"");
				if (alt) alt = alt.replace(/src/gi,"alt");
				if (s2.indexOf("x-mplayer")>=0)	return '<img '+w+' '+h+' '+alt+' class="de_media_file" name2="'+escape(s2)+'" >';
					else return '<img  '+w+' '+h+' '+alt+'class="de_flash_file" name2="'+escape(s2)+'" >';
			}
		);

	// relative path - src
	c1 = /\<[^\>]+?(src="([^"]+)")[\s\S]+?\>/gi;
	code = code.replace(c1,function(s1,s2,s3){
		if (is_path_full(s3)){
			if (canBroken(s3)){
				var r = new RegExp(s2);
				return s1.replace(r,'realsrc="'+s3+'" src="'+s3+'"');
			} else {
				return s1;
			}	
		}
		else {
			var r = new RegExp(s2);
			if (loadedFile) return s1.replace(r,'realsrc="'+s3+'" src="'+loadedFile+s3+'"');
			else return s1.replace(r,'realsrc="'+s3+'" src="'+doc_root+s3+'"');
		}
		}
	);

	// media UPL
	c1 = /(\<(?:style[^\>]+?\>|)([^\>]+?)(url\("?([^"\)]+)"?\))[\s\S]+?\>)/gi;
	code = code.replace(c1,function(s1,sx,s2,s4,s3){
		var r = new RegExp(s2,"gi");
		var r1 = new RegExp(s3,"gi");
		// do not add [.de_] styles
		//if (browser.IE)
		var b = false
		if (s2.lastIndexOf("{")>s2.lastIndexOf("}")){
			if (s2.indexOf(".de_",s2.lastIndexOf("}"))>=0){
				b = true; 
			}
		}
		if (!b) {
			media_urls[media_urls.length] = s3;
		}	

		if (is_path_full(s3)) {
			if (canBroken(s3)){
				return sx.replace(r,'realsrc="'+s3+'" href="'+s3+'"');
			} else {
				return sx;
			}	
		}
		else {
			if (loadedFile) {
				if (s3.indexOf('"')!==-1)
					return sx.replace(r1,loadedFile+s3);
				else
					return sx.replace(r1,loadedFile+s3);
			} else {
				if (s3.indexOf('"')!==-1){
					return sx.replace(r1,doc_root+s3);
				} else {
					return sx.replace(r1,doc_root+s3);
				}	
			}	
		}
		}
	);

	// relative path - href
	c1 = /(background="([^"]+)")/gi;
	code = code.replace(c1,function(s1,s2,s3){
		if (is_path_full(s3) || s3.indexOf("borders.css")!==-1) {
			if (canBroken(s3)){
				return 'realsrc="'+s3+'" background="'+s3+'"';
			} else {
				return s2;
			}	
		}
		else {
			if (loadedFile) return 'realsrc="'+s3+'" background="'+loadedFile+s3+'"';
			else return 'realsrc="'+s3+'" background="'+doc_root+s3+'"';
		}
		}
	);

	// relative path - href
	c1 = /(style="([^"]+)")/gi;
	code = code.replace(c1,function(s1,s2,s3){
		return s2.replace(/(-moz-[^;]+;)/gi,"");
	}
	);

	return code;
}


function canBroken(s){
	if ( s.substr(0,1)=="/" ){
		return true;
	} else {
		return false;
	}
}

function isSpecialHref(s){
	if (s.indexOf("%")==-1 || s.indexOf("..")>=0 ){
		return false;
	} else {
		return true;
	}
}
function setRealSrc(s3) {
	var r = /(\%\w+:\w+\%)/gi;
	var t = (s3.indexOf("..")>=0 || s3.substr(0,1)=="/" || r.test(s3) || s3.indexOf("mailto:")>=0 || s3.indexOf("javascript:")>=0 || s3.indexOf("itpc://")>=0 || s3.indexOf("http://")>=0 || s3.indexOf("www.")>=0 || s3.indexOf("https://")>=0 );
	if (t) return null;
	else {
		if (loadedFile) return loadedFile+s3;
			else return doc_root+s3;
	}
}

function setFixedSrc(s3) {
	var t = (s3.indexOf("..")>=0 || s3.substr(0,1)=="/" || s3.indexOf("mailto:")>=0 || s3.indexOf("javascript:")>=0 || s3.indexOf("itpc://")>=0 || s3.indexOf("http://")>=0 || s3.indexOf("www.")>=0 || s3.indexOf("https://")>=0 );
	if (t) return s3;
	else {
		if (loadedFile) return loadedFile+s3;
			else return doc_root+s3;
	}
}

HTMLEncode = function(t)
{
	if (!t) return '';
	t = t.replace( /&/g, "&amp;");
	t = t.replace( /"/g, "&quot;");
	t = t.replace( /</g, "&lt;");
	t = t.replace( />/g, "&gt;");
	t = t.replace( /'/g, "&#39;");
	return t ;
}


function _de_getContent(){
	E.commands.getCommand("Save").execute("no");
	return parent.document.getElementById(E.InstanceName+"_html").value;
}

function _de_getTextContent()
{
	E.commands.getCommand("Save").execute("no");
	var c = parent.document.getElementById(E.InstanceName+"_html").value;
	var h = c.match(BodyContents);
	if (h != null && h[2]) {
		c = h[2];
	}
	c = c.replace(/(\n)/gi,"");
	c = c.replace(/(\r)/gi,"");
	c = c.replace(/<br\/?>/gi,"\n");
	c = c.replace(/<[^>]+>/g,"");
	c = c.replace(/&lt;/g,"<");
	c = c.replace(/&gt;/g,">");
	c = c.replace(/&nbsp;/g," ");
	return c;
}

function _de_parse_special_char(c)
{
	if (disableHTMLEntities)return c;
	var x = document.createElement("DIV");
	for (var i=0;i<cT.length;i++) {
		x.innerHTML = cT[i];
		if (x.innerHTML=="") {
			continue;
		}
		var r = new RegExp(x.innerHTML,"g");
		c = c.replace(r,cT[i].replace(/&/g, "&amp;"));
	}
	x = null;
	return c;
}

function _de_parse_special_char2(c)
{
	if(disableHTMLEntities)return c;
	for (var i=0;i<cT.length;i++) {
		var r = new RegExp(cT[i],"g");
		c = c.replace(r,cT[i].replace(/&/g, "&amp;"));
	}
	return c;
}

function _de_save_special_char(c)
{
	if(disableHTMLEntities)return c;
	var x = document.createElement("DIV");
	for (var i=0;i<cT.length;i++){
		x.innerHTML = cT[i];
		if (x.innerHTML=="") {
			continue;
		}
		var r = new RegExp(x.innerHTML,"g");
		c = c.replace(r,cT[i]);
	}
	x = null;
	return c;
}

function _de_save_special_char2(c){
	if(disableHTMLEntities)return c;
	for (var i=0;i<cT.length;i++) {
		var r = new RegExp(cT[i],"g");
		c = c.replace(r,cT[i]);
	}
	return c;
}

// Set style command
var SetStyle = function(style,style2)
{
	this.Name = 'SetStyle' ;
	if (style) {
		this.style = style;
	}
	if (style2) {
		this.style2 = style2;
	}
};

SetStyle.prototype.execute = function(name){ // class name or name of style property

	sel = null;
	oContainer=null;

	if (browser.IE){	// IE don't see selected element
		var s = E._frame.selection;
		if (s.type=="Text"){
			var pe = s.createRange().parentElement();
			var news = s.createRange().htmlText.replace(/\n|\r|\f|\v/g,"");

			var pes = pe.outerHTML.replace(/\n|\r|\f|\v/g,"")
			pes =pes.replace(/<[^>]+>/g,"")
			if (pes == news.replace(/<[^>]+>/g,"") && pe.tagName.toLowerCase()!=="body") {
				sel = pe;
			}
			if (E.GetSelection()=="") {
				if (pe.parentNode && pe.parentNode.tagName.toLowerCase()=="span" ) {
					sel = pe.parentNode;
				} else {
					if (pe.tagName && pe.tagName.toLowerCase()!=="span") {
						sel=null;
					}
				}
			}
		}
	}

	if (!sel) {
		sel = E.GetSelectedElement();
	}

	if (!sel && E.GetSelection()!=="" && browser.NS ){// Gecko selection bug

		oSel = E._window.getSelection();
		range = oSel.getRangeAt(0);

		if (!range.collapsed && range.startContainer.nextSibling && range.startContainer.nextSibling.tagName && range.startContainer.nextSibling.tagName.toLowerCase()=="span"){
			oContainer = range.startContainer.nextSibling;
			sel =  oContainer;
		} else {
			var p = range.commonAncestorContainer;
			if (!range.collapsed && range.startContainer == range.endContainer && range.startOffset - range.endOffset <= 1 && range.startContainer.hasChildNodes()){
				p = range.startContainer.childNodes[range.startOffset];
			}
			oContainer = p;

			if (p.nodeType == 3 ) {
				// only when parent tag include all text
				var txt = E._window.getSelection().toString;
				if (p.nodeValue == txt){
					oContainer = p.parentNode;
				} else {
					oContainer = null;
				}
			} else {
				// try to check if text inside parent tag
				var root = range.commonAncestorContainer;
				if (root.firstChild == range.startContainer && range.startOffset==0 && root.lastChild == range.endContainer){
					oContainer = root;
				} else {
					oContainer = null;
				}
			}

			if(oContainer) {
				sel = oContainer;
			}
		}
	}

	if (!sel&&E.GetSelection()=="") {
		sel = E.firstParentNode();

		if (!sel) {
			return;
		}
	}

	if (this.style){ // if set style property
		if (sel){
			eval("sel.style."+this.style2+"=\""+name+"\";");
			var count=0;
			for (var i in sel.style) {
				eval("if(sel.style['"+i+"'] && i!=='accelerator' && i!=='cssText'){count++;}");
			}
			if ((browser.IE && count==0) || (browser.NS && count==6) ) {
				sel.removeAttribute("style");
			}
			if (browser.IE && sel.outerHTML.search(/^<span>/gi)==0) {
				var s = E._frame.selection;
				//if ( s.type.toLowerCase() != "none" )s.clear() ;
				var newNode = s.createRange().parentElement();
				if (newNode.tagName && newNode.tagName.toLowerCase()!=="span" && newNode.parentNode) {
					newNode = newNode.parentNode;
				}
				newNode.outerHTML = sel.innerHTML;
			}

			if (browser.NS && sel.attributes.length==0){ // remove span
				var s = E._window.getSelection();
				range = E._frame.createRange();
				range.selectNode(sel);
				s.removeAllRanges();
				s.addRange(range);

				E._inserthtml(sel.innerHTML);
			}
		} else {
			var newNode;
			var sln = "";
			if (browser.IE) {

				var r = E._frame.selection.createRange(); 
				
				if (E._frame.selection.type=="Text" && r.htmlText.replace(/(\n)/g,"") == r.parentElement().innerHTML){//r.htmlText.indexOf("<",0)==0 &&
					r.collapse(false);
					r.select();
					var p = r.parentElement();

					r.moveToElementText(p);
					r.select()
				}

				sln = r.htmlText;

			} else {
				var s = E._window.getSelection().getRangeAt(0);
				newNode = E._frame.createElement("span");
				f = s.cloneContents();
				newNode.appendChild(f);
				sln = newNode.innerHTML;
		
				if ( s.startContainer == s.endContainer && (s.endOffset - s.startOffset) == 1 ) {
				} else {
					if (sln.indexOf("<",0)==0){
						var p = s.startContainer.childNodes[range.startOffset];;
						while(p && p.firstChild){
							p = p.firstChild;
						}
						
						var r = E._frame.createRange();
						var sel = E._window.getSelection();						
						sel.removeAllRanges();
						r.selectNodeContents(p.parentNode);						
						sel.addRange(r);
						sln = p.parentNode.innerHTML;
					}
				}
				newNode = null;
			}
			
			if (name=="") {
				sptext = sln;
			}else{
				sln = sln.replace(/^<[^>]+>/,"");
				sln = sln.replace(/<[^>]+>$/,"");
				sptext = "<span id='"+_de_temp_element+"' style='"+this.style+":"+name+"'>" + sln + "</span>";
			}

			E._inserthtml (sptext);
		}

	} else { // if set class
		if (sel) {
			// remove class style
			if (!name) {
				if (browser.IE) {
					var clName = "className";
				} else {
					var clName = "class";
				}
				sel.removeAttribute(clName);
				if (sel.tagName && sel.tagName.toLowerCase()=="span") {
					var sln = "";
					if (browser.IE) {
						sel.outerHTML = sel.innerHTML;
					} else {
						var s = E._window.getSelection();
						var range = s.getRangeAt(0) ;
						var f = range.createContextualFragment(sel.innerHTML) ;
						sel.parentNode.replaceChild(f,sel);
						s = null;
					}
				}

			// set style
			} else {
				sel.className = name;
			}

		} else {
			var newNode;
			if (browser.IE) {
				sptext = E._frame.selection.createRange().htmlText;
			} else {

				var s = E._window.getSelection().getRangeAt(0);
				
				// when select all UL list 
				var pN = null;
				var pX = s.commonAncestorContainer;
				if (pX && pX.tagName && (pX.tagName.toLowerCase() == "ul" || pX.tagName.toLowerCase() == "ol")) {
					pN = pX ;
				}
				if (pN) s.selectNode(pN);
				// end
					
				newNode = E._frame.createElement("span");
				f = s.cloneContents();
				newNode.appendChild(f);
				sptext = newNode.innerHTML;
				newNode = null;
			}

			if (name=="None"){
			} else {
				sptext = "<span id='"+_de_temp_element+"' class="+name+">" + sptext + "</span>";
			}
			E._inserthtml (sptext,1);
		}
	}
	try{
		E.Focus();
	}catch(e){}
	ToolbarSets.Redraw();
}

SetStyle.prototype.getMode = function()
{
	return OFF;
};

function addBase(c){
	s = c.match(HeadContents);
	if (s) {
		c = s[1]+'<style id="'+E.InstanceName+'bs">'+border_style+'<\/style>'+s[2]+s[3];
	}	
	return c;
}

function removeBase(c)
{
 	var r = new RegExp("(\<style\\s+id=\"?"+E.InstanceName+"[\\s\\S]*?\<\\/style\>)","gi")
	c = c.replace(r,"");
	var r1 = new RegExp("((&lt;style\\s+id=\"?"+E.InstanceName+"[\\s\\S]*?&lt;\\/style&gt;))","gi")
	c = c.replace(r1,"");
	return c;
}

// NewTag command
var NewTag = function()
{
	this.Name = 'AddTag' ;
	this.mode = OFF;	
};

NewTag.prototype.execute = function(tag, tag2)
{
	var l = tag.length;
	if (tag2){
		if ( tag.toLowerCase() == tag2.substring(0,l).toLowerCase() ){
			E._inserthtml(tag2.substring(tag.length,tag2.length));
		} else {
			E._shift_inserthtml(tag2, tag.length);
		}
	} else {
		E._inserthtml(tag);
	}
};

NewTag.prototype.getMode = function(){
	return this.mode;
}

// Spellcheck command
var Spellcheck = function(){
	this.Name = 'Spellcheck' ;
	this.command = new DialogCommand("SpellCheck","spell_check.html",350,250);
	this.mode = OFF;
	this.isspell = false;
};

// ReplaceWord command
var ReplaceWord = function()
{
	this.Name = 'ReplaceWord' ;
};

ReplaceWord.prototype.execute = function(word)
{
	if (word!=="(no suggestions)") {
		E._inserthtml(word);
	}
};

ReplaceWord.prototype.getMode = function(){return OFF;}

// Spellcheck command
var Spellcheck = function(){
	this.Name = 'Spellcheck' ;
	this.command = new DialogCommand("SpellCheck","spell_check.html",350,250);
	this.mode = OFF;
	this.isspell = false;
};

Spellcheck.prototype.execute = function()
{
	var aed=E._frame;
	if (this.mode==ON){
		this.mode=OFF;
		var l = _de_spell_words.length;
		for (var i=0;i<l;i++){
			var node = aed.getElementById("_de_spell_word_"+i);
			if (node) {
				var textNode =  aed.createTextNode(node.innerHTML);
				node.parentNode.replaceChild(textNode,node);
			}
		}
		_de_spell_words = null;
		_de_spell_words = new Array();
	} else {
		this.mode = ON;
		this.isspell=true;
		this.command.execute();
		this.isspell=false;
	}
};

Spellcheck.prototype.off = function()
{
	var aed=E._frame;
	if (this.mode==ON && !this.isspell) {
		this.mode=OFF;
		var l = _de_spell_words.length;
		for (var i=0;i<l;i++){
			var node = aed.getElementById("_de_spell_word_"+i);
			if (node) {
				var textNode =  aed.createTextNode(node.innerHTML);
				node.parentNode.replaceChild(textNode,node);
			}
		}
		_de_spell_words = null;
		_de_spell_words = new Array();
	}
};

Spellcheck.prototype.getMode = function()
{
	return this.mode;
};


// AddToDictionary command
var AddToDictionary = function()
{
	this.Name = 'AddToDictionary' ;
};

AddToDictionary.prototype.execute = function(word)
{
	var expDate = new Date;
	expDate.setTime (expDate.getTime() + (10*356*24*60*60*1000));
	cw = GetCookie("spellwords");
	if (!cw || !searchWord(cw, word)) {
		SetCookie("spellwords",cw+word+";",expDate);

		E._inserthtml(word);

		// ignore all
		var l = _de_spell_words.length;
		for (var i=0;i<l;i++){
			var node = aed.getElementById("_de_spell_word_"+i);
			if (node && node.innerHTML==word) {
				var textNode =  aed.createTextNode(node.innerHTML);
				node.parentNode.replaceChild(textNode,node);
			}
		}
	}
};

AddToDictionary.prototype.getMode = function()
{
	return OFF;
};

function searchWord(s, word)
{
	var r = new RegExp(word+";","gi");
	if (s.search(r)>=0) {
		return true;
	} else {
		return false;
	}
}

function createArray()
{
	return new Array();
}

// Ignore command
var Ignore = function()
{
	this.Name = 'Ignore' ;
};

Ignore.prototype.execute = function(word)
{
		E._inserthtml(word);
};

Ignore.prototype.getMode = function()
{
	return OFF;
};

// IgnoreAll command
var IgnoreAll = function()
{
	this.Name = 'Ignore' ;
};

IgnoreAll.prototype.execute = function(word)
{
		// ignore all
		var l = _de_spell_words.length;
		for (var i=0;i<l;i++){
			var node = aed.getElementById("_de_spell_word_"+i);
			if (node && node.innerHTML==word) {
				var textNode =  aed.createTextNode(node.innerHTML);
				node.parentNode.replaceChild(textNode,node);
			}
		}
};

IgnoreAll.prototype.getMode = function()
{
	return OFF;
};

function removeSpellSpan(c)
{
	var l = _de_spell_words.length;
	var start = 0;
	var idx = 0;
	var idx2 = 0;
	var idx3 = 0;
	var idx4 = 0;

	for (var i=0;i<l;i++){
		idx = c.indexOf('<span id="_de_spell_word_'+i,start);
		if (idx>=0) {
			idx2 = c.indexOf('>',idx);

			// ignore span
			idx3 = c.indexOf('</span>',idx);
			var s = c.substr(idx2+1,idx3-idx2+1);
			var st = 0;
			while(s.indexOf("<span",st)>=0){
				idx3 = c.indexOf('</span>',idx3+1);
				st = s.indexOf("<span",st)+5;
			}
			idx4 = idx3 + 7;
			c = c.substring(0,idx3)+c.substring(idx4,c.length);

			// strip begin span
			c = c.substring(0,idx)+c.substring(idx2+1,c.length);
			start = 0;
		}
	}
	return c;
}

// SetFormatBlock command
var SetFormatBlock = function()
{
};

SetFormatBlock.prototype.execute = function(name)
{
	sel = null;

	if (browser.IE) {	// IE don't see selected element
		var s = E._frame.selection;
		if (s.type=="Text") {
			var pe = s.createRange().parentElement();
			var news = s.createRange().htmlText.replace(/\n|\r|\f|\v/g,"");
			if (pe.outerHTML.replace(/\n|\r|\f|\v/g,"") == news) {
				sel = pe;
			}
			if (E.GetSelection()=="") {
				if (pe.parentNode) {
					sel = pe.parentNode;
				} else {
					if (pe.tagName && pe.tagName.toLowerCase()!=="span") {
						sel=null;
					}
				}
			}
		}
	}

	if (!sel) {
		sel = E.GetSelectedElement();
	}
	if (!sel&&E.GetSelection()=="") {
		sel = E.firstParentNode();
		if (!sel) {
			return;
		}
	}


	var sln;
	if (sel) {
		var range = E._frame.body.createTextRange();
		range.moveToElementText(sel);
		sln = sel.innerHTML;
		range.select();
	} else {
		sln = E._frame.selection.createRange().htmlText;
	}

	sptext = name + sln + name.replace(/</gi,"</");
	E._inserthtml (sptext);

	ToolbarSets.Redraw();
};

SetFormatBlock.prototype.getMode = function(){
	return OFF;
};

function checkImgLoaded (d)
{
	if (!d) {
		d = E._frame;
	}
	for (var i=0;i<d.images.length;i++) {
		usePageBaseUrl(d.images[i]);
	}
	var t = d.getElementsByTagName("td");
	var rs = "";
	for (var i=0;i<t.length;i++) {
		rs = t[i].getAttribute("realsrc")
		if(rs)t[i].setAttribute("background",rs);
	}
}

// if img not found use base href
function usePageBaseUrl (img)
{
	if ((browser.IE && img.fileSize<=0) || (typeof img.naturalWidth != "undefined" && img.naturalWidth == 0)) {
		var s = img.src;
		var s3 = s;
		var t = false;
		var r1 = new RegExp(loadedFile,"gi");
		var r2 = new RegExp(doc_root,"gi");
		if (loadedFile) {
			s = s.replace(r1,"");
		} else {
			s = s.replace(r2,"");
		}
		if (myBaseHref){
			s = s.replace(/file:\/\/\//gi,"");
			s = myBaseHref + s;
		}

		if (s3)t = (s3.substr(0,1)=="/" || s3.indexOf("itpc://")>=0 ||  s3.indexOf("http://")>=0 || s3.indexOf("www.")>=0 || s3.indexOf("https://")>=0 );
		if (!t)	{
			img.src = s;
		}	
	}
}


// ShowParagraphCommans command
var ShowParagraphCommans = function()
{
	this.Name = 'ShowParagraph' ;
	this.mode = OFF;
};

function cancelEvt(e){
	e.stopPropagation();
	e.preventDefault();
	return false;
}

function _sSC(){
	_hSC();
	var doc=E._frame;
	var aPara=doc.getElementsByTagName("p");
	for(var i=0; i<aPara.length; i++){
		var o=doc.createElement("img");
		o.src = serverurl + deveditPath1 + '/images/para.gif';
		o.className="__p";
		o.border=0;
		o.unselectable="on";
		if (browser.NS)o.addEventListener( 'mousedown', cancelEvt, true ) ;
		aPara[i].appendChild(o)
	}
	var aPara=doc.getElementsByTagName("br");
	for(var i=0;i<aPara.length;i++){
		var o=doc.createElement("img");
		o.src=serverurl + deveditPath1 + '/images/br.gif';
		o.className="__br";
		o.border=0;
		o.unselectable="on";
		if (browser.NS)o.addEventListener( 'mousedown', cancelEvt, true ) ;		
		aPara[i].parentNode.insertBefore(o,aPara[i])
	}
}

function _hSC(){		
	var r=new Array();
	var o=E._frame.getElementsByTagName("img");
	for(var i=0;i<o.length;i++){
		if(o[i].className=="__p"||o[i].className=="__br"){
			r[r.length]=o[i]
		}
	}
	for(var i=0;i<r.length;i++){
		if(browser.IE)r[i].removeNode(true);else r[i].parentNode.removeChild(r[i])
	}
	
}

ShowParagraphCommans.prototype.showChar = function(){
	if (this.mode == ON && E.mode == "edit"){
		_sSC();
	}
}

ShowParagraphCommans.prototype.hideChar = function(){
	_hSC();
}

ShowParagraphCommans.prototype.execute = function()
{
	switch (this.mode){
		case ON:
			this.mode = OFF;
			_hSC();
			break;
		default:
			this.mode = ON;
			_sSC();
	}
};

ShowParagraphCommans.prototype.getMode = function()
{
	return this.mode;
};

var ieRange = function()
{
	this.Name = 'ieRange' ;
	this.startOffset = -1;
	this.startContainer = null;
};

ieRange.prototype._getStartContainer = function(textRange) {

	var element = textRange.parentElement();
	var range = E._frame.body.createTextRange();
	range.moveToElementText(element);
	range.setEndPoint("EndToStart", textRange);
	var rangeLength = range.text.length;

	// Choose Direction
	if(rangeLength < element.innerText.length / 2) {
		var direction = 1;
		var node = element.firstChild;
	} else {
		direction = -1;
		node = element.lastChild;
		range.moveToElementText(element);
		range.setEndPoint("StartToStart", textRange);
		rangeLength = range.text.length;
	}

	// Loop through child nodes
	while(node) {
		
		switch(node.nodeType) {
			case 3:
				nodeLength = node.data.length;
				if(nodeLength < rangeLength) {
					var difference = rangeLength - nodeLength;
					if(direction == 1) range.moveStart("character", difference);
					else range.moveEnd("character", -difference);
					rangeLength = difference;
				}
				else {
					if(direction == 1) {
						this.startOffset = rangeLength;
						this.startContainer = node.previousSibling;
						return node;
					} else {	
						this.startOffset = nodeLength - rangeLength;
						this.startContainer = node;
						return node;
					}	
				}
				break;

			case 1:
				nodeLength = node.innerText.length;
				if (!nodeLength && node.tagName.toLowerCase()=="br")nodeLength = 2;
				if(direction == 1) range.moveStart("character", nodeLength);
				else range.moveEnd("character", -nodeLength);
				rangeLength = rangeLength - nodeLength;
				break;
		}
	
		if(direction == 1) node = node.nextSibling;
		else node = node.previousSibling;
	}

	// The TextRange was not found. Return a reasonable value instead.
	this.startOffset = 0;
	this.startContainer = element;
	return element;
	
}

function findCustomTags(){
	customTags = new Array();
	getCustomTag(E._frame.body);
}

function getCustomTag(node)
{
	if (node.nodeType == 1) {
		if (useXHTML==1 && node.tagName && !_xhtml_parser_aTags[node.tagName.toLowerCase()]){
			customTags[customTags.length]=node.tagName;
		}
	}
	if (node.childNodes != null) {
		for ( var i=0; i < node.childNodes.length; i++) {
			getCustomTag(node.childNodes.item(i));
		}
	}
}

// Replacer object
var Replacer = function()
{
	this.rules = [];
};

Replacer.prototype.addRule = function(r)
{
	this.rules[this.rules.length] = r;
};

Replacer.prototype.preserve = function(c)
{
	for (var i = 0; i < this.rules.length; i++){
		var r = new RegExp(this.rules[i],"gi");
		c = c.replace(r,function(s1,s2){
			 return "<!--_de_begin_temp_comment_" + s2.replace(/(\<\!--)/,"_de_start_0108").replace(/(--\>)/gi,"_de_end_0108") + "_de_end_temp_comment_-->";
			}
			);
	}
	return c;
};

Replacer.prototype.restore = function(c)
{
	c  = c.replace(/(\<\!--_de_begin_temp_comment_)/gi,"");
	c  = c.replace(/(_de_end_temp_comment_--\>)/gi,"");
	c  = c.replace(/(_de_start_0108)/gi,"<!--");
	c  = c.replace(/(_de_end_0108)/gi,"");
	return c;
};
