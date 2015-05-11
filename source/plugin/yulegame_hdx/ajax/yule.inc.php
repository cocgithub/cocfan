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
 * 5: ���ִ��� 
 * *** ��л��Ա�վ�����֧�ֺͺ�!
 * *** <������Ϸ��> - ��������Ŷ�
 * ================================================================
 */

// ����ʹ�ô��жϱ����ⲿ����
if (!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

if (!submitcheck('yulesubmit')) {
    showError(lang('plugin/yulegame_hdx', 'submit_invalid'));
}

// INIT
$maxSta = intval($_setting['max_sta']);

$yuleId = intval($_G['gp_yuleId']);
$yule = DB::fetch_first('SELECT * FROM ' . DB::table('hdx_yule') . ' WHERE id=' . $yuleId);

if (!$yule) {
    showError(lang('plugin/yulegame_hdx', 'no_such_yule'));
}

if ($subop == 'gift') {
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

// ����
$price = $yule['price'];

if ($_money < $price) {
    // �Ƿ�Ǯ

    showError(lang('plugin/yulegame_hdx', 'not_enough_money_to_buy_yule', array('money_title' => $_moneyTitle, 'price' => $price)));
}


$msgAry[] = lang('plugin/yulegame_hdx', 'money_reduce', array('money_title' => $_moneyTitle, 'amount' => $yule['price']));


$msg = lang('plugin/yulegame_hdx', 'buy_success');

// update array
$update = array();

if ($subop == 'gift') {

    // �Ƿ񳬹�max sta
    if ($_sta == $maxSta) {
        showError(lang('plugin/yulegame_hdx', 'player_sta_full', array('player_username' => $receiver['username'], 'sta_title' => $_staTitle, 'money_title' => $_moneyTitle)));
    }


    $msg = lang('plugin/yulegame_hdx', 'give_success');



    // �Ƿ񳬹�max sta
    if ($receiver['sta'] + $yule['add_sta'] > $maxSta) {
        //$update[] = 'sta =' . $maxSta;


        $msgAry[] = lang('plugin/yulegame_hdx', 'player_sta_over_max', array('receiver_username' => $receiver['username'], 'sta_title' => $_staTitle, 'amount' => $yule['add_sta']));
    } else {
        //$update[] = 'sta = sta +' . $yule['add_sta'];



        $msgAry[] = lang('plugin/yulegame_hdx', 'player_sta_increase', array('receiver_username' => $receiver['username'], 'sta_title' => $_staTitle, 'amount' => $yule['add_sta']));
    }

    updateSta($receiver['uid'], $yule['add_sta']);

    //DB::query('UPDATE ' . DB::table('hdx_player') . ' SET ' . implode(',', $update) . ' WHERE uid=' . $receiver['uid']);
    // update array
    $update = array();
} else {
    // �Ƿ񳬹�max sta
    if ($_sta == $maxSta) {


        showError(lang('plugin/yulegame_hdx', 'sta_full', array('money_title' => $_moneyTitle, 'sta_title' => $_staTitle)));
    }



    // �Ƿ񳬹�max sta


    if ($_sta + $yule['add_sta'] > $maxSta) {
        //$update[] = 'sta =' . $maxSta;
        updateSta($_uid, $yule['add_sta']);

        $msgAry[] = lang('plugin/yulegame_hdx', 'your_sta_over_max', array('sta_title' => $_staTitle, 'amount' => $yule['add_sta']));
    } else {
        //$update[] = 'sta = sta+' . $yule['add_sta'];


        $msgAry[] = lang('plugin/yulegame_hdx', 'your_sta_increase', array('sta_title' => $_staTitle, 'amount' => $yule['add_sta']));
    }
    updateSta($_uid, $yule['add_sta']);
}



// update db
$update[] = $_moneyExtStr . '=' . $_moneyExtStr . '-' . $price;

DB::query('UPDATE ' . DB::table('common_member_count') . ' mc,' . DB::table('hdx_player') . ' p SET ' . implode(',', $update) . ' WHERE mc.uid=p.uid AND mc.uid=' . $_uid);

$msg = $msg . '<br><br>' . implode('<br>', $msgAry);
$url = 'plugin.php?id=yulegame_hdx&op=yule';

// ���
showMsg($msg, true, array(
    'url' => $url
));
?>
