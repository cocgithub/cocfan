<?php
/**
*
* 伪原创
* 
* @author 余新启
* @copyright seoeye.cn 2012-08-21
* 
*/

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
include_once DISCUZ_ROOT.'/source/plugin/seo/function_inc.php';
if(!isset($_G['cache']['plugin']['liangjian'])){
	loadcache('plugin');
}
$config=$_G['cache']['plugin']['liangjian'];
$allowaction = array('list', 'detail', 'add');
$op = in_array($_G['gp_op'], $allowaction) ? $_G['gp_op'] : 'list';

$settingfile = DISCUZ_ROOT . './data/sysdata/cache_liangjian_setting.php';

if(file_exists($settingfile)){
	include $settingfile;
}
if(file_exists(DISCUZ_ROOT . './data/cache/cache_liangjian_setting.php')){
	$settingfile = DISCUZ_ROOT . './data/cache/cache_liangjian_setting.php';
	include DISCUZ_ROOT . './data/cache/cache_liangjian_setting.php';
}
switch ($op){
	case 'list':
		
		showformheader("plugins&operation=config&do=$do&identifier=liangjian&pmod=real&op=add",'enctype');
		showtableheader(lang('plugin/liangjian', 'thesaurus_setting'));
		showsetting(lang('plugin/liangjian','tips_thesaurus'), 'thesaurus_setting', $liangjian_setting['thesaurus_setting'], 'textarea', 0, 1,lang('plugin/liangjian','thesaurus_setting_comment'));
		showtagfooter('tbody');
		showsetting(lang('plugin/liangjian','c1'), 'logonew', $nav['pic'], 'filetext', '', 0,lang('plugin/liangjian','c2'));
		showtagfooter('tbody');
		showsetting(lang('plugin/liangjian','tips_post'), 'allow_thesaurus', $liangjian_setting['allow_thesaurus'], 'radio', 0, 1,lang('plugin/liangjian','allow_thesaurus_comment'));
		showtagfooter('tbody');
		showsetting(lang('plugin/liangjian','tips_title'), 'allow_title', $liangjian_setting['allow_title'], 'radio', 0, 1,lang('plugin/liangjian','allow_title_comment'));
		showtagfooter('tbody');
		showsetting(lang('plugin/liangjian','title_portal'), 'title_portal', $liangjian_setting['title_portal'], 'radio', 0, 1,lang('plugin/liangjian','title_portal_comment'));
		showtagfooter('tbody');
		showsetting(lang('plugin/liangjian','content_portal'), 'content_portal', $liangjian_setting['content_portal'], 'radio', 0, 1,lang('plugin/liangjian','content_portal_comment'));
		showtagfooter('tbody');
		showsubmit('editsubmit',lang('plugin/liangjian','cloud_update'));
		showtablefooter();
		showformfooter();
		break;
	case 'add':		
		if(submitcheck('editsubmit')){
			//伪原创管理中心设置项
			$liangjian_setting['thesaurus_setting']=$_GET['thesaurus_setting'];
			$liangjian_setting['allow_thesaurus']=$_GET['allow_thesaurus'];
			$liangjian_setting['allow_title']=$_GET['allow_title'];
			$liangjian_setting['title_portal']=$_GET['title_portal'];
			$liangjian_setting['content_portal']=$_GET['content_portal'];
			if($_FILES['logonew']['tmp_name']) {
				$upload = new discuz_upload();
				$upload->init($_FILES['logonew']);
				if($upload->init($_FILES['logonew']) && $upload->save()) {
					$logonew = $upload->attach['attachment'];
				}
				$tmpaddr=$_G['setting']['attachurl'].'temp/'.$logonew;
				$str=file_get_contents($tmpaddr);
				$liangjian_setting['thesaurus_setting']=$str;
				if($_G['charset']!='gbk'){
					$liangjian_setting['thesaurus_setting']=iconv('gbk','utf-8',$str);
				}
				
			}else if($_GET['logonew']){
				$str=file_get_contents($_GET['logonew']);
				$liangjian_setting['thesaurus_setting']=$str;
				if($_G['charset']!='gbk'){
					$liangjian_setting['thesaurus_setting']=iconv('gbk','utf-8',$str);
				}
			}
			require_once libfile('function/cache');
			writetocache('liangjian_setting', getcachevars(array('liangjian_setting' => $liangjian_setting)));//将管理中心配置项写入缓存
		cpmsg(lang('plugin/liangjian', 'thesaurus_post'), 'action=plugins&operation=config&do=$do&identifier=liangjian&pmod=real', 'succeed');
		break;
	}
	
}
?>