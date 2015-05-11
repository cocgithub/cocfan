<?php
/**
 *	[Discuz!] (C)2001-2099 Comsenz Inc.
 *	This is NOT a freeware, use is subject to license terms
 *
 *	$Id: index.inc.php 2011-11-14 15:14:47 Ian - Zhouxingming $
 */
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

//定义插件id
define('PLUGIN_ID', 'vfastpost');

/**
 * 载入插件模块
 * @param string $model			模块名称, 位于source/plugin/PLUGIN_ID/model/下
 *					名称为 $model.class.php
 */
function ploadmodel($model) {
	$filename = DISCUZ_ROOT.'./source/plugin/'.PLUGIN_ID.'/model/'.$model.'.class.php';
	if(file_exists($filename)) {
		include_once $filename;
	} else {
		exit('Cannot find model named '.$model);
	}
}
/**
 * 插件语言包简化函数
 *
 * @param str 语言包key
 * return string
 */
function plang($str, $vars = array()) {
	return lang('plugin/'.PLUGIN_ID, $str, $vars);
}
?>
