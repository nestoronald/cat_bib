/*
 * jQuery Browser Detect 1.1
 * http://dixso.net/
 *
 * Copyright (c) 2009 Julio De La Calle Palanques
 *
 * Date: 2009-09-17 12:34:00 - (Jueves, 17 Sep 2009)
 *
 */

//Script para comprobar rutas de ficheros físicos.
function file_exists (url) {
    var req = this.window.ActiveXObject ? new ActiveXObject("Microsoft.XMLHTTP") : new XMLHttpRequest();
    if (!req) {throw new Error('XMLHttpRequest not supported');}
    req.open('HEAD', url, false);
    req.send(null);
    if (req.status == 200){
        return true;
    }
    return false;
}

//Especificamos a jQuery los navegadores (Útil para diferenciar Safari de Chrome).
var userAgent = navigator.userAgent.toLowerCase();
jQuery.browser = {
	version: (userAgent.match( /.+(?:rv|it|ra|ie|me)[\/: ]([\d.]+)/ ) || [])[1],
	chrome: /chrome/.test( userAgent ),
	safari: /webkit/.test( userAgent ) && !/chrome/.test( userAgent ),
	opera: /opera/.test( userAgent ),
	msie: /msie/.test( userAgent ) && !/opera/.test( userAgent ),
	mozilla: /mozilla/.test( userAgent ) && !/(compatible|webkit)/.test( userAgent )
};

//Ejecutamos las condiciones si el fichero existe o no.
$(document).ready(function(){
	jQuery.each(jQuery.browser, function(i, val) {
		if(file_exists("css/browsers/ie8.css") ){
			if(i=="msie" && jQuery.browser.version.substr(0,3)=="8.0"){
				$('head').append('<link rel="stylesheet" href="css/browsers/ie8.css" type="text/css" />');
			} 
		}
		if (file_exists("css/browsers/ie7.css")){
			if(i=="msie" && jQuery.browser.version.substr(0,3)=="7.0"){
				$('head').append('<link rel="stylesheet" href="css/browsers/ie7.css" type="text/css" />');
			}
		}
		if (file_exists("css/browsers/ie6.css")){
			if(i=="msie" && jQuery.browser.version.substr(0,3)=="6.0"){
				$('head').append('<link rel="stylesheet" href="css/browsers/ie6.css" type="text/css" />');
			}
		}
		if (file_exists("css/browsers/mozilla.css")){
			if($.browser.mozilla){
				$('head').append('<link rel="stylesheet" href="css/browsers/mozilla.css" type="text/css" />');
			}
		}
		if (file_exists("css/browsers/opera.css")){
			if($.browser.opera){
				$('head').append('<link rel="stylesheet" href="css/browsers/opera.css" type="text/css" />');
			}
		}
		if (file_exists("css/browsers/safari.css")){
			if($.browser.safari){
				$('head').append('<link rel="stylesheet" href="css/browsers/safari.css" type="text/css" />');
			}
		}
		if (file_exists("css/browsers/chrome.css")){
			if($.browser.chrome){
				$('head').append('<link rel="stylesheet" href="css/browsers/chrome.css" type="text/css" />');
			}
		}
	});
});