<?php

	/*
	MZG����֧��С��
	���� fjyxian
	�ͷ���ѯ������QQ:1063790899
	��������QQ:51353835(ֻ�ṩ�г�����֧��)
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