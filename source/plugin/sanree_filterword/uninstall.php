<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$sql = <<<EOF
pre_filterword_log;
EOF;

runquery($sql);

$finish = TRUE;
?>