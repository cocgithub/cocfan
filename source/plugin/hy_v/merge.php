<?php
/**
 * hy_v ���������
 * ԭ����Ȩ by ����
 */
$url = trim($_GET['url']);
$arr = explode('|', $url);
$str = '<m starttype="0" label="" type="" bytes="" duration="" bg_video="" lrc="">';
if(is_array($arr)){
	foreach($arr as $val){
		$item = explode(',', $val);
		$str .= '<u bytes="' . $item[1] . '" duration="' . $item[0] . '" src="' . $item[2] . '?start={start_seconds}" />';
	}
}
$str .= '</m>';
echo $str;