<?php

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$sql = <<<EOF
DROP TABLE IF EXISTS `cdb_milu_seotool_included`, `cdb_milu_seotool_keyword`, `cdb_milu_seotool_keyword_rank`, `cdb_milu_seotool_spider`;
EOF;
runquery($sql);



//清除缓存
$cache_dir = DISCUZ_VERSION != 'X2' ? 'sysdata' : 'cache';
$cachefile = DISCUZ_ROOT.'./data/'.$cache_dir.'/milu_seotool_key.php';
@unlink($cachefile);
$cache_arr = array('milu_seotool_setting', 'milu_seotool_forum_catdata', 'milu_seotool_sitemap', 'milu_seotool_site_category', 'milu_seotool_keyword_data', 'milu_seotool_portal_catdata');
foreach($cache_arr as $k => $v){
	DB::delete('common_syscache', "cname='$v'");
}
//网站地图清理
$sitemap_name_arr = array('html', 'txt', 'xml', 'xml.gz');
foreach($sitemap_name_arr as $k => $v){
	@unlink(DISCUZ_ROOT.'/sitemap.'.$v);
}

//还原reboot.txt
$content = @file_get_contents(DISCUZ_ROOT.'/robots.txt');
if($content){
	$text_arr = explode(PHP_EOL, $content);
	foreach($text_arr as $k => $v){
		if(strexists($v, 'sitemap')) unset($text_arr[$k]);
	}
	$text = implode(PHP_EOL, $text_arr);
	file_put_contents(DISCUZ_ROOT.'/robots.txt', $text);
}
$finish = TRUE;

?>