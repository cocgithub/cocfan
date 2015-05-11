<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
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


  		$sl = $_G['cache']['plugin']['yhty_adtz']['sl'];
		if($sl <= 2){
			$sl = "";
		}else{
			$sl = "LIMIT ".$sl;
		}
		
    $sqtb = $_G['config']['db']['1']['tablepre'];
    $swdd = $_G['cache']['plugin']['yhty_adtz']['swdd'];
		$rowi = 0;
		$result = mysql_query("SELECT * FROM ".$sqtb."forum_announcement WHERE type=0 ORDER BY id DESC ".$sl);
		while($row = mysql_fetch_array($result))
		{
		 $rowi=$rowi+1;
		 if($rowi == 1){
		 $ggjc=$row["id"];	
     $rowtime=date('Y-m-d H:i:s',$row[starttime]);
     $tex1=$tex1.'<p  class="yhad_div1p"><a id="mtggpa'.$row["id"].'" style="display: block;" class="ggtgbg" href="javascript:;" onclick="mt_ggright('.$row["id"].')" >New.'.$row["subject"].'</a></p>';
     $tex2=$tex2.'<p class="ccxtitlep1">'.$row["subject"].'</p><p class="ccxtitlep2">'.ic("作者:").'<a style="margin-right: 10px;" href="home.php?mod=space&username='.$row["author"].'">'.$row["author"].'</a>'.$rowtime.'</p> ';
     $tex3=$tex3.nl2br($row["message"]);
		 }else{
		 $tex1=$tex1.'<p class="yhad_div1p"><a style="display: block;" id="mtggpa'.$row["id"].'" href="javascript:;" onclick="mt_ggright('.$row["id"].')" >'.$row["subject"].'</a></p>';
		 }
		}
		$rowi=0;
echo'
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
<a style="position: absolute;right: 17px;display: block;top: 0px;" href="javascript:;"  onclick="mt_zkgg()" class="flbc"  title="'.ic("关闭:").'"></a>
';
?>
