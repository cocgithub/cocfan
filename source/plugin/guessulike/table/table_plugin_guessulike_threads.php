<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: table_plugin_guessulike_threads.php 31118 2012-07-18 02:55:47Z chenmengshu $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_plugin_guessulike_threads extends discuz_table
{
	public function __construct() {
		$this->_table = 'plugin_guessulike_threads';
		$this->_pk    = 'tid';

		parent::__construct();
	}
	
	public function fetch_all_by_fid($fids, $dateline, $limit) {
		return DB::fetch_all('SELECT tid FROM %t WHERE fid IN(%n) AND dateline>%d ORDER BY replies DESC LIMIT %d', array($this->_table, $fids, $dateline, $limit), $this->_pk);
	}

	public function fetch_all_by_fid_dateline($fids, $limit) {
		return DB::fetch_all('SELECT tid FROM %t WHERE fid IN(%n) ORDER BY dateline DESC LIMIT %d', array($this->_table, $fids, $limit), $this->_pk);
	}
	
	public function delete_outdated($timestamp) {
		return DB::query('DELETE FROM %t WHERE dateline < '.intval($timestamp), array($this->_table));
	}
}

?>