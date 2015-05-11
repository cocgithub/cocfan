-- phpMyAdmin SQL Dump
-- version 2.9.1
-- http://www.phpmyadmin.net
-- 
-- 主机: localhost:6033
-- 生成日期: 2007 年 01 月 27 日 02:09
-- 服务器版本: 5.0.24
-- PHP 版本: 5.1.6
-- 
-- 数据库: `discuzhead`
-- 

-- --------------------------------------------------------

-- 
-- 表的结构 `cdb_luck`
-- 

CREATE TABLE `cdb_luck` (
  `uid` mediumint(8) unsigned NOT NULL,
  `count` tinyint(1) unsigned NOT NULL default '0',
  `credits` smallint(6) unsigned NOT NULL default '0',
  PRIMARY KEY  (`uid`),
  KEY `credits` (`credits`)
) TYPE=MyISAM;
