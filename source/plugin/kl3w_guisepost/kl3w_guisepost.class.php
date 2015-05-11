<?php
/*
	[������׷����ظ�]Guisepost Plugin For Discuz! X1.0 - X2.5 ALL
	Copyring (C) 2010 KL3w.com; You can modify this plugin for your website
	This is not a freeware, use is subject to license terms
	Author: PGCAO��Version: 2.3.1��Time: 2012-11-20 10:12:32
*/
 
if(!defined('IN_DISCUZ')){exit('Access Denied');}

class plugin_kl3w_guisepost{
	protected $vars;							// ��Ų�����ñ���
	protected $uses;							// �������������
	protected $sysversion;						// ���İ汾��
	protected $identifier = 'kl3w_guisepost';   // �������ʶ

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
		$uid = intval($GLOBALS['_G']['uid']); //ȡ���û�UID
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
			
			// ��ʾ������������б�(��Ѱ�ֱ�Ӷ�ȡ���ݿ�)
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
		// ��ȡPOST���ID����,����X1.0��X1.5��X2.0
		$guisepostuserid = isset($_POST['kl3wguisepostuserid']) ? intval($_POST['kl3wguisepostuserid']) : intval($_G['gp_kl3wguisepostuserid']);
		if($_G['uid'] == $this->uses[0] && $guisepostuserid){
			if($this->sysversion > '2.0'){
				// ����û��Ƿ�����浵�������潫�û��ƻ�����(DZX2.5ר��)
				$member = getuserbyuid($guisepostuserid, 1);
				if(isset($member['_inarchive']) && $member['_inarchive']) {
					C::t('common_member_archive')->move_to_master($member['uid']);
				}
				unset($member);
			}
			// �����ݿ��û����л�ȡ����˺�����
			$guisepost = DB::fetch_first("SELECT uid,username,groupid FROM ".DB::table('common_member')." where uid='$guisepostuserid'");
			if($guisepost){
				space_merge($guisepost, 'status');// ��ȡ����������ϷŽ�$guisepost����

				$lastactivity = $guisepost['lastactivity'];
				
				$olduid = $_G['uid'];//����ԭ�û�ID
				
				//�л��û�
				$_G['uid']    = $guisepost['uid'];			// ��ǰ��¼�û�ID���л�������û�ID
				$_G['username']   = $guisepost['username']; // ��ǰ��¼�û������л�������û���

				// ���֪ͨ��ʾĳĳ�������������ߵ����ӻ��ǵ�¼�û�
				$_G['member']['username'] = $_G['username'];
				
				//�ر����η���ʱ�������ƣ�����Ǹ߼�������Աʹ�����
				$_G['setting']['floodctrl'] = 0;
				
				//����ͨ����Ҳ��������׷���ͼƬ��
				$_G['forum']['ismoderator'] = 1;
				
				// ���"��ʾ���������·����ȷ�����֤���������޷��ύ"����
				$_G['gp_formhash'] = formhash(); //��ֹ�ٷ��޸���formhash����֤��������д����ֻΪ����(����)
				$_GET['formhash'] = $_POST['formhash'] = $_G['formhash'] = $_G['gp_formhash'];
				
				//�������ʹ������IP��ʼ
				$ip1arr = explode("\r\n", $this->vars['IP1']);
				$ip1id = intval($ip1arr[mt_rand(0,count($ip1arr)-1)]);
				$ip1id = $ip1id ? $ip1id : "219";					// IP���һ
				
				$ip2arr = explode("\r\n", $this->vars['IP2']);
				$ip2id = intval($ip2arr[mt_rand(0,count($ip2arr)-1)]);
				$ip2id = $ip2id ? $ip2id : "136";					// IP��ζ�
				
				$ip3id = rand(0, 53);								// IP�����
				$ip4id = round(rand(600000,   2550000) / 10000);	// IP�����
				$guise_onlineip = $ip1id.".".$ip2id.".".$ip3id.".".$ip4id; // �ϲ�Ϊ������IP��
				
				$guise_istimeout = false; // ��ʼ�������û��Ƿ�ʱ
				$guise_timeout = 86400;   // �������߳�ʱ�趨��Ĭ��Ϊ86400
				
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
				//�������ʹ������IP����
				
				$discuz_action = APPTYPEID;//��¼����
				$discuz_tid = intval($_G['tid']);
				$discuz_fid = intval($_G['fid']);
				
				//����session[���common_session��ռ�������³�������]
				if(empty($_G['setting']['sessionclose'])){
					$guise_ss = DB::fetch_first("SELECT sid FROM ".DB::table('common_session')." where uid='$_G[uid]'");
					$now_sid = $guise_ss['sid'] ? $guise_ss['sid'] : random(6); $ips = explode('.', $_G['clientip']);
					$setting = DB::fetch_first("SELECT svalue FROM ".DB::table('common_setting')." where skey='maxonlines'");
					$sessioncount = DB::result_first('SELECT COUNT(sid) FROM '.DB::table('common_session'));
					if($setting){$maxonlines = $setting['svalue'];}else{$maxonlines = 5000;}
					if($maxonlines<$sessioncount){DB::query('DELETE FROM '.DB::table('common_session'));}						
					DB::query("REPLACE INTO ".DB::table('common_session')."(sid, ip1, ip2, ip3, ip4, groupid, lastactivity, action, fid, tid, uid, username)VALUES ('$now_sid', '$ips[0]','$ips[1]','$ips[2]','$ips[3]', '$guisepost[groupid]', '$_G[timestamp]','$discuz_action','$discuz_fid','$discuz_tid','$_G[uid]','$_G[username]')", 'UNBUFFERED'); 
				}
				
				//���������û���Ծ״̬
				if($guise_istimeout) {//�����˺ų�ʱ����						
					$oltimeadd = ",lastvisit=lastactivity, lastip='$_G[clientip]'";//��ʱ�����IP���ϴη���ʱ��
					if(!$guisepost['lastip']){//�������ϴη���IPʱ˳�����ע��IPΪ��ǰ����IP							
						$oltimeadd .= ",regip='$_G[clientip]'"; 
					}else{//�˺����ں�̨ע��ʱ���ָ�ע��IPΪ��ǰ����IP							
						$oltimeadd .= $this->_isip($guisepost['regip']) ? '' : ",regip='$_G[clientip]'";
					}
				} else {//�����˺Ų���ʱ����						
					$oltimeadd = $guisepost['lastip'] ? '' : ",lastip='$_G[clientip]'";
					$oltimeadd .= $this->_isip($guisepost['regip']) ? '' : ",regip='$_G[clientip]'";
				}
				
				DB::query("UPDATE ".DB::table('common_member_status')." SET lastactivity='$_G[timestamp]',lastpost='$_G[timestamp]'".$oltimeadd." WHERE uid='$_G[uid]'", 'UNBUFFERED');
				$this->uses[2] = $olduid; //����ԭ�û�ID
			}
		}
	}
}

//��̳ʹ��
class plugin_kl3w_guisepost_forum extends plugin_kl3w_guisepost{
	//�ύ�ɹ���������¼�
	function post_feedlog_message($var) {
		if(!$this->vars['open'])return;//δ��������˳���ִ������
		global $_G; $olduid = $this->uses[2];
		if($olduid != $this->uses[0])return;
		if(substr($var['param'][0], -8) == '_succeed' && $olduid){
			if(isset($_POST['attachnew']) || isset($_G['gp_attachnew'])){
				$tid = $var['param'][2]['tid']; $pid = $var['param'][2]['pid'];
				if($tid && $pid){//�и���ʱ������ת�Ƶ��������
					DB::query("UPDATE ".DB::table('forum_attachment')." SET uid='$_G[uid]' WHERE uid='$olduid' and tid='$tid' and pid='$pid'", 'UNBUFFERED');
					if($this->sysversion < '2.0'){//X1.0-X1.5.1����
						DB::query("UPDATE ".DB::table('forum_attachmentfield')." SET uid='$_G[uid]' WHERE uid='$olduid' and tid='$tid' and pid='$pid'", 'UNBUFFERED');
					}else{//X2.0��������						
						$tableid = intval(substr($tid, -1));
						DB::query("UPDATE ".DB::table('forum_attachment_'.$tableid)." SET uid='$_G[uid]' WHERE uid='$olduid' and tid='$tid' and pid='$pid'", 'UNBUFFERED');
					}
				}
			}
		}
		return;
	}
	
	//�������ת������
	function post_top() {
		if(!$this->vars['open'])return '';
		global $seccodecheck, $secqaacheck;
		if(submitcheck('topicsubmit',0,$seccodecheck,$secqaacheck) || submitcheck('replysubmit',0,$seccodecheck,$secqaacheck) || submitcheck('commentsubmit',0,$seccodecheck,$secqaacheck)){
			$this->_guisepostchk();
		}else{
			return $this->getguisepostuserid('toppost');
		}
	}
	
	//�б�ײ����ٷ���Ƕ���
	function forumdisplay_fastpost_content(){
		return $this->getguisepostuserid();
	}
	
	//�����ظ���������Ƕ���(DZX2���ϰ汾������)
	function post_infloat_top(){
		return $this->getguisepostuserid('floatpost');
	}
	
	//���ÿ��ٷ���ģʽʱʹ�ô�Ƕ���
	function viewthread_top_output(){
		if($GLOBALS['_G']['setting']['fastpost'])return '';
		return $this->getguisepostuserid('threadtop');
	}
	
	//���ĵײ���ݻظ�Ƕ���
	function viewthread_fastpost_content(){
		return $this->getguisepostuserid();
	}
	
	//��׻�����չǶ���(��ҵ��)
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