<?php

/*
 * ���ļ��������������;�������ڲ�ʹ�û���ֿհ�ҳ�����ʹ�ã�����ʹ��������򿪵��Կ���
 */

define('YULEGAME_DEBUG', 0); // ����򿪵��Կ��أ�������Ϊ 1

// debug?
if (YULEGAME_DEBUG == 1 || $_SERVER['HTTP_HOST'] == 'benefitzonline_new') {
    error_reporting(E_ALL ^ E_NOTICE);
    ini_set('display_errors', '1');
}








?>