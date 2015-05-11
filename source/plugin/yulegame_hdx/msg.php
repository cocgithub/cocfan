<?php

/* ===============================================================
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
 * ================================================================
 */

// 必须使用此判断避免外部调用
if (!defined('IN_DISCUZ')) {
    exit('Access Denied');
}



$url = 'plugin.php?id=yulegame_hdx&op=msg';

// 会员列表


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
