<?php

/*
 * 本文件仅供插件调试用途，仅供内部使用或出现空白页面调试使用，正常使用下请勿打开调试开关
 */

define('YULEGAME_DEBUG', 0); // 如需打开调试开关，请设置为 1

// debug?
if (YULEGAME_DEBUG == 1 || $_SERVER['HTTP_HOST'] == 'benefitzonline_new') {
    error_reporting(E_ALL ^ E_NOTICE);
    ini_set('display_errors', '1');
}








?>