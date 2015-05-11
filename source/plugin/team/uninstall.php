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

DELETE FROM pre_common_cron WHERE `name` = '版主工资发放';
DROP TABLE pre_plugin_monthmoney;

EOF;

runquery($sql);

$finish = TRUE;

?>