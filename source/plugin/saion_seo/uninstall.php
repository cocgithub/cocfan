<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$sql = <<<EOF
DROP TABLE IF EXISTS `pre_saion_seo_visit`;
EOF;

runquery($sql);

$finish = TRUE;

?>