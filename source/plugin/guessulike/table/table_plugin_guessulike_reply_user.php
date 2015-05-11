<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: table_plugin_guessulike_reply_user.php 30221 2012-05-17 02:33:43Z chenmengshu $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_plugin_guessulike_reply_user extends discuz_table
{
	public function __construct() {
		$this->_table = 'plugin_guessulike_reply_user';
		$this->_pk    = '';
		$this->_pre_cache_key = 'plugin_guessulike_reply_user_';
		$this->_cache_ttl = 86400;//note »º´æ24Ð¡Ê±

		parent::__construct();
	}
	
	public function fetch_all_targetuid_by_uid($uid, $limit) {
		return DB::fetch_all('SELECT targetuid FROM %t WHERE uid=%d ORDER BY count DESC LIMIT %d', array($this->_table, $uid, $limit), 'targetuid');
	}
	
	public function fetch_by_uid_targetuid($uid, $targetuid) {
		if($this->_allowmem) {
			$data = $this->fetch_cache($uid.'_'.$targetuid);
		}
		if(!$data){
			$data = DB::fetch_first('SELECT * FROM %t WHERE uid=%d AND targetuid=%d', array($this->_table, $uid, $targetuid));
			if($this->_allowmem) {
				$this->store_cache($uid.'_'.$targetuid, $data);
			}
		}
		return $data;
	}

	public function increase_hot($uid, $targetuid, $delta, $dateline) {
		if($this->_allowmem) {
			$data = $this->fetch_cache($uid.'_'.$targetuid);
			$data['count'] += $delta;
			$data['dateline'] = $dateline;
			$this->store_cache($uid.'_'.$targetuid, $data);
		}
		return DB::query('UPDATE %t SET count=count+%d,dateline=%d WHERE uid=%d AND targetuid=%d', array($this->_table, $delta, $dateline, $uid, $targetuid));
	}
	
	public function delete_outdated($timestamp) {
		return DB::query('DELETE FROM %t WHERE dateline < '.intval($timestamp), array($this->_table));
	}

}

?>