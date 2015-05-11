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

$url = 'plugin.php?id=yulegame_hdx&op=shop';

if ($subop == 'gift') {
    $itemId = intval($_GET['item_id']);
    $item = DB::fetch_first("SELECT * FROM " . DB::table('hdx_shop_item') . " WHERE available= 1 AND id='{$itemId}'");

    if (!$item) {
        showError(lang('plugin/yulegame_hdx', 'no_such_item'));
    }

    $item = escape($item, 'html');
    if (strpos($item['rate'], ',') === false) {
        $item['rate'] = $item['rate'] . '%';
    } else {
        list($low, $high) = explode(',', $item['rate']);
        $item['rate'] = $low . '% ~ ' . $high . '%';
    }
    if (strpos($item['d_loss_rate'], ',') === false) {
        $item['d_loss_rate'] = $item['d_loss_rate'];
    } else {
        $item['d_loss_rate'] = str_replace(',', ' ~ ', $item['d_loss_rate']);
    }

    if ($item['type'] == 'weapon') {
        $item['rate'] = strval(' +' . $item['rate']);
    } else if ($item['type'] == 'armor') {
        $item['rate'] = ' -' . $item['rate'];
    } else if ($item['type'] == 'food') {
        $item['rate'] = strval(' +' . str_replace("%", '', $item['rate']));
    }
} else {
// �����б�
    $items = array();

    $query = DB::query('SELECT * FROM ' . DB::table('hdx_shop_item') . ' WHERE  available= 1 ORDER BY disp_order LIMIT ' . $_start . ',' . $_perpage);

    while ($w = DB::fetch($query)) {
        $w = escape($w, 'html');
        if (strpos($w['rate'], ',') === false) {
            $w['rate'] = $w['rate'] . '%';
        } else {
            list($low, $high) = explode(',', $w['rate']);
            $w['rate'] = $low . '% ~ ' . $high . '%';
        }
        if (strpos($w['d_loss_rate'], ',') === false) {
            $w['d_loss_rate'] = $w['d_loss_rate'];
        } else {
            $w['d_loss_rate'] = str_replace(',', ' ~ ', $w['d_loss_rate']);
        }

        if ($w['type'] == 'weapon') {
            $w['rate'] = strval(' +' . $w['rate']);
        } else if ($w['type'] == 'armor') {
            $w['rate'] = ' -' . $w['rate'];
        } else if ($w['type'] == 'food') {
            $w['rate'] = strval(' +' . str_replace("%", '', $w['rate']));
        }


        $items[] = $w;
    }


    $count = DB::result_first('SELECT COUNT(*) FROM ' . DB::table('hdx_shop_item') . ' WHERE  available= 1');

    $multipage = multi($count, $_perpage, $_page, $url);
}
?>
