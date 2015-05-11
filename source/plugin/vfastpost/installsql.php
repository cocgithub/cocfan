<?php
/**
 *	[Discuz!] (C)2001-2099 Comsenz Inc.
 *	This is NOT a freeware, use is subject to license terms
 *
 *	$Id: installsql.php 2011-11-24 15:19:41 Ian - Zhouxingming $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$installsql = <<<SQL
CREATE TABLE pre_plugin_vfastpost_stat (
  `daytime` CHAR(10) NOT NULL COMMENT '日期',
  `nt` SMALLINT(6) NOT NULL COMMENT '新发帖',
  `nt_index` SMALLINT(6) NOT NULL COMMENT '新发帖来自首页',
  `nr` MEDIUMINT(8) NOT NULL COMMENT '新回复',
  `nr_vf` SMALLINT(6) NOT NULL COMMENT '新回复来自vf',
  `nr_vf_float` SMALLINT(6) NOT NULL COMMENT '新回复来自vf浮动',
  `cl_f` SMALLINT(6) NOT NULL COMMENT '板块发帖点击数',
  `cl_v_t` SMALLINT(6) NOT NULL COMMENT '看帖发帖点击数',
  `cl_v_r` SMALLINT(6) NOT NULL COMMENT '看帖回复点击数',
  `cl_vf_float` MEDIUMINT(8) NOT NULL COMMENT '看帖回复点击数来自vf浮动',
  `cl_vf_first` MEDIUMINT(8) NOT NULL COMMENT '看帖回复点击数来子vf顶楼',
  PRIMARY KEY  (`daytime`)
) ENGINE=MyISAM;

CREATE TABLE pre_plugin_vfastpost_myforum (
 `uid` MEDIUMINT( 8 ) UNSIGNED NOT NULL,
 `myforum` VARCHAR( 255 ) NOT NULL,
PRIMARY KEY (`uid`)
) ENGINE=MYISAM ;
SQL;

?>
