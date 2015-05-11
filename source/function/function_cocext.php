<?php

/**
 *      [Discuz!] (C)2001-2099 cocfan.com
 *      其实就是专门给coc新增功能写的功能类
 *
 *      $Id: function_cocext.php 2015-03-18 14:10:26Z luorong $
 */

if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

/*
 * 传入帖子列表，输出回复作者对应的部落信息
 */
function getGroupInfo($postlist){
    $uids = array();
    //提取用户uid
    foreach($postlist as $post){
        $uids[] = $post['authorid'];
    }
    $userGroups = C::t('forum_groupuser')->fetch_allinfo_by_uids($uids);
    $fids = array();
    foreach($userGroups as $group){
        $fids[] = $group['fid'];
    }
    $groupInfos = C::t('forum_forum')->fetch_all_name_by_fid($fids);
    $userGroupInfos = array();
    foreach($userGroups as $group){
        $fid = $group['fid'];
        $newGroup = array(
            'fid' => $group['fid'],
            'name' => $groupInfos[$fid]['name'],
            'level' => intval($group['level']),
        );
        if(isset($userGroupInfos[$group['uid']]) && $userGroupInfos[$group['uid']]['level'] > $newGroup['level']){
            $userGroupInfos[$group['uid']] = $newGroup;
        }else if(!isset($userGroupInfos[$group['uid']])){
            $userGroupInfos[$group['uid']] = $newGroup;
        }
    }
    return $userGroupInfos;
}

function handleThreadForGroup(&$threadlist){
    $pubforumtids = array();
    foreach($threadlist as $thread){
        if($thread['closed'] > 1 && $thread['isgroup'] == 0){
            $pubforumtids[] = $thread['closed'];
        }
    }
    $pThreads = C::t('forum_thread')->fetch_all_by_tid($pubforumtids);
    foreach($threadlist as &$thread){
        if($thread['closed'] > 1 && $thread['isgroup'] == 0){
            $tid = $thread['closed'];
            if(isset($pThreads[$tid]) && $pThreads[$tid]['isgroup'] < 1){
                $newThread = $pThreads[$tid];
                $thread['dblastpost'] = $newThread['lastpost'];
                $thread['lastpost'] = dgmdate($newThread['lastpost'], 'u');
                $thread['allreplies'] = $newThread['replies'] + $newThread['comments'];
                $thread['lastposterenc'] = rawurlencode($newThread['lastposter']);
                $thread['redirect'] = 1;
            }
        }
    }
}