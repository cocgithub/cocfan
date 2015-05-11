<?php
/*
 *	nimba_security (C)2012 AiLab Inc.
 *	nimba_security Made By Nimba, Team From AiLab.org
 *  contact me QQ:594941227 E-mail:lih062624@126.com
 *	File: security.class.php  AiLab.cn 2012-9-8 19:11$
 */
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class plugin_nimba_security {
    function common(){
		loadcache('plugin');
		global $_G;
		$vars = $_G['cache']['plugin']['nimba_security'];
		$long=empty($vars['long'])? 5:$vars['long'];
		$num=empty($vars['num'])? 1:$vars['num'];
		$groups=unserialize($vars['groups']);
		if(empty($groups[0])) $groups[0]=9999;//防止"空"被选上
		$text = implode(",",$groups);
		if(!empty($text)) $in='and groupid in('.$text.')';
		else $in='';
		$reason='防水墙自动处理';
		if(strtolower(CHARSET) == 'utf-8') $reason=iconv('GB2312', 'UTF-8',$reason);//utf-8
		require_once DISCUZ_ROOT.'./source/discuz_version.php';
	    if(DISCUZ_VERSION=='X2'){
			$filepath=DISCUZ_ROOT.'./data/cache/cache_nimba_security_time.php';
			@include_once DISCUZ_ROOT.'./data/cache/cache_nimba_security_wid.php';
			@include_once DISCUZ_ROOT.'./data/cache/cache_nimba_security_bid.php';						
		}
		if(DISCUZ_VERSION=='X2.5'||DISCUZ_VERSION=='X3 Beta'||DISCUZ_VERSION=='X3 RC'||DISCUZ_VERSION=='X3'){
			$filepath=DISCUZ_ROOT.'./data/sysdata/cache_nimba_security_time.php';
			@include_once DISCUZ_ROOT.'./data/sysdata/cache_nimba_security_wid.php';
			@include_once DISCUZ_ROOT.'./data/sysdata/cache_nimba_security_bid.php';			
		}

		if(file_exists($filepath)){
		    @include_once $filepath;
		    $lasttime=@filemtime($filepath);
			if(TIMESTAMP-$lasttime>$long*60){//$long分钟处理一次
				if(TIMESTAMP-$data['dateline']>86400){//每24小时重建一次,避免文件无限增大
					@unlink($filepath);
					$data=array('dateline'=>time(),'num'=>0);
				}
				$dateline=$long*120;//扩大处理区间
				$querys = DB::query("SELECT tid FROM ".DB::table('forum_threadmod')." where reason='".$reason."' and dateline>".$dateline." ORDER BY tid DESC");
				$user=array();
				while($thread = DB::fetch($querys)){
					$uid = DB::result_first("SELECT authorid FROM ".DB::table('forum_thread')." where tid=".$thread['tid']." ORDER BY tid DESC");
					if($uid){
						$user[$uid]+=1;
						if($user[$uid]>=$num&&$data[$uid]['status']!=1&&(!in_array($uid,$wdata)&&!in_array($uid,$bdata))){//超过num贴数，贴还未被禁言
							DB::query("UPDATE ".DB::table('common_member')." SET adminid='-1', groupid='4' WHERE uid ='$uid' $in", 'UNBUFFERED');
							$data[$uid]['status']=1;
							$data[$uid]['time']=time();
							$data[$uid]['uid']=$uid;
							$data[$uid]['name']=DB::result_first("SELECT username FROM ".DB::table('common_member')." WHERE uid=".$uid."");
							$data['num']+=1;
						}
					}
				}				
				//end
			    @require_once libfile('function/cache');
				$cacheArray .= "\$data=".arrayeval($data).";\n";
				writetocache('nimba_security_time', $cacheArray);				
			}
		}else{//第一次执行
				//start
				$data=array('dateline'=>time(),'num'=>0);
				$dateline=$long*120;//扩大处理区间
				$querys = DB::query("SELECT tid FROM ".DB::table('forum_threadmod')." where reason='".$reason."' and dateline>".$dateline." ORDER BY tid DESC");
				$user=array();
				while($thread = DB::fetch($querys)){
					$uid = DB::result_first("SELECT authorid FROM ".DB::table('forum_thread')." where tid=".$thread['tid']." ORDER BY tid DESC");
					if($uid){
						$user[$uid]+=1;
						if($user[$uid]>=$num&&$data[$uid]['status']!=1&&(!in_array($uid,$wdata)&&!in_array($uid,$bdata))){
							DB::query("UPDATE ".DB::table('common_member')." SET adminid='-1', groupid='4' WHERE uid ='$uid' $in", 'UNBUFFERED');
							$data[$uid]['status']=1;
							$data[$uid]['time']=time();
							$data[$uid]['uid']=$uid;
							$data[$uid]['name']=DB::result_first("SELECT username FROM ".DB::table('common_member')." WHERE uid=".$uid."");
							$data['num']+=1;
						}
					}
				}				
				//end	
			    @require_once libfile('function/cache');				
				$cacheArray .= "\$data=".arrayeval($data).";\n";
				writetocache('nimba_security_time', $cacheArray);				
		}
	}
	function global_footerlink() {
	    loadcache('plugin');
		global $_G;
		$copyright['utf']='PHNwYW4gY2xhc3M9InBpcGUiPnw8L3NwYW4+PGEgaHJlZj0iaHR0cDovL3d3dy5haWxhYi5jbi8iIHRhcmdldD0iX2JsYW5rIiB0aXRsZT0i5Lq65bel5pm66IO96Ziy5oqk5byV5pOOLOmYsuawtOWimeiHquWKqOWkhOeQhiI+PGltZyBhbHQ9IuS6uuW3peaZuuiDvSIgc3JjPSJzb3VyY2UvcGx1Z2luL25pbWJhX3NlY3VyaXR5L3RlbXBsYXRlL3d3dy5haWxhYi5vcmcuZ2lmIj48L2E+';
		$copyright['gbk']='PHNwYW4gY2xhc3M9InBpcGUiPnw8L3NwYW4+PGEgaHJlZj0iaHR0cDovL3d3dy5haWxhYi5jbi8iIHRhcmdldD0iX2JsYW5rIiB0aXRsZT0iyMu5pNbHxNy3wLuk0v3H5iy3wMuux73X1LavtKbA7SI+PGltZyBhbHQ9IsjLuaTWx8TcIiBzcmM9InNvdXJjZS9wbHVnaW4vbmltYmFfc2VjdXJpdHkvdGVtcGxhdGUvd3d3LmFpbGFiLm9yZy5naWYiPjwvYT4=';
		if(strtolower(CHARSET) == 'utf-8') return base64_decode($copyright['utf']);//utf-8		
		else return base64_decode($copyright['gbk']);
	}
}

class plugin_nimba_security_forum extends plugin_nimba_security{
}

?>