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

if (!submitcheck('itemsubmit')) {
    showError(lang('plugin/yulegame_hdx', 'submit_invalid'));
}


// INIT
$playerAction = $_GET['player_action'];

$itemId = intval($_G['gp_itemId']);


$itemAry = array('weapon', 'armor', 'food');

if ($playerAction != 'discard') {
    $item = DB::fetch_first('SELECT type,rate FROM ' . DB::table('hdx_shop_item') . ' si,' . DB::table('hdx_player_item') . ' pi WHERE pi.uid=' . $_uid . ' AND si.available = 1 AND pi.item_id=si.id AND pi.id=' . $itemId);

    if (!$item) {
        showError(lang('plugin/yulegame_hdx', 'no_such_item'));
    }

    if (!in_array($item['type'], $itemAry)) {
        showError(lang('plugin/yulegame_hdx', 'shop_item_has_no_type'));
    }
}

switch ($playerAction) {

    case 'equip':
        if (in_array($item['type'], array('weapon', 'armor'))) {
            // 是否替换现有的武器
            DB::query('UPDATE ' . DB::table('hdx_player') . ' SET ' . $item['type'] . '_id = ' . $itemId . '  WHERE uid=' . $_uid);

            $msg = lang('plugin/yulegame_hdx', 'equip_success');
        } else {
            throw new Exception(lang('plugin/yulegame_hdx', 'item_could_not_equip'));
        }
        break;
    case 'unload':
        if (in_array($item['type'], array('weapon', 'armor'))) {
            DB::query('UPDATE ' . DB::table('hdx_player') . ' SET ' . $item['type'] . '_id = 0 WHERE uid=' . $_uid);
            $msg = lang('plugin/yulegame_hdx', 'unload_success');
        } else {
            throw new Exception(lang('plugin/yulegame_hdx', 'item_could_not_unload'));
        }
        break;
    case 'discard':

        DB::query('DELETE FROM ' . DB::table('hdx_player_item') . ' WHERE uid=' . $_uid . ' AND id = ' . $itemId);
        if ($_weapon['id'] == $itemId) {
            $itemType = 'weapon';
        } else if ($_armor['id'] == $itemId) {
            $itemType = 'armor';
        }
        if ($itemType == 'weapon' || $itemType == 'armor') {
            DB::query('UPDATE ' . DB::table('hdx_player') . ' SET ' . $itemType . '_id = 0  WHERE uid=' . $_uid);
        }

        $msg = lang('plugin/yulegame_hdx', 'discard_success');
        break;
    case 'use':
        if (!in_array($item['type'], array('food'))) {
            throw new Exception(lang('plugin/yulegame_hdx', 'item_could_not_use'));
        }

        $foodAddRate = getRandomNumber($item['rate']);
        updateSta($_uid, $foodAddRate);

        $msg = lang('plugin/yulegame_hdx', 'food_use_success', array('sta' => $_staTitle, 'amount' => $foodAddRate));
        DB::query('DELETE FROM ' . DB::table('hdx_player_item') . ' WHERE uid=' . $_uid . ' AND id = ' . $itemId);
        $url = 'plugin.php?id=yulegame_hdx&op=mybag';

        break;
    default:
        showError('Invalid Action!');
}
$url = 'plugin.php?id=yulegame_hdx&op=mybag';

// 输出
showMsg($msg, true, array(
    'url' => $url
));
?>
