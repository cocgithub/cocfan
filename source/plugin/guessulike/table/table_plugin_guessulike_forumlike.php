<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: table_plugin_guessulike_forumlike.php 31136 2012-07-19 02:05:04Z chenmengshu $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_plugin_guessulike_forumlike extends discuz_table
{
	public function __construct() {
		$this->_table = 'plugin_guessulike_forumlike';
		$this->_pk    = '';
		$this->_pre_cache_key = 'plugin_guessulike_forumlike_';
		$this->_cache_ttl = 604800;//note »º´æ7Ìì

		parent::__construct();
	}
	
	public function fetch_all_fid_by_uid($uid, $limit) {
		return DB::fetch_all('SELECT fid FROM %t WHERE uid=%d ORDER BY hot DESC LIMIT %d', array($this->_table, $uid, $limit), 'fid');
	}
	
	public function fetch_by_uid_fid($uid, $fid) {
		if($this->_allowmem) {
			$data = $this->fetch_cache($uid.'_'.$fid);
		}
		if(!$data) {
			$data = DB::fetch_first('SELECT * FROM %t WHERE uid=%d AND fid=%d', array($this->_table, $uid, $fid));
			if($this->_allowmem) {
				$this->store_cache($uid.'_'.$fid, $data);
			}
		}
		return $data;
	}
	
	public function increase_hot($uid, $fid, $delta, $dateline, $weekday, $oldData) {
		if(!is_numeric($weekday) || $weekday < 1 || $weekday > 7) {
			return false;
		}
		if($this->_allowmem) {
			$data = $this->fetch_cache($uid.'_'.$fid);
			$data = $this->processWeekData($data, $dateline, $delta, $weekday);
			$data['dateline'] = $dateline;
			$this->store_cache($uid.'_'.$fid, $data);
		}
		
		$oldData = $this->processWeekData($oldData, $dateline, $delta, $weekday);
	
		return DB::query('UPDATE %t SET hot=%d,hot'.$weekday.'=%d,dateline=%d WHERE uid=%d AND fid=%d', array($this->_table, $oldData['hot'], $oldData['hot'.$weekday], $dateline, $uid, $fid));
	}
	
	public function delete_outdated($timestamp) {
		return DB::query('DELETE FROM %t WHERE dateline < '.intval($timestamp), array($this->_table));
	}
	
	private function processWeekData($data, $dateline, $delta, $weekday) {
		if(date('Ymd', $data['dateline']) != date('Ymd', $dateline)) {
			$data['hot'.$weekday] = $delta;
		} else {
			$data['hot'.$weekday] += $delta;
		}
		$data['hot'] = $data['hot1']+$data['hot2']+$data['hot3']+$data['hot4']+$data['hot5']+$data['hot6']+$data['hot7'];
		
		return $data;
	}

}

?>