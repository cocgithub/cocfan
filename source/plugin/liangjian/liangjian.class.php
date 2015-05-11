<?php

if (! defined ( 'IN_DISCUZ' )) {
	exit ( 'Access Denied' );
}

class plugin_liangjian {
	function __construct() {
	
	}
	function is_ie() {
	$str=preg_match('/MSIE ([0-9]\.[0-9])/',$_SERVER['HTTP_USER_AGENT'],$matches);
	if ($str == 0){
			return 0;
	} else {
		return floatval($matches[1]);
	}
			
}
function is_ff() {
	$str=preg_match('/Firefox/',$_SERVER['HTTP_USER_AGENT'],$matches);
	if ($str == 0){
		return false;
	} else {
		return true;
	}
}
	function global_footerlink(){
		global $_G;
		$config = $_G ['cache'] ['plugin'] ['liangjian'];
		$share1 = $config ['share1'];
		$sharecode1 = $config ['sharecode1'];
			if ($share1) {
				return $sharecode1;
			}
	}
	
}
class plugin_liangjian_forum extends plugin_liangjian {
	function viewthread_bread() {
		global $_G;
		$config = $_G ['cache'] ['plugin'] ['liangjian'];
		$share = $config ['share'];
		$sharecode = $config ['sharecode'];
		if($sharecode){
				$v = $this->is_ie();
				if ($v <= 6 && $v != 0) {
					$sharecode='<div style="float:right;position:relative;">' . $sharecode . '</div>';
				} else if ($v == 7) {
					$sharecode='<div style="float:right;position:relative;">' . $sharecode . '</div>';
				} else if ($this->is_ff()) {
					$sharecode='<div style="float:right;">' . $sharecode . '</div>';
				} else {
					$sharecode='<div style="float:right;position:relative;top:5px;">' . $sharecode . '</div>';
				}
		}
		return $sharecode;
	}
	function post_checkreply() {
		if ($_GET ['topicsubmit']) {
			global $_G;
			$config = $_G ['cache'] ['plugin'] ['liangjian'];
			$wwforums = $config ['wwforums'];
			$wuser = $config ['wuser'];
			$wuser = unserialize ( $wuser );
			$groupid = $_G ['groupid'];
			$wwforums = unserialize ( $wwforums );
			$fid = $_GET ['fid'];
			$settingfile = DISCUZ_ROOT . './data/sysdata/cache_liangjian_setting.php';
			if (file_exists ( $settingfile )) {
				include $settingfile;
			}
			if (file_exists ( DISCUZ_ROOT . './data/cache/cache_liangjian_setting.php' )) {
				$settingfile = DISCUZ_ROOT . './data/cache/cache_liangjian_setting.php';
				include DISCUZ_ROOT . './data/cache/cache_liangjian_setting.php';
			}
			
			if (in_array ( $groupid, $wuser )) {
				if (in_array ( $fid, $wwforums )) {
					if ($liangjian_setting ['allow_thesaurus']) {
						//同义词替换关键代码
						$words = array ();
						$str = str_replace ( "\r", "", $liangjian_setting ['thesaurus_setting'] );
						$arr = explode ( "\n", $str);
						foreach ( $arr as $k => $v ) {
							if($v){
								$str_data = explode ( "=", $v );
								$words += array ("$str_data[0]" => "$str_data[1]" );
							}
						}
						$words=array_unique($words);
						$_GET ['message'] = strtr ( $_GET ['message'], $words ); //返回结果
					}

					if ($liangjian_setting ['allow_title']) {
						//同义词替换关键代码
						$words = array ();
						$str = str_replace ( "\r", "", $liangjian_setting ['thesaurus_setting'] );
						$arr = explode ( "\n",$str);
						foreach ( $arr as $k => $v ) {
							if($v){
								$str_data = explode ( "=", $v );
								$words += array ("$str_data[0]" => "$str_data[1]" );
							}
						}
						$words=array_unique($words);
						$_GET ['subject'] = strtr ( $_GET ['subject'], $words ); //返回结果
					}
				}
			}
		}
	}
}
//门户处理部分代码
class plugin_liangjian_portal extends plugin_liangjian {
	function portalcp_seoreal(){
		if($_GET['articlesubmit']){
			global $_G;
			$config = $_G ['cache'] ['plugin'] ['liangjian'];
			$settingfile = DISCUZ_ROOT . './data/sysdata/cache_liangjian_setting.php';
			if (file_exists ( $settingfile )) {
				include $settingfile;
			}
			if (file_exists ( DISCUZ_ROOT . './data/cache/cache_liangjian_setting.php' )) {
				$settingfile = DISCUZ_ROOT . './data/cache/cache_liangjian_setting.php';
				include DISCUZ_ROOT . './data/cache/cache_liangjian_setting.php';
			}
			
					if ($liangjian_setting ['content_portal']) {
						//同义词替换关键代码
						$words = array ();
						$str = str_replace ( "\r", "", $liangjian_setting ['thesaurus_setting'] );
						$arr = explode ( "\n", $str);
						foreach ( $arr as $k => $v ) {
							if($v){
								$str_data = explode ( "=", $v );
								$words += array ("$str_data[0]" => "$str_data[1]" );
							}
						}
						$words=array_unique($words);
						$_POST['content'] = strtr ( $_POST['content'], $words ); //返回结果
						
					}
					if ($liangjian_setting ['title_portal']) {
						//同义词替换关键代码
						$words = array ();
						$str = str_replace ( "\r", "", $liangjian_setting ['thesaurus_setting']);
						$arr = explode ( "\n",$str);
						foreach ( $arr as $k => $v ) {
							if($v){
								$str_data = explode ( "=", $v );
								$words += array ("$str_data[0]" => "$str_data[1]" );
							}
						}
						$words=array_unique($words);
						$_POST['title'] = strtr ( $_POST['title'], $words ); //返回结果
					}
			
		//同义词替换关键代码

		}
	}
	
}
?>