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

$url = 'plugin.php?id=yulegame_hdx&op=mybag';

// �����б�
$items = array();
$query = DB::query('SELECT pi.id player_item_id,pi.durability player_item_durability, si.* FROM ' . DB::table('hdx_player_item') . ' pi,' . DB::table('hdx_shop_item') . ' si WHERE si.available = 1 AND pi.item_id = si.id AND pi.uid = '. intval($_uid) .' LIMIT ' . $_start . ',' . $_perpage);

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
        $w['rate'] = strval(' +'. $w['rate']);
    } else if ($w['type'] == 'armor') {
        $w['rate'] = ' -'. $w['rate'];
    } else if ($w['type'] == 'food') {
         $w['rate'] = strval(' +'. str_replace("%",'',$w['rate']));
     }
    if ($w['player_item_durability'] <=0 ) {
        $w['player_item_durability'] = '<font color=red>0</font>';
    }
    $items[] = $w;
}


$count = DB::result_first('SELECT COUNT(*) FROM ' . DB::table('hdx_player_item') . ' pi,' . DB::table('hdx_shop_item') . ' si WHERE si.available = 1 AND pi.item_id = si.id AND pi.uid = '. intval($_uid));

$multipage = multi($count, $_perpage, $_page, $url);
?>
