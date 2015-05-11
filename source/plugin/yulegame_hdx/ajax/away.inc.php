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





if (!submitcheck('awaysubmit')) {
    showError(lang('plugin/yulegame_hdx', 'submit_invalid'));
}


if (!$_hdx['allow_away']) {
    throw new Exception(lang('plugin/yulegame_hdx', 'system_not_allow_away',array('hd' => $_hdxLang['hd'])));
}


if ($_player['uid'] > 0) {
    throw new Exception(lang('plugin/yulegame_hdx', 'in_game_could_not_away',array('hd' => $_hdxLang['hd'])));
}   
 

$playerActivity = DB::fetch_first("SELECT * FROM " . DB::table('hdx_player_activity') . " WHERE uid = '" . $_uid . "'");

if ($playerActivity && $playerActivity['type'] == ACTIVITY_AWAY && $playerActivity['expired_time'] > $_timenow) {
    
    throw new Exception(lang('plugin/yulegame_hdx', 'you_alreay_away', array('hd' => $_hdxLang['hd'],
                'year' => date('Y', $playerActivity['expired_time']),
        'month' => date('n', $playerActivity['expired_time']),
        'day' => date('j', $playerActivity['expired_time']),
        'hour' => date('H', $playerActivity['expired_time']),
        'minute' => date('i', $playerActivity['expired_time']))
                    
            )
    );
}


$days = intval($_POST['days']);

if ($days <=0) {
    throw new Exception(lang('plugin/yulegame_hdx', 'away_days_invalid'));
}


if (intval($_hdx['away_max_days']) > 0 && $days > intval($_hdx['away_max_days'])) {
    throw new Exception(lang('plugin/yulegame_hdx', 'over_system_max_days',array('amount' => intval($_hdx['away_max_days']),'hd' => $_hdxLang['hd'])));
}

$result = false;

// INIT
$url = 'plugin.php?id=yulegame_hdx';


// SETTING


// �Ƿ�Ǯ
$awayFee = intval($_hdx['away_fee']);
if ($awayFee > 0) {
    $userMoney = DB::result_first("SELECT ". $_moneyExtStr ." FROM ". DB::table('common_member_count') . " WHERE uid='". $_uid ."'");
    
    if ($userMoney < $awayFee) {
        showError(lang('plugin/yulegame_hdx', 'money_not_enough_to_away', array('money_title' => $_moneyTitle, 'amount' => $awayFee, 'hd' => $_hdxLang['hd'])));
    }

// update db
    $update[] = $_moneyExtStr . '=' . $_moneyExtStr . '-' . $awayFee;

    DB::query('UPDATE ' . DB::table('common_member_count') . ' SET ' . implode(',', $update) . ' WHERE uid=' . $_uid);
}


DB::query("DELETE FROM ". DB::table('hdx_player_activity') ." WHERE uid = '". $_uid ."'");

$expiredTime = $_timenow + ($days * 3600 * 24);

$data = array(
    'uid' => $_uid,
    'type' => ACTIVITY_AWAY,
    'expired_time' => $expiredTime
);

DB::insert('hdx_player_activity', $data);



// log
$log['who_uid'] = $_uid;
$log['to_uid'] = 0;
$log['created_at'] = $_timenow;
$log['msg'] = '<font color=red>{{' . $_player['username'] . '}}'. lang('plugin/yulegame_hdx','already_away') . $_hdxLang['hd'] . '��</font>';

DB::insert('hdx_log', $log);


$msg = lang('plugin/yulegame_hdx', 'away_successfully', array('hd' => $_hdxLang['hd']));

// ���
showMsg($msg, true, array(
    'url' => $url
));
?>
