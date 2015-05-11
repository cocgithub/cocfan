<?php
/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: upgrade.php 31181 2012-07-24 05:21:46Z chenmengshu $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

if ($_GET['fromversion'] < 1.5) {
$sql = <<<EOF
ALTER TABLE  `pre_plugin_guessulike_forumlike` 
ADD  `hot1` MEDIUMINT( 8 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `hot` ,
ADD  `hot2` MEDIUMINT( 8 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `hot1` ,
ADD  `hot3` MEDIUMINT( 8 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `hot2` ,
ADD  `hot4` MEDIUMINT( 8 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `hot3` ,
ADD  `hot5` MEDIUMINT( 8 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `hot4` ,
ADD  `hot6` MEDIUMINT( 8 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `hot5` ,
ADD  `hot7` MEDIUMINT( 8 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `hot6`
EOF;

runquery($sql);
}

$finish = TRUE;