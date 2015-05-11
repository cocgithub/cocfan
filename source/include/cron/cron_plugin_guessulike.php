<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: cron_plugin_guessulike.php 30459 2012-05-30 02:55:11Z chenmengshu $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

loadcache('plugin');

if(!$_G['cache']['plugin']['guessulike']['datalifetime']) {
	$_G['cache']['plugin']['guessulike']['datalifetime'] = 24;
}
$getTime = $_G['cache']['plugin']['guessulike']['datalifetime'] * 3600;

C::t('#guessulike#plugin_guessulike_user_cache')->delete_outdated(TIMESTAMP - 600);
C::t('#guessulike#plugin_guessulike_relatethread_cache')->delete_outdated(TIMESTAMP - 600);

$forumLikeTime = $getTime < 604800 ? 604800 : $getTime;
C::t('#guessulike#plugin_guessulike_forumlike')->delete_outdated(TIMESTAMP - $forumLikeTime);
C::t('#guessulike#plugin_guessulike_reply_user')->delete_outdated(TIMESTAMP - $getTime);
C::t('#guessulike#plugin_guessulike_threads')->delete_outdated(TIMESTAMP - $getTime);
C::t('#guessulike#plugin_guessulike_user_thread')->delete_outdated(TIMESTAMP - $getTime);

?>