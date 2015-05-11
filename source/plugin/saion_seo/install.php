<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$sql = <<<EOF
DROP TABLE IF EXISTS `pre_saion_seo_visit`;
CREATE TABLE IF NOT EXISTS `pre_saion_seo_visit` (
  `type` varchar(10) NOT NULL,
  `id` mediumint(9) NOT NULL,
  `spider` char(10) NOT NULL,
  `time` int(11) NOT NULL DEFAULT '1',
  `dateline` int(10) unsigned NOT NULL,
  UNIQUE KEY `uq` (`type`,`id`,`spider`)
) ENGINE=MyISAM;
EOF;

runquery($sql);

$finish = TRUE;

?>