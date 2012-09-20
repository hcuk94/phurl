<?php
require_once "../config.php";
$surl = SITE_URL;
$jsgen = <<<JSGEN
// Phurl 3 Bookmarklet JavaScript
// http://phurlproject.org/

function addCSS(url){
  var headID = document.getElementsByTagName("head")[0];
  var cssNode = document.createElement('link');
  cssNode.type = 'text/css';
  cssNode.rel = 'stylesheet';
  cssNode.href = url;
  cssNode.media = 'screen';
  headID.appendChild(cssNode);
}

function keyPressHandler(e) {
      var kC  = (window.event) ?    // MSIE or Firefox?
                 event.keyCode : e.keyCode;
      var Esc = (window.event) ?   
                27 : e.DOM_VK_ESCAPE // MSIE : Firefox
      if(kC==Esc){
         // alert("Esc pressed");
         toggleItem("l0pframe");
      }
}

function toggleItem(id){
  var item = document.getElementById(id);
  if(item){
    if ( item.style.display == "none"){
      item.style.display = "";
    }
    else{
      item.style.display = "none";
    } 
  }
}


function showItem(id){
  try{
    var item = document.getElementById(id);
    if(item){
        item.style.display = "";
    }
  }
  catch(e){
  
  }
}


(function(){
var iframe_url = '$surl/assets/bkm.php?alias=&url='+encodeURIComponent(location.href);
var existing_iframe = document.getElementById('l0pframe-iframe');




addCSS("$surl/assets/bkm.css");


var div = document.createElement("div");
  div.id = "l0pframe";


 var str = "";
  str += "<table id='l0pframe-table' valign='top' width='250' cellspacing='0' cellpadding='0'><tr><td width='250' height='10000px'>";
  str += "<iframe frameborder='0' scrolling='no' name='l0pframe-iframe' id='l0pframe-iframe' src='" + iframe_url + "' width='250px' height='10000px' style='textalign:right; backgroundColor: white; filter: alpha (opacity=50);'></iframe>";
  str += "<img src='$surl/images/close.png' onClick='toggleItem(\"l0pframe\");' id='close-icon' /></td></tr></table>";
 
 div.innerHTML = str; 
 div.onkeypress = keyPressHandler;
 document.body.insertBefore(div, document.body.firstChild);
 })()
JSGEN;
echo $jsgen;
header('Content-type: application/javascript');
