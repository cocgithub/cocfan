<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$surl='source/plugin/yhty_bili/'; //插件路径
$secret = $_G['cache']['plugin']['yhty_bili']['SECRET']; //secret
$key = $_G['cache']['plugin']['yhty_bili']['KEY']; //key
$ue = $_G['cache']['plugin']['yhty_bili']['ue']; //key
$ct = $_G['charset']; //获取编码
$av = $_GET['av']; //视频编号
$page = $_GET[page];
$pagesize= $_G['cache']['plugin']['yhty_bili']['pagesize'];
$wd= $_G['cache']['plugin']['yhty_bili']['pagew'];
$hd= $_G['cache']['plugin']['yhty_bili']['pageh'];
$mt= $_G['cache']['plugin']['yhty_bili']['paget'];
$ml= $_G['cache']['plugin']['yhty_bili']['pagel'];
$mr= $_G['cache']['plugin']['yhty_bili']['pager'];
$jskh= $_G['cache']['plugin']['yhty_bili']['pagejsk'];
$ixw= $_G['cache']['plugin']['yhty_bili']['ixw'];
$ixh= $_G['cache']['plugin']['yhty_bili']['ixh'];
$ixmrl= $_G['cache']['plugin']['yhty_bili']['ixmrl'];
$ixmrr= $_G['cache']['plugin']['yhty_bili']['ixmrr'];
$ixmrt= $_G['cache']['plugin']['yhty_bili']['ixmrt'];
$ixmrb= $_G['cache']['plugin']['yhty_bili']['ixmrb'];
$idxsl= $_G['cache']['plugin']['yhty_bili']['idxsl'];
if($page == ''){
$page = 1;
}
if($pagesize > 100){
	$pagesize=100;
}

//api函数
function get_sign($params, $key) {
  $_data = array();
  ksort($params);
  reset($params);
  foreach ($params as $k => $v) {
  // rawurlencode 返回的转义数字必须为大写( 如%2F )
  $_data[] = $k . '=' . rawurlencode($v);
  }
  $_sign = implode('&', $_data);
  return array(
    'sign' => strtolower(md5($_sign . $key)),
    'params' => $_sign,
  );
 }
 
//utf-8转码函数
 function ic($text) {
 	global $ct;

 	if($ct == 'utf-8'){
 		return $text;
 	}else{
    $serial_str = iconv('utf-8',$ct,$text);
    return $serial_str;
  }
}
 function tp($type,$out,$title,$entitle){
 	global $idxsl;
 	$x=0; 
 	echo' <div><div class="sort"><i><a>'.ic($title).'<b>'.$entitle.'</b></a></i></div>';
 	while($x < $idxsl) {
  echo '
 
  <div class="v" >
	<a href="plugin.php?id=yhty_bili:vid&av='.ic($out[$type][$x][aid]).'" target="_blank">
		<div class="medal"></div>
		<div class="original"></div>
		<div class="preview">
			<img src="'.ic($out[$type][$x][pic]).'"></div>
			<div class="t">'.ic($out[$type][$x][title]).'</div>
	</a>
</div>
';

  $x++;
}
echo '<div style="clear: both;"></div></div>'; 
}

function mb_unserialize($array) {
	global $ct;
	if($ct == 'utf-8'){
	  return $array;
	}else{
    $serial_str = iconv('utf-8',$ct,serialize($array));
    $serial_str= preg_replace('!s:(\d+):"(.*?)";!se', "'s:'.strlen('$2').':\"$2\";'", $serial_str );
    $serial_str= str_replace("\r", "", $serial_str);     
    return unserialize($serial_str);
  }
    
    }

//如果编码不是utf8 转换到 使用编码
//var tid='
$arr = array("index"=>"首页","douga"=>"动画","music"=>"音乐 - 舞蹈","game"=>"游戏","kxjs"=>"科学 - 技术","yl"=>"娱乐","ysj"=>"影视剧","bangumi"=>"动画番剧");
$arr = mb_unserialize($arr);
?>
