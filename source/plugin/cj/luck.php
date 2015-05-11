<?php

/*
	[Discuz!] (C)2001-2006 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$RCSfile: luck.php,v $
	$Revision: 1.161 $
	$Date: 2007/01/25 03:05:47 $
*/

require_once './include/common.inc.php';
require_once './forumdata/cache/plugin_luck.php';
require_once DISCUZ_ROOT.'./include/discuzcode.func.php';
if(!$discuz_uid) {
	showmessage('not_loggedin', NULL, 'NOPERM');
}

$startdate = $_DPLUGIN['luck']['vars']['startdate'];	//��ʼ���ڣ���д��ʽ2007-02-11
$enddate = $_DPLUGIN['luck']['vars']['enddate'];	//�������ڣ���д��ʽ2007-02-24
$joincount = $_DPLUGIN['luck']['vars']['joincount']; 		//���Բ���齱�Ĵ���
$getcredit = $_DPLUGIN['luck']['vars']['getcredit']; 		//������չ����1~8
$mincredit = $_DPLUGIN['luck']['vars']['mincredit']; 		//��û��ֵ���Сֵ�p��
$maxcredit = $_DPLUGIN['luck']['vars']['maxcredit'];  		//��û��ֵ����ֵ�j��

if($_GET['clear']){
	if ($discuz_uid && in_array($adminid, array(1)) && $_GET['clear']=='1')
	{
		$db->query("TRUNCATE TABLE `{$tablepre}luck`");
		showmessage('���γ齱�����Ѿ���������ֱ�����','luck.php');
	}else{
		showmessage('������ʲô�����¹���Ա�������id����','luck.php');
	}
}

if(empty($getcredit) || $getcredit < 1 || $getcredit > 8) {
	showmessage('�������������⣬�뷵���޸�');
}

$starttime = strtotime($startdate) + date('Z') - ($timeoffset * 3600);
$endtime = strtotime($enddate) + date('Z') - ($timeoffset * 3600);

if($startdate > $enddate) {
	showmessage('��ʼʱ����ڽ������뷵���޸�');
} elseif($timestamp < $starttime) {
	showmessage('���û��ʼ���뷵��');
} elseif($timestamp > $endtime) {
	showmessage('��Ѿ������ˣ��뷵��', 'index.php');
}

$query = $db->query("SELECT COUNT(uid) as joinnum, SUM(credits) as credits FROM {$tablepre}luck");
$total = $db->fetch_array($query);

$query = $db->query("SELECT count, credits FROM {$tablepre}luck WHERE uid='$discuz_uid'");
if($luck = $db->fetch_array($query)) {
	$update = 1;
} else {
	$update = 0;
}

$remaincount = $joincount - $luck['count'];
$remaincount = $remaincount > 0 ? $remaincount : 0;

if(!submitcheck('lucksubmit', 1)) {

	$query = $db->query("SELECT l.credits, l.uid, m.username
			FROM {$tablepre}luck l
			LEFT JOIN {$tablepre}members m ON m.uid=l.uid
			ORDER BY l.credits DESC LIMIT 0, 10");
	while($top = $db->fetch_array($query)) {
		$toplist[] = $top;
	}

	include template('luck');

} else {

	if($luck['count'] < $joincount) {

		$query = $db->query("SELECT regdate, posts, digestposts, oltime FROM {$tablepre}members WHERE uid='$discuz_uid'");
		$member = $db->fetch_array($query);

		$regday = intval(($timestamp - $member['regdate']) / 86400);
		$creditsformula = $_DPLUGIN['luck']['vars']['lucknum'];
		eval("\$lucknum = round($creditsformula);");
		$mostcredit = $lucknum > $maxcredit ? $maxcredit : intval($lucknum);
		$mostcredit = $mostcredit > $mincredit ? $mostcredit : $mincredit; 
		$finalcredit = rand($mincredit, $mostcredit);

		$db->query("UPDATE {$tablepre}members SET extcredits$getcredit=extcredits$getcredit+'$finalcredit' WHERE uid='$discuz_uid'");

		if($update) {
			$db->query("UPDATE {$tablepre}luck SET count=count+1, credits=credits+'$finalcredit' WHERE uid='$discuz_uid'");
		} else {
			$db->query("INSERT INTO {$tablepre}luck (uid, count, credits) VALUES ('$discuz_uid', '1', '$finalcredit')", 'UNBUFFERED');
		}

		showmessage('��ϲ����'.$finalcredit.$extcredits[$getcredit]['title'], 'luck.php');

	} else {

		showmessage('ÿ��ֻ��'.$joincount.'�γ齱���ᣬ���˲�Ҫ̫̰�İ�!', dreferer());

	}

}

?>