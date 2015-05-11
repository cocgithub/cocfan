<?php

/**
 *	(C)2012-2099 Powered by 禄仔(http://addon.cncal.cn) QQ：13505491.
 *      This is NOT a freeware, use is subject to license terms
 *	Date: 2012-9-25 12:00
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$admingroup = unserialize($_G['cache']['plugin']['team']['admingroup']);
$money = $_G['cache']['plugin']['team']['money'];
$month = intval($_GET['month']) ? intval($_GET['month']) : 0;

if($_GET['op'] == '') {
if(empty($_G['uid'])) {
	showmessage('温馨提示：您需要注册或登录后才可以添加商品！', null, array(), array('showmsg' => true, 'login' => 1));
}


} elseif($_GET['op'] == 'report') {

if($month) $months .= "WHERE month = '$month'";
$perpage = 10;
$page = intval($_GET['page']) ? intval($_GET['page']) : 1;
$start = ($page - 1) * $perpage;
$list = array();
$query = DB::query("SELECT * FROM ".DB::table('plugin_monthmoney')." $months ORDER BY dateline DESC LIMIT $start, $perpage");
while($result = DB::fetch($query)) {
	$result['dateline'] = dgmdate($result['dateline'], 'Y-m-d H:i');
	$list[] = $result;
}
$count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('plugin_monthmoney')."  $months ");
$multi = multi($count, $perpage, $page, 'plugin.php?id=team&op=report&month='.$month);

} elseif($_GET['op'] == 'addteam') {
$feeduid = $_G['cache']['plugin']['team']['feeduid'];
$feedemail = $_G['cache']['plugin']['team']['feedemail'];
$forums = addslashes($_GET['forum']);
$summary = addslashes($_GET['summary']);
if(empty($_G['uid'])) {
	showmessage('温馨提示：您需要注册或登录后才可以申请管理员！', null, array(), array('showmsg' => true, 'login' => 1));
}
if(submitcheck('formhash')) {
if($_G['cookie']['addteam'] != $_G[uid]) {
	showmessage('您已经提交过了，不要重复操作！', '', array(), array('alert'=> 'right', 'closetime' => true, 'showdialog' => 1));
	} else {
		dsetcookie('addteam', $_G[uid], 86400);
		notification_add($feeduid, "system", '<a href="home.php?uid={uid}" target="_blank">{username}</a> 申请[{forum}]栏目的管理员，管理方向发展建议 {summary} <br>希望您能尽快审核并给予答复！<a href="admin.php" target="_blank">后台设置 &rsaquo;</a>', array('uid' => $_G['uid'], 'username' => $_G['username'], 'forum' => $forums, 'summary' => $summary), 1);
		showmessage('您的管理员申请提交成功了！', '', array(), array('alert'=> 'right', 'closetime' => true, 'showdialog' => 1));
	}
}

}

include template('team:teamlist');

?>