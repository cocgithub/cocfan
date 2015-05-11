<?php

/**
 * 百度Sitemap每日生成定时计划任务
 */

if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

$specialFids = array(259,65,67,50);


//生成版块列表的SiteMap
$fids = C::t('forum_forum')->fetch_all_valid_forum();
$urls = array();
foreach($fids as $fid){
    if(!in_array($fid['fid'],$specialFids)){
        $urls[] = "http://www.cocfan.com/forum-$fid[fid]-1.html";
    }
}
genSitemap($urls,'sitemap-forum.xml');
writeSitemapLog('gen forum sitemap success! url count is:'.count($urls));

//生成帖子列表的sitemap
$startTime = time() - 86400;
$preDate = date('Y-m-d',$startTime);
$startTime = strtotime($preDate);
$threads = C::t('forum_thread')->fetch_all_by_dateline($startTime);

$urls = array();
foreach($threads as $thread){
    if(!in_array($thread['fid'],$specialFids)){
        $urls[] = "http://www.cocfan.com/thread-$thread[tid]-1-1.html";
    }
}
genSitemap($urls,'sitemap-thread.xml');
writeSitemapLog('gen thread sitemap success! url count is:'.count($urls));

function genSitemap($urls,$filename){
    if(empty($urls)){
        return;
    }
    $xml = '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:mobile="http://www.baidu.com/schemas/sitemap-mobile/1/">';
    foreach($urls as $url){
        $xml .= '<url>';
        $xml .= '<loc>'.$url.'</loc>';
        $xml .= '<lastmod>'.date('Y-m-d').'</lastmod>';
        $xml .= '<mobile:mobile type="autoadapt"/>';
        $xml .= '<changefreq>daily</changefreq>';
        $xml .= '<priority>0.9</priority>';
        $xml .= '</url>';
    }
    $xml .= '</urlset>';
    $filename = __DIR__ . '/../../../sitemap/' . $filename;
    file_put_contents($filename,$xml);
}

function writeSitemapLog($msg){
    $fileName = __DIR__ . '/../../../logs/' . 'sitemap'.date('Y-m-d').'.log';
    $msg = date('Y-m-d H:i:s') . "\tsitemap_builder\t{$msg}\n";
    file_put_contents($fileName,$msg,FILE_APPEND);
}
