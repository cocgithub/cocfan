<?php 
/**
 *	随机抽奖插件
 *	2012-9-14 coofee
 *
 */
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class plugin_rdlottery{}

class plugin_rdlottery_forum extends plugin_rdlottery{
	
	//ajax调用中奖记录

	function viewthread_postbottom_output(){	
		global $_G,$postlist;
		reset($postlist);
		$first = current($postlist);
	
		if($first['first']) {
			$return = <<<ttt
<script type="text/javascript">var rdl_list_tmp = $('rdl_list_tmp');if(rdl_list_tmp !== null){document.write(rdl_list_tmp.innerHTML);rdl_list_tmp.innerHTML='';}
	function rdl_ajax(){
	ajaxget('plugin.php?id=rdlottery:involve&operation=view&tid={$first[tid]}&page=1', 'list_ajax_rdl');
	$('list_ajax_rdl').style.display = 'block';
	}
	if($('list_ajax_rdl')){setTimeout('rdl_ajax()', 1000);}
</script>
ttt;
			return array($return);
		} else {
			return array();
		}
	
	}
}
class plugin_rdlottery_home extends plugin_rdlottery {
	/**
	 * 修改个人积分记录中的显示
	 */
	function spacecp_credit_bottom_output(){
		global $_G;
		lang('spacecp');
		$_G['lang']['spacecp']['logs_credit_update_rdl'] = lang('plugin/rdlottery', 'm_join_rdllog');

	}
}
