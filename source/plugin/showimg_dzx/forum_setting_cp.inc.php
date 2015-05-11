<?php

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}



loadcache('forums');
$fid = intval($_GET['fid']);
if($fid==0){
	foreach($_G['cache']['forums'] as $key => $value){
		if($value['type']=='forum'){
			$fid=$key;
			break;
		}
	}
}

loadcache('plugin');
if($_GET['op'] == 'save') {
	$pluginvarid = DB::result_first("SELECT pluginvarid FROM ".DB::table('common_pluginvar')." where pluginid=$pluginid and variable='forum_setting'");
	$pluginvars = array();
	$forumset['forum_type'] = $_POST['forum_type'];
	$forumset['content_show'] = $_POST['content_show'];
	$forumset['digest_len'] = $_POST['digest_len'];
	$pluginvars[$pluginvarid][$fid]=serialize($forumset);
	set_pluginsetting($pluginvars);
	updatecache(array('forums'));//¸üÐÂ»º´æ
	$_G['cache']['forums'][$fid]['plugin']['showimg_dzx']['forum_setting'] = $pluginvars[$pluginvarid][$fid];
}




require_once libfile('function/forumlist');
$forumlist = forumselect(FALSE, 0, $fid, TRUE);
$jumpmenu = '<script type="text/javascript">function MM_jumpMenu(targ,selObj,restore){ eval(targ+".location=\''.ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier=showimg_dzx&pmod=forum_setting_cp&fid="+selObj.options[selObj.selectedIndex].value+"\'");  if (restore) selObj.selectedIndex=0;}</script>';


showtableheader();


echo $jumpmenu.'<b>'.lang('plugin/showimg_dzx', 'bkxz').':</b> <br><select name="classid" id="class" class="select" onchange="MM_jumpMenu(\'self\',this,0)">'.$forumlist.'</select><br>';

$forum_type = array(array(1,lang('plugin/showimg_dzx', 'ptms')),array(2,lang('plugin/showimg_dzx', 'tplbys').'1'),array(4,lang('plugin/showimg_dzx', 'tplbys').'2'));
$content_show = array(array(0,lang('plugin/showimg_dzx', 'pttzzy')));

/*
$query = DB::query("SELECT * FROM ".DB::table('xj_sort_class')." where parent=0");
$sort =array();
while($item = DB::fetch($query)){
	$value = array();
	$value[] = $item['classid'];
	$value[] = $item['classname'];
	$sort[] = $value;
}
*/
//echo explode(',',$_G['cache']['forums'][$fid]['plugin']['xj_sort']['sorts']);
$forumset = unserialize($_G['cache']['forums'][$fid]['plugin']['showimg_dzx']['forum_setting']);

showformheader('plugins&operation=config&do='.$pluginid.'&identifier=showimg_dzx&pmod=forum_setting_cp&op=save&fid='.$fid,'','save_setting');
showsetting(lang('plugin/showimg_dzx', 'bkztlbxsfs'),array('forum_type', $forum_type), $forumset['forum_type'], 'select');
showsetting(lang('plugin/showimg_dzx', 'bktzlrzsfs'), array('content_show', $content_show), $forumset['content_show'], 'mcheckbox');
showsetting(lang('plugin/showimg_dzx', 'tzzycd'),'digest_len',$forumset['digest_len'],'text');
showsubmit('save_setting',lang('plugin/showimg_dzx', 'save'));
showformfooter();



showtablefooter();

?>