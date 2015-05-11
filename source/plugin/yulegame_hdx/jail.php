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

$url = 'plugin.php?id=yulegame_hdx&op=jail';

// 犯人列表
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
