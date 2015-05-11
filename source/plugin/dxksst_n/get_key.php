<?php
/**
 * 		Copyright£ºdxksst
 * 		  WebSite£ºwww.dxksst.com
 *              QQ:2811931192
 */
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
function get_key($str,$encode,$match,$type){
	$str=iconv('gbk',$encode,$str);
	if($type){$str=explode("|",$str);
	$str=$str[1];
	}
	$len=2;if($encode=='utf-8')$len=3;
	$ar=str_split($str,$len);
	foreach($ar as $k=>$v){
	if($type&&($k<count($ar)-1))$v=$v.$ar[$k+1];
	if($v==$match){
	if($type){
	for($i=0;$i<$k;$i++)
	{$return.=$ar[$i];}
	return $return;
	}	
	return $ar[$k-2].$ar[$k-1];} 	
		}
	return "";
	}
?>