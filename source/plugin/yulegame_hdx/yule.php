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

$url = 'plugin.php?id=yulegame_hdx&op=yule';

if ($subop == 'gift') {
    $yuleId = intval($_G['gp_yuleId']);
    $yule = DB::fetch_first("SELECT * FROM " . DB::table('hdx_yule') . " WHERE available= 1 AND id='{$yuleId}'");

    if (!$yule) {
        showError(lang('plugin/yulegame_hdx', 'no_such_yule'));
    }
} else {
    // �����б�
    $yules = array();

    $query = DB::query('SELECT * FROM ' . DB::table('hdx_yule') . ' WHERE  available= 1 ORDER BY disp_order LIMIT ' . $_start . ',' . $_perpage);

    while ($y = DB::fetch($query)) {
        $y = escape($y, 'html');
        $yules[] = $y;
    }

    $count = DB::result_first('SELECT COUNT(*) FROM ' . DB::table('hdx_yule') . '  WHERE available= 1');

    $multipage = multi($count, $_perpage, $_page, $url);
}
?>
