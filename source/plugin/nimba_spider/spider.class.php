<?php
/*
 *	nimba_spider (C)2012 AiLab Inc.
 *	nimba_spider Made By Nimba, Team From AiLab.CN
 *	Id: spider.class.php  AiLab.CN 2013-02-28 09:11$
 */
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
class plugin_nimba_spider{
	function common(){
		loadcache('plugin');
		global $_G;
		$var= $_G['cache']['plugin']['nimba_spider'];
		if($var['open']){
			$mod='';//记录版块和帖子备用
			if($_GET['mod']=='forumdisplay'&&!empty($_G['fid'])) $mod='forum';
			if($_GET['mod']=='viewthread'&&!empty($_G['tid'])) $mod='thread';
			include_once 'spider.inc.php';
		}
	}
}

?>