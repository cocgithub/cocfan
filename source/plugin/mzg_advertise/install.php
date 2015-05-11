<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
require_once DISCUZ_ROOT.'./source/discuz_version.php';
$sql=<<<EOF
CREATE TABLE `pre_plugin_advertise` (
  `did` int(8) NOT NULL auto_increment,
  `topid` tinyint(255) NOT NULL,
  `name` char(80) NOT NULL,
  `url` char(220) NOT NULL,
  `pic` char(220) NOT NULL,
  `price` int(4) NOT NULL,
  `price_type` tinyint(12) NOT NULL,
  `method` tinyint(4) NOT NULL,
  `maxcount` int(4) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `stime` int(11) NOT NULL,
  `etime` int(11) NOT NULL,
  `posttime` int(11) NOT NULL,
  `usercount` int(4) NOT NULL,
  PRIMARY KEY  (`did`)
) ENGINE=MyISAM;
CREATE TABLE `pre_plugin_advertise_log` (
  `lid` int(8) NOT NULL auto_increment,
  `uid` int(8) NOT NULL,
  `username` char(32) NOT NULL,
  `did` int(8) NOT NULL,
  `ip` char(32) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY  (`lid`),
  KEY `uid` (`uid`),
  KEY `did` (`did`),
  KEY `ip` (`ip`)
) ENGINE=MyISAM;
INSERT INTO `pre_common_cron` VALUES (23, 1, 'user', '[MZG]检查过期广告', 'mzg_advertise_check.php', 1331486936, 1331571600, -1, -1, 1, '0');

EOF;
runquery($sql);
$finish = TRUE;
?>