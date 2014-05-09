// Name: XHTML function
// Info: XHTML function.

var a = new Array();
a['p'] = 1;             a['span'] = 1;		    a['a'] = 1;	          a['div'] = 1;
a['b'] = 1;		          a['u'] = 1;	          a['i'] = 1;	          a['td'] = 1;
a['img'] = 1;		        a['table'] = 1;	      a['input'] = 1;	      a['li'] = 1;
a['ol'] = 1;	          a['script'] = 1;	    a['br'] = 1;	        a['textarea'] = 1;
a['strong'] = 1;		    a['center'] = 1;	    a['cite'] = 1;	      a['code'] = 1;
a['col'] = 10;		      a['colgroup'] = 1;	  a['dd'] = 1;	        a['del'] = 1;
a['dir'] = 1;  		      a['dfn'] = 1; 	      a['acronym'] = 1;	    a['dl'] = 1;
a['dt'] = 1;		        a['em'] = 1;	        a['fieldset'] = 1;	  a['font'] = 1;
a['form'] = 1;		      a['frame'] = 1;	      a['frameset'] = 1;	  a['h1'] = 1;
a['h2'] = 1;		        a['h3'] = 1;	        a['h4'] = 1;	        a['h5'] = 1;
a['h6'] = 1;		        a['head'] = 1;	      a['hr'] = 1;	        a['html'] = 1;
a['area'] = 1;		      a['iframe'] = 1;	    a['base'] = 1;	      a['bdo'] = 1;
a['ins'] = 1;	  	      a['isindex'] = 1;	    a['kbd'] = 1;	        a['label'] = 1;
a['legend'] = 1;		    a['big'] = 1;	        a['link'] = 1;	      a['map'] = 1;
a['menu'] = 1;		      a['meta'] = 1;	      a['noframes'] = 1;	  a['noscript'] = 1;
a['object'] = 1;		    a['blockquote'] = 1;	a['optgroup'] = 1;	  a['option'] = 1;
a['!doctype'] = 1;      a['param'] = 1;	      a['pre'] = 1;	        a['q'] = 1;
a['s'] = 1;             a['samp'] = 1;	      a['body'] = 1;	      a['select'] = 1;
a['small'] = 1; 		    a['abbr'] = 1;	      a['strike'] = 1;	    a['caption'] = 1;
a['style'] = 1; 	      a['sub'] = 1;	        a['sup'] = 1;	        a['basefont'] = 1;
a['tbody'] = 1;	        a['address'] = 1;	    a['button'] = 1;	    a['tfoot'] = 1;
a['th'] = 1;		        a['thead'] = 1;	      a['title'] = 1;	      a['tr'] = 1;
a['tt'] = 1;		        a['applet'] = 1;	    a['ul'] = 1;	        a['v'] = 1;
a['embed'] = 1;         a['marquee'] = 1;     a['!doctype'] = 1;    a['!--'] = 1;
_xhtml_parser_aTags = a;

function getXHTML(content) {
	content = content.replace(/<br>/gi,"<br/>");
	content = content.replace(/(<img([^>]+)>)/gi,function(s1,s2){return s2.replace(/\/?>/gi,"/>");});
	content = content.replace(/(<input([^>]+)>)/gi,function(s1,s2){return s2.replace(/\/?>/gi,"/>");});
	content = content.replace(/(<param([^>]+)>)/gi,function(s1,s2){return s2.replace(/\/?>/gi,"/>");});
	content = content.replace(/(<embed([^>]+)>)/gi,function(s1,s2){return s2.replace(/\/?>/gi,"/>");});
	return (content);
}