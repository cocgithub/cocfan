<?php
/**
 * 		Copyright£ºdxksst
 * 		  WebSite£ºwww.dxksst.com
 *             QQ:2811931192
 */
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
function get_post_by_key($key,$n,$order,$forbide){
	$key=daddslashes(trim($key));
	if($key=="")return array();
	$n=(int)$n;$orderstr="asc";
	if($order==1)$orderstr="desc";
	if($forbide=="")$forbide=2;
		$forumsql=' and fid='.$forbide;
	$sql="SELECT fid,tid,author,subject,dateline FROM ".DB::table('forum_post').
	" where position=1 and invisible=0 and subject LIKE '%".$key."%'".$forumsql." order by dateline ".$orderstr." limit ".$n;
	$querys=DB::query($sql);$post=array();
	while($mood = DB::fetch($querys)) {
    if($mood['fid']){
	$fsql=	"SELECT name FROM ".DB::table('forum_forum')." where fid=".$mood['fid'];
	$fquery=DB::query($fsql);$fmood = DB::fetch($fquery);$mood['fname']=$fmood['name'];}	
    $mood['subject']=preg_replace(str_preg($key),"<font color='#FF00FF'>".$key."</font>",$mood['subject']);	
	$mood['dateline']=date("Y-m-d",$mood['dateline']);
    $post[]=$mood;
    }
	return $post;
	}
function get_post_by_type($type,$n,$order,$forbide){
	switch($type){
		case   'new':$type='dateline';break;
		case   'hot':$type='replies';break;
		case  'view':$type='views';break;
		case 'newreply':$type='lastpost';break;
		default:return array();
		}
	$n=(int)$n;$orderstr="asc";
	if($order==1)$orderstr="desc";
	if($forbide=="")$forbide=2;
    $forumsql=' and fid='.$forbide;
	$sql="SELECT tid,fid,author,subject,dateline,replies,views,lastpost FROM ".DB::table('forum_thread').
	" where closed=0".$forumsql." order by ".$type." ".$orderstr." limit ".$n;
	$querys=DB::query($sql);$post=array();
	while($mood = DB::fetch($querys)) {
	if($mood['fid']){
	$fsql=	"SELECT name FROM ".DB::table('forum_forum')." where fid=".$mood['fid'];
	$fquery=DB::query($fsql);$fmood = DB::fetch($fquery);$mood['fname']=$fmood['name'];}	
	$mood['dateline']=date("Y-m-d",$mood['dateline']);
	$mood['lastpost']=date("Y-m-d",$mood['lastpost']);
    $post[]=$mood;
    }
	return $post;
	}
function get_user($type,$n,$order){
	$n=(int)$n;$orderstr="asc";
	if($order==1)$orderstr="desc";
		switch($type){
		case    'new':$type='regdate';break;
		case 'credit':$type='credits';break;
		     default:return array();
		}
	$sql="SELECT uid,username,regdate,credits FROM ".DB::table('common_member').
	" where status=0 order by ".$type." ".$orderstr." limit ".$n;
	$querys=DB::query($sql);$user=array();
	while($mood = DB::fetch($querys)) {	
	$mood['regdate']=date("Y-m-d",$mood['regdate']);	
    $user[]=$mood;
    }
	return $user;
		
		}
function get_pic($n,$atturl){
	$n=(int)$n;
	$sql="SELECT tid,attachment FROM ".DB::table('forum_threadimage').
	" order by tid DESC limit ".$n;
	$querys=DB::query($sql);$pic=array();
	while($mood = DB::fetch($querys)) {
	$mood['attachment']=$atturl."forum/".$mood['attachment'];
	if(!(fopen($mood['attachment'],"r")))continue;
	$pic[]=$mood;
    }
	foreach($pic as $k=>$v){
	$sql="SELECT subject FROM ".DB::table('forum_thread')." where tid=".$v['tid'];
	$query=DB::query($sql);
	$mood = DB::fetch($query);
	$pic[$k]['subject']=$mood['subject'];
		}
	return $pic;
		
		}	
	function str_preg($str){
	$ar=str_split($str);
	$tn="$^*()+={}[]|/:<>.?'\"";
	$tn=str_split($tn);
	$re="/";
	foreach($ar as $k=>$v){
	 if(in_array($v,$tn)){$v="\\".$v;}
	 $re=$re.$v;}
	 $re=$re."/";
	 return $re;
	}		
?>