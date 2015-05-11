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
if (!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
    exit('Access Denied');
}
//loadcache('plugin');


if (!submitcheck('settingsubmit', 1)) {
// �ڵ�����
    $hdx = array();
    $query = DB::query("SELECT * FROM " . DB::table('hdx_setting') . " ");

    while ($setting = DB::fetch($query)) {
        $key = $setting['skey'];
        $hdx[$key] = $setting['svalue'];
    }

    //showtips('<li>Ϊ������ֵ���õ�����ԣ�����Ը��ݲ����Ŀ�ѡ��ʽ�����á����˵���������¿�ѡ��ʽ(�����������ţ�A,B����Ϊ������)��</li><li>[A] - ��ʾ������һ��������; </li><li>[A,B] - ��ʾ������һ����С������������м��ð�Ƕ��Ÿ�����ϵͳ�������ȡ֮�������</li><li>[A%] - ��ʾ��������һ���ٷ�����</li><li>[A%,B%] - ��ʾ����������Сֵ�����ֵ�İٷ������м��ð�Ƕ��Ÿ�����</li>');
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
