<?php
if(!defined('IN_DISCUZ')) {
	exit('Access denied');
}
$sql = <<<EOT
CREATE TABLE `pre_plugin_rdlottery` (
  `tid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `username` varchar(15) NOT NULL,
  `aid` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `extid` tinyint(4) NOT NULL,
  `virtual` tinyint(4) NOT NULL,
  `name` varchar(255) NOT NULL,
  `number` smallint(6) NOT NULL,
  `ext_price` smallint(6) NOT NULL,
  `real_price` mediumint(6) NOT NULL,
  `starttimefrom` int(10) NOT NULL,
  `starttimeto` int(10) NOT NULL,
  `rand` smallint(6) NOT NULL,
  `hot` int(11) DEFAULT '0',
  `isinfo` smallint(6) NOT NULL,
  PRIMARY KEY (`tid`),
  KEY `uid` (`uid`),
  KEY `starttimeto` (`starttimeto`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE `pre_plugin_rdlotteryapply` (
  `applyid` int(11) NOT NULL AUTO_INCREMENT,
  `tid` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `uid` int(11) NOT NULL,
  `dateline` int(11) NOT NULL,
  `ext_price` int(11) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `info` varchar(500) NOT NULL,
  PRIMARY KEY (`applyid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE `pre_plugin_rdlottery_message` (
  `mid` int(11) NOT NULL AUTO_INCREMENT,
  `tid` int(11) NOT NULL,
  `message` text NOT NULL,
  `uid` int(11) NOT NULL,
  PRIMARY KEY (`mid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
EOT;

runquery($sql);
$finish = true;
?>
