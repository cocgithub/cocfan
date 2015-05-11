<?php
/*
 *	nimba_majia (C)2012 AiLab Inc.
 *	nimba_majia Made By Nimba, Team From AiLab.org
 *	Id: install.php  AiLab.org 2012-8-2 09:11$
 */
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$sql = <<<EOF
DROP TABLE IF EXISTS `pre_nimba_spider`;
CREATE TABLE `pre_nimba_spider` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `spidername` varchar(200) DEFAULT NULL,
  `spiderip` varchar(200) DEFAULT NULL,
  `dateline` int(10) unsigned NOT NULL default '0',
  `url` varchar(255) DEFAULT NULL,
  `fid` mediumint(8) unsigned NOT NULL,
  `tid` mediumint(8) unsigned NOT NULL,  
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',  
  PRIMARY KEY (`id`)
)ENGINE=MyISAM;
EOF;

runquery($sql);
$finish = TRUE;

?>