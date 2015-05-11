<?php
/**
 * Open Cloud Collection For Discuz!X 2.0
 * ============================================================================
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @package    onexin_occ
 * @date	   2013-02-08
 * @author	   King
 * @copyright  Copyright (c) 2012 Onexin Platform Inc. (http://www.onexin.com)
 */
 
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
	
/*
//--------------Tall us what you think!----------------------------------
*/

class plugin_onexin_occ {

	protected static $conf = array();
	protected static $isopen = FALSE;

	public function plugin_onexin_occ() {
		global $_G;		
		if(!isset($_G['cache']['plugin'])){
			loadcache('plugin');
		}
		self::$isopen = $_G['cache']['plugin']['onexin_occ']['isopen'] ? TRUE : FALSE;
		if(self::$isopen) {
			self::$conf = $_G['cache']['plugin']['onexin_occ'];		
			self::$conf['server'] = "http://".self::$conf['server']."/occ/";
			self::$conf['usefids'] = (array)unserialize(self::$conf['usefids']);
			self::$conf['usergroups'] = (array)unserialize(self::$conf['usergroups']);
			loadcache('onexin_occ');
			self::$conf = array_merge(self::$conf, (array)unserialize(stripslashes($_G['cache']['onexin_occ'])));
				
			if(empty(self::$conf['usergroups'][0]) || in_array($_G['groupid'], self::$conf['usergroups'])){
				self::$conf['isgroupid'] = TRUE;
			}
			if(empty(self::$conf['usefids'][0]) || in_array($_GET['fid'], self::$conf['usefids'])){
				self::$conf['isfid'] = TRUE;
			}
			if(empty(self::$conf['usecatids'][0]) || in_array($_GET['catid'], self::$conf['usecatids'])){
				self::$conf['iscatid'] = TRUE;
			}
			
			$occfied_conf = $_G['cache']['plugin']['onexin_occfied'];
			$occfied_conf['usergroups'] = (array)unserialize($occfied_conf['usergroups']);
			if($occfied_conf['isopen'] && (empty($occfied_conf['usergroups'][0]) || in_array($_G['groupid'], $occfied_conf['usergroups']))){
				self::$conf['script'] = $occfied_conf['script'];
			}
		}
	}
	
}

// forum
class plugin_onexin_occ_forum extends plugin_onexin_occ {

	public function post_top_output() {
		global $_G;
		
		if(!self::$isopen) return '';
		
		@extract(self::$conf);
		if(!$_GET['special'] && $_GET['action']=='newthread' && $isfid && $isgroupid && $isopen) {
			include template('onexin_occ:post_occ_forum');
		}
		
		return $return;
	}
	
}

// portal
class plugin_onexin_occ_portal extends plugin_onexin_occ  {
	
	public function portalcp_top_output(){
		global $_G;
		
		if(!self::$isopen) return '';
		
		@extract(self::$conf);
		if($_GET['ac']=='article' && empty($_GET['op']) && $iscatid && $isgroupid && $isopen) {
			include template('onexin_occ:post_occ_portal');
		}
		
		return $return;
	}
	
}