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


$_hdx['rob_success_sw'] = str_replace(",", "-", $_hdx['rob_success_sw']);
$_hdx['rob_fail_sw'] = str_replace(",", "-", $_hdx['rob_fail_sw']);
$_hdx['rob_member_sta'] = str_replace(",", "-", $_hdx['rob_member_sta']);




$robSuggestList = trim($_hdx['rob_suggest_list']);
if (! empty($robSuggestList)) {
	$suggestListAry = explode("\n", $robSuggestList);
	$suggestLists = array();
	foreach ($suggestListAry as $ary) {
		$suggestLists[] = intval($ary);
	}
	
	if (count($suggestLists) > 0) {
		$suggestMembers = array();
		
		$query = DB::query('SELECT * FROM '. DB::table('common_member') . ' WHERE uid IN ('. implode(',', $suggestLists) .')'); 
		while ($data = DB::fetch($query)) {
			$suggestMembers[] = $data;
		}
	}
}

?>
