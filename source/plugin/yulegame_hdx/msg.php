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



$url = 'plugin.php?id=yulegame_hdx&op=msg';

// ��Ա�б�


$query = DB::query('SELECT * FROM ' . DB::table('hdx_msg') . ' g 
	LEFT JOIN ' . DB::table('common_member') . ' m ON m.uid=g.from_uid 
	WHERE to_uid =' . $_uid . ' ORDER BY g.id DESC LIMIT ' . $_start . ',' . $_perpage);

$msgs = array();
while ($m = DB::fetch($query)) {
    $m = escape($m, 'html');
    if ($m['from_uid'] == 0) {
        $m['sender'] = lang('plugin/yulegame_hdx', 'system_msg');
    }
    $m['time'] = date('Y-n-d H:i:s', $m['created_at']);
    $msgs[] = $m;
}

$count = DB::result_first('SELECT COUNT(*) FROM ' . DB::table('hdx_msg') . ' 
	WHERE to_uid =' . $_uid);


$multipage = multi($count, $_perpage, $_page, $url);
?>
