<?php

	/*
	MZG技术支持小组
	作者 fjyxian
	客服咨询与销售QQ:1063790899
	开发作者QQ:51353835(只提供有偿技术支持)
	*/
	if (!defined('IN_DISCUZ')) {
			exit('Access Denied');
	}
	$did = intval($_GET['did']);
	if ($did) {
			$where = " WHERE status='1' AND stime<='" . $_G['timestamp'] .
					"' AND (etime<='0' OR etime>='" . $_G['timestamp'] . "') AND did='$did'";
			$post = DB::fetch_first("SELECT * FROM " . DB::table('plugin_advertise') . $where .
					" limit 1");
			if (!empty($post['url'])) {
					//CHECK MAXCOUNT
					if ($post['method'] == 1) { //USER
							$maxcount = DB::result_first("SELECT COUNT(*) FROM " . DB::table('plugin_advertise_log') .
									" WHERE did='$post[did]' AND uid='$_G[uid]'");

					} else { //IP

							$maxcount = DB::result_first("SELECT COUNT(*) FROM " . DB::table('plugin_advertise_log') .
									" WHERE did='$post[did]' AND ip='$_G[clientip]'");

					}
					if ($post['maxcount'] <= 0 || $maxcount < $post['maxcount']) {
							if ($post['price'] > 0) {
									//UPDATE CREDIT
									updatemembercount($_G['uid'], array($post['price_type'] => $post['price']),
											1, 'TRC', $_G['uid']);

							}
							DB::insert('plugin_advertise_log', array('uid' => $_G['uid'], 'username' =>
									$_G['username'], 'did' => $post['did'], 'ip' => $_G['clientip'], 'time' =>
									$_G['timestamp']));
                                    DB::update('plugin_advertise',array('usercount'=>$post['usercount']+1),array('did'=>$post['did']));
							header('Location: ' . $post['url']);
							exit;
					}
					showmessage("点击次数已满!");
			}
	}
	showmessage("无效广告记录!");

?>