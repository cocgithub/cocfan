<?php
 /**
 * 维清工作室 [ 专业开发各种Discuz!插件 ]
 *
 * Copyright (c) 2011-2012 http://www.weiqing.org All rights reserved.
 *
 * Author: wuchunuan <wuchunuan@163.com>
 *
 * $Id: managelog.inc.php 2012-4-8 上午09:04:17Z wuchunuan $
 */

if (!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit ('Access Denied');
}

loadcache('plugin');
$option = $_G['cache']['plugin']['wq_links'];
$managelog_num = intval($option['managelog_num']);

$pluginop = !empty ($_G['gp_pluginop']) ? $_G['gp_pluginop'] : 'list';
if (!in_array($pluginop, array ('list'))) {
	showmessage('undefined_action');
}

if ($pluginop == 'list') {
	if (!submitcheck('submit')) {
		$page = intval($_G['page']);
		$page = empty ($page) ? 1 : $page;
		if ($page < 1){
			$page = 1;
		}

		$perpage = $managelog_num <= 0 ? 20 : $managelog_num;

		$start = ($page -1) * $perpage;

		$mpurl = ADMINSCRIPT . '?action=plugins&operation=config&do=' . $pluginid . '&identifier=wq_links&pmod=managelog';
		$mpurl .= '&perpage=' . $perpage;

		$conditions = "l.status != '0'";

		$count = DB :: result_first("SELECT count(*) FROM " . DB :: table('wq_links') . " l LEFT JOIN " . DB :: table('common_member') . " m ON l.uid=m.uid WHERE " . $conditions);

		$blogssql = DB :: query("SELECT l.*,m.email,m.username FROM " . DB :: table('wq_links') . " l LEFT JOIN " . DB :: table('common_member') . " m ON l.uid=m.uid WHERE " . $conditions . " ORDER BY l.id DESC LIMIT $start,$perpage");

		showformheader('plugins&operation=config&do=' . $pluginid . '&identifier=wq_links&pmod=managelog', 'submit');
		showtableheader(lang('plugin/wq_links', 'linksvalidate'));
		if (!empty ($count)) {
			showsubtitle(array (
				'',
				lang('plugin/wq_links', 'sitename'),
				lang('plugin/wq_links', 'siteurl'),
				lang('plugin/wq_links', 'logo'),
				lang('plugin/wq_links', 'intro'),
				lang('plugin/wq_links', 'applyer'),
				lang('plugin/wq_links', 'updatetime'),
				lang('plugin/wq_links', 'status'),
			));

			while ($linksarr = DB :: fetch($blogssql)) {

				$linksarr[updatetime] = date("Y-m-d H:i", $linksarr[updatetime]);
				if($linksarr[status] == 1){
					$linksarr[status] = "<font color='green'>".lang('plugin/wq_links', 'passverify')."</font>";
				}elseif($linksarr[status] == -1){
					$linksarr[status] = "<font color='red'>".lang('plugin/wq_links', 'nopassverify')."</font>";
				}
				if(empty($linksarr['logo'])){
					$linksarr['logo'] = lang('plugin/wq_links', 'nologo');
				}else{
					$linksarr['logo'] = "<img src='".$linksarr['logo']."' width='88' height='31' />";
				}
				$url = str_replace("http://","",$linksarr[siteurl]);
				showtablerow('', array ('','width=160px','width=220px','width=90px','width=260px','width=80px','width=160px','width=60px'), array (
					"<input type=\"checkbox\" class=\"checkbox\" name=\"deleteids[]\" value=\"$linksarr[id]\">",
					$linksarr[sitename],
					"<a href='" . $linksarr[siteurl] . "' target='_blank'>" . $linksarr[siteurl] . "</a>",
					$linksarr[logo],
					$linksarr[description],
					"<a href='home.php?mod=space&uid=" . $linksarr['uid'] . "' target='_blank'>" . $linksarr['username'] . "</a>",
					$linksarr[updatetime],
					$linksarr[status]
				));
			}
			$multipage = multi($count, $perpage, $page, $mpurl);
			$optypehtml = ''.
			'<input type="hidden" name="hiddenpage" id="hiddenpage" value="' . $page . '"/>' .
			'<input type="hidden" name="hiddenperpage" id="hiddenperpage" value="' . $perpage . '"/>' .
			'&nbsp;&nbsp;';

			showsubmit('', '', '', '<input type="checkbox" name="chkall" id="chkall" class="checkbox" onclick="checkAll(\'prefix\', this.form, \'ids\')" /><label for="chkall">' . lang('plugin/wq_links', 'delete') . '</label>&nbsp;&nbsp;' . $optypehtml . '<input type="submit" class="btn" name="submit" value="' . lang('plugin/wq_links', 'submit_url') . '" />', $multipage);

//			showtablerow('',array('colspan=7'),array ($multipage));
		}else{
			showtablerow('',array(),array (lang('plugin/wq_links', 'nolog')));
		}
		showtablefooter();
		showformfooter();
	}else{
//		print_r($_POST);

		$perpage = intval($_G['gp_hiddenperpage']);
		$page = intval($_G['gp_hiddenpage']);

		$deleteids = !empty ($_G['gp_deleteids']) && is_array($_G['gp_deleteids']) ? $_G['gp_deleteids'] : array ();

		$mpurl = 'action=plugins&operation=config&do=' . $pluginid . '&identifier=wq_links&pmod=managelog';
		$mpurl .= '&perpage=' . $perpage;
		if (!empty ($page)) {
			$mpurl .= '&page=' . $page;
		}

		if(count($deleteids) > 0){
			DB::query("DELETE FROM ".DB::table('wq_links')." WHERE `id` IN (".dimplode($deleteids).")");
			cpmsg(lang('plugin/wq_links', 'delete_succeed'), $mpurl, 'succeed');
		}else{
			cpmsg(lang('plugin/wq_links', 'noselect_deleteids'), $mpurl, 'succeed');
		}
	}
}
?>