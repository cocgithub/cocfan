<?php
/**
 *	[Discuz!] (C)2001-2099 Comsenz Inc.
 *	This is NOT a freeware, use is subject to license terms
 *
 *	$Id: vfastpost.class.php 2011-11-25 11:42:27 Ian - Zhouxingming $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class plugin_vfastpost {
	var $setting;
	function plugin_vfastpost() {
		global $_G;
		$this->setting = $_G['cache']['plugin']['vfastpost'];
		$this->setting['fids'] = array_filter(unserialize($this->setting['fids']));
		$this->setting['fixbutton'] = array_filter(unserialize($this->setting['fixbutton']));
		$this->setting['index'] = array_filter(unserialize($this->setting['index']));
		$this->setting['cookie'] = array(
			'index' => $_G['cookie']['vfastpost_index'],
			'forum' => $_G['cookie']['vfastpost']
		);
		$this->setting['uid'] = isset($_G['uid']) ? $_G['uid'] : 0;
	}

	/**
	 * 首页显示快速发帖
	 */
	function index_top() {
		if(!empty($this->setting['index'])) {
			if($this->setting['uid']) {
				$myforums = DB::result_first("SELECT myforum FROM ".DB::table('plugin_vfastpost_myforum')." WHERE uid='{$this->setting[uid]}'");
				$fid = current(explode(',', $myforums));
			}
			$fid = !empty($fid) ? $fid : current($this->setting['index']);
			
			include template('vfastpost:index');
			return $return;
		}
		return '';
	}
	/**
	 * 显示回顶部旁边的按钮
	 */
	function global_footer() {
		global $_G;
		if(defined('CURSCRIPT') && CURSCRIPT == 'forum' && defined('CURMODULE') && CURMODULE == 'forumdisplay') {
			$hash = md5($_G['uid'].substr($_G['timestamp'], 0, 6));
			$return = '<script type="text/javascript">
$(\'newspecial\').onclick=function(){ajaxget(\'plugin.php?id=vfastpost:stat&subop=stat&ac=cl_f&hash='.$hash.'\');setTimeout(function(){showWindow(\'newthread\', \'forum.php?mod=post&action=newthread&fid='.$_G['fid'].'\');},500);};
$(\'newspecialtmp\').onclick=function(){ajaxget(\'plugin.php?id=vfastpost:stat&subop=stat&ac=cl_f&hash='.$hash.'\');setTimeout(function(){showWindow(\'newthread\', \'forum.php?mod=post&action=newthread&fid='.$_G['fid'].'\');},500);};</script>';
			return $return;
		}
		if(defined('CURSCRIPT') && CURSCRIPT == 'forum' && defined('CURMODULE') && CURMODULE == 'viewthread') {
			$hash = md5($_G['uid'].substr($_G['timestamp'], 0, 6));
			$return = '<script type="text/javascript">
$(\'newspecial\').onclick=function(){ajaxget(\'plugin.php?id=vfastpost:stat&subop=stat&ac=cl_v_t&hash='.$hash.'\');setTimeout(function(){showWindow(\'newthread\', \'forum.php?mod=post&action=newthread&fid='.$_G['fid'].'\');},500);};
$(\'newspecialtmp\').onclick=function(){ajaxget(\'plugin.php?id=vfastpost:stat&subop=stat&ac=cl_v_t&hash='.$hash.'\');setTimeout(function(){showWindow(\'newthread\', \'forum.php?mod=post&action=newthread&fid='.$_G['fid'].'\');},500);};
$(\'post_reply\').onclick=function(){ajaxget(\'plugin.php?id=vfastpost:stat&subop=stat&ac=cl_v_r&hash='.$hash.'\');setTimeout(function(){showWindow(\'reply\', \'forum.php?mod=post&action=reply&fid='.$_G['fid'].'&tid='.$_G['tid'].'\');},500);};
$(\'post_replytmp\').onclick=function(){ajaxget(\'plugin.php?id=vfastpost:stat&subop=stat&ac=cl_v_r&hash='.$hash.'\');setTimeout(function(){showWindow(\'reply\', \'forum.php?mod=post&action=reply&fid='.$_G['fid'].'&tid='.$_G['tid'].'\');},500);};
</script>';
			return $return.(in_array($_G['fid'], $this->setting['fids']) ? '<span id="vfpop_menu" onclick="showWindow(\'reply\', \'forum.php?mod=post&action=reply&fid='.$_G['fid'].'&tid='.$_G['tid'].'&fromvf=yes\')">fastpost</span><script type="text/javascript" src="source/plugin/vfastpost/image/v.js"></script>' : '');
		}
	}
}

class plugin_vfastpost_forum extends plugin_vfastpost {
	/**
	 * 显示快速回帖框
	 */
	function viewthread_postbottom_output() {
		global $_G,$postlist,$allowpostreply;

		if(in_array($_G['fid'], $this->setting['fids'])) {
			reset($postlist);
			$post = current($postlist);
			if($_G['uid'] && $post['first']) {
				$hash = md5($_G['uid'].substr($_G['timestamp'], 0, 6));
				include template('vfastpost:viewthread');
				return array($return);
			}
		}
		return array();
	}
	/**
	 * 统计信息
	 */
	function post_vfastpost_message($p) {
		global $_G;
		include_once DISCUZ_ROOT.'./source/plugin/vfastpost/model/index.inc.php';
		ploadmodel('stat');
		$stat = new pluginStat();
		$todaytime = dgmdate($_G['timestamp'], 'Y-m-d');
		if($p['param'][0] == 'post_reply_succeed') {
			if($_G['gp_fromvf']) {
				$stat->updateStat('daytime', $todaytime, array('nr_vf' => '+1'));
				if($_G['gp_float']) {
					$stat->updateStat('daytime', $todaytime, array('nr_vf_float' => '+1'));
				}
			} else {
				$stat->updateStat('daytime', $todaytime, array('nr' => '+1'));
			}
		} elseif($p['param'][0] == 'post_newthread_succeed') {
			if($_G['gp_fromvf']) {
				$stat->updateStat('daytime', $todaytime, array('nt_index' => '+1'));
				$myforums = DB::result_first("SELECT myforum FROM ".DB::table('plugin_vfastpost_myforum')." WHERE uid='{$_G[uid]}'");
				$myforums = !empty($myforums) ? explode(',', $myforums) : array();
				array_unshift($myforums, $_G['fid']);
				$myforums = array_unique($myforums);
				$myforums = count($myforums) > 10 ? array_slice(0, 10) : $myforums;
				DB::query("REPLACE INTO ".DB::table('plugin_vfastpost_myforum')." (uid, myforum) VALUES ('{$_G[uid]}','".implode(',', $myforums)."')");
			} else {
				$stat->updateStat('daytime', $todaytime, array('nt' => '+1'));
			}
		}
	}

	/**
	 * 通过JS更改谈层的action以便于统计信息
	 */
	function post_infloat_middle_output() {
		global $_G;
		if($_G['gp_fromvf']) {
			include_once DISCUZ_ROOT.'./source/plugin/vfastpost/model/index.inc.php';
			ploadmodel('stat');
			$stat = new pluginStat();
			$todaytime = dgmdate($_G['timestamp'], 'Y-m-d');
			$stat->updateStat('daytime', $todaytime, array('cl_vf_float' => '+1'));
			return '<script type="text/javascript" reload="1">$(\'postform\').action += \'&fromvf=1&float=1\';</script>';
		}
	}
	/**
	 * 额外的一些处理
	 */
	function forumdisplay_fastpost_btn_extra() {
		global $_G;
		if($_G['gp_vfastpost']) {
		
		}
	}
	/**
	 * 获取某个板块的快速发帖部分
	 */
	function forumdisplay() {
		global $_G,$allowpostattach,$fastpost,$secqaacheck,$seccodecheck,$allowpostattach,$allowfastpost;
		if($_G['gp_vfastpost']) {
			$allowfastpost = 1;
			if(!empty($this->setting['index'])) {
				$_G['inajax'] = $_G['infloat'] = 0;
				$fastpost = $_G['uid'] ? 1 : 0;
				$_G['setting']['fastsmilies'] = 0;

				include template('common/header_ajax');
				echo '<script type="text/javscript" reload="1"> if($(\'typeid_fast_ctrl_menu\')){ $(\'typeid_fast_ctrl_menu\').parentNode.removeChild($(\'typeid_fast_ctrl_menu\')); } </script>';
				include template('forum/forumdisplay_fastpost');
					$options = '<option disabled="disabled">'.lang('plugin/vfastpost', 'select_fid').'</option>';
					$myforum = array();
					if($_G['uid']){
						//已经使用过的板块
						$myforums = DB::result_first("SELECT myforum FROM ".DB::table('plugin_vfastpost_myforum')." WHERE uid='{$_G[uid]}'");
						$myforums = array_filter(explode(',', $myforums));
					}
					$_G['gp_otherfid'] = !$_G['gp_otherfid'] ? ($_G['gp_fid'] == -1 ? 1 : 0) : $_G['gp_otherfid'];
					if(!$_G['gp_otherfid'] && $myforums) {
						foreach($_G['cache']['forums'] as $forum) {
							if(in_array($forum['fid'], $this->setting['index']) && in_array($forum['fid'], $myforums)) {
								$options .= '<option value="'.$forum['fid'].'" '.($_G['gp_fid'] == $forum['fid'] ? 'selected="selected"' : '').'>'.$forum['name'].'</option>';
							}
						}				
					} else {
						foreach($_G['cache']['forums'] as $forum) {
							if(in_array($forum['fid'], $this->setting['index'])) {
								$options .= '<option value="'.$forum['fid'].'" '.($_G['gp_fid'] == $forum['fid'] ? 'selected="selected"' : '').'>'.$forum['name'].'</option>';
							}
						}			
					}
					$options .= $_G['gp_otherfid'] ? '' : '<option value="-1">'.lang('plugin/vfastpost', 'otherfid').'</option>';
					$a = '<select id="vfastpostfid" onchange="ajaxget(\\\'forum.php?mod=forumdisplay&fid=\\\'+this.value+\\\'&vfastpost=1&otherfid='.$_G['gp_otherfid'].'\\\', \\\'vfi_d\\\');">'.$options.'</select>';
					echo '<style>#fastpostsubmit{margin-right:17px;}</style><script type="text/javascript" reload="1">
						$(\'fastpostsubmit\').parentNode.innerHTML += \''.$a.'\';
						$(\'fastpostsubmit\').className=\'pn pnc y\';
						$(\'fastpostsubmit\').parentNode.className=\'ptm pnpost cl\';
						$(\'fastpostform\').action+=\'&fromvf=1\';
						var vf_div = $(\'f_pst\').getElementsByTagName(\'div\');vf_div[0].innerHTML += \'<span id="vfclose_i">'.lang('plugin/vfastpost', 'pack').'</span>\';
						$(\'vfclose_i\').onclick=function(){$(\'vfi_d\').style.display=\'none\';$(\'vfitips\').style.display=\'\';};
						$(\'subject\').focus();
</script>';
				include template('common/footer_ajax');
				dexit();
			}
		}
	}

	/**
	 * 图片上添加评论当前楼层的按钮
	 *
	function viewthread_bottom_output() {
		global $postlist;
		$pids = array_keys($postlist);

		return <<<HTML
<style type="text/javascript" reload="1">

</style>
<script type="text/javascript" reload="1">

function vfMakeA(id) {
	var post = $('postmessage_'+id).getElementsByTagName('IMG');
	for(var i = 0; i < post.length; i++) {
		if(1||post[i].onload) {
			var vid = id;
			var vi = i;
			post[i].setAttribute('id', 'vfareply_'+vid+'_'+vi);
			post[i].setAttribute('vid', vid);
			post[i].setAttribute('vi', vi);
			post[i].onmouseover = vfShow;
		}
	}
}
function vfShow() {
	var vid = this.getAttribute('vid');
	var vi  = this.getAttribute('vi');
	var menuid = 'vfareply_'+vid+'_'+vi+'_menu';
	var ctrlid = 'vfareply_'+vid+'_'+vi;
	if(!$(menuid)) {
		this.parentNode.innerHTML = '<div id="'+menuid+'" style="position: absolute; display: none;"><a href=""><img src="source/plugin/vfastpost/image/image.png" /></a></div>'+this.parentNode.innerHTML;
	}
	$(menuid).style.display='block';
}
vfMakeA('86');
vfMakeA('89');
vfMakeA('90');
</script>
HTML;
	}
	 */
}
?>
