<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
    exit('Access Denied');
}
loadcache('zzzai_reg_log');
$str = $_G['cache']['zzzai_reg_log'];
$str_arr = explode('|', substr($str, 0, -1));
if(count($str_arr) > 0) {
    list($ip, $time) = explode(',', current($str_arr));
} else {
    $time = 0;
}

$starttime = $time ? dgmdate($time, 'Y-m-d H:i:s') : lang('plugin/zzzai_reg', 'starttime');

$top_arr = array();
foreach($str_arr as $k=>$str) {
    if($str) {
        list($ip, $time) = explode(',', $str);
        if($ip) {
            $top_arr[$ip] = (isset($top_arr[$ip]) ? $top_arr[$ip] : 0) + 1;
        }
    } else {
        unset($str_arr[$k]);
    }
}

$totalnum = count($str_arr);

arsort($top_arr);
$top_arr = array_splice($top_arr, 0, 100);
krsort($str_arr);
$str_arr = array_splice($str_arr, 0, 100);
$str_tr = $top_tr = '';
foreach($str_arr as $str) {
    if($str) {
        list($ip, $time) = explode(',', $str);
        $str_tr .= '<tr class="hover"><td>'.dgmdate($time, 'Y-m-d H:i:s').'</td><td>'.$ip.'</td></tr>'."\r\n";
    }
}
foreach($top_arr as $ip=>$num) {
    $top_tr .= '<tr class="hover"><td>'.$ip.'</td><td>'.$num.'</td></tr>'."\r\n";
}
showtableheader('', '', '');
showtitle(lang('plugin/zzzai_reg', 'description'));
showtablerow('', array('class="tipsblock"'), array("<ul>
    <li>".lang('plugin/zzzai_reg', 'desc1')."</li>
    <li>".lang('plugin/zzzai_reg', 'desc2', array('starttime'=>$starttime, 'num'=>$totalnum))."</li>
    <li>".lang('plugin/zzzai_reg', 'desc3')."</li></ul>"));
showtitle(lang('plugin/zzzai_reg', 'record'));
showtablerow('', array(), array('
    <table style="float: left; width: 300px; clear: none;" class="tb tb2"><tr class="header"><th>'.lang('plugin/zzzai_reg', 'time').'</th><th>'.lang('plugin/zzzai_reg', 'ip').'</th></tr>'.$str_tr.'
    <tr><td colspan="2"></td></tr></table>
    <table style="margin-left: 350px; width: 300px; clear: none;" class="tb tb2"><tr class="header"><th>'.lang('plugin/zzzai_reg', 'ip').'</th><th>'.lang('plugin/zzzai_reg', 'count').'</th></tr>'.$top_tr.'
    <tr><td colspan="2"></td></tr></table>
    '));
showtablefooter();

