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

// =========================================
// �����ʼ
// =========================================
// including
define('IN_PLUGIN', true);
define('PLUGIN_PATH', DISCUZ_ROOT . 'source' . DIRECTORY_SEPARATOR . 'plugin' . DIRECTORY_SEPARATOR . 'yulegame_hdx');
//
define('ACTIVITY_JOIN', 1);
define('ACTIVITY_QUIT', 2);
define('ACTIVITY_AWAY', 3);

// �����Ϣ
define('PLUGIN_NAME', lang('plugin/yulegame_hdx', 'plugin_name'));
define('PLUGIN_VER', "1.5.0 RC");

// ��̨�������
$_setting = $_G['cache']['plugin']['yulegame_hdx'];

// debug??
if (file_exists(PLUGIN_PATH . '/include/debug.inc.php')) {
    include PLUGIN_PATH . '/include/debug.inc.php';
}

// ��ͬ
// =====================================================
// global function
require PLUGIN_PATH . '/include/function.inc.php';

// extra param for exception
$showMsgParam = array();



if ($_G['uid'] == 1) {

    /*
      include_once 'cert.php';
      print_r($cert);
      print_r("<br>");
      print_r(md5(implode('', $cert)));
      print_r("<br>");
      print_r($sign);

      die; */
}

try {

    // �Ƿ�ر�
    if (!$_setting['available']) {
        if (empty($_setting['closed_msg'])) {
            $_setting['closed_msg'] = lang('plugin/yulegame_hdx', 'plugin_closed');
        }
        throw new Exception($_setting['closed_msg']);
    }




    // ȫ�ֱ���
    // dzx
    $_groupid = intval($_G['groupid']);
    $_uid = intval($_G['uid']);
    $_tablepre = $_G['config']['db'][1]['tablepre'];
    $_charset = $_G['charset'];
    $_extcredits = $_G['setting']['extcredits'];
    $_bbname = $_G['setting']['bbname'];
    $_loginURL = 'member.php?mod=logging&action=login';
    $_timenow = intval($_G['timestamp']);

    // LANG
    //$_hdxLang = lang('plugin/hdx');
    // ��ҳ
    $_perpage = $_setting['perpage'];
    if (empty($_perpage))
        $_perpage = 10;
    $_page = intval(getgpc('page')) ? intval($_G['gp_page']) : 1;
    $_start = ($_page - 1) * $_perpage;


    // ��������
    $_moneyExtNum = intval($_setting['money_ext']);
    $_plugin_width = (empty($_setting['plugin_width']) ? '100%' : $_setting['plugin_width']);

    if (!$_moneyExtNum) {
        showError(lang('plugin/yulegame_hdx', 'money_ext_not_set'));
    }

    if (empty($_setting['init_sta'])) {
        showError(lang('plugin/yulegame_hdx', 'init_sta_not_set'));
    }

    // ��Ǯ����������
    $_moneyExt = $_extcredits[$_moneyExtNum];
    $_moneyExtStr = 'extcredits' . $_moneyExtNum;
    $_moneyTitle = $_moneyExt['title'];


    $_staExtStr = 'sta';
    $_swExtStr = 'sw';

    // sta setting
    if (intval($_setting['sta_ext']) > 0 && intval($_setting['sta_ext']) < 9) {
        $extNum = intval($_setting['sta_ext']);
        $_staExt['title'] = $_staTitle = $_extcredits[$extNum]['title'];
    } else {
        if (empty($_setting['sta_title'])) {
            $_staExt['title'] = $_staTitle = lang('plugin/yulegame_hdx', 'sta');
        } else {
            $_staExt['title'] = $_staTitle = $_setting['sta_title'];
        }
    }

    // sw setting
    if (intval($_setting['sw_ext']) > 0 && intval($_setting['sw_ext']) < 9) {
        $extNum = intval($_setting['sw_ext']);
        $_swExt['title'] = $_swTitle = $_extcredits[$extNum]['title'];
    } else {
        if (empty($_setting['sw_title'])) {
            $_swExt['title'] = $_swTitle = lang('plugin/yulegame_hdx', 'sw');
        } else {
            $_swExt['title'] = $_swTitle = $_setting['sw_title'];
        }
    }

// �ڵ�����
    $_hdx = array();
    $query = DB::query("SELECT * FROM " . DB::table('hdx_setting') . " ");

    while ($setting = DB::fetch($query)) {
        $key = $setting['skey'];
        $_hdx[$key] = $setting['svalue'];
    }

    if (empty($_hdx['level_rate'])) {
        $_hdx['level_rate'] = 10;
        //throw new Exception(lang('plugin/yulegame_hdx', 'level_rate_empty'));
    }



// ���԰�
    $_hdxLang = array(
        'hdx' => (trim($_hdx['lang_hdx']) == '' ? lang('plugin/yulegame_hdx', 'hdx') : $_hdx['lang_hdx']),
        'hd' => (trim($_hdx['lang_hd']) == '' ? lang('plugin/yulegame_hdx', 'hd') : $_hdx['lang_hd']),
        'rob' => (trim($_hdx['lang_rob']) == '' ? lang('plugin/yulegame_hdx', 'rob') : $_hdx['lang_rob']),
        'rob_jail' => (trim($_hdx['lang_rob_jail']) == '' ? lang('plugin/yulegame_hdx', 'rob_jail') : $_hdx['lang_rob_jail']),
    );

    $_hdxLang = escape($_hdxLang, 'html');

// ָ��?op=
    $op = $_GET['op'];
    if (empty($op)) {
        $op = 'home';
    }
    $subop = $_GET['subop'];


// ����ʹ�õ��û���
    $userGroups = unserialize($_setting['user_groups']);



    if (!in_array($_groupid, $userGroups)) {
        showError(lang('plugin/yulegame_hdx', 'your_group_could_not_use_plugin'));
    }



    $securedOp = array();

// �οͱ����½���ܷ��ʵ�ҳ��
    $securedOp[] = 'yule';
    $securedOp[] = 'shop';
    $securedOp[] = 'guard';
    $securedOp[] = 'mybag';
    $securedOp[] = 'rob';
    $securedOp[] = 'quit';

    $securedOp[] = 'setting';
    $securedOp[] = 'msg';
    $securedOp[] = 'ajax';


// �������ڵ����ܽ��еĲ���
    $playerOp = $securedOp;

    $securedOp[] = 'home';
    $securedOp[] = 'away';


// �οͷ���
    switch ($op) {
        case 'ajax' :
            break;
    }
//throw new Exception('to_login', '', array(), array('showmsg' => true, 'login' => 1));
// �Ƿ���Ҫ��¼
    if (in_array($op, $securedOp) && !$_uid) {
        showError(lang('plugin/yulegame_hdx', 'not_login_to_use_plugin'), array(
            'login' => true,
            'url' => $_loginURL
        ));
    }


// ��ȡ�û���Ϣ
// user function
    require PLUGIN_PATH . '/include/user.inc.php';

// ����Ƿ����ڵ�

    if (in_array($op, $playerOp) && $subop !='away') {
        // ����Ƿ����ڵ�
        
        if (!$_player['uid']) {
            showError(lang('plugin/yulegame_hdx', 'not_in_game_to_play', array('hd' => $_hdxLang['hd'], 'hdx' => $_hdxLang['hdx'])));
        }
    }

    // check last active time and see if automatically increase
    if (!empty($_setting['sta_rate']) && $_player) {
        if ($_player['last_active_time'] > 0) {
            $activeTime = $_timenow - $_player['last_active_time'];
            if ($activeTime > 60) {
                // calculate how many minutes actived
                $activeMinutes = intval($activeTime / 60);
            }

            $addStaVal = 0;
            if ($activeMinutes > 0) {
                // calculate how many sta player can add
                $addStaVal = intval($_setting['sta_rate']) * $activeMinutes;
                $_sta = updateSta($_uid, $addStaVal);
                DB::query("UPDATE " . DB::table('hdx_player') . " SET last_active_time = '" . intval($_timenow) . "' WHERE uid=" . $_uid);
            }
        } else {
            DB::query("UPDATE " . DB::table('hdx_player') . " SET last_active_time = '" . intval($_timenow) . "' WHERE uid=" . $_uid);
        }
    }


// �Ƿ�������
    switch ($op) {
        case 'yule':
        case 'shop':
        case 'mybag':
        case 'guard':
        case 'rob':
        case 'quit':
        case 'away':
        case 'ajax':

            if ($subop != 'jail' && $_player['out_jail_time'] > $_timenow && $_player['out_jail_time'] > 0) {
                $allowView = false;
                if ($op == 'mybag' && $_hdx['jail_allow_use_item']) {
                    $allowView = true;
                }

                if ($allowView == false) {

                    showError(lang('plugin/yulegame_hdx', 'still_in_jail', array(
                                'month' => date('n', $_player['out_jail_time']),
                                'day' => date('j', $_player['out_jail_time']),
                                'hour' => date('H', $_player['out_jail_time']),
                                'minute' => date('i', $_player['out_jail_time']))), array('url' => 'plugin.php?id=yulegame_hdx&op=jail'));
                }
            }
    }

    if ($op == 'ajax') {
        require PLUGIN_PATH . '/ajax.php';
        exit();
    } else {
        // user function
        require PLUGIN_PATH . '/include/common.inc.php';

        switch ($op) {
            case 'rank' :
                require PLUGIN_PATH . '/rank.php';
                break;
            case 'msg' :
                require PLUGIN_PATH . '/msg.php';
                break;
            case 'join' :
                require PLUGIN_PATH . '/join.php';
                break;
            case 'rob' :
                require PLUGIN_PATH . '/rob.php';
                break;
            case 'quit' :
                require PLUGIN_PATH . '/quit.php';
                break;
            case 'away' :
                require PLUGIN_PATH . '/away.php';
                break;
            case 'jail' :
                require PLUGIN_PATH . '/jail.php';
                break;
            case 'yule' :
                require PLUGIN_PATH . '/yule.php';
                break;
            case 'shop' :
                require PLUGIN_PATH . '/shop.php';
                break;
            case 'mybag' :
                require PLUGIN_PATH . '/mybag.php';
                break;
            case 'guard' :
                require PLUGIN_PATH . '/guard.php';
                break;
            case 'home' :
                require PLUGIN_PATH . '/home.php';
                break;
            case 'setting' :
                require PLUGIN_PATH . '/setting.php';
                break;
            default:
                showError('Invalid Action!');
        }
    }
} catch (Exception $e) {
    showError($e->getMessage(), $showMsgParam);
}

include template('yulegame_hdx:main');
?>
