<?php
/*
	[ÐéÄâÂí¼×·¢Ìû»Ø¸´]Guisepost Plugin For Discuz! X1.0 - X2.5 ALL
	Copyring (C) 2010 KL3w.com; You can modify this plugin for your website
	This is not a freeware, use is subject to license terms
	Author: PGCAO£»Version: 2.3.1£»Time: 2012-11-20 10:12:32
*/
 
if(!defined('IN_DISCUZ')){exit('Access Denied');}

if($_G['inajax']){
	$uid = $_G['uid'];$use_uid = 0;
	$tid = isset($_POST['tid']) ? intval($_POST['tid']) : intval($_G['gp_tid']);
	$pid = isset($_POST['pid']) ? intval($_POST['pid']) : intval($_G['gp_pid']);
	if($uid && $tid && $pid){
		loadcache('plugin'); //¶ÁÈ¡²å¼þ»º´æ
		$vars = $_G['cache']['plugin']['kl3w_guisepost'];		
		$rn = strtoupper(substr(PHP_OS, 0, 3)) == 'WIN' ? "\r\n" : "\n";
		$useidarr = explode($rn, $vars['useid']);
		if(is_array($useidarr) && $vars['open']){
			foreach($useidarr as $key => $val){
				$useval = explode("=", $val);
				if($uid == $useval[0] && isset($useval[1]) && !empty($useval[1])){
					$use_uid = intval(trim($useval[0]));
					break;
				}
			}
		}		
		if($use_uid){
			$action = isset($_GET['action']) ? $_GET['action'] : $_G['gp_action'];
			if($action == 'rate'){//guiserate for vip
				$vip = '&#x5F53;&#x524D;&#x4E3A;&#x514D;&#x8D39;&#x7248;&#x6CA1;&#x8BC4;&#x5206;&#x529F;&#x80FD;&#xFF0C;&#x8BC4;&#x5206;&#x7248;&#x53EA;&#x4E3A;&#x6709;&#x9700;&#x6C42;&#x7684;&#x7AD9;&#x957F;&#x5546;&#x4E1A;&#x8BA2;&#x5236;&#xFF0C;&#x8BC4;&#x5206;&#x7248;&#x8D39;&#x7528;&#x53EA;&#x9700;10&#x5143;&#x5E76;&#x63D0;&#x4F9B;&#x6C38;&#x4E45;&#x5347;&#x7EA7;&#x652F;&#x6301;,&#x8054;&#x7CFB; {url}';
				showmessage($vip, '', array('url'=>'<a href="http://www.kl3w.com" target="_blank">http://www.kl3w.com</a>'));
			}else{
				include template('kl3w_guisepost:comment');
			}
			exit(0);
		}
	}
}
showmessage('undefined_action', NULL);
?>