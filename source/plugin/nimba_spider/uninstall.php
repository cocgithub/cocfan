<?php
/*
 *	nimba_spider (C)2012 AiLab Inc.
 *	nimba_spider Made By Nimba, Team From AiLab.CN
 *	Id: uninstall.php  AiLab.CN 2013-02-28 09:11$
 */
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
DB::query("DROP TABLE IF EXISTS ".DB::table('nimba_spider')."");
$finish = TRUE;
?>