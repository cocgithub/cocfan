<?php

/* ===============================================================
 * @插件名称			黑道生涯X
 * @插件版权			2007-2011 娱乐游戏.NET www.yulegame.net
 * @插件作者			Ricky Lee (ricky_yahoo@hotmail.com)
 * ******** 请尊重作者的劳动成果, 保留以上版权信息 *********************
 * ******** 本站致力于高质量插件开发, 如果你需要定做插件请QQ 231753
 * *** 或者EMAIL: ricky_yahoo@hotmail.com
 * *** 或者访问: http://bbs.yulegame.net 发送论坛短消息给 ricky_yahoo

 * *** 以下为<娱乐游戏网>出品的其他精品插件(请到论坛下载试用版):
 * 1: 黑道生涯 
 * 2: 游戏发号 
 * 3: 猜猜乐 
 * 5: 娱乐大富翁 
 * *** 感谢你对本站插件的支持和厚爱!
 * *** <娱乐游戏网> - 插件制作团队
 * ================================================================
 */

// 必须使用此判断避免外部调用
if (!defined('IN_DISCUZ')) {
    exit('Access Denied');
}



if (!submitcheck('quitsubmit')) {
    showError(lang('plugin/yulegame_hdx', 'submit_invalid'));
}

$result = false;

// INIT
$url = 'plugin.php?id=yulegame_hdx';


// SETTING
$_hdx['quit_rob_times'] = intval($_hdx['quit_rob_times']);
$_hdx['quit_join_times'] = intval($_hdx['quit_join_times']);
$_hdx['quit_rate'] = floatval($_hdx['quit_rate']);






// 是否够钱
$quitFee = ceil($_player['rob_times'] * $_hdx['quit_rate']);
if ($quitFee > 0) {
    if ($_money < $quitFee) {
        showError(lang('plugin/yulegame_hdx', 'money_not_enough_to_quit', array('money_title' => $_moneyTitle, 'quit_fee' => $quitFee, 'hd' => $_hdxLang['hd'])));
    }

// update db
    $update[] = $_moneyExtStr . '=' . $_moneyExtStr . '-' . $quitFee;

    DB::query('UPDATE ' . DB::table('common_member_count') . ' SET ' . implode(',', $update) . ' WHERE uid=' . $_uid);
}

// 打劫次数
if ($_hdx['quit_rob_times'] > 0 && $_player['rob_times'] < $_hdx['quit_rob_times']) {
    showError(lang('plugin/yulegame_hdx', 'rob_times_not_enough_to_quit', array('rob' => $_hdxLang['rob'], 'quit_rob_times' => $_hdx['quit_rob_times'], 'hd' => $_hdxLang['hd'])));
}


// 累积加入次数
if ($_hdx['quit_join_times'] > 0) {
    $times = DB::result_first('SELECT COUNT(*) FROM ' . DB::table('hdx_player_activity') . ' WHERE uid=' . $_uid . ' AND type=' . ACTIVITY_JOIN);

    if ($times >= $_hdx['quit_join_times']) {

        showError(lang('plugin/yulegame_hdx', 'join_times_too_many_to_quit', array('quit_join_times' => $_hdx['quit_join_times'], 'hd' => $_hdxLang['hd'])));
    }
}


$data = array(
    'uid' => $_uid,
    'type' => ACTIVITY_QUIT,
    'created_at' => $_timenow
);

DB::insert('hdx_player_activity', $data);



// log
$log['who_uid'] = $_uid;
$log['to_uid'] = 0;
$log['created_at'] = $_timenow;
$log['msg'] = '<font color=red>{{' . $_player['username'] . '}}'. lang('plugin/yulegame_hdx','already_quit') . $_hdxLang['hd'] . '。</font>';

DB::insert('hdx_log', $log);


// 删除所有相关数据
// msg
DB::query('DELETE FROM ' . DB::table('hdx_msg') . ' WHERE to_uid =' . $_uid . ' OR from_uid=' . $_uid);

// player item
DB::query('DELETE FROM ' . DB::table('hdx_player_item') . ' WHERE uid =' . $_uid);

// player setting
DB::query('DELETE FROM ' . DB::table('hdx_player_setting') . ' WHERE uid =' . $_uid);

// guard
DB::query('DELETE FROM ' . DB::table('hdx_guard') . ' WHERE uid =' . $_uid .' OR employer_uid='. $_uid);

// player
DB::query('DELETE FROM ' . DB::table('hdx_player') . ' WHERE uid =' . $_uid);

// log
DB::query('DELETE FROM ' . DB::table('hdx_log') . ' WHERE who_uid =' . $_uid .' OR to_uid ='. $_uid);


$msg = lang('plugin/yulegame_hdx', 'quit_successfully', array('hd' => $_hdxLang['hd']));

// 输出
showMsg($msg, true, array(
    'url' => $url
));
?>
