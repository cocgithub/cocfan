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

$url = 'plugin.php?id=yulegame_hdx&op=setting';

if (submitcheck('settingsubmit')) {
	
	$setting = $_G['gp_setting'];
	foreach ($setting as $key => $val) {
		DB::query("INSERT INTO " . DB::table('hdx_player_setting') . "(uid, skey, svalue) VALUES (" . $_uid . ",'" . $key . "', '" . $val . "') ON DUPLICATE KEY UPDATE svalue = '" . $val . "'");
	}
	
	showMsg(lang('plugin/yulegame_hdx', 'done_successfully'), 1, array('url' => $url));

} else {
	$setting = array();
	// DEFAULT
	$query = DB::query('SELECT * FROM ' . DB::table('hdx_player_setting') . ' WHERE uid=' . $_uid);
	
	while ( $s = DB::fetch($query) ) {
		$key = $s['skey'];
		$setting[$key] = $s['svalue'];
	}
	

}
?>
