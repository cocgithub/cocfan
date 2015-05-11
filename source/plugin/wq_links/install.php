<?php
/**
 * 维清工作室 [ 专业开发各种Discuz!插件 ]
 *
 * Copyright (c) 2011-2012 http://www.weiqing.org All rights reserved.
 *
 * Author: wuchunuan <wuchunuan@163.com>
 *
 * $Id: main.inc.php 2012-4-8 上午09:04:17Z wuchunuan $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$sql = <<<EOF
DROP TABLE IF EXISTS `pre_wq_links`;
CREATE TABLE `pre_wq_links` (
  `id` int(10) NOT NULL auto_increment,
  `sitename` varchar(100) NOT NULL,
  `siteurl` varchar(255) NOT NULL,
  `description` mediumtext NOT NULL,
  `logo` varchar(255) NOT NULL,
  `uid` int(10) NOT NULL,
  `dateline` int(10) NOT NULL,
  `updatetime` int(10) NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM;
EOF;

runquery($sql);

$finish = TRUE;
?>
