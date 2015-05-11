<?php
/*  nds_up_ques  v3.1
 *  Plugin FOR Discuz! X 
 *	WWW.NWDS.CN | NDS.西域数码工作室 
 *  Plugin update 20121212 BY singcee
 */
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$sql = <<<EOF
DROP TABLE IF EXISTS  pre_ques_option ;
DROP TABLE IF EXISTS  pre_ques_result;
DROP TABLE IF EXISTS  pre_ques_topic;
DROP TABLE IF EXISTS  pre_ques_user;
DROP TABLE IF EXISTS  pre_ques_countlog;
EOF;

runquery($sql);
$finish = TRUE;
?>