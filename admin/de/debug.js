// debug module 
var debug_window = parent.document.createElement("div");
debug_window.style.position = "absolute";
debug_window.style.backgroundColor = "white";
debug_window.style.top		= "450px";
debug_window.style.left		= "30px";
debug_window.style.width	= "1000px";
//debug_window.style.visibility	= "hidden";
debug_window.style.height	= "100px";
debug_window.style.border	= "2px solid black";
debug_window.style.fontFamily	= "Arial";
debug_window.style.fontSize	= "12px";
debug_window.style.scrollable	= "true";
var dbg_cnt = 0
parent.document.body.appendChild(debug_window);

function trace(data){
	if (data=="xxx"){debug_window.innerHTML = "";data="";}

	debug_window.innerHTML += data + "<br>";
	dbg_cnt+=1;
}

function traceHTML(data){
	if (data=="xxx"){debug_window.innerHTML = "";data="";}

	data = data.replace(/</gi,"&lt;");
	data = data.replace(/>/gi,"&gt;");
		
	debug_window.innerHTML += data + "<br>";
	dbg_cnt+=1;
}