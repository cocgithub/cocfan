<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class plugin_nimba_readplus {

}

class plugin_nimba_readplus_forum extends plugin_nimba_readplus{
	function viewthread_modaction(){
		loadcache('plugin');
		global $_G;
		$tid=$_G['tid'];
		if($tid){
			$isforum=intval($_G['cache']['plugin']['nimba_readplus']['isforum']);
			if($isforum==1&&$_G['fid']){
				$forum='and fid='.$_G['fid'];
			}else $forum='';
			$querys = DB::query("SELECT tid,subject FROM ".DB::table('forum_thread')." where displayorder not in(-1,-2) and tid<$tid $forum ORDER BY tid DESC  LIMIT 0,1");
			$thread = DB::fetch($querys);
			if($thread) $unto='<a href="forum.php?mod=viewthread&tid='.$thread['tid'].'" target="_blank">'.$thread['subject'].'</a>';
			else $unto=lang('plugin/nimba_readplus','nocontent');
			
			$querys = DB::query("SELECT tid,subject FROM ".DB::table('forum_thread')." where displayorder not in(-1,-2) and tid>$tid $forum ORDER BY tid ASC  LIMIT 0,1");
			$thread = DB::fetch($querys);
			if($thread) $next='<a href="forum.php?mod=viewthread&tid='.$thread['tid'].'" target="_blank">'.$thread['subject'].'</a>';
			else $next=lang('plugin/nimba_readplus','nocontent');
			return lang('plugin/nimba_readplus','unto').$unto.'<br>'.lang('plugin/nimba_readplus','next').$next;
		}
	}
}
?>