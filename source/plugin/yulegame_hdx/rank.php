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




if (empty($_GET['subop'])) {
    $subop = 'level_rank';
}

$url = 'plugin.php?id=yulegame_hdx&op=rank&subop='. escape($subop,'url');

switch ($subop) {
    case 'level_rank':
        $orderby = 'level DESC,exp DESC';
        $_GET['subop'] = 'level_rank';
        $rankTitle = lang('plugin/yulegame_hdx', 'level_rank');
        break;
    case 'money_rank':
        $orderby = $_moneyExtStr .' DESC';
        $rankTitle = lang('plugin/yulegame_hdx', 'money_rank');
        break;
    case 'rob_rank':
        $orderby = 'rob_times DESC';
        $rankTitle = lang('plugin/yulegame_hdx', 'mad_rank');
        break;
    case 'bad_luck_rank':
        $orderby = 'robbed_times DESC';
        $rankTitle = lang('plugin/yulegame_hdx', 'bad_luck_rank');
        break;
    default:
        throw new Exception(lang('plugin/yulegame_hdx', 'invalid_param_request'));
}

if (intval($_setting['sw_ext']) > 0 && intval($_setting['sw_ext']) < 9) {
    $swField = 'mc.extcredits' . intval($_setting['sw_ext']) . ' as player_sw';
} else {
    $swField = 'p.sw as player_sw';
}

$query = DB::query("
SELECT m.uid,m.username," . $swField . "," . $_moneyExtStr . " as player_money,p.level,p.exp,p.title,p.rob_times,p.rob_money_amount,v.robbed_times,v.robbed_money_amount 
FROM " . DB::table('hdx_player') . " p," . DB::table('common_member') . " m,
    " . DB::table('common_member_count') . " mc 
LEFT JOIN " . DB::table('hdx_victim') . " v ON v.uid=mc.uid 
WHERE p.uid=m.uid AND m.uid=mc.uid AND p.available = 1 
ORDER BY " . $orderby . " LIMIT " . $_start . "," . $_perpage);

$players = array();

$count = DB::result_first('SELECT COUNT(*) FROM ' . DB::table('hdx_player') . ' WHERE available = 1');
$i = $_start;
while ($p = DB::fetch($query)) {
    $p = escape($p, 'html');
    $i = $i + 1;
    $p['robbed_times'] = intval($p['robbed_times']);
    $p['robbed_money_amount'] = intval($p['robbed_money_amount']);
    $p['rank'] = $i;
    $players[] = $p;
}

$multipage = multi($count, $_perpage, $_page, $url);
?>
