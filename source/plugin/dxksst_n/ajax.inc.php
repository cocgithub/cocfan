<?php
/**
 * 		Copyright£ºdxksst
 * 		  WebSite£ºwww.dxksst.com
 *             QQ:2811931192
 */
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
} 
 $ip=$_GET['ip'];
 if($ip=="")exit;
 $dxksst =$_G['cache']['plugin']['dxksst_n'];
 $encode=$_G['charset'];$fcolor=$dxksst['fcolor'];
 require_once './source/plugin/dxksst_n/ip/get_local.php';
 $local_key=$dxksst['local_key'];$local_my=$dxksst['local_my'];
 $forbide_fid=$dxksst['forbide_fid'];
 require_once libfile('get_key','plugin/dxksst_n');
 require_once libfile('getinfo','plugin/dxksst_n');
 require_once libfile('cache','plugin/dxksst_n');
 $local=Mylocal($ip);
 $local_key=explode("s",$local_key);$recomend_post=array();
 $local_key0=$local_key[0];$local_key1=$local_key[1];
 $local0=get_key($local,$encode,$local_key0,0);
 $local1=get_key($local,$encode,$local_key1,0);
 $local_my=explode("|",$local_my);
 $local_my1=$local_my[0];$local_my2=$local_my[1];
 $local_mykey1=get_key($local,$encode,$local_my1,1);
 $local_mykey2=get_key($local,$encode,$local_my2,1);
 if($local_mykey1!="")$local_mykey1=$local_mykey1.$local_my1;
 if($local_mykey2!="")$local_mykey2=$local_mykey2.$local_my2;
 $local0_post=get_post_by_key($local0,10,1,$forbide_fid);
 $local1_post=get_post_by_key($local1,10,1,$forbide_fid);
 $local2_post=get_post_by_key($local_mykey1,10,1,$forbide_fid);
 $local3_post=get_post_by_key($local_mykey2,10,1,$forbide_fid);
  foreach($local3_post as $k=>$v){
	 $recomend_post[]=$v; 
	 }
  if(count($recomend_post)<10)	 
  foreach($local2_post as $k=>$v){
	 $recomend_post[]=$v; 
	 }
 if(count($recomend_post)<10)
  foreach($local1_post as $k=>$v){
	 $recomend_post[]=$v; 
	 }
 if(count($recomend_post)<10)	 
	 foreach($local0_post as $k=>$v){
	 $recomend_post[]=$v; 
	 }	
 if(count($recomend_post)<10)
   { $view_post=dxksst_rcache("view");
	 foreach($view_post as $k=>$v){
	 $recomend_post[]=$v; 
	 }}	 
 foreach($recomend_post as $k=>$thread){
	 if($k<10){			 
echo '<div>[<a href="forum.php?mod=forumdisplay&fid='.$thread['fid'].'" style="color:'.$fcolor.'">'.$thread['fname'].'</a>]
<a href="forum.php?mod=viewthread&tid='.$thread['tid'].'">'.$thread['subject'].'</a></div>';
	 }}
?>