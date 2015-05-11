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
	$perpage = 12;
	$page = empty($_GET['page']) ? 0 : intval($_GET['page']);
	if ($page < 1) $page = 1;
	$start = ($page - 1) * $perpage;

	$list = array();
	$count = 0;
	$multi = '';
	$where = " WHERE status='1' AND stime<='" . $_G['timestamp'] .
			"' AND (etime<='0' OR etime>='" . $_G['timestamp'] . "') AND (usercount<maxcount OR maxcount<=0)";
	$count = DB::result(DB::query("SELECT COUNT(*) FROM " . DB::table('plugin_advertise') .
			$where), 0);

	$query = DB::query("SELECT * FROM " . DB::table('plugin_advertise') . $where .
			" ORDER BY topid ASC,posttime DESC LIMIT $start,$perpage");
	while ($value = DB::fetch($query)) {
			$list[] = $value;
	}
	$multi = multi($count, $perpage, $page, $pageurl . "&mod=$mod");
	include_once template($_G['m_pid'] . ':' . $mod);

?>