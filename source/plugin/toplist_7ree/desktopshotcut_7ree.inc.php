<?php 
/*
	(C)2007-2013 [www.7ree.com]
	This is NOT a freeware, use is subject to license terms
	Agreement: http://addon.discuz.com/?@7.developer.doc/agreement_7ree_html
	More Plugins: http://addon.discuz.com/?@7ree
*/


if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
 
 	
$Shortcut = "[InternetShortcut] 
URL=".$_G['setting']['siteurl']."
IDList= 
[{000214A0-0000-0000-C000-000000000046}] 
Prop3=19,2 
"; 

Header("Content-type: application/octet-stream"); 


	if(strpos($_SERVER['HTTP_USER_AGENT'],"MSIE")){
		header("Content-Disposition: attachment; filename=".urlencode($_G['setting']['bbname']).".url;"); 
	}else{
		header("Content-Disposition: attachment; filename=".$_G['setting']['bbname'].".url;"); 
	}


echo $Shortcut;

?>
