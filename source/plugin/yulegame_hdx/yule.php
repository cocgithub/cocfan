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

$url = 'plugin.php?id=yulegame_hdx&op=yule';

if ($subop == 'gift') {
    $yuleId = intval($_G['gp_yuleId']);
    $yule = DB::fetch_first("SELECT * FROM " . DB::table('hdx_yule') . " WHERE available= 1 AND id='{$yuleId}'");

    if (!$yule) {
        showError(lang('plugin/yulegame_hdx', 'no_such_yule'));
    }
} else {
    // 娱乐列表
    $yules = array();

    $query = DB::query('SELECT * FROM ' . DB::table('hdx_yule') . ' WHERE  available= 1 ORDER BY disp_order LIMIT ' . $_start . ',' . $_perpage);

    while ($y = DB::fetch($query)) {
        $y = escape($y, 'html');
        $yules[] = $y;
    }

    $count = DB::result_first('SELECT COUNT(*) FROM ' . DB::table('hdx_yule') . '  WHERE available= 1');

    $multipage = multi($count, $_perpage, $_page, $url);
}
?>
