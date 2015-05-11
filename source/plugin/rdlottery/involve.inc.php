<?php

/**
 *	随机抽奖插件
 *	2012-9-8 coofee
 *
 * */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$operation = trim(getgpc('operation'));
if(!$_G['uid'] && $operation != 'view') {
	showmessage('to_login', '', array(), array('showmsg' => true, 'login' => 1));
}

$tid = intval(getgpc('tid'));
include libfile('function/forum');
$thread = get_thread_by_tid($tid, '*', "AND special='127'");

if($thread) {
	$rdlottery = DB::fetch_first("SELECT * FROM ".DB::table('plugin_rdlottery')." WHERE tid='$tid'");	
}
if(!$thread || !$rdlottery) {
	showmessage(lang('plugin/rdlottery', 'm_none_exist'), '', '', array('showdialog' => true));
}
if(!in_array($operation, array('finish', 'view', 'message', 'admin_message')) && $rdlottery['starttimeto'] < $_G['timestamp']) {
	showmessage(lang('plugin/rdlottery', 'm_none_exist'), '', '', array('showdialog' => true));
}

//参与
if($operation == 'join') {	
	if($rdlottery['uid'] == $_G['uid']) {
		showmessage(lang('plugin/rdlottery', 'm_owner'), '', '', array('showdialog' => true, 'closetime' => 3));
	}
	if($rdlottery['starttimefrom'] > $_G['timestamp']) {
		showmessage(lang('plugin/rdlottery', 'm_starttime_error'), '', '', array('showdialog' => true, 'closetime' => 3));
	}
	if($rdlottery['status']) {
		showmessage(lang('plugin/rdlottery', 'm_end'), '', '', array('showdialog' => true, 'closetime' => 3));
	}
	//如果剩余金钱不够的话无法参加抽奖		
	$user_extcredits = getuserprofile('extcredits'.($rdlottery['extid'] ? $rdlottery['extid'] : $_G['cache']['plugin']['rdlottery']['rdl_extcredit']));
	if($user_extcredits <= $rdlottery['ext_price']){
		showmessage(lang('plugin/rdlottery', 'm_less_ext'), '', '', array('showdialog' => true, 'closetime' => 3));
	}
	// 中奖数量与总数量对比
	$has_win = DB::result_first("SELECT COUNT(*) FROM ".DB::table('plugin_rdlotteryapply')." WHERE tid='$tid'");
	if($has_win >= $rdlottery['number']){
		if(! $rdlottery['status']){
			DB::query("UPDATE ".DB::table('plugin_rdlottery')." SET status=1 WHERE tid='$tid'");
		}
		showmessage(lang('plugin/rdlottery', 'm_no_goods'), '', '', array('showdialog' => true, 'closetime' => 3));
	}
	
	if(!submitcheck('confirmsubmit')) {
		$limit = $rdlottery['number'] - 1;
		if($_G['cache']['plugin']['rdlottery']['rdl_reply']) { 
			$reply_message = '';
			$reply_messages = array();			
			$reply_messages = explode("\n", $_G['cache']['plugin']['rdlottery']['rdl_reply_message']);			
			if($reply_messages) {
				$reply_message = $reply_messages[array_rand($reply_messages)];
			}
			$reply_message = str_replace(array('{name}', '{price}', '{priceunit}'), array($rdlottery['name'], ($rdlottery['ext_price']), ($_G['setting']['extcredits'][$rdlottery['extid']]['title'])), $reply_message);
		}
		$mobile = $_G['cookie']['rdlottery_mobile'];
		$info = $_G['cookie']['rdlottery_info'];		
		include template('rdlottery:involve');
		exit;
	} else { 
        //正式提交信息
		$userext = getuserprofile('extcredits'.($rdlottery['extid'] ? $rdlottery['extid'] : $_G['cache']['plugin']['rdlottery']['rdl_extcredit']));
		$status_top = $type2_insert = 0;		
		$mobile = $memmobile = 0;
		$mobile = trim($_G['gp_mobile']);
		$mobile = $mobile ? intval($mobile) : '';		
		$info = dhtmlspecialchars(daddslashes(trim($_G['gp_info'])));
		if($rdlottery['isinfo'] && !$mobile && !$info){
			showmessage(lang('plugin/rdlottery', 'info_error'));
		}
		dsetcookie('rdlottery_mobile',$mobile,"86400");
		dsetcookie('rdlottery_info',$info,"86400");
	    if($rdlottery['number']<=0){
	    	showmessage(lang('plugin/rdlottery', 'm_no_goods'), '', '', array('showdialog' => true, 'closetime' => 3));
	    }else{
	    	$iswin = (mt_rand(1,1000) < $rdlottery['rand']) ? 1 : 0 ;
	    	$delta_price = $rdlottery['ext_price'];	    	
	    }
        //更新用户金币
		updatemembercount($_G['uid'], array('extcredits'.($rdlottery['extid'] ? $rdlottery['extid'] : $_G['cache']['plugin']['rdlottery']['rdl_extcredit']) => -$delta_price), false, 'rdl', $tid);
		//中奖
		if($iswin) {
			if($rdlottery['virtual'] == 1){
			    DB::query("UPDATE ".DB::table('plugin_rdlottery_message')." SET uid='{$_G[uid]}' WHERE tid='{$thread[tid]}' AND uid='' LIMIT 1");
			}
			notification_add(
				$_G['uid'],
				'system',
				lang('plugin/rdlottery', 'n_rdlottery_get'),
				array(
					'rdlotteryname' => $rdlottery['name'],
					'rdlotterytid' => $rdlottery['tid'],
				),
				1
			);
		
			$data = array(
				'applyid' => null,
				'tid' => $tid,
				'username' => $_G['username'],
				'uid' => $_G['uid'],
				'dateline' => $_G['timestamp'],
				'ext_price' => $rdlottery['ext_price'],					
				'mobile' => $mobile,
			    'info' => $info,
				);
			DB::insert('plugin_rdlotteryapply', $data);			
			//先到先到模式如果人数已满则自动结算
			$now = DB::result_first("SELECT COUNT(*) FROM ".DB::table('plugin_rdlotteryapply')." WHERE tid='$tid'");
			if($now == $rdlottery['number']) {
				DB::query("UPDATE ".DB::table('plugin_rdlottery')." SET status=1 WHERE tid='$tid'");					
				}
			} elseif($now > $rdlottery['number']) {
				showmessage('undefine_error', 'forum.php?mod=viewthread&tid='.$tid);
			}			
		}
		DB::query("UPDATE ".DB::table('plugin_rdlottery')." SET hot=hot+1 WHERE tid='$tid'");
		
		if($_G['cache']['plugin']['rdlottery']['rdl_reply']) { //自动回复
			$rdl_reply_message = dhtmlspecialchars(daddslashes(trim($_G['gp_rdl_reply_message'])));
			if($rdl_reply_message) {
				$thread = get_thread_by_tid($tid);
				include_once libfile('function/forum');
				$postid = insertpost(array(
					'fid' => $thread['fid'],
					'tid' => $tid,
					'first' => '0',
					'author' => $_G['username'],
					'authorid' => $_G['uid'],
					'subject' => '',
					'dateline' => $_G['timestamp'],
					'message' => $rdl_reply_message,
					'useip' => $_G['clientip'],
					'invisible' => 0,
					'anonymous' => 0,
					'usesig' => 1,
					'htmlon' => 0,
					'bbcodeoff' => 0,
					'smileyoff' => -1,
					'parseurloff' => 0,
					'attachment' => '0',
				));
				if($postid) {
					DB::query("UPDATE ".DB::table('forum_thread')." SET replies=replies+1,lastpost='{$_G[timestamp]}',lastposter='{$_G[username]}' WHERE tid='$tid'");
					DB::query("UPDATE ".DB::table('common_member_count')." SET posts=posts+1 WHERE uid='{$_G[uid]}'");
					DB::query("UPDATE ".DB::table('forum_forum')." SET lastpost='$tid\t$thread[subject]\t{$_G[timestamp]}\t{$_G[username]}',posts=posts+1,todayposts=todayposts+1 WHERE fid='{$thread[fid]}'");
				}
			}
		}        
        if($iswin){
        	showmessage(lang('plugin/rdlottery', 'm_choujiang_succeed'), 'forum.php?mod=viewthread&tid='.$tid);
        }else{
        	showmessage(lang('plugin/rdlottery', 'm_choujiang_faile'), 'forum.php?mod=viewthread&tid='.$tid);
        }  
//查看中奖纪录
}elseif($operation == 'view') {
	
	$a_pp = 10;
	$a_cp = intval($_G['gp_page']);
	$a_cp = $a_cp ? $a_cp : 1;
	$a_s = ($a_cp-1)*$a_pp;	
	
	$list = array();
	$rdl = DB::fetch_first("SELECT * FROM ".DB::table('plugin_rdlottery')." WHERE tid='$tid'");
	$showmobile = ($_G['uid'] == $rdl['uid'] || in_array($_G['adminid'], array(1,2))) && $rdl['isinfo'];
	$list_count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('plugin_rdlotteryapply')." WHERE tid='$tid'");
	if($list_count) {
		$query = DB::query("SELECT * FROM ".DB::table('plugin_rdlotteryapply')." WHERE tid='$tid' ORDER BY dateline DESC LIMIT $a_s,$a_pp");
		while($list_1 = DB::fetch($query)) {
			$list_1['dateline'] = dgmdate($list_1['dateline'], 'Y-m-d H:i:s');
			$list[] = $list_1;
		}
	}
	$_G['gp_ajaxtarget'] = '';
	$multi = multi($list_count, $a_pp, $a_cp, 'plugin.php?id=rdlottery:involve&operation=view&tid='.$tid.($_G['gp_top'] ? '&top=1' : ''));
	$multi = preg_replace("/<a\shref=\"([\s\S]*?)\"(.*?)>/ies", "aaa('\\1','\\2')", $multi);
	$multi = str_replace('\"', '"', $multi);
	include template('rdlottery:viewthread_view');
	
//查看卡密
} elseif($operation == 'message') {
	if($rdlottery['virtual']) {
		$query = DB::query("SELECT * FROM ".DB::table('plugin_rdlottery_message')." WHERE tid='$tid' AND uid='{$_G[uid]}'");
		if($query) {
			$apply = array();
			while ($_apply =  DB::fetch($query)){
				$apply[] = $_apply;
			}
			include template('rdlottery:viewthread_message');
			exit;
		} else {
			showmessage(lang('pluin/rdlottery', 'm_no_message'), '', '', array('alert' => 'error'));
		}
	} else {
		showmessage(lang('plugin/rdlottery', 'm_not_virtual'));
	}
} elseif($operation == 'admin_message') {
	if($_G['adminid'] != 1 && $_G['uid'] != $rdlottery['uid']) {
		showmessage(lang('plugin/rdlottery', 'm_no_perm'));
	}
	if($rdlottery['virtual']) {
		$messages = $messageuids = $messageusers = array();
		$query = DB::query("SELECT * FROM ".DB::table('plugin_rdlottery_message')." WHERE tid='{$tid}' LIMIT 100");
		while($message = DB::fetch($query)) {
			if($message['uid']) {
				$messageuids[] = $message['uid'];
			}
			$messages[] = $message;
		}
		if($messageuids) {
			$query = DB::query("SELECT uid,username FROM ".DB::table('common_member')." WHERE uid IN (".dimplode($messageuids).")");
			while($username = DB::fetch($query)) {
				$messageusers[$username['uid']] = $username['username'];
			}
		}
		include template('rdlottery:viewthread_message');
		exit;
	} else {
		showmessage(lang('plugin/rdlottery', 'm_not_virtual'));	
	}
}

//修改出价记录的翻页
function aaa($aa,$bb) {
	return '<a href="'.$aa.'" onclick="ajaxget(\''.$aa.'\', \'list_ajax\');doane(event);return false;"'.$bb.'>';
}
?>
