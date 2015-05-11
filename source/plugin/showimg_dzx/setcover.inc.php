<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

//加系统的设置封面函数的文件
require_once libfile('function/post');


$tid=intval($_GET['tid']);
$url=$_GET['url'];


$post = DB::fetch_first("SELECT pid,fid FROM ".DB::table(forum_post)." WHERE tid='$tid' and first=1");

$pid=intval($post['pid']);
$fid=intval($post['fid']);
loadcache('forums');
$forumset = unserialize($_G['cache']['forums'][$fid]['plugin']['showimg_dzx']['forum_setting']);
if($forumset['forum_type']==3){
	$imgtype = 2;
}else{
	$imgtype = 1;
}


if(mysetthreadcover($pid,$tid,0,0,$url,$imgtype,$fid)){
	showmessage('set_cover_succeed', '', array(), array('alert' => 'right', 'closetime' => 1));
}else{
	showmessage('set_cover_faild', '', array(), array('closetime' => 3));
}
?>