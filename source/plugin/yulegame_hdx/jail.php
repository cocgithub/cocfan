<?php
/*===============================================================
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
 *================================================================
*/

// ����ʹ�ô��жϱ����ⲿ����
if (! defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$url = 'plugin.php?id=yulegame_hdx&op=jail';

// �����б�
$query = DB::query('SELECT * FROM '. DB::table('hdx_player') .' p 
	LEFT JOIN '. DB::table('common_member') .' m ON m.uid=p.uid 
	WHERE out_jail_time > '. $_timenow .' AND available = 1 LIMIT '. $_start .','. $_perpage);

$crims = array();
while($data = DB::fetch($query)) {
    $data = escape($data, 'html');
	$crims[] = $data;
}

$count = DB::result_first('SELECT COUNT(*) FROM '. DB::table('hdx_player') .' 
	WHERE out_jail_time > '. $_timenow .' AND available = 1');

if ($_player['out_jail_time'] > $_timenow && $_player['out_jail_time'] > 0) {
	$outJailMonth = date('n', $_player['out_jail_time']);
    $outJailDay = date('j', $_player['out_jail_time']);
    $outJailHour = date('H', $_player['out_jail_time']);
    $outJailMinute = date('i', $_player['out_jail_time']);
    
}

$multipage = multi($count, $_perpage, $_page, $url);


?>
