<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: table_plugin_guessulike_user_keywords.php 30227 2012-05-17 03:14:01Z chenmengshu $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_plugin_guessulike_user_keywords extends discuz_table
{
	public function __construct() {
		$this->_table = 'plugin_guessulike_user_keywords';
		$this->_pk    = 'uid';
		$this->_pre_cache_key = 'plugin_guessulike_user_keywords_';
		$this->_cache_ttl = 86400;//note »º´æ10·ÖÖÓ

		parent::__construct();
	}
	
	public function insert($data, $return_insert_id = false, $replace = false, $silent = false) {
		$this->clear_cache($data[$this->_pk]);
		parent::insert($data, $return_insert_id, $replace, $silent);
	}
	
}

?>