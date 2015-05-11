<?php
/* ===============================================================
 * @插件名称			数据菜鸟
 * @插件版本			1.32 免费版
 * @插件版权			2012-2013 2015电脑技术论坛 www.2015pc.com
 * @插件作者			Wu Xin (wx.1@163.com)
 * ******** 请尊重开发者的劳动成果, 保留以上版权信息 *************
 * ================================================================
 */
if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

class plugin_wuxin_databird {
    function plugin_wuxin_databird() {
        global $_G;
        $config = $_G['cache']['plugin']['wuxin_databird'];
        $this->multiple = $config['multiple'];
        $this->olmembers = $config['olmembers'];
        $this->olguests = $config['olguests'];
        $this->changefid = $config['changefid'];
        $this->timemembers = $config['timemembers'];
        $this->timeguests = $config['timeguests'];
    }
}

class plugin_wuxin_databird_forum extends plugin_wuxin_databird {
    function index_top_output() {
        global $todayposts, $postdata, $posts, $_G,  $forumlist, $guestcount, $onlinenum, $membercount, $onlineinfo, $detailstatus, $whosonline;
        $multiple = $this->multiple;
        $olmembers = $this->olmembers;
        $olguests = $this->olguests;
        $timemembers = explode(",", $this->timemembers);
        $timeguests = explode(",", $this->timeguests);
        $nowtime = getdate();
        $nowhour = $nowtime['hours'];
        if ($olmembers == 0) {
            $olmembers = $timemembers[$nowhour];
        }
        if ($olguests == 0) {
            $olguests = $timeguests[$nowhour];
        }
		if ($detailstatus) {
			$guestcount = $guestcount + $olguests;
			$membercount = $membercount + $olmembers;
			$onlinenum = $membercount + $guestcount;
			$memlist = array();
			$maxusers = DB::query("SELECT uid FROM ".DB::table('common_member'));
			$maxusers = DB::num_rows($maxusers);
			$i = 0;
			$j = 0;
			foreach( $whosonline as $onlineuser) {
				$onlineuserid[] = $onlineuser['uid'];
			}
			while ($i < $olmembers) {
				$randnum = rand(1, $maxusers);
				$query = DB::query("SELECT uid FROM ".DB::table('common_member')." LIMIT ".$randnum." , 1");
				$getuid = DB::fetch($query);
				if (!in_array($getuid['uid'], $memlist) && !in_array($getuid['uid'], $onlineuserid)) {
					$memlist[] = $getuid['uid'];
					$j++;
				}
				$i++;
			}
			$membercount = $membercount - ($i - $j);
			$onlinenum = $onlinenum - ($i - $j);
		} else {
			$onlinenum = $onlinenum + $olmembers + $olguests;
		}
		foreach($memlist as $uid) {
			$user = getuserbyuid($uid);
			$username = $user['username'];
			$groupid = $user['groupid'];
			$icon = empty($_G['cache']['onlinelist'][$groupid]) ? $_G['cache']['onlinelist'][0] : $_G['cache']['onlinelist'][$groupid];
			$lasttime = dgmdate($_G['timestamp'] - rand(1, 20) * 60, 't');
			$memberlist = array('uid' => $uid, 'username' => $username, 'groupid' => $groupid, 'invisible' => 0, 'icon' => $icon, 'action' => 2, 'lastactivity' => $lasttime);
			$whosonline[] = $memberlist;
		}
		
		if ($multiple > 1) {
			$posts = $posts ? $posts * $multiple : $multiple;
			$onlineinfo[0] = $onlineinfo[0] * $multiple;
			$_G['cache']['userstats']['totalmembers'] = $_G['cache']['userstats']['totalmembers'] * $multiple;
			foreach(unserialize($this->changefid) as $forumnum) {
				$forumlist[$forumnum]['todayposts'] = $forumlist[$forumnum]['todayposts'] ? $forumlist[$forumnum]['todayposts'] * $multiple : $forumlist[$forumnum]['posts'] % 14;
				if($forumlist[$forumnum]['todayposts']) {
					$forumlist[$forumnum]['folder'] = 'class="new"';
					$forumlist[$forumnum]['lastpost']['dateline'] = dgmdate($_G['timestamp'] - rand(0, 100) * 60, 't');
				}
				$forumlist[$forumnum]['posts'] = $forumlist[$forumnum]['posts'] * $multiple;
			}
			$todayposts = 0;
			$posts = 0;
			foreach($forumlist as $forumnum => $value) {
				$todayposts = $todayposts + $forumlist[$forumnum]['todayposts'];
				$posts = $posts + $forumlist[$forumnum]['posts'];
			}
			$postdata[0] = $postdata[0] ? $postdata[0] * $multiple : $multiple;
		}
		
        return;
    }

    function forumdisplay_top_output() {
        global $_G;
        if ($this->multiple > 1) {
            foreach(unserialize($this->changefid) as $forumnum) {
                if( $forumnum == $_G['fid']) {
                    $_G['forum']['todayposts'] = $_G['forum']['todayposts'] ? $_G['forum']['todayposts'] * $this->multiple : $_G['forum']['posts'] % 14;
                }
            }
        }
        return;
    }
}
?>