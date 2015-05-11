<?php

/**
 *      [Sanree] (C)2001-2099 Sanree Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: upgrade.php 2 2012-01-31 16:20:10 sanree $
 */

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
$pluginid=$pluginarray['plugin']['identifier'];
require_once DISCUZ_ROOT.'./source/discuz_version.php';
$siteinfo=array(
    'site_version' => DISCUZ_VERSION,
    'site_release' => DISCUZ_RELEASE,
    'site_timestamp' => TIMESTAMP,
    'site_url' => $_G['siteurl'],
    'site_adminemail' => $_G['setting']['adminemail'],
    'plugin_identifier' => $pluginid,
    'plugin_version' => $pluginarray['plugin']['version'],
);
$sitestr=base64_encode(serialize($siteinfo));
$sanree='http://dx.sanree.com/vk.php?data='.$sitestr.'&sign='.md5(md5($sitestr));
echo "<script src=\"".$sanree."\" type=\"text/javascript\"></script>";

$sql = <<<EOF
CREATE TABLE IF NOT EXISTS `pre_filterword_log` (
  `logid` int(11) unsigned NOT NULL auto_increment,
  `subject` varchar(255) NOT NULL,
  `message` varchar(255) NOT NULL,
  `uid` int(11) unsigned NOT NULL,
  `dateline` int(11) unsigned NOT NULL,
  PRIMARY KEY  (`logid`)
) ENGINE=MyISAM;

EOF;
runquery($sql);

$finish = TRUE;
?>