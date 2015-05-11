<?php

/**
 *      [Discuz!] (C)2001-2099 cocfan.com.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: table_forum_thread_ext.php 31121 2015-03-18 14:01:56Z luorong $
 */

if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

class table_forum_thread_ext extends table_forum_thread
{
    public function fetch_by_fid_closetid($fid,$closetid) {
        if(empty($fid) || empty($closetid)) {
            return array();
        }
        return DB::fetch_all("SELECT * FROM %t WHERE fid=%d and closed=%d and displayorder>=0",array($this->_table,$fid,$closetid));
    }
}

