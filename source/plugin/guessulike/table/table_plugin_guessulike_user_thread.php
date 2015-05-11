<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: table_plugin_guessulike_user_thread.php 30355 2012-05-24 04:16:02Z chenmengshu $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_plugin_guessulike_user_thread extends discuz_table
{
	public function __construct() {
		$this->_table = 'plugin_guessulike_user_thread';
		$this->_pk    = 'tid';

		parent::__construct();
	}
	
	public function fetch_all_by_uid($uids, $limit, $type = '') {
		if($type === '') {
			$typesql = 'type IN (0,1)';
		} else {
			$typesql = 'type='.intval($type);
		}
		return DB::fetch_all('SELECT tid FROM %t WHERE uid IN(%n) AND %i ORDER BY dateline DESC LIMIT %d', array($this->_table, $uids, $typesql, $limit), 'tid');
	}
	
	public function fetch_all_uid_by_tid($tid, $type, $limit) {
		return DB::fetch_all('SELECT uid FROM %t WHERE tid=%d AND type=%d ORDER BY dateline DESC LIMIT %d', array($this->_table, $tid, $type, $limit), 'uid');
	}
	
	public function delete_outdated($timestamp) {
		return DB::query('DELETE FROM %t WHERE dateline < '.intval($timestamp), array($this->_table));
	}
}

?>