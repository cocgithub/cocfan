<?php
/*
 * �����޸ı�ҳ�κ����ݣ��������Ը���
 * ���� ���������� �˹�����ʵ���� ��Ʒ��Made By Nimba, Team From AiLab.cn)
 */
$info=array();
$info['name']='nimba_security';
$info['version']='v2.0.1';
require_once DISCUZ_ROOT.'./source/discuz_version.php';
$info['siteversion']=DISCUZ_VERSION;
$info['siterelease']=DISCUZ_RELEASE;
$info['timestamp']=TIMESTAMP;
$info['nowurl']=$_G['siteurl'];
$info['siteurl']='http://www.cocfan.com/';
$info['clienturl']='http://www.cocfan.com/';
$info['siteid']='73CA467E-05A2-EBD5-3A64-A4668BD16B7A';
$info['sn']='2013062315M9593797xs';
$info['adminemail']=$_G['setting']['adminemail'];
$infobase=base64_encode(serialize($info));
?>