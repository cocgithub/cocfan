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

// log
if ($subop == 'my') {
    $query = DB::query('SELECT * FROM ' . DB::table('hdx_log') . ' WHERE who_uid=' . $_uid . ' OR to_uid=' . $_uid . ' ORDER BY created_at DESC LIMIT 11');
} else {
    $query = DB::query('SELECT * FROM ' . DB::table('hdx_log') . ' ORDER BY created_at DESC LIMIT 11 ');
}
$logs = array();
while ($l = DB::fetch($query)) {
    $l['msg'] = msgFilter($l['msg'], $_player['username'], lang('plugin/yulegame_hdx', 'i'));
    $logs[] = $l;
}





$rankTopNum = intval($_setting['rank_top_num']);

if ($rankTopNum > 0) {
    $robRanks = array();
    $query = DB::query("
SELECT m.uid,m.username,p.rob_times, p.rob_money_amount  
FROM " . DB::table('hdx_player') . " p," . DB::table('common_member') . " m 
WHERE p.uid=m.uid AND p.available = 1 
ORDER BY rob_times DESC LIMIT " . $rankTopNum);

    $players = array();
    $i = 0;
    while ($p = DB::fetch($query)) {
        $p = escape($p, 'html');
        $i++;
        $p['rank'] = $i;

        $p['rob_times'] = intval($p['rob_times']);
        $robRanks[] = $p;
    }



    $badLuckRanks = array();
    $query = DB::query("
SELECT m.uid,m.username,v.robbed_times, v.robbed_money_amount  
FROM " . DB::table('hdx_player') . " p," . DB::table('common_member') . " m 
LEFT JOIN " . DB::table('hdx_victim') . " v ON v.uid=m.uid 
WHERE p.uid=m.uid AND p.available = 1 
ORDER BY robbed_times DESC LIMIT " . $rankTopNum);

    $i = 0;
    while ($p = DB::fetch($query)) {
        $p = escape($p, 'html');
        $i++;
        $p['rank'] = $i;

        $p['robbed_times'] = intval($p['robbed_times']);
        $p['robbed_money_amount'] = intval($p['robbed_money_amount']);
        $badLuckRanks[] = $p;
    }
    
    
    
     $levelRanks = array();
    $query = DB::query("
SELECT m.uid,m.username,p.level,p.title  
FROM " . DB::table('hdx_player') . " p," . DB::table('common_member') . " m 
WHERE p.uid=m.uid AND p.available = 1 
ORDER BY level DESC,exp DESC LIMIT " . $rankTopNum);

    $players = array();
    $i = 0;
    while ($p = DB::fetch($query)) {
        $p = escape($p, 'html');
        $i++;
        $p['rank'] = $i;

        $levelRanks[] = $p;
    }
    
    $moneyRanks = array();
    $query = DB::query("
SELECT m.uid,m.username," . $_moneyExtStr . " money,p.rob_money_amount  
FROM " . DB::table('hdx_player') . " p," . DB::table('common_member') . " m,
    " . DB::table('common_member_count') . " mc 
WHERE p.uid=m.uid AND m.uid=mc.uid AND p.available = 1 
ORDER BY " . $_moneyExtStr . " DESC LIMIT " . $rankTopNum);


    $i = 0;
    while ($p = DB::fetch($query)) {
        $p = escape($p, 'html');
        $i++;
        $p['rank'] = $i;

        $moneyRanks[] = $p;
    }
}
?>
