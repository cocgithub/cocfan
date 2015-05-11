<?php
class plugin_saion_seo {
    var $botroute=array(
            array('google', array('googlebot', 'mediapartners-google')),
            array('baidu', array('baiduspider')),
            array('sogou', array('sogou', 'sohu-search')),
            array('163', array('youdaobot')),
            array('soso', array('sosospider')),
            array('yahoo', array('yahoo!slurp')),
            array('bing', array('manbot', 'bingbot')),
            array('360', array('360spider')),
    );
    function common() {
        global $_G;
        if(!is_array($_G['setting']['rewritestatus'])) $_G['setting']['rewritestatus']=array();
        $_G['setting']['rewritestatus'][]='saion_seo';
        $arr=array(
            'href="home.php?mod=magic', 'rel="nofollow" href="home.php?mod=magic',
            'href="home.php?mod=medal', 'rel="nofollow" href="home.php?mod=medal',
            'ordertype=1"', 'ordertype=1" rel="nofollow"',
            'href="forum.php?mod=misc&amp;action=viewthreadmod', 'rel="nofollow" href="forum.php?mod=misc&amp;action=viewthreadmod',
            'href="forum.php?mod=misc&amp;action=comment', 'rel="nofollow" href="forum.php?mod=misc&amp;action=comment',
            'href="forum.php?mod=post&amp;action=edit', 'rel="nofollow" href="forum.php?mod=post&amp;action=edit',
            'href="home.php?mod=spacecp', 'rel="nofollow" href="home.php?mod=spacecp',
            '&amp;ordertype=1">', '&amp;ordertype=1" rel="nofollow">',
            '&amp;do=wall"', '&amp;do=wall" rel="nofollow"',
            'href="misc.php?mod=tag', 'rel="tag" href="misc.php?mod=tag',
            '</em> <a href="forum.php?mod=forumdisplay', '</em> <a rel="contents" href="forum.php?mod=forumdisplay',
            '<li>&#8226; <a href="forum.php?mod=viewthread&amp;tid', '<li>&#8226; <a rel="bookmark" href="forum.php?mod=viewthread&amp;tid',
            '<span class="pgb y"><a href="forum.php?mod=forumdisplay&amp;fid=', '<span class="pgb y"><a rel="contents" href="forum.php?mod=forumdisplay&amp;fid=',
            'id="thread_subject"', 'rel="start" id="thread_subject"',
            'class="prev"', 'rel="prev" class="prev"',
            'class="nxt"', 'rel="next" class="nxt"',
            'href="http://www.discuz.net"', 'href="http://www.discuz.net" rel="nofollow"',
            'href="http://www.comsenz.com"', 'href="http://www.comsenz.com" rel="nofollow"',
            'href="forum.php?mobile=yes', 'rel="nofollow" href="forum.php?mobile=yes',
            'href="archiver/"', 'rel="nofollow" href="archiver/"',
            'filter=digest&amp;digest=1"', 'filter=digest&amp;digest=1" rel="nofollow"',
            'filter=recommend&amp;orderby=recommends&amp;recommend=1"', 'filter=recommend&amp;orderby=recommends&amp;recommend=1" rel="nofollow"',
            'href="forum.php?mod=viewthread&amp;action=printable', 'rel="nofollow" href="forum.php?mod=viewthread&amp;action=printable',
            );
        if(CURSCRIPT=='forum'){
             $arr[]=' href="forum.php"'; $arr[]=' rel="index" href="forum.php"';
        }elseif(CURSCRIPT=='portal'){
             $arr[]=' href="portal.php"'; $arr[]=' rel="index" href="portal.php"';
        }
            $i=1;
        while($left=array_shift($arr)){
            $right=array_shift($arr);
            $_G['setting']['output']['str']['search']['static_'.$i]=$left;
            $_G['setting']['output']['str']['replace']['static_'.$i]=$right;
            $i++;
        }


        if(CURSCRIPT=='home' && CURMODULE=='space' && ($_GET['do']!='thread' || $_GET['do']!='reply'))
            $_G['setting']['seohead'].='<meta name="robots" content="noindex follow" />';
        if(CURSCRIPT=='home' && CURMODULE=='spacecp')
            $_G['setting']['seohead'].='<meta name="robots" content="none" />';
        if($_GET['fromuid'] || $_GET['fromuser'])
            $_G['setting']['seohead'] .= '<link href="'.$_G['siteurl'].'forum.php" rel="canonical" />';
        return ;
    }
    function isSpider() {
        $_BotRoute=$this->botroute;
        $ua=strtolower(str_replace(' ', '', $_SERVER['HTTP_USER_AGENT']));

        $s='';
        foreach($_BotRoute as $SE){
            foreach($SE[1] as $BOT){
                if(strpos($ua, $BOT)!==FALSE){
                    $s=$SE[0];
                    return $s;
                }
            }
        }
        return false;
    }
    function global_footer() {
        $name=CURSCRIPT.':'.CURMODULE;
        switch($name) {
            case 'forum:viewthread':
                if(!$spider=$this->isSpider()) return '';

                if($GLOBALS['_G']['tid'] && !empty($GLOBALS['postlist'])){
                    $this->addStat('thread', $GLOBALS['_G']['tid'], $spider);
                }
                return '';
            case 'portal:view':
                if(!$spider=$this->isSpider()) return '';

                if($aid=intval($_GET['aid'])){
                    $this->addStat('article', $aid, $spider);
                }
                return '';
            default:
                return '';
        }
    }
    function addStat($type, $id, $spider) {
        $timestamp=TIMESTAMP;
        DB::query('INSERT '.DB::table('saion_seo_visit')." SET type='$type', id='$id', spider='$spider', dateline='$timestamp' ON DUPLICATE KEY UPDATE time=time+1, dateline='$timestamp'");
    }
}

class plugin_saion_seo_forum extends plugin_saion_seo {
   function viewthread_postheader() {
       global $_G;
       if($_G['adminid']==1){
            return array('<span class="pipe">|</span><a href="'.$_G['siteurl'].$_G['basefilename'].($_SERVER["QUERY_STRING"]?('?'.$_SERVER['QUERY_STRING'].'&view_seo=1'):'?view_seo=1').'">SEO</a>');
       }
   }
   function viewthread_top() {
        global $_G;
        if($_G['adminid']==1){
            if(!$_GET['view_seo']) return;
            $id=$_G['tid'];

            $stats=array();
            $query=DB::query('SELECT * FROM '.DB::table('saion_seo_visit')." WHERE type='thread' and id='$id'");

            while($stat=DB::fetch($query)){
                $stats[$stat['spider']]=array($stat['time'], $stat['dateline']);
            }

            if(!$stats){
                return lang('plugin/saion_seo', 'viewthread_hook_none');
            }else{
                $result='';
                foreach($this->botroute as $se){
                    $name=$se[0];
                    if(isset($stats[$name]))
                        $result.=lang('plugin/saion_seo', 'viewthread_hook_'.$name, array('time'=>$stats[$name][0], 'dateline'=>dgmdate($stats[$name][1])));
                }
                return $result;
            }
        }
        return '';
    }
}