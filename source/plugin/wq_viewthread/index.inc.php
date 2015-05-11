<?php
/**
 * 维清工作室 [ 专业开发各种Discuz!插件 ]
 *
 * Copyright (c) 2011-2012 http://www.weiqing.org All rights reserved.
 *
 * Author: wuchunuan <wuchunuan@163.com
 *
 * $Id: index.inc.php 2013-1-20 上午02:44:08Z wuchunuan $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

//setting
$setting = $_G['cache']['plugin']['wq_viewthread'];
$plugin_name = trim($setting['plugin_name']);

$forums = unserialize($setting['forums']);
$perpage = intval($setting['perpage']);
$title_num = intval($setting['title_num']);
$contents_num = intval($setting['contents_num']);

$navtitle = trim($setting['seo_title']);
$metakeywords = trim($setting['seo_keyword']);
$metadescription = trim($setting['seo_discript']);
$is_attament = intval($setting['is_attament']);

$page = intval($_G['page']);
$page = $page <= 0 ? 1 : $page;
$start = ($page - 1) * $perpage;

$plugin_nav = lang('plugin/wq_viewthread', 'plugin_nav_index');

$week = array(
	lang('plugin/wq_viewthread', 'week0'),
	lang('plugin/wq_viewthread', 'week1'),
	lang('plugin/wq_viewthread', 'week2'),
	lang('plugin/wq_viewthread', 'week3'),
	lang('plugin/wq_viewthread', 'week4'),
	lang('plugin/wq_viewthread', 'week5'),
	lang('plugin/wq_viewthread', 'week6'),
);

//thread
$conditions = " t.fid IN (".dimplode($forums).") AND t.displayorder = '0' AND p.first = '1'";
if($is_attament == '1'){
	$conditions .= " AND t.attachment = '2'";
}
$sql = "SELECT t.*,p.message FROM ".DB::table('forum_thread')." t , ".DB::table('forum_post')." p WHERE ".$conditions." AND t.tid = p.tid ORDER BY t.dateline DESC LIMIT {$start},{$perpage}";
$sql_count = "SELECT count(*) FROM ".DB::table('forum_thread')." t , ".DB::table('forum_post')." p WHERE ".$conditions." AND t.tid = p.tid";

$count = DB::result_first($sql_count);
$url = "plugin.php?id=wq_viewthread:index";

$multipage = multi($count, $perpage, $page, $url, 0, 10);
	
$list_sql = DB::query($sql);
while($row = DB::fetch($list_sql)){
	$row['start_ymd'] = date("Y-m-d",$row['dateline']);
	$row['start_d'] = date("d",$row['dateline']);
	$row['start_w'] = $week[date("w",$row['dateline'])];
	$row['subject_s'] = cutstr($row['subject'],$title_num);
	$row['message'] = archivermessage($row[message]);
	$row['message'] = cutstr($row['message'],$contents_num);
	$row['fname'] = DB::result_first("SELECT name FROM ".DB::table('forum_forum')." WHERE fid = '".$row['fid']."'");

	$img = DB::fetch_first("SELECT attachment,remote FROM ".DB::table("forum_threadimage")." WHERE tid='".$row[tid]."'");
	
	if($img['attachment']){
		if($img['remote']==1){
			$imgurl = $_G['setting']['ftp']['attachurl']."forum/".$img['attachment'];
		}else{
			$imgurl = $_G['setting']['attachurl']."forum/".$img['attachment'];
		}
	}else{
		$imgurl = "./static/image/common/nophoto.gif";
	}
	$row['imgurl'] = $imgurl;
	
	$list[] = $row;	
}
$count_list = count($list) - 1;

include template('wq_viewthread:index');

//common
function archivermessage($message){
	$message=nl2br($message);
	$message=strip_tags($message);
	$message=preg_replace('/\[attach\](.+?)\[\/attach\]/is','',$message);
	$message=preg_replace('/\[img\](.+?)\[\/img\]/is','',$message);
	$message=preg_replace('/\[audio\](.+?)\[\/audio\]/is','',$message);
	$message=preg_replace('/\[media\](.+?)\[\/media\]/is','',$message);
	$message=preg_replace('/\[flash\](.+?)\[\/flash\]/is','',$message);
	$message=preg_replace("/\[hide=?\d*\](.*?)\[\/hide\]/is",'',$message);
	$message=preg_replace("/\[\/?\w+=?.*?\]/",'',$message);
	return $message;
}
?>