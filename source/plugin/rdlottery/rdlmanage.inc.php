<?php
/**
 *	随机抽奖插件后台管理程序
 *	2012-9-3 coofee
 *
 * */

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$op = 'view';
$each = 20;
$start = ($page - 1)*$each;

$tid = intval($_G['gp_tid']);
$tid = $tid > 0 ? $tid : 0;

if(!submitcheck('delete')) {
	$count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('plugin_rdlottery'));
	showtips(lang('plugin/rdlottery', 'm_manage_tips'));
	showtableheader(lang('plugin/rdlottery', 'rdlottery'));
	showformheader('plugins&operation=config&identifier=rdlottery&pmod=rdlmanage');
	showtablerow('', array('width="5%"', 'width="5"', 'width="5%"', 'width="20%"', 'width="20%"' ,'width="20%"', 'width="10%"', 'width="20%"'), array(
		'&nbsp;',
		lang ('plugin/rdlottery', 'id'),
		'TID',
		lang('plugin/rdlottery', 'm_title'),
		lang('plugin/rdlottery', 'm_starttimefrom'),
		lang('plugin/rdlottery', 'm_starttimeto'),
		lang('plugin/rdlottery', 'm_username'),
		lang('plugin/rdlottery', 'm_finished'),	
	));
	if($count) {
		$query = DB::query("SELECT * FROM ".DB::table('plugin_rdlottery')." ORDER BY status ASC,starttimefrom DESC LIMIT $start,$each");
		while($rdlottery = DB::fetch($query)) {
			showtablerow('', array('width="5%"', 'width="10%"', 'width="5%"', 'width="25%"', 'width="20%"', 'width="10%"', 'width="10%"', 'width="15%"'), array(
				'<input type="checkbox" name="deleteids[]" value="'.$rdlottery['tid'].'">',
				$rdlottery['aid'],
				$rdlottery['tid'],
				'<a href="forum.php?mod=viewthread&tid='.$rdlottery['tid'].'" target="_blank">'.$rdlottery['name'].'</a>',
				dgmdate($rdlottery['starttimefrom']),
				dgmdate($rdlottery['starttimeto']),
				'<a href="home.php?mod=space&uid='.$rdlottery['uid'].'" target="_blank">'.$rdlottery['username'].'</a>',
				$rdlottery['status'] ? lang('plugin/rdlottery', 'm_yes') : lang('plugin/rdlottery', 'm_no'),
				));
		} 
		showsubmit('delete','delete');	
	} else {
		showtablerow('', array('colspan="5"'), array(lang('plugin/rdlottery', 'none')));
	}
	showtablerow('', array('colspan="5"'), array(multi($count, $each, $page, ADMINSCRIPT.'?action=plugins&operation=config&identifier=rdlottery&pmod=rdlmanage')));
	showformfooter();
	showtablefooter();
}else{
	$deleteids = $_G['gp_deleteids'];
	$dels = 0;
	foreach($deleteids as $id) {
		$id = intval($id);
		if(DB::result_first("SELECT COUNT(*) FROM ".DB::table('plugin_rdlottery')." WHERE tid='$id'")) {
			$rdlottery = DB::fetch_first("SELECT * FROM ".DB::table('plugin_rdlottery')." WHERE tid='$id'");

			if($rdlottery['status']) {
				DB::query("DELETE FROM ".DB::table('plugin_rdlotteryapply')." WHERE tid='{$id}'");
				DB::query("DELETE FROM ".DB::table('plugin_rdlottery')." WHERE tid='{$id}'");
				$dels++;
			}
		}

	}
	cpmsg(lang('plugin/rdlottery', 'm_delete_succeed')." $dels ".lang('plugin/rdlottery', 'm_delete_end'), 'action=plugins&operation=config&identifier=rdlottery&pmod=rdlmanage');
}

