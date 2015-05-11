<?php
if(!defined('IN_DISCUZ')) {
  exit('Access Denied');
}
class plugin_szqq_mailcheckalert {
	
	function global_footer() {
		global $_G;
		$return = '';
		$szqq_groups = unserialize($_G['cache']['plugin']['szqq_mailcheckalert']['szqq_groups']);
		if (in_array($_G['groupid'],$szqq_groups)) {
			$uid = $_G['uid'] ? $_G['uid'] : '';
			if(!empty($uid)){
				$emailstatus = DB::result_first("SELECT emailstatus FROM ".DB::table('common_member')." WHERE uid='$uid'");
				if(!$emailstatus){
					$szqq_mailcheckalert_setting = $_G ['cache']['plugin']['szqq_mailcheckalert'];
					$lang = lang('plugin/szqq_mailcheckalert');
					$find = array("{szqq_msg}","{member_email}");
					$replace = array($szqq_mailcheckalert_setting['szqq_msg'],$_G['member']['email']);
					$return_lang = str_replace($find,$replace,$lang['return']);
					if ($szqq_mailcheckalert_setting['szqq_skip_radio'] == "1") {
						$var_mail_domain = 'http://mail.'.trim(substr(strrchr($_G['member']['email'],'@'), 1));
						$return = '<script type="text/javascript">setTimeout(function () {showDialog("'.$_G['username'].$return_lang.'","","",function(){window.open("'.$var_mail_domain.'")},1,"","","","","",5);},1);</script>';
					} else {
						$return = '<script type="text/javascript">setTimeout(function () {showDialog("'.$_G['username'].$return_lang.'");},1);</script>';
					}
				}
			}
		}
		return $return;
	}

}

?>