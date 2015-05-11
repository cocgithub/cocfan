<?php

/**
 * UC九游  生成sitemap计划任务
 *
 * @author Win <luorong@ucewb.com>
 * @copyright 优视动景  2014 版权所有
 * @link       http://bbs.9game.cn/
 * @since      Discuz! X2.5   v5.2.27
 */

if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

require_once 'worker.php';

class sitemap_worker extends worker
{

    private $_filePre = '';

    public function __construct() {
        $this->_workername = 'sitemap_worker';
        $this->_filePre = __DIR__ . '/../../sitemap/';
    }

    public function run(){
        //0点--1点间才执行
        $nowHour = date('H',time());
        if(intval($nowHour) == 0){
            //生成热门版块的sitemap
            $this->_genHotForumSiteMap();
            $this->_genSiteMap4Baidu();
        }
        $this->_realTimeSiteMap();
    }

    private function _genSiteMap4Baidu(){
        //生成版块列表的SiteMap
        $gameFids = $this->_getGameFids();
        $urls = array();
        foreach($gameFids as $fid){
            $urls[] = "http://bbs.9game.cn/forum-$fid-1.html";
        }
        $this->_genSitemap($urls,'sitemap-forum.xml');
        $this->_writeScriptLog('gen forum sitemap success! url count is:'.count($urls));

        //生成帖子列表的sitemap
        $startTime = time() - 86400;
        $preDate = date('Y-m-d',$startTime);
        $startTime = strtotime($preDate);
        $threads = C::t('forum_thread')->fetch_all_by_dateline($startTime);
        $gameFids = $this->_getGameFids(true);
        $urls = array();
        foreach($threads as $thread){
            if(in_array($thread['fid'],$gameFids)){
                $urls[] = "http://bbs.9game.cn/thread-$thread[tid]-1-1.html";
            }
        }
        $this->_genSitemap($urls,'sitemap-thread.xml');
        $this->_writeScriptLog('gen thread sitemap success! url count is:'.count($urls));
    }

    private function _realTimeSiteMap(){
        $workers = include __DIR__ . '/../config/config_ucgame_workers.php';
        $realTimeOn = $workers['sitemap']['realTimeOn'];
        if(empty($realTimeOn)){
            $this->_writeScriptLog('real time sitemap is close!');
            return;
        }
        $this->_sendForumSitemap();
        $settingKey = 'sitemap_realtime_max_tid';
        $maxLimit = 200000;//每小时内的最大同步数
        $oneTimeLimit = 10000;//每次同步sitemap的最大url数
        //判断缓存中的tid是否小于帖子列表中的最大tid，若小于，则生成sitemap
        $maxTid = C::t('forum_thread')->fetch_max_tid();
        $nowTid = C::t('common_setting')->fetch($settingKey);
        if(empty($nowTid)){
            $nowTid = 0;
        }
        if($nowTid >= $maxTid){
            return;
        }
        $threads = C::t('#uctable#forum_thread_ucext')->fetch_alltid_by_mintid($nowTid,$maxLimit);
        $tmpThreadIds = array_splice($threads,0,$oneTimeLimit,array());
        $times = 1;
        $lastThreadId = $tmpThreadIds[0]['tid'];
        $gameFids = $this->_getGameFids(true);
        while(!empty($tmpThreadIds)){
            $urls = array();
            foreach($tmpThreadIds as $thread){
                if(in_array($thread['fid'],$gameFids)){
                    $urls[] = "http://bbs.9game.cn/thread-$thread[tid]-1-1.html";
                    $lastThreadId = $thread['tid'];
                }
            }
            $this->_writeScriptLog("do the send mission,loop:$times,threadcount:".count($urls).",lastId:$lastThreadId");
            unset($tmpThreadIds);
            $this->_sendSitemap($urls);
            unset($urls);
            $tmpThreadIds = array_splice($threads,0,$oneTimeLimit,array());
            $times++;
        }
        if(!empty($lastThreadId)){
            C::t('common_setting')->update($settingKey,$lastThreadId);
        }
        unset($threads);//unset的作用是释放内存，因为php每一个进程都有内存使用限制的
    }

    private function _sendForumSitemap(){
        $settingKey = 'sitemap_realtime_max_fid';
        $minFid = C::t('common_setting')->fetch($settingKey);
        if(empty($fid)){
            $fid = 0;
        }
        $allForums = C::t('forum_forum')->fetch_all_fids(true,'forum');
        $gameFids = $this->_getGameFids();
        $urls = array();
        $maxFid = $minFid;
        foreach($allForums as $forum){
            $fid = $forum['fid'];
            if(in_array($fid,$gameFids) && $fid > $minFid){
                $urls[] = "http://bbs.9game.cn/forum-$fid-1.html";
            }
            if($fid > $maxFid){
                $maxFid = $fid;
            }
        }
        if(!empty($urls)){
            $this->_sendSitemap($urls);
            C::t('common_setting')->update($settingKey,$maxFid);
            $this->_writeScriptLog('send forum sitemap success! sendNum:'.count($urls));
        }
        unset($allForums);
    }

    private function _genHotForumSiteMap(){
        $excludeFid = array(51,354,423,429,454,584,668,678,2065,1087,1920,1043,1267,1313,1797,1318,258,2337,1436);
        $hotForums = C::t('#uctable#forum_thread_ucext')->getHotForumsByThread($excludeFid);
        $this->_writeScriptLog('gen sitemap for hotforum infos:'.json_encode($hotForums));
        $domInfos = array();
        $date = date('Y-m-d',time());
        foreach($hotForums as $forum){
            $url = array(
                'loc' => 'http://bbs.9game.cn/forum-'.$forum['fid'].'-1.html',
                'lastmod'=>$date,
                'changefreq'=>'daily',
                'priority'=>0.9
            );
            $domInfos[] = $url;
        }
        $filePath = DISCUZ_ROOT.'./data/cache/sitemap_bbs_hotforum.xml';
        C::s('#utils#sitemapUtil')->genSiteMap($domInfos,$filePath);
    }

    private function _genSitemap($urls,$filename){
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
        $filename = $this->_filePre . $filename;
        file_put_contents($filename,$xml);
    }

    /**获取游戏版块的ID
     * @return array
     */
    private function _getGameFids($includeSub = false){
        $gameFids = array();
        $gameFidInfos = C::t('#uctable#forum_board_game')->fetch_all_fid();
        foreach($gameFidInfos as $fidInfo){
            $gameFids[] = $fidInfo['fid'];
        }
        unset($gameFidInfos);
        //要是游戏版块，需要将子版块也包含进来，不然某些帖子就不会计算在内。。。。
        if($includeSub){
            $subForums = C::t('forum_forum')->fetch_all_fids(true,'sub');
            foreach($subForums as $forum){
                if(in_array($forum['fup'],$gameFids)){
                    $gameFids[] = $forum['fid'];
                }
            }
            unset($subForums);
        }
        return $gameFids;
    }

    /**实时发送sitemap类
     * @param $urls
     * @return bool|string
     */
    private function _sendSitemap($urls){
        $baidu_ping_url = 'ping.baidu.com';
        $get = '/sitemap?site=bbs.9game.cn&resource_name=sitemap&access_token=f9Z1JXrs';
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
            $this->_writeScriptLog('send sitemap success! [rtn]:'.$return);
            return $return;
        }else{
            $this->_writeScriptLog('send sitemap fail! [errorno]:'.$errno.' [emsg]:'.$errstr);
            return false;
        }
    }

    private function _writeScriptLog($msg){
        ucx_Logger::writeCronLog($this->_workername,$msg);
    }
}

