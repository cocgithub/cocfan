<?php
 /**
 * 维清工作室 [ 专业开发各种Discuz!插件 ]
 *
 * Copyright (c) 2011-2012 http://www.weiqing.org All rights reserved.
 *
 * Author: wuchunuan <wuchunuan@163.com>
 *
 * $Id: manage.inc.php 2012-4-8 上午09:04:17Z wuchunuan $
 */

if (!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit ('Access Denied');
}

loadcache('plugin');
$option = $_G['cache']['plugin']['wq_links'];
$is_multi = $option['is_multi'];
$is_pic = $option['is_pic'];
$manage_num = intval($option['manage_num']);

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

		$perpage = $manage_num <= 0 ? 20 : $manage_num;

		$start = ($page -1) * $perpage;

		$mpurl = ADMINSCRIPT . '?action=plugins&operation=config&do=' . $pluginid . '&identifier=wq_links&pmod=manage';
		$mpurl .= '&perpage=' . $perpage;

		$conditions = "l.status = '0'";

		$count = DB :: result_first("SELECT count(*) FROM " . DB :: table('wq_links') . " l LEFT JOIN " . DB :: table('common_member') . " m ON l.uid=m.uid WHERE " . $conditions);

		$blogssql = DB :: query("SELECT l.*,m.email,m.username FROM " . DB :: table('wq_links') . " l LEFT JOIN " . DB :: table('common_member') . " m ON l.uid=m.uid WHERE " . $conditions . " ORDER BY l.id DESC LIMIT $start,$perpage");

		showformheader('plugins&operation=config&do=' . $pluginid . '&identifier=wq_links&pmod=manage', 'submit');
		showtableheader(lang('plugin/wq_links', 'linksvalidate'));
		if (!empty ($count)) {
			showsubtitle(array (
				'',
				lang('plugin/wq_links', 'sitename'),
				lang('plugin/wq_links', 'siteurl'),
				lang('plugin/wq_links', 'logo'),
				lang('plugin/wq_links', 'intro'),
				lang('plugin/wq_links', 'applyer'),
				lang('plugin/wq_links', 'dateline'),
				lang('plugin/wq_links', 'prseo'),
			));

			while ($linksarr = DB :: fetch($blogssql)) {
				$linksarr[dateline] = date("Y-m-d H:i", $linksarr[dateline]);
				if($linksarr[status] == 0){
					$linksarr[status] = lang('plugin/wq_links', 'unverify');
				}
				if(empty($linksarr['logo'])){
					$linksarr['logo'] = lang('plugin/wq_links', 'nologo');
				}else{
					$linksarr['logo'] = "<img src='".$linksarr['logo']."' width='88' height='31' />";
				}
				$linksarr['username'] = empty($linksarr['username']) ? lang('plugin/wq_links', 'youke') : $linksarr['username'];
				$url = str_replace("http://","",$linksarr[siteurl]);
				showtablerow('', array ('','width=140px','width=200px','width=90px','width=260px','width=80px','width=160px','width=140px'), array (
					"<input type=\"checkbox\" class=\"checkbox\" name=\"linkids[]\" value=\"$linksarr[id]\">",
					$linksarr[sitename],
					"<a href='" . $linksarr[siteurl] . "' target='_blank'>" . $linksarr[siteurl] . "</a>",
					$linksarr[logo],
					$linksarr[description],
					"<a href='home.php?mod=space&uid=" . $linksarr['uid'] . "' target='_blank'>" . $linksarr['username'] . "</a>",
					$linksarr[dateline],
					"<a href='http://pr.chinaz.com/?PRAddress=" . $url . "' target='_blank'>".lang('plugin/wq_links', 'pr')."</a> ".
					"<a href='http://seo.chinaz.com/?host=" . $url . "' target='_blank'>".lang('plugin/wq_links', 'seo')."</a>",
				));
			}

			$multipage = multi($count, $perpage, $page, $mpurl);

			$optypehtml = '' .
			'<input type="hidden" name="hiddenpage" id="hiddenpage" value="' . $page . '"/>' .
			'<input type="hidden" name="hiddenperpage" id="hiddenperpage" value="' . $perpage . '"/>' .
			'<input type="radio" name="optype" id="optype_trash" value="validate" class="radio" /><label for="optype_trash">' . lang('plugin/wq_links','validate') . '</label>' .
			'<input type="radio" name="optype" id="optype_delete" value="delete" class="radio" /><label for="optype_delete">' . lang('plugin/wq_links','nopassverify') . '</label>' .
			'&nbsp;&nbsp;';

			showsubmit('', '', '', '<input type="checkbox" name="chkall" id="chkall" class="checkbox" onclick="checkAll(\'prefix\', this.form, \'ids\')" /><label for="chkall">' . lang('plugin/wq_links', 'select_all') . '</label>&nbsp;&nbsp;' . $optypehtml . '<input type="submit" class="btn" name="submit" value="' . lang('plugin/wq_links', 'submit_url') . '" />', $multipage);
		}else{
			showtablerow('',array(),array (lang('plugin/wq_links', 'nodata')));
		}
		showtablefooter();
		showformfooter();

	} else {
		$perpage = intval($_G['gp_hiddenperpage']);
		$page = intval($_G['gp_hiddenpage']);

		$linkids = !empty ($_G['gp_linkids']) && is_array($_G['gp_linkids']) ? $_G['gp_linkids'] : array ();


		$mpurl = 'action=plugins&operation=config&do=' . $pluginid . '&identifier=wq_links&pmod=manage';
		$mpurl .= '&perpage=' . $perpage;
		if (!empty ($page)) {
			$mpurl .= '&page=' . $page;
		}


		if ($_POST['optype'] == 'validate') {
			DB::update('wq_links', array ('updatetime' => time(),'status' => '1'), 'id IN (' . dimplode($linkids) . ')');

			foreach($linkids as $lid){
				$linksinfo=get_links_info($lid);
				$message = lang('plugin/wq_links', 'validate_pmcontent1').$linksinfo['siteurl'].lang('plugin/wq_links', 'validate_pmcontent2');
				sendpm($linksinfo['uid'],'',$message);

				$linksinsert=array(
					'displayorder'	=>88,
					'name' => $linksinfo['sitename'],
					'url' => $linksinfo['siteurl'],
					'description' => $linksinfo['description'],
					'logo' => $linksinfo['logo'],
					'type' => 2
				);
				if($is_multi == 0){
					unset($linksinsert['description']);
				}
				if($is_pic == 0 && $is_multi == 0){
					unset($linksinsert['logo']);
				}
				DB::insert('common_friendlink',$linksinsert);
			}

			updatecache('forumlinks');

			cpmsg(lang('plugin/wq_links', 'validate_succeed'), $mpurl, 'succeed');
		}

		if ($_POST['optype'] == 'delete') {
			DB::update('wq_links', array ('updatetime' => time(),'status' => '-1'), 'id IN (' . dimplode($linkids) . ')');
			foreach($linkids as $lid){
				$linksinfo=get_links_info($lid);
				$message = lang('plugin/wq_links', 'delete_pmcontent1').$linksinfo['siteurl'].lang('plugin/wq_links', 'delete_pmcontent2');
				if($linksinfo['uid'] != 0){
					sendpm($linksinfo['uid'],'',$message);
				}
			}
			cpmsg(lang('plugin/wq_links', 'validate_succeed'), $mpurl, 'succeed');
		}
	}
}

function get_links_info($lid){
	return DB::fetch_first('SELECT * FROM '.DB::table('wq_links')." WHERE `id` = '".$lid."'");
}
?>