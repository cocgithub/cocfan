<?php
/*
 *	nimba_spider (C)2012 AiLab Inc.
 *	nimba_spider Made By Nimba, Team From AiLab.CN
 *	Id: spider.inc.php  AiLab.CN 2013-02-28 09:11$
 */
@$agent=$_SERVER['HTTP_USER_AGENT'];
@$referer=$_SERVER['HTTP_REFERER'];
@$domain=$_SERVER['HTTP_HOST'];
@$url=$_SERVER['REQUEST_URI'];
@$ip=empty($_SERVER['HTTP_X_FORWARDED_FOR'])? $_G['clientip']:$_SERVER['HTTP_X_FORWARDED_FOR'];
@$dateline=time();
$baidu=stristr($agent,"Baiduspider");
$google=stristr($agent,"Googlebot");
$soso=stristr($agent,"Sosospider");
$youdao=stristr($agent,"YoudaoBot");
$bing=stristr($agent,"bingbot");
$sogou=stristr($agent,"Sogou web spider");
$yahoo=stristr($agent,"Yahoo! Slurp");
$Alexa=stristr($agent,"Alexa");
$so=stristr($agent,"360Spider");
if($baidu){
    if($var['baidu']) $agent="baidu";
	else $agent=null;
}
elseif($google){
    if($var['google']) $agent="Google";
	else $agent=null;
}
elseif($soso){
    if($var['soso']) $agent="soso";
	else $agent=null;
}
elseif($youdao){
    if($var['youdao']) $agent="youdao";
	else $agent=null;
}
elseif($bing){
    if($var['bing']) $agent="bing";
	else $agent=null;
}
elseif($sogou){
    if($var['sogou']) $agent="sogou";
	else $agent=null;
}
elseif($yahoo){
    if($var['yahoo']) $agent="yahoo";
	else $agent=null;
}
elseif($Alexa){
    if($var['alexa']) $agent="Alexa";
	else $agent=null;
}
elseif($so){
    if($var['s360']) $agent="so";
	else $agent=null;
}
else{
    $agent=null;
}
if(!substr_count($url,'misc.php')&&$agent){
	$fid=0;
	$tid=0;
	if($mod=='forum') $fid=$_G['fid'];
	if($mod=='thread') $tid=$_G['tid'];
	$url='http://'.$domain.$url;
	//var_dump($url);
	DB::query("insert into ".DB::table('nimba_spider')." (spidername,spiderip,dateline,url,fid,tid,status) values ('$agent','$ip','$dateline','$url','$fid','$tid',1)");
}
?>