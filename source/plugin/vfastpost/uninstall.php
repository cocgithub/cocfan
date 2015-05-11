<?php
/**
 *	[Discuz!] (C)2001-2099 Comsenz Inc.
 *	This is NOT a freeware, use is subject to license terms
 *
 *	$Id: uninstall.php 2011-11-25 11:38:53 Ian - Zhouxingming $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
include_once DISCUZ_ROOT.'./source/plugin/vfastpost/model/index.inc.php';
ploadmodel('install');
$install = new pluginInstall;
$install->uninstall();
$finish = true;
?>
