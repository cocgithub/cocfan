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
if (! defined('IN_DISCUZ') || ! defined('IN_ADMINCP')) {
	exit('Access Denied');
}

//loadcache('plugin');


if (! submitcheck('settingsubmit', 1)) {
	// �ڵ�����
	$hdx = array ();
	$query = DB::query("SELECT * FROM " . DB::table('hdx_setting') . " ");

	while ($setting = DB::fetch($query)) {
		$key = $setting['skey'];
		$hdx[$key] = $setting['svalue'];
	}

	
	showtips(lang('plugin/yulegame_hdx', 'setting_lang_tips'));
	

	showformheader('plugins&operation=config&do=' . $pluginid . '&identifier=yulegame_hdx&pmod=lang_setting');
	
	showtableheader('');
	
	showsetting(lang('plugin/yulegame_hdx', 'setting_lang_hdx'), 'hdx[lang_hdx]', $hdx['lang_hdx'], 'text', '', 0, 'hdx');
	showsetting(lang('plugin/yulegame_hdx', 'setting_lang_hd'), 'hdx[lang_hd]', $hdx['lang_hd'], 'text', '', 0, 'hd');
	showsetting(lang('plugin/yulegame_hdx', 'setting_lang_rob'), 'hdx[lang_rob]', $hdx['lang_rob'], 'text', '', 0, 'rob');
	showsetting(lang('plugin/yulegame_hdx', 'setting_lang_rob_jail'), 'hdx[lang_rob_jail]', $hdx['lang_rob_jail'], 'text', '', 0, 'rob_jail');
	
	showsubmit('settingsubmit');
	showtablefooter();
	showformfooter();
} else {
	
	$hdx = $_G['gp_hdx'];
	
	foreach ($hdx as $key => $val) {
				$key = dhtmlspecialchars($key);
		$val = dhtmlspecialchars($val);
		DB::query("INSERT INTO " . DB::table('hdx_setting') . "(skey, svalue) VALUES ('" . $key . "', '" . $val . "') ON DUPLICATE KEY UPDATE svalue = '" . $val . "'");
	}
	cpmsg(lang('plugin/yulegame_hdx', 'done_successfully'), 'action=plugins&operation=config&do=' . $pluginid . '&identifier=yulegame_hdx&pmod=lang_setting', 'succeed');

}
?>
