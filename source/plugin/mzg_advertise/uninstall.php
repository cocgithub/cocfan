<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
require_once DISCUZ_ROOT.'./source/discuz_version.php';
$sql=<<<EOF
DROP TABLE `pre_plugin_advertise`, `pre_plugin_advertise_log`;
DELETE FROM `pre_common_cron` WHERE `pre_common_cron`.`filename` = 'mzg_advertise_check.php';
EOF;
runquery($sql);
$finish = TRUE;
?>