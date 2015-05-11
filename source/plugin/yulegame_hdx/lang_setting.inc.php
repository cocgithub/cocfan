<?php
/*===============================================================
 * @插件名称			黑道生涯X
 * @插件版权			2007-2011 娱乐游戏.NET www.yulegame.net
 * @插件作者			Ricky Lee (ricky_yahoo@hotmail.com)
 * ******** 请尊重作者的劳动成果, 保留以上版权信息 *********************
 * ******** 本站致力于高质量插件开发, 如果你需要定做插件请QQ 231753
 * *** 或者EMAIL: ricky_yahoo@hotmail.com
 * *** 或者访问: http://bbs.yulegame.net 发送论坛短消息给 ricky_yahoo

 * *** 以下为<娱乐游戏网>出品的其他精品插件(请到论坛下载试用版):
 * 1: 黑道生涯 
 * 2: 游戏发号 
 * 3: 猜猜乐 
 * 5: 娱乐大富翁 
 * *** 感谢你对本站插件的支持和厚爱!
 * *** <娱乐游戏网> - 插件制作团队
 *================================================================
*/

// 必须使用此判断避免外部调用
if (! defined('IN_DISCUZ') || ! defined('IN_ADMINCP')) {
	exit('Access Denied');
}

//loadcache('plugin');


if (! submitcheck('settingsubmit', 1)) {
	// 黑道设置
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
