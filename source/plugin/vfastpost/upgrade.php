<?php
/**
 *	[Discuz!] (C)2001-2099 Comsenz Inc.
 *	This is NOT a freeware, use is subject to license terms
 *
 *	$Id: upgrade.php 2011-12-07 15:39:57 Ian - Zhouxingming $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
include_once DISCUZ_ROOT.'./source/plugin/vfastpost/model/index.inc.php';
ploadmodel('install');
$install = new pluginInstall;
$install->install();
$finish = 1;
?>
