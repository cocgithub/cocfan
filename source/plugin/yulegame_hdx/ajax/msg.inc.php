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

if (!submitcheck('msgsubmit')) {
    showError(lang('plugin/yulegame_hdx', 'submit_invalid'));
}


// INIT
$url = 'plugin.php?id=yulegame_hdx&op=msg';

$ids = $_G['gp_ids'];
if (empty($ids)) {
	showError(lang('plugin/yulegame_hdx', 'choose_record_to_delete'));
}

$idAry = array();
foreach($ids as $id) {
	$idAry[] = intval($id);
}

DB::query('DELETE FROM '. DB::table('hdx_msg') .' WHERE to_uid = '. $_uid .' AND id IN ('. implode(',', $idAry) .')');

$msg = lang('plugin/yulegame_hdx', 'done_successfully');


// ���
showMsg($msg, true, array(
	'url' => $url
));

?>
