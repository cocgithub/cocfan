<?php 
/**
 * 随机抽奖插件
 * 2012-9-3 coofee
 */
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

define('RDL_NAME', lang('plugin/rdlottery', 'rdlottery'));
define('RDL_BUTTONTEXT', lang('plugin/rdlottery', 'post_rdlottery'));
if(DISCUZ_VERSION == "X2"){
    define('RDL_ICON', 'source/plugin/rdlottery/images/rdlottery.gif');
}else{
   define('RDL_ICON', 'images/rdlottery.gif');
}
loadcache('plugin');

class threadplugin_rdlottery{
    var $name = RDL_NAME;             
	var $iconfile = RDL_ICON;        
	var $buttontext = RDL_BUTTONTEXT;
	var $rdl;
	
/**
 * 发帖页面显示
 */
function newthread($fid){
		global $_G;
		include template('rdlottery:rdl_newthread');
	    return $return;
}
/**
 * 发帖提交,在帖子插入之前进行相关的验证
 */
function newthread_submit($fid) {
    global $_G,$modnewthreads,$displayorder,$rdl;
    $rdl = $this->check_gpc();
    $rdl['name'] = cutstr($rdl['name'], 50);
	$rdl['name'] = censor(dhtmlspecialchars($rdl['name']));
	if(empty($rdl['rdlotteryaid'])) {
		rdlshowmessage('m_no_pic');
	}
	$modnewthreads = censormod($rdl['name']) ? 1 : 0;
	$displayorder = $modnewthreads ? -2 : 0;    
}
/**
 * 发帖提交,在帖子插入之后进行相关处理
 */
function newthread_submit_end($fid, $tid) {
	global $_G,$pid,$rdl;

	$rdlaid = $extra = 0;
	$sql = '';
    if($rdl['rdlotteryaid']) {
		$attachtable = DB::result_first("SELECT tableid FROM ".DB::table('forum_attachment')." WHERE aid='$rdl[rdlotteryaid]'");

		!$attachtable && $attachment = DB::fetch_first("SELECT * FROM ".DB::table('forum_attachment_unused')." WHERE aid='$rdl[rdlotteryaid]' AND uid='$_G[uid]' AND isimage='1'");
		
		$attachtable = $attachtable == 127 ? 'unused' : $attachtable;
		($attachtable && empty($attachment)) && $attachment = DB::fetch_first("SELECT * FROM ".DB::table('forum_attachment_'.$attachtable)." WHERE aid='$rdl[rdlotteryaid]' AND uid='$_G[uid]' AND isimage='1'");
		if(empty($attachment)) {
			rdlshowmessage('m_no_pic'); //封面必须上传
		}
		if($attachtable == 'unused') {
			convertunusedattach($rdl['rdlotteryaid'], $tid, $pid);
		}
		$tableid = DB::result_first("SELECT posttableid FROM ".DB::table('forum_thread')." WHERE tid='$tid'");
		if(!$tableid) {
			$tablename = 'forum_post';
		} else {
			$tablename = "forum_post_$tableid";
		}
		DB::query("UPDATE ".DB::table('forum_thread')." SET attachment=2 WHERE tid='$tid'");
		DB::query("UPDATE ".DB::table($tablename)." SET attachment=2 WHERE pid='$pid'");

		$rdlaid = 1;
		$threadimage = DB::fetch_first("SELECT tid, pid, attachment, remote FROM ".DB::table(getattachtablebyaid($aid))." WHERE aid='$aid'");
		if(setthreadcover(0, 0, $rdl['rdlotteryaid'])) {
			$threadimage = daddslashes($threadimage);
			DB::delete('forum_threadimage', "tid='$threadimage[tid]'");
			DB::insert('forum_threadimage', array(
				'tid' => $threadimage['tid'],
				'attachment' => $threadimage['attachment'],
				'remote' => $threadimage['remote'],
			));
		}
	} else {
		rdlshowmessage('m_no_pic'); //error
	}
	
	DB::insert('plugin_rdlottery', array(
			'tid' => $tid,
			'uid' => $_G['uid'],
			'username' => $_G['username'],
			'aid' => ($rdlaid ? $rdl['rdlotteryaid'] : 0),
			'status' => 0,
			'extid' => $_G['cache']['plugin']['rdlottery']['rdl_extcredit'],			
			'virtual' => $rdl['virtual'],
			'name' => $rdl['name'],
			'number' => $rdl['virtual'] ? count($rdl['message']) : $rdl['number'],
			'ext_price' => $rdl['ext_price'],
			'real_price' => $rdl['real_price'],	
			'starttimefrom' => $rdl['starttimefrom']+rand(0,30),
			'starttimeto' => $rdl['starttimeto'],			
		    'rand' => intval($rdl['rand']),
		    'isinfo' => $rdl['isinfo'],
		));
    if($rdl['virtual']) {
			foreach($rdl['message'] as $message) {
				$sql .= "(null,'{$tid}','".daddslashes($message)."',''),";
			}
			$sql = trim($sql, ',');
			DB::query("INSERT INTO ".DB::table('plugin_rdlottery_message')." VALUES {$sql}");
		}
}
/**
 * 编辑页面
 */
function editpost($fid, $tid) {
	global $_G;

	if(DB::result_first("SELECT COUNT(*) FROM ".DB::table('plugin_rdlottery')." WHERE tid='$tid'")) {
		$rdlottery = DB::fetch_first("SELECT * FROM ".DB::table('plugin_rdlottery')." WHERE tid='$tid'");
		$rdlottery['starttimefrom'] = dgmdate($rdlottery['starttimefrom'], 'Y-m-d H:i');
		$rdlottery['starttimeto'] = dgmdate($rdlottery['starttimeto'], 'Y-m-d H:i');
		if($rdlottery['aid']) {
			$rdlotteryatt = DB::fetch_first("SELECT remote,attachment,thumb FROM ".DB::table(getattachtablebytid($tid))." WHERE aid='{$rdlottery[aid]}'");
			if($rdlotteryatt['remote']) {
				$rdlotteryatt['attachment'] = $_G['setting']['ftp']['attachurl'].'forum/'.$rdlotteryatt['attachment'];
				$rdlotteryatt['attachment'] = substr($rdlotteryatt['attachment'], 0, 7) != 'http://' ? 'http://'.$rdlotteryatt['attachment'] : $rdlotteryatt['attachment'];
			} else {
				$rdlotteryatt['attachment'] = $_G['setting']['attachurl'].'forum/'.$rdlotteryatt['attachment'];
			}
		}
	} else {
		return ' ';
	}

	include template('rdlottery:rdl_newthread');
	return $return;
}
/**
 * 编辑提交
 */
function editpost_submit($fid, $tid) {
	global $_G,$modnewthreads,$displayorder,$rdl;
	$this->getrdl_gpc();
	$rdl = $this->rdl;
}
/**
 * 编辑修改,允许修改大部分参数
 */
function editpost_submit_end($fid, $tid) {
	global $_G,$rdl;

	if(!DB::result_first("SELECT COUNT(*) FROM ".DB::table('plugin_rdlottery')." WHERE tid='$tid'")) {
		return ' ';
	} else {	
		if($rdl['rdlotteryaid'] && DB::result_first("SELECT COUNT(*) FROM ".DB::table('forum_attachment_unused')." WHERE aid='{$rdl[rdlotteryaid]}' AND uid='{$_G[uid]}'")) {
			$aid = DB::result_first("SELECT aid FROM ".DB::table('plugin_rdlottery')." WHERE tid='$tid'");
			if($aid) {
				$att = DB::fetch_first("SELECT aid,tid,tableid FROM ".DB::table('forum_attachment')." WHERE aid='$aid'");
				if($att['tableid']) {
					$attach = DB::fetch_first("SELECT tid, pid, attachment, thumb, remote, aid FROM ".DB::table('forum_attachment_'.$att['tableid'])." WHERE aid='$aid'");
					dunlink($attach);
					DB::query("DELETE FROM ".DB::table('forum_attachment_'.$att['tableid'])." WHERE aid='$aid'");
				}
			}
			DB::query("UPDATE ".DB::table('plugin_rdlottery')." SET aid='{$rdl[rdlotteryaid]}' WHERE tid='$tid'");
			DB::query("UPDATE ".DB::table('forum_thread')." SET attachment=2 WHERE tid='$tid'");
			convertunusedattach($rdl['rdlotteryaid'], $tid, $_G['gp_pid']);
		}
		if($rdl['starttimeto']) {
			$rdltion = DB::fetch_first("SELECT * FROM ".DB::table('plugin_rdlottery')." WHERE tid='$tid'");
			if($rdl['starttimeto'] < $rdltion['starttimeto'] || $rdl['starttimeto'] < $_G['timestamp'] || ($rdl['starttimeto'] - $rdltion['starttimefrom'] > 7776000)) {
				showmessage(lang('plugin/rdlottery', 'm_delay_time_error'), '', array('mintime' => dgmdate(max($rdltion['starttimeto'], $_G['timestamp'])),'maxtime' => dgmdate($rdltion['starttimefrom'] + 7776000)));
			} else {
				DB::query("UPDATE ".DB::table('plugin_rdlottery')." SET starttimeto='{$rdl[starttimeto]}' WHERE tid='$tid'");
			}
		}
		// 概率
		if($rdl['rand']<0 || $rdl['rand']>1000){
		    rdlshowmessage('m_rand_error');
		}else{
			DB::query("UPDATE ".DB::table('plugin_rdlottery')." SET rand='{$rdl[rand]}' WHERE tid='$tid'");
		    // 如果延期的话重新设置为未结束
			if($rdl['starttimeto'] > $rdltion['starttimeto']){
				DB::query("UPDATE ".DB::table('plugin_rdlottery')." SET status=0 WHERE tid='$tid'");
			}
		}
		// 2013-2-17 新增  
		DB::query("UPDATE ".DB::table('plugin_rdlottery')." SET name='{$rdl[name]}',real_price='{$rdl[real_price]}',isinfo='{$rdl[isinfo]}',ext_price='{$rdl[ext_price]}',number='{$rdl[number]}' WHERE tid='$tid'");
	}
}


/**
 * 看帖页面
 */
function viewthread($tid) {
	global $_G,$skipaids,$thread;

	if(DB::result_first("SELECT COUNT(*) FROM ".DB::table('plugin_rdlottery')." WHERE tid='$tid'")) {
		$rdlottery = DB::fetch_first("SELECT * FROM ".DB::table('plugin_rdlottery')." WHERE tid='$tid'");
		$has_win = DB::result_first("SELECT COUNT(*) FROM ".DB::table('plugin_rdlotteryapply')." WHERE tid='$tid'");
		$rdlottery['last_number'] = $rdlottery['number'] - $has_win;
		$notstart = $rdlottery['starttimefrom'] > $_G['timestamp'];
		$rdlottery['js_timeto'] = $rdlottery['status'] ? '01/01/1970 00:00' : dgmdate($rdlottery['starttimeto'], 'm/d/Y H:i:s');
		$rdlottery['js_timefrom'] = $rdlottery['status'] ? '01/01/1970 00:01' : dgmdate($rdlottery['starttimefrom'], 'm/d/Y H:i:s');
		$rdlottery['js_timenow'] = TIMESTAMP;
		$rdlottery['starttimefrom'] = dgmdate($rdlottery['starttimefrom'], 'Y-m-d H:i:s');
		$rdlottery['starttimeto_0'] = $rdlottery['starttimeto'];
		$rdlottery['starttimeto'] = dgmdate($rdlottery['starttimeto'], 'Y-m-d H:i:s');		
		$rdlottery['extid'] = empty($rdlottery['extid']) ? $_G['cache']['plugin']['ralottery']['rdl_extcredit'] : $rdlottery['extid'];
		if($rdlottery['aid']) {
			$rdlotteryatt['attachment'] = getforumimg($rdlottery['aid'], 0, 250, 300);
			$rdlotteryatt['encodeaid'] = aidencode($rdlottery['aid']);
			$skipaids[] = $rdlottery['aid'];
		}
		DB::query("UPDATE ".DB::table('plugin_rdlottery')." SET hot=hot+1 WHERE tid='$tid'");
		$showmobile = ($_G['uid'] == $rdlottery['uid'] || in_array($_G['adminid'], array(1,2))) && $rdlottery['isinfo'];
		if($rdlottery['status']==0 && strtotime($rdlottery['starttimeto'])<TIMESTAMP){
		    DB::query("UPDATE ".DB::table('plugin_rdlottery')." SET status=1 WHERE tid='$tid'");
		}
	} else {
		return ' ';
	}

	include template('rdlottery:rdl_viewthread');
	return $return;
}

/**
* 进行变量初始化
* @return array 提交数据中有关的变量
*/
function getrdl_gpc() {
	
	$rdl['name'] = cutstr(trim(getgpc('rdl_name')), 40);
	$rdl['rdlotteryaid'] = intval(getgpc('rdlotteryaid'));		
	$rdl['rdlotteryaid_url'] = getgpc('rdlotteryaid_url');
	$rdl['starttimefrom'] = strtotime(getgpc('rdl_starttimefrom'));
	$rdl['starttimeto'] = strtotime(getgpc('rdl_starttimeto'));
	$rdl['ext_price'] = intval(getgpc('rdl_ext_price'));    //交易出价
	$rdl['real_price'] = intval(getgpc('rdl_real_price'));	//市场价格
	$rdl['number'] = intval(getgpc('rdl_number'));
	$rdl['rdl_type1_'] = intval(getgpc('rdl_type1_'));
	$rdl['virtual'] = intval(getgpc('rdl_virtual')) ? 1 : 0;
	$rdl['isinfo']  = intval(getgpc('rdl_isinfo')) ? intval(getgpc('rdl_isinfo')) : 0;
	$rdl['message'] = str_replace("\r", '', trim(getgpc('rdl_message')));
	$rdl['message'] = array_filter(explode("\n", $rdl['message']));    //卡密
	$rdl['rand'] = intval(getgpc('rdl_rand'));

	$this->rdl = $rdl;	
}

/**
* 检测提交的变量
*/
function check_gpc() {
	$this->getrdl_gpc();
	$rdl = $this->rdl;
	
	if(empty($rdl['starttimefrom']) || empty($rdl['starttimeto'])) {
		rdlshowmessage('m_time_invalide');
	}
	if(empty($rdl['name'])) {
		rdlshowmessage('m_name_invalide');
	}
	if(empty($rdl['real_price']) || $rdl['real_price'] <= 0) {
		rdlshowmessage('m_real_price_invalide');
	}

	if($rdl['virtual']) {
		if(empty($rdl['message']) || !count($rdl['message'])) {
			rdlshowmessage('m_message_ivalide');
		}
	} else {
		if(empty($rdl['number']) || $rdl['number'] <= 0) {
			rdlshowmessage('m_number_invalide');
		}
	}
	if($rdl['ext_price'] < 0) {
		rdlshowmessage('m_ext_price_invalide');		
	}	
	if($rdl['starttimeto'] - $rdl['starttimefrom'] <= 120 || $rdl['starttimeto'] - $rdl['starttimefrom'] > 7776000) {
		rdlshowmessage('m_time_too_short');
	}
	if(empty($rdl['rand']) || $rdl['rand'] < 0){
	    rdlshowmessage('m_rand_error');
	}
	return $rdl;
 }	
	
	
	
	
}

/**
 * 自定义showmessage函数
 */
function rdlshowmessage($str, $url = '') {
	showmessage(lang('plugin/rdlottery', $str), $url);
}