<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: table_plugin_guessulike_relatethread_cache.php 30077 2012-05-09 07:07:56Z chenmengshu $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_plugin_guessulike_relatethread_cache extends discuz_table
{
	public function __construct() {
		$this->_table = 'plugin_guessulike_relatethread_cache';
		$this->_pk    = 'tid';
		$this->_pre_cache_key = 'plugin_guessulike_relatethread_cache_';
		$this->_cache_ttl = 600;//note »º´æ10·ÖÖÓ

		parent::__construct();
	}
	
	public function fetch($id){
		if($this->_allowmem) {
			return $this->fetch_cache($id);
		} else {
			return parent::fetch($id);
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