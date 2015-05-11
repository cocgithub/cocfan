<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
session_start();
$identifier = 'yy_killreg';
$cvar=$_G['cache']['plugin'][$identifier];
include template('common/header_ajax');
if($_GET['sh']=='retry'){
	$_SESSION['yy_regtime']=$_G['timestamp'];
	echo 'succeed';
}else{
	if($_G['timestamp']-$_SESSION['yy_regtime']>=$cvar['time']&&isset($_SESSION['yy_regtime'])&&$cvar['open']){
		$key=strtoupper(substr(md5(random(10)),3,28));
		$_SESSION['yy_regkey']=$key;
		echo 'succeed|'.$_SESSION['yy_regkey'];
	}else{
		echo 'error';
	}
	unset($_SESSION['yy_regtime']);
}
include template('common/footer_ajax');
?>