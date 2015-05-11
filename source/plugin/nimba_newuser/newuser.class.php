<?php
class plugin_nimba_newuser{
    function global_header()
	{
	    loadcache('plugin');
		global $_G;
		$fgs=$this->fgs();
		$var = $_G['cache']['plugin']['nimba_newuser'];
		$open=$var['open'];	
		$refresh=$var['refresh'];
		$fid=$var['baodao'];
		$group=unserialize($var['group']);
		$style=$var['style'];
		if($style==1) $action='forum.php?mod=forumdisplay&fid='.$fid;
		elseif($style==2) $action='forum.php?mod=post&action=newthread&fid='.$fid;		
	    if($fgs==0&&$open==1&&!empty($_G['uid'])&&$open==1&&$_G['fid']!=$fid&&$this->isbaodao($_G['uid'],$fid)==0&&in_array($_G['groupid'],$group)) 
		{ 
			$return="<meta http-equiv=\"refresh\" content=\"$refresh;url=$action\">".'<script type="text/javascript">showWindow(\'nimba_newuser\', \'plugin.php?id=nimba_newuser:ajax&'.FORMHASH.'\');</script>';
		}else return '';
		return $return;
	}
	
	function isbaodao($uid,$fid)
	{
	    $querys = DB::query("SELECT tid FROM ".DB::table('forum_thread')." where authorid=$uid and fid=$fid");
		$tid=DB::fetch($querys);
		if(empty($tid)) return 0;
		else return 1;
	}
	
	function fgs()
	{//设置系统防灌水功能优先运行
	    global $_G;
		$ckuser = $_G['member'];
	    if($_G['setting']['need_avatar'] && !$ckuser['avatarstatus']) $avatar=1;
		else $avatar=0;
		if($_G['setting']['need_email'] && !$ckuser['emailstatus']) $email=1;
		else $email=0;
		$return=$avatar+$email;
		return $return;
	}
 }

class plugin_nimba_newuser_forum extends plugin_nimba_newuser{ 
} 
?>