<?php

	/*
	MZG技术支持小组
	作者 fjyxian
	客服咨询与销售QQ:1063790899
	开发作者QQ:51353835(只提供有偿技术支持)
	*/
	if (!defined('IN_DISCUZ')) {
			exit('Access Denied');
	}

if (empty($_G['uid'])) {
showmessage('to_login', null, array(), array('showmsg' => true, 'login' => 1));
}
	$_G['m_pid'] = 'mzg_advertise';
	loadcache($_G['m_pid']);
	$mod = trim($_GET['mod']);
	$mods = array('index', 'view', 'log');
	if (!$mod || !in_array($mod, $mods)) $mod = 'index';
	$pageurl = "plugin.php?id=" . $_G['m_pid'] . ":index";
	include_once libfile($mod, 'plugin/' . $_G['m_pid']);

?>