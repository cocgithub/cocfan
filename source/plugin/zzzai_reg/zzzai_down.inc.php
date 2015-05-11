<?php
if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

if($_G['adminid'] != 1) showmessage('quickclear_noperm');

loadcache('zzzai_reg_log');
$str = $_G['cache']['zzzai_reg_log'];
$str_arr = explode('|', substr($str, 0, -1));
krsort($str_arr);
header("Content-type:text/csv"); 
header("Content-Disposition:attachment;filename=zzzai_reg_log.csv"); 
header('Cache-Control:must-revalidate,post-check=0,pre-check=0'); 
header('Expires:0'); 
header('Pragma:public'); 
foreach($str_arr as $str) {
    list($ip, $time) = explode(',', $str);
    if($ip && $time) echo gmdate('Y-m-d H:i:s', $time).",".$ip."\n";
}
exit;