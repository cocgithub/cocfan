<?php
/*
	ID: adkiller_7ree
	[www.7ree.com] (C)2007-2013 7ree.com.
	This is NOT a freeware, use is subject to license terms
	Update: 18:43 2013/5/21
	Agreement: http://addon.discuz.com/?@7.developer.doc/agreement_7ree_html
	More Plugins: http://addon.discuz.com/?@7ree
*/
	
if(!defined('IN_DISCUZ')) exit('Access Denied');

$plugin_var_7ree = $_G['cache']['plugin']['adkiller_7ree'];
if(!$plugin_var_7ree['agreement_7ree']) showmessage('adkiller_7ree:php_lang_agree_7ree');

$_GET['uid_7ree'] = intval($_GET['uid_7ree']);

if(!$_GET['uid_7ree']){
showmessage('adkiller_7ree:php_lang_uiderror_7ree');
}else{
	
$killergroup_7ree = $plugin_var_7ree['killergroup_7ree'] ? unserialize($plugin_var_7ree['killergroup_7ree']) : array();	
if(!(in_array($_G[groupid],$killergroup_7ree) && $_G[uid]) ) showmessage('adkiller_7ree:php_lang_denied_7ree');
	
	
$adgroup_7ree = $plugin_var_7ree['adgroup_7ree'] ? unserialize($plugin_var_7ree['adgroup_7ree']) : array();
$aduid_7ree = DB::result_first("SELECT groupid FROM ".DB::table('common_member')." WHERE uid = {$_GET['uid_7ree']} ");
if(!in_array($aduid_7ree,$adgroup_7ree)) showmessage('adkiller_7ree:php_lang_grouperror_7ree');
}

if($_GET['adkillconfirm']=="1"){

if (!submitcheck(submit_7ree))showmessage("Access Denied");
		
adkiller_action_7ree($_GET['uid_7ree']);
showmessage('adkiller_7ree:php_lang_success_7ree',"forum.php?mod=forumdisplay&fid={$_G[gp_fid_7ree]}");

}else{
include(template('adkiller_7ree:adkiller_7ree'));
}

function adkiller_action_7ree($uid_7ree){
	global $_G;
	$bandays_7ree = intval($_G['cache']['plugin']['adkiller_7ree']['banipday_7ree']) * 86400;
	require DISCUZ_ROOT.'./source/function/function_forum.php';
	require DISCUZ_ROOT.'./source/function/function_post.php';
	for ($i_7ree=0; $i_7ree<=9; $i_7ree++) {
	$query = DB::query("SELECT attachment, thumb, remote, aid FROM ".DB::table('forum_attachment_'.$i_7ree)." WHERE uid='$uid_7ree' LIMIT 200");
	while($attach = DB::fetch($query)) {
		dunlink($attach);
	}
    }

 	//get && ban ip
 	if($bandays_7ree){
		 	$banip_7ree = DB::result_first("SELECT lastip FROM ".DB::table('common_member_status')." WHERE uid = '$uid_7ree' LIMIT 1");
		 	$banip_array =  explode('.', $banip_7ree);
		 	DB::query("INSERT INTO ".DB::table('common_banned')." SET ip1='{$banip_array[0]}', ip2='{$banip_array[1]}', ip3='{$banip_array[2]}', ip4='{$banip_array[3]}', admin='{$_G[username]}', dateline='{$_G[timestamp]}',expiration={$_G[timestamp]}+{$bandays_7ree}");
 	}
	
	$adtidlist_7ree=$adfidlist_7ree=array();
	$result = DB::query("SELECT tid,fid FROM ".DB::table('forum_post')." WHERE authorid='$uid_7ree' GROUP BY tid");
	while ($resultlist_7ree = DB::fetch($result)) {
	    	$adtidlist_7ree[]=$resultlist_7ree['tid'];
		if(!in_array($resultlist_7ree['fid'],$adfidlist_7ree)) $adfidlist_7ree[]=$resultlist_7ree['fid'];
	}

	DB::query("UPDATE ".DB::table('common_member')." SET adminid='-1', groupid='5' WHERE uid ='$uid_7ree'", 'UNBUFFERED');	
	DB::query("DELETE FROM ".DB::table('forum_access')." WHERE uid ='$uid_7ree'", 'UNBUFFERED');
	DB::query("DELETE FROM ".DB::table('home_favorite')." WHERE uid ='$uid_7ree'", 'UNBUFFERED');
	DB::query("DELETE FROM ".DB::table('forum_moderator')." WHERE uid ='$uid_7ree'", 'UNBUFFERED');
	DB::query("DELETE FROM ".DB::table('common_member_validate')." WHERE uid ='$uid_7ree'", 'UNBUFFERED');
	DB::query("DELETE FROM ".DB::table('forum_attachment')." WHERE uid='$uid_7ree'");
	    for ($i_7ree=0; $i_7ree<=9; $i_7ree++) {
	    DB::query("DELETE FROM ".DB::table('forum_attachment_'.$i_7ree)." WHERE uid='$uid_7ree'");
	    }
	DB::query("DELETE FROM ".DB::table('forum_thread')." WHERE authorid ='$uid_7ree'");
	DB::query("DELETE FROM ".DB::table('forum_post')." WHERE authorid ='$uid_7ree'");
	DB::query("DELETE FROM ".DB::table('forum_trade')." WHERE sellerid ='$uid_7ree'");
	
 	DB::query("DELETE FROM ".DB::table('home_blog')." WHERE uid ='$uid_7ree'");
	DB::query("DELETE FROM ".DB::table('home_blogfield')." WHERE uid ='$uid_7ree'");
	
	foreach ($adtidlist_7ree as $tid_7ree){
		updatethreadcount($tid_7ree);
	}
	
	foreach ($adfidlist_7ree as $fid_7ree){
		updateforumcount($fid_7ree);
	}

		
}
?>
