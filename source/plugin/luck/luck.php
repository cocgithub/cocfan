<?php

/*
	[Discuz!] (C)2001-2006 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$RCSfile: luck.php,v $
	$Revision: 1.161 $
	$Date: 2007/01/25 03:05:47 $
*/

require_once './include/common.inc.php';

if(!$discuz_uid) {
	showmessage('not_loggedin', NULL, 'NOPERM');
}

$startdate = '2007-02-17';	//��ʼ���ڣ���д��ʽ2007-02-17
$enddate = '2007-02-24';	//�������ڣ���д��ʽ2007-02-24
$joincount = 3; 		//���Բ���齱�Ĵ���
$getcredit = 1; 		//������չ����1~8
$mincredit = 1; 		//��û��ֵ���Сֵ
$maxcredit = 100; 		//��û��ֵ����ֵ

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
	showmessage('��Ѿ�������', 'index.php');
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
		$lucknum = ($member['digestposts'] * 15 + $member['post'] * 10 + $member['oltime'] * 5 + $regday * 5) / 100;
		$mostcredit =  $lucknum > $maxcredit ? $maxcredit : intval($lucknum);
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