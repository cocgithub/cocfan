<?php
/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: install.php 31136 2012-07-19 02:05:04Z chenmengshu $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$sql = <<<EOF
CREATE TABLE IF NOT EXISTS `pre_plugin_guessulike_forumlike` (
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `fid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `hot` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `hot1` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `hot2` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `hot3` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `hot4` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `hot5` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `hot6` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `hot7` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`,`fid`),
  KEY `uid` (`uid`,`hot`)
) ENGINE=MyISAM;


CREATE TABLE IF NOT EXISTS `pre_plugin_guessulike_reply_user` (
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `targetuid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `count` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`,`targetuid`),
  KEY `uid` (`uid`,`count`),
  KEY `dateline` (`dateline`)
) ENGINE=MyISAM;



CREATE TABLE IF NOT EXISTS `pre_plugin_guessulike_user_cache` (
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `tids` mediumtext NOT NULL,
  PRIMARY KEY (`uid`),
  KEY `dateline` (`dateline`)
) ENGINE=MyISAM;



CREATE TABLE IF NOT EXISTS `pre_plugin_guessulike_user_thread` (
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `tid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`,`tid`,`type`),
  KEY `uid` (`uid`,`type`,`dateline`),
  KEY `tid` (`tid`,`type`,`dateline`),
  KEY `dateline` (`dateline`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `pre_plugin_guessulike_relatethread_cache` (
  `tid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `threads` mediumtext NOT NULL,
  PRIMARY KEY (`tid`),
  KEY `dateline` (`dateline`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `pre_plugin_guessulike_threads` (
  `tid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `fid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `replies` int(10) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`tid`),
  KEY `fid` (`fid`,`dateline`,`replies`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `pre_plugin_guessulike_user_keywords` (
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `keywords` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM;

EOF;

runquery($sql);


$error_install = 0;

if(!copy(DISCUZ_ROOT.'./source/plugin/guessulike/cron_plugin_guessulike.php', DISCUZ_ROOT.'./source/include/cron/cron_plugin_guessulike.php') && !file_exists(DISCUZ_ROOT.'./source/include/cron/cron_plugin_guessulike.php')) {
	//无法自动安装计划任务
	cpmsg($installlang['cron_install_failed'], '', 'error');
	$error_install = 1;
} else {
	$cronCheck = DB::fetch_first("SELECT filename FROM ".DB::table('common_cron')." WHERE filename='cron_plugin_guessulike.php'");
	if(!$cronCheck) {
		DB::query("INSERT INTO ".DB::table('common_cron')." (available, type, name, filename, lastrun, nextrun, weekday, day, hour, minute) VALUES ('1','system','{$installlang['cron_name']}','cron_plugin_guessulike.php','1269746623','1269792000','-1','-1','0','0')");
	}
}

$finish = TRUE;

?>