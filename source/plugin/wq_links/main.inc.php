<?php
 /**
 * 维清工作室 [ 专业开发各种Discuz!插件 ]
 *
 * Copyright (c) 2011-2012 http://www.weiqing.org All rights reserved.
 *
 * Author: wuchunuan <wuchunuan@163.com>
 *
 * $Id: main.inc.php 2012-4-8 上午09:04:17Z wuchunuan $
 */

 if(!defined('IN_DISCUZ')){
 	exit('Access Denied');
 }

$option = $_G['cache']['plugin']['wq_links'];
$switch = $option['offset'];
$tips_contents = $option['tips_contents'];
$admins = explode(',',$option['admins']);
$links_type = $option['links_type'];
$site_name = $option['site_name'];
$site_url = $option['site_url'];
$site_logo = $option['site_logo'];
$allow_nologin = $option['allow_nologin'];
$nologin_notice = $option['nologin_notice'];


$navtitle = $option['title'];
$metakeywords = $option['keyword'];
$metadescription = $option['content'];

if ($_G['uid']=='' && $allow_nologin != 1){
	showmessage(lang('plugin/wq_links', 'gotologin'), '', array(), array('login' => true));
}

if($switch != 1){
	showmessage(lang('plugin/wq_links', 'offset1'));
}else{
	if($_G['gp_mod'] == 'apply' || empty($_G['gp_mod'])){
		$plugin_nav = lang('plugin/wq_links', 'tab_nav_apply');
		if(submitcheck('applysubmit')){

			if(empty($_G['gp_sitename'])){
				showmessage(lang('plugin/wq_links', 'needsitename'), dreferer());
			}elseif(empty($_G['gp_siteurl'])){
				showmessage(lang('plugin/wq_links', 'needsiteurl'), dreferer());
			}else{
				$_G['gp_sitename'] = dhtmlspecialchars($_G['gp_sitename']);
				$_G['gp_siteurl'] = dhtmlspecialchars($_G['gp_siteurl']);
				$_G['gp_description'] = dhtmlspecialchars($_G['gp_description']);
				$_G['gp_logo'] = dhtmlspecialchars($_G['gp_logo']);

				$url = str_replace("http://","",$_G['gp_siteurl']);
				$num = DB::result_first("SELECT * FROM ".DB::table('wq_links')." WHERE `siteurl` = '".$_G['gp_siteurl']."' AND `uid` = '".$_G['uid']."' AND (`status` = '0' OR `status` = '1')");
				if(!empty($num)){
					showmessage(lang('plugin/wq_links', 'siteexist1').$url.lang('plugin/wq_links', 'siteexist2'), 'plugin.php?id=wq_links:main',array($url));
				}
				DB::insert('wq_links',array('sitename' => $_G['gp_sitename'], 'siteurl' => $_G['gp_siteurl'], 'description' => $_G['gp_description'], 'logo' => $_G['gp_logo'], 'uid' => $_G['uid'], 'dateline' => time(), 'status' => '0'));
				$message = lang('plugin/wq_links', 'pm_content1').$_G['gp_siteurl'].lang('plugin/wq_links', 'pm_content2');
				foreach($admins as $uid){
					sendpm($uid,'',$message);
				}
				if(empty($_G['uid'])){
					showmessage(lang('plugin/wq_links', 'tips_content_nologin'), 'plugin.php?id=wq_links:main');
				}else{
					showmessage(lang('plugin/wq_links', 'tips_content'), 'plugin.php?id=wq_links:main&mod=log');
				}
			}
		}

	}elseif($_G['gp_mod'] == 'log'){

		if ($_G['uid']==''){
			showmessage(lang('plugin/wq_links', 'gotologin'), '', array(), array('login' => true));
		}
		$plugin_nav = lang('plugin/wq_links', 'tab_nav_log');
		$page = empty($_G['page']) ? 1 : $_G['page'];
		$perpage = 10;
		$lognum = DB::result_first("SELECT COUNT(*) FROM ".DB::table('wq_links') ." WHERE `uid` = '".$_G['uid']."'");
		$start_limit = ($page - 1) * $perpage;
		$multipage = multi($lognum, $perpage, $page, 'plugin.php?id=wq_links:main&mod=log', 0, 10);
		$sql = "SELECT * FROM ".DB::table('wq_links')." WHERE `uid` = '".$_G['uid']."' ORDER BY dateline DESC LIMIT $start_limit, $perpage";
		$query = DB::query($sql);
		$loglist = $loglists = array();

		while($loglist = DB::fetch($query)){
			$loglist['dateline'] = dgmdate($loglist['dateline']);
			if(empty($loglist['logo'])){
				$loglist['logo'] = lang('plugin/wq_links', 'nologo');
			}else{
				$loglist['logo'] = "<img src='".$loglist['logo']."' width='88' height='31' />";
			}

			if($loglist['status'] == 1){
				$loglist['status'] = "<font color=green>".lang('plugin/wq_links', 'passverify')."</font>";
			}elseif($loglist['status'] == 0){
				$loglist['status'] = "<font color='#ff6600'>".lang('plugin/wq_links', 'unverify')."</font>";
			}
			if($loglist['status'] == -1){
				$loglist['status'] = "<font color=red>".lang('plugin/wq_links', 'nopassverify')."</font>";
			}
			$loglists[] = $loglist;
		}
	}else{
		showmessage('undefined_action');
	}
	include template('wq_links:main');
}
?>
