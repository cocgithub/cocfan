<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}



class plugin_yhty_adtz{

	function global_header(){
		global $_G;
		
		$xxjq = $_G['cache']['plugin']['yhty_adtz']['jq'];
		if($xxjq == 1){
    	$rt=$rt.'<script src="source/plugin/yhty_adtz/jquery.min.js" type="text/javascript"></script> <script>var jq=$.noConflict();</script>';
      }
      

    return $rt;
  }
  
	function global_footerlink(){
		global $_G;

		$sl = $_G['cache']['plugin']['yhty_adtz']['sl'];
		if($sl <= 2){
			$sl = "";
		}else{
			$sl = "LIMIT ".$sl;
		}
		

		//utf-8转码函数
 function ic($text) {
 	global $_G;

 	if($_G['charset'] == 'utf-8'){
 		return $text;
 	}else{
    $serial_str = iconv('utf-8',$_G['charset'],$text);
    return $serial_str;
  }
}

		$sqtb = $_G['config']['db']['1']['tablepre'];
		$ct = $_G['charset'];
		$rowi = 0;
		$result = mysql_query("SELECT * FROM ".$sqtb."forum_announcement WHERE type=0 ORDER BY id DESC ".$sl);
		while($row = mysql_fetch_array($result))
		{
		 $rowi=$rowi+1;
		 if($rowi == 1){
		 $ggjc=$row["id"];	
     $rowtime=date('Y-m-d H:i:s',$row[starttime]);
     $tex1=$tex1.'<p  class="yhad_div1p"><a id="mtggpa'.$row["id"].'" style="display: block;" class="ggtgbg" href="javascript:;" onclick="mt_ggright('.$row["id"].')" >'.$row["subject"].'</a></p>';
     $tex2=$tex2.'<p class="ccxtitlep1">'.$row["subject"].'</p><p class="ccxtitlep2">'.ic("作者:").'<a style="margin-right: 10px;" href="home.php?mod=space&username='.$row["author"].'">'.$row["author"].'</a>'.$rowtime.'</p> ';
     $tex3=$tex3.nl2br($row["message"]);
		 }else{
		 $tex1=$tex1.'<p class="yhad_div1p"><a style="display: block;" id="mtggpa'.$row["id"].'" href="javascript:;" onclick="mt_ggright('.$row["id"].')" >'.$row["subject"].'</a></p>';
		 }
		}
		$rowi=0;


    $swdd = $_G['cache']['plugin']['yhty_adtz']['swdd'];
		$sww = $_G['cache']['plugin']['yhty_adtz']['sww'];
		$sgd = $_G['cache']['plugin']['yhty_adtz']['sgd'];
		
		

      
      
				if($sww == 1){
			$rt=$rt.'<script>jq("#anc").hide();</script>';
		}
		
		$rt=$rt.'
		<link rel="stylesheet" type="text/css" href="source/plugin/yhty_adtz/css.css" />
		<div id="ccxbg" class="ccxbg"></div>
		<div id="mt_ggdiv" class="yhad_div1">
<div style="position: absolute;left: 171px;">'.$swdd.'</div>
<!-- time --!>
<div style="display: none;" id="sgddiv">'.$ggjc.'</div>
<div id="yhad_div1left" class="yhad_div1left">
'.$tex1.'
</div>
<div id="yhad_div1right" class="thad_div2right yhad_div1right"> 
'.$tex2.'
<div>'.$tex3.'</div> 
</div>
<a style="position: absolute;right: 17px;display: block;top: 0px;" href="javascript:;"  onclick="mt_zkgg()" class="flbc"  title="'.ic("关闭").'"></a>
</div>
';

$sx = $_G['cache']['plugin']['yhty_adtz']['sx'];
if($sx == 1){
$rt=$rt.'
<div href="javascript:;" style="position: fixed;padding: 5px;bottom: 1px;
right: 1px;
background: #fff;
border: 1px solid #0CF;">
<a href="javascript:;" id="mt_gga1" onclick="mt_zkgg()">'.ic("展开公告　").'</a>
';
if($_G['adminid'] == 1){
$rt=$rt.'<a href="admin.php?frames=yes&action=announce&operation=add" target="_blank">'.ic("发布公告　").'</a>';
}

$rt=$rt.'</div>';
}

$rt=$rt.'<script>
var axtime = '.$sgd.';
var zkggi = 0;
var zkgglefti = 0;
var sgddiv = '.$ggjc.';
var newsgddiv = '.$ggjc.';
function mt_zkgg()
{
  if(zkggi == 0){
    jq("#mt_ggdiv").show(500);
    jq("#ccxbg").show(500);
    jq("#mt_gga1").html("'.ic("关闭公告　").'");
    zkggi=1;
  }else{
    zkggi=0;
    jq("#mt_ggdiv").hide(500);
    jq("#ccxbg").hide(500);
    jq("#mt_gga1").html("'.ic("展开公告　").'");
  }
}

function mt_ggright(int){
 jq("#yhad_div1left a").removeClass("ggtgbg");
 jq("#mtggpa"+int).addClass("ggtgbg");
 jq("#yhad_div1right").html("loading...");
 jq("#yhad_div1right").load("plugin.php?id=yhty_adtz:ccz&getid="+int);
}
function timefun(){
 jq("#sgddiv").load("plugin.php?id=yhty_adtz:ccz", function() {
  setTimeout("timefun()",1000*axtime); 
  newsgddiv = jq("#sgddiv").html();
 if(sgddiv >= newsgddiv){

 }else{
  zkggi=1;
  jq("#mt_ggdiv").html("loading");
  jq("#mt_ggdiv").load("plugin.php?id=yhty_adtz:ccc");
  jq("#mt_ggdiv").show(500);
  jq("#mt_gga1").html("'.ic("关闭公告　").'");
  sgddiv = newsgddiv;
 }
});

}
setTimeout("timefun()",1000*axtime); 

function mt_left_hide(int){

  if(zkgglefti == 0){
    jq("#yhad_div1left").show(500);
    zkgglefti=1;
  }else{
    zkgglefti=0;
    jq("#yhad_div1left").hide(500);
  }
  
}



</script>';


  
		return $rt;
	}
  
}
?>