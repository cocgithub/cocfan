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
// INIT
$result = 0;
if (empty($_GET['from_op'])) {
    $fromOp = 'home';
} else {
    $fromOp = escape($_GET['from_op'], 'url');
}
if ($_REQUEST['redirect'] == 1) {
    $url = 'plugin.php?id=yulegame_hdx&op=' . $fromOp;
}


if (!submitcheck('robsubmit', 1)) {
    showError(lang('plugin/yulegame_hdx', 'submit_invalid'));
}

//
$inThread = intval($_G['gp_inThread']); // �Ƿ�������������
// update array
$robberMemberUpdate = array();
$victimMemberUpdate = array();
$victimPlayerUpdate = array();
$robberPlayerUpdate = array();

// �����ٴ�������
$robDayMax = $_hdx['rob_day_max'];

// �Ƿ���������̳��Ա
$robAnyone = $_hdx['rob_anyone'];

// ÿ��ÿ�챻��ٴ�������
$dailyRobbedTimes = intval($_hdx['rob_daily_robbed_times']);

// SETTING ���δ��ʱ����
$robInterval = intval($_hdx['rob_interval']);
if ($robInterval > 0) {
    if ($_player['last_rob_time'] > 0 && $_timenow - $_player['last_rob_time'] < $robInterval) {


        showError(lang('plugin/yulegame_hdx', 'rob_interval', array('rob' => $_hdxLang['rob'], 'rob_interval' => $robInterval, 'waiting_time' => ($robInterval - ($_timenow - $_player['last_rob_time'])))));
    }
}

// �����ٴ�������
if ($robDayMax != '') {

    if (intval($robDayMax) > 0) {
        if ($_player['last_rob_time'] > 0) {
            $lastRobDay = date('z', $_player['last_rob_time']);
            $day = date('z', $_timenow);

            if ($lastRobDay == $day) {
                // ����
                if ($_player['rob_day_count'] + 1 > $robDayMax) {

                    showError(lang('plugin/yulegame_hdx', 'daily_rob_limit', array('rob' => $_hdxLang['rob'], 'rob_day_max' => $robDayMax)));
                }

                $robberPlayerUpdate[] = 'rob_day_count = rob_day_count + 1';
            } else {
                $robberPlayerUpdate[] = 'rob_day_count = 1';
            }
        } else {
            $robberPlayerUpdate[] = 'rob_day_count = 1';
        }
    } elseif (intval($robDayMax) == 0) {

        showError(lang('plugin/yulegame_hdx', 'rob_function_disabled', array('rob' => $_hdxLang['rob'])));
    }
}

// �����Ҫ����
$robMemberSta = $_hdx['rob_member_sta'];
$robPlayerSta = $_hdx['rob_player_sta'];

$pickType = $_G['gp_pickType'];

if ($pickType == '1') {
    $memberName = dhtmlspecialchars(iconv('utf-8', $_charset, $_G['gp_memberName']));
    //die($memberName);
    $sql = 'SELECT * FROM ' . DB::table('common_member') . ' m LEFT JOIN ' . DB::table('common_member_count') . ' mc ON m.uid=mc.uid 
		WHERE m.username = \'' . $memberName . '\'';
} else {
    if ($pickType == '2') {
        $memberUid = intval($_G['gp_memberUid']);
    } else {
        $memberUid = intval($_G['gp_suggestMemberUid']);
    }

    $sql = 'SELECT m.groupid,m.username,m.uid,mc.* FROM ' . DB::table('common_member') . ' m LEFT JOIN ' . DB::table('common_member_count') . ' mc ON m.uid=mc.uid 
		WHERE m.uid = ' . $memberUid;
}

$victimMember = DB::fetch_first($sql);



if (!$victimMember) {

    showError(lang('plugin/yulegame_hdx', 'no_such_member'));
}

// v1.1.1����
$victim = DB::fetch_first('SELECT * FROM ' . DB::table('hdx_victim') . ' WHERE uid=' . $victimMember['uid']);

// ÿ��ÿ�챻��ٴ�������
if ($dailyRobbedTimes > 0) {

    if ($victim['last_robbed_time'] > 0) {
        $lastRobbedDay = date('z', $victim['last_robbed_time']);
        $day = date('z', $_timenow);

        if ($lastRobbedDay == $day) {
            // ����
            if ($victim['robbed_day_count'] + 1 > $dailyRobbedTimes) {

                showError(lang('plugin/yulegame_hdx', 'could_not_rob_today', array('rob' => $_hdxLang['rob'], 'daily_robbed_times' => $dailyRobbedTimes)));
            }

            $victimUpdate[] = 'robbed_day_count = robbed_day_count + 1';
        } else {
            $victimUpdate[] = 'robbed_day_count = 1';
        }
    } else {
        $victimUpdate[] = 'robbed_day_count = 1';
    }
}

$victimUpdate[] = 'last_robbed_time = ' . $_timenow;
$victimUpdate[] = 'robbed_times = robbed_times + 1';



// ����
//$robMinSw = $_hdx['rob_min_sw'];
// �Ƿ����Լ�
if ($victimMember['uid'] == $_uid) {

    showError(lang('plugin/yulegame_hdx', 'rob_yourself', array('rob' => $_hdxLang['rob'])));
}

// �Ƿ��㹻����
/*
  if ($robMinSw != '' && $_sw < intval($robMinSw)) {

  showError(lang('plugin/yulegame_hdx', 'sw_not_enough_to_rob', array('rob' => $_hdxLang['rob'], 'sw_title' => $_swTitle, 'rob_min_sw' => $robMinSw)));
  } */





// �ܱ����û���
$protectedGroupList = $_hdx['rob_protected_group_list'];
$protectedGroupAry = unserialize($protectedGroupList);

if (in_array($victimMember['groupid'], $protectedGroupAry)) {
    showError(lang('plugin/yulegame_hdx', 'could_not_rob_protected_user_in_group', array('rob' => $_hdxLang['rob'])));
}

// �ܱ�����Ⱥ
$protectedUserList = trim($_hdx['rob_protected_user_list']);
if (!empty($protectedUserList)) {
    $protectedUserAry = explode("\n", $protectedUserList);
    if (in_array($victimMember['uid'], $protectedUserAry)) {
        showError(lang('plugin/yulegame_hdx', 'could_not_rob_protected_user', array('rob' => $_hdxLang['rob'])));
    }
}


// away hd
$victimActivity = DB::fetch_first("SELECT * FROM " . DB::table('hdx_player_activity') . " WHERE uid = '" . $victimMember['uid'] . "'");

if ($victimActivity && $victimActivity['type'] == ACTIVITY_AWAY && $victimActivity['expired_time'] > $_timenow) {

    throw new Exception(lang('plugin/yulegame_hdx', 'could_not_rob_away_member', array('rob' => $_hdxLang['rob'], 'hd' => $_hdxLang['hd'],
            'year' => date('Y', $playerActivity['expired_time']),
            'month' => date('n', $playerActivity['expired_time']),
            'day' => date('j', $playerActivity['expired_time']),
            'hour' => date('H', $playerActivity['expired_time']),
            'minute' => date('i', $playerActivity['expired_time']))
        )
    );
}


// ���ڶ���Ǯ���ܱ����
$robMinMoneyToProtect = $_hdx['rob_min_money_to_protect'];
if ($robMinMoneyToProtect != '' && $victimMember[$_moneyExtStr] < intval($robMinMoneyToProtect)) {

    showError(lang('plugin/yulegame_hdx', 'money_too_less_to_rob', array('rob' => $_hdxLang['rob'], 'money_title' => $_moneyTitle)));
}

// ֻ�����ټ���ڵ�����
// �ж��Ƿ����ڵ�
$victimPlayer = DB::fetch_first('SELECT * FROM ' . DB::table('hdx_player') . ' WHERE uid=' . $victimMember['uid']);

if ($victimPlayer) {
    // ��ټ���ڵ���������������
    if ($robPlayerSta != '' && $robPlayerSta != '0') {
        $robPlayerSta = getRandomNumber($robPlayerSta);
        if ($robPlayerSta > 0 && $_sta < $robPlayerSta) {

            showError(lang('plugin/yulegame_hdx', 'sta_not_enough', array('rob' => $_hdxLang['rob'], 'hdx' => $_hdxLang['hdx'], 'sta_title' => $_staTitle, 'rob_player_sta' => $robPlayerSta)));
        }
        $requiredSta = $robPlayerSta;
    } else {
        $requiredSta = 0;
    }
} else {

    // �����̳��Ա
    if (!$_hdx['rob_anyone']) {

        $robNotLoginMemberTime = floatval($_hdx['rob_not_login_member_time']) * 3600;
        $lastVisit = DB::result_first('SELECT lastvisit FROM ' . DB::table('common_member_status') . ' WHERE uid=' . $victimMember['uid']);

        if ($lastVisit > 0 && ($_timenow - $lastVisit) > $robNotLoginMemberTime && $robNotLoginMemberTime > 0) {
            // δ��¼ʱ���Ƿ񳬹��趨��ʱ�䣬���������
        } else {
            // ����������̳��Ա


            showError(lang('plugin/yulegame_hdx', 'could_not_robbed_without_in', array('hdx' => $_hdxLang['hdx'], 'rob' => $_hdxLang['rob'])));
        }
    }


    // �����̳��Ա����������
    if ($robMemberSta != '' && $robMemberSta != '0') {
        $robMemberSta = getRandomNumber($robMemberSta);
        if ($robMemberSta > 0 && $_sta < $robMemberSta) {

            showError(lang('plugin/yulegame_hdx', 'sta_not_enough_to_rob_member', array('sta_title' => $_staTitle, 'rob' => $_hdxLang['rob'], 'hdx' => $_hdxLang['hdx'], 'rob_member_sta' => $robMemberSta)));
        }
        $requiredSta = $robMemberSta;
    } else {
        $requiredSta = 0;
    }
}



// �Ƿ����������ε���
if ($victimPlayer && $victimPlayer['out_jail_time'] > $_timenow && $victimPlayer['out_jail_time'] > 0 && !$_hdx['rob_allow_rob_prisoner']) {
    showError(lang('plugin/yulegame_hdx', 'could_not_rob_prisoner', array('rob' => $_hdxLang['rob'])));
}


// ��� victim ����
DB::query("INSERT INTO " . DB::table('hdx_victim') . " 
	(uid, last_robbed_time, robbed_times, robbed_day_count) 
	VALUES 
	(" . $victimMember['uid'] . "," . $_timenow . ", 1,1)  ON DUPLICATE KEY 
	UPDATE " . implode(',', $victimUpdate));

// ��ٳɹ���
$robRate = intval($_hdx['rob_rate']);
$robSuccessSw = $_hdx['rob_success_sw'];
$robFailSw = $_hdx['rob_fail_sw'];

// �������ӳɹ���
if ($_weapon['id'] > 0 && $_weapon['durability'] > 0) {
    $weaponAddRate = getRandomNumber($_weapon['rate']);
    $robRate = $robRate + $weaponAddRate;


    // ������
    $weaponLossRate = getRandomNumber($_weapon['d_loss_rate']);

    if ($_weapon['durability'] - $weaponLossRate <= 0) {
        $weaponDurability = 0;
    } else {
        $weaponDurability = $_weapon['durability'] - $weaponLossRate;
    }
    $msgAry[] = lang('plugin/yulegame_hdx', 'weapon_add_rate', array('weapon' => $_weapon['name'], 'rate' => $weaponAddRate));
    DB::query('UPDATE ' . DB::table('hdx_player_item') . ' SET durability=' . intval($weaponDurability) . ' WHERE uid=' . $_uid . ' AND id=' . intval($_weapon['id']));
}


// �Է��Ƿ��з���
if ($victimPlayer && $victimPlayer['armor_id'] > 0) {
    $armor = DB::fetch_first('SELECT d_loss_rate,name,rate,pi.durability FROM ' . DB::table('hdx_shop_item') . ' si,' . DB::table('hdx_player_item') . ' pi WHERE pi.uid=' . intval($victimPlayer['uid']) . ' AND si.available = 1 AND pi.item_id=si.id AND pi.id=' . intval($victimPlayer['armor_id']));

    if ($armor && $armor['durability'] > 0) {
        // ���߼��ٳɹ���
        $armorDeductRate = getRandomNumber($armor['rate']);
        $robRate = $robRate - $armorDeductRate;


        // ������
        $armorLossRate = getRandomNumber($armor['d_loss_rate']);

        if ($armor['durability'] - $armorLossRate <= 0) {
            $armorDurability = 0;
        } else {
            $armorDurability = $armor['durability'] - $armorLossRate;
        }

        $msgAry[] = lang('plugin/yulegame_hdx', 'armor_deduct_rate', array('victim' => $victimMember['username'], 'armor' => $armor['name'], 'rate' => $armorDeductRate));

        DB::query('UPDATE ' . DB::table('hdx_player_item') . ' SET durability=' . intval($armorDurability) . ' WHERE uid=' . $victimPlayer['uid'] . ' AND id=' . intval($victimPlayer['armor_id']));
    }
}

// guard

if ($victimPlayer) {
    $victimGuard = DB::fetch_first("SELECT g.rate,m.username guard_name FROM " . DB::table('hdx_guard') . " g, " . DB::table('common_member') . " m  WHERE m.uid=g.uid AND g.expired_time > " . $_timenow . " AND g.employer_uid='" . $victimPlayer['uid'] . "'");

    if ($victimGuard) {
        $victimGuardRate = getRandomNumber($victimGuard['rate']);
        $msgAry[] = lang('plugin/yulegame_hdx', 'guard_deduct_rate', array('victim' => $victimMember['username'], 'guard' => $victimGuard['guard_name'], 'rate' => $victimGuardRate));
        $robRate = $robRate - $victimGuardRate;
    }

    $guard = DB::fetch_first("SELECT uid FROM " . DB::table('hdx_guard') . " g WHERE g.expired_time > " . $_timenow . " AND g.employer_uid > 0 AND g.uid='" . $victimPlayer['uid'] . "'");

    if ($guard) {
        $victimToBeGuardRate = getRandomNumber($_hdx['guard_to_be_robbed_rate']);
        //$msgAry[] = lang('plugin/yulegame_hdx', 'guard_deduct_rate', array('victim' => $victimMember['username'], 'guard' => $victimGuard['guard_name'], 'rate' => $victimGuardRate));
        $robRate = $robRate + $victimToBeGuardRate;
    }
}



// add exp
$addExp = getRandomNumber($_hdx['rob_exp']);

if (rand(0, 100) <= $robRate) {
    $result = 1;

    // ��öԷ���Ǯ
    $victimMoney = $victimMember[$_moneyExtStr];

    // �Ѽ���ڵ���ȡ�úڵ���ٽ�Ǯ��
    if ($victimPlayer) {
        $robMemberMoneyRate = $_hdx['rob_player_money_rate'];
    } else {
        $robMemberMoneyRate = $_hdx['rob_member_money_rate'];
    }

    $robAmount = getRandomNumber($robMemberMoneyRate, $victimMoney);



    // add exp
    $addSuccessExp = getRandomNumber($_hdx['rob_success_exp']);
    if ($addSuccessExp > 0) {
        // update db
        $addExp = $addExp + $addSuccessExp;
    }




    // ûǮ
    if ($robAmount == 0) {

        $msg = lang('plugin/yulegame_hdx', 'rob_success_without_money', array('rob' => $_hdxLang['rob']));

        $log['result'] = lang('plugin/yulegame_hdx', 'rob_success_without_money_log');
    } else {

        $msg = lang('plugin/yulegame_hdx', 'rob_success_with_money', array('rob' => $_hdxLang['rob'], 'rob_amount' => $robAmount, 'money_title' => $_moneyTitle));

        $log['result'] = lang('plugin/yulegame_hdx', 'rob_success_with_money_log', array('rob_amount' => $robAmount, 'money_title' => $_moneyTitle));


        // db
        $robberMemberUpdate[] = $_moneyExtStr . '=' . $_moneyExtStr . '+' . $robAmount;

        // victim ��ʧ��Ǯ
        $victimMemberUpdate[] = $_moneyExtStr . '=' . $_moneyExtStr . '-' . $robAmount;


        //$victimUpdate[] = 'robbed_money_amount = robbed_money_amount + '. $robAmount;

        // update victim ����
        DB::query("UPDATE " . DB::table('hdx_victim') . " SET robbed_money_amount = robbed_money_amount + '" . $robAmount . "' WHERE uid='" . $victimMember['uid'] . "'");
    }

    // ��������
    if ($robSuccessSw == '' || $robSuccessSw == '0') {
        $robSuccessSw = 0;
    } else {
        $robSuccessSw = getRandomNumber($robSuccessSw);
    }

    if ($robSuccessSw > 0) {

        $msgAry[] = lang('plugin/yulegame_hdx', 'sw_increase', array('rob_success_sw' => $robSuccessSw, 'sw_title' => $_swTitle));
        $log['ext']['sw'] = $_swTitle . "+" . $robSuccessSw;

        // db
        updateSw($_uid, $robSuccessSw);
        //$robberMemberUpdate[] = 'sw = sw +' . $robSuccessSw;
    }
    // update db
    $robberPlayerUpdate[] = 'rob_money_amount = rob_money_amount +' . $robAmount;
    $robberPlayerUpdate[] = 'rob_success_times = rob_success_times + 1';
} else {

    // ���μ���
    $jailRate = intval($_hdx['jail_rate']);

    // ���ʧ�ܼ��ٽ�Ǯ
    if ($_hdx['rob_fail_lost_money_rate'] != '0' && $_hdx['rob_fail_lost_money_rate'] != '') {
        $lostMoney = round(getRandomNumber($_hdx['rob_fail_lost_money_rate'], $_money));


        $msgAry[] = lang('plugin/yulegame_hdx', 'money_reduce', array('amount' => $lostMoney, 'money_title' => $_moneyTitle));

        // �˻ظ��ܺ���
        if (intval($_hdx['rob_fail_lost_money_rate']) > 0) {
            $backMoneyAmount = intval($lostMoney * (intval($_hdx['rob_fail_back_money_rate']) / 100));
        }

        if ($backMoneyAmount > 0) {
            $victimMemberUpdate[] = $_moneyExtStr . '=' . $_moneyExtStr . '+\'' . $backMoneyAmount . '\'';
            $msgAry[] = lang('plugin/yulegame_hdx', 'money_back_to_victim', array('amount' => $backMoneyAmount, 'money_title' => $_moneyTitle));
        }

        // update db
        $robberMemberUpdate[] = $_moneyExtStr . '=' . $_moneyExtStr . '-' . $lostMoney;
        $log['ext']['money'] = "{$_moneyTitle}-{$lostMoney}";
    }

    // ��������
    if ($robFailSw == '' || $robFailSw == '0') {
        $robFailSw = 0;
    } else {
        $robFailSw = getRandomNumber($robFailSw);
    }
    if ($robFailSw > 0) {

        $msgAry[] = lang('plugin/yulegame_hdx', 'sw_reduce', array('rob_fail_sw' => $robFailSw, 'sw_title' => $_swTitle));
        // update db
        updateSw($_uid, (0 - abs($robFailSw)));
        //$robberMemberUpdate[] = 'sw=sw-' . $robFailSw;

        $log['ext']['sw'] = $_swTitle . "-" . $robFailSw;
    }

    // �Ƿ�����	
    if (rand(0, 100) <= $jailRate) {

        $outJailTime = $_timenow + intval($_hdx['jail_time']) * 60;

        // ����
        $robberPlayerUpdate[] = 'jail_times = jail_times + 1, in_jail_time =' . $_timenow . ', out_jail_time =' . $outJailTime;

        $url = 'plugin.php?id=yulegame_hdx&op=jail';
        $redirect = 1;


        $msg = lang('plugin/yulegame_hdx', 'send_to_jail', array('month' => date('n', $outJailTime), 'day' => date('j', $outJailTime), 'hour' => date('H', $outJailTime), 'minute' => date('i', $outJailTime)));

        // log

        $log['result'] = lang('plugin/yulegame_hdx', 'send_to_jail_log');
    } else {
        // ������

        $msg = lang('plugin/yulegame_hdx', 'escape', array('rob' => $_hdxLang['rob']));

        // log

        $log['result'] = lang('plugin/yulegame_hdx', 'escape_log');
    }
}

if ($addExp > 0) {
    $robberMemberUpdate[] = "exp=exp+'" . intval($addExp) . "'";
    $msgAry[] = lang('plugin/yulegame_hdx', 'add_exp', array('amount' => $addExp));
    if (chkUpgrade($_exp, $_next_level_required_exp, $addExp)) {
        $robberMemberUpdate[] = "level=level+'1'";
        $nextLevelReqExp = nextLevelExp(($_level + 1), $_hdx['level_rate']);
        $playerTitle = getPlayerTitle($_level + 1);
        if ($playerTitle != $_title) {
            $robberMemberUpdate[] = "title='" . escape($playerTitle) . "'";
        }
        $msgAry[] = lang('plugin/yulegame_hdx', 'upgraded', array('amount' => $nextLevelReqExp));
    }
}


// ��������
if ($requiredSta > 0) {
    $msgAry[] = lang('plugin/yulegame_hdx', 'sta_reduce', array('required_sta' => $requiredSta, 'sta_title' => $_staTitle));
    // update db
    //$robberMemberUpdate[] = 'sta=sta-' . $requiredSta;
    updateSta($_uid, (0 - abs($requiredSta)));
}








$msg = $msg . '<br><br>' . ($msgAry ? implode('<br>', $msgAry) : '');

// robber member
if ($robberMemberUpdate) {

    DB::query('UPDATE ' . DB::table('common_member_count') . ' mc,' . DB::table('hdx_player') . ' p SET ' . implode(',', $robberMemberUpdate) . ' WHERE mc.uid=p.uid AND mc.uid=' . $_uid);
}

// robber player
$robberPlayerUpdate[] = 'rob_times = rob_times + 1, last_rob_time = ' . $_timenow;
if ($robberPlayerUpdate) {
    DB::query('UPDATE ' . DB::table('hdx_player') . ' SET ' . implode(",", $robberPlayerUpdate) . ' WHERE uid=' . $_uid);
}

// victim member
if ($victimMemberUpdate) {
    DB::query('UPDATE ' . DB::table('common_member_count') . ' SET ' . implode(",", $victimMemberUpdate) . ' WHERE uid=' . $victimMember['uid']);
}

// robber player
if ($victimPlayerUpdate && $victimPlayer) {
    DB::query('UPDATE ' . DB::table('hdx_player') . ' SET ' . implode(",", $victimPlayerUpdate) . ' WHERE uid=' . $victimPlayer['uid']);
}

// log
$log['who_uid'] = $_uid;
$log['to_uid'] = $victimMember['uid'];
$log['created_at'] = $_timenow;

if (count($log['ext']) > 0) {
    $logExt = implode("��", $log['ext']);
}

$log['msg'] = "{{" . $_player['username'] . "}}" . dhtmlspecialchars($_hdxLang['rob']) . "{{" . $victimMember['username'] . "}}" . $log['result'] . $logExt;

$data = array(
    'who_uid' => $_uid,
    'to_uid' => $victimMember['uid'],
    'created_at' => $_timenow,
    'msg' => $log['msg']
);

DB::insert('hdx_log', $data);

// msg
$data = array(
    'from_uid' => 0,
    'to_uid' => $victimMember['uid'],
    'created_at' => $_timenow,
    'content' => msgFilter($log['msg'], $victimMember['username'], lang('plugin/yulegame_hdx', 'i'))
);
DB::insert('hdx_msg', $data);

// ��������
$robbedAlert = DB::result_first("SELECT svalue FROM " . DB::table('hdx_player_setting') . " WHERE uid=" . $victimMember['uid'] . " AND skey='robbed_alert'");

if (intval($robbedAlert) == 1 || !$victimPlayer) {



    $subject = lang('plugin/yulegame_hdx', 'you_robbed', array('player_username' => $_player[username], 'hdx' => $_hdxLang['hdx'], 'rob' => $_hdxLang['rob']));
    $message = msgFilter($log['msg'], $victimMember['username'], lang('plugin/yulegame_hdx', 'i'));

    // DZ����
    notification_add($victimMember['uid'], 'system', 'system_notice', array(
        'subject' => $subject,
        'message' => $message
        ), 1);
}



// ���
showMsg($msg, $result, array(
    'url' => $url
));
?>
