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

	$perpage = 20;
	$page = empty($_GET['page']) ? 0 : intval($_GET['page']);
	if ($page < 1) $page = 1;
	$start = ($page - 1) * $perpage;

	$list = array();
	$count = 0;
	$multi = '';
    $where="";
    $did = intval($_GET['did']);
    if ($did){
        $where = " AND a.did='$did'";
    }
	$count = DB::result(DB::query("SELECT COUNT(*) FROM " . DB::table('plugin_advertise_log') .
			" l," . DB::table('plugin_advertise') . " a WHERE l.did=a.did".$where), 0);

	$query = DB::query("SELECT l.*,a.name,a.price,a.price_type FROM " . DB::table('plugin_advertise_log') .
			" l," . DB::table('plugin_advertise') .
			" a WHERE l.did=a.did$where ORDER BY l.time DESC LIMIT $start,$perpage");
	while ($value = DB::fetch($query)) {
			$list[] = $value;
	}
	$multi = multi($count, $perpage, $page, $pageurl . "&mod=$mod&did=".$did);
	include_once template($identifier . ':admin_' . $mod);

?>