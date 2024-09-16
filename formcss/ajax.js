function toggle(obj) {
	var el = document.getElementById(obj);
	if ( el.style.display != 'none' ) {
		el.style.display = 'none';
	}
	else {
		el.style.display = '';
	}
}

var bustcachevar=1 //bust potential caching of external pages after initial request? (1=yes, 0=no)
 var loadedobjects=""
 var rootdomain="http://"+window.location.hostname
 var bustcacheparameter=""
 function ajaxpage(url, containerid,secondcontainerid){ 

 var page_request = false
 if (window.XMLHttpRequest) // if Mozilla, Safari etc
 page_request = new XMLHttpRequest()
 else if (window.ActiveXObject){ // if IE
 try {
 page_request = new ActiveXObject("Msxml2.XMLHTTP")
 } 
 catch (e){
 try{
 page_request = new ActiveXObject("Microsoft.XMLHTTP")
 }
 catch (e){}
 }
 }
 else
 return false
 page_request.onreadystatechange=function(){
	 
 if (page_request.readyState == 4 && (page_request.status==200 || window.location.href.indexOf("http")==-1))

 document.getElementById(containerid).style.display = 'inline';
 document.getElementById(containerid).innerHTML=page_request.responseText;
 

 if(page_request.responseText != '')
 {
  document.getElementById(secondcontainerid).style.display = 'none';
    if(page_request.readyState == 4 &&  secondcontainerid == 'contectwaiting' ){
		alert("Thanks For Contact...!");
		// window.location='index.htm';
	
	  }
 }
 }
 if (bustcachevar) //if bust caching of external page
 bustcacheparameter=(url.indexOf("?")!=-1)? "&"+new Date().getTime() : "?"+new Date().getTime()
 document.getElementById(containerid).style.display = 'none';
 if(secondcontainerid != '')
 {
  document.getElementById(secondcontainerid).style.display = 'inline';
  
 }
// alert(url);
 page_request.open('GET', url+bustcacheparameter, true)
 page_request.send(null)
 
 
 }