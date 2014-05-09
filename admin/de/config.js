// Name: Config file
// Info: Contain constants and menu defenition.

// Buttons constants
EXCLUDE		= -2;
DISABLED	= -1;
CHOOSE		= 2;
ON 			= 1;
OFF			= 0;

// CompareHow
START_TO_START  = 0;
START_TO_END    = 1;
END_TO_END      = 2;
END_TO_START    = 3;
// mag tag in the path

_de_max_tag = 15;

// char table
cT = ["&#252;","&frac12;","&iexcl;","&cent;","&pound;","&yen;","&sect;","&uml;","&copy;","&laquo;","&not;","&reg;","&deg;","&plusmn;","&acute;","&micro;","&para;","&middot;","&cedil;","&raquo;","&iquest;","&Agrave;","&Aacute;","&Acirc;","&Atilde;","&Auml;","&Aring;","&AElig;","&Ccedil;","&Egrave;","&Eacute;","&Ecirc;","&Euml;","&Igrave;","&Iacute;","&Icirc;","&Iuml;","&Ntilde;","&Ograve;","&Oacute;","&Ocirc;","&Otilde;","&Ouml;","&Oslash;","&Ugrave;","&Uacute;","&Ucirc;","&szlig;","&agrave;","&aacute;","&acirc;","&atilde;","&auml;","&aring;","&aelig;","&ccedil;","&egrave;","&eacute;","&ecirc;","&euml;","&igrave;","&iacute;","&icirc;","&iuml;","&ntilde;","&ograve;","&oacute;","&ocirc;","&otilde;","&ouml;","&divide;","&oslash;","&ugrave;","&uacute;","&ucirc;","&yuml;","&#8218;","&#402;","&#8222;","&#8230;","&#8224;","&#8225;","&#710;","&#8240;","&#8249;","&#338;","&#8216;","&#8217;","&#8220;","&#8221;","&#8226;","&#8211;","&#8212;","&#732;","&#8482;","&#8250;","&#339;","&#376;"];//"&nbsp;",,

// set smart undo/redo
smart_undo_redo = false;

// history length
_de_max_history_item = 50;

// Main stylesheet file
var main_css_file = "styles.css";
var style_id = "de_b";
var _de_temp_element = "_de_temp_element";

// DOCTYPE
var _de_doctype = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
var combostyle_flag = false;

// reg expr
var docType_expr	= /(<!doctype[^>]+>)/i ;
var HtmlContents	= /([\s\S]*\<html[^\>]*\>)([\s\S]*)(\<\/html\>[\s\S]*)/i ;
var HtmlContents1	= /([\s\S]*\<html[^\>]*\>)/i ;
var HtmlContents2	= /(\<\/html\>[\s\S]*)/i ;
var InsideBody		= /(<body[^\>]*\>)/i ;
var BodyContents	= /([\s\S]*\<body[^\>]*\>)([\s\S]*)(\<\/body\>[\s\S]*)/i ;
var HeadContents	= /([\s\S]*\<head[^\>]*\>)([\s\S]*)(\<\/head\>[\s\S]*)/i ;
var begin_edit_expr	= /(<!--[#\s]*?(?:Instance|Template|)BeginEditable[^>]+>)/gi;
var end_edit_expr	= /(<!--[#\s]*?(?:Instance|Template|)EndEditable[^>]+>)/gi;

var start_block_expr	= /(<!--[#\s]*?StartBlockNoDelete[^>]+>)/gi;
var end_block_expr	= /(<!--[#\s]*?EndBlockNoDelete[^>]+>)/gi;
var start_block_expr2	= /(<!--[#\s]*?StartBlockCanDelete[^>]+>)/gi;
var end_block_expr2	= /(<!--[#\s]*?EndBlockCanDelete[^>]+>)/gi;

var remove_block_expr	= /((<!--[#\s]*?StartBlock(?:No|Can)Delete[^>]+>\s*?)(<div\s+id="?de_add[^>]+>\s*?<img[^>]+>\s*?(?:<img[^>]+>|))([\s\S]*?)(<\/div>)\s*?(<!--[#\s]*?EndBlock(?:No|Can)Delete[^>]+>))/gi;

var edit_expr		= /(<!--[#\s]*?(?:Instance|Template|)BeginEditable[^>]+>[\s\S]*?<!--[#\s]*?(?:Instance|Template|)EndEditable[^>]+>)/gi;
var wrap_tag_expr	= /(<!--[#\s]*?(?:Instance|Template|)BeginEditable[^\<]+<div\s+id="?de_wrap_div[^>]+>)/gi;
var wrap_end_tag_expr	= /(<\/div><!--[#\s]*?(?:Instance|Template|)EndEditable[^>]+>)/gi;;
var meta_keywords_expr	= /(<meta[^>]+name="?keywords"?[^>]+>)/gi;;
var meta_description_expr	= /(<meta[^>]+name="?description"?[^>]+>)/gi;;
var title_expr	= /(<title>([\s\S]*)<\/title>)/gi;;

// ShortCut Keys: Ctrl + []
ShortCutKeys = [
	["B" , "Bold"],
	["U" , "Underline"],
	["I" , "Italic"],
	["Z" , "Undo"],
	["Y" , "Redo"],
	["D" , "Pastefrommsword"],
	["K" , "CreateLink"],	
	["M" , "Image"]
];

specialKeyCode = [90,89,32,13,46,37,38,39,40];
sKey = new Array();
sKey[37] = 1;
sKey[38] = 1;
sKey[39] = 1;
sKey[40] = 1;
sKey[13] = 1;
sKey[32] = 1;
sKey[90] = 1;
sKey[89] = 1;
sKey[46] = 1;

// Toolbar Buttons Sets
DevEditToolbars  = ['Complete','Simple'];
DevEditToolbarSets = new Array();
DevEditToolbarSets["footer"] = [['GeckoXPConnect','EditorMode']];
DevEditToolbarSets["path"] = [['Path']];
DevEditToolbarSets["_source"] = [
	['Save','Fullscreen','Cut','Copy','Paste','Findreplace','-','UndoSource','RedoSource'],
	[]
];

DevEditToolbarSets["_preview"] = [['Previewlabel']];

DevEditToolbarSets["Complete"] = [
	['Save','Fullscreen','Cut','Copy','Paste','Findreplace','-','Undo','Redo','-','Spellcheck','-','RemoveFormat','-','Bold','Underline','Italic','Strikethrough','-','OrderedList','UnorderedList','Indent','Outdent','-','SubScript','SuperScript','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyFull','-','CreateLink','CreateEmailLink','Anchor','-','Help'],
	['Fontname','Fontsize','Formatblock','Styles','-','Fontcolor','Highlight','-','Table','-','Form','-','Flash','Image','Media','-','Inserttextbox','HR','Insertchars','Pageproperties','Clearcode','Custominsert','Toggleposition','Showborders','Paragraph']
];

DevEditToolbarSets["Simple"] = [
	['Save','Fullscreen','Cut','Copy','Paste','Findreplace','-','Undo','Redo','-','Bold','Underline','Italic','-','OrderedList','UnorderedList','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyFull','-','CreateLink','-','Fontname','Fontsize','-','Fontcolor','Highlight','-','Image']
];


// Popups
Popups = new Array();

Popups["paste"] = [
	['Paste','Paste	Ctrl+V','paste.gif'],
	['Pastetext','Paste as Plain Text','pastetext.gif'],
	['Pastefrommsword','Paste from MS Word	Ctrl+D','pasteword.gif']
];

Popups["form"] = [
	['Insertform','Insert Form ...','insertform.gif'],
	['Modifyform','Modify Form Properties ...','modifyform.gif'],
	['-','-'],
	['Inserttextfield',	['Insert Text Field ...','Modify Text Field ...'],'inserttextfield.gif'],
	['Inserttextarea',	['Insert Text Area ...','Modify Text Area ...'],'inserttextarea.gif'],
	['Inserthidden',	['Insert Hidden Field ...','Modify Hidden Field ...'],'inserthidden.gif'],
	['Insertbutton',	['Insert Button ...','Modify Button ...'],'insertbutton.gif'],
	['Insertcheckbox',	['Insert Checkbox ...','Modify Checkbox ...'],'insertcheckbox.gif'],
	['Insertradio',		['Insert Radio ...','Modify Radio ...'],'insertradio.gif'],
	['Insertselect',	['Insert Select Field ...','Modify Select Field ...'],'insertselect.gif']
];

Popups["table"] = [
	['Inserttable','Insert Table ...','inserttable.gif'],
	//['Quicktable','Quick Table ...','inserttable.gif'],	
	['Modifytable','Modify Table Properties ...','modifytable.gif'],
	['Modifycell','Modify Cell Properties ...','modifycell.gif'],
	['-','-'],
	['Insertcolumnright','Insert Column to the Right','insertcolumnright.gif'],
	['Insertcolumnleft','Insert Column to the Left','insertcolumnleft.gif'],
	['-','-'],
	['Insertrowabove','Insert Row Above','insertrowabove.gif'],
	['Insertrowbelow','Insert Row Below','insertrowbelow.gif'],
	['-','-'],
	['Deleterow','Delete Row','deleterow.gif'],
	['Deletecolumn','Delete Column','deletecolumn.gif'],
	['-','-'],
	['Increasecolumnspan','Increase Column Span','increasecolumnspan.gif'],
	['Decreasecolumnspan','Decrease Column Span','decreasecolumnspan.gif'],
	['-','-'],
	['Increaserowspan','Increase Row Span','increaserowspan.gif'],
	['Decreaserowspan','Decrease Row Span','decreaserowspan.gif']
];

Popups["contextmenu"] = [
	['Cut','Cut','cut.gif'],
	['Copy','Copy','copy.gif'],
	['Paste','Paste','paste.gif'],
	['Pastetext','Paste as Plain Text','pastetext.gif'],
	['Pastefrommsword','Paste from MS Word','pasteword.gif'],
	['-','-'],
	['Modifytable','Modify Table Properties ...','modifytable.gif','TABLE'],
	['Modifycell','Modify Cell Properties ...','modifycell.gif','TD'],
	['-','-',,'TABLE'],
	['Insertcolumnright','Insert Column to the Right','insertcolumnright.gif','TABLE'],
	['Insertcolumnleft','Insert Column to the Left','insertcolumnleft.gif','TABLE'],
	['-','-',,'TABLE'],
	['Insertrowabove','Insert Row Above','insertrowabove.gif','TABLE'],
	['Insertrowbelow','Insert Row Below','insertrowbelow.gif','TABLE'],
	['-','-',,'TABLE'],
	['Deleterow','Delete Row','deleterow.gif','TABLE'],
	['Deletecolumn','Delete Column','deletecolumn.gif','TABLE'],
	['-','-',,'TABLE'],
	['Increasecolumnspan','Increase Column Span','increasecolumnspan.gif','TABLE'],
	['Decreasecolumnspan','Decrease Column Span','decreasecolumnspan.gif','TABLE'],
	['-','-',,'TABLE'],
	['Increaserowspan','Increase Row Span','increaserowspan.gif','TABLE'],
	['Decreaserowspan','Decrease Row Span','decreaserowspan.gif','TABLE'],
	['-','-',,'TABLE'],	
	['Image','Modify Image Properties...','insertimage.gif','IMG'],
	['CreateLink','Create or Modify Link...','createlink.gif','text|IMG|A'],
	['Flash','Modify Flash Properties...','flash.gif','flash'],
	['Media','Modify Media Properties...','media.gif','media'],
	['-','-',,'text|IMG|A|flash|media'],
	['Spellcheck','Check Spelling...','spellcheck.gif']
];

Popups["spellmenu"] = [
	['AddToDictionary', "Add to Dictionary", 'addtodictionary.gif']
];

Popups["sourcetag"] = [];

useXHTML = true;

mFontname = [
	["Default",""],
	["Arial","Arial"],
	["Verdana","Verdana"],
	["Tahoma","Tahoma"],
	["Courier New","Courier New"],
	["Georgia","Georgia"],
	["-",""],
	["Remove Font",""]
];

mFontsize = [
	["Size 1","8pt","1"],
	["Size 2","10pt","2"],
	["Size 3","12pt","3"],
	["Size 4","14pt","4"],
	["Size 5","18pt","5"],
	["Size 6","24pt","6"],
	["Size 7","36pt","7"],
	["-",""],
	["Remove Size","","3"]
];

mFormatblock = [
	["Normal","<P>"],
	["Heading 1","<H1>"],
	["Heading 2","<H2>"],
	["Heading 3","<H3>"],
	["Heading 4","<H4>"],
	["Heading 5","<H5>"],
	["Heading 6","<H6>"],
	["-",""],
	["Remove Formatting","<P>"]
];

CustomLink = [
	["DevEdit", "http://www.devedit.com"]
];

var emptyTags = new Array();
emptyTags['br'] = 1;
emptyTags['img'] = 1;
emptyTags['input'] = 1;
emptyTags['hr'] = 1;
emptyTags['link'] = 1;
emptyTags['meta'] = 1;
emptyTags['embed'] = 1;
emptyTags['area'] = 1;
emptyTags['param'] = 1;
emptyTags['base'] = 1;
emptyTags['basefont'] = 1;


var tab_pos = new Array();
tab_pos["EditMode"] = 0;
tab_pos["EditModeup"] = 1;
tab_pos["SourceMode"] = 2;
tab_pos["SourceModeup"] = 3;
tab_pos["PreviewMode"] = 4;
tab_pos["PreviewModeup"] = 5;

var tlb_pos = new Array();
tlb_pos["addtodictionary"] = 0;
tlb_pos["anchor"] = 1;
tlb_pos["bold"] = 2;
tlb_pos["clearcode"] = 3;
tlb_pos["copy"] = 4;
tlb_pos["createemaillink"] = 5;
tlb_pos["createlink"] = 6;
tlb_pos["custominsert"] = 7;
tlb_pos["cut"] = 8;
tlb_pos["decreasecolumnspan"] = 9;
tlb_pos["deletecolumn"] = 10;
tlb_pos["email"] = 11;
tlb_pos["file"] = 12;
tlb_pos["find"] = 13;
tlb_pos["findreplace"] = 14;
tlb_pos["flash"] = 15;
tlb_pos["fontcolor"] = 16;      
tlb_pos["fontcolor2"] = 17;      
tlb_pos["form"] = 18;      
tlb_pos["fullscreen"] = 19;      
tlb_pos["help"] = 20;      
tlb_pos["hidden_icon"] = 21;      
tlb_pos["highlight"] = 22;      
tlb_pos["highlight2"] = 23;      
tlb_pos["hr"] = 24;      
tlb_pos["image"] = 25;      
tlb_pos["increasecolumnspan"] = 26;      
tlb_pos["indent"] = 27;      
tlb_pos["insertbutton"] = 28;
tlb_pos["insertchars"] = 29;
tlb_pos["insertcheckbox"] = 30;
tlb_pos["insertcolumnleft"] = 31;      
tlb_pos["insertcolumnright"] = 32;      
tlb_pos["insertform"] = 33;
tlb_pos["inserthidden"] = 34;      
tlb_pos["inserthorizontalrule"] = 35;
tlb_pos["insertimage"] = 36;
tlb_pos["insertorderedlist"] = 37;
tlb_pos["insertradio"] = 38;
tlb_pos["insertrowabove"] = 39;
tlb_pos["insertrowbelow"] = 40;
tlb_pos["insertselect"] = 41;
tlb_pos["inserttable"] = 42;      
tlb_pos["inserttextarea"] = 43;      
tlb_pos["inserttextbox"] = 44;      
tlb_pos["inserttextfield"] = 45;      
tlb_pos["insertunorderedlist"] = 46;      
tlb_pos["italic"] = 47;
tlb_pos["justifycenter"] = 48;      
tlb_pos["justifyfull"] = 49;
tlb_pos["justifyleft"] = 50;
tlb_pos["justifyright"] = 51;
tlb_pos["mail"] = 52;
tlb_pos["media"] = 53;
tlb_pos["modifycell"] = 54;
tlb_pos["modifyform"] = 55;
tlb_pos["modifytable"] = 56;
tlb_pos["outdent"] = 57; 
tlb_pos["pageproperties"] = 58;
tlb_pos["paragraph"] = 59;
tlb_pos["paste"] = 60;
tlb_pos["pastetext"] = 61;
tlb_pos["pasteword"] = 62;
tlb_pos["pastefrommsword"] = 62;
tlb_pos["preview"] = 63;
tlb_pos["print"] = 64;
tlb_pos["redo"] = 65;
tlb_pos["removeformat"] = 66;
tlb_pos["save"] = 67;
tlb_pos["showborders"] = 68;
tlb_pos["spellcheck"] = 69;
tlb_pos["strikethrough"] = 70;
tlb_pos["subscript"] = 71; 
tlb_pos["superscript"] = 72;
tlb_pos["table"] = 73;
tlb_pos["underline"] = 74;
tlb_pos["undo"] = 75;
tlb_pos["toggleposition"] = 76;
tlb_pos["deleterow"] = 77;
tlb_pos["newtag"] = 78;
tlb_pos["increaserowspan"] = 79;
tlb_pos["decreaserowspan"] = 80;

border_style = '#de_wrap_div{border:1px solid #9cf}#de_wrap_div1{border:1px solid #9cf}#de_wrap_div2{border:1px solid #9cf}#de_wrap_div3{border:1px solid #9cf}#de_wrap_div4{border:1px solid #9cf}#de_wrap_div5{border:1px solid #9cf}#de_wrap_div6{border:1px solid #9cf}#de_wrap_div7{border:1px solid #9cf}#de_wrap_div8{border:1px solid #9cf}#de_wrap_div9{border:1px solid #9cf}#de_wrap_div10{border:1px solid #9cf}#de_wrap_div11{border:1px solid #9cf}#de_wrap_div12{border:1px solid #9cf}#de_wrap_div13{border:1px solid #9cf}#de_wrap_div14{border:1px solid #9cf}#de_wrap_div15{border:1px solid #9cf}form{border:dotted #F00 1px}table{border:dotted #BFBFBF 1px}td{border:dotted #BFBFBF 1px}.de_media_file{border:darkgray 1px solid;background-position:center center;background-image:url(skins/default/media_file.gif);background-repeat:no-repeat}.de_flash_file{border:darkgray 1px solid;background-position:center center;background-image:url(skins/default/flash_file.gif);background-repeat:no-repeat}.de_style_input{width:20px;height:20px;background-repeat:no-repeat;background-image:url(skins/default/hidden_icon.gif);padding-left:20px;font-size:45px;display:block}.de_style_anchor{width:20px;height:20px;padding-left:20px;background-repeat:no-repeat;background-image:url(skins/default/anchor_icon.gif)}';

// alert messages
security_error = "This feature is not currently available in FireFox. To turn this feature on, please click on the Help icon and follow the instructions. Alternatively, use the keyboard shortcuts such as CTRL+X, CTRL+C and CTRL+V";
delete_block_msg = "Are you sure you wish to delete this block?";