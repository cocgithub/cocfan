<?php

/**
 * 百度Sitemap每日生成定时计划任务
 */

if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

$specialFids = array(259,65,67,50);

//发送新增版块sitemap
$settingKey = 'sitemap_realtime_max_fid';
$minFid = C::t('common_setting')->fetch($settingKey);
if(empty($minFid)){
    $minFid = 0;
}
$allForums = C::t('forum_forum')->fetch_all_fids(true,'forum');
$urls = array();
$maxFid = $minFid;
foreach($allForums as $forum){
    $fid = $forum['fid'];
    if(!in_array($fid,$specialFids) && $fid > $minFid){
        $urls[] = "http://www.cocfan.com/forum-$fid-1.html";
    }
    if($fid > $maxFid){
        $maxFid = $fid;
    }
}
if(!empty($urls)){
    sendSitemap($urls);
    C::t('common_setting')->update($settingKey,$maxFid);
    writeSitemapLog('send forum sitemap success! sendNum:'.count($urls));
}

//发送新增帖子sitemap
$urls = array();
$settingKey = 'sitemap_realtime_max_tid';
$oneTimeLimit = 10000;//每次同步sitemap的最大url数
$maxTid = C::t('forum_thread')->fetch_max_tid();
$nowTid = C::t('common_setting')->fetch($settingKey);
if(empty($nowTid)){
    $nowTid = 0;
}
if($nowTid >= $maxTid){
    return;
}
$threads = C::t('forum_thread')->fetch_all_new_thread_by_tid($nowTid,0,$oneTimeLimit);
$lastThreadId = 0;
foreach($threads as $thread){
    if(!in_array($thread['fid'],$specialFids)){
        $urls[] = "http://www.cocfan.com/thread-$thread[tid]-1-1.html";
        $lastThreadId = $thread['tid'];
    }
}
if(!empty($urls)){
    sendSitemap($urls);
    if(!empty($lastThreadId)){
        C::t('common_setting')->update($settingKey,$lastThreadId);
    }
    writeSitemapLog('send thread sitemap success! sendNum:'.count($urls));
}


function sendSitemap($urls){
    $baidu_ping_url = 'ping.baidu.com';
    $get = '/sitemap?site=www.cocfan.com&resource_name=sitemap&access_token=f9Z1JXrs';
    $port= 80;
    if ( ( $io = fsockopen( $baidu_ping_url, $port, $errno, $errstr, 50 ) ) !== false )  {
        $send = "POST $get HTTP/1.1"."\r\n";
        $send .= 'Accept: */*'."\r\n";
        $send .= 'Cache-Control: no-cache'."\r\n";

        $send .= 'Host: '.$baidu_ping_url."\r\n";
        $send .= 'Pragma: no-cache'."\r\n";
        //$send .= "Referer: http://".$url.$get."\r\n";
        //$send .= 'User-Agent: Ning/1.0'."\r\n";

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

        $send .= 'Content-Length:'.strlen($xml)."\r\n";
        $send .= "Connection: Close\r\n\r\n";

        $send .= $xml."\r\n";

        fputs ( $io, $send );

        $return = '';
        while ( ! feof ( $io ) )
        {
            $return .= fread ( $io, 4096 );
        }
        unset($send);unset($xml);
        writeSitemapLog('send sitemap success! [rtn]:'.$return);
        return $return;
    }else{
        writeSitemapLog('send sitemap fail! [errorno]:'.$errno.' [emsg]:'.$errstr);
        return false;
    }
}

function writeSitemapLog($msg){
    $fileName = __DIR__ . '/../../../logs/' . 'sitemap'.date('Y-m-d').'.log';
    $msg = date('Y-m-d H:i:s') . "\tsitemap_realtime\t{$msg}\n";
    file_put_contents($fileName,$msg,FILE_APPEND);
}
