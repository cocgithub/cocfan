<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: table_plugin_guessulike_user_cache.php 32258 2012-12-10 05:30:34Z chenmengshu $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_plugin_guessulike_user_cache extends discuz_table
{
	public function __construct() {
		$this->_table = 'plugin_guessulike_user_cache';
		$this->_pk    = 'uid';
		$this->_pre_cache_key = 'plugin_guessulike_user_cache_';
		$this->_cache_ttl = 600;//note »º´æ10·ÖÖÓ

		parent::__construct();
	}
	
	public function fetch($id){
		if($this->_allowmem) {
			return $this->fetch_cache($id);
		} else {
			$data = DB::fetch_first('SELECT * FROM '.DB::table($this->_table).' WHERE '.DB::field($this->_pk, $id));
			return $data;
		}
	}
	
	public function insert($data, $return_insert_id = false, $replace = false, $silent = false) {
		if($this->_allowmem) {
			$this->store_cache($data[$this->_pk], $data);
		} else {
			parent::insert($data, $return_insert_id, $replace, $silent);
		}
	}
	
	public function delete_outdated($timestamp) {
		if(!$this->_allowmem) {
			return DB::query('DELETE FROM %t WHERE dateline < '.intval($timestamp), array($this->_table));
		}
	}
}

?>