<?php

/*
	(C)2007-2013 [www.7ree.com]
	This is NOT a freeware, use is subject to license terms
	Agreement: http://addon.discuz.com/?@7.developer.doc/agreement_7ree_html
	More Plugins: http://addon.discuz.com/?@7ree
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class plugin_toplist_7ree_forum {
	
	function index_top() {
		global $_G;
		require_once libfile('function/cache');


		$vars_7ree = $_G['cache']['plugin']['toplist_7ree'];
		$attachurl = $_G['setting']['attachurl'];
        $agreement_7ree = $vars_7ree['agreement_7ree'];
        $onoff_7ree = $vars_7ree['onoff_7ree'];
        $width_7ree = $vars_7ree['width_7ree'];
        $height_7ree = $vars_7ree['height_7ree'];
        $style_7ree = $vars_7ree['style_7ree'];
        $highlight_7ree = $vars_7ree['highlight_7ree'];
        $cutstr_7ree = $vars_7ree['cutstr_7ree']; 
        $blank_7ree = $vars_7ree['blank_7ree']; 
        $forums_7ree = array_filter(unserialize($vars_7ree['forums_7ree']));
        $fid_7ree = implode($forums_7ree,",") ;
        $not_fid_7ree = !$fid_7ree ? "" : "AND t.fid NOT IN ($fid_7ree)";
        $blank_7ree = $blank_7ree ? "target='_blank'" :  "target='_self'" ;
        $hottime_7ree = $vars_7ree['hottime_7ree'];
        $pic_title_7ree = $vars_7ree['pic_title_7ree'];
        $new_title_7ree = $vars_7ree['new_title_7ree'];
        $rep_title_7ree = $vars_7ree['rep_title_7ree'];
        $dig_title_7ree = $vars_7ree['dig_title_7ree'];
        $hot_title_7ree = $vars_7ree['hot_title_7ree'];
        $right_show_7ree = $vars_7ree['right_show_7ree'];
        $headertitleshow_7ree = $vars_7ree['headertitleshow_7ree']; 
		$linetitle_style_7ree =  $vars_7ree['linetitle_style_7ree'];
	    $tiptime_7ree = $vars_7ree['tiptime_7ree'] * 1000;	
        $shownum_7ree = min(intval($vars_7ree[shownum_7ree]) ? intval($vars_7ree[shownum_7ree]) : 9, 50);
        $newbienum_7ree = min(intval($vars_7ree[newbienum_7ree]),20);

        if (!$agreement_7ree) return "<div class='tbms mtm mbm'>".lang('plugin/toplist_7ree', 'php_lang_agree_7ree')."</div>";
        if (!$onoff_7ree ||(!$vars_7ree[gidshow_7ree] && $_GET[gid])) return "";

		$weekarray=array(lang('plugin/toplist_7ree', 'php_lang_week0_7ree'),
					     lang('plugin/toplist_7ree', 'php_lang_week1_7ree'),
					  	 lang('plugin/toplist_7ree', 'php_lang_week2_7ree'),
						 lang('plugin/toplist_7ree', 'php_lang_week3_7ree'),
						 lang('plugin/toplist_7ree', 'php_lang_week4_7ree'),
				 		 lang('plugin/toplist_7ree', 'php_lang_week5_7ree'),
						 lang('plugin/toplist_7ree', 'php_lang_week6_7ree')
		);
        $date_7ree = gmdate(lang('plugin/toplist_7ree', 'php_lang_date_7ree'), $_G[timestamp] + $_G[setting][timeoffset] * 3600); 
        $week_7ree = $weekarray[gmdate("w", $_G[timestamp] + $_G[setting][timeoffset] * 3600)]; 
        $time_7ree = gmdate(lang('plugin/toplist_7ree', 'php_lang_time_7ree'), $_G[timestamp] + $_G[setting][timeoffset] * 3600); 

@require_once(DISCUZ_ROOT.'./source/discuz_version.php');
$cahcedir_7ree = DISCUZ_VERSION == "X2" ? './data/cache/cache_' : './data/sysdata/cache_';
///////////////////////////////幻灯图///////////////
 if ($vars_7ree[pic_on_7ree]){
//幻灯图获取
//---设置---start
$shownums	= min(intval($vars_7ree[picnum_7ree]) ? intval($vars_7ree[picnum_7ree]) : 5 , 20);
$searchnums	= 18;//搜索总数.
$cachelife	= $vars_7ree[cachetime_7ree] * 60;//缓存时间.
$orderby	= 'dateline';//搜索顺序:按日期dateline.按浏览次数downloads.随机rand.
$pic_cachename	= 'showpic_7ree';//缓存名.若与何插件冲突请修改,只要不冲突不会影响使用.

//---设置---end

@include(DISCUZ_ROOT.$cahcedir_7ree.$pic_cachename.'.php');
if(@filemtime(DISCUZ_ROOT.$cahcedir_7ree.$pic_cachename.'.php') + $cachelife < $_G[timestamp]){
	$datapic = array();
	
	$query = DB::query("SELECT a.attachment,t.tid, t.fid, t.subject FROM ".DB::table('forum_threadimage')." a INNER JOIN ".DB::table('forum_thread')." t ON t.tid=a.tid WHERE t.isgroup=0 AND t.displayorder>=0 AND t.dateline<{$_G[timestamp]} $not_fid_7ree GROUP BY a.tid ORDER BY a.tid DESC LIMIT $searchnums");
                 

				while($pic = DB::fetch($query)) {
					$pics['picpics'] = $_G['setting']['attachurl'].'forum/'.$pic['attachment'];
					$pics['attachment'] = $pic['attachment'];
					$pics['piclinks'] = 'forum.php?mod=viewthread%26tid='.$pic['tid'];
					$pics['pictexts'] = str_replace('\'', ' ',$pic['subject']);
					$pics['attaid'] = $pic['aid'];
					$pics['attachment'] = $pic['attachment'];
					$datapic[] = $pics;
				}
				$cacheArray .= "\$pic_7ree=".arrayeval($datapic).";\n";
				
	writetocache($pic_cachename, $cacheArray);
	@include(DISCUZ_ROOT.$cahcedir_7ree.$pic_cachename.'.php');


}


if(is_array($pic_7ree) && PHP_VERSION > 4.3 && $vars_7ree['picsorder_7ree']) shuffle($pic_7ree);

$i = 0;
$j = 0;
while ($j < $shownums && isset($pic_7ree[$i])) {
	if(is_readable($pic_7ree[$i]['picpics'])) {
		$showpicpics .= $comma.$pic_7ree[$i]['picpics'];
		$showpiclinks .= $comma.$pic_7ree[$i]['piclinks'];
		$showpictexts .= $comma.$pic_7ree[$i]['pictexts'];
		$comma = '|';
		$j++;
	}elseif($_G['setting']['ftp']['on']){
	  	$showpicpics .= $comma.$_G['setting']['ftp']['attachurl']."forum/".$pic_7ree[$i]['attachment'];
	    $showpiclinks .= $comma.$pic_7ree[$i]['piclinks'];
	    $showpictexts .= $comma.$pic_7ree[$i]['pictexts'];
		$j++;
	}
	$comma = '|';
	$i++;
	}
}
//////////////////其他数据调用
        //---设置---start
        $cachename_toplist	= "toplist_7ree";//缓存名.若与何插件冲突请修改,只要不冲突不会影响使用.

        	
		@include(DISCUZ_ROOT.$cahcedir_7ree.$cachename_toplist.'.php');
if(@filemtime(DISCUZ_ROOT.$cahcedir_7ree.$cachename_toplist.'.php') + $cachelife < $_G[timestamp]){

		$colorarray = array('', '#EE1B2E', '#EE5023', '#996600', '#3C9D40', '#2897C5', '#2B65B7', '#8F2A90', '#EC1282');



//新贴
 if ($vars_7ree[new_on_7ree]){
        $hack_cut_str = $cutstr_7ree; //标题字数
        $hack_cut_strauthor = 9;
        $new_post_threadlist = array();
        $nthread = array();
        $query = DB::query("SELECT t.*, f.name FROM ".DB::table('forum_thread')." t, ".DB::table('forum_forum')." f WHERE f.fid=t.fid AND t.displayorder >=0 AND t.dateline<{$_G[timestamp]} $not_fid_7ree ORDER BY t.dateline DESC LIMIT 0, $shownum_7ree");
        while($nthread = DB::fetch($query)) {
        $nthread['forumname'] = ereg_replace('<[^>]*>','',$nthread['name']);
        $nthread['view_subject'] = cutstr($nthread['subject'],$hack_cut_str);
        $nthread['view_author'] = cutstr($nthread['author'],$hack_cut_strauthor);
        $nthread['date']= gmdate("Y-m-d H:i:s", $nthread['dateline'] + $_G[setting][timeoffset] * 3600);
        $nthread['lastreplytime']= gmdate("Y-m-d H:i:s", $nthread[lastpost] + ($_G[setting][timeoffset] * 3600));
        if($nthread['highlight'] && $highlight_7ree) {
                $string = sprintf('%02d', $nthread['highlight']);
                $stylestr = sprintf('%03b', $string[0]);
                $nthread['highlight'] = 'style="';
                $nthread['highlight'] .= $stylestr[0] ? 'font-weight: bold;' : '';
                $nthread['highlight'] .= $stylestr[1] ? 'font-style: italic;' : '';
                $nthread['highlight'] .= $stylestr[2] ? 'text-decoration: underline;' : '';
                $nthread['highlight'] .= $string[1] ? 'color: '.$colorarray[$string[1]] : '';
                $nthread['highlight'] .= '"';
        } else {
                $nthread['highlight'] = '';
        }
        $newpost_threadlist[] = $nthread;
		}
        $curdata = "\$new_post_threadlist = ".arrayeval($newpost_threadlist).";\n\n";
}

        //新回复
 if ($vars_7ree[rep_on_7ree]){
        $hack_cut_str = $cutstr_7ree; //标题字数
        $hack_cut_strauthor = 9;
        $new_reply_threadlist = array();
        $rthread = array();
        $query = DB::query("SELECT t.*, f.name FROM ".DB::table('forum_thread')." t, ".DB::table('forum_forum')." f WHERE f.fid=t.fid AND t.displayorder >=0 AND t.closed NOT LIKE 'moved|%' AND t.replies !=0 AND t.dateline<{$_G[timestamp]} $not_fid_7ree ORDER BY t.lastpost DESC LIMIT 0, $shownum_7ree");
        while($rthread = DB::fetch($query)) {
        $rthread['forumname'] = ereg_replace('<[^>]*>','',$rthread['name']);
        $rthread['view_subject'] = cutstr($rthread['subject'],$hack_cut_str);
        $rthread['view_lastposter'] = cutstr($rthread['lastposter'],$hack_cut_strauthor);
	$rthread['date']= gmdate("Y-m-d H:i:s", $rthread['dateline'] + $_G[setting][timeoffset] * 3600);
        $rthread['lastreplytime']= gmdate("Y-m-d H:i:s", $rthread[lastpost] + ($_G[setting][timeoffset] * 3600));
        if($rthread['highlight'] && $highlight_7ree) {
                $string = sprintf('%02d', $rthread['highlight']);
                $stylestr = sprintf('%03b', $string[0]);
                $rthread['highlight'] = 'style="';
                $rthread['highlight'] .= $stylestr[0] ? 'font-weight: bold;' : '';
                $rthread['highlight'] .= $stylestr[1] ? 'font-style: italic;' : '';
                $rthread['highlight'] .= $stylestr[2] ? 'text-decoration: underline;' : '';
                $rthread['highlight'] .= $string[1] ? 'color: '.$colorarray[$string[1]] : '';
                $rthread['highlight'] .= '"';
        } else {
                $rthread['highlight'] = '';
        }
        $replypost_threadlist[] = $rthread;
		}
$curdata .= "\$reply_post_threadlist = ".arrayeval($replypost_threadlist).";\n\n";
}

        //精华帖
 if ($vars_7ree[dig_on_7ree]){
        $hack_cut_str = $cutstr_7ree; //标题字数
        $hack_cut_strauthor = 9;
        $new_digest_threadlist = array();
        $dthread = array();
        $query = DB::query("SELECT t.*, f.name FROM ".DB::table('forum_thread')." t, ".DB::table('forum_forum')." f WHERE f.fid=t.fid  AND t.closed NOT LIKE 'moved|%'  AND t.digest in (1,2,3) AND t.displayorder >=0 AND t.dateline<{$_G[timestamp]} $not_fid_7ree ORDER BY t.dateline DESC LIMIT $shownum_7ree");
        while($dthread = DB::fetch($query)) {
        $dthread['forumname'] = ereg_replace('<[^>]*>','',$dthread['name']);
        $dthread['view_subject'] = cutstr($dthread['subject'],$hack_cut_str);
        $dthread['view_lastposter'] = cutstr($dthread['lastposter'],$hack_cut_strauthor);
		$dthread['date']= gmdate("Y-m-d H:i:s", $dthread['dateline'] + $_G[setting][timeoffset] * 3600);
        $dthread['lastreplytime']= gmdate("Y-m-d H:i:s", $dthread[lastpost] + ($_G[setting][timeoffset] * 3600));
        if($dthread['highlight'] && $highlight_7ree) {
                $string = sprintf('%02d', $dthread['highlight']);
                $stylestr = sprintf('%03b', $string[0]);
                $dthread['highlight'] = 'style="';
                $dthread['highlight'] .= $stylestr[0] ? 'font-weight: bold;' : '';
                $dthread['highlight'] .= $stylestr[1] ? 'font-style: italic;' : '';
                $dthread['highlight'] .= $stylestr[2] ? 'text-decoration: underline;' : '';
                $dthread['highlight'] .= $string[1] ? 'color: '.$colorarray[$string[1]] : '';
                $dthread['highlight'] .= '"';
        } else {
                $dthread['highlight'] = '';
        }
        $digestpost_threadlist[] = $dthread;
		}
$curdata .= "\$digest_post_threadlist = ".arrayeval($digestpost_threadlist).";\n\n";
}

//热贴
if ($vars_7ree[hot_on_7ree]){
        $hack_cut_str = $cutstr_7ree; //标题字数
        $hack_cut_strauthor = 9;
        $hot_post_threadlist = array();
        $hthread = array();
        $query = DB::query("SELECT t.*, f.name FROM ".DB::table('forum_thread')." t, ".DB::table('forum_forum')." f WHERE f.fid=t.fid AND t.displayorder >=0 AND t.dateline + $hottime_7ree * 86400 > $_G[timestamp] AND t.dateline<{$_G[timestamp]} $not_fid_7ree ORDER BY t.views DESC LIMIT 0, $shownum_7ree");
        while($hthread = DB::fetch($query)) {
        $hthread['forumname'] = ereg_replace('<[^>]*>','',$hthread['name']);
        $hthread['view_subject'] = cutstr($hthread['subject'],$hack_cut_str);
        $hthread['view_author'] = cutstr($hthread['author'],$hack_cut_strauthor);
        $hthread['date']= gmdate("Y-m-d H:i:s", $hthread['dateline'] + $_G[setting][timeoffset] * 3600);
        $hthread['lastreplytime']= gmdate("Y-m-d H:i:s", $hthread[lastpost] + ($_G[setting][timeoffset] * 3600));
        if($hthread['highlight'] && $highlight_7ree) {
                $string = sprintf('%02d', $hthread['highlight']);
                $stylestr = sprintf('%03b', $string[0]);
                $hthread['highlight'] = 'style="';
                $hthread['highlight'] .= $stylestr[0] ? 'font-weight: bold;' : '';
                $hthread['highlight'] .= $stylestr[1] ? 'font-style: italic;' : '';
                $hthread['highlight'] .= $stylestr[2] ? 'text-decoration: underline;' : '';
                $hthread['highlight'] .= $string[1] ? 'color: '.$colorarray[$string[1]] : '';
                $hthread['highlight'] .= '"';
        } else {
                $hthread['highlight'] = '';
        }
        $hotpost_threadlist[] = $hthread;
		}
        $curdata .= "\$hot_post_threadlist = ".arrayeval($hotpost_threadlist).";\n\n";
}

if ($newbienum_7ree){//新会员

		$query = DB::query("SELECT username, uid, regdate FROM ".DB::table('common_member')." ORDER BY uid DESC LIMIT $newbienum_7ree");
        while($member_7ree = DB::fetch($query)) {
           		$member_7ree['regdate'] = gmdate("Y-m-d H:i:s", $member_7ree[regdate] + ($_G[setting][timeoffset] * 3600));
           		$member_7ree['avatar_7ree'] = discuz_uc_avatar($member_7ree['uid'],'small');
                $newbie_7ree[] = $member_7ree;
        }      	
		$curdata .= "\$newbie_list_7ree = ".arrayeval($newbie_7ree).";\n\n";

}


	writetocache($cachename_toplist, $curdata);
	@include DISCUZ_ROOT.$cahcedir_7ree.$cachename_toplist.'.php';
}




		include template('toplist_7ree:toplist_7ree');
		return $return;
	}
}

?>
