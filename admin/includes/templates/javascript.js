
// Tells AJAX what to do with the returned data
var ajaxWhat = "";
var ajaxData = "";
var ajaxButt = null;

function ShowQuickHelp(div, title, desc)
{
	div = document.getElementById(div);
	div.style.display = 'inline';
	div.style.position = 'absolute';
	div.style.width = '185';
	div.style.backgroundColor = '#FEFCD5';
	div.style.border = 'solid 1px #E7E3BE';
	div.style.padding = '10px';
	div.innerHTML = '<span class=helpTip><b>' + title + '</b></span><br><img src=images/1x1.gif width=1 height=5><br><div style="padding-left:10; padding-right:5" class=helpTip>' + desc + '</div>';
}

function ShowHelp(div, title, desc)
{
	div = document.getElementById(div);
	div.style.display = 'inline';
	div.style.position = 'absolute';
	div.style.width = '240';
	div.style.backgroundColor = '#FEFCD5';
	div.style.border = 'solid 1px #E7E3BE';
	div.style.padding = '10px';
	div.innerHTML = '<span class=helpTip><b>' + title + '</b></span><br><img src=images/1x1.gif width=1 height=5><br><div style="padding-left:10; padding-right:5" class=helpTip>' + desc + '</div>';
}

function HideHelp(div)
{
	div = document.getElementById(div);
	div.style.display = 'none';
}

function doCustomDate(myObj, tab) {
	if (myObj.options[myObj.selectedIndex].value == "Custom") {
		document.getElementById("customDate"+tab).style.display = ""
		document.getElementById("showDate"+tab).style.display = "none"
	} else {
		document.getElementById("customDate"+tab).style.display = "none"
		document.getElementById("showDate"+tab).style.display = ""
	}
}

function inArray(id, arraylist, returnvalue) {
	for (alitem = 0; alitem < arraylist.length; alitem++) {
		val = arraylist[alitem].toString();
		if (id == val) {
			if (returnvalue)
			{
				return alitem;
			}
			return true;
		}
	}

	if (returnvalue)
	{
		return -1;
	}
	return false;
}

function display(RowID) {
	Row = RowID + "_detail";

	var table = document.getElementById(Row);
	var rowCount = table.rows.length;

	for (i = 1; i < rowCount; i++) {
		table.rows[i].style.display = "";
	}

	document.getElementById(RowID + "plus").style.display = "none"
	document.getElementById(RowID + "minus").style.display = ""
}

function hide(RowID) {
	Row = RowID + "_detail";
	var table = document.getElementById(Row);
	var rowCount = table.rows.length;

	for (i = 1; i < rowCount; i++) {
		table.rows[i].style.display = "none";
	}

	document.getElementById(RowID + "plus").style.display = ""
	document.getElementById(RowID + "minus").style.display = "none"
}

function getIFrameDocument(aID){
	// if contentDocument exists, W3C compliant (Mozilla)
	if (document.getElementById(aID).contentDocument){
		return document.getElementById(aID).contentDocument;
	} else {
		// IE
		return document.frames[aID].document;
	}
}

function ShowCustomFields(contentarea, editorname, pagename) {
	var top = screen.height / 2 - (170);
	var left = screen.width / 2 - (180);
	window.open('index.php?Page=ShowCustomFields&EditorName=' + editorname + '&ContentArea=' + contentarea + '&PageName=' + pagename, 'CustomFields', 'left=' + left + ', top=' + top + ', toolbar=false, status=no, directories=false, menubar=false, scrollbars=yes, resizable=true, copyhistory=false, width=360, height=400');
}

function ShowCustomFields_toolbar(contentarea, editorname) {
	var top = screen.height / 2 - (170);
	var left = screen.width / 2 - (180);
	window.open('../index.php?Page=ShowCustomFields&EditorName=' + editorname + '&ContentArea=' + contentarea, 'CustomFields', 'left=' + left + ', top=' + top + ', toolbar=false, status=no, directories=false, menubar=false, scrollbars=yes, resizable=true, copyhistory=false, width=360, height=400');
}

// Used in text areas to make sure text is inserted into the Text area
function insertAtCursor(myField, myValue) {
	if (document.selection) {
		myField.focus();
		sel = document.selection.createRange();
		sel.text = myValue;
	} else {
		if (myField.selectionStart || myField.selectionStart == '0') {
			var startPos = myField.selectionStart;
			var endPos = myField.selectionEnd;
			myField.value = myField.value.substring(0, startPos)
			+ myValue
			+ myField.value.substring(endPos, myField.value.length);
			} else {
			myField.value += myValue;
		}
	}
}

function InsertLink(placeholder, contentarea, editorname) {
	placeholder = '%%' + placeholder + '%%';
	if (contentarea == 'TextContent') {
		if (window.opener) {
			// window.opener.document.getElementById(contentarea).value += placeholder;
			window.opener.insertAtCursor(window.opener.document.getElementById(contentarea), placeholder);
			window.close();
		} else {
			insertAtCursor(document.getElementById(contentarea), placeholder);
			// document.getElementById(contentarea).value += placeholder;
		}
		return;
	}

	// set the default for the editor name.
	if (!editorname || editorname == 'undefined')
	{
		editorname = 'myDevEditControl';
	}

	if (placeholder == '%%unsubscribelink%%')
	{
		placeholder = "<a href='%%unsubscribelink%%'>" + UnsubLinkPlaceholder + "</a>";
	}

	if (window.opener) {
		eval("var editr = window.opener." + editorname);
		editr.insertHTML(placeholder);
		window.close();
		return;
	}

	eval("var editr = " + editorname);
	editr.insertHTML(placeholder);
}


function InsertUnsubscribeLink(contentarea, editorname) {
	InsertLink('unsubscribelink', contentarea, editorname);
}


Array.prototype.compareArrays = function(arr) {
	if (this.length != arr.length) return false;
	for (var i = 0; i < arr.length; i++) {
		if (this[i].compareArrays) { //likely nested array
			if (!this[i].compareArrays(arr[i])) return false;
			else continue;
		}
		if (this[i] != arr[i]) return false;
	}
	return true;
}

function enableDate_SubscribeDate(formElement, datefield) {
	if (formElement.checked) {
		document.getElementById(datefield).style.display = ""
	} else {
		document.getElementById(datefield).style.display = "none"
	}
}

function ChangeFilterOptionsSubscribeDate(formElement, datefield) {
	if (formElement.selectedIndex == 3) {
		document.getElementById(datefield+"date2").style.display = ""
	} else {
		document.getElementById(datefield+"date2").style.display = "none"
	}
}

var LinkSelectBox = "";
var LinksLoaded = false;

function enable_ClickedLink(formElement, linkfield, linkselect, loadingmessage, chosen_link) {
	LinkSelectBox = linkselect;
	if (formElement.checked) {
		document.getElementById(linkfield).style.display = "";

		if (!LinksLoaded) {
			linkselect = document.getElementById(linkselect);
			linkselect.options.length = 0;
			linkselect.options[0] = new Option(loadingmessage, '-2');
			ajaxWhat = "LoadLinks(" + chosen_link + ")";
			DoCallback('what=importlinks');
		}
	} else {
		document.getElementById(linkfield).style.display = "none";
	}
}

function LoadLinks(linkid) {
	LinksLoaded = true;
	mylinks = new Array();
	ajaxData = unescape(ajaxData);
	eval(ajaxData);
	linkselect = document.getElementById(LinkSelectBox);
	linkselect.options[0] = null;

	for(lnk in mylinks) {
		// we need to do this because eval'ing an array also evals prototype functions etc that go with it.
		// and we use that (above)...
		if (isNaN(lnk)) {
			continue;
		}

		linkselect.options[linkselect.options.length] = new Option(mylinks[lnk], lnk);

		// do we need to preselect a link?
		if (linkid == lnk)
		{
			linkselect.options[linkselect.options.length-1].selected = true;
		}
	}
}

var NewsSelectBox = "";
var NewsLoaded = false;

function enable_OpenedNewsletter(formElement, newsfield, newsselect, loadingmessage, chosen_news) {
	NewsSelectBox = newsselect;
	if (formElement.checked) {
		document.getElementById(newsfield).style.display = "";

		if (!NewsLoaded) {
			newsselect = document.getElementById(newsselect);
			newsselect.options.length = 0;
			newsselect.options[0] = new Option(loadingmessage, '-2');
			ajaxWhat = "LoadNewsletter(" + chosen_news + ")";
			DoCallback('what=importnewsletters');
		}
	} else {
		document.getElementById(newsfield).style.display = "none";
	}
}

function LoadNewsletter(chosen_news) {
	NewsLoaded = true;
	mynews = new Array();
	ajaxData = unescape(ajaxData);
	eval(ajaxData);
	newsselect = document.getElementById(NewsSelectBox);
	newsselect.options[0] = null;

	for(news in mynews) {
		// we need to do this because eval'ing an array also evals prototype functions etc that go with it.
		// and we use that (above)...
		if (isNaN(news)) {
			continue;
		}

		newsselect.options[newsselect.options.length] = new Option(mynews[news], news);

		// do we need to preselect a link?
		if (news == chosen_news)
		{
			newsselect.options[newsselect.options.length-1].selected = true;
		}
	}
}

function switchContentSource(HTMLOrText, Id)
{
	// Toggle the WYSIWYG editor, file upload box, or web file import box
	if(HTMLOrText == 'html')
	{
		var htmlEditor = document.getElementById('htmlEditor');
		var htmlCF = document.getElementById('htmlCF');
		var htmlNLFile = document.getElementById('htmlNLFile');
		var htmlNLImport = document.getElementById('htmlNLImport');
		var newsletterurl = document.getElementById('newsletterurl');

		switch(Id)
		{
			case 1:
			{
				document.getElementById('hct1').checked = true;
				htmlEditor.style.display = '';
				htmlCF.style.display = '';
				htmlNLFile.style.display = 'none';
				htmlNLImport.style.display = 'none';
				break;
			}
			case 2:
			{
				document.getElementById('hct2').checked = true;
				htmlEditor.style.display = 'none';
				htmlCF.style.display = 'none';
				htmlNLFile.style.display = '';
				htmlNLImport.style.display = 'none';
				break;
			}
			case 3:
			{
				document.getElementById('hct3').checked = true;
				htmlEditor.style.display = 'none';
				htmlCF.style.display = 'none';
				htmlNLFile.style.display = 'none';
				htmlNLImport.style.display = '';
				newsletterurl.focus();
				newsletterurl.select();
				break;
			}
		}
	}
	else
	{
		var textEditor = document.getElementById('textEditor');
		var textCF = document.getElementById('textCF');
		var textNLFile = document.getElementById('textNLFile');
		var textNLImport = document.getElementById('textNLImport');
		var newsletterurl = document.getElementById('textnewsletterurl');

		switch(Id)
		{
			case 1:
			{
				document.getElementById('tct1').checked = true;
				textEditor.style.display = '';
				textCF.style.display = '';
				textNLFile.style.display = 'none';
				textNLImport.style.display = 'none';
				break;
			}
			case 2:
			{
				document.getElementById('tct2').checked = true;
				textEditor.style.display = 'none';
				textCF.style.display = 'none';
				textNLFile.style.display = '';
				textNLImport.style.display = 'none';
				break;
			}
			case 3:
			{
				document.getElementById('tct3').checked = true;
				textEditor.style.display = 'none';
				textCF.style.display = 'none';
				textNLFile.style.display = 'none';
				textNLImport.style.display = '';
				newsletterurl.focus();
				newsletterurl.select();
				break;
			}
		}
	}
}

function createCookie(name,value,days)
{
	if (days)
	{
		var date = new Date();
		date.setTime(date.getTime()+(days*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
	} else {
		var expires = "";
	}
	document.cookie = name+"="+value+expires+"; path=/";
}

/**
 * Gets the value of the specified cookie.
 *
 * name  Name of the desired cookie.
 *
 * Returns a string containing value of specified cookie,
 *   or null if cookie does not exist.
 */
function getCookie(name) {
	var dc = document.cookie;
	var prefix = name + "=";
	var begin = dc.indexOf("; " + prefix);
	if (begin == -1) {
		begin = dc.indexOf(prefix);
		if (begin != 0) return null;
	} else {
		begin += 2;
	}
	var end = document.cookie.indexOf(";", begin);
	if (end == -1) {
		end = dc.length;
	}
	return unescape(dc.substring(begin + prefix.length, end));
}

function ChangePaging(page, formaction, DisplayName) {
	paging = document.getElementById('PerPageDisplay' + DisplayName);
	pagingId = paging.selectedIndex;
	pagingamount = paging[pagingId].value;
	document.location = 'index.php?Page=' + page + '&' + formaction + '&PerPageDisplay'+DisplayName+'=' + pagingamount;
}

function toggleAllCheckboxes(check)
{
	formObj = check.form;
	for (var i=0;i < formObj.length; i++) {
		fldObj = formObj.elements[i];
		if (fldObj.type == 'checkbox') {
			fldObj.checked = check.checked;
		}
	}
}

function ImportWebsite(Butt, description, importtype, newButtonDesc, errorMsg)
{
	check_form = ImportCheck(importtype);
	if (!check_form) {
		return;
	}

	var url = "";
	if (importtype.toLowerCase() == 'text') {
		url = document.getElementById('textnewsletterurl').value;
	} else {
		url = document.getElementById('newsletterurl').value;
	}
	ajaxWhat = "DoImport('website', '" + importtype + "', '" + newButtonDesc + "', '" + errorMsg + "');";
	DoCallback('what=importurl&url='+url);

	ajaxButt = Butt;
	Butt.value = description;
	Butt.style.width = "150px";
	Butt.disabled = true;
}

function DoImport(importtype, TextOrHTML, newButtonDesc, errorMsg)
{
	if (ajaxButt)
	{
		ajaxButt.value = newButtonDesc;
		ajaxButt.style.width = "70px";
		ajaxButt.disabled = false;
		ajaxButt = null;
	} else {
		ajaxData = unescape(ajaxData);
	}

	if(ajaxData.length == 0)
	{
		alert(errorMsg);
	}
	else
	{
		if (TextOrHTML.toLowerCase() == 'text') {
			switchContentSource('text', 1);
			document.getElementById('TextContent').value = ajaxData;
		} else {
			// Everything was OK
			switchContentSource('html', 1);
			myDevEditControl.writeHTMLContent(ajaxData);
		}
	}
}

function DoCallback(data)
{
	var url = 'remote.php';

	// branch for native XMLHttpRequest object
	if (window.XMLHttpRequest) {
		req = new XMLHttpRequest();
		req.onreadystatechange = processReqChange;
		req.open('POST', url, true);
		req.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		req.send(data);
	// branch for IE/Windows ActiveX version
	} else if (window.ActiveXObject) {
		req = new ActiveXObject('Microsoft.XMLHTTP')
		if (req) {
			req.onreadystatechange = processReqChange;
			req.open('POST', url, true);
			req.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
			req.send(data);
		}
	}
}

function processReqChange() {
	// only if req shows 'loaded'
	if (req.readyState == 4) {
		// only if 'OK'
		if (req.status == 200) {
			ajaxData = req.responseText;
			eval(ajaxWhat);
		} else {
			alert('There was a problem retrieving the XML data:\n' + req.responseText);
		}
	}
}

function ShowTab(T)
{
	if (TabSize != null) {
		maxSize = TabSize + 1;
	} else {
		maxSize = 7;
	}

	for(i = 1; i < maxSize; i++)
	{
		document.getElementById("div" + i).style.display = "none";
		document.getElementById("tab" + i).className = "";
	}

	document.getElementById("div" + T).style.display = "";
	document.getElementById("tab" + T).className = "active";
}

function CheckRadio(Id)
{
	for(i = 0; i < document.forms[0].elements.length; i++) {
		if(document.forms[0].elements[i].type == "radio") {
			if(document.forms[0].elements[i].id.indexOf(Id) == 0) {
				if(document.forms[0].elements[i].checked) {
					return true;
				}
			}
		}
	}
	return false;
}

function CheckMultiple(name) {
	frm = document.forms[0];
	for (var i=0; i < frm.length; i++)
	{
		fldObj = frm.elements[i];
		var fieldnamecheck=fldObj.name.indexOf(name);
		if (fieldnamecheck != -1) {
			if (fldObj.checked) {
				return true;
			}
		}
	}
	return false;
}

var keyStr = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";

function decode64(input) {
	var output = "";
	var chr1, chr2, chr3;
	var enc1, enc2, enc3, enc4;
	var i = 0;

	// remove all characters that are not A-Z, a-z, 0-9, +, /, or =
	input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");

	do {
		enc1 = keyStr.indexOf(input.charAt(i++));
		enc2 = keyStr.indexOf(input.charAt(i++));
		enc3 = keyStr.indexOf(input.charAt(i++));
		enc4 = keyStr.indexOf(input.charAt(i++));

		chr1 = (enc1 << 2) | (enc2 >> 4);
		chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
		chr3 = ((enc3 & 3) << 6) | enc4;

		output = output + String.fromCharCode(chr1);

		if (enc3 != 64) {
			output = output + String.fromCharCode(chr2);
		}

		if (enc4 != 64) {
			output = output + String.fromCharCode(chr3);
		}
	} while (i < input.length);
	return output;
}

/**
 * Convert a single file-input element into a 'multiple' input list
 *
 * Usage:
 *
 *   1. Create a file input element (no name)
 *      eg. <input type="file" id="first_file_element">
 *
 *   2. Create a DIV for the output to be written to
 *      eg. <div id="files_list"></div>
 *
 *   3. Instantiate a MultiSelector object, passing in the DIV and an (optional) maximum number of files
 *      eg. var multi_selector = new MultiSelector( document.getElementById( 'files_list' ), 3 );
 *
 *   4. Add the first element
 *      eg. multi_selector.addElement( document.getElementById( 'first_file_element' ) );
 *
 *   5. That's it.
 *
 *   You might (will) want to play around with the addListRow() method to make the output prettier.
 *
 *   You might also want to change the line
 *       element.name = 'file_' + this.count;
 *   ...to a naming convention that makes more sense to you.
 *
 * Licence:
 *   Use this however/wherever you like, just don't blame me if it breaks anything.
 *
 * Credit:
 *   If you're nice, you'll leave this bit:
 *
 *   Class by Stickman -- http://www.the-stickman.com
 *      with thanks to:
 *      [for Safari fixes]
 *         Luis Torrefranca -- http://www.law.pitt.edu
 *         and
 *         Shawn Parker & John Pennypacker -- http://www.fuzzycoconut.com
 *      [for duplicate name bug]
 *         'neal'
 */
function MultiSelector( list_target, max ) {

	// Where to write the list
	this.list_target = list_target;
	// How many elements?
	this.count = 0;
	// How many elements?
	this.id = 0;
	// Is there a maximum?
	if( max ){
		this.max = max;
	} else {
		this.max = -1;
	};

	/**
	 * Add a new file input element
	 */
	this.addElement = function( element ){

		// Make sure it's a file input element
		if( element.tagName == 'INPUT' && element.type == 'file' ){

			// Element name -- what number am I?
			// element.name = 'file_' + this.id++;
			element.name = 'attachments[]';

			// Add reference to this object
			element.multi_selector = this;

			// What to do when a file is selected
			element.onchange = function(){

				var start_pos = element.value.lastIndexOf("/");

				if (start_pos < 0)
					start_pos = element.value.lastIndexOf("\\");

				var end_pos = element.value.length - 1;

				var file_size = element.value.substring(start_pos, end_pos);

				if (file_size.length > 30)
				{
					alert("This file name is too large and could cause problems in some email clients such as Outlook. Please rename the file to be less than 30 characters and try again.");
					return false;
				}

				// New file input
				var new_element = document.createElement( 'input' );
				new_element.type = 'file';
				new_element.className = "field250";

				// Add new element
				this.parentNode.insertBefore( new_element, this );

				// Apply 'update' to element
				this.multi_selector.addElement( new_element );

				// Update list
				this.multi_selector.addListRow( this );

				// Hide this: we can't use display:none because Safari doesn't like it
				this.style.position = 'absolute';
				this.style.left = '-1000px';

			};
			// If we've reached maximum number, disable input element
			if( this.max != -1 && this.count >= this.max ){
				element.disabled = true;
			};

			// File element counter
			this.count++;
			// Most recent element
			this.current_element = element;

		} else {
			// This can only be applied to file input elements!
			alert( 'Error: not a file input element' );
		};

	};

	/**
	 * Add a new row to the list of files
	 */
	this.addListRow = function( element ){

		// Row div
		var new_row = document.createElement( 'div' );

		// Delete button
		var new_div = document.createElement( 'div' );
		new_div.innerHTML = "&nbsp;&nbsp;"
		new_div.style.display = "inline";

		var new_row_button = document.createElement( 'a' );
		// new_row_button.type = 'button';
		new_row_button.innerHTML = 'Remove';
		new_row_button.href = "javascript:void()";

		// References
		new_row.element = element;

		// Delete function
		new_row_button.onclick= function(){

			// Remove element from form
			this.parentNode.element.parentNode.removeChild( this.parentNode.element );

			// Remove this row from the list
			this.parentNode.parentNode.removeChild( this.parentNode );

			// Decrement counter
			this.parentNode.element.multi_selector.count--;

			// Re-enable input element (if it's disabled)
			this.parentNode.element.multi_selector.current_element.disabled = false;

			// Appease Safari
			//    without it Safari wants to reload the browser window
			//    which nixes your already queued uploads
			return false;
		};

		// Set row value
		new_row.innerHTML = element.value;

		// Add button
		new_row.appendChild( new_div );
		new_row.appendChild( new_row_button );

		// Add it to the list
		this.list_target.appendChild( new_row );

	};

};

// we do this to get around the "click here to activate control" issue in internet explorer
// we don't need to do this with firefox, but at least it will be done consistently across all browsers
// for more info see http://msdn.microsoft.com/library/default.asp?url=/workshop/author/dhtml/overview/activating_activex.asp
function PrintChart(contents) {
	document.write(contents);
}

// used by autoresponders, templates & newsletters.
function previewTemplate(selectedValue) {
	if (isNaN(selectedValue)) {
		document.getElementById("imgPreview").src = "resources/email_templates/" + selectedValue+ "/preview.gif";
	} else {
		if (selectedValue > 0) {
			document.getElementById("imgPreview").src = "resources/user_template_previews/" + selectedValue + "_preview.gif";
		} else {
			document.getElementById("imgPreview").src = "images/nopreview.gif";
		}

		document.getElementById("imgPreview").onerror = function (evt) {
			document.getElementById("imgPreview").src = "images/nopreview.gif";
		}
	}
}
