<?php
/**
 * 		Copyright£ºdxksst
 * 		  WebSite£ºwww.dxksst.com
 *             QQ:2811931192
 */
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
function get_history($n,$order,$type,$describe,$forbide){
$orderstr="asc";$ourtype=array("year","month","week","day");
if(!in_array($type,$ourtype))return array();
if($order==1)$orderstr="desc";
	if($forbide=="")$forbide=2;
    $forumsql=' and fid='.$forbide;
$query=DB::query("SELECT min(dateline) FROM ".DB::table('forum_post')." where position=1 and invisible=0");
$have_date=DB::fetch($query);$min_date=$have_date['min(dateline)']; 
$osql="SELECT tid,author,subject,dateline FROM ".DB::table('forum_post')." where position=1 and invisible=0".$forumsql;
$oneday=86400;$post=array();$maxyear=0;
$sql=$osql;
for($i=1;$i<20;$i++)
{
	if($i==1)$tb=strtotime(date('Y-m-d',strtotime('-1 '.$type)));
	else $tb=strtotime(date('Y-m-d',strtotime('-'.$i.' '.$type."s")));
	if($tb<$min_date)break;
	$maxyear=$i;$te=$tb+$oneday;
	if($i==1)$sql=$sql." and ((dateline>".$tb." and dateline<".$te.")";
	else $sql=$sql." or (dateline>".$tb." and dateline<".$te.")";
	}
$sql=$sql.")";
if($maxyear==1){
$sql=$osql." and dateline>".$tb." and dateline<".$te;
	}	
$sql=$sql." order by dateline ".$orderstr." limit ".$n;	
if($maxyear){	
$querys=DB::query($sql);	
while($mood = DB::fetch($querys)) {
	$describe1=preg_replace("/\[subject\]/",$mood['subject'],$describe);
	$describe2=preg_replace("/\[author\]/",$mood['author'],$describe1);
	$describe3=preg_replace("/\[time\]/",date("Y-m-d",$mood['dateline']),$describe2);
	$mood['subject']=$describe3;	
	$post[]=$mood;}	
}
return $post;	
}

?>