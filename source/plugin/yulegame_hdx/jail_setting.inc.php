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

// ��̨�������
loadcache('plugin');
$_setting = $_G['cache']['plugin']['yulegame_hdx'];


$pmod = $_G['gp_pmod'];
$formAction = 'plugins&operation=config&do=' . $pluginid . '&identifier=' . $plugin['identifier'] . '&pmod=' . $pmod;

if (!submitcheck('settingsubmit', 1)) {
// �ڵ�����
    $hdx = array();
    $query = DB::query("SELECT * FROM " . DB::table('hdx_setting') . " WHERE skey LIKE 'jail_%'");

    while ($setting = DB::fetch($query)) {
        $key = $setting['skey'];
        $hdx[$key] = $setting['svalue'];
    }


    if (empty($hdx['jail_rate'])) {
        $hdx = array(
            'jail_rate' => 40,
            'jail_time' => 5,
            'jail_allow_view' => 0,
            'jail_allow_post' => 0,
            'jail_escape_sta' => 5,
            'jail_escape_rate' => 40,
            'jail_escape_interval' => 10,
            'jail_rob_sta' => 20,
            'jail_rob_rate' => 20,
            'jail_rob_in_jail_rate' => 20,
            'jail_rob_in_jail_time' => 10,
            'jail_bail_fee' => 500
        );
    }

    //showtips('<li>��</li><li>sss</li>');

    showformheader($formAction);

    showtableheader('');








    showsetting(lang('plugin/yulegame_hdx', 'setting_jail_rate'), 'hdx[jail_rate]', $hdx['jail_rate'], 'text', '', 0, lang('plugin/yulegame_hdx', 'setting_jail_rate_desc'));


    showsetting(lang('plugin/yulegame_hdx', 'setting_jail_time'), 'hdx[jail_time]', $hdx['jail_time'], 'text', '', 0, lang('plugin/yulegame_hdx', 'setting_jail_time_desc'));

    
    


    showsetting(lang('plugin/yulegame_hdx', 'setting_jail_allow_view'), 'hdx[jail_allow_view]', $hdx['jail_allow_view'], 'radio', '', 0, lang('plugin/yulegame_hdx', 'setting_jail_allow_view_desc'));




    $query = DB::query("SELECT * FROM " . DB::table('common_usergroup') . " ORDER BY (creditshigher<>'0' || creditslower<>'0'), creditslower, groupid");


    $allowViewGroupAry = unserialize($hdx['jail_allow_view_group_list']);
    $groupselect = array();
    while ($group = DB::fetch($query)) {

        $groupselect[$group['type']] .= '<option value="' . $group['groupid'] . '"' . (@in_array($group['groupid'], $allowViewGroupAry) ? ' selected' : '') . '>' . $group['grouptitle'] . '</option>';
    }





    $usergroups = '<select name="hdx[jail_allow_view_group_list][]" size="10" multiple="multiple"><option value=""' . (@in_array('', $allowViewGroupAry) ? ' selected' : '') . '>' . cplang('plugins_empty') . '</option>' .
            '<optgroup label="' . $lang['usergroups_member'] . '">' . $groupselect['member'] . '</optgroup>' .
            ($groupselect['special'] ? '<optgroup label="' . $lang['usergroups_special'] . '">' . $groupselect['special'] . '</optgroup>' : '') .
            ($groupselect['specialadmin'] ? '<optgroup label="' . $lang['usergroups_specialadmin'] . '">' . $groupselect['specialadmin'] . '</optgroup>' : '') .
            '<optgroup label="' . $lang['usergroups_system'] . '">' . $groupselect['system'] . '</optgroup></select>';



    showsetting(lang('plugin/yulegame_hdx', 'setting_allow_view_group_list'), '', '', $usergroups, '', 0, lang('plugin/yulegame_hdx', 'setting_allow_view_group_list_desc'));




    $allowPostGroupAry = unserialize($hdx['jail_allow_post_group_list']);
    $groupselect = array();
    $query = DB::query("SELECT * FROM " . DB::table('common_usergroup') . " ORDER BY (creditshigher<>'0' || creditslower<>'0'), creditslower, groupid");

    while ($group = DB::fetch($query)) {

        $groupselect[$group['type']] .= '<option value="' . $group['groupid'] . '"' . (@in_array($group['groupid'], $allowPostGroupAry) ? ' selected' : '') . '>' . $group['grouptitle'] . '</option>';
    }

    $usergroups = '<select name="hdx[jail_allow_post_group_list][]" size="10" multiple="multiple"><option value=""' . (@in_array('', $allowPostGroupAry) ? ' selected' : '') . '>' . cplang('plugins_empty') . '</option>' .
            '<optgroup label="' . $lang['usergroups_member'] . '">' . $groupselect['member'] . '</optgroup>' .
            ($groupselect['special'] ? '<optgroup label="' . $lang['usergroups_special'] . '">' . $groupselect['special'] . '</optgroup>' : '') .
            ($groupselect['specialadmin'] ? '<optgroup label="' . $lang['usergroups_specialadmin'] . '">' . $groupselect['specialadmin'] . '</optgroup>' : '') .
            '<optgroup label="' . $lang['usergroups_system'] . '">' . $groupselect['system'] . '</optgroup></select>';


    showsetting(lang('plugin/yulegame_hdx', 'setting_jail_allow_post'), 'hdx[jail_allow_post]', $hdx['jail_allow_post'], 'radio', '', 0, lang('plugin/yulegame_hdx', 'setting_jail_allow_post_desc'));

    showsetting(lang('plugin/yulegame_hdx', 'setting_allow_post_group_list'), '', '', $usergroups, '', 0, lang('plugin/yulegame_hdx', 'setting_allow_post_group_list_desc'));


    showsetting(lang('plugin/yulegame_hdx', 'setting_jail_escape_sta'), 'hdx[jail_escape_sta]', $hdx['jail_escape_sta'], 'text', '', 0, lang('plugin/yulegame_hdx', 'setting_jail_escape_sta_desc'));

    showsetting(lang('plugin/yulegame_hdx', 'setting_jail_escape_rate'), 'hdx[jail_escape_rate]', $hdx['jail_escape_rate'], 'text', '', 0, lang('plugin/yulegame_hdx', 'setting_jail_escape_rate_desc'));



    showsetting(lang('plugin/yulegame_hdx', 'setting_jail_escape_interval'), 'hdx[jail_escape_interval]', $hdx['jail_escape_interval'], 'text', '', 0, lang('plugin/yulegame_hdx', 'setting_jail_escape_interval_desc'));

    showsetting(lang('plugin/yulegame_hdx', 'setting_jail_rob_sta'), 'hdx[jail_rob_sta]', $hdx['jail_rob_sta'], 'text', '', 0, lang('plugin/yulegame_hdx', 'setting_jail_rob_sta_desc'));


    showsetting(lang('plugin/yulegame_hdx', 'setting_jail_rob_rate'), 'hdx[jail_rob_rate]', $hdx['jail_rob_rate'], 'text', '', 0, lang('plugin/yulegame_hdx', 'setting_jail_rob_rate_desc'));

    
     showsetting(lang('plugin/yulegame_hdx', 'setting_jail_rob_in_jail_rate'), 'hdx[jail_rob_in_jail_rate]', $hdx['jail_rob_in_jail_rate'], 'text', '', 0, lang('plugin/yulegame_hdx', 'setting_jail_rob_in_jail_rate_desc'));


    showsetting(lang('plugin/yulegame_hdx', 'setting_jail_rob_in_jail_time'), 'hdx[jail_rob_in_jail_time]', $hdx['jail_rob_in_jail_time'], 'text', '', 0, lang('plugin/yulegame_hdx', 'setting_jail_rob_in_jail_time_desc'));

    
    
    showsetting(lang('plugin/yulegame_hdx', 'setting_jail_bail_fee'), 'hdx[jail_bail_fee]', $hdx['jail_bail_fee'], 'text', '', 0, lang('plugin/yulegame_hdx', 'setting_jail_bail_fee_desc'));

    
      showsetting(lang('plugin/yulegame_hdx', 'setting_jail_allow_use_item'), 'hdx[jail_allow_use_item]', $hdx['jail_allow_use_item'], 'radio', '', 0, lang('plugin/yulegame_hdx', 'setting_jail_allow_use_item_desc'));


    showsubmit('settingsubmit');
    showtablefooter();
    showformfooter();
} else {

    require DISCUZ_ROOT . './source/plugin/yulegame_hdx/include/function.inc.php';

    $hdx = $_POST['hdx'];

    if ($hdx['jail_allow_view_group_list'][0] == '') {
        $hdx['jail_allow_view_group_list'] = array();
        $hdx['jail_allow_view_group_list'][0] = '';
    }

    $hdx['jail_allow_view_group_list'] = serialize($hdx['jail_allow_view_group_list']);


    if ($hdx['jail_allow_post_group_list'][0] == '') {
        $hdx['jail_allow_post_group_list'] = array();
        $hdx['jail_allow_post_group_list'][0] = '';
    }

    $hdx['jail_allow_post_group_list'] = serialize($hdx['jail_allow_post_group_list']);

    // ����ǰ����
    foreach ($hdx as $key => $val) {
        $key = escape($key);
        $val = escape($val);
        DB::query("INSERT INTO " . DB::table('hdx_setting') . "(skey, svalue) VALUES ('" . $key . "', '" . $val . "') ON DUPLICATE KEY UPDATE svalue = '" . $val . "'");
    }
    cpmsg(lang('plugin/yulegame_hdx', 'done_successfully'), 'action=plugins&operation=config&do=' . $pluginid . '&identifier=yulegame_hdx&pmod=jail_setting', 'succeed');
}
?>
