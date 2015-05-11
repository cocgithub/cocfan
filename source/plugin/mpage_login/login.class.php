<?php
/**
 *	[ЮДЕЧТМЬсаб(mpage_login.{login.class.php})] (C)2012-2099 Powered by www.mpage.cn.
 *	Version: 1.0
 *	Date: 2012-10-26 20:32
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class plugin_mpage_login {

	function plugin_mpage_login() {
		global $_G;

		$this->title = $_G['cache']['plugin']['mpage_login']['title'];
		$this->target = $_G['cache']['plugin']['mpage_login']['target'];
		$this->sina = $_G['cache']['plugin']['mpage_login']['sina'];
		$this->taobao = $_G['cache']['plugin']['mpage_login']['taobao'];
		$this->qihoo = $_G['cache']['plugin']['mpage_login']['qihoo'];
	}

	function global_footer() {
		global $_G;

		$this->page = CURSCRIPT . '_' . CURMODULE;
		$this->target = dunserialize($this->target);
		if($_G['uid'] || $_G['cookie']['loginuser'] || $_G['connectguest'] ||  !in_array($this->page, $this->target)) {
			return;
		}

		include template('mpage_login:login');
		return $return;
	}

}

?>