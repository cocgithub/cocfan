<?php

/**
 *      [Discuz!] (C)2001-2099 cocfan.com.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: table_forum_groupuser_ext.php 31121 2015-03-18 14:01:56Z luorong $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_forum_groupuser_ext extends table_forum_groupuser
{
	public function fetch_allinfo_by_uids($uids) {
		if(empty($uids)) {
			return array();
		}
		return DB::fetch_all("SELECT * FROM %t WHERE ".DB::field('uid', $uids),array($this->_table));
	}
}

