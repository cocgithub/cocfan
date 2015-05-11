<?php
/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: uninstall.php 30693 2012-06-12 07:49:50Z chenmengshu $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

@unlink(DISCUZ_ROOT.'./source/include/cron/cron_plugin_guessulike.php');

$sql = <<<EOF
DELETE FROM pre_common_cron WHERE filename='cron_plugin_guessulike.php';
DROP TABLE IF EXISTS pre_plugin_guessulike_forumlike;
DROP TABLE IF EXISTS pre_plugin_guessulike_reply_user;
DROP TABLE IF EXISTS pre_plugin_guessulike_user_thread;
DROP TABLE IF EXISTS pre_plugin_guessulike_user_cache;
DROP TABLE IF EXISTS pre_plugin_guessulike_relatethread_cache;
DROP TABLE IF EXISTS pre_plugin_guessulike_threads;
DROP TABLE IF EXISTS pre_plugin_guessulike_user_keywords;
EOF;

runquery($sql);

$finish = TRUE;
?>