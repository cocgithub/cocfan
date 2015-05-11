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
if (!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
    exit('Access Denied');
}
//loadcache('plugin');


if (!submitcheck('settingsubmit', 1)) {
// 黑道设置
    $hdx = array();
    $query = DB::query("SELECT * FROM " . DB::table('hdx_setting') . " ");

    while ($setting = DB::fetch($query)) {
        $key = $setting['skey'];
        $hdx[$key] = $setting['svalue'];
    }

    //showtips('<li>为增加数值设置的灵活性，你可以根据参数的可选格式来设置。如果说明内有以下可选格式(不包括中括号，A,B必须为正整数)：</li><li>[A] - 表示可填入一个正整数; </li><li>[A,B] - 表示可填入一个最小数和最大数，中间用半角逗号隔开，系统会随机抽取之间的数。</li><li>[A%] - 表示可以填入一个百分数。</li><li>[A%,B%] - 表示可以填入最小值和最大值的百分数，中间用半角逗号隔开。</li>');
    if (empty($hdx)) {
        $hdx = array(
            'quit_allow_quit' => 1,
            'quit_rate' => 10.5,
            'quit_rob_times' => 0,
            'quit_join_times' => 0,
            'allow_away' => 0,
            'away_fee' => 0,
            'away_max_day' => 0,
            'level_rate' => 10,
        );
    }

    showformheader('plugins&operation=config&do=' . $pluginid . '&identifier=yulegame_hdx&pmod=other_setting');

    showtableheader('');

    showtitle(lang('plugin/yulegame_hdx', 'setting_quit'));

    showsetting(lang('plugin/yulegame_hdx', 'setting_quit_allow_quit'), 'hdx[quit_allow_quit]', $hdx['quit_allow_quit'], 'radio', '', 0, lang('plugin/yulegame_hdx', 'setting_quit_allow_quit_desc'));

    showsetting(lang('plugin/yulegame_hdx', 'setting_quit_rate'), 'hdx[quit_rate]', $hdx['quit_rate'], 'text', '', 0, lang('plugin/yulegame_hdx', 'setting_quit_rate_desc'));
    showsetting(lang('plugin/yulegame_hdx', 'setting_quit_rob_times'), 'hdx[quit_rob_times]', $hdx['quit_rob_times'], 'text', '', 0, lang('plugin/yulegame_hdx', 'setting_quit_rob_times_desc'));
    showsetting(lang('plugin/yulegame_hdx', 'setting_quit_join_times'), 'hdx[quit_join_times]', $hdx['quit_join_times'], 'text', '', 0, lang('plugin/yulegame_hdx', 'setting_quit_join_times_desc'));


     showsetting(lang('plugin/yulegame_hdx', 'setting_allow_away'), 'hdx[allow_away]', $hdx['allow_away'], 'radio', '', 0, lang('plugin/yulegame_hdx', 'setting_allow_away_desc'));

    showsetting(lang('plugin/yulegame_hdx', 'setting_away_fee'), 'hdx[away_fee]', $hdx['away_fee'], 'text', '', 0, lang('plugin/yulegame_hdx', 'setting_away_fee_desc'));

    showsetting(lang('plugin/yulegame_hdx', 'setting_away_max_days'), 'hdx[away_max_days]', $hdx['away_max_days'], 'text', '', 0, lang('plugin/yulegame_hdx', 'setting_away_max_days_desc'));

    
    showsetting(lang('plugin/yulegame_hdx', 'setting_level_rate'), 'hdx[level_rate]', $hdx['level_rate'], 'text', '', 0, lang('plugin/yulegame_hdx', 'setting_level_rate_desc'));

    showsubmit('settingsubmit');
    showtablefooter();
    showformfooter();
} else {

    require DISCUZ_ROOT . './source/plugin/yulegame_hdx/include/function.inc.php';

    $hdx = $_G['gp_hdx'];


    if (floatval($hdx['level_rate']) <= 0) {
        cpmsg(lang('plugin/yulegame_hdx', 'level_rate_setting_error'), '', 'error');
    }

    foreach ($hdx as $key => $val) {
        $key = dhtmlspecialchars($key);
        $val = dhtmlspecialchars($val);
        DB::query("INSERT INTO " . DB::table('hdx_setting') . "(skey, svalue) VALUES ('" . $key . "', '" . $val . "') ON DUPLICATE KEY UPDATE svalue = '" . $val . "'");
    }

    cpmsg(lang('plugin/yulegame_hdx', 'done_successfully'), 'action=plugins&operation=config&do=' . $pluginid . '&identifier=yulegame_hdx&pmod=other_setting', 'succeed');
}
?>
