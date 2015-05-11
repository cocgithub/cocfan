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
	$op = trim($_GET['op']);
	if (!in_array($op, array('add'))) {
			$op = 'index';
	}
	$opav = array($op => ' class="modav"');

	if ($op == 'add' and $_GET['did']) {
			$did = intval($_GET['did']);
			$post = DB::fetch_first("SELECT * FROM " . DB::table('plugin_advertise') .
					" WHERE did='$did'");
	} else {
			$perpage = 20;
			$page = empty($_GET['page']) ? 0 : intval($_GET['page']);
			if ($page < 1) $page = 1;
			$start = ($page - 1) * $perpage;

			$list = array();
			$count = 0;
			$multi = '';
			$where = '';
			$count = DB::result(DB::query("SELECT COUNT(*) FROM " . DB::table('plugin_advertise') .
					" WHERE status>=0$where"), 0);

			$query = DB::query("SELECT * FROM " . DB::table('plugin_advertise') .
					" WHERE status>=0$where ORDER BY topid ASC,posttime DESC LIMIT $start,$perpage");
			while ($value = DB::fetch($query)) {
					$list[] = $value;
			}
			$multi = multi($count, $perpage, $page, $pageurl . "&mod=$mod");
	}
	$stime = date("Y-m-d", ($post['stime'] ? $post['stime'] : $_G['timestamp']));
	$etime = ($post['etime'] ? date("Y-m-d", $post['etime']) : "");
	$maxcount = $post['maxcount'] > 0 ? $post['maxcount'] : 0;
	$price = $post['price'] > 0 ? $post['price'] : 0;
	$topid = $post['topid'] > 0 ? $post['topid'] : 0;
	include_once template($identifier . ':admin_' . $mod);

?>