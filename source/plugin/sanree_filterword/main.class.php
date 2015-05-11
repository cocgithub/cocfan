<?php

/**
 *      [Sanree] (C)2012-2099 Sanree Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: main.class.php $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
class plugin_sanree_filterword {
    function global_header() {
		global $_G;
	}  
}
function dget_headers($url, $format=0) { 
	$headers = array(); 
	$url = parse_url($url); 
	$host = isset($url['host']) ? $url['host'] : ''; 
	$port = isset($url['port']) ? $url['port'] : 80; 
	$path = (isset($url['path']) ? $url['path'] : '/') . (isset($url['query']) ? '?' . $url['query'] : ''); 
	$fp = fsockopen($host, $port, $errno, $errstr, 3); 
	if ($fp) { 
		$hdr = "GET $path HTTP/1.1\r\n"; 
		$hdr .= "Host: $host \r\n"; 
		$hdr .= "Connection: Close\r\n\r\n"; 
		fwrite($fp, $hdr); 
		while (!feof($fp) && $line = trim(fgets($fp, 1024))) 
		{ 
			if ($line == "\r\n") break; 
			list($key, $val) = explode(': ', $line, 2); 
			if ($format) 
				if ($val) $headers[$key] = $val; 
				else $headers[] = $key; 
			else $headers[] = $line; 
		} 
		fclose($fp); 
		return $headers; 
	} 
	return false; 
} 

function dfile_exists($uid, $size='middle') {
	global $_G;
	$ucenterurl= 'http://dx.sanree.com/uc_server/';
	$ucenterurl = empty($ucenterurl) ? $_G['setting']['ucenterurl'] : $ucenterurl;
	$uid = sprintf("%09d", $uid);
	$dir1 = substr($uid, 0, 3);
	$dir2 = substr($uid, 3, 2);
	$dir3 = substr($uid, 5, 2);
	$signurl = $ucenterurl.'/data/avatar/'.$dir1.'/'.$dir2.'/'.$dir3.'/'.substr($uid, -2).($real ? '_real' : '').'_avatar_'.$size.'.jpg';
	$importtxt = @dget_headers($signurl);
	if (is_array($importtxt)) {
		if ($importtxt[0]==='HTTP/1.1 200 OK') {
			return true;
		}
	}
	return false;
}

class plugin_sanree_filterword_forum extends plugin_sanree_filterword{
    function post(){
	    global $_G,$mod;
		if ($_G['adminid']==1) return;		
		$config = $_G['cache']['plugin']['sanree_filterword'];
		$filtermessage = $config['filtermessage'];
		$infoface = $config['infoface'];
		$checkgroups = unserialize($config[checkgroups]);
		if (!$config['isopen']) {
		    return;
		}
		if (!in_array($_G['group']['groupid'],$checkgroups)) {
            return;
		}	
		if ($config['isface']==1) {
			if(!function_exists('uc_check_avatar')) {
				loaducenter();
			}			
			if (!uc_check_avatar($_G['uid'])) {
			     showmessage($infoface,'home.php?mod=spacecp&ac=avatar'); 
			}
		}

		if ((CURSCRIPT == 'forum') && ($mod == 'post') && isset( $_G['gp_message'] ) ) {
		    $action=intval($config[action]);
			$subject = isset($_G['gp_subject']) ? dhtmlspecialchars(censor(trim($_G['gp_subject']))) : '';
			$subject = !empty($subject) ? str_replace("\t", ' ', $subject) : $subject;
			$message = isset($_G['gp_message']) ? censor($_G['gp_message']) : '';
			$filtword=explode("\r\n",$config[filtword]);
			foreach($filtword as $k){
                $k=trim($k);
                if (empty($k)) continue;
				if (preg_match_all('/('.$k.')/i',$subject,$matches)||preg_match_all('/('.$k.')/i',$message,$matches)){
					switch ($action) {
						case 1: 
							showmessage('word_banned', '', array('wordbanned' => $k));
						break;
						case 2: 
							$sql .= "groupid='5', status='-1'";
							DB::query("UPDATE ".DB::table('common_member')." SET $sql WHERE uid='$_G[uid]'");						
							showmessage(lang('plugin/sanree_filterword','stopuid'));
						break;
						/*	
						case 3: 
							$ip = explode('.', $_G['clientip']);
							$addwhere=" where ip1='".$ip[0]."' and ip2='".$ip[1]."' and ip3='".$ip[2]."' and ip4='".$ip[3]."'";
							$result = DB::fetch_first("SELECT * FROM ".DB::table('common_banned').$addwhere);
							if (!$result)
							{
								$expiration = TIMESTAMP + 30 * 86400;
								$data = array(
									'ip1' => $ip[0],
									'ip2' => $ip[1],
									'ip3' => $ip[2],
									'ip4' => $ip[3],
									'admin' => lang('plugin/sanree_filterword','adminname'),
									'dateline' => $_G['timestamp'],
									'expiration' => $expiration,
								);
								DB::insert('common_banned', $data);
								require_once libfile('function/cache');	
								updatecache('ipbanned');
							}	
							DB::query("UPDATE ".DB::table('common_member')." SET groupid='6' WHERE uid='".$_G["uid"]."'");
							DB::query("UPDATE ".DB::table('common_session')." SET groupid='6' WHERE ('".$ip[0]."'='-1' OR ip1='".$ip[0]."') AND ('".$ip[1]."'='-1' OR ip2='".$ip[1]."') AND ('".$ip[2]."'='-1' OR ip3='".$ip[2]."') AND ('".$ip[3]."'='-1' OR ip4='".$ip[3]."')");															
							showmessage(lang('plugin/sanree_filterword','stopip'));
						break;
						*/											
					}
					
				}
			}
		}
	}	   
}
?>
