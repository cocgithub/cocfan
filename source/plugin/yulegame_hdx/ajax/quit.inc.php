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






// �Ƿ�Ǯ
$quitFee = ceil($_player['rob_times'] * $_hdx['quit_rate']);
if ($quitFee > 0) {
    if ($_money < $quitFee) {
        showError(lang('plugin/yulegame_hdx', 'money_not_enough_to_quit', array('money_title' => $_moneyTitle, 'quit_fee' => $quitFee, 'hd' => $_hdxLang['hd'])));
    }

// update db
    $update[] = $_moneyExtStr . '=' . $_moneyExtStr . '-' . $quitFee;

    DB::query('UPDATE ' . DB::table('common_member_count') . ' SET ' . implode(',', $update) . ' WHERE uid=' . $_uid);
}

// ��ٴ���
if ($_hdx['quit_rob_times'] > 0 && $_player['rob_times'] < $_hdx['quit_rob_times']) {
    showError(lang('plugin/yulegame_hdx', 'rob_times_not_enough_to_quit', array('rob' => $_hdxLang['rob'], 'quit_rob_times' => $_hdx['quit_rob_times'], 'hd' => $_hdxLang['hd'])));
}


// �ۻ��������
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
$log['msg'] = '<font color=red>{{' . $_player['username'] . '}}'. lang('plugin/yulegame_hdx','already_quit') . $_hdxLang['hd'] . '��</font>';

DB::insert('hdx_log', $log);


// ɾ�������������
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

// ���
showMsg($msg, true, array(
    'url' => $url
));
?>
