<?php

	/*
	MZG����֧��С��
	���� fjyxian
	�ͷ���ѯ������QQ:1063790899
	��������QQ:51353835(ֻ�ṩ�г�����֧��)
	*/
	if (!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
			exit('Access Denied');
	}

    $identifier='mzg_advertise';
	$mod = trim($_G['gp_mod']);
	$mods = array('action', 'adview', 'adlog');
	if (!$mod || !in_array($mod, $mods)) $mod = 'adview';

	loadcache($identifier);
	$pageurl = "admin.php?action=plugins&operation=config&do=$_GET[do]&identifier=$_GET[identifier]&pmod=$_GET[pmod]";
	$rurl = "action=plugins&operation=config&do=$_GET[do]&identifier=$_GET[identifier]&pmod=$_GET[pmod]";

	$ahover[$mod] = ' class="ahover"';
	include_once libfile('admin_' . $mod, 'plugin/' . $identifier);

?>