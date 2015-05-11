<?php


if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class plugin_yy_killreg {
	var $identifier = 'yy_killreg';
	var $cvar=null;
	function plugin_yy_killreg(){
		global $_G;
		$this->cvar=$_G['cache']['plugin'][$this->identifier];
	}
	function _killreg(){
		global $_G;
		if(!$this->cvar['open'])
			return;
		session_start();
		if(submitcheck('regsubmit')){
			$security=$_GET[$this->cvar['name']];
			$uname=$_GET[$_G['setting']['reginput']['username']];
			if(!$security){
				showmessage($this->plang('nokey').$this->cvar['title']);
			}
			if(!isset($_SESSION['yy_regkey'])||$_SESSION['yy_regkey']!=trim($security)){
				showmessage($this->cvar['title'].$this->plang('keyfalse'));
			}
			unset($_SESSION['yy_regkey']);
		}
	}
	function _killregout(){
		global $_G;
		if(!$this->cvar['open'])
			return;
		session_start();
		$_SESSION['yy_regtime']=$_G['timestamp'];
		$cvar=$this->cvar;
		include template('yy_killreg:reg');
		return $return;
	}
	function plang($str) {
		return lang('plugin/'.$this->identifier, $str);
	}
}
class plugin_yy_killreg_member extends plugin_yy_killreg{
	function register_member(){
		$this->_killreg();
	}
	function register_top_output(){
		return $this->_killregout();
	}

}
?>