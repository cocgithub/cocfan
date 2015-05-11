<?php

/**
 *	(C)2012-2099 Powered by 禄仔(http://addon.cncal.cn) QQ：13505491.
 *      This is NOT a freeware, use is subject to license terms
 *	Date: 2012-9-25 12:00
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$sql = <<<EOF

INSERT INTO pre_common_cron VALUES ('0','1','system','版主工资发放','cron_teamstar_daily.php','1308127620','1309446000','-1','1','0','2');
DROP TABLE IF EXISTS pre_plugin_monthmoney;
CREATE TABLE pre_plugin_monthmoney (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  uid mediumint(8) unsigned NOT NULL,
  username char(15) NOT NULL,
  `month` int(3) NOT NULL,
  alldata smallint(6) NOT NULL,
  monthpost smallint(6) NOT NULL,
  modactions smallint(6) NOT NULL,
  digestposts smallint(6) NOT NULL,
  thismonth smallint(6) NOT NULL,
  dateline int(10) NOT NULL,
  PRIMARY KEY (id),
  KEY uid (uid)
) ENGINE=MyISAM;

EOF;

runquery($sql);

$finish = TRUE;
?>