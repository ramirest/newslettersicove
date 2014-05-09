var thefield="";
var go_up, go_down;
var fw=(navigator.userAgent.indexOf("Safari")!=-1)?"2em":"1.6em";

var tp_numbers=/\d*/;
var tp_ampm=/AM|PM/;
var valid_time=/^\d{1,2}:\d{1,2}(:{0,1})(\d{1,2}){0,1}(AM|PM){0,1}/i;

function makeTimePicker(){
	var tp_length=document.getElementsByTagName('timepicker').length;
		 for(tn=0;tn<tp_length;tn++){
			 document.createElement("timepicker");
			if(!document.getElementsByTagName('timepicker')[tn].id){
				var udata=new Date();
				document.getElementsByTagName('timepicker')[tn].id="tp_"+udata.getTime();
			}
			var t_id=document.getElementsByTagName('timepicker')[tn].id;
			var readonly=(document.getElementById(t_id).getAttribute("readonly")=="readonly")?"readonly=\"readonly\"":"";
			var tp_value=document.getElementById(t_id).getAttribute("value");
			var dis=(document.getElementById(t_id).getAttribute("disabled") || document.getElementById(t_id).getAttribute("disabled")=="true")?"disabled=\"disabled\"":"";
			var displaySeconds=(document.getElementById(t_id).getAttribute("displaySeconds")!="true")?"none":"inline";
			var displayAMPM=(document.getElementById(t_id).getAttribute("displayAMPM")!="true")?"none":"inline";
			parseTimePickerValue(t_id,tp_value);
			var tp_content="<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" style=\"margin: 0; display:inline; border:0px solid green !important; background: none !important;\"><tr><td rowspan=\"2\" style=\"vertical-align:middle;\"><div style=\"background-color:#FFFFFF;\"><div style=\"white-space: nowrap; padding: 0px;text-align: center\" id=\"div_"+t_id+"\" ><input type=\"text\" "+readonly+" style=\"border:0px  !important;width:"+fw+";background:none;\" id=\""+t_id+"_hours\" onfocus=\"this.select();thefield=this\" onchange=\"validateHours(this,def_hours"+t_id+",'"+t_id+"')\" onblur=\"validateHours(this,def_hours"+t_id+",'"+t_id+"')\"  "+dis+" size=\"2\" maxlength=\"2\" value=\""+eval("def_hours"+t_id)+"\" /><span style=\"width: auto;background:none;border:none !important\">:</span><input type=\"text\" "+readonly+" style=\"border:0px  !important;width:"+fw+";background:none;\" id=\""+t_id+"_minutes\" onfocus=\"this.select();thefield=this\" onblur=\"validateMinutes(this,def_minutes"+t_id+",'"+t_id+"')\" onchange=\"validateMinutes(this,def_minutes"+t_id+",'"+t_id+"')\"  "+dis+"  size=\"1\" maxlength=\"2\" value=\""+eval("def_minutes"+t_id)+"\" /><span id=\""+t_id+"_sec_sep\" style=\"width:auto;background:none;border:none !important;display:"+displaySeconds+"\">:</span><input type=\"text\" "+readonly+" style=\"border:0px !important;width:"+fw+";background:none;display:"+displaySeconds+"\" id=\""+t_id+"_seconds\" onfocus=\"this.select();thefield=this\" onblur=\"validateMinutes(this,def_seconds"+t_id+",'"+t_id+"')\" onchange=\"validateMinutes(this,def_seconds"+t_id+",'"+t_id+"')\"  "+dis+" maxlength=\"2\" value=\""+eval("def_seconds"+t_id)+"\" /><input type=\"text\" "+readonly+" style=\"border:0px  !important;width:2em;background:none;display:"+displayAMPM+"\" id=\""+t_id+"_ampm\" onfocus=\"this.select();thefield=this\" "+dis+" size=\"1\" maxlength=\"2\" onblur=\"validateAMPM(this,def_ampm"+t_id+",'"+t_id+"')\" onchange=\"validateAMPM(this,def_ampm"+t_id+",'"+t_id+"')\" value=\""+eval("def_ampm"+t_id)+"\" /></div></div></td><td style=\"width: auto;background:none;border:none !important;\" valign=\"bottom\" ><button id=\"btn_up_"+t_id+"\" "+dis+" type=\"button\" style=\"font-size:6px;padding:0px; text-align: center\" onmousedown=\"setDisplayField('"+t_id+"');if(!clearInterval(go_up)){go_up=setInterval('spinDisplayValue(\\'up\\',\\'"+t_id+"\\')',70)}\" onclick=\"clearInterval(go_up);syncTimePickerValue('"+t_id+"')\" onmousemove=\"clearInterval(go_up);syncTimePickerValue('"+t_id+"')\"><img src=images/t_up.gif></4></td></tr><tr><td style=\"width: auto;background:none;border:none !important;\" valign=\"top\"><button id=\"btn_down_"+t_id+"\" "+dis+" type=\"button\" style=\"font-size:6px;padding:0px;text-align: center\" onmousedown=\"setDisplayField('"+t_id+"');if(!clearInterval(go_down)){go_down=setInterval('spinDisplayValue(\\'down\\',\\'"+t_id+"\\')',70)}\" onclick=\"clearInterval(go_down);syncTimePickerValue('"+t_id+"')\" onmousemove=\"clearInterval(go_down);syncTimePickerValue('"+t_id+"')\"><img src=images/t_down.gif></button></td></tr></table><input type=\"hidden\" name=\""+t_id+"\" id=\""+t_id+"_time\" value=\""+tp_value+"\" />";
			if(document.all && !window.opera){
				if(document.getElementsByTagName('/timepicker')[tn]){document.getElementsByTagName('/timepicker')[tn].outerHTML=""};
				document.getElementById(t_id).outerHTML+="</TIMEPICKER>";
			}
			document.getElementById(t_id).innerHTML=tp_content;
			applyTimePickerCSS(t_id);
			syncTimePickerValue(t_id);
		 }
}

function applyTimePickerCSS(t_id){
	var tp=document.getElementById(t_id);
	var tp_style=tp.style.cssText;
	var tp_class=tp.className;
	var theDiv=document.getElementById("div_"+t_id);
	theDiv.style.cssText+=";"+tp_style;
	theDiv.className = tp_class;
	document.getElementById("btn_up_"+t_id).parentNode.className=document.getElementById("btn_down_"+t_id).parentNode.className=tp_class;

	var f_color=theDiv.style.color;
	var f_fontW=theDiv.style.fontWeight;
	var f_fontsz=theDiv.style.fontSize;

	for(t=0;t<theDiv.childNodes.length;t++){
		if(theDiv.childNodes[t].nodeType!=3){
			theDiv.childNodes[t].className=theDiv.className;
			theDiv.childNodes[t].style.color=f_color;
			theDiv.childNodes[t].style.fontWeight=f_fontW;
			theDiv.childNodes[t].style.fontSize=f_fontsz;
		}
	}
	if(tp){
		tp.style.background=tp.className=tp.style.cssText="";
	}
}

function setDisplayField(t_id){
	if(thefield=="" || (thefield.id!=t_id+"_hours" && thefield.id!=t_id+"_minutes" && thefield.id!=t_id+"_seconds" && thefield.id!=t_id+"_ampm")){
		thefield=document.getElementById(t_id+"_hours");
	}
}

function spinDisplayValue(d,t_id){
	var dis=(document.getElementById(t_id).getAttribute("disabled") || document.getElementById(t_id).getAttribute("disabled")=="true")?true:false;
	var readonly=(document.getElementById(t_id).getAttribute("readonly")!="readonly" && document.getElementById(t_id).getAttribute("readonly")!="true")?false:true;
	if(!readonly && !dis){
		var leadZero=(document.getElementById(t_id).getAttribute("leadZero")=="true")?"0":"";
		var displayAMPM=(document.getElementById(t_id).getAttribute("displayAMPM")=="true");
		var maxHours=(displayAMPM)?12:23;
			with(thefield){
				if(d=="up"){
					if(thefield==document.getElementById(t_id+"_hours")){
						value=(Number(value)>=maxHours)?(leadZero+((displayAMPM)?"1":"0")):((Number(value)>=9)?(Number(value)+1):(leadZero+Number(Number(value)+1)));
					}
					else if(thefield==document.getElementById(t_id+"_minutes")|| thefield==document.getElementById(t_id+"_seconds")){
						value=(value>=59)?(leadZero+"0"):((Number(value)>=9)?(Number(value)+1):(leadZero+Number(Number(value)+1)));
					}
				}
				else{
					if(thefield==document.getElementById(t_id+"_hours")){
						value=(Number(value)<=(displayAMPM)?1:0)?maxHours:((Number(value)>10)?(Number(value)-1):(leadZero+Number(Number(value)-1)));
					}
					else if(thefield==document.getElementById(t_id+"_minutes") || thefield==document.getElementById(t_id+"_seconds")){
						value=(value<=0)?59:((Number(value)>10)?(Number(value)-1):(leadZero+Number(Number(value)-1)));
					}
				}
				if(thefield==document.getElementById(t_id+"_ampm")){
					value=(value=="AM")?"PM":"AM";
				}
				focus();
				select();
			}
	}
}

function syncTimePickerValue(t_id){
	var seconds="";
	var leadZero=(document.getElementById(t_id).getAttribute("leadZero")=="true")?"0":"";
	if(document.getElementById(t_id).getAttribute("displaySeconds")=="true"){
		var cur_sec=document.getElementById(t_id+"_seconds").value;
		var seconds=(cur_sec=="")?(":"+leadZero+"0"):(":"+cur_sec);
	}
	var v=document.getElementById(t_id+"_hours").value+":"+document.getElementById(t_id+"_minutes").value+seconds+document.getElementById(t_id+"_ampm").value;
	document.getElementById(t_id+"_time").value=(valid_time.test(v))?v:"";

	document.getElementById(t_id).setAttribute("value",(valid_time.test(v))?v:"");
}

function validateMinutes(f,r,t_id){
	if(f.value!=""){
		var leadZero=(document.getElementById(t_id).getAttribute("leadZero")=="true")?"0":"";
		if(isNaN(f.value)||f.value>59 ||f.value<0 ){f.value=r}else{f.value=(f.value.length<2 && leadZero=="0")?("0"+f.value):f.value}
	}
	syncTimePickerValue(t_id);
}

function validateAMPM(f,r,t_id){
	if(f.value!=""){
		if(f.value.toUpperCase()!="AM" && f.value.toUpperCase()!="PM"){f.value=r}else{f.value=f.value.toUpperCase()}
	}
	syncTimePickerValue(t_id);
}

function validateHours(f,r,t_id){
	if(f.value!=""){
		var leadZero=(document.getElementById(t_id).getAttribute("leadZero")=="true")?"0":"";
		var displayAMPM=(document.getElementById(t_id).getAttribute("displayAMPM")=="true");
		var maxHours=(displayAMPM)?12:23;
		if(isNaN(f.value)||f.value>maxHours||f.value<0){f.value=r}else{f.value=(f.value.length<2 && leadZero=="0")?("0"+f.value):f.value}
	}
	syncTimePickerValue(t_id);
}

function setTimePickerValue(t_id,val){
	var displaySeconds=(document.getElementById(t_id).getAttribute("displaySeconds")=="true");
	var displayAMPM=(document.getElementById(t_id).getAttribute("displayAMPM")=="true");
	document.getElementById(t_id).setAttribute('value',val);
	parseTimePickerValue(t_id,val);
	document.getElementById(t_id+"_hours").value=eval("def_hours"+t_id);
	document.getElementById(t_id+"_minutes").value=eval("def_minutes"+t_id);
	if(displaySeconds){document.getElementById(t_id+"_seconds").value=eval("def_seconds"+t_id);}	
	if(displayAMPM){document.getElementById(t_id+"_ampm").value=eval("def_ampm"+t_id);}
	syncTimePickerValue(t_id);
}

function setTimePickerReadOnly(t_id,b){
	var div_t_id=document.getElementById("div_"+t_id);
	if(b==false){
	document.getElementById(t_id).removeAttribute("readOnly");
	document.getElementById(t_id).removeAttribute("readonly");	
		for(i=0;i<div_t_id.childNodes.length;i++){
			if(div_t_id.childNodes[i].tagName.toUpperCase()=="INPUT"){
				div_t_id.childNodes[i].removeAttribute("readOnly");
			}
		}
	}
	else{
	document.getElementById(t_id).setAttribute("readOnly","readonly");
		for(i=0;i<div_t_id.childNodes.length;i++){
			if(div_t_id.childNodes[i].tagName.toUpperCase()=="INPUT"){
				div_t_id.childNodes[i].setAttribute("readOnly","readonly");
			}
		}
	}
}

function enableTimePicker(t_id,b){
	var div_t_id=document.getElementById("div_"+t_id);
	if(!b==false){
	document.getElementById(t_id).removeAttribute("disabled");
		for(i=0;i<div_t_id.childNodes.length;i++){
			if(div_t_id.childNodes[i].nodeType!=3){
				div_t_id.childNodes[i].removeAttribute("disabled");
			}
		}
		document.getElementById("btn_down_"+t_id).removeAttribute("disabled");
		document.getElementById("btn_up_"+t_id).removeAttribute("disabled");
	}
	else{
	document.getElementById(t_id).setAttribute("disabled","disabled");
		for(i=0;i<div_t_id.childNodes.length;i++){
			if(div_t_id.childNodes[i].nodeType!=3){
				div_t_id.childNodes[i].setAttribute("disabled","disabled");
			}
		}
		document.getElementById("btn_down_"+t_id).setAttribute("disabled","disabled");
		document.getElementById("btn_up_"+t_id).setAttribute("disabled","disabled");
	}
}
function parseTimePickerValue(t_id,val){
	var d_hours=null;
	var d_minutes=null;
	var d_seconds=null;
	var d_ampm=null;

	var leadZero=(document.getElementById(t_id).getAttribute("leadZero")=="true")?"0":"";
	var displayAMPM=(document.getElementById(t_id).getAttribute("displayAMPM")=="true");
	var displaySeconds=(document.getElementById(t_id).getAttribute("displaySeconds")=="true");
	if(!document.getElementById(t_id).value){document.getElementById(t_id).setAttribute("value","")}
	var gotValue=val;

	var al=document.getElementById(t_id).attributes.length;
	
	if(gotValue==""){
		if(document.getElementById(t_id).getAttribute("enforceDefaultValue")=="true"){
			var nowTime=new Date();
			d_hours=nowTime.getHours();
			d_minutes=nowTime.getMinutes();
			d_seconds=nowTime.getSeconds();
			d_ampm=(d_hours>12)?"PM":"AM";
		}
	}else{
		if(valid_time.test(gotValue)){
			var val_arr=gotValue.split(":");
			d_hours=val_arr[0].substring(0,2).match(tp_numbers);
			d_minutes=val_arr[1].substring(0,2).match(tp_numbers);
			if(val_arr.length>2){
				d_seconds=val_arr[2].substring(0,2).match(tp_numbers);
			}
			else{
				d_seconds=(displaySeconds)?(leadZero+"0"):null;
			}
			if(val_arr[1].length>2){
				d_ampm=val_arr[1].match(tp_ampm);
			}
			else if(val_arr[2] && val_arr[2].length>2){
				d_ampm=val_arr[2].match(tp_ampm);
			}
			if((d_minutes>59) || (d_hours>23) || (d_seconds>59)){
				d_hours=d_seconds=d_minutes=d_ampm=null;
			}
		}
	}
	var dm=(d_minutes!=null)?((d_minutes>=10)?d_minutes:(leadZero+Number(d_minutes))):"";
	eval("def_minutes"+t_id+"=dm;");
	
	if(displaySeconds){
		var ds=(d_seconds!=null)?((d_seconds>=10)?d_seconds:(leadZero+Number(d_seconds))):"";
		eval("def_seconds"+t_id+"=ds;");
	}
	else{
		eval("def_seconds"+t_id+"='';");
	}
	if(!displayAMPM){
		var hours_v=d_hours;
		eval("def_ampm"+t_id+"='';");
	}else{
		var hours_v=(d_hours>12)?(d_hours-12):d_hours;
		d_ampm=(d_hours>12)?"PM":d_ampm;
		dampm=(d_ampm!=null)?d_ampm:"";
		eval("def_ampm"+t_id+"=dampm;");
	}
	var dh=(d_hours!=null)?((hours_v>=10)?hours_v:(leadZero+Number(hours_v))):"";
	eval("def_hours"+t_id+"=dh;");

}

function addEvent(obj, et, fn, uc) {
  if (obj.addEventListener){
    obj.addEventListener(et, fn, uc);
    return true;
  } else if (obj.attachEvent){
    var r = obj.attachEvent("on"+et, fn);
    return r;
  }
}
addEvent(window, "load", makeTimePicker);