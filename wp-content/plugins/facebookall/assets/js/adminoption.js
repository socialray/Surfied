// JavaScript Document
jQuery(document).ready(function($) {    
    $( "#tabs" ).tabs();  
}); 

function getXMLHttp() {
  var xmlHttp
  try {
    //Firefox, Opera 8.0+, Safari
    xmlHttp = new XMLHttpRequest();
  }
  catch(e) {

    //Internet Explorer
    try {
      xmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
    }
    catch(e) {
      try {
		xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
      }
      catch(e) {
        alert("Your browser does not support AJAX!")
        return false;
      }
    }
  }
  return xmlHttp;
}
function MakeApiRequest() {
  document.getElementById("showmsg").innerHTML = '<div id ="apiloading">Contacting Facebook API - please wait ...</div>'; 	
  var sitebase_url = document.getElementById('sitebase_url').value;
  var apikey = document.getElementById('apikey').value;
  if (apikey == '') {
    document.getElementById('showmsg').innerHTML = '<div id="apierror">please enter your facebook api key</div>';
    return false;
  }
  var apisecret = document.getElementById('apisecret').value;
  if (apisecret == '') {
	document.getElementById('showmsg').innerHTML = '<div id="apierror">please enter your facebook api secret</div>';
   return false;
  }
  if (document.getElementById('curl').checked) {
	var api_request = 'curl';
  }
  else {
	var api_request = 'fopen';   
  }
  var xmlHttp = getXMLHttp();
  xmlHttp.onreadystatechange = function()
  {
   if(xmlHttp.readyState == 4){
     if (xmlHttp.status==200 ){
       document.getElementById("showmsg").innerHTML=xmlHttp.responseText
     }
     else{
       document.getElementById("showmsg").innerHTML='<div id="apierror">An error has occured while making the request</div>';
     }
   }
  }
  xmlHttp.open("GET", sitebase_url+"?apikey=" + apikey +"&apisecret="+apisecret+"&api_request="+api_request, true);
  xmlHttp.send(null);
}