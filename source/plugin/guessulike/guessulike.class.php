<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: guessulike.class.php 32335 2012-12-26 08:46:31Z chenmengshu $
 */

class plugin_guessulike{
	function common() {
		if (CURSCRIPT == 'forum' && (CURMODULE == 'forumdisplay' || CURMODULE == 'viewthread')) {
			$this->_forumlike();
		}
		if (CURSCRIPT == 'forum' && CURMODULE == 'index' && $_GET['guessulike_ac']) {
			$this->_keywordSettings();
		}
		if (CURSCRIPT == 'forum' && CURMODULE == 'index' && $_GET['guessulike_ajax']) {
			global $_G;
			
			$uid = $_G['uid'] ? $_G['uid'] : 0;
			include_once template('guessulike:global_footer');
			$threads = getGuessULike($uid, true);
			include template('common/header');
			echo tpl_guessulike_guessfloatajax($threads);
			include template('common/footer');
			exit;
		}
	}
	
	function global_footer() {
		global $_G;
		
		$data = '';
		
		if($_G['cache']['plugin']['guessulike']['recentlyforum'] && CURSCRIPT == 'forum' && (CURMODULE == 'forumdisplay' || CURMODULE == 'viewthread')) {
			$rForumList = array_keys(C::t('#guessulike#plugin_guessulike_forumlike')->fetch_all_fid_by_uid($_G['uid'], 5));
			
			if($rForumList) {
				loadcache('forums');
				foreach($rForumList as $sKey => $sForum) {
					if($sForum && $_G['cache']['forums'][$sForum]) {
						$rForumList[$sKey] = array('id' => $sForum, 'name' => dhtmlspecialchars($_G['cache']['forums'][$sForum]['name']));
					} else {
						unset($rForumList[$sKey]);
					}
				}
				include_once template('guessulike:global_footer');
				$data = tpl_guessulike_viewthread_footer($rForumList);
			}
		}
		if($_G['cache']['plugin']['guessulike']['floatenable'] && CURSCRIPT == 'forum' && CURMODULE == 'viewthread') {
			$uid = $_G['uid'] ? $_G['uid'] : 0;
			if($uid || $_G['cache']['plugin']['guessulike']['guestenable']) {
				include_once template('guessulike:global_footer');
				$data .= tpl_guessulike_guessfloat();
			}
		}
		return $data;
	}

	function _keywordSettings($output = true) {
		global $_G;
		
		if(!$_G['uid']) {
			return false;
		}
		$maxKwNum = 5; //最多关键词
		$tips = '';
		
		$data = C::t('#guessulike#plugin_guessulike_user_keywords')->fetch($_G['uid']);
		if(trim($data['keywords'])) {
			$kwdata = explode(',', $data['keywords']);
		} else {
			$kwdata = array();
		}
		$keyword = str_replace(',', ' ', $_GET['keyword']);
		if($_GET['guessulike_ac'] == 'add') {
			if(strlen($keyword) > 20 || !trim($keyword)) {
				$tips = lang('plugin/guessulike', 'keyword_len_exceed');
			} elseif(!in_array($keyword, $kwdata) && count($kwdata) < $maxKwNum) {
				$kwdata[] = $keyword;
				C::t('#guessulike#plugin_guessulike_user_keywords')->insert(array('uid' => $_G['uid'], 'keywords' => implode(',', $kwdata)), false, true);
			} else {
				$tips = lang('plugin/guessulike', 'keyword_exceed', array('maxKwNum' => $maxKwNum));
			}
		} elseif($_GET['guessulike_ac'] == 'del') {
			foreach($kwdata as $key=>$kw) {
				if($kw == $keyword) {
					unset($kwdata[$key]);
				}
			}
			if(!$kwdata) {
				C::t('#guessulike#plugin_guessulike_user_keywords')->delete($_G['uid']);
			} else {
				C::t('#guessulike#plugin_guessulike_user_keywords')->insert(array('uid' => $_G['uid'], 'keywords' => implode(',', $kwdata)), false, true);
			}
		}
		
		if($output !== false) {
			$searchResult = array();
			foreach($kwdata as &$kw) {
				$searchResult += $this->getRelatedThreadsTao($kw, 1, 10);
				$kw = dhtmlspecialchars($kw);
			}
			if ($searchResult){
				$maxresult = $_G['cache']['plugin']['guessulike']['keywordmaxthreads'];
				$searchResult = array_keys($searchResult);
			//	$searchResult = array(19,32,33,34,35,36,37,37);//伪造数据
				if(count($searchResult) > $maxresult) {
					$searchResult = array_slice($searchResult, 0, $maxresult);
				}
				$threads = C::t('forum_thread')->fetch_all($searchResult);
				krsort($threads);
				
				require_once libfile('function/search');

				if($threads) {
					if(in_array('forum_viewthread', $_G['setting']['rewritestatus'])) {
						$rewrite = true;
					}
					loadcache('forums');
					foreach($threads as $threadKey=>&$thread) {
						if($thread['displayorder'] < 0) {
							unset($threads[$threadKey]);
							continue;
						}
						$thread['authoravatar'] = avatar($thread['authorid'], 'small');
						$thread['forumname'] = $_G['cache']['forums'][$thread['fid']]['name'];
						$thread['subject'] = bat_highlight($thread['subject'], implode(' ', $kwdata));
						$thread['urllink'] = $rewrite ? rewriteoutput('forum_viewthread', 1, '', $thread['tid'], 1, '') : "forum.php?mod=viewthread&tid={$thread['tid']}";
						if($_G['cache']['plugin']['guessulike']['tracklink']) {
							$thread['urllink'] .= ((strpos($thread['urllink'], '?') !== false) ? "&" : '?').'from=subscribelink';
						}
					}
				}
			}
			include_once template('guessulike:module');
			include template('common/header');
			echo tpl_keyword_list($kwdata, $tips, $threads);
			include template('common/footer');
			dexit();
		}
	}
	
	public function getRelatedThreadsTao($keyword, $page, $tpp, $excludeForumIds = '', $cache = false) {
		global $_G;

		$sId = $_G['setting']['my_siteid'];
		$threadlist = $result = array();
		if($sId) {
			if($cache === true) {
				$kname = 'search_recommend_guessulike_'.$keyword.'_'.$page.'_'.$excludeForumIds;
				loadcache($kname);
			}
			if(isset($_G['cache'][$kname]['ts']) && (TIMESTAMP - $_G['cache'][$kname]['ts'] <= 21600)) {
				$threadlist = $_G['cache'][$kname]['result'];
			} else {

				$apiUrl = 'http://api.discuz.qq.com/search/discuz/tao?';
				$params = array(
					'sId' => $sId,
					'q' => $keyword,
					'tpp' => $tpp,
					'excludeForumIds' => $excludeForumIds,
					'page' => $page ? $page : 1,
					'clientIp' => $_G['clientip'],
					'timeLength' => 1,
					'scope' => 4
				);

				$utilService = Cloud::loadClass('Service_Util');
				$response = dfsockopen($apiUrl.$utilService->generateSiteSignUrl($params), 0, '', '', false, $_G['setting']['cloud_api_ip']);
				// debug 调试暂时先用unserialize
				require_once libfile('class/xml');
				$result = (array) xml2array($response);
				
				if($result['result']['data']) {
					foreach ($result['result']['data'] as $sPost) {
						$threadlist[$sPost['tThreadId']] = $sPost['tThreadId'];
					}
				}

				if($cache === true && isset($result['status']) && $result['status'] == 0) {
					save_syscache($kname, array('ts' => TIMESTAMP, 'result' => $threadlist));
				}
				if($result['status'] != 0) {
					$result = null;
				}
			}
		}

		return $threadlist;
	}
	
	function _forumlike() {
		global $_G;
		
		$_disabledforums = unserialize($_G['cache']['plugin']['guessulike']['disabledforums']);
		if(!$_G['uid'] || !$_G['fid'] || in_array($_G['fid'], $_disabledforums) || $_G['basescript'] == 'group') {
			return false;
		}
		
		$forumLike = C::t('#guessulike#plugin_guessulike_forumlike')->fetch_by_uid_fid($_G['uid'], $_G['fid']);
		$weekday = date('w', TIMESTAMP) + 1;
		if ($forumLike) {
			if ((TIMESTAMP - $forumLike['dateline']) > 600) {
				C::t('#guessulike#plugin_guessulike_forumlike')->increase_hot($_G['uid'], $_G['fid'], 1, TIMESTAMP, $weekday, $forumLike);
			}
		} else {
			$data = array(
					'uid' => $_G['uid'],
					'fid' => $_G['fid'],
					'hot'.$weekday => 1,
					'hot' => 1,
					'dateline' => TIMESTAMP
				);
			C::t('#guessulike#plugin_guessulike_forumlike')->insert($data, false, true);
		}
	}
	
	/**
	 * 删除主题回调
	 */
	public function deletethread($params) {
		$tids = $params['param'][0];
		$step = $params['step'];

		if($step == 'delete' && is_array($tids)) {
			C::t('#guessulike#plugin_guessulike_threads')->delete($tids);
			C::t('#guessulike#plugin_guessulike_user_thread')->delete($tids);
		}
	}
}

class plugin_guessulike_forum extends plugin_guessulike {

	function viewthread_endline_output() {
		global $_G;
		
		if($_G['page'] != 1 || $_GET['inajax'] || !$_G['cache']['plugin']['guessulike']['topicmaxthreads']) {
			return array();
		}
		
		//读取缓存
		$cache = C::t('#guessulike#plugin_guessulike_relatethread_cache')->fetch($_G['tid']);
		
		//不存在情况下从数据库中读取并建立缓存
		if(!($cache && TIMESTAMP - $cache['dateline'] < 600)) {
			$relatedUsers = array_keys(C::t('#guessulike#plugin_guessulike_user_thread')->fetch_all_uid_by_tid($_G['tid'], 0, 20));
			$tids = C::t('#guessulike#plugin_guessulike_user_thread')->fetch_all_by_uid($relatedUsers, $_G['cache']['plugin']['guessulike']['topicmaxthreads'] + 1, 0);
			unset($tids[$_G['tid']]);
			$tids = array_keys($tids);
			if(!$tids) {
				C::t('#guessulike#plugin_guessulike_relatethread_cache')->insert(array('tid' => $_G['tid'], 'dateline' => TIMESTAMP, 'threads' => ''), false, true);
				return array();
			}
			$data = array('tid' => $_G['tid'], 'dateline' => TIMESTAMP, 'threads' => implode(' ', $tids));
			
			C::t('#guessulike#plugin_guessulike_relatethread_cache')->insert($data, false, true);
		} else {
			if(!$cache['threads']) {
				return array();
			}
			$tids = explode(' ', $cache['threads']);
		}
		
		if(count($tids) > $_G['cache']['plugin']['guessulike']['topicmaxthreads']) {
			$tids = array_slice($tids, 0, $_G['cache']['plugin']['guessulike']['topicmaxthreads']);
		}

		$threads = C::t('forum_thread')->fetch_all($tids);

		if($threads) {
			uasort($threads, 'guessulike_sort_lastpost');
			
			if(in_array('forum_viewthread', $_G['setting']['rewritestatus'])) {
				$rewrite = true;
			}
			loadcache('forums');
			foreach($threads as $threadKey=>&$thread) {
				if($thread['displayorder'] < 0) {
					unset($threads[$threadKey]);
					continue;
				}
				$thread['authoravatar'] = avatar($thread['authorid'], 'small');
				$thread['forumname'] = $_G['cache']['forums'][$thread['fid']]['name'];
				$thread['urllink'] = $rewrite ? rewriteoutput('forum_viewthread', 1, '', $thread['tid'], 1, '') : "forum.php?mod=viewthread&tid={$thread['tid']}";
				if($_G['cache']['plugin']['guessulike']['tracklink']) {
					$thread['urllink'] .= ((strpos($thread['urllink'], '?') !== false) ? "&" : '?').'from=threadlink';
				}
			}
		}
		
		include_once template('guessulike:module');
		return array(0 => tpl_guessulike_viewthread($threads));
	}
	
	function index_top_output() {
		global $_G;
		
		if((!$_G['uid'] && !$_G['cache']['plugin']['guessulike']['guestenable']) || $_GET['gid']) {
			return '';
		} else {
			$uid = $_G['uid'] ? $_G['uid'] : 0;
		}
		if(!$_G['cache']['plugin']['guessulike']['datalifetime']) {
			$_G['cache']['plugin']['guessulike']['datalifetime'] = 24;
		}

		loadcache('forums');
		
		$threads = getGuessULike($uid);
		
		if($threads) {
			$_G['guessulike_thread_enable'] = true;
		}
		
		$cloud_apps = (array)unserialize($_G['setting']['cloud_apps']);
		$search_status = $cloud_apps['search']['status'] == 'normal' ? TRUE : FALSE;
		
		if(!$search_status) {
			$_G['cache']['plugin']['guessulike']['switcherkeyword'] = false;
		}
		
		if($_G['cache']['plugin']['guessulike']['recentlyforum'] && $uid) {
			$rForumList = array_keys(C::t('#guessulike#plugin_guessulike_forumlike')->fetch_all_fid_by_uid($uid, $_G['cache']['plugin']['guessulike']['recentlyforum']));
		} else {
			$rForumList = null;
		}
		if($rForumList) {
			array_unshift($rForumList, null);
		}
		
		include_once template('guessulike:module');
		return tpl_guessulike_index_top_output($threads, $rForumList);
	}
	
	function index_middle_output() {
		global $_G, $forumlist, $favforumlist, $forum_favlist, $catlist, $gid;
		
		//分区页不破坏原有结构
		if($gid || !$_G['cache']['plugin']['guessulike']['controlflist'] || !$_G['guessulike_thread_enable']) {
			return '';
		}
		
		$guessulike_forum_favlist = $forum_favlist;
		$guessulike_catlist = $catlist;
		
		$forum_favlist = $catlist = array();
		
		foreach($favforumlist as $key=>&$forum) {
			$forum['forumurl'] = !empty($forum['domain']) && !empty($_G['setting']['domain']['root']['forum']) ? 'http://'.$forum['domain'].'.'.$_G['setting']['domain']['root']['forum'] : 'forum.php?mod=forumdisplay&fid='.$forum['fid'];
		}
		foreach($forumlist as $key=>&$forum) {
			$forum['forumurl'] = !empty($forum['domain']) && !empty($_G['setting']['domain']['root']['forum']) ? 'http://'.$forum['domain'].'.'.$_G['setting']['domain']['root']['forum'] : 'forum.php?mod=forumdisplay&fid='.$forum['fid'];
		}		
		foreach($guessulike_catlist as $key=>&$cat) {
			$cat['caturl'] = !empty($cat['domain']) && !empty($_G['setting']['domain']['root']['forum']) ? 'http://'.$cat['domain'].'.'.$_G['setting']['domain']['root']['forum'] : '';
		}		
		include_once template('guessulike:module');
		return tpl_guessulike_newforumlist($guessulike_forum_favlist, $guessulike_catlist, $favforumlist, $forumlist);
	}

	
	function post_message($params) {
		global $_G, $thread, $author;

		$_disabledforums = unserialize($_G['cache']['plugin']['guessulike']['disabledforums']);
		$isanonymous = $_G['group']['allowanonymous'] && $_GET['isanonymous'] ? 1 : 0;
		if (!$_G['uid'] || in_array($_G['fid'], $_disabledforums) || !$params || $isanonymous) {
			return;
		}
		
		$param = $params['param'];
		if ($param[0] == 'post_reply_succeed') {
			if($_G['uid'] != $thread['authorid'] && $thread['authorid'] && $thread['author']) {
				//检查并更新用户跟作者关系
				$userLike = C::t('#guessulike#plugin_guessulike_reply_user')->fetch_by_uid_targetuid($_G['uid'], $thread['authorid']);
				if ($userLike) {
					if ((TIMESTAMP - $userLike['dateline']) > 600) {
						C::t('#guessulike#plugin_guessulike_reply_user')->increase_hot($_G['uid'], $thread['authorid'], 1, TIMESTAMP);
					}
				} else {
					$data = array(
							'uid' => $_G['uid'],
							'targetuid' => $thread['authorid'],
							'count' => 1,
							'dateline' => TIMESTAMP
						);
					C::t('#guessulike#plugin_guessulike_reply_user')->insert($data, false, true);
				}
			}
		}
		
		if ($param[0] == 'post_reply_succeed' || $param[0] == 'post_newthread_succeed') {
			// 更新用户和主题关系库
			if($param[0] == 'post_reply_succeed') {
				$tid = $thread['tid'];
				$replies = $thread['replies'] + 1;
				$dateline = $thread['dateline'];
				$type = 0;	//0为回复
			} else {
				global $tid;
				$replies = 0;
				$type = 1;	//1为主题
				$dateline = TIMESTAMP;
			}
			$data = array(
					'uid' => $_G['uid'],
					'tid' => $tid,
					'type' => $type,		
					'dateline' => TIMESTAMP
			);
			C::t('#guessulike#plugin_guessulike_user_thread')->insert($data, false, true);
			
			if(TIMESTAMP - $dateline < ($_G['cache']['plugin']['guessulike']['datalifetime'] * 3600)) {
				// 更新排序用主题表
				$data = array(
						'tid' => $tid,
						'fid' => $_G['fid'],
						'replies' => $replies,
						'dateline' => $dateline
				);
				C::t('#guessulike#plugin_guessulike_threads')->insert($data, false, true);
			}
		}
	}

}

function guessulike_sort_lastpost($a, $b) {
	$a = $a['lastpost'];
	$b = $b['lastpost'];
	
    if ($a == $b) {
        return 0;
    }
    return ($a > $b) ? -1 : 1;
}

/* 
 * 获取猜你喜欢数据
 * $uid 用户uid
 * $threadMode 是否为主题下浮动模式
 */

function getGuessULike($uid, $threadMode = false) {
	global $_G;
	
	loadcache('forums');
	$getTime = $_G['cache']['plugin']['guessulike']['datalifetime'] * 3600;
	$tids = array();
	
	//读取缓存
	$cache = C::t('#guessulike#plugin_guessulike_user_cache')->fetch($uid);
	
	//不存在情况下从数据库中读取并建立缓存
	if(!($cache && TIMESTAMP - $cache['dateline'] < 600)) {
		if($uid) {
			$forums = array_keys(C::t('#guessulike#plugin_guessulike_forumlike')->fetch_all_fid_by_uid($uid, 3));
			$targets = array_keys(C::t('#guessulike#plugin_guessulike_reply_user')->fetch_all_targetuid_by_uid($uid, 10));
		} else {
			//游客状态下，限制版块
			$forums = array();
			foreach($_G['cache']['forums'] as $fid => $forum) {
				if($forum['type'] != 'group' && $forum['status'] > 0 && !$forum['viewperm'] && !$forum['havepassword']) {
					$forums[] = $fid;
				}
			}
		}
		if(!$forums && !$targets) {
			C::t('#guessulike#plugin_guessulike_user_cache')->insert(array('uid' => $uid, 'dateline' => TIMESTAMP, 'tids' => ''), false, true);
		} else {
			if($uid) {
				$memberThreads = C::t('#guessulike#plugin_guessulike_user_thread')->fetch_all_by_uid($targets, intval($_G['cache']['plugin']['guessulike']['indexmaxthreads'] / 2));
			} else {
				$memberThreads = array();
			}
			
			$forumGetNums = $_G['cache']['plugin']['guessulike']['indexmaxthreads'] - count($memberThreads);
			if($forums) {
				if($_G['cache']['plugin']['guessulike']['displaytype']) {
					$forumThreads = C::t('#guessulike#plugin_guessulike_threads')->fetch_all_by_fid_dateline($forums, $forumGetNums);
				} else {
					$forumThreads = C::t('#guessulike#plugin_guessulike_threads')->fetch_all_by_fid($forums, TIMESTAMP - $getTime, $forumGetNums);
				}
			} else {
				$forumThreads = array();
			}
			
			$tids = array_keys($forumThreads) + array_keys($memberThreads);
			$data = array('uid' => $uid, 'dateline' => TIMESTAMP, 'tids' => implode(' ', $tids));
			
			C::t('#guessulike#plugin_guessulike_user_cache')->insert($data, false, true);
		}
	} else {
		if($cache['tids']) {
			$tids = explode(' ', $cache['tids']);
		}
	}
	
	if($tids) {
		
		if($threadMode && count($tids) > 2) {
			$randtids = array_rand($tids, 2);
			$newtids = array();
			foreach($randtids as $rtid) {
				$newtids[] = $tids[$rtid];
			}
			$tids = &$newtids;
		}
		
		$threads = C::t('forum_thread')->fetch_all($tids);
		if($threads) {
			uasort($threads, 'guessulike_sort_lastpost');
			
			if(in_array('forum_viewthread', $_G['setting']['rewritestatus'])) {
				$rewrite = true;
			}
			foreach($threads as $threadKey=>&$thread) {
				if($thread['displayorder'] < 0) {
					unset($threads[$threadKey]);
					continue;
				}
				$thread['authoravatar'] = avatar($thread['authorid'], 'small');
				$thread['forumname'] = $_G['cache']['forums'][$thread['fid']]['name'];
				$thread['urllink'] = $rewrite ? rewriteoutput('forum_viewthread', 1, '', $thread['tid'], 1, '') : "forum.php?mod=viewthread&tid={$thread['tid']}";
				if($_G['cache']['plugin']['guessulike']['tracklink']) {
					$thread['urllink'] .= ((strpos($thread['urllink'], '?') !== false) ? "&" : '?').'from=indexlink';
				}
			}
		}
		
	}
	return $threads;
}