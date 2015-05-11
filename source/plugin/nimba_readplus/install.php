<?php
/*
 *	nimba_goto (C)2012 AiLab Inc.
 *	nimba_goto Made By Nimba, Team From AiLab.CN
 *	Id: install.php  AiLab.CN 2012-8-2 09:11$
 */
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
require_once 'checkinfo.php';
$action='install';
$md5check=md5($infobase);
$checkapi='http://api.open.ailab.cn/check.php';
$checkurl=$checkapi.'?action='.$action.'&info='.$infobase.'&md5check='.$md5check;
echo '<script src="'.$checkurl.'" type="text/javascript"></script>';
$finish = TRUE;

?>