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
