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
