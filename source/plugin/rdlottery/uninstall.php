<?php
if(!defined('IN_DISCUZ')) {
	exit('Access denied');
}
$sql = <<<EOT
DROP TABLE IF EXISTS pre_plugin_rdlottery;
DROP TABLE IF EXISTS pre_plugin_rdlotteryapply;
DROP TABLE IF EXISTS pre_plugin_rdlottery_message;
EOT;

runquery($sql);

$finish = true;
?>
