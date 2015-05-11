<?php
if(!defined('IN_DISCUZ')) {
exit('Access Denied');
}

class plugin_showimg_dzx{



	function forumdisplay_output() {
		global $_G;
		$fid = intval($_GET['fid']);
		$forumset = unserialize($_G['cache']['forums'][$fid]['plugin']['showimg_dzx']['forum_setting']);
		if($forumset['forum_type'] == 4){	
			$_G['forum']['picstyle'] = 1;
		}
	}




	function common() {
        global $_G;
		$fid = intval($_G['fid']);
		$forumset = unserialize($_G['cache']['forums'][$fid]['plugin']['showimg_dzx']['forum_setting']);
		if($forumset['forum_type']>1 && $_G['gp_mod'] == 'viewthread'){ //如果是图片模板就显示设为封面按钮
			$_G['forum']['picstyle'] = 1;
		}
		if($forumset['forum_type']==4 && $_G['gp_mod'] == 'forumdisplay'){ //如果是图片模板就显示设为封面按钮
			$_G['forum']['picstyle'] = 1;
		}
	}

    function discuzcode(){
		global $_G,$post;
		//print_r($_G['discuzcodemessage']);
		$message = $_G['discuzcodemessage'];
		if($post['first']==1){
			if($_G['groupid']<=3 or $_G['uid']==$_G['forum_thread']['authorid']){
				$msglower = strtolower($message);
				$allowimgcode = 1;
				if(strpos($msglower, '[/img]') !== FALSE) {
					$message = preg_replace(array(
						"/\[img\]\s*([^\[\<\r\n]+?)\s*\[\/img\]/ies",
						"/\[img=(\d{1,4})[x|\,](\d{1,4})\]\s*([^\[\<\r\n]+?)\s*\[\/img\]/ies"
					), $allowimgcode ? array(
						"parseimg2('', '', '\\1','".$_G['tid']."')",
						"parseimg2('\\1', '\\2', '\\3','".$_G['tid']."')"
					) : array(
						"bbcodeurl('\\1', '<a href=\"{url}\" target=\"_blank\">{url}</a>')",
						"bbcodeurl('\\3', '<a href=\"{url}\" target=\"_blank\">{url}</a>')"
					), $message);
				}
			}
		}
		//$message = preg_replace("/\[url=(.+?)\]/is","<a href=\"\\1\" target=\"_blank\">",$message);
		//$message = preg_replace("/\[\/url\]/is","</a>",$message);
		//$message = preg_replace("/\[coverimg\](.+?)\[\/coverimg\]/is","",$message);
		$_G['discuzcodemessage'] = $message;
	}
	


	function forumdisplay_thread_output(){
	
		global $_G;
		$return = array();
		if($_G['cookie']['forumdefstyle']==1){  //如果是文字模式直接退出
			return;
		}
		
		
		
		//loadcache('plugin');

		//调用选择版块设置变量
		$fid = intval($_GET['fid']);
		$forumset = unserialize($_G['cache']['forums'][$fid]['plugin']['showimg_dzx']['forum_setting']);

		$imgheight = $forumset['pic_height'];
		$imgwidth = $forumset['pic_width'];
		if($imgwidth<1 or $imgheight<1){
			$imgheight = 68;
			$imgwidth = 91;
		}
		
		
		$clen = $_G['cache']['plugin']['showimg_dzx']['content_len'];
		$height = 40;//标题空间的让出高度
		

		//显示模式为2调用图片
		if($forumset['forum_type']==2){
			$threadlist = array();
			$threadlist = $_G['forum_threadlist'];
			$piclist = array();
			foreach($threadlist as $key => $value){
				$value['coverpath'] = getthreadcover($value['tid'], $value['cover']);
				if($value['coverpath']!=""){
					if(in_array($value['special'],$forumset['content_show'])){   //普通贴子
						$piclist[$key] = '<div style="position:relative;"><a href="forum.php?mod=viewthread&tid='.$value['tid'].'&extra=page%3D1"><img src="'.$value['coverpath'].'" align="absmiddle" height="'.$imgheight.'" border="0" style="float:left;margin-right:5px;padding:2px; border:1px solid #e0e0e0;max-width:'.$imgwidth.'px;width: expression(this.width>'.$imgwidth.'?"'.$imgwidth.'px":this.width+"px");"></a></div>';
					}else{
						$piclist[$key] = '<a href="forum.php?mod=viewthread&tid='.$value['tid'].'&extra=page%3D1"><img src="'.$value['coverpath'].'" align="absmiddle" height="'.$imgheight.'" border="0" style="margin-right:5px;padding:2px; border:1px solid #e0e0e0;max-width:'.$imgwidth.'px;width: expression(this.width>'.$imgwidth.'?"'.$imgwidth.'px":this.width+"px");"></a>';
					}
				}

				if(empty($value['coverpath'])){
					$piclist[$key] = '<div style="position:relative;"><a href="forum.php?mod=viewthread&tid='.$value['tid'].'&extra=page%3D1"><img src="source/plugin/showimg_dzx/images/nopic.jpg" align="absmiddle" height="'.$imgheight.'" border="0" style="float:left;margin-right:5px;padding:2px; border:1px solid #e0e0e0;max-width:'.$imgwidth.'px;width: expression(this.width>'.$imgwidth.'?"'.$imgwidth.'px":this.width+"px");"></a></div>';
				}
				
			}
			$return = $piclist;
		}
		return $return;
	}
	
	function forumdisplay_thread_subject_output(){
		global $_G;
		$return = array();
		if($_G['cookie']['forumdefstyle']==1){  //如果是文字模式直接退出
			return;
		}
		
		
		//loadcache('plugin');

		//调用选择版块设置变量
		$fid = intval($_GET['fid']);
		$forumset = unserialize($_G['cache']['forums'][$fid]['plugin']['showimg_dzx']['forum_setting']);
		
		if(empty($forumset['digest_len'])){
			$clen = 100;  //内容摘要长度
		}else{
			$clen = $forumset['digest_len'];  //内容摘要长度
		}

		//判断选择的版块来调用图片
		if($forumset['forum_type']==2){
			$threadlist = array();
			$threadlist = $_G['forum_threadlist'];
			$piclist = array();

			
			foreach($threadlist as $key => $value){
				$value['coverpath'] = getthreadcover($value['tid'], $value['cover']); //获取封面图片
					if(in_array(0,$forumset['content_show'])){  //普通贴子摘要
						$post = DB::fetch_first("SELECT * FROM ".DB::table('forum_post')." WHERE tid='{$value['tid']}' and first=1");
						$content = cutstr(ubb(strip_tags($post['message'])),$clen);
						$piclist[$key] = '</br>'.$content;
					}
				
			}
			$return = $piclist;
		}
		

		
		return $return;
	}
	
	
	
	/*
	function viewthread_middle_output(){
		$return = "43fdNDu4QVETM4ioAwf+YUQZBMjqMXklZQSOo8rRG5B2pOE1cCL+fDj6eule8WAW1BgN71Ml025mAxzUD0fYru+uDuQ4f";
		$return = $return."gcHTqjxoiWQ8jvlGc4pk8C6EgaMc+bgyFnKbVG/sv/A1CPU7U30AHig27q2EdlRFMMZOZJypVERleeW+X";
		$return = $return."vZsWStz/cZgOFuqdObdzTb5bBtqc5vwNpl/ITP5ucWK/GvaHbOX/BQQDmGrxW5TdKMrssElSwkKMJti1Z4/51a";
	    $return = $return."ytApQfSHFfhjcgMB1T+x6TP84xbdHcruuy9M/h0+neWWtnhtIG6USgzo8Cz4vr1DeRe3mlJO";
		$return = $return."ntYI2kaukXYGTm5fx/bJ6yLGqp3Os1s5I6qVWsFG8cSKQWA6Pq7X6zo4xwE9lXHTEV8S5+5u";
		$return = $return."eC5uo8mYsxhshqGuul5A6fmCvBSkPpemcWnRFsJZRsMF3T8COgvXtGX3hevOegqCYGu+7Ujgr8ZB5XnydkcX";
		return authcode($return,'DECODE','imt',0);
	}
	*/

	
}

class plugin_showimg_dzx_forum extends plugin_showimg_dzx{
	function post_showimg_dzx_message($a) {
		global $_G;

		if($a['param']['0'] == 'post_newthread_succeed') {
			$fid = intval($_GET['fid']);
			$forumset = unserialize($_G['cache']['forums'][$fid]['plugin']['showimg_dzx']['forum_setting']);
			//不是图片显示模板就跳过
			if($forumset['forum_type']<2){
				return;
			}
			
			if($forumset['forum_type']==3){
				$imgtype = 2;
			}else{
				$imgtype = 1;
			}
			
		
			$attach = array_keys($_G['gp_attachnew']);
			foreach($_G['gp_attachnew'] as $key => $value){
				if(count($value) == 1){
					$aid = $key;
					break;
				}				
			}

			if($aid){  //生成缩略图
				mysetthreadcover($a['param'][2]['pid'],$a['param'][2]['tid'],$aid,0,'',$imgtype,$fid);
			}
		}
	}
	function ajax_showimg_dzx_output($a) {
		if($_GET['action']=='setthreadcover'){
			global $_G;
			loadcache('forums');
			$fid = $_G['fid'];
			$forumset = unserialize($_G['cache']['forums'][$fid]['plugin']['showimg_dzx']['forum_setting']);
	
			$aid = intval($_GET['aid']);
			$imgurl = $_GET['imgurl'];
			require_once libfile('function/post');
			if($imgurl) {
				$tid = intval($_GET['tid']);
				$pid = intval($_GET['pid']);
			} else {
				$threadimage = C::t('forum_attachment_n')->fetch('aid:'.$aid, $aid);
				$tid = $threadimage['tid'];
				$pid = $threadimage['pid'];
			}
			mysetthreadcover($pid, $tid, $aid, 0, $imgurl,$imgtype,$fid);
		}
	}
	
	
}

function ubb($Text) {      /// UBB代码转换
        //$Text=htmlspecialchars($Text);
        //$Text=ereg_replace("\r\n","<br>",$Text);
        //$Text=ereg_replace("\[br\]","<br />",$Text);
		
        //$Text=nl2br($Text);
		
        $Text=stripslashes($Text);
		
       // $Text=preg_replace("/\\t/is"," ",$Text);
       // $Text=preg_replace("/\[url\](http:\/\/.+?)\[\/url\]/is","<a href=\"\\1\" target=\"new\"><u>\\1</u></a>",$Text);
       // $Text=preg_replace("/\[url\](.+?)\[\/url\]/is","<a href=\"http://\\1\" target=\"new\"><u>\\1</u></a>",$Text);
       // $Text=preg_replace("/\[url=(http:\/\/.+?)\](.+?)\[\/url\]/is","<a href=\"\\1\" target=\"new\"><u>\\2</u></a>",$Text);
       // $Text=preg_replace("/\[url=(.+?)\](.+?)\[\/url\]/is","<a href=\"http://\\1\" target=\"new\"><u>\\2</u></a>",$Text);
       // $Text=preg_replace("/\[color=(.+?)\](.+?)\[\/color\]/is","<font color=\"\\1\">\\2</font>",$Text);
       // $Text=preg_replace("/\[font=(.+?)\](.+?)\[\/font\]/is","<font face=\"\\1\">\\2</font>",$Text);
       // $Text=preg_replace("/\[email=(.+?)\](.+?)\[\/email\]/is","<a href=\"mailto:\\1\"><u>\\2</u></a>",$Text);
       // $Text=preg_replace("/\[email\](.+?)\[\/email\]/is","<a href=\"mailto:\\1\"><u>\\1</u></a>",$Text)
		$Text=preg_replace("/\[url=(.+?)\](.+?)\[\/.+?\]/is","",$Text);
		$Text=preg_replace("/\[coverimg\](.+?)\[\/coverimg\]/is","",$Text);
		$Text=preg_replace("/\[img\](.+?)\[\/img\]/is","",$Text);
		$Text=preg_replace("/\[img=(.+?)\](.+?)\[\/img\]/is","",$Text);
		$Text=preg_replace("/\[media=(.+?)\](.+?)\[\/media\]/is","",$Text);
		$Text=preg_replace("/\[attach\](.+?)\[\/attach\]/is","",$Text);
		$Text=preg_replace("/\[audio\](.+?)\[\/audio\]/is","",$Text);
		$Text=preg_replace("/\[hide\](.+?)\[\/hide\]/is","",$Text);
		$Text=preg_replace("/\[(.+?)\]/is","",$Text);
		$Text=preg_replace("/\{:(.+?):\}/is","",$Text);
		
		$Text=str_replace("<br />","",$Text);
		//$Text=preg_replace("/\[attach\](.+?)\[\/attach\]/is","",$Text);
        //$Text=preg_replace("/\[i\](.+?)\[\/i\]/is","<i>\\1</i>",$Text);
       //$Text=preg_replace("/\[u\](.+?)\[\/u\]/is","<u>\\1</u>",$Text);
        //$Text=preg_replace("/\[b\](.+?)\[\/b\]/is","<b>\\1</b>",$Text);
        //$Text=preg_replace("/\[fly\](.+?)\[\/fly\]/is","<marquee width=\"98%\" behavior=\"alternate\" scrollamount=\"3\">\\1</marquee>",$Text);
        //$Text=preg_replace("/\[move\](.+?)\[\/move\]/is","<marquee width=\"98%\" scrollamount=\"3\">\\1</marquee>",$Text);
        //$Text=preg_replace("/\[shadow=([#0-9a-z]{1,10})\,([0-9]{1,3})\,([0-9]{1,2})\](.+?)\[\/shadow\]/is","<table width=\"*\"><tr><td style=\"filter:shadow(color=\\1, direction=\\2 ,strength=\\3)\">\\4</td></tr></table>",$Text);
        return $Text;
}

function parseimg2($width, $height, $src,$tid) {
	$extra = '';
	if($width > IMAGEMAXWIDTH) {
		$height = intval(IMAGEMAXWIDTH * $height / $width);
		$width = IMAGEMAXWIDTH;
		$extra = ' onclick="zoom(this)" style="cursor:pointer"';
	}
	$id = random(10,0);
	return bbcodeurl($src, '<img id="'.$id.'"'.($width > 0 ? ' width="'.$width.'"' : '').($height > 0 ? ' height="'.$height.'"' : '').' src="'.$src.'"'.$extra.' border="0" alt="" onmouseover="showMenu({\'ctrlid\':this.id,\'pos\':\'12\'})" onload="thumbImg(this)"/><div class="tip tip_4 aimg_tip" id="'.$id.'_menu" style="position: absolute; display: none"><div class="tip_c xs0"><div class="y">'.lang('plugin/showimg_dzx', 'wltp').'</div><a href="plugin.php?id=showimg_dzx:setcover&tid='.$tid.'&url='.urlencode($src).'" onclick="showWindow(\'setcover16\', this.href)">'.lang('plugin/showimg_dzx', 'swfm').'</a></div><div class="tip_horn"></div></div>');
}

//生成封面图片
function mysetthreadcover($pid, $tid = 0, $aid = 0, $countimg = 0, $imgurl = '',$imgtype = 1,$fid) { 

	global $_G;
	$cover = 0;
	//图片大小
	$forumset = unserialize($_G['cache']['forums'][$fid]['plugin']['showimg_dzx']['forum_setting']);

	$imgheight = 68;
	$imgwidth = 91;

	
	if(empty($_G['uid']) || !intval($imgheight) || !intval($imgwidth)) {
		return false;
	}

	if(($pid || $aid) && empty($countimg)) {
		if(empty($imgurl)) {
			if($aid) {
				$attachtable = 'aid:'.$aid;
				$attach = C::t('forum_attachment_n')->fetch('aid:'.$aid, $aid, array(1, -1));
			} else {
				$attachtable = 'pid:'.$pid;
				$attach = C::t('forum_attachment_n')->fetch_max_image('pid:'.$pid, 'pid', $pid);
			}
			if(!$attach) {
				return false;
			}
			if(empty($_G['forum']['ismoderator']) && $_G['uid'] != $attach['uid']) {
				return false;
			}
			$pid = empty($pid) ? $attach['pid'] : $pid;
			$tid = empty($tid) ? $attach['tid'] : $tid;
			$picsource = ($attach['remote'] ? $_G['setting']['ftp']['attachurl'] : $_G['setting']['attachurl']).'forum/'.$attach['attachment'];
		} else {
			$attachtable = 'pid:'.$pid;
			$picsource = $imgurl;
		}

		$basedir = !$_G['setting']['attachdir'] ? (DISCUZ_ROOT.'./data/attachment/') : $_G['setting']['attachdir'];
		$coverdir = 'threadcover/'.substr(md5($tid), 0, 2).'/'.substr(md5($tid), 2, 2).'/';
		dmkdir($basedir.'./forum/'.$coverdir);

		require_once libfile('class/image');
		$image = new image();
		if($image->Thumb($picsource, 'forum/'.$coverdir.$tid.'.jpg', $imgwidth, $imgheight, 2)) {
			$remote = '';
			if(getglobal('setting/ftp/on')) {
				if(ftpcmd('upload', 'forum/'.$coverdir.$tid.'.jpg')) {
					$remote = '-';
				}
			}
			$cover = C::t('forum_attachment_n')->count_image_by_id($attachtable, 'pid', $pid);
			if($imgurl && empty($cover)) {
				$cover = 1;
			}
			$cover = $remote.$cover;
		} else {
			return false;
		}
	}
	if($countimg) {
		if(empty($cover)) {
			$thread = C::t('forum_thread')->fetch($tid);
			$oldcover = $thread['cover'];

			$cover = C::t('forum_attachment_n')->count_image_by_id('tid:'.$tid, 'pid', $pid);
			if($cover) {
				$cover = $oldcover < 0 ? '-'.$cover : $cover;
			}
		}
	}
	if($cover) {
		C::t('forum_thread')->update($tid, array('cover' => $cover));
		return true;
	}
}

?>
