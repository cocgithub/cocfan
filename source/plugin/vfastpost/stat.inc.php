<?php
/**
 *	[Discuz!] (C)2001-2099 Comsenz Inc.
 *	This is NOT a freeware, use is subject to license terms
 *
 *	$Id: stat.inc.php 2011-11-24 17:40:29 Ian - Zhouxingming $
 */
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
include DISCUZ_ROOT.'./source/plugin/vfastpost/model/index.inc.php';

ploadmodel('stat');

$stat = new pluginStat();
$todaytime = dgmdate($_G['timestamp'], 'Y-m-d');
if($_G['gp_subop'] == 'xml') {
	$siteuniqueid = DB::result_first("SELECT svalue FROM ".DB::table('common_setting')." WHERE skey='siteuniqueid'");
	$stat_hash = md5($siteuniqueid."\t".substr($_G['timestamp'], 0, 6));
	$hash = md5($_G['uid']."\t".substr($_G['timestamp'], 0, 6));
	if($_G['gp_hash'] != $hash && $stat_hash != $_G['gp_hash']) {
		showmessage('no_privilege_statdata');
	}
	@header("Expires: -1");
	@header("Cache-Control: no-store, private, post-check=0, pre-check=0, max-age=0", FALSE);
	@header("Pragma: no-cache");
	@header("Content-type: application/xml; charset=utf-8");

	$_G['gp_begin'] = dgmdate(dmktime($_G['gp_begin']), 'Y-m-d');
	$_G['gp_end'] = dgmdate(dmktime($_G['gp_end']), 'Y-m-d');
	$begin = !empty($_G['gp_begin']) ? $_G['gp_begin'] : '';
	$end = !empty($_G['gp_end']) ? $_G['gp_end'] : '';
	$option = $stat->getShortOption(intval($_G['gp_option']));
	$xml = $stat->makeXML($option, "daytime<='$end' AND daytime>='$begin' ORDER BY daytime ASC LIMIT 50", 'daytime', CHARSET);
	echo $xml;exit;
} elseif($_G['gp_subop'] == 'stat') {
	if($_G['uid'] && $_G['gp_hash'] == md5($_G['uid'].substr($_G['timestamp'], 0, 6))) {
		$acallow = array('cl_f', 'cl_v_t', 'cl_v_r', 'cl_vf_first');
		if(in_array($_G['gp_ac'], $acallow)) {
			$stat->updateStat('daytime', $todaytime, array($_G['gp_ac'] => '+1'));
		}
	}
	exit;
} else {
	exit('Access Denied');
}
?>
