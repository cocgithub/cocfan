<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
$developer=array(0 => array('cznnw_com','3932'));
echo '<link rel="stylesheet" href="http://addon.discuz.com/resource/common.css" type="text/css" media="all" />';
foreach($developer as $dev){
	$url='http://addon.discuz.com/?@'.$dev[1].'.developer';
	$str = file_data($url);
	$ratesv=str_cut($str,'<p class="mbm">','</p>');
	$author=str_cut($str,'<h1 class="xs3 mbn">','</h1>');
	$content='<table class="tb tb2 "><tr><th colspan="15" class="partition">'.$author.'&nbsp;&nbsp;&nbsp;'.$ratesv.'</th></tr></table>';
	$content = $content . str_cut($str,'<div class="mtm mbw">','</div>
</div>');
	$content = str_replace('src="resource','src="http://addon.discuz.com/resource',$content);
	$content=iconv("GBK",$_G['charset'],$content);
	$content.='<script>function getMemo(obj, id) {
	var baseobj = $(\'base_\' + id);
	var memoobj = $(\'memo_\' + id);
	baseobj.style.display = \'none\';
	memoobj.style.display = \'\';
	if(!obj.onmouseout) {
		obj.onmouseout = function () {
			baseobj.style.display = \'\';
			memoobj.style.display = \'none\';
		}
	}
}</script>';
	echo $content;
}
function str_cut($str, $pre, $end) {
	$pos_pre = strpos($str, $pre) + strlen($pre);
	$str_end = substr($str, $pos_pre);
	$pos_end = strpos($str_end, $end);
	return substr($str, $pos_pre, $pos_end);
}
function file_data($url) {
        for ($i = 0; $i < 3; $i++) {
            $data = file_get_contents($url);
            if ($data)
                return $data;
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        @ $data = curl_exec($ch);
        curl_close($ch);
        return $data;
}
?>