<?php

/* ===============================================================
 * @插件名称			黑道生涯X
 * @插件版权			2007-2011 武器游戏.NET www.yulegame.net
 * @插件作者			Ricky Lee (ricky_yahoo@hotmail.com)
 * ******** 请尊重作者的劳动成果, 保留以上版权信息 *********************
 * ******** 本站致力于高质量插件开发, 如果你需要定做插件请QQ 231753
 * *** 或者EMAIL: ricky_yahoo@hotmail.com
 * *** 或者访问: http://bbs.yulegame.net 发送论坛短消息给 ricky_yahoo

 * *** 以下为<娱乐游戏网>出品的其他精品插件(请到论坛下载试用版):
 * 1: 黑道生涯 
 * 2: 游戏发号 
 * 3: 猜猜乐 
 * 5: 武器大富翁 
 * *** 感谢你对本站插件的支持和厚爱!
 * *** <娱乐游戏网> - 插件制作团队
 * ================================================================
 */

// 必须使用此判断避免外部调用
if (!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

$url = 'plugin.php?id=yulegame_hdx&op=shop';

if ($subop == 'gift') {
    $itemId = intval($_GET['item_id']);
    $item = DB::fetch_first("SELECT * FROM " . DB::table('hdx_shop_item') . " WHERE available= 1 AND id='{$itemId}'");

    if (!$item) {
        showError(lang('plugin/yulegame_hdx', 'no_such_item'));
    }

    $item = escape($item, 'html');
    if (strpos($item['rate'], ',') === false) {
        $item['rate'] = $item['rate'] . '%';
    } else {
        list($low, $high) = explode(',', $item['rate']);
        $item['rate'] = $low . '% ~ ' . $high . '%';
    }
    if (strpos($item['d_loss_rate'], ',') === false) {
        $item['d_loss_rate'] = $item['d_loss_rate'];
    } else {
        $item['d_loss_rate'] = str_replace(',', ' ~ ', $item['d_loss_rate']);
    }

    if ($item['type'] == 'weapon') {
        $item['rate'] = strval(' +' . $item['rate']);
    } else if ($item['type'] == 'armor') {
        $item['rate'] = ' -' . $item['rate'];
    } else if ($item['type'] == 'food') {
        $item['rate'] = strval(' +' . str_replace("%", '', $item['rate']));
    }
} else {
// 武器列表
    $items = array();

    $query = DB::query('SELECT * FROM ' . DB::table('hdx_shop_item') . ' WHERE  available= 1 ORDER BY disp_order LIMIT ' . $_start . ',' . $_perpage);

    while ($w = DB::fetch($query)) {
        $w = escape($w, 'html');
        if (strpos($w['rate'], ',') === false) {
            $w['rate'] = $w['rate'] . '%';
        } else {
            list($low, $high) = explode(',', $w['rate']);
            $w['rate'] = $low . '% ~ ' . $high . '%';
        }
        if (strpos($w['d_loss_rate'], ',') === false) {
            $w['d_loss_rate'] = $w['d_loss_rate'];
        } else {
            $w['d_loss_rate'] = str_replace(',', ' ~ ', $w['d_loss_rate']);
        }

        if ($w['type'] == 'weapon') {
            $w['rate'] = strval(' +' . $w['rate']);
        } else if ($w['type'] == 'armor') {
            $w['rate'] = ' -' . $w['rate'];
        } else if ($w['type'] == 'food') {
            $w['rate'] = strval(' +' . str_replace("%", '', $w['rate']));
        }


        $items[] = $w;
    }


    $count = DB::result_first('SELECT COUNT(*) FROM ' . DB::table('hdx_shop_item') . ' WHERE  available= 1');

    $multipage = multi($count, $_perpage, $_page, $url);
}
?>
