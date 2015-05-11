<?php
/*
	[虚拟马甲发帖回复]Guisepost Plugin For Discuz! X1.0 - X2.5 ALL
	Copyring (C) 2010 KL3w.com; You can modify this plugin for your website
	This is not a freeware, use is subject to license terms
	Author: PGCAO；Version: 2.3.1；Time: 2012-11-20 10:12:32
*/
 
if(!defined('IN_DISCUZ')){exit('Access Denied');}

class plugin_kl3w_guisepost{
	protected $vars;							// 存放插件配置变量
	protected $uses;							// 存放马甲数组变量
	protected $sysversion;						// 核心版本号
	protected $identifier = 'kl3w_guisepost';   // 本插件标识

	function __construct(){
		$this->vars = $this->plugin_get_cache();
		$this->uses = $this->getuseid_uid($GLOBALS['_G']['uid']);
		$this->sysversion = str_replace("x",'',strtolower($GLOBALS['_G']['setting']['version']));
	}

	function plugin_kl3w_guisepost(){
		if(version_compare('5.0.0', PHP_VERSION, '>'))$this->__construct();
	}

	function plugin_get_cache(){
		$cache_plugin_var = $GLOBALS['_G']['cache']['plugin'][$this->identifier];
		if(empty($cache_plugin_var)){$cache_plugin_var = array('useid'=>'','open'=>0);}
		return $cache_plugin_var;
	}
	
	function getuseid_uid($uid){		
		if(!$uid)return array(-1, '', 0);$use_uid = -1;$useid_uid='';
		$rn = strtoupper(substr(PHP_OS, 0, 3)) == 'WIN' ? "\r\n" : "\n";
		$useidarr = explode($rn, $this->vars['useid']);
		if(is_array($useidarr)){
			foreach($useidarr as $key => $val){
				$useval = explode("=", $val);
				if($uid == $useval[0] && isset($useval[1]) && !empty($useval[1])){
					$use_uid = intval(trim($useval[0]));
					$useid_uid = $useval[1];
					break;
				}
			}
		}
		return array($use_uid, $useid_uid, 0);
	}
	
	function getguisepostuserid($guisepost_type='post'){
		$uid = intval($GLOBALS['_G']['uid']); //取出用户UID
		if(!$uid || !$this->vars['open'] || $uid != $this->uses[0])return;
		$myguiseuid = ''; $myguiseuidarr = explode(",", $this->uses[1]);
		$kl3w_guisepost_type = $guisepost_type;
		foreach($myguiseuidarr as $val){
				$val = trim($val);
				if(!empty($val) && is_numeric($val)){
					$myguiseuid[] = $val;
				}
		}
		if($myguiseuid){
			$lastpost_arr = array();
			$query = DB::query("SELECT uid,lastpost FROM ".DB::table('common_member_status')." WHERE uid in('".implode("','", $myguiseuid)."')");
			while($row = DB::fetch($query)) {
				$row['lastpost'] = dgmdate($row['lastpost'], 'u', '9999', 'Y-m-d');
				$lastpost_arr[$row['uid']] = $row['lastpost'];
			}
			
			$gender_arr = array();
			$query = DB::query("SELECT uid,gender FROM ".DB::table('common_member_profile')." WHERE uid in('".implode("','", $myguiseuid)."')");
			while($row = DB::fetch($query)) {
				$gender_arr[$row['uid']] = $row['gender']=='1' ? 'F30' : ($row['gender']=='2' ? '060' : '000');
			}
			
			$useravatar = $this->vars['useravatar'] ? 1 : 0;
			
			// 显示马甲名单下拉列表(免费版直接读取数据库)
			$input = array();
			$query = DB::query("SELECT uid,username FROM ".DB::table('common_member')." WHERE uid in('".implode("','", $myguiseuid)."')");
			$douid_arr = array();
			while($row = DB::fetch($query)) {
				$avatar = $useravatar ? avatar($row['uid'],'small') : '';
				$input[] = array('uid'=>$row['uid'],'username'=>$row['username'],'avatar'=>$avatar);
				$douid_arr[] = $row['uid'];
			}
			$nosearch_arr = array_diff($myguiseuid, $douid_arr);
			if($this->sysversion > '2.0' && $nosearch_arr){
				if(DB::fetch_first("SHOW TABLES LIKE '".DB::table('common_member_archive')."'")){
					$query = DB::query("SELECT uid,username FROM ".DB::table('common_member_archive')." WHERE uid in('".implode("','", $nosearch_arr)."')");
					while($row = DB::fetch($query)) {
						$gender_arr[$row['uid']] = '999';$avatar = $useravatar ? avatar($row['uid'],'small') : '';
						$input[] = array('uid'=>$row['uid'],'username'=>$row['username'],'avatar'=>$avatar);
					}
				}
			}
			$guiseuidcount = count($input)+1;
			include template('kl3w_guisepost:input');
			return $return;
		}else{
			return  '';
		}
	}
	
	function _isip($ip){   
		return !strcmp(@long2ip(sprintf('%u',@ip2long($ip))),$ip) ? true : false;
	}   
	
	function _guisepostchk(){
		global $_G; $guisepost='';$olduid = 0;
		// 获取POST马甲ID变量,兼容X1.0、X1.5、X2.0
		$guisepostuserid = isset($_POST['kl3wguisepostuserid']) ? intval($_POST['kl3wguisepostuserid']) : intval($_G['gp_kl3wguisepostuserid']);
		if($_G['uid'] == $this->uses[0] && $guisepostuserid){
			if($this->sysversion > '2.0'){
				// 检查用户是否被移入存档表，若属真将用户移回主表(DZX2.5专用)
				$member = getuserbyuid($guisepostuserid, 1);
				if(isset($member['_inarchive']) && $member['_inarchive']) {
					C::t('common_member_archive')->move_to_master($member['uid']);
				}
				unset($member);
			}
			// 从数据库用户表中获取马甲账号资料
			$guisepost = DB::fetch_first("SELECT uid,username,groupid FROM ".DB::table('common_member')." where uid='$guisepostuserid'");
			if($guisepost){
				space_merge($guisepost, 'status');// 读取马甲其它资料放进$guisepost变量

				$lastactivity = $guisepost['lastactivity'];
				
				$olduid = $_G['uid'];//记忆原用户ID
				
				//切换用户
				$_G['uid']    = $guisepost['uid'];			// 当前登录用户ID暂切换成马甲用户ID
				$_G['username']   = $guisepost['username']; // 当前登录用户名暂切换成马甲用户名

				// 解决通知提示某某答复了主题帖作者的帖子还是登录用户
				$_G['member']['username'] = $_G['username'];
				
				//关闭两次发表时间间隔限制，方便非高级管理人员使用马甲
				$_G['setting']['floodctrl'] = 0;
				
				//让普通主号也可以用马甲发布图片帖
				$_G['forum']['ismoderator'] = 1;
				
				// 解决"提示你的请求来路不正确或表单验证串不符，无法提交"问题
				$_G['gp_formhash'] = formhash(); //防止官方修改了formhash的验证方法，多写几个只为兼容(很晕)
				$_GET['formhash'] = $_POST['formhash'] = $_G['formhash'] = $_G['gp_formhash'];
				
				//处理马甲使用虚拟IP开始
				$ip1arr = explode("\r\n", $this->vars['IP1']);
				$ip1id = intval($ip1arr[mt_rand(0,count($ip1arr)-1)]);
				$ip1id = $ip1id ? $ip1id : "219";					// IP域段一
				
				$ip2arr = explode("\r\n", $this->vars['IP2']);
				$ip2id = intval($ip2arr[mt_rand(0,count($ip2arr)-1)]);
				$ip2id = $ip2id ? $ip2id : "136";					// IP域段二
				
				$ip3id = rand(0, 53);								// IP域段三
				$ip4id = round(rand(600000,   2550000) / 10000);	// IP域段四
				$guise_onlineip = $ip1id.".".$ip2id.".".$ip3id.".".$ip4id; // 合并为完整的IP域
				
				$guise_istimeout = false; // 初始化虚拟用户是否超时
				$guise_timeout = 86400;   // 虚拟在线超时设定，默认为86400
				
				if($guisepost['lastip']){
					if(intval($_G['timestamp'] - $lastactivity) > $guise_timeout) {
						$guise_istimeout = true;
						$ipal = explode('.', $guisepost['lastip']);
						$_G['clientip'] = $ipal[0].".".$ipal[1].".".$ipal[2].".".$ip4id;
					}else{
						$_G['clientip'] = $guisepost['lastip'];
					}
				}else{
					$_G['clientip'] = $guise_onlineip;
				}
				//处理马甲使用虚拟IP结束
				
				$discuz_action = APPTYPEID;//记录动作
				$discuz_tid = intval($_G['tid']);
				$discuz_fid = intval($_G['fid']);
				
				//更新session[解决common_session表占满而导致出错问题]
				if(empty($_G['setting']['sessionclose'])){
					$guise_ss = DB::fetch_first("SELECT sid FROM ".DB::table('common_session')." where uid='$_G[uid]'");
					$now_sid = $guise_ss['sid'] ? $guise_ss['sid'] : random(6); $ips = explode('.', $_G['clientip']);
					$setting = DB::fetch_first("SELECT svalue FROM ".DB::table('common_setting')." where skey='maxonlines'");
					$sessioncount = DB::result_first('SELECT COUNT(sid) FROM '.DB::table('common_session'));
					if($setting){$maxonlines = $setting['svalue'];}else{$maxonlines = 5000;}
					if($maxonlines<$sessioncount){DB::query('DELETE FROM '.DB::table('common_session'));}						
					DB::query("REPLACE INTO ".DB::table('common_session')."(sid, ip1, ip2, ip3, ip4, groupid, lastactivity, action, fid, tid, uid, username)VALUES ('$now_sid', '$ips[0]','$ips[1]','$ips[2]','$ips[3]', '$guisepost[groupid]', '$_G[timestamp]','$discuz_action','$discuz_fid','$discuz_tid','$_G[uid]','$_G[username]')", 'UNBUFFERED'); 
				}
				
				//更新虚拟用户活跃状态
				if($guise_istimeout) {//虚拟账号超时处理						
					$oltimeadd = ",lastvisit=lastactivity, lastip='$_G[clientip]'";//超时后更换IP和上次访问时间
					if(!$guisepost['lastip']){//不存在上次访问IP时顺便更新注册IP为当前虚拟IP							
						$oltimeadd .= ",regip='$_G[clientip]'"; 
					}else{//账号是在后台注册时，恢复注册IP为当前虚拟IP							
						$oltimeadd .= $this->_isip($guisepost['regip']) ? '' : ",regip='$_G[clientip]'";
					}
				} else {//虚拟账号不超时处理						
					$oltimeadd = $guisepost['lastip'] ? '' : ",lastip='$_G[clientip]'";
					$oltimeadd .= $this->_isip($guisepost['regip']) ? '' : ",regip='$_G[clientip]'";
				}
				
				DB::query("UPDATE ".DB::table('common_member_status')." SET lastactivity='$_G[timestamp]',lastpost='$_G[timestamp]'".$oltimeadd." WHERE uid='$_G[uid]'", 'UNBUFFERED');
				$this->uses[2] = $olduid; //存起原用户ID
			}
		}
	}
}

//论坛使用
class plugin_kl3w_guisepost_forum extends plugin_kl3w_guisepost{
	//提交成功后处理后续事件
	function post_feedlog_message($var) {
		if(!$this->vars['open'])return;//未开启插件退出不执行以下
		global $_G; $olduid = $this->uses[2];
		if($olduid != $this->uses[0])return;
		if(substr($var['param'][0], -8) == '_succeed' && $olduid){
			if(isset($_POST['attachnew']) || isset($_G['gp_attachnew'])){
				$tid = $var['param'][2]['tid']; $pid = $var['param'][2]['pid'];
				if($tid && $pid){//有附件时处理附件转移到马甲名下
					DB::query("UPDATE ".DB::table('forum_attachment')." SET uid='$_G[uid]' WHERE uid='$olduid' and tid='$tid' and pid='$pid'", 'UNBUFFERED');
					if($this->sysversion < '2.0'){//X1.0-X1.5.1适用
						DB::query("UPDATE ".DB::table('forum_attachmentfield')." SET uid='$_G[uid]' WHERE uid='$olduid' and tid='$tid' and pid='$pid'", 'UNBUFFERED');
					}else{//X2.0以上适用						
						$tableid = intval(substr($tid, -1));
						DB::query("UPDATE ".DB::table('forum_attachment_'.$tableid)." SET uid='$_G[uid]' WHERE uid='$olduid' and tid='$tid' and pid='$pid'", 'UNBUFFERED');
					}
				}
			}
		}
		return;
	}
	
	//处理马甲转换工作
	function post_top() {
		if(!$this->vars['open'])return '';
		global $seccodecheck, $secqaacheck;
		if(submitcheck('topicsubmit',0,$seccodecheck,$secqaacheck) || submitcheck('replysubmit',0,$seccodecheck,$secqaacheck) || submitcheck('commentsubmit',0,$seccodecheck,$secqaacheck)){
			$this->_guisepostchk();
		}else{
			return $this->getguisepostuserid('toppost');
		}
	}
	
	//列表底部快速发帖嵌入点
	function forumdisplay_fastpost_content(){
		return $this->getguisepostuserid();
	}
	
	//发帖回复浮动窗口嵌入点(DZX2以上版本才适用)
	function post_infloat_top(){
		return $this->getguisepostuserid('floatpost');
	}
	
	//禁用快速发帖模式时使用此嵌入点
	function viewthread_top_output(){
		if($GLOBALS['_G']['setting']['fastpost'])return '';
		return $this->getguisepostuserid('threadtop');
	}
	
	//帖文底部快捷回复嵌入点
	function viewthread_fastpost_content(){
		return $this->getguisepostuserid();
	}
	
	//马甲互交扩展嵌入点(商业版)
	function viewthread_postfooter_output(){
		if(!$GLOBALS['_G']['uid'] || !$this->vars['open'] || $GLOBALS['_G']['uid'] != $this->uses[0])return array();
		if($this->vars['docomment'] || $this->vars['dorate']){
			global $allowpostreply, $thread; $tid = $GLOBALS['_G']['tid'];$a = $b = '';$out = array();
			foreach($GLOBALS['postlist'] as $k => $post) {
				$pid = $post['pid'];
				if($this->vars['dorate']){
					if($GLOBALS['_G']['group']['raterange'] && $post['authorid']){
						$a = '[<a style="padding:0px;line-height:26px;" onclick="showWindow(\'rate\', this.href, \'get\', 0)" href="plugin.php?id='.$this->identifier.':guisemisc&action=rate&tid='.$tid.'&pid='.$pid.'">&#x8BC4;&#x5206;</a>]';
					}
				}
				if($post['allowcomment'] && $this->vars['docomment']){
					if($allowpostreply && (!$thread['closed'] || $GLOBALS['_G']['forum']['ismoderator'])){
						$b = '[<a style="padding:0px;line-height:26px;" onclick="showWindow(\'comment\', this.href, \'get\', 0)" href="plugin.php?id='.$this->identifier.':guisemisc&action=comment&tid='.$tid.'&pid='.$pid.'">&#x70B9;&#x8BC4;</a>]';
					}
				}
				if(!$a && !$b)$b = '<span title="&#x8BF7;&#x5230;&#x540E;&#x53F0;&#x5F00;&#x542F;&#x76F8;&#x5173;&#x8BBE;&#x7F6E;">&#x4E3B;&#x53F7;&#x65E0;&#x6743;</span>';
				$out[] = '<span class="cmmnt" style="padding:2px 0px 2px 24px;">&#x9A6C;&#x7532;'.$b.$a.'</span>';
			}
			return $out;
		}else{
			return array();
		}
	}
}
?>