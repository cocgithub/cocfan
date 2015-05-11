<?php

/* ===============================================================
 * @�������			�ڵ�����X
 * @�����Ȩ			2007-2011 ������Ϸ.NET www.yulegame.net
 * @�������			Ricky Lee (ricky_yahoo@hotmail.com)
 * ******** ���������ߵ��Ͷ��ɹ�, �������ϰ�Ȩ��Ϣ *********************
 * ******** ��վ�����ڸ������������, �������Ҫ���������QQ 231753
 * *** ����EMAIL: ricky_yahoo@hotmail.com
 * *** ���߷���: http://bbs.yulegame.net ������̳����Ϣ�� ricky_yahoo

 * *** ����Ϊ<������Ϸ��>��Ʒ��������Ʒ���(�뵽��̳�������ð�):
 * 1: �ڵ�����
 * 2: ��Ϸ����
 * 3: �²���
 * 5: ��������
 * *** ��л��Ա�վ�����֧�ֺͺ�!
 * *** <������Ϸ��> - ��������Ŷ�
 * ================================================================
 */

// ����ʹ�ô��жϱ����ⲿ����
if (!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

if (!submitcheck('formsubmit')) {
    showError(lang('plugin/yulegame_hdx', 'submit_invalid'));
}


// INIT
if ($_POST['item_action'] == 'shop_gift') {

    $pickType = $_G['gp_pickType'];

    if ($pickType == '1') {
        $memberName = iconv('utf-8', $_charset, $_G['gp_memberName']);
        $query = 'SELECT * FROM ' . DB::table('common_member') . ' m,' . DB::table('hdx_player') . ' p WHERE p.uid=m.uid AND m.username = \'' . dhtmlspecialchars($memberName) . '\'';
    } else {
        $memberUid = intval($_G['gp_memberUid']);
        $query = 'SELECT * FROM ' . DB::table('common_member') . ' m,' . DB::table('hdx_player') . ' p WHERE p.uid=m.uid AND m.uid = ' . $memberUid;
    }

    $receiver = DB::fetch_first($query);

    if (!$receiver) {
        showError(lang('plugin/yulegame_hdx', 'no_such_player_to_recieve'));
    }

    $player = DB::fetch_first('SELECT * FROM ' . DB::table('hdx_player') . ' WHERE uid=' . $receiver['uid']);

    if (!$player) {

        showError(lang('plugin/yulegame_hdx', 'member_not_in_hd_to_give', array('hd' => $_hdxLang['hd'])));
    }

    if ($receiver['uid'] == $_uid) {

        showError(lang('plugin/yulegame_hdx', 'could_not_give_yourself'));
    }
}

$itemId = intval($_POST['itemId']);
$item = DB::fetch_first('SELECT * FROM ' . DB::table('hdx_shop_item') . ' WHERE id=' . $itemId);

if (!$item) {
    showError(lang('plugin/yulegame_hdx', 'no_such_item'));
}



// ����
$price = $item['price'];

if ($_money < $price) {
    // �Ƿ�Ǯ
    showError(lang('plugin/yulegame_hdx', 'money_not_enough_to_buy_item', array('money_title' => $_moneyTitle, 'price' => $item['price'])));
}

$msgAry[] = lang('plugin/yulegame_hdx', 'money_reduce', array('money_title' => $_moneyTitle, 'amount' => $item['price']));




// update array
$update = array();


// update db
$update[] = $_moneyExtStr . '=' . $_moneyExtStr . '-' . $price;

DB::query('UPDATE ' . DB::table('common_member_count') . ' SET ' . implode(',', $update) . ' WHERE uid=' . $_uid);


if ($_POST['item_action'] == 'shop_gift') {

    DB::query('INSERT INTO ' . DB::table('hdx_player_item') . ' (uid, item_id, durability) VALUES (' . $receiver['uid'] . ',' . $item['id'] . ',' . $item['durability'] . ')');

    // send msg
    $subject = lang('plugin/yulegame_hdx', 'you_receive_gift', array('player_username' => $_player[username], 'hdx' => $_hdxLang['hdx']));
    $message = lang('plugin/yulegame_hdx', 'gift_in_your_bag_now', array('url' => 'plugin.php?id=yulegame_hdx&op=mybag', 'item' => $item['name']));

    // DZ����
    notification_add($receiver['uid'], 'system', 'system_notice', array(
        'subject' => $subject,
        'message' => $message
            ), 1);

  

    $msg = lang('plugin/yulegame_hdx', 'gift_success');
} else {


    DB::query('INSERT INTO ' . DB::table('hdx_player_item') . ' (uid, item_id, durability) VALUES (' . $_uid . ',' . $item['id'] . ',' . $item['durability'] . ')');


    $msg = lang('plugin/yulegame_hdx', 'buy_success');
}
$msg = $msg . '<br><br>' . implode('<br>', $msgAry);
$url = 'plugin.php?id=yulegame_hdx&op=shop';

// ���
showMsg($msg, true, array(
    'url' => $url
));
?>
